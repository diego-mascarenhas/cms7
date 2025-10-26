<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Configuracion extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Configuracion_model');
	}


	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un titulo.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['item'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
				$datos['adicionales'] = $this->Configuracion_model->getIdiomasAdicionales($datos['item']['id']);

				$this->load->view('header');
				$this->load->view('cms-v2/'.$datos['item']['template'].'/configuracion', $datos);
				$this->load->view('footer');
			}
			else
			{
				if ($ingresar = $this->Configuracion_model->ingresarContenido($this->input->post()))
		        {
					$datos['item'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
					$datos['adicionales'] = $this->Configuracion_model->getIdiomasAdicionales($datos['item']['id']);
	
					$this->load->view('header');
					$this->load->view('cms-v2/'.$datos['item']['template'].'/configuracion', $datos);
					$this->load->view('footer');
		        }
		        else
		        {
					$this->load->view('cms-v2/'.$this->input->post('template').'/configuracion', $id);
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

}
