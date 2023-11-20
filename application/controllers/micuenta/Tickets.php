<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Tickets extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('ticket_model');
			
			
			$data['tickets'] = $this->ticket_model->getTickets();
			
			
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('/tickets/index', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}