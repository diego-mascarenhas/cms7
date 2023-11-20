<?php defined('BASEPATH') or exit('No direct script access allowed');


class Auth
{
	private $_CI;

	public function __construct()
	{
		$this->_CI =& get_instance();
	}
	

	public function login($user, $pass)
	{
		$this->_CI->load->model('user_model');
		
		if ($this->_CI->user_model->userLogin($user, $pass))
		{
			$id = $this->_CI->user_model->getUserIdFromUsername($user);
			
			switch ((isset($_SERVER['HTTP_AUTH_MODE'])) ? $_SERVER['HTTP_AUTH_MODE'] : null)
			{
				case 'servicios':
					$user = $this->_CI->user_model->getUserInfo($id);
					$user->servicios = $this->_CI->user_model->getUserServicios($id);
					break;
				default:
					$user = $this->_CI->user_model->getUserInfo($id);
					break;
			}

			$this->_CI->usuario = $user;
			return true;
		}
		else
		{
			return false;
		}
	}


}