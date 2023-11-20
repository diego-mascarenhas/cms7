<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/carrito/pedidos_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['listado'] = $this->pedidos_model->getPedidos();

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/pedidos/index', $data);
			$this->load->view('footer');
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

			$this->form_validation->set_rules('id', 'Pedido', 'required', array('required' => 'Debe ingresar un pedido.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));

			if ($this->form_validation->run() === false)
			{
				$parametros['id_pedido'] = $id;
				$data['detalle'] = $this->pedidos_model->detallePedido($parametros);
				$data['items'] = $this->pedidos_model->listadoPedidoItems($parametros);
				$data['cantidaditems'] = $this->pedidos_model->cantidadPedidoItems($id);
				$data['estados'] = $this->pedidos_model->comboEstados();
	
				$this->load->view('header');
				$this->load->view('cms-v2/carrito/pedidos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->pedidos_model->cambiarEstadoPedido($id, $this->input->post('estado')))
		        {
					redirect(base_url('cms-v2/carrito/pedidos/'));
		        }

		        else
		        {
					$data['mensaje'] = array("mensaje" =>"No se pudo modificar el pedido", "link" =>"pedidos", "texto_link" => "Volver a Pedidos");
					$this->load->view('header');
					$this->load->view('cms-v2/carrito/error', $data);
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