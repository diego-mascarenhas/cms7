<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cupones extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/elearning/Cupones_model');
		$this->load->model('cms-v2/Configuracion_model');
	}

	public function index($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$parametros['order_by'] = 'con_carro_cupones.fecha_alta';
				$parametros['order'] = 'DESC';
				$data['listado'] = $this->Cupones_model->getCupones($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/index', $data);
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
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->form_validation->set_rules('cupon', 'Cupon', 'required|min_length[5]', array('required' => 'Debe ingresar un cup&oacute;n.'));
				$this->form_validation->set_rules('descuento', 'Descuento', 'required|decimal', array('required' => 'Debe ingresar un descuento.','decimal' => 'Debe ingresar un n&uacute;mero decimal, con punto.'));
				$this->form_validation->set_rules('stock', 'Stock', 'required|integer', array('required' => 'Debe ingresar un stock.','integer' => 'Debe ingresar un n&uacute;mero entero, sin espacio, punto o coma.'));
	
				if ($this->form_validation->run() === false)
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
	
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/form', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($datos = $this->Cupones_model->ingresarCuponCMS($this->input->post()))
			        {
				        $this->session->set_flashdata('resultado', 'ok');
				        $this->session->set_flashdata('mensaje', 'El cupón se ingresó correctamente.');
						redirect(base_url('cms-v2/elearning/cupones'));
			        }
			        else
			        {
				        $this->session->set_flashdata('resultado', 'error');
				        $this->session->set_flashdata('mensaje', 'Se produjo un error al ingresar el cupón.');
						$this->load->view('header');
						$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/form');
				        echo 'Error';
				        $this->load->view('footer');
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
			redirect(base_url('user/login/'));
		}
	}

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{	
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->form_validation->set_rules('cupon', 'Cupon', 'required|min_length[5]', array('required' => 'Debe ingresar un cup&oacute;n.'));
				$this->form_validation->set_rules('descuento', 'Descuento', 'required|decimal', array('required' => 'Debe ingresar un descuento.','decimal' => 'Debe ingresar un n&uacute;mero decimal, con punto.'));
				$this->form_validation->set_rules('stock', 'Stock', 'required|integer', array('required' => 'Debe ingresar un stock.','integer' => 'Debe ingresar un n&uacute;mero entero, sin espacio, punto o coma.'));
			
				if ($this->form_validation->run() === false)
				{
					$data['item'] = $this->Cupones_model->detalleCuponCMS($id);
	
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/form', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($datos = $this->Cupones_model->modificarCupon($this->input->post()))
			        {
				        $this->session->set_flashdata('resultado', 'ok');
				        $this->session->set_flashdata('mensaje', 'El cupón se modificó correctamente.');
						redirect(base_url('cms-v2/elearning/cupones'));
			        }
			        else
			        {
				        $this->session->set_flashdata('resultado', 'error');
				        $this->session->set_flashdata('mensaje', 'Se produjo un error al modificar el cupón.');
						$this->load->view('header');
						$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/form');
				        echo 'Error';
				        $this->load->view('footer');
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
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar($id = null)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cupones_model->detalleRelacionCupon($this->input->post('id')))
	        {
		        $this->session->set_flashdata('resultado', 'error');
		        $this->session->set_flashdata('mensaje', 'El cupón no se puede eliminar porque fue usado en un pedido.');
				redirect(base_url('cms-v2/elearning/cupones'));
	        }
	        else
	        {
				if ($datos = $this->Cupones_model->eliminarItems($this->input->post()))
		        {
			        $this->session->set_flashdata('resultado', 'ok');
			        $this->session->set_flashdata('mensaje', 'El cupón se eliminó correctamente.');
					redirect(base_url('cms-v2/elearning/cupones'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cupones/form');
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