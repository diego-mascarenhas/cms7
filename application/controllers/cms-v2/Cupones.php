<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupones extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//cargo el modelo de secciones
		$this->load->model('cms-v2/Cupones_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Cupones_model->listadoContenidos(0);
	
			$this->load->view('header');
			$this->load->view('cms-v2/cupones/index', $datos);
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
	
			if(isset($id))
			{
				$this->form_validation->set_rules('cupon', 'CUPON', 'required', array('required' => 'Debe ingresar un CUPON.'));
			}
			else
			{
				$this->form_validation->set_rules('cupon', 'CUPON', 'required|is_unique[con_car_cupones.cupon]', array('required' => 'Debe ingresar un CUPON.','is_unique' => 'Debe ingresar un nombre diferente para el cupón.'));
			}
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('stock', 'STOCK', 'required', array('required' => 'Debe ingresar un stock.'));
			$this->form_validation->set_rules('descuento', 'DESCUENTO', 'required', array('required' => 'Debe ingresar un descuento.'));
			$this->form_validation->set_rules('fecha_vencimiento', 'VENCIMIENTO', 'required', array('required' => 'Debe ingresar una fecha de vencimiento.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				if(isset($id))
				{
					$datos['item'] = $this->Cupones_model->detalleContenido($id);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view('header');
				$this->load->view('cms-v2/cupones/ingresar', $datos);
			}
			else
			{
				if ($datos = $this->Cupones_model->ingresarContenido($this->input->post()))
		        {
		            redirect(base_url('cms-v2/cupones/index'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/cupones/detalle', $datos);
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
			if ($datos = $this->Cupones_model->duplicarItem($id))
	        {
				redirect(base_url('cms-v2/cupones/'));
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cupones_model->eliminarContenido($this->input->post()))
	        {
				redirect(base_url('cms-v2/cupones/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cupones/detalle', $datos);
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
