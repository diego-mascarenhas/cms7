<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupones extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoCupones($data['tienda']['id']);
			
			if($this->input->get('error'))
			{
				$data['error'] = "El cupón no se puede eliminar porque ya fue utilizado.";
			}

			$this->load->view('header');
			$this->load->view('tienda/cupones/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('cupon', 'Cupon', 'required|min_length[5]', array('required' => 'Debe ingresar un cup&oacute;n.'));
			$this->form_validation->set_rules('descuento', 'Descuento', 'required|decimal', array('required' => 'Debe ingresar un descuento.','decimal' => 'Debe ingresar un n&uacute;mero decimal, con punto.'));
			$this->form_validation->set_rules('cantidad', 'Stock', 'required|integer', array('required' => 'Debe ingresar un stock.','integer' => 'Debe ingresar un n&uacute;mero entero, sin espacio, punto o coma.'));

			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('tienda/cupones/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->ingresarCuponCMS($this->input->post()))
		        {
					redirect(base_url('tienda/cupones'));
		        }
		        else
		        {
					$this->load->view('tienda/cupones/form');
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
			$this->form_validation->set_rules('cupon', 'Cupon', 'required|min_length[5]', array('required' => 'Debe ingresar un cup&oacute;n.'));
			$this->form_validation->set_rules('cantidad', 'Stock', 'required|integer', array('required' => 'Debe ingresar un stock.','integer' => 'Debe ingresar un n&uacute;mero entero, sin espacio, punto o coma.'));
			$this->form_validation->set_rules('descuento', 'Descuento', 'required|decimal', array('required' => 'Debe ingresar un descuento.','decimal' => 'Debe ingresar un n&uacute;mero decimal, con punto.'));
			$this->form_validation->set_rules('fecha_vencimiento', 'Fecha de Vencimiento', 'required', array('required' => 'Debe ingresar una fecha de vencimiento.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = $this->tienda_model->detalleCuponCMS($id);

				$this->load->view('header');
				$this->load->view('tienda/cupones/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->modificarCupon($this->input->post()))
		        {
					redirect(base_url('tienda/cupones/'));
		        }
		        else
		        {
					$this->load->view('tienda/cupones/index');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar($id = null)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->detalleRelacionCupon($this->input->post('id')))
	        {
				redirect(base_url('tienda/cupones?error=eliminar'));
	        }
	        else
	        {
				if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_cupones'))
		        {
					redirect(base_url('tienda/cupones'));
		        }
		        else
		        {
					$this->load->view('header');
			        $this->load->view('tienda/cupones');
			        echo 'Error';
					$this->load->view('footer');
		        }
		    }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

}
