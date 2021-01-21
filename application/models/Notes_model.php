<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notes_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($user_id, $id){
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        if($this->db->delete('notes'))
            return true;
        else
            return false;
    }

    function get_notes($user_id, $note_id = ''){
        $where = "";
        $where .= (!empty($note_id) && is_numeric($note_id))?" AND id=$note_id":"";
        $query = $this->db->query("SELECT * FROM notes WHERE user_id=$user_id $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('notes', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('notes', $data))
            return true;
        else
            return false;
    }

}