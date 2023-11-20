<?php defined('BASEPATH') or exit('No direct script access allowed');


class Errores extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// db Mailer
			$this->db = $this->load->database('mailer', true);
			
			// models
			$this->load->model('newsletter_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			
			
			$data['errores'] = $this->newsletter_model->getNewslettersEnviosErrores($parametros);
			
			$config['total_rows'] = $this->newsletter_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/emailer/errores/index', $data) : $this->load->view('/emailer/errores/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}