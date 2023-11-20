<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function index($periodicidad = null, $estado = null)
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoPedidos($data['tienda']['id'], $periodicidad, $estado);
			
			if($periodicidad != null)
			{
				$data['periodicidad'] = $periodicidad;
			}
			else
			{
				$data['periodicidad'] = 'todos';
			}

			if($estado != null)
			{
				$data['estado'] = $estado;
			}

			$this->load->view('header');
			$this->load->view('tienda/pedidos/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function listado($periodicidad = null, $estado = null)
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoPedidos($data['tienda']['id'], $periodicidad, $estado);

			if($periodicidad != null)
			{
				$data['periodicidad'] = $periodicidad;
			}
			else
			{
				$data['periodicidad'] = 'todos';
			}
	
			if($estado != null)
			{
				$data['estados'] = $estado;
			}
			$this->load->view('header');
			$this->load->view('tienda/pedidos/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['detalle'] = $this->tienda_model->detallePedido($id);
			$data['items'] = $this->tienda_model->detallePedidoItems($id);
			$data['cantidaditems'] = $this->tienda_model->cantidadPedidoItems($id);
			$data['cupones'] = $this->tienda_model->listadoCuponesPedido($id);
			
			$this->load->view('header');
			$this->load->view('tienda/pedidos/detalle', $data);
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
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['detalle'] = $this->tienda_model->detallePedido($id);
				$data['items'] = $this->tienda_model->detallePedidoItems($id);
				$data['cantidaditems'] = $this->tienda_model->cantidadPedidoItems($id);
				$data['cupones'] = $this->tienda_model->listadoCuponesPedido($id);
				$data['estados'] = array('2' => 'Solicitado sin pagar','3' => 'Pendiente Mercado Pago','4' => 'Cancelado', '5' => 'Solicitado/Pagado MP','6' => 'En proceso para entregar','7' => 'Entregado al cliente','8' => 'En proceso de Pago PayPal','9' => 'Solicitado/Pagado PayPal','10' => 'Cancelado PayPal','11' => 'Pagado otros medios');
	
				$this->load->view('header');
				$this->load->view('tienda/pedidos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->modificarPedido($id, $this->input->post()))
				{
					redirect(base_url('tienda/pedidos/modificar/'.$id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					$this->load->view('header');
					$this->load->view('tienda/pedidos/form', $data);
					$this->load->view('footer');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
}