<?php defined('BASEPATH') or exit('No direct script access allowed');


class Tickets extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('ticket_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			$this->lang->load('tickets', $this->usuario->idioma);
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['agente'] = $this->ticket_model->esAgente();
			$data['tickets'] = $this->ticket_model->getTickets($parametros);
			
			$config['total_rows'] = $this->ticket_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/tickets/index', $data) : $this->load->view('/tickets/empty', $data);
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
			$this->load->model('ticket_model');
			$this->load->model('servicio_model');
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->helper('text');
			
			
			if ($data['detalle'] = $this->ticket_model->getTicketDetalle($id))
			{
				$this->load->model('nota_model');
				$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>50, 'id_referencia'=>$id));
				
				// set validation rules
				$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required');
				$this->form_validation->set_rules('visibilidad', 'Visibilidad', 'trim|integer');
				$this->form_validation->set_rules('id_origen', 'Origen', 'trim|integer');
				
				if ($this->form_validation->run() === false)
				{
					// form values
					foreach ($this->ticket_model->getTicketItems($data['detalle']['id']) as $item)
					{
						$data['detalle']['items'][$item['id']] = $item;
						$data['detalle']['items'][$item['id']]['adjuntos'] = $this->ticket_model->getTicketItemAdjuntos($item['id']);;
					}
					
					$data['detalle']['agentes'] = $this->ticket_model->getTicketContactosAsociados($data['detalle']['id']);
					$data['detalle']['servicios'] = $this->servicio_model->getServicios(array('id_empresa'=>$data['detalle']['id_empresa']));
					
					$data['agentes'] = $this->ticket_model->comboAgentes();
					$data['contactos'] = $this->contacto_model->comboContactos();
					
					$data['detalle']['mensaje'] = $this->input->post('mensaje');
					$data['detalle']['visibilidad'] = $this->input->post('visibilidad');
					$data['detalle']['id_origen'] = $this->input->post('id_origen');
						
					// validation not ok, send validation errors to the view
					$this->load->view('/header');
					$this->load->view('/tickets/detalle', $data);
					$this->load->view('/footer');
				}
				else
				{
					// form values
					$valores = $this->input->post();
					$valores['id_origen'] = (isset($valores['id_origen'])) ? $valores['id_origen'] : 3;
					$valores['mensaje'] = nl2br($valores['mensaje']);
					
					if ($data = $this->ticket_model->ingresarTicketItem($id, $valores))
					{
						if (!$this->ticket_model->verificarAsociacionDeContacto($id, $this->usuario->id)) $this->ticket_model->asociarContacto($id, $this->usuario->id);
						
						$this->ticket_model->cambiarEstado($id, 3);
						$this->ticket_model->setInicio($id);
						$contactos = $this->ticket_model->notificarNuevaRespuesta($data['id']);
						
	
						// APP
						if ($contactos)
						{
							// models
							$this->load->model('app_model');
							
							// helpers and libraries
							$this->load->config('firebase');
							$this->load->library('Firebase', $this->config->item('firebase'));
												
							foreach ($contactos as $contacto)
							{
								if ($contacto['id'] != $this->usuario->id) $dispositivos = $this->app_model->getDispositivos(1, $contacto['id']);
							
								if (isset($dispositivos))
								{
									$push['id_tipo'] = '26';
									$push['title'] = 'Nuevo respuesta';
									$push['body'] = $valores['mensaje'];
									$push['id_referencia'] = $id;
									
									foreach ($dispositivos as $obj)
									{
										$res_push = $this->firebase->notificar($obj['token'], $push);
										
										if ($res_push['failure']) $this->app_model->desactivarDispositivo(1, $obj['token']);
									}
								}
							}
						}

						
						// upload
						if (!empty($_FILES['file']['name']))
						{
							$config['upload_path'] = FCPATH . 'multimedia/tickets/';
						    $config['encrypt_name'] = true;
						    $config['file_ext_tolower'] = 'true';
							$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp|pdf|zip|doc|docx|ppt|pptx|pps';
							
							$this->load->library('upload', $config);

							if (!$this->upload->do_upload('file'))
					        {
								$data['error'] = $this->upload->display_errors();
								
								$this->load->view('/tickets/error/', $data);
					        }
					        else
					        {
						        $upload_data = $this->upload->data();
						        
						        $this->load->model('multimedia_model');
			
								$data['id_ticket_item'] = $data['id'];
								$data['id_tipo'] = $this->multimedia_model->getMediaTipoId($upload_data['file_ext']);
								$data['nombre'] = $upload_data['orig_name'];
								$data['archivo'] = $upload_data['file_name'];
								$data['peso'] = $upload_data['file_size'];
					
								$this->ticket_model->ingresarTicketItemAdjunto($data);
					        }
						}
						
						# Bot
						if ($bot = $this->ticket_model->clasificarTicket($valores['mensaje']))
						{
							if (!$this->ticket_model->verificarAsociacionDeContacto($id, $bot['id_contacto'])) $this->ticket_model->asociarContacto($id, $bot['id_contacto']);
							
							$valores['id_contacto'] = $bot['id_contacto'];
							$valores['id_origen'] = 5;
							$valores['mensaje'] = nl2br($bot['mensaje']);
							
							if ($data = $this->ticket_model->ingresarTicketItem($id, $valores))
							{
								$this->ticket_model->cambiarEstado($id, 6);
								$this->ticket_model->notificarNuevaRespuesta($data['id']);
							}
						}
						
						
						redirect(base_url('tickets/detalle/' . $id));
					}
					else
					{
						$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
		
						$this->load->view('/tickets/error/', $data);
					}
				}
			}
			else
			{
				redirect(base_url('tickets/'));
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar()
	{
		// models
		$this->load->model('ticket_model');
		$this->load->model('empresa_model');
		$this->load->model('servicio_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);


		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('id_servicio', 'Servicio', 'trim|integer');
		$this->form_validation->set_rules('id_area', 'Area', 'trim|required|integer');
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required');
		$this->form_validation->set_rules('prioridad', 'Prioridad', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required');
		$this->form_validation->set_rules('visibilidad', 'Visibilidad', 'trim|integer');
		$this->form_validation->set_rules('id_origen', 'Origen', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
			$data['detalle']['id_area'] = (isset($data['detalle']['id_area'])) ? $data['detalle']['id_area'] : 4;
			$data['detalle']['prioridad'] = (isset($data['detalle']['prioridad'])) ? $data['detalle']['prioridad'] : 1;
			if (isset($data['detalle']['id_empresa'])) $data['servicios'] = $this->servicio_model->comboServicios(array('id_empresa'=>$data['detalle']['id_empresa']));
			
			// validation not ok, send validation errors to the view
			$this->load->view('/header');
			$this->load->view('/tickets/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			$this->db->trans_begin();
			
			// form values
			$valores = $this->input->post();
			$valores['id_origen'] = (isset($valores['id_origen'])) ? $valores['id_origen'] : 3;
			$valores['mensaje'] = nl2br($valores['mensaje']);

			$data = $this->ticket_model->ingresarTicket($valores);
			$data['item'] = $this->ticket_model->ingresarTicketItem($data['id'], $valores);
			if ($this->usuario->perfil != 'reseller') $data['id_contacto'] = $this->ticket_model->asociarContacto($data['id'], $this->usuario->id);
			if ($valores['id_contacto']) $this->ticket_model->asociarContacto($data['id'], $valores['id_contacto']);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';

				$this->load->view('/tickets/error/', $data);
			}
			else
			{
				$this->db->trans_commit();
				
				$contactos = $this->ticket_model->notificarNuevoTicket($data['item']['id']);
				
				
				// APP
				if ($contactos)
				{
					// models
					$this->load->model('app_model');
					
					// helpers and libraries
					$this->load->config('firebase');
					$this->load->library('Firebase', $this->config->item('firebase'));
										
					foreach ($contactos as $contacto)
					{
						if ($contacto['id'] != $this->usuario->id) $dispositivos = $this->app_model->getDispositivos(1, $contacto['id']);
					
						if (isset($dispositivos))
						{
							$push['id_tipo'] = '26';
							$push['title'] = 'Nuevo ticket';
							$push['body'] = $valores['asunto'];
							$push['id_referencia'] = $data['id'];
							
							foreach ($dispositivos as $obj)
							{
								$res_push = $this->firebase->notificar($obj['token'], $push);
								
								if ($res_push['failure']) $this->app_model->desactivarDispositivo(1, $obj['token']);
							}
						}
					}
				}
				
				
				redirect(base_url('tickets/detalle/' . $data['id']));
			}
		}
	}
	
	
	public function modificar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('ticket_model');
			$this->load->model('empresa_model');
			$this->load->model('servicio_model');
	
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
				
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
			$this->form_validation->set_rules('id_servicio', 'Servicio', 'trim|integer');
			$this->form_validation->set_rules('id_area', 'Area', 'trim|required|integer');
			$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required');
			$this->form_validation->set_rules('prioridad', 'Prioridad', 'trim|required|integer');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|required|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->ticket_model->getTicketDetalleRaw($id);
				if (isset($data['detalle']['id_empresa'])) $data['servicios'] = $this->servicio_model->comboServicios(array('id_empresa'=>$data['detalle']['id_empresa']));
				$data['estados'] = $this->ticket_model->comboTicketsEstados();
					
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/tickets/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->ticket_model->modificarTicket($id, $this->input->post()))
				{
					redirect(base_url('tickets/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
	
					$this->load->view('/tickets/error/', $data);
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function agentes()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('ticket_model');
			
			
			$data['agentes'] = $this->ticket_model->getAgentesArea();
			
			$this->load->view('/header');
			$this->load->view('/tickets/agentes', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function get_efectividad_agente($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('ticket_model');
			
			// Mes anterior
			$parametros['id_contacto'] = $id;
			
			$data['tickets'] = $this->ticket_model->getTicketsMesFromuserId($parametros, 1);
			
			if (isset($data['tickets']))
			{
				$total = null;
				
				foreach ($data['tickets'] as $obj)
				{
					$total = $total+100*$obj['efectividad']/100;
				}
				
				$data['total']['anterior'] = $total;
			}
			
			
			// Mes actual
			$data['tickets_actuales'] = $this->ticket_model->getTicketsMesFromuserId($parametros);
			
			if (isset($data['tickets_actuales']))
			{
				$total = null;
				
				foreach ($data['tickets_actuales'] as $obj)
				{
					$total = $total+100*$obj['efectividad']/100;
				}
				
				$data['total']['actual'] = $total;
			}
			
			
			$this->load->view('/header');
			$this->load->view('/tickets/efectividad', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function asignar_contacto()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('ticket_model');
			$this->load->model('user_model');
			
			if (!$this->ticket_model->verificarAsociacionDeContacto($this->input->post('id'), $this->input->post('id_contacto')))
			{
				$this->ticket_model->asociarContacto($this->input->post('id'), $this->input->post('id_contacto'));
				
				// form values
				$valores = $this->input->post();
				$valores['id_origen'] = 3;
				$valores['visibilidad'] = 1;
				$valores['mensaje'] = 'El usuario ' . $this->usuario->contacto . ' ha añadido a ' . $this->user_model->getUserInfo($this->input->post('id_contacto'))->contacto . ' a la conversación';
				
				if ($this->usuario->id != $this->input->post('id_contacto') && $data = $this->ticket_model->ingresarTicketItem($this->input->post('id'), $valores))
				{
					$this->ticket_model->notificarAsignacion($data['id'], $this->input->post('id_contacto'));
					
					
					// models
					$this->load->model('app_model');
					
					// helpers and libraries
					$this->load->config('firebase');
					$this->load->library('Firebase', $this->config->item('firebase'));
										
				
					if ($this->input->post('id_contacto') != $this->usuario->id) $dispositivos = $this->app_model->getDispositivos(1, $this->input->post('id_contacto'));
				
					if (isset($dispositivos))
					{
						$push['id_tipo'] = '26';
						$push['title'] = 'Nueva asignación';
						$push['body'] = 'El usuario ' . $this->usuario->contacto . ' te ha asignado un ticket';
						$push['id_referencia'] = $this->input->post('id');
						
						foreach ($dispositivos as $obj)
						{
							$res_push = $this->firebase->notificar($obj['token'], $push);
							
							if ($res_push['failure']) $this->app_model->desactivarDispositivo(1, $obj['token']);
						}
					}
					
				}
			}
			
			redirect(base_url('tickets/detalle/' . $this->input->post('id')));
		}
	}
	
	
	public function ingresar_comunicaciones()
	{
		// models
		$this->load->model('ticket_model');
		
		$data['comunicaciones']['nivel1'] = $this->ticket_model->ingresarComunicaciones(); // 0 MINUTOS (Nivel 1)
		$data['comunicaciones']['nivel2'] = $this->ticket_model->ingresarComunicaciones(120, 2); // 2 MINUTOS (Nivel 2)
		$data['comunicaciones']['nivel3'] = $this->ticket_model->ingresarComunicaciones(300, 3, 4); // 5 MINUTOS (Nivel 3 - Críticos)
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function enviar_comunicacion($id)
	{
		// models
		$this->load->model('ticket_model');
		
		$data['template'] = $this->ticket_model->getAlertaTemplate($id);
		$data['comunicacion'] = $this->ticket_model->enviarComunicacion($id);
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function enviar_alertas()
	{
		// models
		$this->load->model('ticket_model');
		
		$data['alertas'] = $this->ticket_model->enviarComunicaciones();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function enviar_alertas_criticas()
	{
		// models
		$this->load->model('ticket_model');
		$this->load->model('hosting_model');
		
		$data['alertas'] = $this->ticket_model->getAlertasCriticas();

			
		if (count($data['alertas']))
		{
			$this->load->library('curl');
			
			//$data['agente']['celular'] = $this->hosting_model->getContactoDeGuardiaCelular($this->hosting_model->getContactoDeGuardiaId());
			//$data['agente']['alerta'] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>$data['agente']['celular'])), true); // revision alpha
			
			foreach ($this->hosting_model->getAgentes() as $obj)
			{
				$data['agente']['alerta'] = json_decode($this->curl->simple_post('http://voip.revisionalpha.com/alerta-revision.php', array('agente'=>$this->hosting_model->getContactoDeGuardiaCelular($obj))), true);
			}
		}
	}
	
	
	public function set_inicio($id)
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->setInicio($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function get_inicio($id)
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->getInicio($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function actualizar_inicios()
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->actualizarInicios();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function get_tickets_stats()
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->getTicketsStats();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function get_efectividad($id)
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->getEfectividad($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function ayuda()
	{
		// models
		$this->load->model('ticket_model');
		
		if ($this->is_logged_in('reseller'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			// set validation rules
			$this->form_validation->set_rules('texto', 'Texto', 'trim|required|min_length[5]');
			
			if ($this->form_validation->run() === false)
			{
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
			
				$this->load->view('/header');
				$this->load->view('/tickets/ayuda', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data = $this->ticket_model->clasificarTicket($this->input->post('texto'));
				
				if ($data)
				{
					echo '<pre>' . print_r($data, true) . '</pre>';	
				}
				else
				{
					echo 'No se encontraron sugerencias';
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function crons()
	{
		// models
		$this->load->model('ticket_model');
		
		$data = $this->ticket_model->notificarTicketsSinEnviar();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function track($id)
	{
		// models
		$this->load->model('ticket_model');
		
		$this->ticket_model->track($id);
	}
	
	
	public function menu($padre = null)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('ticket_model');
			
			
			$data['menu'] = $this->ticket_model->menu($padre, null, 10, null, 3);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}