<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function clear_cache()
	{	
		$cache_path = 'install';
		delete_files($cache_path, true);
		rmdir($cache_path);
		redirect('auth', 'refresh');
	}

	public function index()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('setting_view') || $this->ion_auth->in_group(3)))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'general';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['timezones'] = timezones();
			$this->data['time_formats'] = time_formats();
			$this->data['date_formats'] = date_formats();

			$this->data['company_name'] = company_name();
			$this->data['company_email'] = company_email();
			$this->data['footer_text'] = footer_text();
			$this->data['google_analytics'] = google_analytics();
			$this->data['mysql_timezone'] = mysql_timezone();
			$this->data['php_timezone'] = php_timezone();
			$this->data['date_format'] = system_date_format();
			$this->data['time_format'] = system_time_format();
			$this->data['file_upload_format'] = file_upload_format();
			$this->data['date_format_js'] = system_date_format_js();
			$this->data['time_format_js'] = system_time_format_js();
			$this->data['full_logo'] = full_logo();
			$this->data['half_logo'] = half_logo();
			$this->data['favicon'] = favicon();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function update()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'update';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function payment()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'payment';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['paypal_client_id'] = get_payment_paypal();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_payment_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('paypal_client_id', 'Paypal Client ID', 'required');

			if($this->form_validation->run() == TRUE){
				$data_json = array(
					'paypal_client_id' => $this->input->post('paypal_client_id'),
				);

				$data = array(
					'value' => json_encode($data_json)
				);
				$setting_type = 'payment';
				
				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = 'Payment Setting Saved.';
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

	public function save_update_setting()
	{
		
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{		$get_system_version = get_system_version();
				$upload_path = 'update';
				if(!is_dir($upload_path)){
					mkdir($upload_path,0775,true);
				}

				$config['upload_path']          = $upload_path;
				$config['allowed_types']        = 'zip';
				$config['overwrite']             = true;

				$this->load->library('upload', $config);
				if (!empty($_FILES['update']['name']) && $_FILES['update']['name'] == 'update-v'.($get_system_version+1).'.zip'){

					if ($this->upload->do_upload('update')){
							$update_data = $this->upload->data();

							$zip = new ZipArchive;
							if ($zip->open($update_data['full_path']) === TRUE) 
							{
								if($zip->extractTo($upload_path)){
									$zip->close();
									if(is_dir($upload_path) && is_dir($upload_path.'/files') && file_exists($upload_path."/version.txt") && file_exists($upload_path."/movefiles.php") && file_exists($upload_path.'/files/validate.txt')){
										
										$version = file_get_contents($upload_path."/version.txt");
										$validate = file_get_contents($upload_path.'/files/validate.txt');
										if($version-1 == $get_system_version && $validate == 'hhmsbbhmrs'){
											
											include($upload_path."/movefiles.php");
											if(count($movepaths)>0){
												foreach($movepaths as $line) {
													if(!empty($line)){
														if (!is_dir($line) && !file_exists($line) && !preg_match("/\./i", $line)) {
															mkdir($line, 0777, true);
														}

														if(preg_match("/\./i", $line)){
															copy($upload_path.'/files/'.$line, $line);
														}
													}
												}
											}

											if(file_exists($upload_path."/deletefiles.php")){
												include($upload_path."/deletefiles.php");
													if(count($deletepaths)>0){
													foreach($deletepaths as $line) {
														if(!empty($line)){
															unlink($line);
														}
													}
												}
											}
											
											if(is_dir($upload_path.'/files/application/migrations')){
												$this->load->library('migration');
												$this->migration->latest();
											}

											$data = array(
												'value' => $version
											);

											if($this->settings_model->save_settings('system_version',$data)){
												delete_files($upload_path, true);
												rmdir($upload_path);

												$this->session->set_flashdata('message', 'System updated successfully.');
												$this->session->set_flashdata('message_type', 'success');

												$this->data['error'] = false;
												$this->data['message'] = 'System updated successfully.';
												echo json_encode($this->data); 
											}else{
												$this->data['error'] = true;
												$this->data['message'] = "Some Error occured. Please Try again later.";
												echo json_encode($this->data);
											}

										}else{
											$this->data['error'] = true;
											$this->data['message'] = 'Wrong update file is selected you need version '.($get_system_version+1).' file.';
											echo json_encode($this->data); 
											return false;
										}
										
									}else{
										
										$this->data['error'] = true;
										$this->data['message'] = 'Select valid zip file...';
										echo json_encode($this->data); 
										return false;
									}
								}else{
									$this->data['error'] = true;
									$this->data['message'] = 'Error occured during file extracting. Select valid zip file OR Please Try again later';
									echo json_encode($this->data); 
									return false;
								}
							}else{
								
								$this->data['error'] = true;
								$this->data['message'] = 'Error occured during file uploading. Select valid zip file OR Please Try again later';
								echo json_encode($this->data); 
								return false;
							}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
					
				}else{
					$this->data['error'] = true;
					$this->data['message'] = 'Select valid zip file.';
					echo json_encode($this->data); 
					return false;
				}
		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
	}

	public function user_permissions()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('setting_view')))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'permissions';
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['permissions'] = permissions();
			$this->data['clients_permissions'] = clients_permissions();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function email()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->data['page_title'] = 'Settings - '.company_name();
			$this->data['main_page'] = 'email';
			$this->data['current_user'] = $this->ion_auth->user()->row();

			$this->data['smtp_host'] = smtp_host();
			$this->data['smtp_port'] = smtp_port();
			$this->data['smtp_username'] = smtp_email();
			$this->data['smtp_password'] = smtp_password();
			$this->data['smtp_encryption'] = smtp_encryption();
			$this->load->view('settings',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function save_permissions_setting()
	{
		
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('setting_update')))
		{
				$data_json = array(
					'project_view' => $this->input->post('project_view') != ''?1:0,
					'project_create' => $this->input->post('project_create') != ''?1:0,
					'project_edit' => $this->input->post('project_edit') != ''?1:0,
					'project_delete' => $this->input->post('project_delete') != ''?1:0,
					'task_view' => $this->input->post('task_view') != ''?1:0,
					'task_create' => $this->input->post('task_create') != ''?1:0,
					'task_edit' => $this->input->post('task_edit') != ''?1:0,
					'task_delete' => $this->input->post('task_delete') != ''?1:0,
					'user_view' => $this->input->post('user_view') != ''?1:0,
					'client_view' => $this->input->post('client_view') != ''?1:0,
					'setting_view' => $this->input->post('setting_view') != ''?1:0,
					'setting_update' => $this->input->post('setting_update') != ''?1:0,
					'todo_view' => $this->input->post('todo_view') != ''?1:0,
					'notes_view' => $this->input->post('notes_view') != ''?1:0,
					'chat_view' => $this->input->post('chat_view') != ''?1:0,
				);

				$data = array(
					'value' => json_encode($data_json)
				);
				$setting_type = 'permissions';
				if(!$this->ion_auth->in_group(3)){
					$setting_type = 'permissions_'.$this->session->userdata('saas_id');
				}

				$client_data_json = array(
					'project_view' => $this->input->post('client_project_view') != ''?1:0,
					'project_create' => $this->input->post('client_project_create') != ''?1:0,
					'project_edit' => $this->input->post('client_project_edit') != ''?1:0,
					'project_delete' => $this->input->post('client_project_delete') != ''?1:0,
					'task_view' => $this->input->post('client_task_view') != ''?1:0,
					'task_create' => $this->input->post('client_task_create') != ''?1:0,
					'task_edit' => $this->input->post('client_task_edit') != ''?1:0,
					'task_delete' => $this->input->post('client_task_delete') != ''?1:0,
					'user_view' => $this->input->post('client_user_view') != ''?1:0,
					'client_view' => $this->input->post('client_client_view') != ''?1:0,
					'setting_view' => $this->input->post('client_setting_view') != ''?1:0,
					'setting_update' => $this->input->post('client_setting_update') != ''?1:0,
					'todo_view' => $this->input->post('client_todo_view') != ''?1:0,
					'notes_view' => $this->input->post('client_notes_view') != ''?1:0,
					'chat_view' => $this->input->post('client_chat_view') != ''?1:0,
				);

				$client_data = array(
					'value' => json_encode($client_data_json)
				);

				$client_setting_type = 'clients_permissions';
				if(!$this->ion_auth->in_group(3)){
					$client_setting_type = 'clients_permissions_'.$this->session->userdata('saas_id');
				}

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->settings_model->save_settings($client_setting_type,$client_data);
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = 'Permissions Setting Saved.';
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
				}
		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = 'Access Denied';
			echo json_encode($this->data); 
		}
	}

	public function save_email_setting()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			$setting_type = 'email';
			$this->form_validation->set_rules('smtp_host', 'SMTP Host', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_username', 'Username', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_password', 'Password', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('smtp_encryption', 'Encryption', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				$template_path 	= 'assets/templates/email.php';
                    
        		$output_path 	= 'application/config/email.php';
        
        		$email_file = file_get_contents($template_path);

        		if($this->input->post('smtp_encryption') == 'none'){
				     $smtp_encryption = $this->input->post('smtp_encryption');
				}else{
				     $smtp_encryption = $this->input->post('smtp_encryption').'://'.$this->input->post('smtp_host');
				}
				
        		$new  = str_replace("%SMTP_HOST%",$smtp_encryption,$email_file);
        		$new  = str_replace("%SMTP_PORT%",$this->input->post('smtp_port'),$new);
        		$new  = str_replace("%SMTP_USER%",$this->input->post('smtp_username'),$new);
        		$new  = str_replace("%SMTP_PASS%",$this->input->post('smtp_password'),$new);
        
        		if(!write_file($output_path, $new)){
        			$this->data['error'] = true;
					$this->data['message'] = "Some Error occured. Please Try again later.";
					echo json_encode($this->data);
					return false;
        		} 

				$data_json = array(
					'smtp_host' => $this->input->post('smtp_host'),
					'smtp_port' => $this->input->post('smtp_port'),
					'smtp_username' => $this->input->post('smtp_username'),
					'smtp_password' => $this->input->post('smtp_password'),
					'smtp_encryption' => $this->input->post('smtp_encryption'),	
				);

				$data = array(
					'value' => json_encode($data_json)
				);

				if(!$this->ion_auth->in_group(3)){
					$setting_type = 'email_'.$this->session->userdata('saas_id');
				}

				if($this->settings_model->save_settings($setting_type,$data)){
				    
				    if($this->input->post('email')){
    				    $this->email->from(smtp_email(), company_name()); 
            			$this->email->to($this->input->post('email'));
            			$this->email->subject('Testing SMTP');  
            			$body = "<html>
            				<body>
            					<p>SMTP is perfectly configured.</p>
            					<p>Go To your workspace <a href='".base_url()."'>Click Here</a></p>
            				</body>
            			</html>";
            			$this->email->message($body); 
            	  		$this->email->send();
				    }
				    
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = 'Email Setting Saved.';
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

	public function save_general_setting()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)))
		{

			$setting_type = 'general';
			if($this->ion_auth->in_group(3)){
				$this->form_validation->set_rules('company_name', 'Company Name', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('footer_text', 'Footer Text', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('google_analytics', 'Google Analytics', 'trim|strip_tags|xss_clean');
			}

			$this->form_validation->set_rules('mysql_timezone', 'Timezone', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('php_timezone', 'Timezone', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('date_format', 'Date Format', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('time_format', 'Time Format', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('file_upload_format', 'File Upload Format', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){

				if($this->ion_auth->in_group(3)){
					$upload_path = 'assets/uploads/logos/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}

					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = 'gif|jpg|png|ico';
					$config['overwrite']             = false;
					$config['max_size']             = 10000;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$this->load->library('upload', $config);
					if (!empty($_FILES['full_logo']['name'])){
						if ($this->upload->do_upload('full_logo')){
								$full_logo = $this->upload->data('file_name');
								if($this->input->post('full_logo_old')){
									$unlink_path = $upload_path.''.$this->input->post('full_logo_old');
									unlink($unlink_path);
								}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data); 
							return false;
						}
					}else{
						$full_logo = $this->input->post('full_logo_old');
					}

					if (!empty($_FILES['half_logo']['name'])){
						if ($this->upload->do_upload('half_logo')){
								$half_logo = $this->upload->data('file_name');
								if($this->input->post('half_logo_old')){
									$unlink_path = $upload_path.''.$this->input->post('half_logo_old');
									unlink($unlink_path);
								}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data);  
							return false;
						}
					}else{
						$half_logo = $this->input->post('half_logo_old');
					}

					if (!empty($_FILES['favicon']['name'])){
						if ($this->upload->do_upload('favicon')){
							$favicon = $this->upload->data('file_name');
							if($this->input->post('favicon_old')){
								$unlink_path = $upload_path.''.$this->input->post('favicon_old');
								unlink($unlink_path);
							}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data);  
							return false;
						}
					}else{
						$favicon = $this->input->post('favicon_old');
					}

					$data_json = array(
						'company_name' => $this->input->post('company_name'),
						'footer_text' => $this->input->post('footer_text'),
						'google_analytics' => $this->input->post('google_analytics'),
						'mysql_timezone' => !empty($this->input->post('mysql_timezone') && $this->input->post('mysql_timezone') == '00:00')?'+'.$this->input->post('mysql_timezone'):$this->input->post('mysql_timezone'),
						'php_timezone' => $this->input->post('php_timezone'),
						'date_format' => $this->input->post('date_format'),
						'time_format' => $this->input->post('time_format'),	
						'date_format_js' => $this->input->post('date_format_js'),
						'time_format_js' => $this->input->post('time_format_js'),		
						'file_upload_format' => $this->input->post('file_upload_format'),		
						'full_logo' => $full_logo,		
						'half_logo' => $half_logo,		
						'favicon' => $favicon,		
					);
				}else{

					$setting_type = 'general_'.$this->session->userdata('saas_id');

					$data_json = array(
						'mysql_timezone' => !empty($this->input->post('mysql_timezone') && $this->input->post('mysql_timezone') == '00:00')?'+'.$this->input->post('mysql_timezone'):$this->input->post('mysql_timezone'),
						'php_timezone' => $this->input->post('php_timezone'),
						'date_format' => $this->input->post('date_format'),
						'time_format' => $this->input->post('time_format'),	
						'date_format_js' => $this->input->post('date_format_js'),
						'time_format_js' => $this->input->post('time_format_js'),		
						'file_upload_format' => $this->input->post('file_upload_format'),		
					);
				}
				$data = array(
					'value' => json_encode($data_json)
				);

				if($this->settings_model->save_settings($setting_type,$data)){
					$this->data['error'] = false;
					$this->data['data'] = $data_json;
					$this->data['message'] = 'General Setting Saved.';
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


