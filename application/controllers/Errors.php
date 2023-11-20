<?php defined('BASEPATH') or exit('No direct script access allowed');


class Errors extends MY_Controller {

	public function index()
	{
		$this->load->view('/header');
		$this->load->view('/errors/403.html');
		$this->load->view('/footer');
	}
		

}