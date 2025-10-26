<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Session Hook Class
 *
 * This hook ensures that session data is properly saved at the end of each request
 */
class SessionHook {

    /**
     * Post-system hook to ensure session data is saved
     */
    public function session_end()
    {
        // Reference to the CI instance
        $CI =& get_instance();
        
        if (isset($CI->session) && $CI->session->userdata('usuario')) {
            // Force session write
            session_write_close();
        }
    }
} 