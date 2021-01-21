<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Todo_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function delete($user_id, $id){
        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        if($this->db->delete('todo'))
            return true;
        else
            return false;
    }

    function get_todo($user_id, $note_id = '', $filter = ''){
        $where = "";
        $where .= (!empty($note_id) && is_numeric($note_id))?" AND id=$note_id":"";

       if($filter == 'today'){
            $where .= " AND date(due_date) = CURDATE()";
        }elseif($filter == 'upcoming'){
            $where .= " AND date(due_date) > CURDATE()";
        }elseif($filter == 'finished'){
            $where .= " AND done = 1";
        }elseif($filter == 'pending'){
            $where .= " AND done = 0";
        }elseif($filter == 'overdue'){
            $where .= " AND date(due_date) < CURDATE()";
        }else{
            $where .= " ";
        }
        
        $query = $this->db->query("SELECT * FROM todo WHERE user_id=$user_id $where");
        $data = $query->result_array();
        $temp = [];

        foreach($data as $key => $task){
            $temp[$key] = $task;
            $temp[$key]['due_date'] = format_date($task['due_date'],system_date_format());
            $days_count = count_days_btw_two_dates(date("Y-m-d"),$task['due_date']);
            $temp[$key]['days_count'] = $days_count['days'];
            $temp[$key]['days_status'] = $days_count['days_status'];
        }
        $todo = $temp;
        if($todo){
            return $todo;
        }else{
            return false;
        }
    }

    function create($data){
        if($this->db->insert('todo', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('todo', $data))
            return true;
        else
            return false;
    }

}