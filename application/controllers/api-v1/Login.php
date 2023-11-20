<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Login extends REST_Controller {

	public function index_get()
	{
		$this->load->model('user_model');
		
		$data = $this->usuario;
		if ($this->usuario->estado > 1) $this->user_model->updateUltimaVisita($this->usuario->id);

		$this->response($data);
	}


}