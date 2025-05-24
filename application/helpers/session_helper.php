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
    function ensure_session_data($data = array()) {
        $CI =& get_instance();
        
        // Check if the session already has the data
        $missing = false;
        foreach ($data as $key) {
            if (!$CI->session->has_userdata($key)) {
                $missing = true;
                break;
            }
        }
        
        // If any data is missing, force session write and restart
        if ($missing) {
            $CI->session->mark_as_temp('dummy', 1);
            // Only do session operations if headers haven't been sent
            if (!headers_sent()) {
                @session_write_close();
                @session_start();
            } else {
                log_message('error', 'Cannot manipulate session - headers already sent');
            }
        }
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