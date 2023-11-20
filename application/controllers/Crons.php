<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Crons extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('cron_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$data['crons'] = $this->cron_model->getCrons($parametros);
			
			$config['total_rows'] = $this->cron_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/crons/index', $data) : $this->load->view('/crons/empty');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ejecutar()
	{
		//models
		$this->load->model('cron_model');
		
		$data['destrabar'] = $this->cron_model->destrabarCrons();
		$data['crons'] = $this->cron_model->ejecutarCrons();
				
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function index_get($id = null)
	{
		$this->load->model('contacto_model');

		if ($id)
		{
			$data = $this->contacto_model->getContactoDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->contacto_model->getContactos($parametros);
		}

		$this->response($data);
	}
	
	
	public function voip()
	{
		$this->load->model('voip_model');
		
		$data = $this->voip_model->actualizarCreditos();

		$this->response($data);
	}

}