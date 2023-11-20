<?php defined('BASEPATH') or exit('No direct script access allowed');


class Smtps extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('smtp_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			
			$data['servidores'] = $this->smtp_model->getServidores($parametros);
			
			$config['total_rows'] = $this->smtp_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/servidores/index', $data) : $this->load->view('/emailer/servidores/empty', $data);
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
			$this->load->model('smtp_model');
			
			
			$data['detalle'] = $this->smtp_model->getServidorDetalle($id);
			
			
			$this->load->view('/header');
			$this->load->view('/emailer/servidores/detalle', $data);
			//$this->load->view('/debug', array('debug'=>array($data['detalle'])));
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
			$this->load->model('smtp_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('host', 'Host', 'trim|required');
			$this->form_validation->set_rules('user', 'Usuario', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('puerto', 'Puerto', 'trim|required|integer');
			$this->form_validation->set_rules('seguridad', 'Seguridad', 'trim|min_length[3]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['seguridades'] = $this->combos->seguridades();
				$data['accion'] = 'ingresar';
				
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/emailer/servidores/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->smtp_model->ingresarServidor($this->input->post()))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'emailer/smtps/detalle/' . $data['id']));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/emailer/servidores/error/');
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
			$this->load->model('smtp_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('host', 'Host', 'trim|required');
			$this->form_validation->set_rules('user', 'Usuario', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('puerto', 'Puerto', 'trim|required|integer');
			$this->form_validation->set_rules('seguridad', 'Seguridad', 'trim|min_length[3]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$post = $this->input->post();
				
				$data['detalle'] = ($this->input->post()) ? $post : $this->smtp_model->getServidorDetalleRaw($id);
				$data['seguridades'] = $this->combos->seguridades();
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/emailer/servidores/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->smtp_model->modificarServidor($id, $this->input->post()))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'emailer/smtps/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/emailer/servidores/error/');
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
			$this->load->model('smtp_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->smtp_model->getServidorDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/emailer/servidores/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'emailer_servidores'))
				{
					$res = $this->sys_model->eliminar($id, 'emailer_servidores');
					
					redirect(base_url('emailer/smtps/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/emailer/servidores/error/');
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
	
	
	public function actualizar_mailq($ip, $cantidad)
	{
		// models
		$this->load->model('smtp_model');
			
		$this->smtp_model->actualizarMailq($this->smtp_model->getSmtpIdFromHost($ip), $cantidad);
	}
	
	
	public function probar_directo($id)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('smtp_model');
			
			
			$data['detalle'] = $this->smtp_model->probarSmtp($id);
			
			echo '<pre>' . print_r($data['detalle'], true) . '</pre>';
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function probar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('smtp_model');
			$this->load->model('emailer_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('host', 'Host', 'trim|required');
			$this->form_validation->set_rules('user', 'Usuario', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('puerto', 'Puerto', 'trim|required|integer');
			$this->form_validation->set_rules('seguridad', 'Seguridad', 'trim|min_length[3]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$post = $this->input->post();
				
				$data['detalle'] = ($this->input->post()) ? $post : $this->smtp_model->getServidorDetalleRaw($id);
				$data['detalle']['accion'] = 'probar';
				$data['seguridades'] = $this->combos->seguridades();
				$data['templates'] = $this->emailer_model->comboTemplates();
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/emailer/servidores/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$res = $this->input->post();
				
				$data['smtp'] = array('protocol' => 'smtp',
						'smtp_host' => $res['host'],
						'smtp_port' => $res['puerto'],
						'smtp_crypto' => $res['seguridad'],
						'smtp_user' => $res['user'],
						'smtp_pass' => $res['pass'],
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'wordwrap' => true,
						'nodebug' => true
						);
						
				$this->load->library('email', $data['smtp']);
				
				
				$site_url = ($_SERVER['HTTPS'] == 'on') ? 'https://'  . $_SERVER['SERVER_NAME'] . '/' : 'http://' . $_SERVER['SERVER_NAME'] . '/';
				$url = $site_url . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/template-' .  $res['id_template']  . '.php';
		
				$this->load->library('curl');
				$res['template'] = $this->curl->simple_post($url, (isset($res['data'])) ? array_merge($res, json_decode($res['data'], true)) : $res);
				
				$this->email->set_newline("\r\n");
				$this->email->from($res['user'], 'CMS+');
				$this->email->to(($res['email']) ? $res['email'] : $this->usuario->email, $this->usuario->contacto);
				$this->email->subject('CMS+ Prueba de sistema (' . $res['host'] . ')');
				//$this->email->message('Esta es una prueba desde CMS+');
				$this->email->message($res['template']);
				
				
				if (!$this->email->send($data['smtp']['nodebug']))
				{
					$data['error'] = $this->email->print_debugger();
				}
				else
				{
					if (!$data['smtp']['nodebug']) $data['debug'] = $this->email->print_debugger(array('headers', 'subject', 'body'));
					
					if ($data = $this->smtp_model->modificarServidor($id, $this->input->post()))
					{
						redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'emailer/smtps/detalle/' . $id));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
		
						$this->load->view('/emailer/servidores/error/');
					}
				}
				
				echo '<pre>' . print_r($data, true) . '</pre>';
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function sync()
	{
		// models
		$this->load->model('smtp_model');
		
		
		// local
		$data['servidores'] = $this->smtp_model->getServidores(array('order_by'=>'id','order'=>'ASC','per_page'=> 40));
		
		
		//db Mailer
		$this->db = $this->load->database('mailer', true);
		
		foreach ($data['servidores'] as $obj)
		{
			$this->smtp_model->sync($obj['id'], array('mailq'=>$obj['mailq'], 'estado'=>$obj['id_estado']));
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function pausar($cantidad = 1000)
	{
		// models
		$this->load->model('smtp_model');
		
		
		$data['servidores'] = $this->smtp_model->getSmtpActivos($cantidad);
		
		if (isset($data['servidores']))
		{
			foreach ($data['servidores'] as $obj)
			{
				$this->smtp_model->modificarSmtp($obj['id'], array('estado'=>5));
			}
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function reactivar($cantidad = 1000)
	{
		// models
		$this->load->model('smtp_model');
		
		
		$data['servidores'] = $this->smtp_model->getSmtpPausados($cantidad);
		
		if (isset($data['servidores']))
		{
			foreach ($data['servidores'] as $obj)
			{
				$this->smtp_model->modificarSmtp($obj['id'], array('estado'=>4));
			}
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}


}