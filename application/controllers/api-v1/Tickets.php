<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Tickets extends REST_Controller {
 
	public function index_get($id = null)
	{
		$this->load->model('ticket_model');

		if ($id)
		{
			$data = $this->ticket_model->getTicketDetalle($id);
			$data['items'] = $this->ticket_model->getTicketItems($data['id']);
			$data['contactos'] = $this->ticket_model->getTicketContactosAsociados($data['id']);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->ticket_model->getTickets($parametros);
		}

		$this->response($data);
	}


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
		$this->form_validation->set_rules('id_servicio', 'Servicio', 'trim|integer');
		$this->form_validation->set_rules('id_area', 'Area', 'trim|integer');
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('prioridad', 'Prioridad', 'trim|integer');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('visibilidad', 'visibilidad', 'trim|integer');
		$this->form_validation->set_rules('id_origen', 'origen', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->ticket_model->ingresarTicket($this->post());
			$data['item'] = $this->ticket_model->ingresarTicketItem($data['id'], $this->post());
			if ($this->usuario->perfil != 'reseller') $data['id_contacto'] = $this->ticket_model->asociarContacto($data['id'], $this->usuario->id);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
				
				$this->ticket_model->notificarNuevoTicket($data['item']['id']);
			}
		}
		
		$this->response($data);
	}


	public function index_put($id)
	{
		// models
		$this->load->model('ticket_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('id_servicio', 'Servicio', 'trim|integer');
		$this->form_validation->set_rules('id_area', 'Area', 'trim|integer');
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|min_length[4]');
		$this->form_validation->set_rules('prioridad', 'Prioridad', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->ticket_model->modificarTicket($id, $this->put());
		}
		
		$this->response($data);
	}
	
	
	public function item_post($id)
	{
		// models
		$this->load->model('ticket_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());
			
		// set validation rules
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->ticket_model->ingresarTicketItem($id, $this->post());
			if (!$this->ticket_model->verificarAsociacionDeContacto($id, $this->usuario->id)) $this->ticket_model->asociarContacto($id, $this->usuario->id);
			
			$this->ticket_model->cambiarEstado($id, 3);
			$this->ticket_model->notificarNuevaRespuesta($data['id']);
		}

		$this->response($data);
	}


}