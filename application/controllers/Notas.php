<?php defined('BASEPATH') or exit('No direct script access allowed');


class Notas extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// models
		$this->load->model('nota_model');
	}

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// helpers and libraries
			$this->load->library('pagination');
			$this->load->helper('text');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['per_page'] = 24;

			$data['notas'] = $this->nota_model->getNotas($parametros);
			
			$config['total_rows'] = $this->nota_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/notas/index', $data) : $this->load->view('/notas/empty', $data);
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
			$this->load->model('ticket_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_tipo', 'Tipo', 'trim|required|integer');
			$this->form_validation->set_rules('id_referencia', 'Referencia', 'trim|required|integer');
			
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_tipo'] = $this->input->get('id_tipo');
				$default['id_referencia'] = $this->input->get('id_referencia');
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['agentes'] = $this->ticket_model->comboAgentes(null, '--- Seleccione un responsable ---');
	
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/plugins/summernote/summernote-bs3.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/notas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->nota_model->ingresarNota($this->input->post()))
				{
					$this->session->set_userdata('notas', $this->nota_model->getNotas(array('responsable'=>$this->usuario->id)));
					
					redirect(base_url($this->getUri()));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/notas/error/');
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
			// models
			$this->load->model('ticket_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->nota_model->getNotaDetalleRaw($id);
				$data['agentes'] = $this->ticket_model->comboAgentes(null, '--- Seleccione un responsable ---');
	
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/plugins/summernote/summernote-bs3.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/notas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->nota_model->modificarNota($id, $this->input->post()))
				{
					$this->session->set_userdata('notas', $this->nota_model->getNotas(array('responsable'=>$this->usuario->id)));
					
					redirect(base_url($this->getUri()));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/notas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->nota_model->getNotaDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/notas/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'notas'))
				{
					$res = $this->sys_model->eliminar($id, 'notas');
					
					$this->session->set_userdata('notas', $this->nota_model->getNotas(array('responsable'=>$this->usuario->id)));
					
					redirect(base_url('notas/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/notas/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}