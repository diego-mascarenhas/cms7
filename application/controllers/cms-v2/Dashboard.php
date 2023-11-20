<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Dashboard_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['item'] = $this->Dashboard_model->detalleConfiguracion(1);
			$datos['pedidos'] = $this->Dashboard_model->listadoPedidos();
			$datos['noticias'] = $this->Dashboard_model->listadoNoticias();
			$datos['totalpedidospesos'] = $this->Dashboard_model->totalPedidos('ar');
			$datos['totalpedidosdolares'] = $this->Dashboard_model->totalPedidos('ex');
			$datos['totalpedidospendientes'] = $this->Dashboard_model->totalPedidosEstado(5);
			$datos['totalpedidosregalados'] = $this->Dashboard_model->totalPedidosEstado(7);
			$datos['totalpedidosfinalizados'] = $this->Dashboard_model->totalPedidosEstado(2);
			$datos['totalfavoritos'] = $this->Dashboard_model->totalFavoritos();
			$datos['totalusuarios'] = $this->Dashboard_model->totalUsuarios();
	
			//cargo las visats
			$this->load->view('header');
			$this->load->view('cms-v2/dashboard', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}