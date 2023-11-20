<?php defined('BASEPATH') or exit('No direct script access allowed');


class Archivos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// models
		$this->load->model('archivo_model');
	}

	
	public function ingresar($id_referencia, $id_padre)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('ticket_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_padre', 'Padre', 'trim|required|integer');
			$this->form_validation->set_rules('id_referencia', 'Referencia', 'trim|required|integer');
			
			$this->form_validation->set_rules('upload', 'Upload', 'required');
			
			if (empty($_FILES['file']['name']))
			{
				$this->form_validation->set_rules('file', 'Archivo', 'required');
			}
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_padre'] = $id_padre;
				$default['id_referencia'] = $id_referencia;
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
	
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/archivos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				set_time_limit(0);
				ini_set('memory_limit', -1);
				
				// models
				$this->load->model('multimedia_model');
				
				$config['upload_path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/archivos/';
				
				if (!is_dir($config['upload_path']))
				{
					if (!mkdir($config['upload_path'], 0777, TRUE))
					{
						exit('No se puede crear la carpeta.');									
					}
				}
				
				$config['encrypt_name'] = true;
				$config['file_ext_tolower'] = 'true';
				$config['allowed_types'] = implode('|', $this->multimedia_model->getMediaArchivosPermitidos());
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('file'))
				{
						$error = array('error' => $this->upload->display_errors());
						echo '<pre>' . print_r($error, true) . '</pre>';
				}
				else
				{
					$upload_data = $this->upload->data();
					
					$data['id_padre'] = $this->input->post('id_padre');
					$data['id_referencia'] = $this->input->post('id_referencia');
					$data['estado'] = $this->input->post('estado');
					
					$data['id_tipo'] = $this->multimedia_model->getMediaTipoId($upload_data['file_ext']);
					$data['nombre'] = $upload_data['orig_name'];
					$data['archivo'] = $upload_data['file_name'];
					$data['peso'] = $upload_data['file_size'];
				
					if ($data = $this->archivo_model->ingresar($data))
					{
						redirect(base_url($this->getUri()));
					}
					else
					{
						redirect(base_url('multimedia/error'));
					}
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
}