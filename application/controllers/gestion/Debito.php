<?php defined('BASEPATH') or exit('No direct script access allowed');


class Debito extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('movimiento_model');
			
			$data['debitos'] = $this->movimiento_model->generarDebito();
			$data['total'] = $this->movimiento_model->totalDebito();
			
			$this->load->view('/header');
			$this->load->view('/gestion/debito/index', $data);
			$this->load->view('/footer');
		}
		
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('user'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('guest'))
		{
			redirect(base_url('multimedia'));
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}