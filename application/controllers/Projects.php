<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function delete_project($project_id='')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_delete')))
		{

			if(empty($project_id)){
				$project_id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($project_id) && $this->projects_model->delete_project_files('',$project_id) &&
			$this->projects_model->delete_project_users($project_id) &&
			$this->projects_model->delete_project($project_id)){

				$this->session->set_flashdata('message', 'Project deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = "Project deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_task($task_id='')
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_delete')))
		{

			if(empty($task_id)){
				$task_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if(!empty($task_id) && $this->projects_model->delete_task_files('',$task_id) &&
			$this->projects_model->delete_task_comment('','','task_comment',$task_id)  &&
			$this->projects_model->delete_task_users($task_id)  &&
			$this->projects_model->delete_task($task_id)){

				$this->session->set_flashdata('message', 'Task deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = "Task deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function create_comment()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('comment_task_id', 'Task ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			
			if($this->input->post('is_comment') == 'true'){
				$this->form_validation->set_rules('message', 'Message', 'trim|required|strip_tags|xss_clean');
			}

			if($this->input->post('is_attachment') == 'true'){
				if (empty($_FILES['attachment']['name'])){
					$this->form_validation->set_rules('attachment', 'Attachment', 'required');
				}
			}

			if($this->form_validation->run() == TRUE){

				if (!empty($_FILES['attachment']['name'])){
					$upload_path = 'assets/uploads/tasks/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
	
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = file_upload_format();
					$config['overwrite']             = false;
					$config['max_size']             = 10000;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$this->load->library('upload', $config);
					$full_logo = '';
					if ($this->upload->do_upload('attachment')){
						$data = array(
							'type' => 'task',
							'type_id' => $this->input->post('comment_task_id'),
							'user_id' => $this->session->userdata('user_id'),
							'file_name' => $this->upload->data('file_name'),
							'file_type' => $this->upload->data('file_ext'),		
							'file_size' => $this->upload->data('file_size'),		
						);

						if($this->projects_model->upload_files($data)){
							$this->data['error'] = false;
							$this->data['message'] = 'Comment created successfully.';
							echo json_encode($this->data); 
						}else{
							$this->data['error'] = true;
							$this->data['message'] = "Some Error occured. Please Try again later.";
							echo json_encode($this->data);
						}

					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				if($this->input->post('is_comment') == 'true'){
					$data = array(
						'type' => 'task_comment',
						'from_id' => $this->session->userdata('user_id'),
						'to_id' => $this->input->post('comment_task_id'),
						'message' => $this->input->post('message'),		
					);

					if($this->projects_model->create_comment($data)){
						$this->data['error'] = false;
						$this->data['message'] = 'Comment created successfully.';
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = "Some Error occured. Please Try again later.";
						echo json_encode($this->data);
					}
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
		
	}

	public function task_status_update($task_id = '', $new_status = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(!$task_id && !$new_status){

				$this->form_validation->set_rules('id', 'Task ID', 'trim|required|strip_tags|xss_clean|is_numeric');
				$this->form_validation->set_rules('status', 'New status', 'trim|required|strip_tags|xss_clean|is_numeric');
			
				if($this->form_validation->run() == FALSE){
					$this->data['error'] = true;
					$this->data['message'] = validation_errors();
					echo json_encode($this->data);
					return false;
				}

				$task_id = $this->input->post('id');
				$new_status = $this->input->post('status');
			}

			if($this->projects_model->task_status_update($task_id, $new_status)){
				$this->data['error'] = false;
				$this->data['message'] = "Status updated successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_task_users($task_id = '',$user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if($this->projects_model->delete_task_users($task_id, $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = "User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_task_users($user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if($this->projects_model->delete_project_users('', $user_id) && delete_task_users('', $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = "User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_users($project_id = '',$user_id = '')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($user_id)){
				$user_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}

			if($this->projects_model->delete_project_users($project_id, $user_id)){
				$this->data['error'] = false;
				$this->data['message'] = "User deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_task_files($file_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($file_id)){
				$file_id = $this->uri->segment(3);
				
			}
			if($this->projects_model->delete_task_files($file_id)){
				$this->data['error'] = false;
				$this->data['message'] = "File deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_project_files($file_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($file_id)){
				$file_id = $this->uri->segment(3);
				
			}
			if($this->projects_model->delete_project_files($file_id)){
				$this->data['error'] = false;
				$this->data['message'] = "File deleted successfully.";
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = "Access Denied";
			echo json_encode($this->data);
		}
	}

	public function upload_files($project_id)
	{
		$project_id = !empty($project_id)?$project_id:$this->uri->segment(3);
		if ($this->ion_auth->logged_in() && $project_id)
		{
			$upload_path = 'assets/uploads/projects/';
			if(!is_dir($upload_path)){
				mkdir($upload_path,0775,true);
			}

			$config['upload_path']          = $upload_path;
			$config['allowed_types']        = file_upload_format();
			$config['overwrite']             = false;
			$config['max_size']             = 10000;
			$config['max_width']            = 0;
			$config['max_height']           = 0;
			$this->load->library('upload', $config);
			if (!empty($_FILES['file']['name'])){
				if ($this->upload->do_upload('file')){
					$file_data = $this->upload->data();
					$data = array(
						'type' => 'project',
						'type_id' => $project_id,
						'user_id' => $this->session->userdata('user_id'),
						'file_name' => $file_data['file_name'],
						'file_type' => $file_data['file_ext'],		
						'file_size' => $file_data['file_size'],		
					);
					$this->projects_model->upload_files($data);
				}else{
					return false;
				}
			}
			return false;
		}else{
			return false;
		}
	}

	public function get_tasks_files($task_id = '')
	{
		if ($this->ion_auth->logged_in())
		{	
			$task_id = (empty($task_id) && isset($_GET['task_id']) && !empty($_GET['task_id']))?$_GET['task_id']:'';
			$files = $this->projects_model->get_tasks_files($task_id);
			if($files){
				foreach($files as $key => $file){
					$temp[$key] = $file;
					$temp[$key]['file_size'] = formatBytes($file['file_size']);

					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url('assets/uploads/tasks/'.$file['file_name']).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
						<a href="'.base_url('projects/delete-task-files/'.$file['id']).'" data-delete="'.base_url('projects/delete-task-files/'.$file['id']).'" class="btn btn-icon btn-sm btn-danger delete_files" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></a></span>';
					}else{
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url('assets/uploads/tasks/'.$file['file_name']).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>';
					}

				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_project_files($project_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$files = $this->projects_model->get_project_files($project_id);
			if($files){
				foreach($files as $key => $file){
					$temp[$key] = $file;
					$temp[$key]['file_size'] = formatBytes($file['file_size']);
					
					if($this->ion_auth->is_admin()){
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url('assets/uploads/projects/'.$file['file_name']).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>
						<a href="'.base_url('projects/delete-project-files/'.$file['id']).'" data-delete="'.base_url('projects/delete-project-files/'.$file['id']).'" class="btn btn-icon btn-sm btn-danger delete_files" data-toggle="tooltip" title="Delete"><i class="fas fa-trash"></i></a></span>';
					}else{
						$temp[$key]['action'] = '<span class="d-flex"><a download="'.$file['file_name'].'" href="'.base_url('assets/uploads/projects/'.$file['file_name']).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="Download"><i class="fas fa-download"></i></a>';
					}

				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_project_users($project_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$users = $this->projects_model->get_project_users($project_id);
			if($users){
				foreach($users as $key => $user){
					$temp[$key] = $user;
					$temp[$key]['full_name'] = $user['first_name'].' '.$user['last_name'];
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function detail()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{

			$this->data['page_title'] = 'Projects Detail - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();
			
			$this->data['project_status'] = project_status();
			$this->data['task_status'] = task_status();
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3))){

				if($this->ion_auth->in_group(4) && !is_my_project($this->uri->segment(3))){
					redirect('projects', 'refresh');
				}

				if($this->ion_auth->is_admin()){
					$this->data['project'] = $this->projects_model->get_projects('',$this->uri->segment(3));
				}else{
					$this->data['project'] = $this->projects_model->get_projects($this->session->userdata('user_id'),$this->uri->segment(3));
				}
			}else{
				redirect('projects', 'refresh');
			}
			$this->load->view('projects-detail',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{
			$this->data['page_title'] = 'Projects - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			
			$this->data['system_users'] = $this->ion_auth->users(array(1,2))->result();
			$this->data['system_clients'] = $this->ion_auth->users(4)->result();

			$this->data['project_status'] = project_status();

			$config = array();
			$config["base_url"] = base_url('projects');
			$config["total_rows"] = get_count('id','projects','');
			$config["per_page"] = 10;
			$config["uri_segment"] = 2;
			    
            $config['next_link']        = 'Next';
            $config['prev_link']        = 'Previous';
            $config['first_link']       = false;
            $config['last_link']        = false;
            $config['full_tag_open']    = '<nav aria-label="...">
											<ul class="pagination">';
            $config['full_tag_close']   = '</ul>
											</nav>';
			$config['attributes']       = ['class' => 'page-link'];
			
            $config['first_tag_open']   = '<li class="page-item">';
			$config['first_tag_close']  = '</li>';
			
            $config['prev_tag_open']    = '<li class="page-item">';
			$config['prev_tag_close']   = '</li>';
			
            $config['next_tag_open']    = '<li class="page-item">';
			$config['next_tag_close']   = '</li>';
			
            $config['last_tag_open']    = '<li class="page-item">';
			$config['last_tag_close']   = '</li>';
			
            $config['cur_tag_open']     = '<li class="page-item active">
			<a class="page-link" href="#">';
			$config['cur_tag_close']    = '<span class="sr-only">(current)</span></a>
			</li>';
			
            $config['num_tag_open']     = '<li class="page-item">';
            $config['num_tag_close']    = '</li>';
			
			$this->pagination->initialize($config);

			$page = ($this->uri->segment(2) && is_numeric($this->uri->segment(2)))?$this->uri->segment(2):0;
			$this->data["links"] = $this->pagination->create_links();

			if(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status'])){
				$filter = $_GET['status'];
				$filter_type = 'status';
			}elseif(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user'])){
				$filter = $_GET['user'];
				$filter_type = 'user';
			}elseif(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client'])){
				$filter = $_GET['client'];
				$filter_type = 'client';
			}else{
				$filter = (isset($_GET['sortby']) && !empty($_GET['sortby']) && ($_GET['sortby'] == 'latest' || $_GET['sortby'] == 'old' || $_GET['sortby'] == 'name'))?$_GET['sortby']:'latest';
				$filter_type = 'sortby';
			}
			
			if($this->ion_auth->is_admin()){
				$this->data['projects'] = $this->projects_model->get_projects('','',$config["per_page"], $page, $filter_type, $filter);
			}else{
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'),'',$config["per_page"], $page, $filter_type, $filter);
			}
			$this->load->view('projects',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_projects()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('project_id', 'Project ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->projects_model->get_projects('',$this->input->post('project_id'));
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data);
		}
	}

	public function edit_project()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_edit')))
		{
			$this->form_validation->set_rules('title', 'Project Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('client', 'Client', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('update_id', 'Project ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$project_id = $this->input->post('update_id');
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");
				$ending_date = format_date($this->input->post('ending_date'),"Y-m-d");

				if($ending_date < $starting_date){
					$response['error'] = true;
					$response['message'] = 'Ending date should not be less then starting date.';
					echo json_encode($response);
					return false;
				}

				$data = array(
					'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'starting_date' => $starting_date,
					'ending_date' => $ending_date,
					'status' => $this->input->post('status'),		
				);
				if($this->projects_model->edit_project($project_id,$data)){
					if($this->input->post('users')){
						$this->projects_model->delete_project_users($project_id);
						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'project_id' => $project_id,	
							);
							$this->projects_model->create_project_users($user_data);
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'project_id' => $project_id,	
						);
						$this->projects_model->create_project_users($user_data);
					}
					$this->session->set_flashdata('message', 'Project updated successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Project updated successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
		
	}

	public function create_project()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('project_create')))
		{
			if(!my_plan_features('projects')){ 
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

			$this->form_validation->set_rules('title', 'Project Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('client', 'Client', 'trim|strip_tags|xss_clean');
			
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$starting_date = format_date($this->input->post('starting_date'),"Y-m-d");
				$ending_date = format_date($this->input->post('ending_date'),"Y-m-d");

				if($ending_date < $starting_date){
					$response['error'] = true;
					$response['message'] = 'Ending date should not be less then starting date.';
					echo json_encode($response);
					return false;
				}

				$data = array(
					'client_id' => $this->input->post('client')?$this->input->post('client'):NULL,
					'saas_id' => $this->session->userdata('saas_id'),
					'created_by' => $this->session->userdata('user_id'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'starting_date' => $starting_date,
					'ending_date' => $ending_date,
					'status' => $this->input->post('status'),		
				);
				$project_id = $this->projects_model->create_project($data);
				
				if($project_id){
					if($this->input->post('users')){
						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'project_id' => $project_id,	
							);
							$this->projects_model->create_project_users($user_data);
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'project_id' => $project_id,	
						);
						$this->projects_model->create_project_users($user_data);
					}
					$this->session->set_flashdata('message', 'Project created successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Project created successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
		
	}
	
	public function tasks()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('project_view')))
		{
			
			if($this->uri->segment(3) && (!is_numeric($this->uri->segment(3)) || !is_my_project($this->uri->segment(3)))){
				redirect('projects/tasks', 'refresh');
			}

			$this->data['page_title'] = 'Tasks - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['project_id'] = $project_id = $this->uri->segment(3);

			if($project_id && $this->ion_auth->in_group(4)){
				if(!is_my_project($project_id)){
					redirect('projects/tasks', 'refresh');
				}
			}

			$this->data['projecr_users'] = $this->projects_model->get_project_users($project_id);
			$this->data['task_status'] = task_status();
			$this->data['task_priorities'] = priorities();

			if($this->ion_auth->is_admin()){
				$this->data['tasks'] = $this->projects_model->get_tasks('','',$project_id);
				$this->data['projects'] = $this->projects_model->get_projects();
			}else{
				$this->data['tasks'] = $this->projects_model->get_tasks($this->session->userdata('user_id'),'',$project_id);
				$this->data['projects'] = $this->projects_model->get_projects($this->session->userdata('user_id'));
			}

			$this->load->view('tasks',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_tasks()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('task_id', 'Task ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->projects_model->get_tasks('',$this->input->post('task_id'));
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data);
		}
	}

	public function get_comments()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['error'] = false;
			$this->data['data'] = $this->projects_model->get_comments('task_comment','',$this->input->post('to_id'));
			$this->data['message'] = 'Successful';
			echo json_encode($this->data);
		}else{
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data);
		}
	}

	public function create_task()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_create')))
		{
			if(!my_plan_features('tasks')){ 
				$this->data['error'] = true;
				$this->data['message'] = "Some Error occured. Please Try again later.";
				echo json_encode($this->data);
			}

			$this->form_validation->set_rules('project_id', 'ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'Task Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('priority', 'Priority', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$due_date = format_date($this->input->post('due_date'),"Y-m-d");
				
				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),
					'project_id' => $this->input->post('project_id'),
					'created_by' => $this->session->userdata('user_id'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'due_date' => $due_date,
					'priority' => $this->input->post('priority'),
					'status' => $this->input->post('status'),		
				);
				$task_id = $this->projects_model->create_task($data);
				
				if($task_id){
					if($this->input->post('users')){
						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'task_id' => $task_id,	
							);
							$this->projects_model->create_task_users($user_data);
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'task_id' => $task_id,	
						);
						$this->projects_model->create_task_users($user_data);
					}
					$this->session->set_flashdata('message', 'Task created successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Task created successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
		
	}

	public function edit_task()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('task_edit')))
		{
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|is_numeric|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'Task Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'Description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('priority', 'Priority', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Status', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$task_id = $this->input->post('update_id');
				$due_date = format_date($this->input->post('due_date'),"Y-m-d");
				
				$data = array(
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'due_date' => $due_date,
					'priority' => $this->input->post('priority'),
					'status' => $this->input->post('status'),		
				);
				
				if($this->projects_model->edit_task($task_id,$data)){
					if($this->input->post('users')){
						$this->projects_model->delete_task_users($task_id);
						foreach($this->input->post('users') as $user_id){
							$user_data = array(
								'user_id' => $user_id,
								'task_id' => $task_id,	
							);
							$this->projects_model->create_task_users($user_data);
						}
					}else{
						$user_data = array(
							'user_id' => $this->session->userdata('user_id'),
							'task_id' => $task_id,	
						);
						$this->projects_model->create_task_users($user_data);
					}
					$this->session->set_flashdata('message', 'Task updated successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Task updated successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
		
	}

}







