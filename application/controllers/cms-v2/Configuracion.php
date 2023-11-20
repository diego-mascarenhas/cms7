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
/* 			$this->form_validation->set_rules('subtitulo', 'Tagline', 'required', array('required' => 'Debe ingresar un tagline.')); */
	
			if ($this->form_validation->run() === false)
			{
				if(isset($this->usuario->id_empresa))
				{
					$datos['item'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/configuracion', $datos);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Configuracion_model->ingresarContenido($this->input->post()))
		        {
					redirect(base_url('cms-v2/configuracion'));
		        }
		        else
		        {
					$this->load->view('cms-v2/configuracion', $id);
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
