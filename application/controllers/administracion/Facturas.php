<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Facturas extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			// Set default parameters if not provided
			$parametros['estado'] = $this->input->get('estado');
			$parametros['id_empresa_fiscal'] = $this->input->get('id_empresa_fiscal');
			$parametros['pendiente'] = $this->input->get('pendiente') ? $this->input->get('pendiente') : 'true';
			$parametros['operacion'] = $this->input->get('operacion');
			
			// Exclude credit notes by default
			if (!$this->input->get('estado')) {
				$parametros['excluir_notas'] = true;
			}
			
			// Get invoice totals
			$data['totales'] = $this->factura_model->getTotalFacturado();
			
			$data['facturas'] = $this->factura_model->getFacturas($parametros);
			
			$config['total_rows'] = $this->factura_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/facturas/index', $data) : $this->load->view('/administracion/facturas/empty', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta/facturas/'));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('factura_model');
			$this->load->model('movimiento_model');
			$this->load->model('nota_model');
			$this->load->model('archivo_model');
			
			// helpers and libraries
			$this->load->helper('text');
	

			$data['factura'] = $this->factura_model->getFacturaDetalle($id);
			$data['factura']['items'] = $this->factura_model->getFacturaItems($id);
			$data['movimientos'] = $this->movimiento_model->getMovimientos(array('id_factura'=>$id));
			$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>115, 'id_referencia'=>$id));
			$data['archivos'] = $this->archivo_model->getArchivos(array('id_referencia'=>115, 'id_padre'=>$id, 'per_page'=>1));
			
			if ($data['factura']['error'])
			{
				if ($data['factura']['anterior']['id'] = $this->factura_model->anterior($data['factura']['grupo'], $data['factura']['operacion'], $data['factura']['id_factura_tipo'], $data['factura']['numero_talonario'], $data['factura']['id']))
				{
					$data['factura']['anterior']['detalle'] = $this->factura_model->getFacturaDetalle($data['factura']['anterior']);
					$data['factura']['numero_factura'] = ++$data['factura']['anterior']['detalle']['numero_factura'];
	
					$raw['factura'] = $this->factura_model->getFacturaDetalleRaw($id);
					
					$url = 'http://wsaa.revisionalpha.com/comprobante/' . $raw['factura']['grupo']  . '/' . $raw['factura']['afip_cuit']  . '/' . $raw['factura']['id_afip']  . '/' . $raw['factura']['numero_talonario'] . '/' . $data['factura']['numero_factura'] . '/';
					
					$this->load->library('curl');
					$res = json_decode($this->curl->simple_get($url), true);
					
					$data['afip'] = $res['FECompConsultarResult']['ResultGet'];
					
					if ($data['afip']['DocNro'] == $raw['factura']['cuit'] && $data['afip']['ImpTotal'] == $raw['factura']['total_neto'] && $data['afip']['CbteTipo'] == $raw['factura']['id_afip'] && $data['afip']['PtoVta'] == $raw['factura']['numero_talonario'] && $data['afip']['FchServDesde'] == date('Ymd', strtotime($raw['factura']['fecha'])))
						
					{
						$data['afip']['accion'] = 'recalcular';
					}
					else
					{
						$data['afip']['accion'] = null;
					}
				}
				else
				{
					$data['afip']['accion'] = 'La factura no coincide con la de la AFIP.';
				}
				
			}
			

			$this->load->view('/header');
			$this->load->view('/administracion/facturas/detalle', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta/facturas/detalle/' . $id));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function recalcular($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			
			
			$raw['factura'] = $this->factura_model->getFacturaDetalleRaw($id);
			
			if ($raw['factura']['error'])
			{
				$raw['factura']['anterior']['id'] = $this->factura_model->anterior($raw['factura']['grupo'], $raw['factura']['operacion'], $raw['factura']['id_factura_tipo'], $raw['factura']['numero_talonario'], $raw['factura']['id']);
				$raw['factura']['anterior']['detalle'] = $this->factura_model->getFacturaDetalle($raw['factura']['anterior']);
				$raw['factura']['numero_factura'] = ++$raw['factura']['anterior']['detalle']['numero_factura'];
				
				if ($raw['factura']['error'])
				{
					$url = 'http://wsaa.revisionalpha.com/comprobante/' . $raw['factura']['grupo']  . '/' . $raw['factura']['afip_cuit']  . '/' . $raw['factura']['id_afip']  . '/' . $raw['factura']['numero_talonario'] . '/' . $raw['factura']['numero_factura'] . '/';
					
					$this->load->library('curl');
					$res = json_decode($this->curl->simple_get($url), true);
					
					$data['afip'] = $res['FECompConsultarResult']['ResultGet'];
				
					if ($data['afip']['DocNro'] == $raw['factura']['cuit'] && $data['afip']['ImpTotal'] == $raw['factura']['total_neto'] && $data['afip']['CbteTipo'] == $raw['factura']['id_afip'] && $data['afip']['PtoVta'] == $raw['factura']['numero_talonario'] && $data['afip']['FchServDesde'] == date('Ymd', strtotime($raw['factura']['fecha'])))
						
					{
						$valores['numero_factura'] = $raw['factura']['numero_factura'];
						$valores['cae_numero'] = $data['afip']['CodAutorizacion'];
						$valores['cae_vencimiento'] = $data['afip']['FchVto'];
						$valores['error'] = false;
	 					$valores['estado'] = 2;
 					}
 					else
 					{
	 					$valores['error'] = 'La factura no coincide con la de la AFIP.';
 					}
 					
 					$this->factura_model->modificarFactura($id, $valores);
				}
			}
			
			if (!isset($_GET['debug'])) redirect(base_url('administracion/facturas/detalle/' . $id));
		}
	}
	
	
	public function ingresar()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');
				
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id_empresa_fiscal', 'Empresa fiscal', 'trim|required|integer');
			$this->form_validation->set_rules('operacion', 'Operación', 'trim|required');
			$this->form_validation->set_rules('id_factura_tipo', 'Tipo de factura', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('bruto', 'Valor sin I.V.A.', 'trim|required|decimal');
			$this->form_validation->set_rules('total_neto', 'Total', 'trim|required|decimal');
			
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|decimal');
			$this->form_validation->set_rules('SUBTOTAL105', 'SUBTOTAL105', 'trim|decimal');
			$this->form_validation->set_rules('IMP105', 'IMP105', 'trim|decimal');
			$this->form_validation->set_rules('NO_GRAVADOS105', 'NO_GRAVADOS105', 'trim|decimal');
			$this->form_validation->set_rules('SUBTOTAL210', 'SUBTOTAL210', 'trim|decimal');
			$this->form_validation->set_rules('IMP210', 'IMP210', 'trim|decimal');
			$this->form_validation->set_rules('NO_GRAVADOS210', 'NO_GRAVADOS210', 'trim|decimal');
			$this->form_validation->set_rules('SUBTOTAL270', 'SUBTOTAL270', 'trim|decimal');
			$this->form_validation->set_rules('IMP270', 'IMP270', 'trim|decimal');
			$this->form_validation->set_rules('NO_GRAVADOS270', 'NO_GRAVADOS270', 'trim|decimal');
			$this->form_validation->set_rules('EXENTO', 'EXENTO', 'trim|decimal');
			$this->form_validation->set_rules('RETENCION_IVA', 'RETENCION_IVA', 'trim|decimal');
			$this->form_validation->set_rules('RETENCION_IIBB', 'RETENCION_IIBB', 'trim|decimal');
			$this->form_validation->set_rules('RETENCIONES_GENERALES', 'RETENCIONES_GENERALES', 'trim|decimal');
			$this->form_validation->set_rules('PERCEPCION_IIBB', 'PERCEPCION_IIBB', 'trim|decimal');
			
			if ($this->form_validation->run() === false)
			{
				$default['empresa'] = $this->empresa_model->getEmpresaDetalle($this->input->get('id_empresa'));
				$default['id_empresa_fiscal'] = $default['empresa']['id_empresa_fiscal'];
				$default['fecha'] = date('d-m-Y', strtotime('today UTC'));
				$default['id_forma_pago'] = $default['empresa']['id_forma_pago'];
				$default['id_factura_tipo'] = $default['empresa']['id_factura_tipo'];

				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['monedas'] = $this->sys_model->comboMonedas();
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();
				$data['operaciones'] = array('C'=>'Compra', 'V'=>'Venta');
				$data['facturas_tipo'] = $this->factura_model->comboFacturasTipo();
				$data['categorias_generales'] = $this->sys_model->comboCategoriasGenerales();
	
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/facturas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$this->db->trans_begin();
				
				$data['id'] = $this->factura_model->ingresarFactura($this->input->post());
				
				if (isset($data['id']))
				{
					// Factura Items
					$factura_items['id_factura'] = $data['id'];
					$factura_items['id_categoria'] = ($this->input->post('id_categoria')) ? $this->input->post('id_categoria') : null;
					$factura_items['valor'] = $this->input->post('bruto');
					$factura_items['descuento'] = $this->input->post('descuento');
					$factura_items['descripcion'] = $this->input->post('descripcion');
					
					$this->factura_model->ingresarFacturaItems($factura_items);
					
					
					if ($this->db->trans_status() === false)
					{
						$this->db->trans_rollback();
						
						$data['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
						
						$this->load->view('/administracion/facturas/error/', $data);
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
					
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/', $data);
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
			$this->load->model('factura_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');
				
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');
			$this->form_validation->set_rules('numero_talonario', 'Talonario', 'trim|required|integer');
			$this->form_validation->set_rules('numero_factura', 'Número de factura', 'trim|required|integer');
			$this->form_validation->set_rules('cae_numero', 'Número de CAE', 'trim|integer');
			$this->form_validation->set_rules('cae_vencimiento', 'Vencimiento de CAE', 'trim');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->factura_model->getFacturaDetalleRaw($id);
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();
			
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/facturas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->factura_model->modificarFactura($id, $this->input->post()))
				{
					redirect(base_url('administracion/facturas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->factura_model->getFacturaDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'facturas'))
				{
					$res = $this->sys_model->eliminar($id, 'facturas');
					
					redirect(base_url('administracion/facturas/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function modificar_item($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			$this->load->model('sys_model');
				
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('id', 'ID Item', 'trim|required|integer');
			$this->form_validation->set_rules('id_factura', 'ID Factura', 'trim|required|integer');
			$this->form_validation->set_rules('id_categoria', 'ID Categoría', 'trim|required|is_natural_no_zero');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|required|min_length[5]');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|decimal');
			$this->form_validation->set_rules('valor', 'Valor sin I.V.A.', 'trim|required|decimal');
			
			if ($this->form_validation->run() === false)
			{
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->factura_model->getFacturaItemDetalle($id);
				$data['categorias_generales'] = $this->sys_model->comboCategoriasGenerales();
			
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/form_items', $data);
				$this->load->view('/footer');
			}
			else
			{
				$this->db->trans_begin();
				
				if (!empty($this->factura_model->modificarFacturaItem($id, $this->input->post())))
				{
					$data = $this->factura_model->confeccionarFactura($this->input->post('id_factura'));
					
					if ($this->db->trans_status() === false)
					{
						$this->db->trans_rollback();
						
						$data['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
						
						$this->load->view('/administracion/facturas/error/', $data);
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
					
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/', $data);
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar_item($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID Item', 'trim|required|integer');
			$this->form_validation->set_rules('id_factura', 'ID Factura', 'trim|required|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->factura_model->getFacturaItemDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/eliminar_item', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'facturas_items'))
				{
					if ($this->factura_model->eliminar($id))
					{
						$data = $this->factura_model->confeccionarFactura($this->input->post('id_factura'));
					
						redirect(base_url('administracion/facturas/detalle/' . $this->input->post('id_factura')));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
						$this->load->view('/administracion/facturas/error/');
					}
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function marcar_como_impresa($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->factura_model->getFacturaDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/marcar_como_impresa', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($this->input->post('id'), 'facturas'))
				{
					$res = $this->factura_model->cambiarEstado($this->input->post('id'), 2);
					
					redirect(base_url('administracion/facturas/detalle/' . $this->input->post('id')));
				}
				else
				{
					// form values
					$data['detalle'] = $this->factura_model->getFacturaDetalle($id);
				
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/header');
					$this->load->view('/administracion/facturas/marcar_como_impresa', $data);
					$this->load->view('/footer');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar_nota_de_credito($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('factura_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->factura_model->getFacturaDetalleRaw($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/nota', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'facturas'))
				{
					$res = $this->factura_model->ingresarNodaDeCredito($id);
					
					redirect(base_url('administracion/facturas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/facturas/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function comunicar($id)
	{
		// models
		$this->load->model('factura_model');	
		
		$data = $this->factura_model->comunicarFactura($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function servicios_para_facturar()
	{
		// models
		$this->load->model('servicio_model');	
		
		$data = $this->servicio_model->serviciosParaFacturar(500);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function facturar_servicios()
	{
		// models
		$this->load->model('servicio_model');
		$this->load->model('factura_model');
		$this->load->model('empresa_model');
		
		//$cantidad = (!empty($this->input->post('cantidad'))) ? $this->input->post('cantidad') : 1; // Corregir, no funciona con esto
		$servicios = $this->servicio_model->serviciosParaFacturar(5);
		
		if (isset($servicios))
		{
			foreach ($servicios as $obj)
			{
				if ($obj['id_factura_tipo'] && $obj['id_forma_pago'])
				{
					$this->db->trans_begin();
					
					if ($id = $this->factura_model->verificarSiExiste($obj['grupo'], $obj['operacion'], $obj['id_factura_tipo'], $obj['id_forma_pago'], $obj['id_moneda'], $obj['id_empresa_fiscal']))
					{
						$obj['id_factura'] = $id;
					}
					elseif ($obj['valor'] > $obj['descuento'])
					{
						$obj['total_neto'] = 0;
						$obj['estado'] = 8;
						
						$obj['id_factura'] = $this->factura_model->ingresarFactura($obj);
					}
					
					
					// Factura Items
					if (isset($obj['id_factura']))
					{
						$factura_items['grupo'] = $obj['grupo'];
						$factura_items['id_factura'] = $obj['id_factura'];
						$factura_items['id_categoria'] = $obj['id_categoria'];
				
						$factura_items['valor'] = $obj['valor'];
						$factura_items['descuento'] = $obj['descuento'];
						
						$factura_items['username_alta'] = $obj['username_alta'];
						
						if (stripos($obj['descripcion'], '{FECHA}') == false && stripos($obj['descripcion'], '{ANTERIOR}') == false && stripos($obj['descripcion'], '{SIGUIENTE}') == false) $obj['descripcion'] .= ' mes {FECHA}.';
						
						if ($obj['frecuencia'] == 1)
						{
							$factura_items['descripcion'] = str_replace('{FECHA}', date('m-Y', strtotime($obj['actual'])), $obj['descripcion']);
							$factura_items['descripcion'] = str_replace('{ANTERIOR}', date('m-Y', strtotime("-1 Month", strtotime($obj['actual']))), $factura_items['descripcion']);
							$factura_items['descripcion'] = str_replace('{SIGUIENTE}', date('m-Y', strtotime("+1 Month", strtotime($obj['actual']))), $factura_items['descripcion']);
						}
						else
						{
							$factura_items['descripcion'] = str_replace('{FECHA}', date('m-Y',strtotime($obj['actual'])) . ' al ' . date('m-Y', strtotime("-1 Month", strtotime($obj['proxima']))), $obj['descripcion']);
						}
						
						$factura_items['descripcion'] = strip_tags($factura_items['descripcion']);
						
						$this->factura_model->ingresarFacturaItems($factura_items);
						
						unset($factura_items);
					}
					
					
					// Servicio
					if ((date('Y-m', strtotime($obj['actual'])) == date('Y-m', strtotime($obj['caduca']))) && (date('Y-m-d', strtotime($obj['actual'])) >= date('Y-m-d', strtotime($obj['caduca']))))
					{
						$servicio['estado'] = 2;
						$servicio['ultima'] = $obj['actual'];
					}
					
					else
					{
						$servicio['ultima'] = $obj['actual'];
						$servicio['proxima'] = $obj['proxima'];
					}
					
					$this->servicio_model->facturado($obj['id'], $servicio);
					
					unset($servicio);
					
					
					if ($this->db->trans_status() === false)
					{
						$this->db->trans_rollback();
						
						$obj['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
					}
					else
					{
						$this->db->trans_commit();
					}
					
					$data[] = $obj;
				}
				else
				{
					$data = $this->empresa_model->cambiarEstado($obj['id_empresa'], 3);
				}
			}
			
			if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
		}
		else
		{
			if (isset($_GET['debug'])) echo '<pre>No hay servicios para facturar.</pre>';
		}
	}
	
	
	public function facturar_proyectos()
	{
		// models
		$this->load->model('proyecto_model');
		$this->load->model('factura_model');
		
		$cantidad = (!empty($this->input->post('cantidad'))) ? $this->input->post('cantidad') : 1;
		$proyectos = $this->proyecto_model->proyectosParaFacturar($cantidad);
		
		if (isset($proyectos))
		{
			foreach ($proyectos as $obj)
			{
				$this->db->trans_begin();
				
				if ($obj['valor'] > $obj['descuento'])
				{
					$obj['id_moneda'] = 1;
					$obj['total_neto'] = 0;
					$obj['estado'] = 8;
					
					$obj['id_factura'] = $this->factura_model->ingresarFactura($obj);
				}
				
				
				// Factura Items
				if (isset($obj['id_factura']))
				{
					$factura_items['grupo'] = $obj['grupo'];
					$factura_items['id_factura'] = $obj['id_factura'];
					$factura_items['id_categoria'] = $obj['id_categoria'];
			
					$factura_items['valor'] = $obj['valor'];
					$factura_items['descuento'] = $obj['descuento'];
					
					$factura_items['username_alta'] = $obj['username_alta'];
					$factura_items['descripcion'] = strip_tags($obj['descripcion']);
					
					$this->factura_model->ingresarFacturaItems($factura_items);
					
					unset($factura_items);
				}
				
				
				// Proyecto
				$this->proyecto_model->facturado($obj['id']);
				
				unset($servicio);
				
				
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$obj['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
				
				$data[] = $obj;
			}
			
			if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
		}
		else
		{
			if (isset($_GET['debug'])) echo '<pre>No hay proyectos para facturar.</pre>';
		}
	}
	
	
	public function confeccionar_factura($id)
	{
		// models
		$this->load->model('factura_model');
		
		$data = $this->factura_model->confeccionarFactura($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function confeccionar_facturas()
	{
		// models
		$this->load->model('factura_model');
		
		$facturas = $this->factura_model->facturasParaConfeccionar(7200, 50);
		
		if (isset($facturas))
		{
			foreach ($facturas as $obj)
			{
				$data[] = $this->factura_model->confeccionarFactura($obj['id']);
			}
			
			if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
		}
	}
	
	
	public function facturas_para_confeccionar()
	{
		// models
		$this->load->model('factura_model');
		
		$data = $this->factura_model->facturasParaConfeccionar(7200);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function notificacion_nueva_factura()
	{
		// models
		$this->load->model('factura_model');
		$this->load->model('comunicacion_model');
		
		$facturas = $this->factura_model->comunicarFacturasNuevas();
		
		if (isset($facturas))
		{
			foreach ($facturas as $obj)
			{
				$this->db->trans_begin();
		
				$factura = $this->factura_model->comunicarFactura($obj['id']);
				$data[] = $this->comunicacion_model->ingresarComunicacion($factura['id_contacto'], 1, $obj['id'], $factura);
		
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo ingresar la comunicación, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else
		{
			$data['error'] = 'No hay facturas a comunicar';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function notificacion_facturas_a_vencer()
	{
		// models
		$this->load->model('factura_model');
		$this->load->model('comunicacion_model');
		
		$facturas = $this->factura_model->comunicarFacturasAVencer();
		
		if (isset($facturas))
		{
			foreach ($facturas as $obj)
			{
				$this->db->trans_begin();
		
				$factura = $this->factura_model->comunicarFactura($obj['id']);
				$data[] = $this->comunicacion_model->ingresarComunicacion($factura['id_contacto'], 2, $obj['id'], $factura);
		
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo ingresar la comunicación, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else
		{
			$data['error'] = 'No hay facturas a comunicar';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function notificacion_facturas_vencidas()
	{
		// models
		$this->load->model('factura_model');
		$this->load->model('comunicacion_model');
		
		$facturas = $this->factura_model->comunicarFacturasVencidas();
		
		if (isset($facturas))
		{
			foreach ($facturas as $obj)
			{
				$this->db->trans_begin();
		
				$factura = $this->factura_model->comunicarFactura($obj['id']);
				$data[] = $this->comunicacion_model->ingresarComunicacion($factura['id_contacto'], 4, $obj['id'], $factura);
		
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo ingresar la comunicación, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else
		{
			$data['error'] = 'No hay facturas a comunicar';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function notificacion_suspension_de_servicios()
	{
		// models
		$this->load->model('factura_model');
		$this->load->model('comunicacion_model');
		
		$facturas = $this->factura_model->comunicarSuspensionDeServicios(45);
		
		if (isset($facturas))
		{
			foreach ($facturas as $obj)
			{
				$this->db->trans_begin();
		
				$data[] = $this->comunicacion_model->ingresarComunicacion($obj['id_contacto'], 5, $obj['id'], $obj);
		
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo ingresar la comunicación, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else
		{
			$data['error'] = 'No hay facturas a comunicar';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function importar()
	{
		// models
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
				$data['tipos'] = array(2=>'cPanel', 1=>'Dominios');
			
				$this->load->view('/header');
				$this->load->view('/administracion/facturas/importar', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($this->input->post('id_tipo') == 1)
				{
					$lines = explode("\n", $this->input->post('texto')); // Corregir, cuando el pago es mediante tarjeta de crédito, existe un salto de línea en el campo 10 que hace que el programa lo interprete como una nueva línea y hace que se corte
					
					if (!empty($lines))
					{
						foreach ($lines as $line)
						{
							$items = explode(',', str_replace('"', '', $line));
							
							if (is_numeric($items[0]) && !$this->factura_model->verificarFacturaMyDomain($items[0]) && $items[8] == 'PAID')
							{
								/*
									[0] => Reference #
								    [1] => Item
								    [2] => Term Start Date
								    [3] => Term End Date
								    [4] => Original Amount Due
								    [5] => Credit Applied
								    [6] => VAT
								    [7] => Total Amount
								    [8] => Status on 04/23/2015
								    [9] => Payment Method
								    [10] => Paid Date
								*/
								
								/* INGRESO LA FACTURA */
								$factura['grupo'] = 502;
								$factura['id_empresa_fiscal'] = 6373;
								$factura['id_factura_tipo'] = 11;
								$factura['operacion'] = 'C';
								$factura['numero_talonario'] = 1;
								$factura['numero_factura'] = $items[0];
								$factura['fecha'] = date('Y-m-d', strtotime($items[2]));
								$factura['vencimiento'] = date('Y-m-d', strtotime($items[2]));
								$factura['bruto'] = str_replace('$', '', $items[7]);
								$factura['total_neto'] = $factura['bruto'];
								$factura['saldo'] = $factura['bruto'];
								$factura['id_moneda'] = 2;
								$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
								$factura['id_forma_pago'] = 10;
								$factura['estado'] = 2;
								$factura['fecha_alta'] = date('Y-m-d H:i:s');
								$factura['username_alta'] = 'cron';
								
								$data['id'] = $this->factura_model->ingresarFactura($factura);
					
					
								/* INGRESO EL ITEM DE LA FACTURA */
								$factura_item['grupo'] = 502;
								$factura_item['id_factura'] = $data['id'];
								$factura_item['id_categoria'] = 37;
								$factura_item['descripcion'] = $items[1];
								$factura_item['valor'] = $factura['bruto'];
								$factura_item['descuento'] = 0;
								$factura_item['fecha_alta'] = date('Y-m-d H:i:s');
								$factura_item['username_alta'] = 'cron';
								
								$this->factura_model->ingresarFacturaItems($factura_item);
							}
						}
						
						$mensaje = (isset($factura['id_empresa_fiscal'])) ? 'Las facturas fueron ingresadas correctamente' : 'Las facturas ya habían sido ingresadas';
						
						redirect(base_url('administracion/facturas?id_empresa_fiscal=6373'));
					}
				}	
				
				elseif ($this->input->post('id_tipo') == 2)
				{
					$factura = $this->importarFacturasDeCpanel($this->input->post('texto'));
					
					$factura['grupo'] = 502;
					$factura['id_empresa_fiscal'] = 6506;
					$factura['id_factura_tipo'] = 11;
					$factura['operacion'] = 'C';
					$factura['numero_talonario'] = 1;
					$factura['total_neto'] = $factura['bruto'];
					$factura['saldo'] = $factura['bruto'];
					$factura['id_moneda'] = 2;
					$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
					$factura['id_forma_pago'] = 10;
					$factura['estado'] = 2;
					
					$data['id'] = $this->factura_model->verificarSiExiste($factura['grupo'], $factura['operacion'], $factura['id_factura_tipo'], $factura['id_forma_pago'], $factura['id_moneda'], $factura['id_empresa_fiscal'], $factura['estado'], $factura['numero_talonario'], $factura['numero_factura']);
					
					if (!isset($data['id']))
					{
						$this->db->trans_begin();
						
						$data['id'] = $this->factura_model->ingresarFactura($factura);
						
						if (isset($data['id']))
						{
							// Factura Items
							$factura_items['grupo'] = $factura['grupo'];
							$factura_items['id_categoria'] = 81;
							$factura_items['id_factura'] = $data['id'];
							$factura_items['valor'] = $factura['bruto'];
							$factura_items['descuento'] = 0;
							$factura_items['descripcion'] = $factura['descripcion'];
							
							$this->factura_model->ingresarFacturaItems($factura_items);
							
							
							if ($this->db->trans_status() === false)
							{
								$this->db->trans_rollback();
								
								$data['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
								
								$this->load->view('/administracion/facturas/error/', $data);
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
							
							$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
							
							$this->load->view('/administracion/facturas/error/', $data);
						}
					}
					else
					{
						redirect(base_url('administracion/facturas/detalle/' . $data['id'] . '/factura-ingresada-previamente'));
					}
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function importar_mailbox($id_tipo)
	{
		// models
		$this->load->model('mailbox_model');
		$this->load->model('factura_model');
		
		$parametros['id_tipo'] = $id_tipo;

		$data['emails'] = $this->mailbox_model->getEmails($parametros);
		
		foreach ($data['emails'] as $obj)
		{
			$this->mailbox_model->marcarComoLeido($obj['id']);
			$data['detalle'] = $this->mailbox_model->getEmailDetalleRaw($obj['id'], array('modo'=>'raw'));
			
			if ($id_tipo == 11)
			{
				$factura = $this->importarFacturasDeCpanel($data['detalle']['body_html']);
				
				$factura['grupo'] = 502;
				$factura['id_empresa_fiscal'] = 6506;
				$factura['id_factura_tipo'] = 11;
				$factura['operacion'] = 'C';
				$factura['numero_talonario'] = 1;
				$factura['total_neto'] = $factura['bruto'];
				$factura['saldo'] = $factura['bruto'];
				$factura['id_moneda'] = 2;
				$factura['cambio'] = $this->factura_model->getCambioMoneda($factura['id_moneda']);
				$factura['id_forma_pago'] = 10;
				$factura['estado'] = 2;
				
				$data['id'] = $this->factura_model->verificarSiExiste($factura['grupo'], $factura['operacion'], $factura['id_factura_tipo'], $factura['id_forma_pago'], $factura['id_moneda'], $factura['id_empresa_fiscal'], $factura['estado'], $factura['numero_talonario'], $factura['numero_factura']);
				
				if (!isset($data['id']))
				{
					$this->db->trans_begin();
					
					$factura['grupo'] = 502;
					$factura['username_alta'] = 'cron';
					
					$data['id'] = $this->factura_model->ingresarFactura($factura);
					
					if (isset($data['id']))
					{
						// Factura Items
						$factura_items['grupo'] = $factura['grupo'];
						$factura_items['username_alta'] = 'cron';
						$factura_items['id_categoria'] = 81;
						$factura_items['id_factura'] = $data['id'];
						$factura_items['valor'] = $factura['bruto'];
						$factura_items['descuento'] = 0;
						$factura_items['descripcion'] = $factura['descripcion'];
						
						$this->factura_model->ingresarFacturaItems($factura_items);
						
						
						if ($this->db->trans_status() === false)
						{
							$this->db->trans_rollback();
							
							$data['error'] = 'Ha habido un problema y no se pudo ingresar la factura, por favor intenta más tarde';
						}
						else
						{
							$this->db->trans_commit();
						}
					}
					else
					{
						$this->db->trans_rollback();
						
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					}
				}
				
				if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
			}
		}
	}
	
	
	function importarFacturasDeCpanel($texto)
	{
		$buscar = array('Pagada');
		$reemplazar = array('Paid');
		$texto = str_replace($buscar, $reemplazar, $texto);
		
		$patron['factura'] = '/This is a payment receipt for Invoice (\d+) sent on (\d\d\/\d\d\/\d\d\d\d)/i';
	
		$patron['fecha'] = '/Monthly License - \d{0,3}\.\d{0,3}\.\d{0,3}\.\d{0,3} \((\d\d\/\d\d\/\d\d\d\d) - \d\d\/\d\d\/\d\d\d\d\) \$\d+\.\d\d USD/i';
	
		$patron['valor'] = '/Total Paid\: \$(\d+\.\d\d) USD/i';
	
		$patron['estado'] = '/Status: (\w+)/i';
		
		
		preg_match($patron['factura'], $texto, $coincidencias_factura);
		preg_match($patron['fecha'], $texto, $coincidencias_fecha);
		
		preg_match($patron['valor'], $texto, $coincidencias_valor);
		preg_match($patron['estado'], $texto, $coincidencias_estado);
		
		
		if (is_numeric($coincidencias_factura[1]) && ($coincidencias_estado[1] == 'Paid' || $coincidencias_estado[1] == 'Pago'))
		{
			$res['numero_factura'] = $coincidencias_factura[1];
			$res['fecha'] = date('Y-m-d', strtotime($coincidencias_factura[2]));
			$res['vencimiento'] = (isset($coincidencias_fecha[1])) ? date('Y-m-d', strtotime($coincidencias_fecha[1])) : $res['fecha'];
			$res['bruto'] = $coincidencias_valor[1];
			$res['descripcion'] = $texto;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function exportar($frecuencia, $ano, $periodo, $operacion)
	{
		// models
		$this->load->model('factura_model');
		
		// helpers and libraries
		$this->load->dbutil();
		$this->load->helper('download');

		$archivo = ($operacion == 'C') ? 'COMPROBANTES_COMPRA_' : 'COMPROBANTES_VENTA_';
		$archivo .= strtoupper($frecuencia) . '_';
		$archivo .= $ano . '-' . $periodo . '.CSV';
			
		$facturas = $this->factura_model->exportar(strtolower($frecuencia), $ano, $periodo, $operacion);
		$data = ltrim(strstr($this->dbutil->csv_from_result($facturas, ';', "\r\n"), "\r\n"));
		
		force_download($archivo, $data);
	}


}
