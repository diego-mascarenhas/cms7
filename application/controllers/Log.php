<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Log extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['tipo'] = 'E';
			
			$data['lista'] = $this->sys_model->getLogs($parametros);
			
			$config['total_rows'] = $this->sys_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/sys/log', $data) : $this->load->view('/sys/empty');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}