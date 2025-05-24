<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Add a hook to ensure session data is properly saved
$hook['post_system'] = array(
    'class'    => 'SessionHook',
    'function' => 'session_end',
    'filename' => 'SessionHook.php',
    'filepath' => 'hooks',
    'params'   => array()
);
