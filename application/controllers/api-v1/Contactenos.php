<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Contactenos extends REST_Controller {

	public function index_post()
	{
		// models
		$this->load->model('ticket_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$valores = $this->post();
			$valores['id_area'] = 3;
			$valores['asunto'] = 'Contacto desde formulario web';
			
			$this->db->trans_begin();

			$data = $this->ticket_model->ingresarTicket($valores);
			$data['id_item'] = $this->ticket_model->ingresarTicketItem($data['id'], $this->post());
			if ($this->usuario->perfil != 'reseller') $data['id_contacto'] = $this->ticket_model->asociarContacto($data['id'], $this->usuario->id);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
				
				$this->ticket_model->notificarNuevoTicket($data['id_item']);
			}
		}
		
		$this->response($data);
	}


}