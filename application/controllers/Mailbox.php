<?php defined('BASEPATH') or exit('No direct script access allowed');


class Mailbox extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('mailbox_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['filter'] = $this->input->get('filter');
			$parametros['id_tipo'] = $this->input->get('id_tipo');
			
			if ($this->input->get('prioridad')) $parametros['prioridad'] = $this->input->get('prioridad');

			$data['emails'] = $this->mailbox_model->getEmails($parametros);
			
			$config['total_rows'] = $this->mailbox_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$data['total_rows'] = $config['total_rows'];

			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('/mailbox/index', $data);
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
			$this->load->model('mailbox_model');
			
			$this->mailbox_model->marcarComoLeido($id);
			$data['detalle'] = $this->mailbox_model->getEmailDetalle($id);

			
			$this->load->view('/header');
			$this->load->view('/mailbox/detalle', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('mailbox_model');
			
			$this->mailbox_model->eliminar($id);
			
			redirect(base_url('mailbox'));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function marcar_como_leidos_varios()
	{
		// models
		$this->load->model('mailbox_model');
			
		foreach ($this->input->post('checks') as $obj)
		{
			$res[] = $this->mailbox_model->marcarComoLeido($obj);
		}
		
		echo json_encode($res);
	}
	
	
	public function eliminar_varios()
	{
		// models
		$this->load->model('mailbox_model');
			
		foreach ($this->input->post('checks') as $obj)
		{
			$res[] = $this->mailbox_model->eliminar($obj);
		}
		
		echo json_encode($res);
	}
	
	
	public function eliminar_emails_anteriores($tiempo)
	{
		// models
		$this->load->model('mailbox_model');
		
		$res = $this->mailbox_model->eliminarEmailsAnteriores($tiempo);
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($res, true) . '</pre>';
	}
	
	
	public function test()
	{
		$this->load->library('email_reader');
		
/*
		while (1) {
		// get an email
		$email = $this->email_reader->get();
		
		// if there are no emails, jump out
		if (count($email) <= 0) {
			break;
			}
		}
*/
		echo '<pre>' . print_r($this->email_reader->get(4), true) . '</pre>';
		
		//$emails = $this->email_reader->inbox();
		//$emails = $this->email_reader->get(1);
		//echo '<pre>' . print_r($emails, true) . '</pre>';
		
		//$emails = New Email_reader();
		$emails = $this->email_reader->inbox();
		echo '<pre>' . print_r($emails, true) . '</pre>';
		
		echo $total = count($emails->inbox);
		
		for($i=$total-1;$i>=0;$i--)
		{
			$email = $emails->inbox[$i];
			echo '<pre>' . print_r($email, true) . '</pre>';
		}
		
		$this->email_reader->close();
	}
	

}