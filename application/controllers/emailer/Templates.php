<?php defined('BASEPATH') or exit('No direct script access allowed');


class Templates extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('emailer_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['templates'] = $this->emailer_model->getTemplates($parametros);
			
						
			$config['total_rows'] = $this->emailer_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/templates/index', $data) : $this->load->view('/emailer/templates/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('emailer_model');
			
			
			$data['detalle'] = $this->emailer_model->getTemplateDetalle($id);
			
			
			$this->load->view('/header');
			$this->load->view('/emailer/templates/detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar()
	{
		// models
		$this->load->model('emailer_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		$this->load->helper('file');

		// set validation rules
		$this->form_validation->set_rules('template', 'Template', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('codigo', 'Código', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$default['estado'] = 2;
			
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
			
			
			$this->load->view('/header');
			$this->load->view('/emailer/templates/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->ingresarTemplate($this->input->post());

			if ($this->db->trans_status() === false || !write_file(FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/template-' . $data['id'] . '.php', $this->input->post('codigo')))
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el template, por favor intenta más tarde';

				$this->load->view('/emailer/templates/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/templates/detalle/' . $data['id']));
			}
		}
	}
	
	
	public function modificar($id)
	{
		// models
		$this->load->model('emailer_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		$this->load->helper('file');

		// set validation rules
		$this->form_validation->set_rules('template', 'Template', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('codigo', 'Código', 'trim|required|min_length[6]');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->emailer_model->getTemplateDetalleRaw($id);
		
			
			$this->load->view('/header');
			$this->load->view('/emailer/templates/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->modificarTemplate($id, $this->input->post());
						
			if ($this->db->trans_status() === false || !write_file(FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/template-' . $id . '.php', $this->input->post('codigo')))
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el template, por favor intenta más tarde';

				$this->load->view('/emailer/templates/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/templates/detalle/' . $id));
			}
		}
	}
	
	
	public function ver($id)
	{
		// helpers and libraries
		$this->load->helper('file');
		
		echo read_file(FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/template-' . $id . '.php');
	}
	

}