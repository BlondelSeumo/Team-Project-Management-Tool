<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			$my_plan = get_current_plan();
			if ($my_plan && $my_plan['end_date'] < date('Y-m-d') && $my_plan['expired'] == 1)
			{
				$users_plans_data = array(
					'expired' => 0,			
				);
				$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
			}

			$this->data['page_title'] = 'Dashboard - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['project_status'] = project_status();
			$this->data['task_status'] = task_status();
			
			if($this->ion_auth->in_group(3)){
				$this->data['plans'] = $this->plans_model->get_plans();
				$this->data['transaction_chart'] = $this->plans_model->get_transaction_chart();
				$this->load->view('saas-home',$this->data);
			}else{
				$this->load->view('home',$this->data);
			}

			
		}else{
			redirect('auth', 'refresh');
		}
	}

}
