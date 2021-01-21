<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function my_plan_features($feature_type = '')
{
    $CI =& get_instance();

    if($CI->session->userdata('saas_id') == ''){
        return true;
    }
    $count_query = $CI->db->query("SELECT * FROM users_plans WHERE saas_id=".$CI->session->userdata('saas_id')." AND end_date >= CURDATE()");
    $count = $count_query->row_array();
    if($count){
        $current_plan = get_current_plan();
    }else{
        return false;
    }

    if($current_plan[$feature_type] < 0){
        return true;
    }elseif($current_plan[$feature_type] == get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
        return false;
    }else{
        if($current_plan[$feature_type] < get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
            return false;
        }
        return true;
    }
    
} 

function get_current_plan($saas_id = ''){
    $CI =& get_instance();

    if(empty($saas_id)){
        $saas_id = $CI->session->userdata('saas_id');
    }

    $left_join = " LEFT JOIN plans p ON up.plan_id=p.id ";

    $query = $CI->db->query("SELECT up.*,p.title,p.price,p.billing_type,p.projects,p.tasks,p.users FROM users_plans up $left_join WHERE up.saas_id=$saas_id ORDER BY up.created DESC LIMIT 1");
    $data = $query->row_array();

    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where('type', 'permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = 'permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'permissions';
    }

    if($CI->ion_auth->in_group(4)){
        $CI->db->where('type', 'clients_permissions_'.$CI->session->userdata('saas_id'));
        $count = $CI->db->get('settings');

        if($count->num_rows() > 0){
            $where_type = 'clients_permissions_'.$CI->session->userdata('saas_id');
        }else{
            $where_type = 'permissions';
        }
    }

    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return true;
        }
    }
} 

function clients_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where('type', 'clients_permissions_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');

    if($count->num_rows() > 0){
        $where_type = 'clients_permissions_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'clients_permissions';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        return $data->$permissions_type;
    }
} 

function project_status($field = ''){
    $CI =& get_instance();
    if(!empty($field)){
        $CI->db->select($field);
    }
    $CI->db->from('project_status');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function priorities(){
    $CI =& get_instance();
    $CI->db->from('priorities');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function task_status(){
    $CI =& get_instance();
    $CI->db->from('task_status');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function get_payment_paypal(){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    $CI->db->where(['type'=>'payment']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paypal_client_id)){
            return $data->paypal_client_id;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

function get_system_version(){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    $CI->db->where(['type'=>'system_version']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data[0]['value'];
    }else{
        return false;
    }
}

function is_my_project($id){
    $CI =& get_instance();
    if($CI->ion_auth->in_group(4)){
    $query = $CI->db->query("SELECT id FROM projects WHERE id=$id AND client_id=".$CI->session->userdata('user_id'));
    }else{
        $query = $CI->db->query("SELECT id FROM projects WHERE id=$id AND saas_id=".$CI->session->userdata('saas_id'));
    }
        
    $res = $query->result_array();
    if(!empty($res)){
        return true;
    }else{
        return false;
    } 
}

function get_earnings(){ 
    
    $CI =& get_instance();
    $query = $CI->db->query("SELECT sum(amount) AS amount FROM transactions WHERE status=1");
    $res = $query->result_array();
    if(!empty($res)){
        return $res[0]['amount']?$res[0]['amount']:0;
    }else{
        return false;
    }
    
}

function get_count($field,$table,$where = ''){ 
    if(!empty($where))
        $where = "where ".$where;
        
    $CI =& get_instance();
    $query = $CI->db->query("SELECT COUNT(".$field.") as total FROM ".$table." ".$where." ");
    $res = $query->result_array();
    if(!empty($res)){
        return $res[0]['total'];
    }
    
}

function smtp_host()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }

    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_host)){
        return $data->smtp_host;
    }else{
        return false;
    }
} 

function smtp_port()
{
    $CI =& get_instance();

    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_port)){
        return $data->smtp_port;
    }else{
        return false;
    }
} 

