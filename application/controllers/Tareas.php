<?php defined('BASEPATH') or exit('No direct script access allowed');


class Tareas extends MY_Controller {

	public function lista()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('tarea_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');

			$data['tareas'] = $this->tarea_model->getTareas($parametros);
			
			$config['total_rows'] = $this->tarea_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/tareas/index', $data) : $this->load->view('/tareas/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('tarea_model');
			$this->load->model('contacto_model');
			$this->load->model('proyecto_model');
			
			// helpers and libraries
			$this->load->helper('form');
			
			$data['agentes'] = $this->contacto_model->comboContactos(array('id_empresa'=>$this->usuario->id_empresa));
			$data['proyectos'] = $this->proyecto_model->comboProyectos(null, '--- Seleccione un proyecto ---');
			
			$parametros['id_proyecto'] = $this->input->get('id_proyecto');
			$parametros['id_contacto'] = $this->input->get('id_contacto');
			$parametros['order_by'] = 'orden';
			$parametros['order'] = 'ASC';
			
			$parametros['estado'] = 1;
			$data['tareas']['todo'] = $this->tarea_model->getTareas($parametros);
			
			$parametros['estado'] = 2;
			$data['tareas']['inprogress'] = $this->tarea_model->getTareas($parametros);
			
			$parametros['estado'] = 3;
			$data['tareas']['completed'] = $this->tarea_model->getTareas($parametros);
			
			$data['parametros'] = $parametros;
						
