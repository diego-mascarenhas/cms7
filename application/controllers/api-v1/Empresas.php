<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Empresas extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('empresa_model');

		if ($id)
		{
			$data = $this->empresa_model->getEmpresaDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->empresa_model->getEmpresas($parametros);
		}

		$this->response($data);
	}


	public function index_post()
	{
		// models
		$this->load->model('empresa_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|integer');
		$this->form_validation->set_rules('referido', 'Referido', 'trim|integer');
		$this->form_validation->set_rules('domicilio', 'Domicilio', 'trim|min_length[3]');
		$this->form_validation->set_rules('telefono', 'telefono', 'trim');
		$this->form_validation->set_rules('web', 'Web', 'trim|valid_url');
		$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|min_length[10]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->empresa_model->ingresarEmpresa($this->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear la empresa, por favor intenta más tarde';
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
		$this->load->model('empresa_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|integer');
		$this->form_validation->set_rules('referido', 'Referido', 'trim|integer');
		$this->form_validation->set_rules('domicilio', 'Domicilio', 'trim|min_length[3]');
		$this->form_validation->set_rules('telefono', 'telefono', 'trim');
		$this->form_validation->set_rules('web', 'Web', 'trim|valid_url');
		$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|min_length[10]');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->empresa_model->modificarEmpresa($id, $this->put());
		}
		
		$this->response($data);
	}


}