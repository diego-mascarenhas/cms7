<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Registrarme extends REST_Controller {

	public function index_post()
	{
		// models
		$this->load->model('contacto_model');
		$this->load->model('comunicacion_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$valores = array(
			        'email' => $this->post('email'),
			        'username' => $this->post('email'),
			        'area_privada' => 3,
			        'estado' => 6
				);
		
		$this->form_validation->set_data($valores);

		// set validation rules
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]|is_unique[contactos.username]');
		
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data['contacto'] = $this->contacto_model->ingresarContacto($valores);
			
			$data['comunicacion'] = $this->contacto_model->comunicarContacto($data['contacto']);
			$this->comunicacion_model->ingresarComunicacion($data['contacto'], 8, $data['contacto'], $data['comunicacion']);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el contacto, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
			}
		}
		
		$this->response($data);
	}
	

}