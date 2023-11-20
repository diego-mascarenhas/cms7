<?php defined('BASEPATH') or exit('No direct script access allowed');


class Categorias extends MY_Controller {

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
			
			$data['categorias'] = $this->emailer_model->getNewslettersCategorias($parametros);
			
			$config['total_rows'] = $this->emailer_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/categorias/index', $data) : $this->load->view('/emailer/categorias/empty', $data);
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
			
			// helpers and libraries
			$this->load->helper('text');
			

			$data['detalle'] = $this->emailer_model->getNewslettersCategoriaDetalle($id);
			
		
			$this->load->view('/header');
			$this->load->view('/emailer/categorias/detalle', $data);
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

		// set validation rules
		$this->form_validation->set_rules('categoria', 'Categoría', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$default['estado'] = 2;
			
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;

			
			$this->load->view('/header');
			$this->load->view('/emailer/categorias/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->ingresarNewsletterCategoria($this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/categorias/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/categorias/detalle/' . $data['id']));
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

		// set validation rules
		$this->form_validation->set_rules('categoria', 'Categoría', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->emailer_model->getNewslettersCategoriaDetalleRaw($id);

			
			$this->load->view('/header');
			$this->load->view('/emailer/categorias/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->modificarNewsletterCategoria($id, $this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/categorias/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/categorias/detalle/' . $id));
			}
		}
	}


}