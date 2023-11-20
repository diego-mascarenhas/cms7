<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Voip extends REST_Controller {

	public function index_get() // TEST
	{
		$this->load->model('voip_model');

		
		$data['key'] = $this->voip_model->getVoipKey();
		
		$data['credito'] = $this->voip_model->getCredito();
		
		$data['PBK'] = $this->voip_model->getValorDeLlamadaDesdePBX(1);
		
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function index_post()
	{
		// models
		$this->load->model('voip_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());
		
		// set validation rules
		$this->form_validation->set_rules('codigo_pais', 'Código de País', 'trim|integer');
		$this->form_validation->set_rules('codigo_area', 'Código de Area', 'trim|integer');
		$this->form_validation->set_rules('numero', 'Número de Teléfono', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('id_contacto', 'ID Contacto', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->voip_model->llamar($this->post());
		}
		
		$this->response($data);
	}


}