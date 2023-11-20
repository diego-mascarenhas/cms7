<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Newsletters extends REST_Controller {
 
	public function index_get($id = null)
	{
		$this->load->model('newsletter_model');

		if ($id)
		{
			$data = $this->newsletter_model->getNewsletterDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->newsletter_model->getNewsletters($parametros);
		}

		$this->response($data);
	}


	public function index_post()
	{
		// models
		$this->load->model('newsletter_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('remitente', 'Remitente', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('email_respuesta', 'Email de respuesta', 'trim|valid_email');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->newsletter_model->ingresarNewsletter($this->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
			}
		}
		
		$this->response($data);
	}


	public function index_put($id)
	{
		// models
		$this->load->model('newsletter_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('remitente', 'Remitente', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('email_respuesta', 'Email de respuesta', 'trim|valid_email');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->newsletter_model->modificarNewsletter($id, $this->put());
		}
		
		$this->response($data);
	}



}