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
		if ($this->is_logged_in())
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
			$anio = date('Y');
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
			if($data['facturacion']['total'] > 0) { $data['facturacion']['total'] = $data['facturacion']['total']; } else { $data['facturacion']['total'] = 0;}
			if($data['pedidosmes']['total'] > 0) { $data['pedidosmes']['total'] = $data['pedidosmes']['total']; } else { $data['pedidosmes']['total'] = 0;}
			if(($data['facturacion']['total'] > 0) && ($data['pedidosmes']['total'] > 0))
			{
				$data['promediofacturas'] = round($data['facturacion']['total']/$data['pedidosmes']['total'], 2);
			}
			else
			{
				$data['promediofacturas'] = 0;
			}
			$data['pedidosmesanterior'] = $this->tienda_model->totalPedidosMes($mesanterior, $data['item']['id']);
			$data['comparativames'] = $data['pedidosmes']['total']-$data['pedidosmesanterior']['total'];
			$data['facturacionant'] = $this->tienda_model->totalFacturacionMes($mesanterior, $data['item']['id']);
			if($data['facturacionant']['total'] > 0) { $data['facturacionant']['total'] = $data['facturacionant']['total']; } else { $data['facturacionant']['total'] = 0;}
			if($data['pedidosmesanterior']['total'] > 0) { $data['pedidosmesanterior']['total'] = $data['pedidosmesanterior']['total']; } else { $data['pedidosmesanterior']['total'] = 0;}
			if(($data['facturacionant']['total'] > 0) && ($data['pedidosmesanterior']['total'] > 0))
			{
				$data['promediofacturasant'] = round($data['facturacionant']['total']/$data['pedidosmesanterior']['total'], 2);
			}
			else
			{
				$data['promediofacturasant'] = 0;
			}
			if(($data['promediofacturas'] > 0) && ($data['promediofacturasant'] > 0) && ($data['promediofacturasant'] > 0))
			{
				$data['comparativamesant'] = round((($data['promediofacturas']-$data['promediofacturasant'])*100)/$data['promediofacturasant'], 2);
			}
			else
			{
				$data['comparativamesant'] = 0;
			}
			$data['clientesmes'] = $this->tienda_model->totalClientesMes($mes, $data['item']['id']);
			$data['clientesmesanterior'] = $this->tienda_model->totalClientesMes($mesanterior, $data['item']['id']);
			$data['comparativaclientes'] = round(($data['clientesmes']['total']-$data['clientesmesanterior']['total'])*$data['clientesmesanterior']['total']/100, 2);
			$data['pedidospordia'] = $this->tienda_model->listadoPedidosDia($mes, $data['item']['id']);
			$data['diasactual'] = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
			
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