<?php defined('BASEPATH') or exit('No direct script access allowed');


class Home extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('emailer_model');
			
			$data['detalle'] = array(	'creditos'=>10,
										'utilizados'=>45,
										'restantes'=>16,
										'mensajes'=>60,
										'listas'=>33,
										'enviados'=>76,	
										'suscriptores'=>89
									);


			$this->load->view('/header');
			$this->load->view('/emailer/index', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}