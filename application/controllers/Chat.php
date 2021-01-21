<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function get_chat()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('opposite_user_id', 'Chat ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){

				$data = $this->chat_model->get_chat($this->session->userdata('user_id'),$this->input->post('opposite_user_id'));

				foreach($data as $key => $task){
					$temp[$key] = $task;
					$temp[$key]['text'] = $task['message'];
					$temp[$key]['position'] = $this->session->userdata('user_id') == $task['from_id']?'right':'left';
				}
				$Chat = $temp;

				$this->data['error'] = false;
				$this->data['data'] = $Chat;
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

	public function create()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('message', 'Message', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('to_id', 'User', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'type' => 'chat',
					'from_id' => $this->session->userdata('user_id'),
					'to_id' => $this->input->post('to_id'),	
					'message' => $this->input->post('message'),	
				);

				$Chat_id = $this->chat_model->create($data);
				
				if($Chat_id){
					$this->data['error'] = false;
					$this->data['message'] = 'Chat created successfully.';
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

	public function index()
	{
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('chat_view')))
		{
			$this->data['page_title'] = 'Chat - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			if(clients_permissions('chat_view')){
				$system_users = $this->ion_auth->users()->result();
			}else{
				$system_users = $this->ion_auth->users(array(1,2))->result();
			}
			
			foreach ($system_users as $system_user) {
				if($this->session->userdata('saas_id') == $system_user->saas_id){
					$tempRow['id'] = $system_user->user_id;
					$tempRow['email'] = $system_user->email;
					$tempRow['active'] = $system_user->active;
					$tempRow['first_name'] = $system_user->first_name;
					$tempRow['last_name'] = $system_user->last_name;
					$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'No Number';
					$tempRow['profile'] = !empty($system_user->profile)?base_url(UPLOAD_PROFILE.''.$system_user->profile):'';
					$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
					$group = $this->ion_auth->get_users_groups($system_user->user_id)->result();
					$tempRow['role'] = ucfirst($group[0]->name);
					$tempRow['group_id'] = $group[0]->id;
					$tempRow['projects_count'] = get_count('id','project_users','user_id='.$system_user->user_id);
					$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$system_user->user_id);
					$rows[] = $tempRow;
				}
			}
			$this->data['chat_users'] = $rows;

			$this->load->view('chat',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
