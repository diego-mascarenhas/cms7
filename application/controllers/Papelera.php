<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Papelera extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('admin'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('papelera_model');
			

			$data['eliminados'] = $this->papelera_model->getEliminados();
			
			$this->load->view('/header');
			$this->load->view('/papelera/index', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}