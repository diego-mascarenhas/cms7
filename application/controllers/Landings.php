<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Landings extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// models
		$this->load->model('landing_model');
	}

	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['listado'] = $this->landing_model->getLandings();
	
			$this->load->view('header');
			$this->load->view('landings/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			$data['detalle'] = $this->landing_model->getLandingDetalle($id);
			$data['contactos'] = $this->landing_model->getLandingConversiones($id);

			if (isset($data['detalle']))
			{
				$this->load->view('/header');
				$this->load->view('/landings/detalle', $data);
				$this->load->view('/footer');
			}
			else
			{
				$this->load->view('/401');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				$data['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				
				
				$this->load->view('header');
				$this->load->view('landings/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->landing_model->ingresarLanding($this->input->post()))
		        {
			        redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'landings/detalle/' . $data['id']));
		        }
		        else
		        {
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/landings/error/');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	
	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				$data['detalle'] = $this->landing_model->getLandingDetalleRaw($id);
				
				
				$this->load->view('header');
				$this->load->view('landings/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->landing_model->modificarLanding($id, $this->input->post()))
		        {redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'landings/detalle/' . $id));
		        }
		        else
		        {
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/landings/error/');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	
	public function duplicar($id)
	{
		if ($this->is_logged_in())
		{
			$valores = $this->landing_model->getLandingDetalle($id);
			$valores['estado'] = 1;
			
			unset($valores['fecha_alta']);
			unset($valores['fecha_modificacion']);
			unset($valores['username_modificacion']);
			
			if ($data = $this->landing_model->ingresarLanding($valores))
	        {
		        redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'landings/detalle/' . $data['id']));
	        }
	        else
	        {
				$data['error'] = 'Ha habido un problema, por favor intenta más tarde';

				$this->load->view('/landings/error/');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
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
				$data['detalle'] = $this->landing_model->getLandingDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/landings/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'landings'))
				{
					$res = $this->sys_model->eliminar($id, 'landings');
					
					redirect(base_url('landings'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/landings/error/');
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