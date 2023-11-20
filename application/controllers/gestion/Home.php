<?php defined('BASEPATH') or exit('No direct script access allowed');


class Home extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('empresa_model');
			$this->load->model('movimiento_model');
			
			$data['debito']['total'] = $this->movimiento_model->totalDebito();
			$data['cuentas'] = $this->movimiento_model->totalCuentas();
			$data['iva'] = $this->movimiento_model->totalIva();
			$data['deudores'] = $this->empresa_model->getEmpresasDeudoras(45);
			$data['dolar'] = $this->movimiento_model->getMonedaCambio(2);
			$data['ingresos'] = $this->movimiento_model->getIngresosDeHoy();
			
			$this->load->view('/header');
			$this->load->view('/gestion/index', $data);
			$this->load->view('/footer');
		}
		
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('user'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('guest'))
		{
			redirect(base_url('multimedia'));
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}