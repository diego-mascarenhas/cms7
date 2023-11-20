<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Empresas extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('sys_model');
			$this->load->model('configuracion_model');
			
			// helpers and libraries
			$this->load->library('pagination');
	

			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$parametros['id_empresa'] = $this->input->get('id_empresa');
			
			$data['detalle']['id_empresa'] = $parametros['id_empresa'];
			
			$data['tipos'] = $this->sys_model->comboConfigTipo();
			$data['config'] = $this->configuracion_model->getConfig($parametros);
			
			$config['total_rows'] = $this->configuracion_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
				
				
			$this->load->view('/header');
			$this->load->view('/configuracion/administracion/empresas/index', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('sys_model');
			$this->load->model('configuracion_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('id_tipo', 'Tipo', 'trim|required|integer');
			$this->form_validation->set_rules('value', 'Valor', 'trim|required|min_length[3]');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['tipos'] = $this->sys_model->comboConfigTipo();
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/configuracion/administracion/empresas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if (!$existe = $this->configuracion_model->verificarSiExiste($this->input->post('id_empresa'), $this->input->post('id_tipo')))
				{
					$data = $this->configuracion_model->ingresarConfig($this->input->post());
				}
				else
				{
					$data = $this->configuracion_model->modificarConfig($existe, $this->input->post());
				}
				
				if (!isset($data['error']))
				{
					redirect(base_url('configuracion/administracion/empresas/?id_empresa=' . $this->input->post('id_empresa')));
				}
				else
				{
					$this->load->view('/configuracion/administracion/empresas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}