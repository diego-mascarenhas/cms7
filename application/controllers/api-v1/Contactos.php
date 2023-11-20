<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Contactos extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('contacto_model');

		if ($id)
		{
			$data = $this->contacto_model->getContactoDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->contacto_model->getContactos($parametros);
		}

		$this->response($data);
	}


	public function index_post()
	{
		// models
		$this->load->model('contacto_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('celular', 'Celular', 'trim');
		
		$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
		$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
		$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
		
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->contacto_model->ingresarContacto($this->post());

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


	public function index_put($id = null)
	{
		// models
		$this->load->model('contacto_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('celular', 'Celular', 'trim');
		
		$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
		$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
		$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
		
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->contacto_model->modificarContacto($id, $this->put());
			
			$row = $this->contacto_model->getContactoDetalleRaw($id);
			if (isset($row['username']) && !isset($row['hash'])) $this->contacto_model->modificarHash($id, $this->put('username'));
		}
		
		$this->response($data);
	}


}