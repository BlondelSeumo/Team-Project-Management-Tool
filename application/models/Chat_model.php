<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function get_chat($from_id, $to_id){
        $query = $this->db->query("SELECT * FROM messages WHERE type='chat' AND ((from_id=$from_id AND to_id=$to_id) OR (from_id=$to_id AND to_id=$from_id))");
        return $query->result_array();
    }

    function create($data){
        if($this->db->insert('messages', $data))
            return $this->db->insert_id();
        else
            return false; 
    }
}