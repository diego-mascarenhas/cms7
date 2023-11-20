<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Comunicaciones extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('comunicacion_model');

		if ($id)
		{
			$data = $this->comunicacion_model->getComunicacionDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->comunicacion_model->getComunicaciones($parametros);
		}

		$this->response($data);
	}
	
	
	public function index_post()
	{
		// models
		$this->load->model('comunicacion_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id_tipo', 'ID Tipo', 'trim|integer');
		$this->form_validation->set_rules('id_contacto', 'ID Contacto', 'trim|required|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->comunicacion_model->ingresarComunicacion($this->post('id_contacto'), $this->post('id_tipo'), $this->post('id_contacto'), ($this->post('data')) ? $this->post('data') : null);
			
			if ($this->post('enviar') == true) $this->comunicacion_model->enviarComunicacion($data['id']);
		}
		
		$this->response($data);
	}


}