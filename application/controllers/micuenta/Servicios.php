<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Servicios extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('servicio_model');

			$data['servicios'] = $this->servicio_model->getServicios();

			
			$this->load->view('/header');
			$this->load->view('/micuenta/servicios/index', $data);
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
			// models
			$this->load->model('servicio_model');
			$this->load->model('hosting_model');
			
			
			$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
			if (isset($data['detalle']['host'])) $data['nagios'] = $this->hosting_model->getNagiosStatsLive(array('host'=>$data['detalle']['host']));
			
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
				}
				
				$data['emails'] = $this->cpanel->listpopswithdisk($obj['user']);
				
				// helpers and libraries
				$this->load->helper('number');
				
				$data['nagios'] = $this->hosting_model->getNagiosStatsLive(array('host'=>$data['plan']['servidor']));
			}
			
			$this->load->view('/header');
			$this->load->view('/micuenta/servicios/detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function para_activar($id)
	{
		if ($this->is_logged_in('admin'))
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
				$this->load->view('/micuenta/servicios/activar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($this->input->post('id'), 'servicios'))
				{
					$res = $this->servicio_model->cambiarEstado($this->input->post('id'), 3);
					
					redirect(base_url('micuenta/servicios/detalle/' . $this->input->post('id')));
				}
				else
				{
					// form values
					$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/header');
					$this->load->view('/micuenta/servicios/activar', $data);
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
	
	
	public function activar($id)
	{
		$this->para_activar($id);
	}
	
	
	public function para_suspender($id)
	{
		if ($this->is_logged_in('admin'))
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
				$this->load->view('/micuenta/servicios/suspender', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($this->input->post('id'), 'servicios'))
				{
					$res = $this->servicio_model->cambiarEstado($this->input->post('id'), 2);
					
					redirect(base_url('micuenta/servicios/detalle/' . $this->input->post('id')));
				}
				else
				{
					// form values
					$data['detalle'] = $this->servicio_model->getServicioDetalle($id);
				
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/header');
					$this->load->view('/micuenta/servicios/activar', $data);
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
	
	
	public function suspender($id)
	{
		$this->para_suspender($id);
	}


}