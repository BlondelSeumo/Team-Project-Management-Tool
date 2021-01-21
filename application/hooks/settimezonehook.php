<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetTimeZoneHook {

    function SetTimeZoneFunc() {

        $CI =& get_instance();
        $CI->db->from('settings');
        $CI->db->where(['type'=>'general']);
        $query = $CI->db->get();
        $confi = $query->result_array();
        $confi = json_decode($confi[0]['value']);
        
        if(!empty($confi->mysql_timezone)){
            $CI->db->query("SET time_zone='".$confi->mysql_timezone."'");
        }else{
            $CI->db->query("SET time_zone='-11:00'");
        }
        if(!empty($confi->php_timezone)){
            date_default_timezone_set($confi->php_timezone);
        }else{
            date_default_timezone_set('Pacific/Midway');
        }

    }

}
