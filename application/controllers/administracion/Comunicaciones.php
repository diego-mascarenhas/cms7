<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Comunicaciones extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('comunicacion_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$parametros['estado'] = $this->input->get('estado');

			
			$data['lista'] = $this->comunicacion_model->getComunicaciones($parametros);
						
			$config['total_rows'] = $this->comunicacion_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/comunicaciones/index', $data) : $this->load->view('/administracion/comunicaciones/empty');
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
			$this->load->model('comunicacion_model');
			
			$data['detalle'] = $this->comunicacion_model->getComunicacionTemplate($id);
			echo $data['detalle']['template'];
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function stats()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('comunicacion_model');
			
			$parametros['search'] = $this->input->get('search');
			
			$data['lista'] = $this->comunicacion_model->getComunicacionesStats($parametros);
			
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('/administracion/comunicaciones/stats', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function enviar_comunicacion($id)
	{
		// models
		$this->load->model('comunicacion_model');
		
		$data['detalle'] = $this->comunicacion_model->getComunicacionTemplate($id);
		$data['comunicacion'] = $this->comunicacion_model->enviarComunicacion($id);
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function crons()
	{
		// models
		$this->load->model('comunicacion_model');
		
		$data = $this->comunicacion_model->enviarComunicaciones();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function track($id)
	{
		// models
		$this->load->model('comunicacion_model');
		
		$this->comunicacion_model->track($id);
	}
	

}