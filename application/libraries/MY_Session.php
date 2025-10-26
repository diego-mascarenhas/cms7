<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extended Session Class
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Sessions
 * @author      Your Name
 */
class MY_Session extends CI_Session {

    /**
     * Class constructor
     *
     * @param   array   $params Configuration parameters
     * @return  void
     */
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        log_message('debug', 'MY_Session Class Initialized');
    }

    /**
     * Extended set_userdata method with forced session write
     *
     * @param   mixed   $data   Session data key or an associative array
     * @param   mixed   $value  Value to store
     * @return  void
     */
    public function set_userdata($data, $value = NULL)
    {
        parent::set_userdata($data, $value);
        
        // Force session write to ensure the data is saved
        session_write_close();
        session_start();
    }
    
    /**
     * Serialize fix - Make sure objects are properly serialized
     *
     * @param   mixed   $data   Data to be serialized
     * @return  string
     */
    protected function _serialize($data)
    {
        if (is_object($data))
        {
            // Clone the object to avoid circular references
            $data = clone $data;
        }
        
        return parent::_serialize($data);
    }
    
    /**
     * Check for valid session data
     *
     * @return  bool
     */
    public function has_valid_session()
    {
        return $this->has_userdata('usuario') && $this->has_userdata('logged_in');
    }
} 