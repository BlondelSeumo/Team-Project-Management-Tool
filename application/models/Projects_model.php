<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function get_comments($type = '',$from_id = '',$to_id = ''){

        $where = " WHERE m.type = '$type' AND m.to_id = $to_id ";
        $order = " ORDER BY m.created DESC ";

        $left_join = " LEFT JOIN users u ON m.from_id=u.id ";

        $query = $this->db->query("SELECT m.*,u.first_name,u.last_name,u.profile FROM messages m $left_join $where $order ");
    
        $comments = $query->result_array();
 
        $temp = [];

        foreach($comments as $key => $comment){
            $temp[$key] = $comment;
            $temp[$key]['created'] = format_date($comment['created'],system_date_format());
            $temp[$key]['profile'] = $comment['profile'];
            $temp[$key]['short_name'] = ucfirst(mb_substr($comment['first_name'], 0, 1, 'utf-8')).''.ucfirst(mb_substr($comment['last_name'], 0, 1, 'utf-8'));
        }
        $comments = $temp;
        if($comments){
            return $comments;
        }else{
            return false;
        }
    }

    function create_comment($data){
        if($this->db->insert('messages', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function task_status_update($task_id, $new_status){
        $this->db->set('status', $new_status);
        $this->db->where('id', $task_id);
        if($this->db->update('tasks'))
            return true;
        else
            return false;
    }

    function delete_task_files($file_id='',$task_id=''){
        if($file_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE id='.$file_id.'');
            $data = $query->result_array();
            if(!empty($data)){
                if(unlink('assets/uploads/tasks/'.$data[0]['file_name'])){
                    $this->db->delete('media_files', array('id' => $file_id));
                }
            }
            return true;
        }elseif($task_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE type="task" AND type_id='.$task_id.'');
            $datas = $query->result_array();
            if(!empty($datas)){
                foreach($datas as $data){
                    if(unlink('assets/uploads/tasks/'.$data['file_name'])){
                        $this->db->delete('media_files', array('id' => $data['id']));
                    }
                }
            }
            return true;
        }else{
            return false;
        }
        
    }
    function delete_project_files($file_id='',$project_id=''){
        if($file_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE id='.$file_id.'');
            $data = $query->result_array();
            if(!empty($data)){
                if(unlink('assets/uploads/projects/'.$data[0]['file_name'])){
                    $this->db->delete('media_files', array('id' => $file_id));
                }
            }
            return true;
        }elseif($project_id){
            $query = $this->db->query('SELECT * FROM media_files WHERE type="project" AND type_id='.$project_id.'');
            $datas = $query->result_array();
            if(!empty($datas)){
                foreach($datas as $data){
                    if(unlink('assets/uploads/projects/'.$data['file_name'])){
                        $this->db->delete('media_files', array('id' => $data['id']));
                    }
                }
            }
            return true;
        }else{
            return false;
        }
        
    }

    function upload_files($data){
        if($this->db->insert('media_files', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_project($data){
        if($this->db->insert('projects', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_task($data){
        if($this->db->insert('tasks', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function edit_task($task_id, $data){
        $this->db->where('id', $task_id);
        if($this->db->update('tasks', $data))
            return true;
        else
            return false;
    }

    function edit_project($project_id, $data){
        $this->db->where('id', $project_id);
        if($this->db->update('projects', $data))
            return true;
        else
            return false;
    }

    function create_project_users($data){
        if($this->db->insert('project_users', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_task_users($data){
        if($this->db->insert('task_users', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function delete_project_users($project_id='',$user_id=''){

        if(empty($project_id) && empty($user_id)){
            return false;
        }

        if(!empty($project_id)){
            $this->db->where('project_id', $project_id);
        }
        if(!empty($user_id)){
            $this->db->where('user_id', $user_id);
        }
        $this->db->delete('project_users');
        return true;
    }

    function delete_task_comment($comment_id='',$user_id='',$type='',$to_id=''){

        if(empty($comment_id) && empty($user_id) && empty($type) && empty($to_id)){
            return false;
        }
        if(!empty($type)){
            $this->db->where('type', $type);
        }
        if(!empty($to_id)){
            $this->db->where('to_id', $to_id);
        }
        if(!empty($comment_id)){
            $this->db->where('id', $comment_id);
        }
        if(!empty($user_id)){
            $this->db->where('from_id', $user_id);
        }
        $this->db->delete('messages');
        return true;
    }

    function delete_task($task_id){
        $this->db->where('id', $task_id);
        if($this->db->delete('tasks'))
            return true;
        else
            return false;
    }

    
    function delete_project($project_id){
        $this->db->where('id', $project_id);
        if($this->db->delete('projects'))
            return true;
        else
            return false;
    }

    function delete_task_users($task_id='',$user_id=''){

        if(empty($task_id) && empty($user_id)){
            return false;
        }

        if(!empty($task_id)){
            $this->db->where('task_id', $task_id);
        }
        if(!empty($user_id)){
            $this->db->where('user_id', $user_id);
        }
        $this->db->delete('task_users');
        return true;
    }

    function get_tasks_files($task_id = '',$user_id = ''){
        $where = "";
        $where .= (!empty($task_id) && is_numeric($task_id))?"AND type_id=$task_id":"";
        $where .= (!empty($user_id) && is_numeric($user_id))?"AND user_id=$user_id":"";
        $query = $this->db->query("SELECT * FROM media_files WHERE type='task' $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_project_files($project_id = '',$user_id = ''){
        $where = "";
        $where .= (!empty($project_id) && is_numeric($project_id))?"AND type_id=$project_id":"";
        $where .= (!empty($user_id) && is_numeric($user_id))?"AND user_id=$user_id":"";
        $query = $this->db->query("SELECT * FROM media_files WHERE type='project' $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_project_users($project_id = ''){
        $where = " WHERE u.saas_id = ".$this->session->userdata('saas_id');
        $where .= (!empty($project_id) && is_numeric($project_id))?" AND pu.project_id=$project_id ":" ";
        $left_join = "LEFT JOIN users u ON pu.user_id=u.id";
        $query = $this->db->query("SELECT u.id,u.email,u.first_name,u.last_name,u.profile FROM project_users pu $left_join $where GROUP BY pu.user_id");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_task_users($task_id = ''){
        $where = "";
        $where .= (!empty($task_id) && is_numeric($task_id))?"WHERE pu.task_id=$task_id":"";
        $left_join = "LEFT JOIN users u ON pu.user_id=u.id";
        $query = $this->db->query("SELECT u.id,u.email,u.first_name,u.last_name,u.profile FROM task_users pu $left_join $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_projects($user_id = '',$project_id = '',$limit='', $start='', $filter_type='', $filter=''){

        if(!empty($limit)){
            $where_limit = ' LIMIT '.$limit;
            if(!empty($start)){
                $where_limit .= ' OFFSET '.$start;
            }
        }else{
            $where_limit = '';
        }

        $where = "";
        $order = " ORDER BY p.created DESC ";

        if(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'status'){
            $where = "WHERE ps.id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'user'){
            $where = "WHERE pu.user_id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && is_numeric($filter) && $filter_type == 'client'){
            $where = "WHERE p.client_id = $filter";
        }elseif(!empty($filter_type) && !empty($filter) && $filter_type == 'sortby'){
            if($filter == 'old'){
                $order = " ORDER BY p.created ASC ";
            }elseif($filter == 'name'){
                $order = " ORDER BY p.title ASC ";
            }else{
                $order = " ORDER BY p.created DESC ";
            }
            
        }

        $where .= (!empty($project_id) && is_numeric($project_id) && empty($where))?"WHERE pu.project_id=$project_id":"";

        if(!empty($user_id) && is_numeric($user_id)){
            if($this->ion_auth->in_group(4)){
                $where .= (empty($where))?" WHERE p.client_id=$user_id ":" AND p.client_id=$user_id ";
            }else{
                $where .= (empty($where))?" WHERE pu.user_id=$user_id ":"";
            }
        }
        $where .= empty($where)?" WHERE p.saas_id=".$this->session->userdata('saas_id'):" AND p.saas_id=".$this->session->userdata('saas_id');

        $left_join = " LEFT JOIN projects p ON pu.project_id=p.id ";
        $left_join .= " LEFT JOIN project_status ps ON p.status=ps.id ";
        $query = $this->db->query("SELECT p.*,ps.title AS project_status,ps.class AS project_class FROM project_users pu $left_join $where GROUP BY pu.project_id $order $where_limit");
    
        $projects = $query->result_array();

        $temp = [];

        foreach($projects as $key => $project){
            $temp[$key] = $project;
            $temp[$key]['starting_date'] = format_date($project['starting_date'],system_date_format());
            $temp[$key]['ending_date'] = format_date($project['ending_date'],system_date_format());
            $days_count = count_days_btw_two_dates(date("Y-m-d"),$project['ending_date']);
            $temp[$key]['days_count'] = $days_count['days'];
            $temp[$key]['days_status'] = $days_count['days_status'];
            $temp[$key]['total_tasks'] = get_count('id','tasks','project_id='.$project['id']);
            $temp[$key]['completed_tasks'] = get_count('id','tasks','status = 4 and project_id='.$project['id']);
            if($project['client_id']){
                $temp[$key]['project_client'] = $this->ion_auth->user($project['client_id'])->row();
            }else{
                $temp[$key]['project_client'] = null;
            }
            $temp[$key]['project_users'] = $project_users = $this->get_project_users($project['id']);
            $temp[$key]['project_users_ids'] = '';
            if(!empty($project_users)){
                foreach($project_users as  $pkey => $project_user){
                    $tempid[$pkey] = $project_user['id'];
                }
                $temp[$key]['project_users_ids'] = implode(",",$tempid);
            }
            
        }
        $projects = $temp;
        if($projects){
            return $projects;
        }else{
            return false;
        }
    }

    function get_tasks($user_id = '',$task_id = '',$project_id = ''){

        $where = "";
        $order = " ORDER BY t.created DESC ";

        $where .= (!empty($task_id) && is_numeric($task_id) && empty($where))?"WHERE t.id=$task_id":"";
        
        if(!empty($project_id) && is_numeric($project_id) && empty($where)){
            $where .="WHERE p.id=$project_id";
        }elseif(!empty($project_id) && is_numeric($project_id) && !empty($where)){
            $where .=" AND p.id=$project_id";
        }

        if(!empty($user_id) && is_numeric($user_id)){
            if($this->ion_auth->in_group(4)){
                $where .= (empty($where))?" WHERE p.client_id=$user_id ":" AND p.client_id=$user_id ";
            }else{
                $where .= (empty($where))?" WHERE tu.user_id=$user_id ":"";
            }
        }

        $where .= empty($where)?" WHERE t.saas_id=".$this->session->userdata('saas_id'):" AND t.saas_id=".$this->session->userdata('saas_id');

        $left_join = " LEFT JOIN tasks t ON tu.task_id=t.id ";
        $left_join .= " LEFT JOIN task_status ts ON t.status=ts.id ";
        $left_join .= " LEFT JOIN priorities tp ON t.priority=tp.id ";
        $left_join .= " LEFT JOIN projects p ON t.project_id=p.id ";
        $query = $this->db->query("SELECT t.*,ts.title AS task_status,ts.class AS task_class,tp.title AS task_priority,tp.class AS priority_class,p.title AS project_title FROM task_users tu $left_join $where GROUP BY tu.task_id $order ");
    
        $tasks = $query->result_array();

        $temp = [];

        foreach($tasks as $key => $task){
            $temp[$key] = $task;
            $temp[$key]['due_date'] = format_date($task['due_date'],system_date_format());
            $days_count = count_days_btw_two_dates(date("Y-m-d"),$task['due_date']);
            $temp[$key]['days_count'] = $days_count['days'];
            $temp[$key]['days_status'] = $days_count['days_status'];
            $temp[$key]['task_users'] = $task_users = $this->get_task_users($task['id']);
            $temp[$key]['task_users_ids'] = '';
            if(!empty($task_users)){
                foreach($task_users as  $pkey => $task_user){
                    $tempid[$pkey] = $task_user['id'];
                }
                $temp[$key]['task_users_ids'] = implode(",",$tempid);
            }
            
        }
        $tasks = $temp;
        if($tasks){
            return $tasks;
        }else{
            return false;
        }
    }

}