<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plans extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function orders()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Subscription Orders - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('orders',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function transactions()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Transactions - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('transactions',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	public function get_transactions($transaction_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$transactions = $this->plans_model->get_transactions($transaction_id);
			if($transactions){
				foreach($transactions as $key => $transaction){
					$temp[$key] = $transaction;
					$temp[$key]['user'] = $transaction['first_name']." ".$transaction['last_name'];
					$temp[$key]['created'] = format_date($transaction['created'],system_date_format());
					$temp[$key]['status'] = $transaction['status']==1?'<div class="badge badge-success">Completed</div>':'<div class="badge badge-danger">Rejected</div>';
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_orders($order_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$orders = $this->plans_model->get_orders($order_id);
			if($orders){
				foreach($orders as $key => $order){
					$temp[$key] = $order;
					$temp[$key]['user'] = $order['first_name']." ".$order['last_name'];
					$temp[$key]['created'] = format_date($order['created'],system_date_format());
					$temp[$key]['status'] = $order['status']==1?'<div class="badge badge-success">Completed</div>':'<div class="badge badge-danger">Rejected</div>';
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function order_completed()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('status', 'Status', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');

			$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
			if($this->form_validation->run() == TRUE && $plan){
				if($this->input->post('amount') > 0){
					$transaction_data = array(
						'saas_id' => $this->session->userdata('saas_id'),			
						'amount' => $this->input->post('amount'),		
						'status' => $this->input->post('status')?$this->input->post('status'):0,		
					);

					$transaction_id = $this->plans_model->create_transaction($transaction_data);

					$order_data = array(
						'saas_id' => $this->session->userdata('saas_id'),		
						'plan_id' => $this->input->post('plan_id'),		
						'transaction_id' => $transaction_id,			
					);
					$order_id = $this->plans_model->create_order($order_data);
				}
				
				$dt = strtotime(date("Y-m-d"));
				if($plan[0]['billing_type'] == "Monthly"){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}else{
					$date = date("Y-m-d", strtotime("+1 year", $dt));
				}

				$my_plan = get_current_plan();
				if($my_plan){
					if($my_plan['expired'] == 1){
						if($my_plan['plan_id'] == 1){
							$date = date("Y-m-d", strtotime("+3 days", $dt));
						}else{
							$dt = strtotime($my_plan['end_date']);
							if($plan[0]['billing_type'] == "Monthly"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}else{
								$date = date("Y-m-d", strtotime("+1 year", $dt));
							}
						}
					}
					$users_plans_data = array(
						'plan_id' => $this->input->post('plan_id'),		
						'expired' => 1,		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);
					$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
				}else{
					$users_plans_data = array(
						'saas_id' => $this->session->userdata('saas_id'),	
						'expired' => 1,				
						'plan_id' => $this->input->post('plan_id'),		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);
					$users_plans_id = $this->plans_model->create_users_plans($users_plans_data);
				}
				
				if($users_plans_id){
					$this->session->set_flashdata('message', 'Plan subscribed successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Plan subscribed successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later";
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

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->plans_model->delete($id)){

				$this->plans_model->delete_plan_update_users_plan($id);
				
				$this->session->set_flashdata('message', 'Plan deleted successfully.');
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = "Plan deleted successfully.";
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

	public function validate($plan_id = '')
	{	
		if(empty($plan_id)){
			$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
		}
		
		$plan = $this->plans_model->get_plans($plan_id);

		if(!empty($plan_id) && is_numeric($plan_id) && $plan){
			$this->data['validationError'] = false;
			$this->data['plan'] = $plan;
			$this->data['message'] = "Successfully.";
			echo json_encode($this->data);
		}else{
			$this->data['validationError'] = true;
			$this->data['message'] = "Unsuccessfully.";
			echo json_encode($this->data);
		}
		
	}

	public function index()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Subscription Plans - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['plans'] = $this->plans_model->get_plans();
			$this->load->view('plans',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('update_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('projects', 'Projects', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('tasks', 'Tasks', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users', 'Users', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){

				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'projects' => $this->input->post('projects'),		
					'tasks' => $this->input->post('tasks'),			
					'users' => $this->input->post('users'),		
				);

				if($this->plans_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', 'Plan updated successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Plan updated successfully.';
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

	public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('projects', 'Projects', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('tasks', 'Tasks', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('users', 'Users', 'trim|required|strip_tags|xss_clean|is_numeric');

			if($this->form_validation->run() == TRUE){
				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'projects' => $this->input->post('projects'),		
					'tasks' => $this->input->post('tasks'),		
					'users' => $this->input->post('users'),		
				);

				$plan_id = $this->plans_model->create($data);
				
				if($plan_id){
					$this->session->set_flashdata('message', 'Plan created successfully.');
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = 'Plan created successfully.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later";
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

	public function get_plans($plan_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$plans = $this->plans_model->get_plans($plan_id);
			if($plans){
				foreach($plans as $key => $plan){
					$temp[$key] = $plan;

					$temp[$key]['features'] = '
					<strong>Projects: </strong>'.(($plan["projects"] < 0)?"Unlimited":$plan["projects"]).'<br>
					<strong>Tasks: </strong>'.(($plan["tasks"] < 0)?"Unlimited":$plan["tasks"]).'<br>
					<strong>Users: </strong>'.(($plan["users"] < 0)?"Unlimited":$plan["users"]);
					$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success modal-edit-plan mr-1" data-id="'.$plan["id"].'" data-toggle="tooltip" title="Edit Plan"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_plan" data-id="'.$plan["id"].'" data-toggle="tooltip" title="Delete Plan"><i class="fas fa-trash"></i></a></span>';
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function ajax_get_plan_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$plans = $this->plans_model->get_plans($id);
			if(!empty($plans)){
				$this->data['error'] = false;
				$this->data['data'] = $plans;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'No user found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data);
		}
	}

}







