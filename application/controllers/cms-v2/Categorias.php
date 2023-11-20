<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Informacion_model');
		$this->load->model('cms-v2/Configuracion_model');
	}

	public function index($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				//$this->trackUri();
				$this->config->set_item('language', $this->usuario->idioma);
				
				//$parametros['estado'] = 3;
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); } 
				$parametros['order_by'] = 'id';
				$parametros['order'] = 'DESC';
				
				if($this->input->get('tipo')) { $data['tipo'] = $parametros['tipo']; } else { $data['tipo'] = 'todos';}
				$data['listado'] = $this->Informacion_model->getCategorias($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/categorias', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	//Ingresar 
	public function ingresar($tipo = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->form_validation->set_rules('seccion', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
				$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
				if ($this->form_validation->run() === false)
				{
					$default['estado'] = 1;
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
					$parametros['tipo'] = $tipo;
				
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/categorias_form', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($data = $this->Informacion_model->ingresarCategoria($this->input->post()))
					{
			            redirect(base_url('cms-v2/categorias'));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
		
						$this->load->view('cms-v2/error/');
					}
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	//Modificar 
	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->config->set_item('language', $this->usuario->idioma);
				
				$this->form_validation->set_rules('seccion', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
				$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
				if ($this->form_validation->run() === false)
				{
					// form values
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Informacion_model->getCategoriaDetalleRaw($id);
					
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/categorias_form', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($data = $this->Informacion_model->modificarCategoria($id, $this->input->post()))
					{					
			            redirect(base_url('cms-v2/categorias/modificar/'.$id.'/'));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
		
						$this->load->view('cms-v2/error/');
					}
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
	
	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($data = $this->Informacion_model->duplicarCategoria($id))
		        {
					redirect(base_url('cms-v2/categorias'));
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenar($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				//$this->trackUri();
				$this->config->set_item('language', $this->usuario->idioma);
				
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); } 
				$parametros['estado'] = 3;
				$parametros['order_by'] = 'orden';
				$parametros['order'] = 'ASC';
				
				$data['item'] = $this->Informacion_model->getCategoriaTipoDetalle(9);
				$data['listado'] = $this->Informacion_model->getCategorias($parametros);
	
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/categorias_ordenar', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarCategorias()
	{
		$data = $this->Informacion_model->ordenarItems(json_decode($_POST['items']), 'con_secciones');
		echo json_encode($data);
	}


	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Informacion_model->eliminarItem($this->input->post(), 'con_secciones'))
		        {
					redirect(base_url('cms-v2/categorias'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/categorias', $data);
					$this->load->view('/footer');
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}