<?php defined('BASEPATH') or exit('No direct script access allowed');
	

class Hosting extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['hosting'] = $this->hosting_model->getPlanes($parametros);
			
			$config['total_rows'] = $this->hosting_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/hosting/index', $data) : $this->load->view('/hosting/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->helper('number');
						
			$data['detalle'] = $this->hosting_model->getPlanDetalle($id);
			
			
			if ($data['detalle']['id_servidor'])
			{
				$config = $this->hosting_model->getCredenciales($data['detalle']['id_servidor']);
				$this->load->library('Cpanel', $config);
			
				$data['emails'] = $this->cpanel->listpopswithdisk($data['detalle']['user']);
			}
			
			$this->load->view('/header');
			$this->load->view('/hosting/detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function alertas_stats()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['alertas'] = $this->hosting_model->getAlertasStats($parametros);
			
			$config['total_rows'] = $this->hosting_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/hosting/alertas_stats', $data) : $this->load->view('/hosting/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ips()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['blacklist'] = $this->input->get('blacklist');
			
			$data['hosting'] = $this->hosting_model->getIps($parametros);
			
			$config['total_rows'] = $this->hosting_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/hosting/ips', $data) : $this->load->view('/hosting/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function athina()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('hosting_model');
			
			$this->load->view('/hosting/mikrotik');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function mxtoolbox_stats()
	{
		// models
		$this->load->model('hosting_model');

		$data = $this->hosting_model->actualizarMxToolboxStats();
		

		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function get_nagios_stats()
	{
		// models
		$this->load->model('hosting_model');

		$data = $this->hosting_model->getNagiosStatsRaw();
		

		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function actualizar_servidores_estado()
	{
		// models
		$this->load->model('hosting_model');

		$data = $this->hosting_model->actualizarServidoresEstado();
	}
	
	
	public function actualizar_nagios_data()
	{
		// models
		$this->load->model('hosting_model');

		$data = $this->hosting_model->actualizarNagiosData();
	}
	
	
	public function ingresar_comunicaciones()
	{
		// models
		$this->load->model('hosting_model');
		
		$data['comunicaciones']['nivel1'] = $this->hosting_model->ingresarComunicaciones(); // 0 MINUTOS
		$data['comunicaciones']['nivel2'] = $this->hosting_model->ingresarComunicaciones(120, 2); // 2 MINUTOS
		$data['comunicaciones']['nivel3'] = $this->hosting_model->ingresarComunicaciones(14400, 3); // 4 HORAS
	}
	
	
	public function enviar_alertas()
	{
		// models
		$this->load->model('hosting_model');
		
		$data['alertas'] = $this->hosting_model->enviarComunicaciones();
	}
	
	
	public function enviar_alertas_criticas()
	{
		// models
		$this->load->model('hosting_model');
		
		$data['alertas'] = $this->hosting_model->getAlertasCriticas();

			
		if (count($data['alertas']))
		{
			$this->load->library('curl');
			
			//$data['agente']['celular'] = $this->hosting_model->getContactoDeGuardiaCelular($this->hosting_model->getContactoDeGuardiaId());
			//$data['agente']['alerta'] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>$data['agente']['celular'])), true); // revision alpha
			
			foreach ($this->hosting_model->getAgentes() as $obj)
			{
				$data['agente']['alerta'] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>$this->hosting_model->getContactoDeGuardiaCelular($obj))), true);
				
				sleep(10);
			}
			
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>2014)), true); // revision alpha
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>2012)), true); // Marce
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>111558521389)), true); // Danny
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>111560338504)), true); // Hugo
			
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>2019)), true); // magoo Mac
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>2017)), true); // magoo Home
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>1145410593)), true); // magoo Home
			//$data['agentes'][] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>111562913580)), true); // magoo Celular
		}
	}
	
	
	public function tomo_la_guardia()
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('hosting_model');
		
			$this->hosting_model->tomoLaGuardia($this->usuario->id);
			
			redirect(base_url());
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

	public function yo_me_encargo($id)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('hosting_model');
		
			$this->hosting_model->yoMeEncargo($id);
			
			redirect(base_url());
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function yo_me_encargo_ajax()
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('hosting_model');
		
			$data = $this->hosting_model->yoMeEncargo($this->input->post('id'));
		}
		else
		{
			$data['error'] = 'Es necsario estar logueado!';
		}
		
		echo json_encode($data);
	}
	
	
	public function track($id)
	{
		// models
		$this->load->model('hosting_model');
		
		$this->hosting_model->track($id);
	}
	
	
	public function accounts($id_servidor)
	{
		// models
		$this->load->model('hosting_model');
		$this->load->model('sys_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($id_servidor);
		$this->load->library('Cpanel', $config);

		$res['id_servidor'] = $id_servidor;
		$res['data'] = $this->cpanel->accounts();
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function importar_accounts($id_servidor)
	{
		// models
		$this->load->model('hosting_model');
		$this->load->model('sys_model');
		
		
		if ($res = $this->accounts($id_servidor))
		{
			if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	
			$config = $this->hosting_model->getCredenciales($res['id_servidor']);
			$this->load->library('Cpanel', $config);
	
			foreach ($res['data'] as $obj)
			{
				$data = $this->cpanel->accountsummary($obj['user']);
				$obj['id_servidor'] = $res['id_servidor'];
				
				if (!$this->hosting_model->verificarSiExiste($obj['user']))
				{
					$this->hosting_model->ingresarAccount($obj);
				}
				else
				{
					$this->hosting_model->modificarAccount($obj['user'], $obj);
				}
				
				unset($obj);
			}
		}
	}
	
	
	private function actualizar_account($id)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		if ($obj = $this->hosting_model->getPlanParaActualizar($id))
		{
			$config = $this->hosting_model->getCredenciales($obj['id_servidor']);
			$this->load->library('Cpanel', $config);

			$res['diskusage'] = $this->hosting_model->actualizarDiskusage($obj['user']);
			
			$res['bndwidthusage'] = $this->hosting_model->actualizarBandwidthusage($obj['user']);
			
			$res['accountsummary'] = $this->cpanel->accountsummary($obj['user']);

			//if (!isset($res['diskusage']['error']) && !isset($res['bndwidthusage']['error']))
			if (isset($res['accountsummary']))
			{
				$this->hosting_model->modificarAccount($obj['user'], $res['accountsummary']);
			}
			else
			{
				$res['accountsummary']['suspended'] = 2;
				$res['accountsummary']['suspendreason'] = 'Eliminada';
				
				$this->hosting_model->modificarAccount($obj['user'], $res['accountsummary']);
			}
			
			if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
		}
	}
	
	
	public function recalcular($id)
	{
		$this->actualizar_account($id);
		
		if (!isset($_GET['debug'])) redirect(base_url('hosting/detalle/' . $id));
	}
	
	
	public function actualizar_accounts()
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$row = $this->hosting_model->getPlanesParaActualizar(3600, 1);
		
		if (isset($row))
		{
			echo '<pre>' . print_r($row, true) . '</pre>';
			
			foreach ($row as $plan)
			{
				if (isset($_GET['debug'])) echo '<pre>' . print_r($plan, true) . '</pre>';
				
				$this->actualizar_account($plan['id']);
				
				break; // CORREGIR, CUANDO SE HACE UN LOOP Y LAS PLANES PERTENECEM A DIFERENTES SERVIDORES, DA ERROR CUANDO CAMBIA PORQUE NO TOMA EL CAMBIO
			}
		}
		else
		{
			if (isset($_GET['debug'])) echo 'Los planes están todos actualizados';
		}
	}
	
	
	public function stats($user)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);
		
		$data = $this->cpanel->stats($user);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function lve($user)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);
		
		$data = $this->cpanel->lveinfo($user);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function listpops($user)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);
		
		$data = $this->cpanel->listpops($user);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function listpopswithdisk($user)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);
		
		$data = $this->cpanel->listpopswithdisk($user);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function spf($user)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);
		
		$data = $this->cpanel->spf($user);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function cpanel_password_reset($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
		{
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->hosting_model->getPlanDetalle($id);
			}
			else
			{
				// models
				$this->load->model('sys_model');

				if ($this->sys_model->verificarPropiedad($this->input->post('id_servicio'), 'servicios'))
				{
					$data['detalle'] = $this->hosting_model->getPlanDetalle($id);
					$data['detalle']['password'] = substr(md5(uniqid()), 0, 6) . '*2002!';
					
					// helpers and libraries
					$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($data['detalle']['user']));
					$this->load->library('Cpanel', $config);
					
					$data['api'] = $this->cpanel->passwd($data['detalle']['user'], $data['detalle']);
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
				}
			}
			
			$this->load->view('/header');
			$this->load->view('/hosting/cpanel_password_reset', $data);
			$this->load->view('/footer');
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
	
	
	public function email_password_reset($id, $user_email, $domain)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
		{
			// models
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->hosting_model->getPlanDetalle($id);
				$data['detalle']['email'] = $user_email . '@' . $domain;
			}
			else
			{
				// models
				$this->load->model('sys_model');

				if ($this->sys_model->verificarPropiedad($this->input->post('id_servicio'), 'servicios'))
				{
					$data['detalle'] = $this->hosting_model->getPlanDetalle($id);
					$data['detalle']['domain'] = $domain;
					$data['detalle']['email'] = $user_email . '@' . $domain;
					$data['detalle']['password'] = substr(md5(uniqid()), 0, 6) . '*2002!';
					
					// helpers and libraries
					$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($data['detalle']['user']));
					$this->load->library('Cpanel', $config);
					
					$data['api'] = $this->cpanel->passwdpop($data['detalle']['user'], $data['detalle']);
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
				}
			}
			
			$this->load->view('/header');
			$this->load->view('/hosting/email_password_reset', $data);
			$this->load->view('/footer');
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
	
	
	public function probar_alerta($agente)
	{
		// models
		$this->load->model('hosting_model');
		
		// helpers and libraries
		$this->load->library('curl');
		
		$data['agente']['alerta'] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>$this->hosting_model->getContactoDeGuardiaCelular($agente))), true);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	

}