<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}
	public function client()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('client_view')))
		{
			$this->data['page_title'] = 'Clients - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$system_users = $this->ion_auth->users(array(4))->result();
			foreach ($system_users as $system_user) {
				if(isset($system_user->saas_id) && $this->session->userdata('saas_id') == $system_user->saas_id){
				$tempRow['id'] = $system_user->user_id;
				$tempRow['email'] = $system_user->email;
				$tempRow['active'] = $system_user->active;
				$tempRow['first_name'] = $system_user->first_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['phone'] = $system_user->phone!=0?$system_user->phone:'No Number';
				$tempRow['company'] = $system_user->company;
				$tempRow['profile'] = !empty($system_user->profile)?base_url(UPLOAD_PROFILE.''.$system_user->profile):'';
				$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
				$group = $this->ion_auth->get_users_groups($system_user->user_id)->result();
				$tempRow['role'] = ucfirst($group[0]->name);
				$tempRow['group_id'] = $group[0]->id;
				$tempRow['projects_count'] = get_count('id','projects','client_id='.$system_user->user_id);
				$rows[] = $tempRow;
				}
			}
			$this->data['system_users'] = isset($rows)?$rows:'';
			$this->data['user_groups'] = $this->ion_auth->groups()->result();
			$this->load->view('clients',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	public function index()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('user_view') || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Users - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			if($this->ion_auth->in_group(3)){
				$system_users = $this->ion_auth->users(array(3))->result();
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
					$tempRow['company'] = $system_user->company;
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
			$this->data['system_users'] = $rows;
			$this->data['user_groups'] = $this->ion_auth->groups(array(1,2))->result();
			if($this->ion_auth->in_group(3)){
				$this->load->view('saas-admins',$this->data);
			}else{
				$this->load->view('users',$this->data);
			}
			
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function get_saas_users()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			return $this->users_model->get_saas_users();
		}else{
			return '';
		}
	}

	public function saas()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Users - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['plans'] = $this->plans_model->get_plans();
			$this->load->view('saas-users',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function profile()
	{	
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Profile - '.company_name();
			$this->data['current_user'] = $profile_user = $this->ion_auth->user()->row();
			
			$tempRow['id'] = $profile_user->user_id;
			$tempRow['email'] = $profile_user->email;
			$tempRow['active'] = $profile_user->active;
			$tempRow['first_name'] = $profile_user->first_name;
			$tempRow['last_name'] = $profile_user->last_name;
			$tempRow['phone'] = $profile_user->phone!=0?$profile_user->phone:'';
			$tempRow['company'] = $profile_user->company;
			$tempRow['profile'] = !empty($profile_user->profile)?$profile_user->profile:'';
			$tempRow['short_name'] = mb_substr($profile_user->first_name, 0, 1, "utf-8").''.mb_substr($profile_user->last_name, 0, 1, "utf-8");
			$group = $this->ion_auth->get_users_groups($profile_user->user_id)->result();
			$tempRow['role'] = ucfirst($group[0]->name);
			$tempRow['group_id'] = $group[0]->id;
			if($this->ion_auth->in_group(4)){
				$tempRow['projects_count'] = get_count('id','projects','client_id='.$profile_user->user_id);
			}else{
				$tempRow['projects_count'] = get_count('id','project_users','user_id='.$profile_user->user_id);
			}

			$tempRow['tasks_count'] = get_count('id','task_users','user_id='.$profile_user->user_id);
        		
			$this->data['profile_user'] = $tempRow;
			$this->data['user_groups'] = $this->ion_auth->groups(array(1,2))->result();
			$this->load->view('profile',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function ajax_get_user_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$system_user = $this->ion_auth->user($id)->row();
			if(!empty($system_user)){
				$tempRow['id'] = $system_user->id;
				$tempRow['profile'] = $system_user->profile;
				$tempRow['first_name'] = $system_user->first_name;
				$tempRow['last_name'] = $system_user->last_name;
				$tempRow['company'] = $system_user->company;
				$tempRow['short_name'] = mb_substr($system_user->first_name, 0, 1, "utf-8").''.mb_substr($system_user->last_name, 0, 1, "utf-8");
				$tempRow['phone'] = $system_user->phone;
				$tempRow['active'] = $system_user->active;
				$current_plan = get_current_plan($system_user->saas_id);
				if($current_plan){
					$tempRow['current_plan_expiry'] = format_date($current_plan['end_date'],system_date_format());
					$tempRow['current_plan_id'] = $current_plan['plan_id'];
				}
				$group = $this->ion_auth->get_users_groups($system_user->id)->result();
				$tempRow['role'] = ucfirst($group[0]->name);
				$tempRow['group_id'] = $group[0]->id;
				$this->data['error'] = false;
				$this->data['data'] = $tempRow;
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







