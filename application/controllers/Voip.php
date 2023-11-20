<?php defined('BASEPATH') or exit('No direct script access allowed');


class Voip extends MY_Controller {

	public function index()
	{
		
	}
	
	
	public function llamar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('voip_model');
			$this->load->model('contacto_model');

			// helpers and libraries
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('codigo_pais', 'Código de País', 'trim|integer');
			$this->form_validation->set_rules('codigo_area', 'Código de Area', 'trim|integer');
			$this->form_validation->set_rules('numero', 'Número de Teléfono', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('id_contacto', 'ID Contacto', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->contacto_model->getContactoDetalle($id);
				$data['detalle']['codigo_pais'] = 54;
				$data['detalle']['codigo_area'] = 11;
				$data['detalle']['agente'] = $this->usuario->telefono;
				
				$this->load->view('/header');
				$this->load->view('/voip/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['detalle'] = $this->voip_model->llamar($this->input->post());
				
				$this->load->view('/header');
				$this->load->view('/voip/llamando', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}