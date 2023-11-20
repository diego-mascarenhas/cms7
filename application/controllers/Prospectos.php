<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prospectos extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->library('pagination');
	

			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			$parametros['estado'] = 3;
			
			$parametros['order_by'] = 'id';
			$parametros['order'] = 'DESC';

			
			$data['empresas'] = $this->empresa_model->getEmpresas($parametros);
			
			$config['total_rows'] = $this->empresa_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/empresas/index', $data) : $this->load->view('/administracion/empresas/empty');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}