			$this->load->view('/header');
			$this->load->view('/tareas/agile_board', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	public function process_sortable($tipo)
	{
		// models
		$this->load->model('tarea_model');
			
		$data = $this->tarea_model->ordenar($tipo, json_decode($_POST['items']));
		
		echo json_encode($data);
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('tarea_model');
			
			
			$data['detalle'] = $this->tarea_model->getTareaDetalle($id);
			
			
			$this->load->view('/header');
			$this->load->view('/tareas/detalle', $data);
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
			$this->load->model('tarea_model');
			$this->load->model('contacto_model');
			$this->load->model('proyecto_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_contacto', 'Contacto', 'trim|required|integer');
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('desde', 'Desde', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('hasta', 'Hasta', 'trim|alpha_dash');
			$this->form_validation->set_rules('estado', 'Estado', 'integer');
			
			$this->form_validation->set_rules('id_proyecto', 'Proyecto', 'integer');
			$this->form_validation->set_rules('horas_designadas', 'Horas designadas', 'integer');
			$this->form_validation->set_rules('horas_utilizadas', 'Horas utilizadas', 'integer');
			$this->form_validation->set_rules('porcentaje_realizado', 'Porcentaje realizado', 'integer');
			
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_contacto'] = $this->usuario->id;
				$default['desde'] = date('d-m-Y', strtotime('today UTC'));
				$default['hasta'] = null;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['contactos'] = $this->contacto_model->comboContactos(array('id_empresa'=>$this->usuario->id_empresa));
				$data['proyectos'] = $this->proyecto_model->comboProyectos(null, '--- Seleccione un proyecto ---');
	
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/tareas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->tarea_model->ingresarTarea($this->input->post()))
				{
					redirect(base_url('tareas/detalle/' . $data['id']));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/tareas/error/');
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
			$this->load->model('tarea_model');
			$this->load->model('contacto_model');
			$this->load->model('proyecto_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('id_contacto', 'Contacto', 'trim|required|integer');
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('desde', 'Desde', 'trim|required|alpha_dash');
			$this->form_validation->set_rules('hasta', 'Hasta', 'trim|alpha_dash');
			$this->form_validation->set_rules('estado', 'Estado', 'integer');
			
			$this->form_validation->set_rules('id_proyecto', 'Proyecto', 'integer');
			$this->form_validation->set_rules('horas_designadas', 'Horas designadas', 'integer');
			$this->form_validation->set_rules('horas_utilizadas', 'Horas utilizadas', 'integer');
			$this->form_validation->set_rules('porcentaje_realizado', 'Porcentaje realizado', 'integer');
			
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->tarea_model->getTareaDetalleRaw($id);
				$data['contactos'] = $this->contacto_model->comboContactos(array('id_empresa'=>$this->usuario->id_empresa));
				$data['proyectos'] = $this->proyecto_model->comboProyectos(null, '--- Seleccione un proyecto ---');
	
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/tareas/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->tarea_model->modificarTarea($id, $this->input->post()))
				{
					redirect(base_url('tareas/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/tareas/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function cambiar_estado()
	{
		// models
		$this->load->model('tarea_model');

		$data = $this->tarea_model->cambiarEstado($this->input->post('id'));
				
		echo json_encode($data);
	}
	
	
	public function tareas_diarias()
	{
		// models
		$this->load->model('tarea_model');
		
		$day = date('j', now());
		
		if ($day == 1)
		{
			if (!$this->tarea_model->verificarSiExiste('Pagar alquiler', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar alquiler', 'descripcion'=>'Pagar alquiler', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-03') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Generar débito automático', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Generar débito automático', 'descripcion'=>'Se realiza desde el Windows virtual', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-03') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Factura WAM', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Factura WAM', 'descripcion'=>'Notificar a Alejandro García Conte de la factura por conectividad (Mbits) mandar mail con link a factura. Ver mail anterior.', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-03') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Cobro Ciberiada', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Cobro Ciberiada', 'descripcion'=>'Llamar a Ismael Cuasnicú de Ciberiada y coordinar con Gerardo de la mensajería Time Fly para retirar el pago', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-05') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ajuste de cajas', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ajuste de cajas', 'descripcion'=>'Ajuste de cuenta corriente, Mercado Pago y PayPal', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-05') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar expensas', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar expensas', 'descripcion'=>'Pagar expensas', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Enviar comprobantes', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Enviar comprobantes', 'descripcion'=>'Descargar resúmenes mensuales y de tarjetas, bajar archivos de compra y venta y enviarlos a 	Verónica', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Honorarios contables', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Honorarios contables', 'descripcion'=>'Llevar las facturas impresas y pagarle los honorarios a Verónica', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Cobro débito automático', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Cobro débito automático', 'descripcion'=>'Descargar archivo desde Galicia Office e importar los débitos al sistema', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar infraestructura', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar infraestructura', 'descripcion'=>'Contactarse con Juan Pablo Gilabert para confirmar el importe a pagar a Gigared', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-12') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar I.V.A.', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar I.V.A.', 'descripcion'=>'Pedir VEP de I.V.A. al estudio contable García Polino y Asociados guardarlo en la carpeta de presentaciones y pagarlo desde la opción pago de servicios', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-15') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar IIBB', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar IIBB', 'descripcion'=>'Pedir VEP de Ingresos Brutos al estudio contable García Polino y Asociados guardarlo en la carpeta de presentaciones y pagarlo desde la opción pago de servicios', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-15') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Cobro Casamientos Online', 43209)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43209, 'titulo'=>'Cobro Casamientos Online', 'descripcion'=>'Llamar a Gonzalo Frery de Idóneo y coordinar por el pago de Casamientos Online', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Renovar dominos', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Renovar dominos', 'descripcion'=>'Renovar dominios Nic.Ar', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Importar dominos', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Importar dominos', 'descripcion'=>'Importar dominios desde MyDomain', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ingresar factura Rackspace', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Rackspace', 'descripcion'=>'Ingresar factura de servicio Rackspace', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ingresar factura Google Addwords', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Google Addwords', 'descripcion'=>'Ingresar factura de servicio Google Addwords', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ingresar factura Fibertel', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Fibertel', 'descripcion'=>'Ingresar factura de servicio Fibertel', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ingresar factura Claro', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Claro', 'descripcion'=>'Ingresar factura de servicio Claro', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Ingresar factura Personal', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Personal', 'descripcion'=>'Ingresar factura de servicio Personal', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-10') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar ABL', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar ABL', 'descripcion'=>'Pagar ABL partida 4052020 - Mercado Pago', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-08') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Pagar Edenor', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pagar Edenor', 'descripcion'=>'Pagar Edenor', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			//if (!$this->tarea_model->verificarSiExiste('Ingresar factura Movistar', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Ingresar factura Movistar', 'descripcion'=>'Ingresar factura de servicio Movistar', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Recaudación cobranzas 1º débito', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Recaudación cobranzas 1º débito', 'descripcion'=>'Recaudación cobranzas 1º débito', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-16') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Recaudación cobranzas 2º débito', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Recaudación cobranzas 2º débito', 'descripcion'=>'Recaudación cobranzas 2º débito', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
						
			if (!$this->tarea_model->verificarSiExiste('Pasar gastos de tarjetas', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Pasar gastos de tarjetas', 'descripcion'=>'Pasar gastos de tarjetas VISA, AMEX (Asignar como sueldo a Diego Mascarenhas) y MASTERCARD (Asignar como sueldo a Diego Mascarenhas).', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-15') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Llamar a deudores', 43358)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>43358, 'titulo'=>'Llamar a deudores', 'descripcion'=>'Llamar a los que tiene facturas sin pagar por más de 45 días y confirmar si quieren seguir teniendo el servicio.', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-26') ,'estado'=>1, 'username_alta'=>'cron'));
			
			if (!$this->tarea_model->verificarSiExiste('Comprobar IPs en Blacklists', 42526)) $data[] = $this->tarea_model->ingresarTarea(array('grupo'=>502, 'id_empresa'=>1, 'id_contacto'=>42526, 'titulo'=>'Comprobar IPs en Blacklists', 'descripcion'=>'Comprobar que las IPs no estén en ninguna lista negra. 190.7.29.16/28, 190.57.225.160/27, 190.57.233.224/27, 190.57.234.80/29, 190.183.215.144/29, 190.183.220.192/29, 190.7.17.128/27', 'desde'=>date('y-m-d', strtotime('today UTC')) ,'hasta'=>date('y-m-10') ,'estado'=>1, 'username_alta'=>'cron'));
		}


		$weekday = date('N', now());
		
		if (in_array($weekday, array(1,2,3,4,5)))
		{
			//$this->comunicar(475); // magoo
			//$this->comunicar(42321); // carla
			//$this->comunicar(42526); // danny
			//$this->comunicar(43209); // pablo
			//$this->comunicar(43358); // brenda
			//$this->comunicar(44432); // camila
		}
	}
	
	
	public function comunicar($id_contacto)
	{
		// models
		$this->load->model('tarea_model');
		$this->load->model('comunicacion_model');
		
		$data['tareas'] = $this->tarea_model->comunicarTareas($id_contacto, 2);
		
		$res = $this->comunicacion_model->ingresarComunicacion($id_contacto, 18, date('Ymd'), $data);
	}
	

}