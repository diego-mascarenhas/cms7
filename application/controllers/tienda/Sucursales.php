<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursales extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->getSucursales($data['item']['id']);
	
			$this->load->view('header');
			$this->load->view('tienda/sucursales/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}


	public function ingresar($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un nombre para la sucursal.'));
			$this->form_validation->set_rules('orden', 'Orden', 'required', array('required' => 'Debe ingresar un orden.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			$this->form_validation->set_rules('celular', 'Celular', 'required', array('required' => 'Debe ingresar un celular.'));
			$this->form_validation->set_rules('email', 'Email', 'required', array('required' => 'Debe ingresar un email.'));
			$this->form_validation->set_rules('domicilio', 'Domicilio', 'required', array('required' => 'Debe ingresar un domicilio.'));
			$this->form_validation->set_rules('numero', 'N&uacute;mero', 'required', array('required' => 'Debe ingresar un n&uacute;mero de calle.'));
			$this->form_validation->set_rules('localidad', 'Localidad', 'required', array('required' => 'Debe ingresar una localidad.'));
			$this->form_validation->set_rules('provincia', 'Provincia', 'required', array('required' => 'Debe ingresar una provincia.'));
			$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'required|is_natural_no_zero', array('required' => 'Debe ingresar un pa&iacute;s.', 'is_natural_no_zero' => 'Debe ingresar un pa&iacute;s.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['paises'] = $this->tienda_model->listadoPaises();
/* 				$data['paises'] = array( 0 => 'Seleccione pa&iacute;s', 1 => 'Argentina', 2 => 'Uruguay', 3 => 'Bolivia', 4 => 'Paraguay', 5 => 'Per&uacute;', 6 => 'Chile', 7 => 'Ecuador', 8 => 'Colombia', 9 => 'M&eacute;xico'); */
				
				if(isset($id))
				{
					$data['item'] = $this->tienda_model->detalleSucursal($id);
				}
				else
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view('header');
				$this->load->view('tienda/sucursales/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->ingresarSucursal($this->input->post()))
		        {
					redirect(base_url('/tienda/sucursales'));
		        }
		        else
		        {
					$this->load->view('tienda/sucursales/index', $data);
			        echo 'Error';
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
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un nombre para la sucursal.'));
			$this->form_validation->set_rules('orden', 'Orden', 'required', array('required' => 'Debe ingresar un orden.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			$this->form_validation->set_rules('celular', 'Celular', 'required', array('required' => 'Debe ingresar un celular.'));
			$this->form_validation->set_rules('email', 'Email', 'required', array('required' => 'Debe ingresar un email.'));
			$this->form_validation->set_rules('domicilio', 'Domicilio', 'required', array('required' => 'Debe ingresar un domicilio.'));
			$this->form_validation->set_rules('numero', 'N&uacute;mero', 'required', array('required' => 'Debe ingresar un n&uacute;mero de calle.'));
			$this->form_validation->set_rules('localidad', 'Localidad', 'required', array('required' => 'Debe ingresar una localidad.'));
			$this->form_validation->set_rules('provincia', 'Provincia', 'required', array('required' => 'Debe ingresar una provincia.'));
			$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'required|is_natural_no_zero', array('required' => 'Debe ingresar un pa&iacute;s.', 'is_natural_no_zero' => 'Debe ingresar un pa&iacute;s.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['paises'] = $this->tienda_model->listadoPaises();
/* 				$data['paises'] = array( 0 => 'Seleccione pa&iacute;s', 1 => 'Argentina', 2 => 'Uruguay', 3 => 'Bolivia', 4 => 'Paraguay', 5 => 'Per&uacute;', 6 => 'Chile', 7 => 'Ecuador', 8 => 'Colombia', 9 => 'M&eacute;xico'); */
				
				if(isset($id))
				{
					$data['item'] = $this->tienda_model->detalleSucursal($id);
				}
				else
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view('header');
				$this->load->view('tienda/sucursales/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->ingresarSucursal($this->input->post()))
		        {
					redirect(base_url('/tienda/sucursales'));
		        }
		        else
		        {
					$this->load->view('tienda/sucursales/index', $data);
			        echo 'Error';
		        }
			}

		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function ordenar()
	{
		if ($this->is_logged_in())
		{
			$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->getSucursales($data['item']['id']);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('tienda/sucursales/ordenar', $data);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarCategorias()
	{
		$data = $this->tienda_model->ordenarItems(json_decode($_POST['items']), 'tienda_sucursales');
		echo json_encode($data);
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_sucursales'))
	        {
				redirect(base_url('tienda/sucursales'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/sucursales/index');
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