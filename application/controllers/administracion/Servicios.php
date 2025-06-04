<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Servicios extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('servicio_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			$parametros['id_categoria'] = $this->input->get('id_categoria');
			
			$data['servicios'] = $this->servicio_model->getServicios($parametros);
			
			$config['total_rows'] = $this->servicio_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/servicios/index', $data) : $this->load->view('/administracion/servicios/empty', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in())
		{
			// models
			$this->load->model('servicio_model');
			
			
			$data['servicios'] = $this->servicio_model->getServicios();
			
			$this->load->view('/header');
			$this->load->view('/administracion/servicios/index_admin', $data);
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
			$this->load->model('servicio_model');
			$this->load->model('nota_model');
			
			// helpers and libraries
			$this->load->helper('text');
			
			$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
			
			// Parse JSON data field if it exists
			if (isset($data['detalle']['data']) && !empty($data['detalle']['data'])) {
				$json_data = json_decode($data['detalle']['data'], true);
				if (is_array($json_data)) {
					// Add empresa from JSON if not already set
					if (!isset($data['detalle']['empresa']) && isset($json_data['empresa'])) {
						$data['detalle']['empresa'] = $json_data['empresa'];
					}
					
					// Merge JSON data with detalle array
					$data['detalle'] = array_merge($data['detalle'], $json_data);
				}
			}
			
			$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>114, 'id_referencia'=>$id));
			
			$this->load->view('/header');
			$this->load->view('/administracion/servicios/detalle', $data);
			$this->load->view('/footer');
		}
		elseif ($this->is_logged_in())
		{
			// models
			$this->load->model('servicio_model');
			$this->load->model('hosting_model');
			
			
			$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
			
			// Parse JSON data field if it exists
			if (isset($data['detalle']['data']) && !empty($data['detalle']['data'])) {
				$json_data = json_decode($data['detalle']['data'], true);
				if (is_array($json_data)) {
					// Add empresa from JSON if not already set
					if (!isset($data['detalle']['empresa']) && isset($json_data['empresa'])) {
						$data['detalle']['empresa'] = $json_data['empresa'];
					}
					
					// Merge JSON data with detalle array
					$data['detalle'] = array_merge($data['detalle'], $json_data);
				}
			}
			
			if (isset($data['detalle']['host'])) $data['nagios'] = $this->hosting_model->getNagiosStatsLive(array('host'=>$data['detalle']['host']));
			
			$this->load->view('/header');
			$this->load->view('/administracion/servicios/detalle_admin', $data);
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
			$this->load->model('servicio_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|integer');
			$this->form_validation->set_rules('operacion', 'Operación', 'trim|required|in_list[C,V]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('id_moneda', 'Moneda', 'trim|required|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			$this->form_validation->set_rules('frecuencia', 'Frecuencia', 'trim|required');
			$this->form_validation->set_rules('proxima', 'Próxima', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('caduca', 'Caduca', 'trim|alpha_dash');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_empresa'] = $this->input->get('id_empresa');
				$default['operacion'] = 'V';
				$default['proxima'] = date('d-m-Y', strtotime('today UTC'));
				$default['caduca'] = null;
				$default['valor'] = null;
				$default['descuento'] = null;
				$default['estado'] = 3;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(null, (isset($data['detalle']['id_categoria'])) ? $data['detalle']['id_categoria'] : null);
				$data['frecuencias'] = $this->combos->frecuenciasCombo();
				$data['monedas'] = $this->sys_model->comboMonedas();
				$data['formas_pago'] = $this->sys_model->comboFormasDePago('--- Selecciona una forma de pago ---');
	
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/servicios/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->servicio_model->ingresarServicio($this->input->post()))
				{
					redirect(base_url('administracion/servicios/detalle/' . $data));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/servicios/error/');
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
			$this->load->model('servicio_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|required|integer');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|integer');
			$this->form_validation->set_rules('operacion', 'Operación', 'trim|required|in_list[C,V]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('id_moneda', 'Moneda', 'trim|required|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			$this->form_validation->set_rules('frecuencia', 'Frecuencia', 'trim|required');
			$this->form_validation->set_rules('proxima', 'Próxima', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('caduca', 'Caduca', 'trim|alpha_dash');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->servicio_model->getServicioDetalleRaw($id);
				
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(null, (isset($data['detalle']['id_categoria'])) ? $data['detalle']['id_categoria'] : null);
				$data['frecuencias'] = $this->combos->frecuenciasCombo();
				$data['monedas'] = $this->sys_model->comboMonedas();
				$data['formas_pago'] = $this->sys_model->comboFormasDePago('--- Selecciona una forma de pago ---');
				
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/servicios/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($this->servicio_model->modificarServicio($id, $this->input->post()))
				{
					// models
					$this->load->model('categorias_generales_model');
					
					$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
					$data['detalle']['categoria'] = $this->categorias_generales_model->getCategoriaGeneralDetalle($data['detalle']['id_categoria']);
					$data['detalle']['categoria']['caracteristicas'] = json_decode($data['detalle']['categoria']['caracteristicas'], true);
					
					if ($data['detalle']['categoria']['id_tipo'] == 1 || $data['detalle']['categoria']['id_tipo'] == 2)
					{
						// models
						$this->load->model('hosting_model');
						
						$data['detalle']['hosting'] = $this->hosting_model->getPlanDetalle($data['detalle']['id_servicio_hosting']);
						
						// helpers and libraries
						$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($data['detalle']['hosting']['user']));
						$this->load->library('Cpanel', $config);
						
						$this->cpanel->changepackage($data['detalle']['hosting']['user'], array('plan'=>$data['detalle']['categoria']['caracteristicas']['plan']));
					}
					
					redirect(base_url('administracion/servicios/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/servicios/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function comunicar($id)
	{
		// models
		$this->load->model('servicio_model');	
		
		$data = $this->servicio_model->comunicarServicio($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function get_servicios()
	{
		//models
		$this->load->model('servicio_model');
		
		$data = $this->servicio_model->comboServicios($this->input->post());
			    
	    echo json_encode($data);
	}
	
	
	public function para_activar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('servicio_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/servicios/activar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($this->input->post('id'), 'servicios'))
				{
					$res = $this->servicio_model->cambiarEstado($this->input->post('id'), 3);
					
					redirect(base_url('administracion/servicios/detalle/' . $this->input->post('id')));
				}
				else
				{
					// form values
					$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/header');
					$this->load->view('/administracion/servicios/activar', $data);
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
	
	
	public function para_suspender($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('servicio_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/servicios/suspender', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($this->input->post('id'), 'servicios'))
				{
					$res = $this->servicio_model->cambiarEstado($this->input->post('id'), 2);
					
					redirect(base_url('administracion/servicios/detalle/' . $this->input->post('id')));
				}
				else
				{
					// form values
					$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/header');
					$this->load->view('/administracion/servicios/activar', $data);
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
	
	
	public function servicios_para_activar()
	{
		// models
		$this->load->model('servicio_model');
		
		// helpers and libraries
		$data = $this->servicio_model->getServiciosParaActivar();
		
		echo json_encode($data);
	}
		
		
	public function activar()
	{
		// models
		$this->load->model('servicio_model');
		
		// helpers and libraries
		$row = $this->servicio_model->getServiciosParaActivar();
		
		
		if (isset($row))
		{
			foreach ($row as $obj)
			{
				if (isset($_GET['debug'])) echo '<pre>' . print_r($obj, true) . '</pre>';
				
				if ($obj['id_tipo'] == 1 || $obj['id_tipo'] == 2) // Hosting
				{
					// models
					$this->load->model('hosting_model');
					
					$plan = $this->hosting_model->getExtrasFromServiciosId($obj['id']);
					
					if (isset($plan)) // Si existe el plan, lo activa
					{
						$config = $this->hosting_model->getCredenciales($plan['id_servidor']);
						$this->load->library('Cpanel', $config);
		
						if ($this->cpanel->activar($plan['user']))
						{
							$this->servicio_model->cambiarEstado($obj['id'], 4);
						}
					}
					else // Si no existe el plan, lo crea
					{
						$extras = json_decode($obj['data'], true);
						$extras['dominio'] = limpiarDominio($extras['dominio']);
						
						if (isset($_GET['debug'])) echo '<pre>' . print_r($extras, true) . '</pre>';

						if (isset($extras['dominio']))
						{
							$config = $this->hosting_model->getWhmCredenciales($obj['grupo']);
							$this->load->library('Cpanel', $config);
							if (isset($_GET['debug'])) echo '<pre>' . print_r($config, true) . '</pre>';
							
							$caracteristicas = $this->servicio_model->getCategoriasCaracteristicas($obj['id_categoria']);
							
							$plan = $this->cpanel->createacct($extras['dominio'], $caracteristicas['plan'], $obj['email']);
							if (isset($_GET['debug'])) echo '<pre>' . print_r($plan, true) . '</pre>';
							
							if (!isset($plan['error']))
							{
								$valores['id_servicio'] = $obj['id'];
								
								$valores['id_servidor'] = $config['id_servidor'];
								
								$valores['user'] = $plan['user'];
								$valores['ip'] = $config['ip'];
								$valores['domain'] = $plan['dominio'];
								$valores['email'] = $obj['email'];
								
								$this->hosting_model->ingresarAccount($valores);
								
								
								// models
								$this->load->model('comunicacion_model');
								
								$plan['ip'] = $config['ip'];
								$plan['descripcion'] = $obj['descripcion'];
								
								$res['comunicacion'] = $this->comunicacion_model->ingresarComunicacion($extras['id_contacto'], 13, $obj['id'], $plan);
				
								$this->servicio_model->cambiarEstado($obj['id'], 4);
							}
							else
							{
								$res = $plan;
							}
							
							if (isset($_GET['debug'])) echo  '<pre>' . print_r($caracteristicas, true) . '</pre>';
						}
						
						if (isset($_GET['debug'])) echo  '<pre>' . print_r($res, true) . '</pre>';
					}

				}
				
				elseif ($obj['id_tipo'] == 20) // Tienda
				{
					// models
					$this->load->model('tienda_model');
										
					$this->tienda_model->cambiarEstadoFromServicio($obj['id'], 3);
					
					$this->servicio_model->cambiarEstado($obj['id'], 4);
				}
				
				unset($obj);
			}
		}
	}
	
	
	public function suspender()
	{
		// models
		$this->load->model('servicio_model');
		
		// helpers and libraries
		$row = $this->servicio_model->getServiciosParaSuspender();
		
		
		if (isset($row))
		{
			foreach ($row as $obj)
			{
				if (isset($_GET['debug'])) echo '<pre>' . print_r($obj, true) . '</pre>';
				
				if ($obj['id_tipo'] == 1 || $obj['id_tipo'] == 2)  // Hosting
				{
					// models
					$this->load->model('hosting_model');
					
					$plan = $this->hosting_model->getExtrasFromServiciosId($obj['id']);
					if (isset($_GET['debug']))  echo '<pre>' . print_r($plan, true) . '</pre>';
					
					if (isset($plan))
					{
						$config = $this->hosting_model->getCredenciales($plan['id_servidor']);
						$this->load->library('Cpanel', $config);

						$suspender = $this->cpanel->suspender($plan['user']);
						echo '<pre>' . print_r($suspender, true) . '</pre>';
						
						if ($suspender['status'] == 1)
						{
							$this->servicio_model->cambiarEstado($obj['id'], 1);
						}
					}
				}
				
				elseif ($obj['id_tipo'] == 20)  // Tienda
				{
					$this->suspender_tienda($obj['id']);
					// models
					$this->load->model('tienda_model');
										
					$this->tienda_model->cambiarEstadoFromServicio($obj['id'], 1);
					
					$this->servicio_model->cambiarEstado($obj['id'], 1);
				}
				
				unset($obj);
			}
		}
	}
	
	
	public function suspender_tienda($id, $redirect = null)
	{
		// models
		$this->load->model('servicio_model');
		$this->load->model('tienda_model');
							
		$this->tienda_model->cambiarEstadoFromServicio($id, 1);
		
		$this->servicio_model->cambiarEstado($id, 1);
		
		if ($redirect) redirect(base_url('administracion/servicios/detalle/' . $id));
	}
	
	
	public function suspension_de_servicios_por_falta_de_pago()
	{
		// models
		$this->load->model('empresa_model');
		$this->load->model('servicio_model');
		
		$results = $this->empresa_model->getEmpresasDeudoras(47);
		
		if (isset($results))
		{
			foreach ($results as $data)
			{
				$servicios = $this->servicio_model->getServiciosActivosPorEmpresa($data['id']);
				
				if (isset($servicios))
				{
					foreach ($servicios as $servicio)
					{
						if (isset($servicio['autosuspender']))
						{
							$res[] = $this->servicio_model->cambiarEstado($servicio['id'], 2);
						}
					}
				}
				else
				{
					$res[] = 'No hay empresas con servicios vencidos';
				}			
			}
		}
		else
		{
			$res['error'] = 'No hay empresas con servicios vencidos';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}
	
	
	public function suspension_de_servicios_por_caducidad()
	{
		// models
		$this->load->model('servicio_model');
		
		$servicios = $this->servicio_model->getServiciosCaducados();
			
		if (isset($servicios))
		{
			foreach ($servicios as $servicio)
			{
				if (isset($servicio['caduca']))
				{
					$res[] = $this->servicio_model->cambiarEstado($servicio['id'], 2);
				}
			}
		}
		else
		{
			$res[] = 'No hay servicios que caduquen';
		}			
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}
	
	
}
