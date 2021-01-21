<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Todo extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in())
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->todo_model->delete($this->session->userdata('user_id'), $id)){

				$this->session->set_flashdata('message', 'ToDo deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = "ToDo deleted successfully.";
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

	public function update_status()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('id', 'ToDo ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('status', 'Todo Status', 'trim|required|strip_tags|xss_clean');
			if($this->form_validation->run() == TRUE){
				$data = array(	
					'done' => $this->input->post('status'),
				);

				if($this->todo_model->edit($this->input->post('id'), $data)){
					$this->data['error'] = false;
					$this->data['message'] = 'ToDo updated successfully.';
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

	public function edit()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('update_id', 'ToDo ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('todo', 'Todo', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'todo' => $this->input->post('todo'),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),
				);

				if($this->todo_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', 'ToDo updated successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'ToDo updated successfully.';
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

	public function get_todo()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('id', 'ToDo ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if($this->form_validation->run() == TRUE){
				$this->data['error'] = false;
				$this->data['data'] = $this->todo_model->get_todo($this->session->userdata('user_id'),$this->input->post('id'));
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
			$this->form_validation->set_rules('todo', 'ToDo', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('due_date', 'Due Date', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'user_id' => $this->session->userdata('user_id'),
					'todo' => $this->input->post('todo'),	
					'due_date' => format_date($this->input->post('due_date'),"Y-m-d"),	
				);

				$ToDo_id = $this->todo_model->create($data);
				
				if($ToDo_id){
					$this->session->set_flashdata('message', 'ToDo created successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'ToDo created successfully.';
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
		if ($this->ion_auth->logged_in() && !$this->ion_auth->in_group(3) && ($this->ion_auth->is_admin() || permissions('todo_view')))
		{
			if(isset($_GET['filter']) && !empty($_GET['filter'])){
				$filter = $_GET['filter'];
			}else{
				$filter = '';
			}

			$this->data['page_title'] = 'ToDo - '.company_name();
			$this->data['main_page'] = $filter;
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['todo'] = $this->todo_model->get_todo($this->session->userdata('user_id'), '',$filter);
			$this->load->view('todo',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

}
