<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Descargar extends MY_Controller {

	public function index()
	{
		// helpers and libraries
		$this->load->helper('download');
		
		$data = file_get_contents('application/views/developers/sdk.cms.txt');
		$name = 'sdk.cms.php';
		
		force_download($name, $data);
	}


}