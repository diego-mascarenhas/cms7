<?php defined('BASEPATH') or exit('No direct script access allowed');


class Movimientos extends MY_Controller
{

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();

			//models
			$this->load->model('movimiento_model');

			// helpers and libraries
			$this->load->library('pagination');


			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['id_forma_pago'] = $this->input->get('id_forma_pago');

			$data['movimientos'] = $this->movimiento_model->getMovimientos($parametros);

			$config['total_rows'] = $this->movimiento_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador' => true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/movimientos/index', $data) : $this->load->view('/administracion/movimientos/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function ingresar()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('movimiento_model');
			$this->load->model('factura_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('fecha', 'Fecha', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('transaccion', 'Transacción', 'trim|required');
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');
			$this->form_validation->set_rules('id_cuenta', 'Cuenta', 'trim|required|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|required|numeric');

			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_empresa'] = $this->input->get('id_empresa');
				$default['fecha'] = date('d-m-Y', strtotime('today UTC'));

				if ($ultimo_movimientos_id = $this->movimiento_model->getIdUltimoMovimiento($default))
				{
					$ultimo_movimientos = $this->movimiento_model->getMovimientoDetalleRaw($ultimo_movimientos_id);

					$default['id_forma_pago'] = $ultimo_movimientos['id_forma_pago'];
					$default['id_cuenta'] = $ultimo_movimientos['id_cuenta'];

					if (!empty($this->input->get('id_factura')))
						$default['id_factura'] = $this->factura_model->getFacturaDetalle($this->input->get('id_factura'))['id'];
				}
				elseif (!empty($this->input->get('id_factura')))
				{
					$factura = $this->factura_model->getFacturaDetalle($this->input->get('id_factura'));

					$default['id_factura'] = $factura['id'];
					$default['id_forma_pago'] = $factura['id_forma_pago'];
				}
				else
				{
					$empresa = $this->empresa_model->getEmpresaDetalle($this->input->get('id_empresa'));
					;

					$default['id_forma_pago'] = $empresa['id_forma_pago'];
				}

				$default['transaccion'] = ($this->input->get('operacion')) ? ($this->input->get('operacion') == 'C') ? 'G' : 'I' : null;
				$default['valor'] = $this->input->get('valor');

				$data['detalle'] = ($this->input->post()) ? $this->input->post() : ($this->session->flashdata()) ? ($this->session->flashdata()) : $default;
				$data['cuentas'] = $this->movimiento_model->comboCuentas();
				if (isset($data['detalle']['id_empresa']))
					$data['facturas'] = $this->factura_model->comboFacturas(array('id_empresa' => $data['detalle']['id_empresa']));
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();

				// validation not ok, send validation errors to the view
				$header['css'] = array(base_url('assets/css/plugins/datapicker/datepicker3.css')
				);

				$this->load->view('/header', $header);
				$this->load->view('/administracion/movimientos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$valores = $this->input->post();

				if ($this->input->post('id_factura'))
				{
					$data['valor'] = $this->input->post('valor');
					$data['saldo'] = $this->factura_model->getFacturaSaldo($this->input->post('id_factura'));

					if ($data['valor'] > $data['saldo'])
					{
						$valores['valor'] = $data['saldo'];

						$this->movimiento_model->ingresarMovimiento($valores);
						$this->factura_model->actualizarFacturaSaldo($this->input->post('id_factura'));

						$this->session->set_flashdata('id_empresa', $this->input->post('id_empresa'));
						$this->session->set_flashdata('fecha', $this->input->post('fecha'));
						$this->session->set_flashdata('transaccion', $this->input->post('transaccion'));
						$this->session->set_flashdata('valor', $data['valor'] - $data['saldo']);
						$this->session->set_flashdata('id_forma_pago', $this->input->post('id_forma_pago'));
						$this->session->set_flashdata('id_cuenta', $this->input->post('id_cuenta'));
						$this->session->set_flashdata('observaciones', $this->input->post('observaciones'));
						$this->session->set_flashdata('readonly', true);

						redirect(base_url('administracion/movimientos/ingresar?id_empresa=' . $this->input->post('id_empresa')));
					}
					else
					{
						$this->movimiento_model->ingresarMovimiento($valores);
						$this->factura_model->actualizarFacturaSaldo($this->input->post('id_factura'));

						redirect(base_url('administracion/empresas/balance/' . $this->input->post('id_empresa')));
					}
				}
				else
				{
					$this->movimiento_model->ingresarMovimiento($valores);

					redirect(base_url('administracion/empresas/balance/' . $this->input->post('id_empresa')));
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function modificar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('movimiento_model');
			$this->load->model('factura_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
			$this->form_validation->set_rules('fecha', 'Fecha', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('transaccion', 'Transacción', 'trim|required');
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');
			$this->form_validation->set_rules('id_cuenta', 'Cuenta', 'trim|required|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|required|numeric');

			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->movimiento_model->getMovimientoDetalleRaw($id);
				$data['cuentas'] = $this->movimiento_model->comboCuentas();
				if (isset($data['detalle']['id_empresa']))
					$data['facturas'] = $this->factura_model->comboFacturas(array('id_empresa' => $data['detalle']['id_empresa']));
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();

				// validation not ok, send validation errors to the view
				$header['css'] = array(base_url('assets/css/plugins/datapicker/datepicker3.css')
				);

				$this->load->view('/header', $header);
				$this->load->view('/administracion/movimientos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$valores = $this->input->post();

				if ($this->input->post('id_factura'))
				{
					$data['valor'] = $this->input->post('valor');
					$data['saldo'] = $this->factura_model->getFacturaSaldo($this->input->post('id_factura'));

					if ($data['valor'] > $data['saldo'])
					{
						$valores['valor'] = $data['saldo'];

						$this->movimiento_model->modificarMovimiento($id, $valores);
						$this->factura_model->actualizarFacturaSaldo($this->input->post('id_factura'));

						$this->session->set_flashdata('id_empresa', $this->input->post('id_empresa'));
						$this->session->set_flashdata('fecha', $this->input->post('fecha'));
						$this->session->set_flashdata('transaccion', $this->input->post('transaccion'));
						$this->session->set_flashdata('valor', $data['valor'] - $data['saldo']);
						$this->session->set_flashdata('id_forma_pago', $this->input->post('id_forma_pago'));
						$this->session->set_flashdata('id_cuenta', $this->input->post('id_cuenta'));
						$this->session->set_flashdata('observaciones', $this->input->post('observaciones'));
						$this->session->set_flashdata('readonly', true);

						redirect(base_url('administracion/movimientos/ingresar?id_empresa=' . $this->input->post('id_empresa')));
					}
					else
					{
						$this->movimiento_model->modificarMovimiento($id, $valores);
						$this->factura_model->actualizarFacturaSaldo($this->input->post('id_factura'));

						redirect(base_url('administracion/empresas/balance/' . $this->input->post('id_empresa')));
					}
				}
				else
				{
					$this->movimiento_model->modificarMovimiento($id, $valores);

					redirect(base_url('administracion/empresas/balance/' . $this->input->post('id_empresa')));
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function transferir()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('movimiento_model');
			$this->load->model('factura_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			// set validation rules
			$this->form_validation->set_rules('fecha_debito', 'Fecha débito', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('fecha_credito', 'Fecha crédito', 'trim|alpha_dash');
			$this->form_validation->set_rules('id_cuenta_debito', 'Cuenta débito', 'trim|required|integer');
			$this->form_validation->set_rules('id_cuenta_credito', 'Cuenta crédito', 'trim|integer|differs[id_cuenta_debito]', array('differs' => 'La cuenta destino tiene que ser diferente a la cuenta origen.'));
			$this->form_validation->set_rules('valor_debito', 'Valor débito', 'trim|required|numeric');
			$this->form_validation->set_rules('valor_credito', 'Valor crédito', 'trim|numeric');
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');


			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['cuentas'] = $this->movimiento_model->comboCuentas();
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();


				// validation not ok, send validation errors to the view
				$header['css'] = array(base_url('assets/css/plugins/datapicker/datepicker3.css')
				);

				$this->load->view('/header', $header);
				$this->load->view('/administracion/movimientos/form_transferir', $data);
				$this->load->view('/footer');
			}
			else
			{
				$this->db->trans_begin();

				// form values
				$valores_debito['transaccion'] = 'G';
				$valores_debito['id_empresa'] = 'transferencia';
				$valores_debito['fecha'] = $this->input->post('fecha_debito');
				$valores_debito['id_cuenta'] = $this->input->post('id_cuenta_debito');
				$valores_debito['id_forma_pago'] = $this->input->post('id_forma_pago');
				$valores_debito['valor'] = $this->input->post('valor_debito');
				$valores_debito['observaciones'] = nl2br($this->input->post('observaciones'));
				$valores_debito['estado'] = 2;

				$this->movimiento_model->ingresarMovimiento($valores_debito);


				$valores_credito['transaccion'] = 'I';
				$valores_credito['id_empresa'] = 'transferencia';
				$valores_credito['fecha'] = (!empty($this->input->post('fecha_credito'))) ? $this->input->post('fecha_credito') : $this->input->post('fecha_debito');
				$valores_credito['id_cuenta'] = $this->input->post('id_cuenta_credito');
				$valores_credito['id_forma_pago'] = $this->input->post('id_forma_pago');
				$valores_credito['valor'] = (!empty($this->input->post('valor_credito'))) ? $this->input->post('valor_credito') : $this->input->post('valor_debito');
				$valores_credito['observaciones'] = nl2br($this->input->post('observaciones'));
				$valores_credito['estado'] = 2;

				$this->movimiento_model->ingresarMovimiento($valores_credito);


				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();

					$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';

					$this->load->view('/administracion/movimientos/', $data);
				}
				else
				{
					$this->db->trans_commit();

					redirect(base_url('administracion/movimientos/'));
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function conciliar_pago()
	{
		// models
		$this->load->model('movimiento_model');

		$data = $this->movimiento_model->conciliarPago($this->input->post('id'));

		echo json_encode($data);
	}


	public function convertir_moneda()
	{
		// models
		$this->load->model('movimiento_model');

		// load the library
		$this->load->library('geolib/Geolib');

		$geo = new Geolib();

		error_reporting(0);
		$data = $this->geolib->convert_currency('USD', 'ARS', 1);

		$this->movimiento_model->convertir_moneda(2, $data);

		if (isset($_GET['debug']))
			echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function importar()
	{
		// models
		$this->load->model('movimiento_model');
		$this->load->model('empresa_model');
		$this->load->model('factura_model');

		if ($this->is_logged_in('reseller'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			// set validation rules
			$this->form_validation->set_rules('id_tipo', 'Tipo de archivo', 'trim|required|integer');
			$this->form_validation->set_rules('texto', 'Texto', 'trim|required|min_length[5]');

			if ($this->form_validation->run() === false)
			{
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
				$data['tipos'] = array(1 => 'Débito Automático', 2 => 'Mercado Pago');

				$this->load->view('/header');
				$this->load->view('/administracion/movimientos/importar', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($this->input->post('id_tipo') == 1) // Debito automático
				{
					$lines = explode("\n", $this->input->post('texto'));

					if (!empty($lines))
					{
						if (strpos(trim($lines[0]), 'EMPRESA') !== 0)
						{
							// Manejar el error con un mensaje más amigable
							throw new Exception("El archivo que has subido no es válido. Asegúrate de que la primera línea comience con 'EMPRESA' y que el formato sea correcto (Reporte de Cobros.csv).");
						}
						foreach ($lines as $line)
						{
							$items = explode(';', $line);

							if ($items[0] != 'EMPRESA' && isset($items[7]) && !$this->movimiento_model->verificarSiExiste(array(preg_replace("/[^0-9,.]/", "", $items[7]))))
							{
								$data['id_empresa'] = $this->empresa_model->getEmpresaIdFromCodigo(502, $items[2]);

								$data['transaccion'] = 'I';
								$data['fecha'] = date('Y-m-d', strtotime($items[5]));
								$data['id_cuenta'] = 6521; // 6347 (C.C. Diego)
								$data['id_forma_pago'] = 5;
								$data['id_externo'] = preg_replace("/[^0-9,.]/", "", $items[7]); // ELIMINA CARACTERES NO NUMERICOS

								switch ($items[11])
								{
									case 'R02': // CUENTA CERRADA O SUSPENDIDA
										$data['estado'] = 10;
										break;
									case 'R03': // CUENTA INEXISTENTE
										$data['estado'] = 11;
										break;
									case 'R08':
										$data['estado'] = 6;
										break;
									case 'R10': // FALTA DE FONDOS
										$data['estado'] = 9;
										break;
									case 'R15': // BAJA DEL SERVICIO
										$data['estado'] = 12;
										break;
									case 'R20': // MONEDA DISTINTA A LA C
										$data['estado'] = 20;
										break;
									case 'R01': // SIN ESPECIFICAR
									case 'R04':
									case 'R05':
									case 'R06':
									case 'R07':
									case 'R09':
									case 'R11':
									case 'R12':
									case 'R13':
									case 'R14':
									case 'R16':
									case 'R17':
									case 'R18':
									case 'R19':
										$data['estado'] = 13;
										break;
									default: // DEBITADO
										$data['estado'] = 2;
								}

								$data['observaciones'] = 'BG' . ' | Orden: ' . $items[7];
								if (trim($items[12]) != false)
									$data['observaciones'] .= ' (' . trim($items[12]) . ')';

								$data['fecha_alta'] = date('Y-m-d H:i:s');
								$data['username_alta'] = 'BG';

								$facturas = $this->factura_model->facturasCobradasPorDebito($data['id_empresa']);

								if (isset($facturas))
								{
									foreach ($facturas as $factura)
									{
										$data['valor'] = $factura['saldo'];
										$data['id_factura'] = $factura['id'];
										$data['grupo'] = $factura['grupo'];

										$data['res'] = $this->movimiento_model->ingresarMovimiento($data);

										if ($data['estado'] == 2)
										{
											$factura['saldo'] = 0;
											$factura['fecha_modificacion'] = date('Y-m-d H:i:s');
											$factura['username_modificacion'] = 'BG';

											$data['saldo'] = $this->factura_model->actualizarFacturaSaldo($factura['id']);
										}
									}
								}
							}
						}

						redirect(base_url('administracion/movimientos?id_forma_pago=5'));
					}
				}
				elseif ($this->input->post('id_tipo') == 2) // Mercado Pago
				{
					$this->load->library('curl');

					$data = json_decode($this->curl->simple_get('https://cms.revisionalpha.com/administracion/ipn/mercadopago/' . $this->usuario->grupo . '?id=' . $this->input->post('texto') . '&json=true'), true);

					if ($id = $this->movimiento_model->getMovimientoIdFromIdExterno($this->input->post('texto')))
					{
						if ($id_factura = $this->movimiento_model->getMovimientoDetalle($id)['id_factura'])
						{
							redirect(base_url('administracion/facturas/detalle/' . $id_factura));
						}
						else
						{
							redirect(base_url('administracion/movimientos/'));
						}
					}

					echo 'No se registró el movimiento';
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function test()
	{
		// models
		$this->load->model('movimiento_model');

		$data = $this->movimiento_model->getMovimientoDetalleRaw($this->movimiento_model->getIdUltimoMovimiento());

		echo '<pre>' . print_r($data, true) . '</pre>';
	}



}
