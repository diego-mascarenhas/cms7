<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Mailer extends MY_Controller {

	public function newsletters()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/newsletters');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}