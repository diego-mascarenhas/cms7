<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secciones extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Secciones_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Secciones_model->listadoItems();
	
			//cargo las visats
			$this->load->view('header');
			$this->load->view('cms-v2/secciones/index', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('seccion', 'Seccion', 'required', array('required' => 'Debe ingresar una secci&oacute;n.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				if(isset($id))
				{
					$datos['item'] = $this->Secciones_model->detalleItem($id);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/secciones/ingresar', $datos);
				$this->load->view('footer');
			}

			else
			{
				if ($datos = $this->Secciones_model->ingresarSeccion($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->Secciones_model->subirOriginal($id,'imagen'); 
					}
					redirect(base_url('cms-v2/secciones/'));
		        }

		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/secciones/detalle', $datos);
					$this->load->view('footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			
			if ($datos = $this->Secciones_model->duplicarItem($id))
	        {
				redirect(base_url('cms-v2/secciones/'));
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Secciones_model->eliminarItem($this->input->post()))
	        {
				redirect(base_url('cms-v2/secciones/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/secciones/detalle', $datos);
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

}
