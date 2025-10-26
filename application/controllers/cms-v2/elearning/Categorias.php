<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/elearning/Categorias_model');
		$this->load->model('cms-v2/Configuracion_model');
	}

	public function index($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$parametros['order_by'] = 'con_categorias.fecha_alta';
				$parametros['order'] = 'DESC';
				$data['listado'] = $this->Categorias_model->getCategorias($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/categorias/index', $data);
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

	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('categoria', 'Categoría', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
					$data['idiomas'] = $this->Categorias_model->getIdiomas();
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/categorias/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Categorias_model->getIdiomas();
					if ($data = $this->Categorias_model->ingresarCategoria($this->input->post()))
					{			        
				        $this->session->set_flashdata('mensaje', 'La categoría fue modificada correctamente.');
			            redirect(base_url('cms-v2/elearning/categorias/modificar/'.$data['id']));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
						$this->load->view('cms-v2/error/', $data);
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

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('categoria', 'Categoría', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = $this->Categorias_model->detalleCategoria($id);
				
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/categorias/form', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($data = $this->Categorias_model->modificarCategoria($this->input->post()))
					{			        
				        $this->session->set_flashdata('mensaje', 'La categoría fue modificada correctamente.');
			            redirect(base_url('cms-v2/elearning/categorias/modificar/'.$id));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
						$this->load->view('cms-v2/error/', $data);
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

	public function ordenar($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				$parametros['estado'] = 2;
/*
				$parametros['order_by'] = 'con_elearning_categorias.orden';
				$parametros['order'] = 'ASC';
*/
				
				$data['listado'] = $this->Categorias_model->getCategorias($parametros);
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/categorias/ordenar', $data);
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
		$data = $this->Categorias_model->ordenarItems(json_decode($_POST['items']), 'con_elearning_categorias');
		echo json_encode($data);
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Categorias_model->eliminarCategoria($this->input->post(), 'con_servicios_categorias'))
		        {
					redirect(base_url('cms-v2/elearning/categorias/'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios', $data);
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