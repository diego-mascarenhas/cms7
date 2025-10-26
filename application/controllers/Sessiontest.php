<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Session Test Controller
 * 
 * Dedicated controller for session debugging and testing
 */
class Sessiontest extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Session Test Index
     */
    public function index()
    {
        echo "<h1>Session Test Tools</h1>";
        echo "<ul>";
        echo "<li><a href='" . base_url('sessiontest/info') . "'>Session Info</a></li>";
        echo "<li><a href='" . base_url('sessiontest/set') . "'>Set Test Values</a></li>";
        echo "<li><a href='" . base_url('sessiontest/clear') . "'>Clear Session</a></li>";
        echo "<li><a href='" . base_url('sessiontest/file') . "'>Session File Content</a></li>";
        echo "<li><a href='" . base_url('user/login?debug=1') . "'>Login with Debug</a></li>";
        echo "</ul>";
    }
    
    /**
     * Session Info
     */
    public function info()
    {
        echo "<h1>Current Session Info</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Session Name: " . session_name() . "\n";
        echo "Session Save Path: " . session_save_path() . "\n\n";
        
        echo "SESSION DATA:\n";
        print_r($_SESSION);
        
        echo "\nCI SESSION DATA:\n";
        print_r($this->session->userdata());
        
        echo "</pre>";
        
        echo "<p><a href='" . base_url('sessiontest') . "'>Back to Session Tools</a></p>";
    }
    
    /**
     * Set Test Values
     */
    public function set()
    {
        // Set test values
        $_SESSION['test_direct'] = 'Direct $_SESSION value: ' . time();
        $this->session->set_userdata('test_ci', 'CI set_userdata value: ' . time());
        
        echo "<h1>Session Test Values Set</h1>";
        echo "<p>Test values have been set in the session.</p>";
        echo "<p><a href='" . base_url('sessiontest/info') . "'>View Session Info</a></p>";
    }
    
    /**
     * Clear Session
     */
    public function clear()
    {
        $this->session->sess_destroy();
        
        echo "<h1>Session Cleared</h1>";
        echo "<p>The session has been destroyed.</p>";
        echo "<p><a href='" . base_url('sessiontest/info') . "'>View Session Info</a></p>";
    }
    
    /**
     * Session File Content
     */
    public function file()
    {
        echo "<h1>Session File Content</h1>";
        
        $session_id = session_id();
        $save_path = session_save_path();
        $session_file = $save_path . '/ci_session' . $session_id;
        
        echo "<p>Session ID: " . $session_id . "</p>";
        echo "<p>Session Save Path: " . $save_path . "</p>";
        echo "<p>Session File Path: " . $session_file . "</p>";
        
        if (file_exists($session_file)) {
            echo "<p>File exists: YES</p>";
            echo "<p>File size: " . filesize($session_file) . " bytes</p>";
            echo "<p>File content:</p>";
            echo "<pre>" . htmlspecialchars(file_get_contents($session_file)) . "</pre>";
        } else {
            echo "<p>File exists: NO</p>";
        }
        
        echo "<p><a href='" . base_url('sessiontest') . "'>Back to Session Tools</a></p>";
    }
    
    /**
     * Test redirect functionality
     */
    public function redirect_test()
    {
        // Set a value before redirect
        $this->session->set_userdata('redirect_test', 'Value set before redirect: ' . time());
        $_SESSION['redirect_test_direct'] = 'Direct value before redirect: ' . time();
        
        // Force session write
        session_write_close();
        
        echo "<h1>Redirect Test</h1>";
        echo "<p>Session values have been set. Click the link below to redirect and check if values persist.</p>";
        echo "<p><a href='" . base_url('sessiontest/after_redirect') . "'>Redirect Now</a></p>";
    }
    
    /**
     * After redirect page
     */
    public function after_redirect()
    {
        echo "<h1>After Redirect</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Redirect Test Value (CI): " . $this->session->userdata('redirect_test') . "\n";
        echo "Redirect Test Value (Direct): " . (isset($_SESSION['redirect_test_direct']) ? $_SESSION['redirect_test_direct'] : 'NOT SET') . "\n";
        echo "</pre>";
        
        echo "<p><a href='" . base_url('sessiontest') . "'>Back to Session Tools</a></p>";
    }
} 