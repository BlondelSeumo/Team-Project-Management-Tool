<?php

error_reporting(0);

$db_config_path = '../application/config/database.php';

function show_message($type,$message) {
    return $message;
}

function write_config($data) {

    $template_path 	= 'data/template.php';
    
    $output_path 	= '../application/config/database.php';

    $database_file = file_get_contents($template_path);

    $new  = str_replace("%HOSTNAME%",$data['hostname'],$database_file);
    $new  = str_replace("%USERNAME%",$data['username'],$new);
    $new  = str_replace("%PASSWORD%",$data['password'],$new);
    $new  = str_replace("%DATABASE%",$data['name'],$new);

    $handle = fopen($output_path,'w+');
    @chmod($output_path,0777);
    
    if(is_writable(dirname($output_path))) {

        if(fwrite($handle,$new)) {

            $template_path_user 	= 'data/template.sql';
            
            $output_path_user 	= 'data/sqlcommand.sql';

            $database_file_user = file_get_contents($template_path_user);
            
            $password = trim($data['admin_password']); 

            $email = trim($data['admin_email']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }

            if(strlen($password) < 8){
                return false;
            }

            $new_user  = str_replace("%ADMINEMAIL%",$email,$database_file_user);

            $params = [
                'cost' => 12
            ];

            if (empty($password) || strpos($password, "\0") !== FALSE || strlen($password) > 32)
            {
                return FALSE;
            }else{
                $password = password_hash($password, PASSWORD_BCRYPT, $params);
            }

            $new_user  = str_replace("%ADMINPASSWORD%",$password,$new_user);
            
            $handle_user = fopen($output_path_user,'w+');
            @chmod($output_path_user,0777);
            
            if(is_writable(dirname($output_path_user))) {

                if(fwrite($handle_user,$new_user)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkFile(){
    $output_path = '../application/config/database.php';
    
    if (file_exists($output_path)) {
       return true;
    } 
    else{
        return false;
    }
}

function create_database($data)
{
    $mysqli = new mysqli($data['hostname'],$data['username'],$data['password'],'');
    if(mysqli_connect_errno())
        return false;
    $mysqli->query("CREATE DATABASE IF NOT EXISTS ".$data['name']);
    $mysqli->close();
    return true;
}

function create_tables($data)
{	
    $password = !empty($data['password'])?$data['password']:'';
    $mysqli = new mysqli($data['hostname'],$data['username'],$password,$data['name']);
    if(mysqli_connect_errno())
        return false;
    $query = file_get_contents('data/sqlcommand.sql');
    $mysqli->multi_query($query);
    $mysqli->close();
    return true;
}
?>