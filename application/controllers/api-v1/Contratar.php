<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Contratar extends REST_Controller {

	public function index_post()
	{
		// models
		$this->load->model('empresa_model');
		$this->load->model('servicio_model');
		$this->load->model('contacto_model');
		$this->load->model('comunicacion_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		if (!$this->post('id_contacto'))
		{
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
			$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
			$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]|is_unique[contactos.username]');
			$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
			$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
		}
		else
		{
			$contacto = $this->contacto_model->getContactoDetalleRaw($this->post('id_contacto'));
		}
		
		if (empty($contacto['id_empresa']))
		{
			if ($this->post('empresa'))
			{
				$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
			}
		}
		
		$this->form_validation->set_rules('web', 'Web', 'trim|valid_url');
		$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|min_length[10]');

		$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|integer');
		
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();
			
			// Empresa
			if (empty($contacto['id_empresa']))
			{
				$empresa['empresa'] = $this->post('empresa');
				$empresa['web'] = $this->post('web');
				$empresa['observaciones'] = $this->post('observaciones');
				
				$data['empresa']['id'] = $this->empresa_model->ingresarEmpresa($empresa);
			}
			else
			{
				$data['empresa']['id'] = $contacto['id_empresa'];
			}

			
			if (!isset($data['empresa']['error']))
			{
				// Contacto
				if (empty($contacto['id']))
				{
					$contacto['id_empresa'] = $servicio['id_empresa'];
					$contacto['nombre'] = $this->post('nombre');
					$contacto['apellido'] = $this->post('apellido');
					$contacto['email'] = $this->post('email');
					$contacto['telefono'] = $this->post('telefono');
					$contacto['area_privada'] = $this->post('area_privada');
					$contacto['username'] = $this->post('username');
					$contacto['password'] = $this->post('password');
					$contacto['timezone'] = $this->post('timezone');
					$contacto['idioma'] = $this->post('idioma');
					$contacto['estado'] = 6; // sin confirmar
					
					$data['contacto'] = $this->contacto_model->ingresarContacto($contacto);
				}
				else
				{
					$data['contacto'] = $contacto['id'];
				}
				
				
				// Servicio
				$servicio['id_empresa'] = $data['empresa']['id'];
				$servicio['id_categoria'] = $this->post('id_categoria');
				$servicio['frecuencia'] = $this->post('frecuencia');
				$servicio['username'] = $data['contacto'];
				
				$servicio['data'] = json_encode(array_merge(array('id_contacto'=>$data['contacto']), $this->post()));
				
				$data['servicio']['id'] = $this->servicio_model->ingresarServicio($servicio);
				
				
				if (!isset($data['servicio']['error']))
				{
					$this->empresa_model->modificarEmpresa($data['empresa']['id'], array('id_contacto'=>$data['contacto']));
					
					$this->contacto_model->modificarContacto($data['contacto'], array('id_empresa'=>$data['empresa']['id']));
					
					$data['comunicacion'] = $this->servicio_model->comunicarServicio($data['servicio']['id']);
					$this->comunicacion_model->ingresarComunicacion($data['contacto'], 12, $data['servicio']['id'], $data['comunicacion']);
				}
			}
			
			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = $this->db->_error_message();
			}
			else
			{
				$this->db->trans_commit();
			}
		}

		$this->response($data);
	}


}