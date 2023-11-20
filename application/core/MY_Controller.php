<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if ($this->session->has_userdata('usuario'))
		{
			$this->usuario = $this->session->userdata('usuario');
			$this->lang->load('site', $this->usuario->idioma);
			
			$this->session->set_userdata('lang', $this->usuario->idioma);
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


}
