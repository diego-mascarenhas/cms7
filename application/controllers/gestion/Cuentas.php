<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cuentas extends MY_Controller {

	public function reporte($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('movimiento_model');
			$this->load->model('cuenta_model');
			
			$desde = date('Y-m-d', strtotime('-365 days', now()));
			$hasta = date('Y-m-d', now());
			
			$data['detalle'] = $this->cuenta_model->getCuentaDetalle($id);
			
			$data['detalle']['desde'] = date('Y-m-d', strtotime('-365 days'));
			$data['detalle']['hasta'] = $hasta;
			
			// $servicio['proxima'] = date('Y-m-d', strtotime($valores['proxima']));
			// $servicio['proxima'] = date('Y-m-d', strtotime('today UTC'));
			// $servicio['proxima'] = date('Y-m-d', strtotime('+7 days'));
			
			$reporte = $this->movimiento_model->reporteCuenta($id, $desde, $hasta);
			
			$total = $this->movimiento_model->totalHasta($id, 'I', $desde) - $this->movimiento_model->totalHasta($id, 'G', $desde);
			
			if (isset($reporte))
			{
				foreach ($reporte as $row)
				{
					if ($row['transaccion'] == 'I' && $row['id_estado'] == 2)
					{
						$total += $row['valor'];
					}
					elseif ($row['transaccion'] == 'G' && $row['id_estado'] == 2)
					{
						$total -= $row['valor'];
					}
						
					$data['reporte'][] = array(
									'id'=>$row['id'],
									'fecha'=>$row['fecha'],
									'empresa'=>$row['empresa'],
									'id_empresa'=>$row['id_empresa'],						
									'transaccion'=>$row['transaccion'],
									'observaciones'=>$row['observaciones'],
									'valor'=>number_format($row['valor'], 2, ',', '.'),
									'id_estado'=>$row['id_estado'],
									'estado'=>$row['estado'],
									'estado_ui_class'=>$row['estado_ui_class'],
									'username_alta'=>$row['username_alta'],
									'username_modificacion'=>$row['username_modificacion'],
									'total'=>number_format($total, 2, ',', '.'),
								);
				}
			}
			
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
								base_url('assets/css/plugins/clockpicker/clockpicker.css')
							);
							
			$this->load->view('/header', $header);
			$this->load->view('/gestion/cuentas/reporte', $data);
			$this->load->view('/footer');
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function conciliar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('cuenta_model');
			$this->load->model('movimiento_model');
			$this->load->model('factura_model');
				
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id', 'Cuenta', 'trim|required|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|required|decimal');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default = $this->cuenta_model->getCuentaDetalle($id);
				$default['valor'] = $this->movimiento_model->totalHasta($id, 'I') - $this->movimiento_model->totalHasta($id, 'G');
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
	
				
				$this->load->view('/header');
				$this->load->view('/gestion/cuentas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($this->input->post('valor_original') > $this->input->post('valor'))
				{
					$factura['operacion'] = 'C';
					
					$factura['bruto'] = $this->input->post('valor_original')-$this->input->post('valor');
				}
				elseif ($this->input->post('valor_original') < $this->input->post('valor'))
				{
					$factura['operacion'] = 'V';
					
					$factura['bruto'] = $this->input->post('valor')-$this->input->post('valor_original');
				}
				
				if ($this->input->post('valor_original') != $this->input->post('valor'))
				{
					if ($this->input->post('id') == 6333) // Hardcoded
					{
						// Cuenta en u$s
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6380;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 2;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 2;
						$factura['estado'] = 2;
					}
					
					elseif ($this->input->post('id') == 6347) // Hardcoded C.C. Diego
					{
						// Cuenta corriente
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6380;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 1;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 2;
						$factura['estado'] = 2;
					}
					
					
					elseif ($this->input->post('id') == 6521) // Hardcoded C.C. SAS
					{
						// Cuenta corriente
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6380;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 1;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 2;
						$factura['estado'] = 2;
					}
					
					elseif ($this->input->post('id') == 6351) // Hardcoded
					{
						// PayPal
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6739;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 2;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 7;
						$factura['estado'] = 2;
					}
					
					elseif ($this->input->post('id') == 6388) // Hardcoded
					{
						// MercadoPago
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6777;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 1;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 13;
						$factura['estado'] = 2;
					}
					
					elseif ($this->input->post('id') == 6360) // Efectivo como restaurante
					{
						// MercadoPago
						$factura['grupo'] = 502;
						$factura['id_empresa_fiscal'] = 6578;
						$factura['id_factura_tipo'] = 5;
						$factura['numero_talonario'] = 1;
						$factura['total_neto'] = $factura['bruto'];
						$factura['saldo'] = $factura['bruto'];
						$factura['id_moneda'] = 1;
						$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
						$factura['id_forma_pago'] = 1;
						$factura['estado'] = 2;
					}
					
					else
					{
						$data['error'] = 'Esta cuenta no se puede conciliar.';
					}
					
					if (!isset($data['error']))
					{
						$this->db->trans_begin();
						
						$data['id'] = $this->factura_model->ingresarFactura($factura);
						
						if (isset($data['id']))
						{
							// Factura Items
							$factura_items['grupo'] = $factura['grupo'];
							$factura_items['id_categoria'] = 35;
							$factura_items['id_factura'] = $data['id'];
							$factura_items['valor'] = $factura['bruto'];
							$factura_items['descuento'] = 0;
							$factura_items['descripcion'] = 'Comisiones y gastos bancarios';
							
							$this->factura_model->ingresarFacturaItems($factura_items);
							
							
							if ($this->db->trans_status() === false)
							{
								$this->db->trans_rollback();
								
								$data['codigo'] = 500;
								$data['error'] = 'Error de aplicación';
								$data['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
								
								$this->load->view('/error', $data);
							}
							else
							{
								$this->db->trans_commit();
								
								redirect(base_url('administracion/facturas/detalle/' . $data['id']));
							}
						}
						else
						{
							$this->db->trans_rollback();
							
							// error
							$data['codigo'] = 500;
							$data['error'] = 'Error de aplicación';
							$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
							
							$this->load->view('error', $data);
						}
					}
				}
				else
				{
					// error
					$data['codigo'] = $this->input->post('id');
					$data['error'] = 'Cuenta conciliada';
					$data['mensaje'] = 'El valor en el CMS es igual a lo que hay en cuenta';
					
					$this->load->view('error', $data);
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}