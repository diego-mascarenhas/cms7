<?php defined('BASEPATH') or exit('No direct script access allowed');


class Newsletters extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('emailer_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['newsletters'] = $this->emailer_model->getNewsletters($parametros);
			
						
			$config['total_rows'] = $this->emailer_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/newsletters/index', $data) : $this->load->view('/emailer/newsletters/empty', $data);
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
			$this->load->model('emailer_model');
			
			
			$data['detalle'] = $this->emailer_model->getNewsletterDetalle($id);
			
			$data['lista'] = $this->emailer_model->getNewslettersListaDetalle($data['detalle']['id_lista']);
			//$data['contactos'] = $this->emailer_model->listaDinamica(json_decode($data['lista']['filtros'], true));
			
			$data['stats']['progreso'] = $this->emailer_model->getNewsletterStats($id);
			$data['stats']['destinatarios'] = $this->emailer_model->getNewsletterDestinatariosStats($id);
			
			
			$this->load->view('/header');
			$this->load->view('/emailer/newsletters/detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function crear()
	{
		// models
		$this->load->model('emailer_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);

		// set validation rules
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('id_lista', 'Lista', 'trim|integer');
		$this->form_validation->set_rules('id_template', 'Plantilla', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$default['estado'] = 2;
			$default['desde'] = date('d-m-Y', strtotime('today UTC'));
			$default['hasta'] = null;
			
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
			$data['listas'] = $this->emailer_model->comboListas();
			$data['templates'] = $this->emailer_model->comboTemplates();
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/iCheck/green.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/emailer/newsletters/crear', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->ingresarNewsletter($this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/newsletters/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/newsletters/detalle/' . $data['id']));
			}
		}
	}
	
	
	public function ingresar()
	{
		// models
		$this->load->model('emailer_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);

		// set validation rules
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('id_lista', 'Lista', 'trim|integer');
		$this->form_validation->set_rules('id_template', 'Plantilla', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$default['estado'] = 2;
			$default['desde'] = date('d-m-Y', strtotime('today UTC'));
			$default['hasta'] = null;
			
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
			$data['listas'] = $this->emailer_model->comboListas();
			$data['templates'] = $this->emailer_model->comboTemplates();
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/emailer/newsletters/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->ingresarNewsletter($this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/newsletters/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/newsletters/detalle/' . $data['id']));
			}
		}
	}
	
	
	public function modificar($id)
	{
		// models
		$this->load->model('emailer_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);

		// set validation rules
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('id_lista', 'Lista', 'trim|integer');
		$this->form_validation->set_rules('id_template', 'Plantilla', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->emailer_model->getNewsletterDetalleRaw($id);
			$data['listas'] = $this->emailer_model->comboListas();
			$data['templates'] = $this->emailer_model->comboTemplates();
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/emailer/newsletters/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->emailer_model->modificarNewsletter($id, $this->input->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el newsletter, por favor intenta más tarde';

				$this->load->view('/emailer/newsletters/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				redirect(base_url('emailer/newsletters/detalle/' . $id));
			}
		}
	}
	
	
	public function enviar_newsletter($id, $cantidad = 1)
	{
		// db Mailer
		$this->db = $this->load->database('nodo2', true);
		
		// models
		$this->load->model('emailer_model');
		

		$data['detalle'] = $this->emailer_model->getNewsletterDetalleEnvio($id);
		
		$data['lista'] = $this->emailer_model->getNewslettersListaDetalleEnvio($data['detalle']['id_lista']);
		
		$parametros = json_decode($data['lista']['filtros'], true);
		$parametros['per_page'] = $cantidad;
		$parametros['modo'] = 'envio';
		
		$data['contactos'] = $this->emailer_model->listaDinamica($parametros, $data['detalle']['id']);
		
		if (isset($data['contactos']))
		{
			foreach ($data['contactos'] as $obj)
			{
				$enviar['grupo'] = $data['detalle']['grupo'];
				$enviar['id_empresa'] = $data['detalle']['id_empresa'];
				$enviar['id_newsletter'] = $data['detalle']['id'];
				$enviar['id_template'] = $data['detalle']['id_template'];
				$enviar['id_contacto'] = $obj['id'];
				$enviar['email'] = $data['detalle']['email'];
				$enviar['remitente'] = $data['detalle']['remitente']; 
				$enviar['destinatario'] = $obj['email'];
				$enviar['contacto'] = $obj['contacto'];
				$enviar['asunto'] = $data['detalle']['asunto'];
			
				$this->enviar($enviar, 'smtp');
			}
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function enviar_local($data, $modo = null)
	{
		// db Mailer
		$this->db = $this->load->database('nodo2', true);
		
		// models
		$this->load->model('emailer_model');
		
		
		if ($modo == 'smtp')
		{
			$this->load->model('smtp_model');
			
			$smtp = 1;
			$smtp_config = $this->smtp_model->getServidorEnvio($smtp);
			
			$res['id'] = $this->emailer_model->ingresarEnvio($data['grupo'], $data['id_newsletter'], $data['id_contacto'], $smtp);
		}
		else
		{
			$this->load->config('smtp');
			
			$smtp_config = $this->config->item('smtp');
			
			$res['id'] = $this->emailer_model->ingresarEnvio($data['grupo'], $data['id_newsletter'], $data['id_contacto']);
		}
		
		// helpers and libraries
		$this->load->library('email', $smtp_config);
		
		
		$track_url = base_url();;
		$url = $track_url . 'multimedia/' . $data['grupo'] . '/' . $data['id_empresa'] . '/template-' .  $data['id_template']  . '.php';

		$this->load->library('curl');
		$res['template'] = $this->curl->simple_post($url, (isset($data['data'])) ? array_merge($data, json_decode($data['data'], true)) : $data);
		
		$this->email->set_newline("\r\n");
		$this->email->from($data['email'], $data['remitente']);
		$this->email->to($data['destinatario'], $data['contacto']);
		$this->email->subject($data['asunto']);
		$this->email->message(preg_replace('/(<body.*?(?=>)>)/i', '$1' . '<img src="' . $track_url . 'mlr' . $res['id'] . '.gif' . '" border="0" height="1" width="1" />', $res['template']));
		//if (isset($res['mensaje'])) $this->email->set_alt_message($res['mensaje']);
		
		$this->email->set_header('Track-ID', $res['id']);
		
		
		if (!$this->email->send($smtp_config['nodebug']))
		{
			$res['debug'] = $this->email->print_debugger();
			
			$this->emailer_model->marcarEnvioComoFallido($res['id']);
		}
		else
		{
			if (!$smtp_config['nodebug']) $data['debug'] = $this->email->print_debugger(array('headers', 'subject', 'body'));
			
			$this->emailer_model->marcarEnvioComoEnviado($res['id']);
		}

		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function enviar($data, $modo = null)
	{
		// db Mailer
		$this->db = $this->load->database('nodo2', true);
		
		// models
		$this->load->model('emailer_model');
		$this->load->model('smtp_model');
		
		
		// helpers and libraries
		$this->load->library('phpmailer_lib');
		
		// PHPMailer object
        $mail = $this->phpmailer_lib->load();
        
        $smtp = 43;
		$smtp_config = $this->smtp_model->getServidorEnvio($smtp);
		$res['id'] = $this->emailer_model->ingresarEnvio($data['grupo'], $data['id_newsletter'], $data['id_contacto'], $smtp);

		$mail->isSMTP();
		if (isset($_GET['debug'])) $mail->SMTPDebug = 4;
        $mail->Host     = $smtp_config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_config['smtp_user'];
        $mail->Password = $smtp_config['smtp_pass'];
        $mail->SMTPSecure = $smtp_config['smtp_crypto'];
        $mail->Port     = $smtp_config['smtp_port'];
        
        $mail->setFrom($data['email'], $data['remitente']);
        
        // Add a recipient
        $mail->addAddress($data['destinatario'], $data['contacto']);
        
        // Email subject
        $mail->Subject = $data['asunto'];
        
        // Set email format to HTML
        $mail->isHTML(true);
        
        // Email body content
        $site_url = ($_SERVER['HTTPS'] == 'on') ? 'https://'  . $_SERVER['SERVER_NAME'] . '/' : 'http://' . $_SERVER['SERVER_NAME'] . '/';
		$url = $site_url . 'multimedia/' . $data['grupo'] . '/' . $data['id_empresa'] . '/template-' .  $data['id_template']  . '.php';

		$this->load->library('curl');
		$res['template'] = $this->curl->simple_post($url, (isset($data['data'])) ? array_merge($data, json_decode($data['data'], true)) : $data);
		
        $mail->Body = preg_replace('/(<body.*?(?=>)>)/i', '$1' . '<img src="' . $site_url . 'mlr' . $res['id'] . '.gif' . '" border="0" height="1" width="1" />', $res['template']);
        
        // Send email
        if (!$mail->send())
        {
            $data['debug'] =  $mail->ErrorInfo;
            
            $this->emailer_model->marcarEnvioComoFallido($res['id']);
        }
        else
        {
            $this->emailer_model->marcarEnvioComoEnviado($res['id']);
        }
		

		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function spam($cantidad = 1)
	{
		// db Mailer
		$this->db = $this->load->database('nodo3', true);
		
		// models
		$this->load->model('emailer_model');
		

		$parametros['per_page'] = $cantidad;
		$data['contactos'] = $this->emailer_model->getSuscriptoresActivos($parametros);
		
		
		if (isset($data['contactos']))
		{
			foreach ($data['contactos'] as $obj)
			{
				if (!$this->emailer_model->verificarSiExiste(trim($obj['email']), 502))
				{
					$item['grupo'] = 502;
					$item['id_empresa'] = 1;
					$item['nombre'] = $obj['nombre'];
					$item['apellido'] = $obj['apellido'];
					$item['email'] = $obj['email'];
					$item['area_privada'] = 6;
					$item['estado'] = 2;
					
					$contacto = $this->emailer_model->ingresarSuscriptor($item);
					$categoria = $this->emailer_model->agregarSuscriptorALaCategoria($contacto, 4, 502);
				}
			}
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function crons($cantidad = 1)
	{
		// db Mailer
		$this->db = $this->load->database('nodo2', true);
		
		// models
		$this->load->model('emailer_model');
		
		$data = $this->emailer_model->getNewslettersParaEnviar();
		
		foreach ($data as $obj)
		{
			$this->enviar_newsletter($obj['id'], $cantidad);
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function track($id)
	{
		// db Mailer
		$this->db = $this->load->database('nodo2', true);

		// models
		$this->load->model('emailer_model');
		
		$this->emailer_model->track($id);
	}
	
	
	public function test()
	{
		$obj['email'] = 'diego@revisionalpha.com';
		
		// models
		$this->load->model('emailer_model');
		

		if (!$this->emailer_model->verificarSiExiste(trim($obj['email']), 502))
		{
			$data = 'No exite';
		}
		else
		{
			$data = 'Exite';
		}
		
		echo $data;
	}
	
	
	public function stats($id)
	{
		$reporte['suscriptores'] 	= 2000; // SUSCRIPTORES
		$reporte['restantes'] 		= 1500; // RESTANTES
		$reporte['fallidos'] 		= 100; // FALLIDOS
		
		$reporte['enviados'] 		= 1400; // ENVIADOS
		$reporte['rechazados']		= 50; // RECHAZADOS
		$reporte['recibidos']		= 1000; // NO LEIDOS
		$reporte['abiertos']		= 350; // LECTURAS UNICAS
		$reporte['desuscriptos']	= 5; // DESUSCRIPTODS
		$reporte['clicks']			= 300; // CLICKS
		
		
		/* VALORES EN PORCENTAJE */
		if ($reporte['suscriptores'] > 0)
		{
			$reporte['p_restantes'] 	= round($reporte['restantes']*100/$reporte['suscriptores']); // RESTANTES
			$reporte['p_fallidos'] 		= round($reporte['fallidos']*100/$reporte['suscriptores']); // FALLIDOS
			$reporte['p_enviados'] 		= round($reporte['enviados']*100/$reporte['suscriptores']); // ENVIADOS
		}
		
		if ($reporte['enviados'] > 0)
		{
			$reporte['p_rechazados'] 	= round($reporte['rechazados']*100/$reporte['enviados']); // RECHAZADOS
			$reporte['p_recibidos'] 	= round($reporte['recibidos']*100/$reporte['enviados']); // RECIBIDOS
			$reporte['p_abiertos'] 		= round($reporte['abiertos']*100/$reporte['enviados']); // LECTURAS UNICAS
		}
	
		echo json_encode($reporte);
	}


}