<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('/configuracion/home');
			$this->load->view('/footer');
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}