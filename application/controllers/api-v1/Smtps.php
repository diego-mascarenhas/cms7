<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Smtps extends REST_Controller {

	public function index_put($id = null)
	{
		// models
		$this->load->model('smtp_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('mailq', 'Mailq', 'trim|integer');

		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		
		if ($this->put('host')) $id = $this->smtp_model->getSmtpIdFromHost($this->put('host'));

		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		elseif (empty($id))
		{
			$data['error'] = 'No se ha encontrado ningún host';
		}
		else
		{
			$data = $this->smtp_model->modificarSmtp($id, $this->put());
		}
		
		$this->response($data);
	}


}