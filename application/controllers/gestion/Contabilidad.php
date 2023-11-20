<?php defined('BASEPATH') or exit('No direct script access allowed');


class Contabilidad extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('contabilidad_model');
			$this->load->model('cuenta_model');
			$this->load->model('empresa_model');
			
			//$data['deudores'] = $this->empresa_model->getEmpresasDeudoras(45);
			
			$parametros['desde'] = '2020-01-01';
			$data['contabilidad'] = $this->contabilidad_model->getMovimientos();
			
			echo '<pre>' . print_r($data, true) . '</pre>';
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

	
}