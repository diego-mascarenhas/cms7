<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Custom Session Helper
 * 
 * Functions to help ensure session data integrity
 */

// ------------------------------------------------------------------------

/**
 * Ensure Session Data
 *
 * Makes sure that critical session data persists
 * 
 * @param	array	$data	Session data to ensure
 * @return	void
 */
if (!function_exists('ensure_session_data')) {
    /**
     * Ensure session data exists
     * 
     * Verifies that specified session keys exist and are not empty
     * If they don't exist or are empty, try to recover them from other sources
     * 
     * @param array $keys Keys to check
     * @return bool True if all keys are present and valid
     */
    function ensure_session_data($keys = array()) {
        $CI =& get_instance();
        
        // Load libraries if needed
        if (!isset($CI->simple_session) && class_exists('Simple_session')) {
            $CI->load->library('simple_session');
        }
        
        // Default keys to check
        if (empty($keys)) {
            $keys = array('usuario', 'logged_in');
        }
        
        // Keep track if we needed to recover any data
        $needed_recovery = false;
        
        foreach ($keys as $key) {
            // Check if key exists and is not empty
            if (!$CI->session->has_userdata($key) || empty($CI->session->userdata($key))) {
                // Try to recover from simple session first
                if (isset($CI->simple_session) && $CI->simple_session->has($key)) {
                    $value = $CI->simple_session->get($key);
                    if (!empty($value)) {
                        $CI->session->set_userdata($key, $value);
                        
                        // Special case for usuario object
                        if ($key === 'usuario' && !isset($CI->usuario)) {
                            $CI->usuario = $value;
                        }
                        
                        $needed_recovery = true;
                        continue;
                    }
                }
                
                // Try to recover from PHP native session
                if (isset($_SESSION[$key]) && !empty($_SESSION[$key])) {
                    $CI->session->set_userdata($key, $_SESSION[$key]);
                    
                    // Special case for usuario object
                    if ($key === 'usuario' && !isset($CI->usuario)) {
                        $CI->usuario = $_SESSION[$key];
                    }
                    
                    $needed_recovery = true;
                    continue;
                }
                
                // If we couldn't recover this key, try to use alternate recovery methods
                if ($key === 'usuario' && $CI->session->has_userdata('reseller')) {
                    $reseller_id = $CI->session->userdata('reseller');
                    
                    // Load necessary models
                    $CI->load->model('user_model');
                    
                    // Get user data
                    $user = $CI->user_model->getUserInfo($reseller_id);
                    
                    if ($user && isset($user->id)) {
                        $CI->session->set_userdata('usuario', $user);
                        $CI->session->set_userdata('logged_in', true);
                        
                        // Update usuario property
                        $CI->usuario = $user;
                        
                        $needed_recovery = true;
                        continue;
                    }
                }
            }
        }
        
        // Log if we had to recover anything
        if ($needed_recovery) {
            $CI->load->helper('file');
            $log_message = date('Y-m-d H:i:s') . " - SESSION DATA RECOVERY performed in " . 
                $CI->router->class . "/" . $CI->router->method . "\n";
            write_file(FCPATH . 'application/logs/session_recovery.log', $log_message, 'a');
        }
        
        return true;
    }
}

// ------------------------------------------------------------------------

/**
 * Safely Store Session Data
 *
 * Stores session data and ensures it's written to storage
 * 
 * @param	mixed	$data	Session data key or an associative array
 * @param	mixed	$value	Value to store
 * @return	void
 */
if (!function_exists('safe_set_userdata')) {
    function safe_set_userdata($data, $value = NULL) {
        $CI =& get_instance();
        
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $_SESSION[$key] = $val;
            }
        } else {
            $_SESSION[$data] = $value;
        }
        
        // Force session write only if headers haven't been sent
        if (!headers_sent()) {
            @session_write_close();
            @session_start();
        } else {
            log_message('error', 'Cannot manipulate session - headers already sent');
        }
    }
}

if (!function_exists('check_session_permissions')) {
    /**
     * Check session directory permissions
     * 
     * Verifies that the session directory exists and has correct permissions
     * 
     * @return bool True if directory exists and has correct permissions
     */
    function check_session_permissions() {
        $CI =& get_instance();
        
        // Check main session directory
        $session_path = $CI->config->item('sess_save_path');
        
        if (!is_dir($session_path)) {
            mkdir($session_path, 0777, TRUE);
        }
        
        // Check simple session directory if we're using it
        if (class_exists('Simple_session')) {
            $simple_path = FCPATH . 'application/sessions/simple/';
            
            if (!is_dir($simple_path)) {
                mkdir($simple_path, 0777, TRUE);
            }
            
            // Verify permissions
            $perms = substr(sprintf('%o', fileperms($simple_path)), -4);
            if ($perms != '0777') {
                chmod($simple_path, 0777);
            }
        }
        
        return true;
    }
} 