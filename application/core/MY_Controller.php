<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load simple session first
		$this->load->library('simple_session');

		// Verify session directory exists
		$sess_path = $this->config->item('sess_save_path');
		if (!is_dir($sess_path)) {
			mkdir($sess_path, 0777, TRUE);
		}
		
		// Log session state for debugging
		$this->log_session_state();

		// Check for user in simple session first
		$simple_usuario = $this->simple_session->get('usuario');
		if ($simple_usuario && is_object($simple_usuario) && 
		   (!$this->session->has_userdata('usuario') || empty($this->session->userdata('usuario')))) {
			// Transfer from simple session to CI session
			$this->session->set_userdata('usuario', $simple_usuario);
			$this->session->set_userdata('logged_in', true);
			$this->session->set_userdata('reseller', $simple_usuario->id);
			$this->usuario = $simple_usuario;
			
			// Load language
			if (isset($simple_usuario->idioma)) {
				$this->session->set_userdata('lang', $simple_usuario->idioma);
				$this->lang->load('site', $simple_usuario->idioma);
			}
			
			// Log transfer
			$this->load->helper('file');
			$log_message = date('Y-m-d H:i:s') . " - MY_CONTROLLER TRANSFERRED FROM SIMPLE SESSION: User ID: " . $simple_usuario->id . "\n";
			write_file(FCPATH . 'application/logs/session_transfer.log', $log_message, 'a');
		}
		// Check standard CI session
		elseif ($this->session->has_userdata('usuario'))
		{
			$this->usuario = $this->session->userdata('usuario');
			
			// Ensure usuario is not empty or corrupted
			if (empty($this->usuario) || !is_object($this->usuario)) {
				$this->recover_session();
			}
			
			$this->lang->load('site', $this->usuario->idioma);
			$this->session->set_userdata('lang', $this->usuario->idioma);
		}
		elseif ($this->session->has_userdata('reseller'))
		{
			// Session lost usuario data but still has reseller ID
			// Try to recover it
			$this->recover_session();
		}
		
		elseif ($this->input->get('lang'))
		{
			switch ($this->input->get('lang'))
			{
				case 'en':
					$this->session->set_userdata('lang', 'english');
					break;
				default:
					$this->session->set_userdata('lang', 'spanish');
					break;
			}
			
			$this->lang->load('site', $this->session->userdata('lang'));
		}
		
		elseif (!$this->session->has_userdata('lang'))
		{
			// load the library
			$this->load->library('geolib/Geolib');
		
			$geo = new Geolib();
		
			$data = $this->geolib->user_agent();
		
			switch ($data['accept_langs'][0])
			{
				case 'en-us':
					$this->session->set_userdata('lang', 'english');
					break;
				default:
					$this->session->set_userdata('lang', 'spanish');
					break;
			}
					
			$this->lang->load('site', $this->session->userdata('lang'));
		}
		
		else
		{
			$this->lang->load('site', $this->session->userdata('lang'));
		}
	}
	
	/**
	 * Log the current session state for debugging
	 */
	private function log_session_state()
	{
		// Only log if we're not in the debug controller to avoid infinite recursion
		if ($this->router->class != 'debug') {
			$this->load->helper('file');
			
			$log_file = FCPATH . 'application/logs/session_monitor.log';
			$log_message = date('Y-m-d H:i:s') . " | " . $this->router->class . "/" . $this->router->method . " | ";
			$log_message .= "SID: " . substr(session_id(), 0, 8) . " | ";
			$log_message .= "Usuario: " . ($this->session->has_userdata('usuario') ? 'Y' : 'N') . " | ";
			$log_message .= "Logged: " . ($this->session->has_userdata('logged_in') ? 'Y' : 'N') . " | ";
			$log_message .= "Reseller: " . ($this->session->has_userdata('reseller') ? $this->session->userdata('reseller') : 'N') . "\n";
			
			write_file($log_file, $log_message, 'a');
		}
	}
	
	/**
	 * Attempt to recover a lost session
	 */
	private function recover_session()
	{
		// First try to recover from simple session if available
		if (class_exists('Simple_session')) {
			$this->load->library('simple_session');
			if ($this->simple_session->has('usuario')) {
				$user = $this->simple_session->get('usuario');
				if ($user && isset($user->id)) {
					// Log the recovery attempt
					$this->load->helper('file');
					$log_message = date('Y-m-d H:i:s') . " - SIMPLE SESSION RECOVERY: User ID: " . $user->id . "\n";
					write_file(FCPATH . 'application/logs/session_recovery.log', $log_message, 'a');
					
					// Store in CI session
					$this->session->set_userdata('usuario', $user);
					$this->session->set_userdata('logged_in', true);
					$this->session->set_userdata('reseller', $user->id);
					
					// Use object in this request
					$this->usuario = $user;
					
					return true;
				}
			}
		}
		
		// If simple session recovery failed, try from reseller ID
		if ($this->session->has_userdata('reseller')) {
			$reseller_id = $this->session->userdata('reseller');
			
			// Load necessary models
			$this->load->model('user_model');
			$this->load->model('sys_model');
			
			// Get user data
			$user = $this->user_model->getUserInfo($reseller_id);
			
			if ($user && isset($user->id)) {
				// Log the recovery attempt
				$this->load->helper('file');
				$log_message = date('Y-m-d H:i:s') . " - SESSION RECOVERY: User ID: " . $user->id . "\n";
				write_file(FCPATH . 'application/logs/session_recovery.log', $log_message, 'a');
				
				// Use the safe storage method
				$result = $this->store_user_in_session($user);
				
				// Use object in this request
				$this->usuario = $user;
				
				return $result;
			}
		}
		
		// If recovery failed, redirect to login
		$this->session->sess_destroy();
		redirect(base_url('user/login'));
		return false;
	}
	
	public function is_logged_in($perfil = null)
    {
	    $user = $this->session->userdata('usuario');
	    
	    if (isset($user))
	    {
		    if (isset($perfil))
			{
				if ($perfil == $user->perfil)
				{
					return true;
			    }
			    else
			    {
				    return false;
			    }
			}
			else
			{
				return true;
			}
	    }
	    else
	    {
	        return false;
        }
    }
    
    
    public function trackUri()
    {
	    // models
		$this->load->model('user_model');
		
		$this->user_model->trackUri();
	}
	
	
	public function getUri()
    {
	    // models
		$this->load->model('user_model');
		
		return $this->user_model->getUri();
	}
	
	
	public function tema()
    {
	    if (isset($this->session->userdata('config')['tema']))
	    {
			$res = $this->session->userdata('config')['tema'];
	    }
	    
	    return (!empty($res)) ? $res : null;
    }
    
    /**
     * Check if session needs repairing (session exists but user data is missing)
     * This helps recover from partial session loss
     */
    protected function check_session_integrity()
    {
        // Check if we need to recover from simple session
        if (class_exists('Simple_session')) {
            $this->load->library('simple_session');
            $simple_usuario = $this->simple_session->get('usuario');
            
            if ($simple_usuario && (!$this->session->has_userdata('usuario') || empty($this->session->userdata('usuario')))) {
                // We have usuario in simple session but not in CI session - restore it
                $this->session->set_userdata('usuario', $simple_usuario);
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('reseller', $simple_usuario->id);
                $this->usuario = $simple_usuario;
                
                // Log recovery
                $this->load->helper('file');
                $log_message = date('Y-m-d H:i:s') . " - INTEGRITY RECOVERY FROM SIMPLE SESSION: User ID: " . $simple_usuario->id . "\n";
                write_file(FCPATH . 'application/logs/session_integrity.log', $log_message, 'a');
                
                return true;
            }
        }
        
        // If we have a session ID but no user data, try to restore it
        if ($this->session->session_id && !$this->session->has_userdata('usuario') && $this->session->has_userdata('reseller')) {
            $reseller_id = $this->session->userdata('reseller');
            if ($reseller_id) {
                $this->load->model('user_model');
                $user = $this->user_model->getUserInfo($reseller_id);
                if ($user && $user->estado > 0) {
                    // Restore the session data
                    $this->load->model('sys_model');
                    $this->session->set_userdata('logged_in', true);
                    $this->session->set_userdata('usuario', $user);
                    $this->session->set_userdata('servicios', $this->user_model->getUserServicios($reseller_id));
                    $this->session->set_userdata('menu', $this->sys_model->menu($user->grupo, $user->id_perfil, $user->id));
                    $this->session->set_userdata('config', $this->user_model->getUserConfig($user->id_empresa));
                    
                    // Force session write
                    session_write_close();
                    session_start();
                    return true;
                }
            }
        }
        return false;
    }

	/**
	 * Safely store user object in session
	 * 
	 * @param object $user User object to store
	 * @return bool Success
	 */
	protected function store_user_in_session($user)
	{
		if (!is_object($user) || empty($user)) {
			return false;
		}
		
		// Load required models if not already loaded
		if (!isset($this->user_model)) {
			$this->load->model('user_model');
		}
		
		if (!isset($this->sys_model)) {
			$this->load->model('sys_model');
		}
		
		// Remove any potential circular references
		$user_copy = clone $user;
		$user_serialized = serialize($user_copy);
		$user_unserialized = unserialize($user_serialized);
		
		// Store directly in $_SESSION
		$_SESSION['usuario'] = $user_unserialized;
		$_SESSION['logged_in'] = true;
		$_SESSION['reseller'] = $user->id;
		$_SESSION['servicios'] = $this->user_model->getUserServicios($user->id);
		$_SESSION['menu'] = $this->sys_model->menu($user->grupo, $user->id_perfil, $user->id);
		$_SESSION['config'] = $this->user_model->getUserConfig($user->id_empresa);
		
		// Also set in CI session data
		$this->session->set_userdata('usuario', $user_unserialized);
		$this->session->set_userdata('logged_in', true);
		$this->session->set_userdata('reseller', $user->id);
		
		// Make sure the usuario property is set for this request
		$this->usuario = $user_unserialized;
		
		// Force write to disk only if headers haven't been sent
		if (!headers_sent()) {
			@session_write_close();
			@session_start();
		} else {
			log_message('error', 'store_user_in_session: Cannot manipulate session - headers already sent');
		}
		
		// Log the action
		$this->load->helper('file');
		$log_message = date('Y-m-d H:i:s') . " - STORE USER: User ID: " . $user->id . "\n";
		write_file(FCPATH . 'application/logs/session_store.log', $log_message, 'a');
		
		return true;
	}

}
