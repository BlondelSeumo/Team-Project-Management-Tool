<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class'    => 'SetTimeZoneHook',
    'function' => 'SetTimeZoneFunc',
    'filename' => 'settimezonehook.php',
    'filepath' => 'hooks');