function smtp_email()
{
    $CI =& get_instance();
    $CI->load->library('session');
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_username)){
        return $data->smtp_username;
    }else{
        return false;
    }
}

function smtp_password()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_password)){
        return $data->smtp_password;
    }else{
        return false;
    }
}

function smtp_encryption()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_encryption)){
        return $data->smtp_encryption;
    }else{
        return false;
    }
}

function company_name()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_name)){
        return $data->company_name;
    }else{
        return 'Tim Work';
    }
} 

function company_email()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_email)){
        return $data->company_email;
    }else{
        return 'admin@admin.com';
    }
} 

function footer_text()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->footer_text)){
        return $data->footer_text;
    }else{
        return company_name().' '.date('Y').' All Rights Reserved';
    }
} 

function google_analytics()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->google_analytics)){
        return $data->google_analytics;
    }else{
        return false;
    }
} 

function mysql_timezone()
{
    $CI =& get_instance();

    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->mysql_timezone)){
        return $data->mysql_timezone;
    }else{
        return '-11:00';
    }
} 

function php_timezone()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->php_timezone)){
        return $data->php_timezone;
    }else{
        return 'Pacific/Midway';
    }
} 

function system_date_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format_js)){
        return $data->date_format_js;
    }else{
        return 'd-m-yyyy';
    }
} 

function system_time_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format_js)){
        return $data->time_format_js;
    }else{
        return 'hh:MM tt';
    }
} 

function count_days_btw_two_dates($today , $sec_date){
    $today=date_create($today);
    $sec_date=date_create($sec_date);
    $diff=date_diff($today,$sec_date);
    $data['days'] = $diff->format("%a");
    if($today < $sec_date || $today == $sec_date){
        $data['days_status'] = 'Left';
    }else{
        $data['days_status'] = 'Overdue';
    }
    return $data;
}

function format_date($date , $date_format){
    $date = date_create($date);
    return date_format($date,$date_format);
}

function system_date_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format)){
        return $data->date_format;
    }else{
        return 'd-m-Y';
    }
} 

function system_time_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format)){
        return $data->time_format;
    }else{
        return 'h:i A';
    }
} 

function full_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->full_logo)){
        return $data->full_logo;
    }else{
        return 'logo.png';
    }
} 

function file_upload_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->file_upload_format)){
        return $data->file_upload_format;
    }else{
        return 'jpg|png';
    }
}


function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function half_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->half_logo)){
        return $data->half_logo;
    }else{
        return 'logo-half.png';
    }
} 

function favicon()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();
    
    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->favicon)){
        return $data->favicon;
    }else{
        return 'favicon.png';
    }
} 


function time_formats(){
    $CI =& get_instance();
    $CI->db->from('time_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function date_formats(){
    $CI =& get_instance();
    $CI->db->from('date_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function timezones(){
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();
    
        $data = $offset = $added = array();
        foreach ($list as $abbr => $info) {
            foreach ($info as $zone) {
                if ( ! empty($zone['timezone_id'])
                    AND
                    ! in_array($zone['timezone_id'], $added)
                    AND 
                      in_array($zone['timezone_id'], $idents)) {
                    $z = new DateTimeZone($zone['timezone_id']);
                    $c = new DateTime(null, $z);
                    $zone['time'] = $c->format('H:i a');
                    $offset[] = $zone['offset'] = $z->getOffset($c);
                    $data[] = $zone;
                    $added[] = $zone['timezone_id'];
                }
            }
        }
    
        array_multisort($offset, SORT_ASC, $data);
        
        $i = 0;$temp = array();
        foreach ($data as $key => $row) {
            $temp[0] = $row['time'];
            $temp[1] = formatOffset($row['offset']);
            $temp[2] = $row['timezone_id'];
            $options[$i++] = $temp;
        }
        
        if(!empty($options)){
            return $options;
        }
}

function formatOffset($offset) {
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 AND $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT).':'. str_pad($minutes,2, '0');
}

?>