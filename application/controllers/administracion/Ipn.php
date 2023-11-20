<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Ipn extends MY_Controller {

	public function mercadopago($id)
    {
	    // models
		$this->load->model('sys_model');
		$this->load->model('movimiento_model');
		$this->load->model('empresa_model');
		$this->load->model('factura_model');
			
		// helpers and libraries
        $config = $this->sys_model->getMercadoPagoCredenciales($id);

        if (isset($config['client_id']))
        {
	        $this->load->library('Mercadopago', $config);
	        
	        if (isset($_GET['id']))
	        {
				$payment_info = $this->mercadopago->get_payment_info($_GET['id']);
				
				if (isset($_GET['debug'])) echo '<pre>' . print_r($payment_info, true) . '</pre>';
				
				// Show payment information
				if ($payment_info['status'] == 200)
				{
					$data['grupo'] = $config['grupo'];
					
					// VERIFICAR SI EXISTE EL MOVIMIENTO
					if (!$this->movimiento_model->verificarkPagoExterno('MLA', $payment_info['response']['collection']['id']))
					{
						$data['valor'] = $payment_info['response']['collection']['transaction_amount'];
		
						if ($payment_info['response']['collection']['operation_type'] == 'recurring_payment')
						{
							$data['id_empresa'] = $this->empresa_model->getEmpresaIdFromCodigo($data['grupo'], $payment_info['response']['collection']['payer']['email']);
							$data['id_factura'] = $this->factura_model->getFacturaMercadoPagoRecurrente($data['id_empresa'], $data['valor']);
							$data['id_forma_pago'] = 12; # MERCADO PAGO (SUSCRIPCION)
						}
						else
						{
							$data['id_empresa'] = $this->empresa_model->getEmpresaIdFromFarcturaId($data['grupo'], $payment_info['response']['collection']['external_reference']);
							
							$data['id_factura'] = $payment_info['response']['collection']['external_reference'];
							$data['id_forma_pago'] = 13; # MERCADO PAGO
						}
						
						$data['transaccion'] = 'I';
						$data['fecha'] = date('Y-m-d', strtotime('-3 HOURS', strtotime($payment_info['response']['collection']['date_created'])));
						$data['id_cuenta'] = $config['id_cuenta'];
						$data['id_externo'] = $payment_info['response']['collection']['id'];
		
						$data['estado'] = $this->movimiento_model->getEstadoIdFromEstado($payment_info['response']['collection']['status']);
						
						$data['observaciones'] = $payment_info['response']['collection']['site_id'];
						if (!empty($payment_info['response']['collection']['merchant_order_id'])) $data['observaciones'] .= ' | Orden: ' . $payment_info['response']['collection']['merchant_order_id'];
						$data['observaciones'] .= ' (' . $payment_info['response']['collection']['payment_type'] . ')';
						
						$data['fecha_alta'] = date('Y-m-d H:i:s');
						$data['username_alta'] = 'MLA';
						
						// INGRESO EL MOVIMIENTO
						$data['res'] = $this->movimiento_model->ingresarMovimiento($data);
						
						$data['saldo'] = $this->factura_model->actualizarFacturaSaldo($data['id_factura']);
						
						$this->sys_model->track('A', json_encode($payment_info['response']), 2, $data['res']['id']);
					}
					
					else
					{
						// ACTUALIZO EL ESTADO SI EXISTE EL MOVIMIENTO
						$data['estado'] = $this->movimiento_model->getEstadoIdFromEstado($payment_info['response']['collection']['status']);
						
						$data['valor'] = $payment_info['response']['collection']['transaction_amount'];
						
						$data['fecha_modificacion'] = date('Y-m-d H:i:s');
						$data['username_modificacion'] = 'MLA';
						
						// ACTUALIZO EL MOVIMIENTO
						$id = $this->movimiento_model->getMovimientoIdFromIdExterno($_GET['id']);
						
						$data['res'] = $this->movimiento_model->modificarMovimiento($id, $data);

						$data['id_factura'] = $this->factura_model->getFacturaIdFromMovimientoId($id);
						
						$data['saldo'] = $this->factura_model->actualizarFacturaSaldo($data['id_factura']);
					
						$this->sys_model->track('M', json_encode($payment_info['response']), 2, $id);
					}
				}
			}
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
		
		if (isset($_GET['json'])) echo json_encode($data);
    }
    
    
    function paypal($id)
	{
		// models
		$this->load->model('sys_model');
		$this->load->model('movimiento_model');
		$this->load->model('empresa_model');
		$this->load->model('factura_model');
		
		// helpers and libraries
		$config = $this->sys_model->getPayPalCredenciales($id);
		$this->load->library('paypal', $config);
		
		// Paypal posts the transaction data
		$paypalInfo = $this->input->post();
		
		
		if (!empty($paypalInfo))
		{
			$data['grupo'] = $config['grupo'];

			// VERIFICAR SI EXISTE EL MOVIMIENTO
			if (!$this->movimiento_model->verificarkPagoExterno('paypal', $paypalInfo['txn_id']))
			{
				$data['valor'] = $paypalInfo['payment_gross'];
				
				if (!$data['id_empresa'] = $this->empresa_model->getEmpresaIdFromFarcturaId($data['grupo'], $paypalInfo['item_number']))
				{
					$data['id_empresa'] = 1;
				}
				else
				{
					$data['id_factura'] = $paypalInfo['item_number'];
				}
				
				$data['id_forma_pago'] = 7; # PayPal
						
				$data['transaccion'] = 'I';
				$data['fecha'] = date('Y-m-d', strtotime('-3 HOURS', strtotime($paypalInfo['payment_date'])));
				$data['id_cuenta'] = $config['id_cuenta'];
				$data['id_externo'] = $paypalInfo['txn_id'];

				$data['estado'] = $this->movimiento_model->getEstadoIdFromEstado($paypalInfo['payment_status']);
				
				$data['observaciones'] = 'PayPal';
				if (!empty($paypalInfo['txn_id'])) $data['observaciones'] .= ' | Orden: ' . $paypalInfo['txn_id'];
				$data['observaciones'] .= ' (' . $paypalInfo['payment_type'] . ')';
				
				$data['fecha_alta'] = date('Y-m-d H:i:s');
				$data['username_alta'] = 'paypal';
				
				// INGRESO EL MOVIMIENTO
				$data['res'] = $this->movimiento_model->ingresarMovimiento($data);
				
				$data['saldo'] = $this->factura_model->actualizarFacturaSaldo($data['id_factura']);
				
				$this->sys_model->track('A', json_encode($paypalInfo), 2, $data['res']['id']);
			}
			
			else
			{
				// ACTUALIZO EL ESTADO SI EXISTE EL MOVIMIENTO
				$data['estado'] = $this->movimiento_model->getEstadoIdFromEstado($paypalInfo['payment_status']);
				
				$data['valor'] = $paypalInfo['payment_gross'];
				
				$data['fecha_modificacion'] = date('Y-m-d H:i:s');
				$data['username_modificacion'] = 'paypal';
				
				// ACTUALIZO EL MOVIMIENTO
				$id = $this->movimiento_model->getMovimientoIdFromIdExterno($paypalInfo['txn_id']);
				
				$data['res'] = $this->movimiento_model->modificarMovimiento($id, $data);

				$data['id_factura'] = $this->factura_model->getFacturaIdFromMovimientoId($id);
				
				$data['saldo'] = $this->factura_model->actualizarFacturaSaldo($data['id_factura']);
			
				$this->sys_model->track('M', json_encode($paypalInfo), 2, $id);
			}
		}
	}
    
    
    public function test($id)
    {
	    //models
		$this->load->model('sys_model');
		

        $config = $this->sys_model->getMercadoPagoCredenciales($id);
        
        if (isset($config['client_id']))
        {
	        echo '<pre>' . print_r($config, true) . '</pre>';
	        
	        $res = $this->load->library('Mercadopago', $config);
	        
	        echo $accessToken = $this->mercadopago->get_access_token();
		}
		
		$mercado_pago = '{"collection":{"id":13140265692,"site_id":"MLA","date_created":"2021-01-20T10:55:04.000-04:00","date_approved":"2021-01-20T10:55:05.000-04:00","money_release_date":"2021-02-03T10:55:05.000-04:00","last_modified":"2021-01-20T10:55:05.000-04:00","payer":{"id":545577843,"first_name":"BARBAKOA SRL","last_name":".-","phone":{"area_code":"11","number":"21483913","extension":""},"identification":{"type":"Otro","number":"30716009307"},"email":"restobarbakoa@gmail.com","nickname":"BARBAKOARESTOBAR"},"order_id":"2237966592","external_reference":"24252","merchant_order_id":"2237966592","reason":"COMPROBANTE B 0005-00000003","currency_id":"ARS","transaction_amount":1208.79,"net_received_amount":1133.6,"total_paid_amount":1208.79,"shipping_cost":0,"coupon_amount":0,"coupon_fee":0,"finance_fee":0,"discount_fee":0,"coupon_id":null,"status":"approved","status_detail":"accredited","issuer_id":null,"installment_amount":0,"deferred_period":null,"payment_type":"account_money","payment_method_id":"account_money","marketplace":"NONE","operation_type":"regular_payment","transaction_order_id":null,"statement_descriptor":null,"cardholder":null,"authorization_code":null,"marketplace_fee":0,"deduction_schema":null,"refunds":[],"amount_refunded":0,"last_modified_by_admin":null,"api_version":"2","concept_id":null,"concept_amount":0,"collector":{"id":94423147,"first_name":"Diego","last_name":"Mascarenhas","phone":{"area_code":"011","number":"52748490","extension":""},"identification":{"type":null,"number":null},"email":"administracion@revisionalpha.com","nickname":"REVISION ALPHA"}}}';
		
		echo '<pre>' . print_r(json_decode($mercado_pago), true) . '</pre>';
		
		
        // helpers and libraries
		$config = $this->sys_model->getPayPalCredenciales($id);
		$this->load->library('paypal', $config);
                
        if (isset($config['paypal_business_email']))
        {
	        echo '<pre>' . print_r($config, true) . '</pre>';
	        
	        $res = $this->load->library('paypal');
	        
	        echo '<pre>' . print_r($res, true) . '</pre>';
		}
		
		$paypal = '{"mc_gross":"0.01","protection_eligibility":"Eligible","address_status":"confirmed","payer_id":"RAD5GFN33K8WY","address_street":"Vuelta de Obligado\r\n2443","payment_date":"17:32:35 Jan 19, 2021 PST","payment_status":"Pending","charset":"windows-1252","address_zip":"1428","first_name":"Diego Adrin","address_country_code":"AR","address_name":"revision alpha","notify_version":"3.9","custom":"","payer_status":"verified","business":"info@revisionalpha.com","address_country":"Argentina","address_city":"CABA","quantity":"1","verify_sign":"AnqtGQDY3Dn3xRNcy61tQ2.LjlskAdQ-Q.K9MHTXttbIsKBb0E-C80N5","payer_email":"administracion@revisionalpha.com.ar","txn_id":"4K684251LV844945V","payment_type":"instant","payer_business_name":"revision alpha","last_name":"Mascarenhas Goyta","address_state":"CIUDAD AUTNOMA DE BUENOS AIRES","receiver_email":"info@revisionalpha.com","shipping_discount":"0.00","insurance_amount":"0.00","receiver_id":"BZV6RSH9Y7JRW","pending_reason":"verify","txn_type":"web_accept","item_name":"COMPROBANTE S 0001-30062415","discount":"0.00","mc_currency":"USD","item_number":"24423","residence_country":"AR","shipping_method":"Default","transaction_subject":"","payment_gross":"0.01","ipn_track_id":"2bc5f0b66c150"}';
		
		echo '<pre>' . print_r(json_decode($paypal), true) . '</pre>';
    }


}