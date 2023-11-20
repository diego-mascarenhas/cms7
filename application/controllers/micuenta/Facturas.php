<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Facturas extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('admin'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = 2;
			$parametros['order_by'] = 'facturas.id';
			$parametros['order'] = 'DESC';
			
			$data['facturas'] = $this->factura_model->getFacturas($parametros);
			
			$config['total_rows'] = $this->factura_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/micuenta/facturas/index', $data) : $this->load->view('/micuenta/facturas/empty', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in('reseller'))
		{
			redirect(base_url('administracion/facturas'));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in('admin'))
		{
			$this->trackUri();
			
			$data['alerta'] = ($this->uri->segment(5)) ? $this->uri->segment(5) : null;
			
			// models
			$this->load->model('sys_model');
			$this->load->model('factura_model');
			$this->load->model('movimiento_model');
			
			// helpers and libraries
	        $config = $this->sys_model->getMercadoPagoCredenciales($this->usuario->grupo);
			$this->load->library('Mercadopago', $config);
	
			
			$data['factura'] = $this->factura_model->getFacturaDetalle($id);
			$data['factura']['items'] = $this->factura_model->getFacturaItems($id);
			$data['movimientos'] = $this->movimiento_model->getMovimientos(array('id_factura'=>$id));
			
			if ($data['factura']['saldo'] > 0 && $data['factura']['id_forma_pago'] == 13)
			{
				$track_url = base_url();;
				
				$preference_data = array(
										    'items' => array(
													        array(
													            'title' => 'COMPROBANTE ' . $data['factura']['comprobante'],
													            'currency_id' => 'ARS',
													            'quantity' => 1,
													            'unit_price' => (float) $data['factura']['saldo'],
													            'picture_url' => 'https://cms.revisionalpha.com/templates/' . $this->usuario->grupo . '/thumb_250x250.png'
													        )
														),
										    'back_urls' => array(
																'success' => $track_url . 'micuenta/facturas/detalle/' . $data['factura']['id'],
																'failure' => $track_url . 'micuenta/facturas/detalle/' . $data['factura']['id'] . '/error/',
																'pending' => $track_url . 'micuenta/facturas/detalle/' . $data['factura']['id'] . '/pendiente/'
															),
										    'notification_url' => $track_url . 'administracion/ipn/mercadopago/' . $this->usuario->grupo,
										    'external_reference' => $data['factura']['id']
										);
				
				$data['preference'] = $this->mercadopago->create_preference($preference_data);
			}
			
			elseif ($data['factura']['saldo'] > 0 && $data['factura']['id_forma_pago'] == 7)
			{
				$data['paypal'] = true;
			}

			$this->load->view('/header');
			$this->load->view('/micuenta/facturas/detalle', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in('reseller'))
		{
			redirect(base_url('administracion/facturas/detalle/' . $id));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function paypal_checkout($id)
    {
	    $track_url = base_url();;
	    
	    // models
	    $this->load->model('sys_model');
		$this->load->model('factura_model');
		
		$config = $this->sys_model->getPayPalCredenciales($this->usuario->grupo);
		
		$data['factura'] = $this->factura_model->getFacturaDetalle($id);
	    $config['paypal_currency_code'] = $data['factura']['moneda_codigo'];
	    
	    // helpers and libraries
	    $this->load->library('paypal', $config);
		
	    // Add fields to paypal form
        $this->paypal->add_field('return', $track_url . 'micuenta/facturas/detalle/' . $data['factura']['id']);
        $this->paypal->add_field('cancel_return', $track_url . 'micuenta/facturas/detalle/' . $data['factura']['id'] . '/error/');
        $this->paypal->add_field('notify_url', $track_url . 'administracion/ipn/paypal/' . $this->usuario->grupo);
        $this->paypal->add_field('item_name', 'COMPROBANTE ' . $data['factura']['comprobante']);
        //$this->paypal->add_field('custom', $this->usuario->id);
        $this->paypal->add_field('item_number', $data['factura']['id']);
        $this->paypal->add_field('amount', (float) $data['factura']['saldo']);
        
        // Render paypal form
        $this->paypal->paypal_auto_form();
    }
	
	
	public function test()
    {
	    // models
		$this->load->model('sys_model');
			
		// helpers and libraries
		$config = $this->sys_model->getMercadoPagoCredenciales($this->usuario->grupo);
		$this->load->library('Mercadopago', $config);
			
		echo $accessToken = $this->mercadopago->get_access_token();
    }


}
