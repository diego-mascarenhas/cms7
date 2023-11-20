<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Micuenta extends REST_Controller {

	public function index_get()
	{
		// models
		$this->load->model('empresa_model');

		$data = $this->empresa_model->getMiCuenta();
		
		$this->response($data);
	}
	
	
	public function index_put()
	{
		// models
		$this->load->model('empresa_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$valores = $this->put();
		$valores['cuit'] = preg_replace('/\D/', '', $this->put('cuit'));
		
		$this->form_validation->set_data($valores);
		
		// set validation rules
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('id_empresa', 'ID Empresa', 'trim|required|integer');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|min_length[8]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		
		$this->form_validation->set_rules('id_condicion_iva', 'Condición fiscal', 'trim|required|integer');
		
		if ($this->put('id_condicion_iva') == 3) // Consumidor Final
		{
			$this->form_validation->set_rules('cuit', 'DNI', 'trim|required|valid_dni');
		}
		elseif ($this->put('id_condicion_iva') == 2) // Monotributista
		{
			$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
		}
		else // Responsable Inscripto, Exento
		{
			$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
			$this->form_validation->set_rules('razon_social', 'Razón Social', 'trim|required|min_length[3]');
		}
		
		$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');
		$this->form_validation->set_rules('titular', 'Titular', 'trim|min_length[3]');
		$this->form_validation->set_rules('cuenta_documento', 'Ducumento del titular de la cuenta', 'trim');
		$this->form_validation->set_rules('cbu', 'CBU', 'trim|min_length[22]');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->empresa_model->actualizarMiCuenta($this->put());
		}
		
		$this->response($data);
	}


}