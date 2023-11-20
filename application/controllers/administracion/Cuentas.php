<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Cuentas extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('cuenta_model');
			
			// helpers and libraries
			$this->load->library('pagination');
						
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');

			$data['cuentas'] = $this->cuenta_model->getCuentas($parametros);
						
			$config['total_rows'] = $this->cuenta_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/cuentas/index', $data) : $this->load->view('/administracion/cuentas/empty');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('cuenta_model');


			$data['detalle'] = $this->cuenta_model->getCuentaDetalle($id);
			
			$this->load->view('/header');
			$this->load->view('/administracion/cuentas/detalle', $data);
			$this->load->view('/debug', array('debug'=>array($data['detalle'])));
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
			$this->load->model('cuenta_model');
				
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('titular', 'Titular', 'trim|required');
			$this->form_validation->set_rules('id_documento_tipo', 'Tipo de documento', 'trim|required|integer');
			$this->form_validation->set_rules('numero_documento', 'Número de documento', 'trim|required|integer|min_length[7]|max_length[11]');
			$this->form_validation->set_rules('cbu', 'CBU', 'trim|required|integer|min_length[22]|max_length[22]');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = (object) ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['documentos_tipo'] = array(1=>'C.U.I.T.', 2=>'C.U.I.L.', 3=>'DNI');
	
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/administracion/cuentas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->cuenta_model->ingresarCuenta($this->input->post()))
				{
					redirect(base_url('administracion/cuentas/detalle/' . $data['id']));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/cuentas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function modificar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// model
			$this->load->model('cuenta_model');
	
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('titular', 'Titular', 'trim|required');
			$this->form_validation->set_rules('id_documento_tipo', 'Tipo de documento', 'trim|required|integer');
			$this->form_validation->set_rules('numero_documento', 'Número de documento', 'trim|required|integer|min_length[7]|max_length[11]');
			$this->form_validation->set_rules('cbu', 'CBU', 'trim|required|integer|min_length[22]|max_length[22]');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->cuenta_model->getCuentaDetalle($id);
				$data['documentos_tipo'] = array(1=>'C.U.I.T.', 2=>'C.U.I.L.', 3=>'DNI');
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/administracion/cuentas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->cuenta_model->modificarCuenta($id, $this->input->post()))
				{
					redirect(base_url('administracion/cuentas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/cuentas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}