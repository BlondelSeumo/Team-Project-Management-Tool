<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plans_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function get_transaction_chart(){
        $query = $this->db->query("SELECT sum(amount) AS amount, DATE(created) as date
        FROM transactions
        WHERE created BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() GROUP BY DATE(created)");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_transactions($transaction_id = ''){
        $where = "";
        $where .= (!empty($transaction_id) && is_numeric($transaction_id))?" AND o.id=$transaction_id":"";
        $left_join = " LEFT JOIN users u ON o.saas_id=u.id ";
        $query = $this->db->query("SELECT o.*,u.first_name,u.last_name FROM transactions o $left_join $where ORDER BY o.id DESC");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
    
    function get_orders($order_id = ''){
        $where = "";
        $where .= (!empty($order_id) && is_numeric($order_id))?" AND o.id=$order_id":"";
        $left_join = " LEFT JOIN plans p ON o.plan_id=p.id ";
        $left_join .= " LEFT JOIN users u ON o.saas_id=u.id ";
        $query = $this->db->query("SELECT o.*,p.title,p.price,p.billing_type,u.first_name,u.last_name FROM orders o $left_join $where ORDER BY o.id DESC");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function get_plans($plan_id = ''){
        $where = "WHERE status = 1";
        $where .= (!empty($plan_id) && is_numeric($plan_id))?" AND id=$plan_id":"";
        $query = $this->db->query("SELECT * FROM plans $where");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function delete($id){
        $this->db->where('id', $id);
        if($this->db->delete('plans'))
            return true;
        else
            return false;
    }

    function create($data){
        if($this->db->insert('plans', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_transaction($data){
        if($this->db->insert('transactions', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_order($data){
        if($this->db->insert('orders', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function create_users_plans($data){
        if($this->db->insert('users_plans', $data))
            return $this->db->insert_id();
        else
            return false; 
    }

    function update_users_plans($saas_id, $data){
        $this->db->where('saas_id', $saas_id);
        if($this->db->update('users_plans', $data))
            return true;
        else
            return false;
    }

    function delete_plan_update_users_plan($id){
        $this->db->where('plan_id', $id);
        if($this->db->update('users_plans', array('plan_id' => 1)))
            return true;
        else
            return false;
    }

    function edit($id, $data){
        $this->db->where('id', $id);
        if($this->db->update('plans', $data))
            return true;
        else
            return false;
    }

}