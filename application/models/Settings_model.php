<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function save_settings($setting_type,$data){
        $this->db->where('type', $setting_type);
        $query = $this->db->get('settings');
        if($query->num_rows() > 0){
            $this->db->where('type', $setting_type);
            $this->db->update('settings', $data);
            return true;
        }else{
            $data["type"] = $setting_type;
            if($this->db->insert('settings', $data)){
                return true;
            }else{
                return false;
            }
        }
    }
}