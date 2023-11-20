<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/carrito/pedidos_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->model('cms-v2/carrito/productos_model');
			$this->load->model('cms-v2/carrito/dashboard_model');
			$this->load->helper('form');

			$parametros['estado'] = 2;
			$parametros1['limit'] = 10;
			$data['pedidos'] = $this->pedidos_model->getPedidos($parametros1);
			$data['productos'] = $this->productos_model->getProductos($parametros1);
			$data['totalpedidos'] = $this->dashboard_model->totalPedidos();
			$data['totalpagados'] = $this->dashboard_model->totalPedidos($parametros);
			$data['totalproductos'] = $this->dashboard_model->totalProductos();

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/dashboard', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}