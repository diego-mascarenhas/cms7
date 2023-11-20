<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Pedidos_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Pedidos_model->listadoPedidosCms();
	
			//cargo las visats
			$this->load->view('header');
			$this->load->view('cms-v2/pedidos/index', $datos);
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
	
			$this->form_validation->set_rules('pedido', 'Pedido', 'required', array('required' => 'Debe ingresar un pedido.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				if(isset($id))
				{
					$datos['item'] = $this->Pedidos_model->detallePedidoId($id);
					$datos['listado'] = $this->Pedidos_model->listadoPedidoItems($id);
					$datos['comprador'] = $this->Pedidos_model->detallePedidoUsuario($datos['item']['id_contacto']);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/pedidos/ingresar', $datos);
				$this->load->view('footer');
			}

			else
			{
				if ($datos = $this->Pedidos_model->ingresarPedido($this->input->post()))
		        {
					redirect(base_url('cms-v2/pedidos/'));
		        }

		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/pedidos/detalle', $datos);
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

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Pedidos_model->eliminarItem($this->input->post()))
	        {
				redirect(base_url('cms-v2/pedidos/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/pedidos/detalle', $datos);
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
