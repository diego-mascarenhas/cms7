<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Balance extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('movimiento_model');
			
			
			$data['balance'] = $this->movimiento_model->getBalance();
			
			
			$this->load->view('/header');
			$this->load->view('/micuenta/balance', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}