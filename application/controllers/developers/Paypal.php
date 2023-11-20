<?php defined('BASEPATH') OR exit('No direct script access allowed');
//error_reporting(E_ALL & ~E_NOTICE);

class Paypal extends MY_Controller {

	public function index()
	{
		echo 1;
	}
	
	
	public function paypal_checkout()
	{
		// models
		//$this->load->model('sys_model');
				
		//$config = $this->sys_model->getPayPalCredenciales($this->usuario->grupo);
		//$config['paypal_currency_code'] = 'ARG';
		
		$config['paypal_sandbox_mode'] = true; // FALSE for live environment
		
		// PayPal business email
		$config['paypal_business_email'] = 'administracion@revisionalpha.com.ar';
		
		// What is the default currency?
		$config['paypal_currency_code'] = 'USD';
		
		// If (and where) to log ipn response in a file
		$config['paypal_ipn_log'] = false;
		$config['paypal_ipn_log_file'] = 'logs/paypal.log';
		
		
		
		$data['factura']['id'] = 1;
		$data['factura']['comprobante'] = 'Prueba';
		$data['factura']['saldo'] = 30;
		
		// helpers and libraries
		$this->load->library('Paypal', $config);
		
		// Add fields to paypal form
		$this->paypal->add_field('return', base_url('micuenta/facturas/detalle/' . $data['factura']['id']));
		$this->paypal->add_field('cancel_return', base_url('micuenta/facturas/detalle/' . $data['factura']['id'] . '/error/'));
		$this->paypal->add_field('notify_url', base_url('administracion/ipn/paypal/' . $this->usuario->grupo));
		$this->paypal->add_field('item_name', 'COMPROBANTE ' . $data['factura']['comprobante']);
		//$this->paypal->add_field('custom', $this->usuario->id);
		$this->paypal->add_field('item_number', $data['factura']['id']);
		$this->paypal->add_field('amount', (float) $data['factura']['saldo']);
		
		// Render paypal form
		$this->paypal->paypal_auto_form();
	}


}