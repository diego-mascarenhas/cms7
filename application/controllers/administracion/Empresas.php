<?php defined('BASEPATH') or exit('No direct script access allowed');


class Empresas extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->library('pagination');
	

			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			if ($this->input->get('estado')) $parametros['estado'] = $this->input->get('estado');

			
			$data['empresas'] = $this->empresa_model->getEmpresas($parametros);
			
			$config['total_rows'] = $this->empresa_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/empresas/index', $data) : $this->load->view('/administracion/empresas/empty');
			$this->load->view('/footer');
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
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->helper('text');
			
			
			$data['detalle'] = $this->empresa_model->getEmpresaDetalle($id);
			
			if (isset($data['detalle']))
			{
				$this->load->model('contacto_model');
				$data['contactos'] = $this->contacto_model->getContactos(array('id_empresa'=>$id));
				
				$this->load->model('servicio_model');
				$data['servicios'] = $this->servicio_model->getServicios(array('id_empresa'=>$id, 'per_page'=>100));
				
				$this->load->model('proyecto_model');
				$data['proyectos'] = $this->proyecto_model->getProyectos(array('id_empresa'=>$id));
				
				$this->load->model('factura_model');
				$data['facturas'] = $this->factura_model->getFacturas(array('id_empresa'=>$id));

				$this->load->model('nota_model');
				$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>112, 'id_referencia'=>$id));
				
				$data['grafico'] = $this->graficoSparkline(array('id_empresa'=>$id));
				
				
				$this->load->view('/header');
				$this->load->view('/administracion/empresas/detalle', $data);
				//$this->load->view('/debug', array('debug'=>array($data['detalle'])));
				$this->load->view('/footer');
			}
			else
			{
				$this->load->view('/401');
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
		
	
	public function ingresar()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('empresa_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|integer');
			$this->form_validation->set_rules('referido', 'Referido', 'trim|integer');
			$this->form_validation->set_rules('domicilio', 'Domicilio', 'trim|min_length[3]');
			$this->form_validation->set_rules('id_localidad', 'Localidad', 'integer');
			$this->form_validation->set_rules('telefono', 'telefono', 'trim');
			$this->form_validation->set_rules('web', 'Web', 'trim|valid_url');
			$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|min_length[10]');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['categorias'] = $this->combos->categoriasGestion(1);
				$data['referidos'] = $this->empresa_model->comboEmpresas();
				$data['paises'] = $this->sys_model->comboPaises();
				
				if (isset($data['detalle']['id_localidad']))
				{
					$data['ubicacion'] = $this->sys_model->localidadProvinciaPais($data['detalle']['id_localidad']);
				}
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/administracion/empresas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->empresa_model->ingresarEmpresa($this->input->post()))
				{
					redirect(base_url('administracion/empresas/detalle/' . $data));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/empresas/error/');
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
			$this->load->model('empresa_model');
			$this->load->model('contacto_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|integer');
			$this->form_validation->set_rules('referido', 'Referido', 'trim|integer');
			$this->form_validation->set_rules('domicilio', 'Domicilio', 'trim|min_length[3]');
			$this->form_validation->set_rules('id_localidad', 'Localidad', 'integer');
			$this->form_validation->set_rules('telefono', 'telefono', 'trim');
			$this->form_validation->set_rules('web', 'Web', 'trim|valid_url');
			$this->form_validation->set_rules('observaciones', 'Observaciones', 'trim|min_length[10]');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->empresa_model->getEmpresaDetalleRaw($id);
				$data['categorias'] = $this->combos->categoriasGestion(1, $data['detalle']['id_categoria']);
				$data['contactos'] = $this->contacto_model->comboContactos(array('id_empresa'=>$data['detalle']['id']));
				$data['referidos'] = $this->empresa_model->comboEmpresas();
				$data['paises'] = $this->sys_model->comboPaises();
				
				if (isset($data['detalle']['id_localidad']))
				{
					$data['ubicacion'] = $this->sys_model->localidadProvinciaPais($data['detalle']['id_localidad']);
				}
				
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/sweetalert/sweetalert.css')
								);
								
				$this->load->view('/header', $header);
				$this->load->view('/administracion/empresas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->empresa_model->modificarEmpresa($id, $this->input->post()))
				{
					redirect(base_url('administracion/empresas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/empresas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function preferencias($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('empresa_model');
			$this->load->model('contacto_model');
			$this->load->model('factura_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_contacto', 'Contacto', 'trim|required|integer');
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|integer');
			$this->form_validation->set_rules('id_factura_tipo', 'Tipo de factura', 'trim|integer');
			$this->form_validation->set_rules('codigo', 'Código', 'trim|min_length[3]');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->empresa_model->getEmpresaDetalleRaw($id);
				$data['contactos'] = $this->contacto_model->comboContactos(array('id_empresa'=>$data['detalle']['id']));
				$data['facturas_tipo'] = $this->factura_model->comboFacturasTipo();
				$data['formas_pago'] = $this->sys_model->comboFormasDePago();

				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/sweetalert/sweetalert.css')
								);
								
				$this->load->view('/header', $header);
				$this->load->view('/administracion/empresas/preferencias', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->empresa_model->modificarEmpresa($id, $this->input->post()))
				{
					redirect(base_url('administracion/empresas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/empresas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function actualizar_datos_fiscales($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('empresa_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID Empresa Fiscal', 'integer');
			$this->form_validation->set_rules('id_empresa', 'ID Empresa', 'integer');
			
			$this->form_validation->set_rules('id_condicion_iva', 'Condición fiscal', 'trim|required|integer');
			
			if ($this->input->post('id_condicion_iva') == 3) // Consumidor Final
			{
				//$this->form_validation->set_rules('cuit', 'DNI', 'trim|required|valid_dni');
			}
			elseif ($this->input->post('id_condicion_iva') == 2) // Monotributista
			{
				//$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
			}
			elseif ($this->input->post('id_condicion_iva') == 1) // Responsable Inscripto
			{
				//$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
				$this->form_validation->set_rules('razon_social', 'Razón Social', 'trim|required|min_length[3]');
			}
			else // Exento
			{
				$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required');
				$this->form_validation->set_rules('razon_social', 'Razón Social', 'trim|required|min_length[3]');
			}
			
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : ($this->empresa_model->getDatosFiscalesDetalle($this->empresa_model->getDatosFiscalesIdFromIdEmpresa($id)));
				$data['detalle']['id_empresa'] = $id;
				$data['detalle']['condiciones_fiscales'] = $this->sys_model->comboCondicionesFiscales();		
				
				$this->load->view('/header');
				$this->load->view('/administracion/empresas/form_datos_fiscales', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->empresa_model->actualizarDatosFiscales($this->input->post()))
				{
					redirect(base_url('administracion/empresas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/empresas/error/');
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
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->empresa_model->getEmpresaDetalleRaw($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/empresas/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'empresas'))
				{
					$res = $this->sys_model->eliminar($id, 'empresas');
					
					redirect(base_url('administracion/empresas/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/empresas/error/');
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
	
	
	public function balance($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('movimiento_model');
			$this->load->model('empresa_model');
			
			$parametros['id_empresa'] = $id;
			
			$data['balance'] = $this->movimiento_model->getBalance($parametros);
			$data['empresa'] = $this->empresa_model->getEmpresaDetalle($id);
			
			$this->load->view('/header');
			$this->load->view('/administracion/empresas/balance', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function facturas_y_pagos($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('factura_model');
			$this->load->model('movimiento_model');
			$this->load->model('empresa_model');
			
			
			$data['facturas'] = $this->factura_model->getFacturas(array('id_empresa'=>$id, 'estado'=>2, 'per_page'=>500, 'order_by'=>'facturas.id', 'order'=>'DESC'));
			$data['movimientos'] = $this->movimiento_model->getMovimientos(array('id_empresa'=>$id, 'per_page'=>500));
			$data['empresa'] = $this->empresa_model->getEmpresaDetalle($id);
			
			$this->load->view('/header');
			$this->load->view('/administracion/empresas/facturas_y_pagos', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function facturas_con_detalle($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('factura_model');
			$this->load->model('movimiento_model');
			$this->load->model('empresa_model');
			
			
			$row = $this->factura_model->getFacturas(array('id_empresa'=>$id, 'estado'=>2, 'per_page'=>500, 'order_by'=>'facturas.id', 'order'=>'DESC'));
			$data['empresa'] = $this->empresa_model->getEmpresaDetalle($id);
			
			if (isset($row))
			{
				foreach ($row as $obj)
				{
					$obj['movimientos'] = $this->movimiento_model->getMovimientos(array('id_factura'=>$obj['id'], 'order_by'=>'movimientos.id', 'order'=>'ASC'));
					if (isset($obj['padre'])) $obj['padre'] = $this->factura_model->getFacturaDetalle($obj['padre']);
					$data['facturas'][] = $obj;
					
				}
			}
			
			//echo '<pre>' . print_r($data, true) . '</pre>';
			
			$this->load->view('/header');
			$this->load->view('/administracion/empresas/facturas_con_detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
	public function graficoSparkline($id)
	{
		$total = null;
		$intervalo = 12;
		
		// models
		$this->load->model('movimiento_model');
		
		
		$data = $this->movimiento_model->getMovimientosMensuales(array('id_empresa'=>$id, 'intervalo'=>$intervalo));
		
		if (isset($data))
		{
			foreach ($data as $value)
			{
			    $valor[] = $value['valor'];
			    $total += $value['valor'];
			}
			
			$res['valores'] = implode(',', $valor);
			$res['total'] = $total;
			$res['intervalo'] = $intervalo;
		}
		else
		{
			$res['valores'] = 0;
			$res['total'] = 0;
			$res['intervalo'] = 12;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function empresas_con_datos_incompletos($dias = 2)
	{
		// models
		$this->load->model('empresa_model');	
		
		$data = $this->empresa_model->empresasConDatosIncompletos($dias);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function aviso_de_datos_de_perfil_incompletos()
	{
		// models
		$this->load->model('empresa_model');
		$this->load->model('comunicacion_model');
		
		$results = $this->empresa_model->empresasConDatosIncompletos(2);
		
		foreach ($results as $data)
		{
			$res[] = $this->comunicacion_model->ingresarComunicacion($data['id'], 6, $data['id_empresa'], $data);
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}
	
	
	public function suspension_por_datos_de_perfil_incompletos()
	{
		// models
		$this->load->model('empresa_model');
		$this->load->model('comunicacion_model');
		$this->load->model('servicio_model');
		
		$results = $this->empresa_model->empresasConDatosIncompletos(7);
		
		if (isset($results))
		{
			foreach ($results as $data)
			{
				$servicios = $this->servicio_model->getServiciosActivosPorEmpresa($data['id_empresa']);
				
				if (isset($servicios))
				{
					foreach ($servicios as $servicio)
					{
						$this->servicio_model->cambiarEstado($servicio['id'], 2);
					}
					
					$res[] = $this->comunicacion_model->ingresarComunicacion($data['id'], 7, $data['id_empresa'], $data);
				}
				else
				{
					$res[] = 'No hay servicios para suspender';
				}
			}
		}
		else
		{
			$res[] = 'No hay servicios para suspender';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}
	
	
	public function empresas_deuroras()
	{
		// models
		$this->load->model('empresa_model');
		
		$res = $this->empresa_model->getEmpresasDeudoras(60);
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}


}