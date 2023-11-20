<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			redirect(base_url());
		}
		
		elseif ($this->is_logged_in('admin'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('sys_model');
			$this->load->model('contacto_model');
			$this->load->model('empresa_model');
			$this->load->model('servicio_model');
			$this->load->model('factura_model');
			$this->load->model('multimedia_model');
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->helper('number');
			$this->load->helper('text');
			
			$data['servicios'] = $this->servicio_model->getServicios();
			$data['facturas'] = $this->factura_model->getFacturas();
			$data['balance'] = $this->empresa_model->getEmpresaSaldo($this->usuario->id_empresa);
			$data['contactos'] = $this->contacto_model->getCantidadDeContactosActivos();
			
			$data['media']['archivos'] = $this->multimedia_model->getMediaArchivos();
			$data['media']['proyectos'] = $this->multimedia_model->getMediaProyectos();
			$data['media']['espacio'] = $this->multimedia_model->getMediaEspacio();
			
			// Alertas
			$this->sys_model->setAlerta('contacto', (!$this->session->userdata('usuario')->password) ? true : false);
			$this->sys_model->setAlerta('empresa', (!$this->session->userdata('usuario')->id_empresa || $this->empresa_model->verificarDatosDeLaEmpresaIncompletos($this->usuario->id_empresa)) ? true : false);
			$this->sys_model->setAlerta('saldo', $this->empresa_model->getEmpresaSaldo($this->usuario->id_empresa)['saldo']);
			$this->sys_model->setAlerta('ip', $this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']));
			
			$this->load->view($this->tema() . '/header');
			$this->load->view($this->tema() . '/micuenta/index', $data);
			$this->load->view($this->tema() . '/footer');
		}
		
		elseif ($this->is_logged_in('user'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('sys_model');
			$this->load->model('servicio_model');
			$this->load->model('hosting_model');
			
			// helpers and libraries
			$this->load->helper('number');
			$this->load->helper('text');
			
			$data['servicios'] = $this->servicio_model->getServicios();
			
			// Alertas
			$this->sys_model->setAlerta('contacto', (!$this->session->userdata('usuario')->password) ? true : false);
			$this->sys_model->setAlerta('ip', $this->hosting_model->verificarSiEstaBloqueada($_SERVER['REMOTE_ADDR']));
			
			$this->load->view($this->tema() . '/header');
			$this->load->view($this->tema() . '/micuenta/index', $data);
			$this->load->view($this->tema() . '/footer');
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}


}