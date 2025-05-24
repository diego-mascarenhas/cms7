<?php defined('BASEPATH') or exit('No direct script access allowed');


class Home extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// Detailed session dump
		echo "<pre style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; margin: 20px; max-height: 500px; overflow: auto;'>";
		echo "<h2>Session Debug in Home Controller</h2>";
		echo "<h3>REQUEST INFO:</h3>";
		echo "Controller: " . $this->router->class . "<br>";
		echo "Method: " . $this->router->method . "<br>";
		echo "URI: " . $this->uri->uri_string() . "<br>";
		echo "Time: " . date('Y-m-d H:i:s') . "<br>";
		
		echo "<h3>SESSION DETAILS:</h3>";
		echo "Session ID: " . session_id() . "<br>";
		echo "Session Name: " . session_name() . "<br>";
		echo "Session Save Path: " . session_save_path() . "<br>";
		
		echo "<h3>SESSION DATA:</h3>";
		var_dump($this->session->userdata());
		
		echo "<h3>RAW SESSION DATA:</h3>";
		var_dump($_SESSION);
		
		echo "<h3>USUARIO OBJECT:</h3>";
		var_dump($this->usuario);
		
		echo "<h3>SESSION FILE CONTENT:</h3>";
		$session_file = session_save_path() . '/ci_session' . session_id();
		if (file_exists($session_file)) {
			echo "File exists: YES<br>";
			echo "File size: " . filesize($session_file) . " bytes<br>";
			echo "File content: <br>";
			echo htmlspecialchars(file_get_contents($session_file));
		} else {
			echo "File exists: NO<br>";
		}
		
		echo "</pre>";
		
		// Check if session needs repair
		$this->check_session_integrity();
		
		// Ensure critical session data is available
		ensure_session_data(array('usuario', 'logged_in'));
	}

	public function index()
	{
		if (isset($this->usuario->dashboard) && $this->usuario->dashboard != '/')
		{
			redirect($this->usuario->dashboard);	
		}
		
		elseif ($this->is_logged_in('root'))
		{
			$data['debug'] = $this->session->userdata();
			
			$this->load->view('header');
			$this->load->view('debug', $data);
			$this->load->view('footer');
		}
		
		elseif ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			$this->load->model('comunicacion_model');
			
			// helpers and libraries
			$this->load->helper('number');
			
			$data['media']['archivos'] = $this->multimedia_model->getMediaArchivos();
			$data['media']['proyectos'] = $this->multimedia_model->getMediaProyectos();
			$data['media']['espacio'] = $this->multimedia_model->getMediaEspacio();
			
			$data['comunicaciones']['enviar'] = $this->comunicacion_model->getCantidadDeComunicaciones(1);
			
			$this->load->view('/header');
			$this->load->view('/index', $data);
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
	
	
	public function revision()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// db Slave
			//$this->db = $this->load->database('nodo2', true);
			
			//models
			$this->load->model('dashboard_model');
			$this->load->model('ticket_model');
			$this->load->model('contacto_model');
			$this->load->model('servicio_model');
			//$this->load->model('movimiento_model');
			$this->load->model('smtp_model');
			$this->load->model('factura_model');
			$this->load->model('mailbox_model');
			$this->load->model('hosting_model');
			$this->load->model('tarea_model');
			$this->load->model('comunicacion_model');
			$this->load->model('nota_model');
			
			// helpers and libraries
			$this->load->library('curl');
			
			$data['dashboard'] = $this->dashboard_model->getDashboardStats();
			
			$data['tickets']['nuevos'] = $this->ticket_model->getCantidadDeTickets(2);
			if (!isset($data['tickets']['nuevos'])) $data['tickets']['abiertos'] = $this->ticket_model->getCantidadDeTickets(3);
			$data['tickets']['stats'] = $this->ticket_model->getTicketsStats();
			
			$data['usuarios']['online'] = $this->contacto_model->getCantidadDeContactosOnline();
			
			$data['servicios']['activos'] = $this->servicio_model->getCantidadDeServicios(4);
			$data['servicios']['suspender'] = $this->servicio_model->getCantidadDeServicios(2);
			if (!isset($data['servicios']['suspender'])) $data['servicios']['activar'] = $this->servicio_model->getCantidadDeServicios(3);
			if (!isset($data['servicios']['activar'])) $data['servicios']['suspendidos'] = $this->servicio_model->getCantidadDeServicios(1);
			
			//$data['movimientos']['usd'] = $this->movimiento_model->getMonedaCambio(2);
			//$data['movimientos']['hoy'] = $this->movimiento_model->getIngresosDeHoy();
			$data['smtps']['mails_en_cola'] = $this->smtp_model->getMailsEnCola();
			
			$data['facturas']['imprimir'] = $this->factura_model->getCantidadDeFacturas(1);
			if (!isset($data['facturas']['imprimir'])) $data['facturas']['error'] = $this->factura_model->getCantidadDeFacturas(7);
			
			$data['inbox']['prioridad'] = $this->mailbox_model->getMaximaPrioridad();
			$data['inbox']['noleidos'] = $this->mailbox_model->getCantidadDeEmails($data['inbox']['prioridad']);
			
			//$data['nagios'] = $this->hosting_model->getNagiosStatsLive();
			//$data['nagios']['totales'] = $this->hosting_model->getAlertasTotales();
			//$data['nagios']['servicios'] = $this->hosting_model->getAlertas();
			$data['hosting']['contacto_de_guardia'] = $this->contacto_model->getContactoDetalle($this->hosting_model->getContactoDeGuardiaId());
			
			$data['tareas']['lista'] = $this->tarea_model->getTareas(array('id_contacto'=>$this->usuario->id, 'dashboard'=>true));
			$data['tareas']['pendientes'] = $this->tarea_model->getCantidadDeTareas(1);
			if (!isset($data['tareas']['vencidas'])) $data['tareas']['vencidas'] = $this->tarea_model->getCantidadDeTareasVencidas();
			
			$data['bandwidth']['athina'] = base64_encode($this->curl->simple_get('http://190.7.29.18/graphs/iface/WAN/daily.gif'));
			//$data['bandwidth']['rocoto'] = base64_encode($this->curl->simple_get('http://10.0.0.1/graphs/iface/WAN/daily.gif'));
			
			$data['planes'] = $this->hosting_model->getPlanes(array('dashboard'=>true));
			
			$data['comunicaciones']['error'] = $this->comunicacion_model->getCantidadDeComunicaciones(4);
			if (!isset($data['comunicaciones']['error'])) $data['comunicaciones']['enviar'] = $this->comunicacion_model->getCantidadDeComunicaciones(1);
			
			$this->session->set_userdata('notas', $this->nota_model->getNotas(array('responsable'=>$this->usuario->id)));
			$this->session->set_userdata('tareas', $this->tarea_model->getTareas(array('id_contacto'=>$this->usuario->id, 'sidebar'=>true)));
			

			//db Mailer
			$this->db = $this->load->database('mailer', true);
			
			//models
			$this->load->model('newsletter_model');
			
			$data['newsletters']['news'] = $this->newsletter_model->getNewslettersStats(array('per_page'=>10));
			$data['newsletters']['restantes'] = $this->newsletter_model->getCantidadDeemailesRestantes();
			
			$this->load->view('/header');
			$this->load->view('/index_revision', $data);
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
	
	
	public function get_nagios_stats()
	{
		//models
		$this->load->model('hosting_model');
		
		$data = $this->hosting_model->getNagiosStats();
		
	    echo json_encode($data);
	}	

	
}