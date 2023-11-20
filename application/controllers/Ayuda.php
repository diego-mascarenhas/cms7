<?php defined('BASEPATH') or exit('No direct script access allowed');


class Ayuda extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('admin') || $this->is_logged_in('user'))
		{
			//models
			$this->load->model('hosting_model');
			$this->load->model('ticket_model');
			$this->load->model('empresa_model');
			$this->load->model('factura_model');
			
			if ($this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']))
			{
				redirect(base_url('ayuda/desbloquear-ip'));
			}
			
			else
			{
				$data['blocked_ip'] = $this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']);
				$data['tickets'] = $this->ticket_model->getTickets(array('estado'=>'abiertos'));
				$data['balance'] = $this->empresa_model->getEmpresaSaldo($this->usuario->id_empresa, 45);
				$data['facturas'] = $this->factura_model->getFacturas();
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/ayuda.css')
								);
								
				$this->load->view('/header', $header);
				$this->load->view('/ayuda/wizard', $data);
				$this->load->view('/footer');
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
	
	
	public function test()
	{
		if ($this->is_logged_in('admin') || $this->is_logged_in('user'))
		{
			//models
			$this->load->model('hosting_model');
			$this->load->model('ticket_model');
			$this->load->model('empresa_model');
			$this->load->model('factura_model');
			
			if ($this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']))
			{
				redirect(base_url('ayuda/desbloquear-ip'));
			}
			
			else
			{
				$data['blocked_ip'] = $this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']);
				$data['tickets'] = $this->ticket_model->getTickets(array('estado'=>'abiertos'));
				$data['balance'] = $this->empresa_model->getEmpresaSaldo($this->usuario->id_empresa, 45);
				$data['facturas'] = $this->factura_model->getFacturas();
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/ayuda.css')
								);
								
				$this->load->view('/header', $header);
				$this->load->view('/ayuda/index', $data);
				$this->load->view('/footer');
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
	
	
	public function desbloquear_ip()
	{
		if ($this->is_logged_in('admin') || $this->is_logged_in('user'))
		{
			// models
			$this->load->model('ticket_model');
			$this->load->model('mailbox_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('ip', 'IP', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle']['ip'] = $_SERVER['REMOTE_ADDR'];
				
				$this->load->view('/header');
				$this->load->view('/ayuda/desbloquear_ip', $data);
				$this->load->view('/footer');
			}
			else
			{
				$this->db->trans_begin();
				
				// form values
				$valores['id_empresa'] = $this->usuario->id_empresa;
				$valores['id_area'] = 4;
				$valores['id_origen'] = 3;
				$valores['prioridad'] = 4;
				$valores['asunto'] = 'Solicito el desbloqueo de la IP: ' . $this->input->post('ip');
				$valores['mensaje'] = nl2br($this->mailbox_model->getEmailDetalle($this->mailbox_model->getEmailIdFromBlockedIp($this->input->post('ip')))['body']);

				$data = $this->ticket_model->ingresarTicket($valores);
				$data['item'] = $this->ticket_model->ingresarTicketItem($data['id'], $valores);
				$data['id_contacto'] = $this->ticket_model->asociarContacto($data['id'], $this->usuario->id);
	
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';
	
					$this->load->view('/ayuda/error/', $data);
				}
				else
				{
					$this->db->trans_commit();
					
					$this->ticket_model->notificarNuevoTicket($data['item']['id']);
					
					redirect(base_url('tickets/detalle/' . $data['id']));
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
	
	
	public function seleccion_del_servicio($problema)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('servicio_model');
			
			
			$data['servicios'] = $this->servicio_model->getServicios(array('tipo'=>'hosting'));
			$data['detalle']['problema'] = $problema;
			
			if ($this->servicio_model->total() == 1)
			{
				redirect(base_url('ayuda/detalle-del-servicio/' . $data['detalle']['problema'] . '/' . $data['servicios'][0]['id']));
			}
			else
			{
				$this->load->view('/header');
				$this->load->view('/ayuda/' . $problema, $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle_del_servicio($problema, $id)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('servicio_model');
			$this->load->model('hosting_model');
			
			
			$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
			$data['detalle']['problema'] = $problema;
			
			if ($data['detalle']['id_servicio_hosting'])
			{
				$data['plan'] = $this->hosting_model->getPlanDetalle($data['detalle']['id_servicio_hosting']);
				
				$obj = $this->hosting_model->getPlanParaActualizar($data['detalle']['id_servicio_hosting']);
		
				if (isset($obj))
				{
					$config = $this->hosting_model->getCredenciales($obj['id_servidor']);
					$this->load->library('Cpanel', $config);
		
					$res['diskusage'] = $this->hosting_model->actualizarDiskusage($obj['user']);
					$res['bndwidthusage'] = $this->hosting_model->actualizarBandwidthusage($obj['user']);
					
					$data['plan'] = $this->hosting_model->getPlanDetalle($data['detalle']['id_servicio_hosting']);
					
					$data['emails'] = $this->cpanel->listpopswithdisk($obj['user']);
				}
				
				// helpers and libraries
				$this->load->helper('number');
				
				$data['nagios'] = $this->hosting_model->getAlertas(array('host'=>'hebe'));
			}
			
			$this->load->view('/header');
			$this->load->view('/ayuda/detalle_del_servicio', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function administracion()
	{
		if ($this->is_logged_in())
		{
			$data = null;
			
			$this->load->view('/header');
			$this->load->view('/ayuda/administracion', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
}