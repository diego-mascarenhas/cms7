<?php defined('BASEPATH') or exit('No direct script access allowed');


class Listas extends MY_Controller {

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
			
			$data['listas'] = $this->emailer_model->getNewslettersListas($parametros);
			
			$config['total_rows'] = $this->emailer_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/listas/index', $data) : $this->load->view('/emailer/listas/empty', $data);
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
			$this->load->library('pagination');
			
			
			$data['detalle'] = $this->emailer_model->getNewslettersListaDetalle($id);
			
			
			//$parametros = null;
			
			//$parametros['id_empresa'] = 1; // revision alpha

			//$parametros['contactos_estados'][] = 2; // Activo
			//$parametros['contactos_estados'][] = 3; // Online
			
			//$parametros['contactos_categorias'][] = 3; // Staff
			//$parametros['contactos_categorias'][] = 4; // Tester
			
			//$parametros['servicios_categorias'][] = 200; // Hosting Standard
			//$parametros['servicios_categorias'][] = 201; // Hosting Enterprise
			//$parametros['servicios_categorias'][] = 202; // Hosting Premium
			//$parametros['servicios_categorias'][] = 204; // Cloud
			
			//$parametros['servicios_estados'][] = 4; // Activo
			
			//$data['detalle']['filtros'] = json_encode($parametros);
			
			
			$data['contactos'] = $this->emailer_model->listaDinamica(json_decode($data['detalle']['filtros'], true));
			
			$config['total_rows'] = $this->emailer_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header');
			$this->load->view('/emailer/listas/detalle', $data);
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
		$this->form_validation->set_rules('lista', 'Lista', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$default['estado'] = 2;
			
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;

			
			$this->load->view('/header');
			$this->load->view('/emailer/listas/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->ingresarNewsletterLista($this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/listas/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/listas/detalle/' . $data['id']));
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
		$this->form_validation->set_rules('lista', 'Lista', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->emailer_model->getNewslettersListaDetalleRaw($id);
			
			// filtros
			$filtros = json_decode($data['detalle']['filtros'], true);
			
			$filtro = null;
			
			if (isset($filtros['contactos_categorias'])) $filtro['contactos_categorias'] = implode(',', $filtros['contactos_categorias']);
        	if (isset($filtros['contactos_estados'])) $filtro['contactos_estados'] = implode(',', $filtros['contactos_estados']);
        	
        	if (isset($filtros['servicios_categorias'])) $filtro['servicios_categorias'] = implode(',', $filtros['servicios_categorias']);
        	if (isset($filtros['servicios_estados'])) $filtro['servicios_estados'] = implode(',', $filtros['servicios_estados']);
			
			$data['detalle']['filtro'] = $filtro;

			
			$this->load->view('/header');
			$this->load->view('/emailer/listas/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();
			
			$post = $this->input->post();
			$filtros = $post['filtros'];
			
			$filtro['grupo'] = $this->usuario->grupo;
			if ($this->usuario->perfil != 'reseller') $filtro['id_empresa'] = $this->usuario->id_empresa;
			
			if (!empty($filtros['contactos_categorias'])) $filtro['contactos_categorias'] = explode(',', $filtros['contactos_categorias']);
        	if (!empty($filtros['contactos_estados'])) $filtro['contactos_estados'] = explode(',', $filtros['contactos_estados']);
        	
        	if (!empty($filtros['servicios_categorias'])) $filtro['servicios_categorias'] = explode(',', $filtros['servicios_categorias']);
        	if (!empty($filtros['servicios_estados'])) $filtro['servicios_estados'] = explode(',', $filtros['servicios_estados']);
        	
        	$post['filtros'] = json_encode($filtro, JSON_NUMERIC_CHECK);
        	
			
			$data = $this->emailer_model->modificarNewsletterLista($id, $post);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/listas/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/listas/detalle/' . $id));
			}
		}
	}

}