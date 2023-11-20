<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			redirect(base_url('prospectos/'));
		}
		else if ($this->is_logged_in())
		{
			$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
	
			$this->load->view('header');
			$this->load->view('tienda/dashboard', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function operativo($parametros = null)
	{
		if ($this->is_logged_in())
		{
			if($parametros != null)
			{
				$data['periodicidad'] = $parametros;
			}
			else
			{
				$data['periodicidad'] = 'todos';
			}

			$day = date('Y-m-d');
			$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoPedidos($data['item']['id'], $parametros);
			$data['pedidosdia'] = $this->tienda_model->totalPedidosDia($day, $data['item']['id']);
			$data['entregados'] = $this->tienda_model->totalPedidosEstado(3, $day, $data['item']['id']);
			$data['pendientes'] = $this->tienda_model->totalPedidosEstado(2, $day, $data['item']['id']);
			$data['cancelados'] = $this->tienda_model->totalPedidosEstado(4, $day, $data['item']['id']);
				
			$this->load->view('header');
			$this->load->view('tienda/dashboard_operativo', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function gestion($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$day = date('Y-m-d');
			$mes = date('m');
			$mesanterior = date('m', strtotime('-1 month'));
			$yesterday = date('Y-m-d',strtotime($day.'- 1 day'));

			if($parametros != null)
			{
				$data['periodicidad'] = $parametros;
			}
			else
			{
				$data['periodicidad'] = 'todos';
			}
			$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoPedidos($data['item']['id'], $parametros);
			
			$data['pedidosmes'] = $this->tienda_model->totalPedidosMes($mes, $data['item']['id']);
			$data['entregados'] = $this->tienda_model->totalPedidosMesEstado(3, $mes, $data['item']['id']);
			$data['pendientes'] = $this->tienda_model->totalPedidosMesEstado(2, $mes, $data['item']['id']);
			$data['cancelados'] = $this->tienda_model->totalPedidosMesEstado(4, $mes, $data['item']['id']);

			$data['facturacion'] = $this->tienda_model->totalFacturacionMes($mes, $data['item']['id']);
			$data['comparativafactura'] = $this->tienda_model->porcentajesFacturasMes($mes, $data['item']['id']);
			if($data['facturacion']['total'] > 0) { $data['promediofacturas'] = round($data['facturacion']['total']/$data['pedidosmes']['total'], 2);}
			
			$data['pedidosmesanterior'] = $this->tienda_model->totalPedidosMes($mesanterior, $data['item']['id']);
			$data['comparativames'] = $data['pedidosmes']['total']-$data['pedidosmesanterior']['total'];

			$data['facturacionant'] = $this->tienda_model->totalFacturacionMes($mesanterior, $data['item']['id']);
			//$data['promediofacturasant'] = round($data['facturacionant']['total']/$data['pedidosmesanterior']['total'], 2);
			$data['promediofacturasant'] = 1320;
			//$data['comparativamesant'] = round(($data['promediofacturas']-$data['promediofacturasant'])*$data['pedidosmesanterior']['total']/100, 2);
			$data['comparativamesant'] = 10;
			
			$data['clientesmes'] = $this->tienda_model->totalClientesMes($mes, $data['item']['id']);
			$data['clientesmesanterior'] = $this->tienda_model->totalClientesMes($mesanterior, $data['item']['id']);
			$data['comparativaclientes'] = round(($data['clientesmes']['total']-$data['clientesmesanterior']['total'])*$data['clientesmesanterior']['total']/100, 2);

			$this->load->view('header');
			$this->load->view('tienda/dashboard_gestion', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}