<?php defined('BASEPATH') or exit('No direct script access allowed');

class Geoip extends MY_Controller {

	public function index()
	{
		// load the library
		$this->load->library('geolib/Geolib');
		
		$geo = new Geolib();
		
		$data = $this->geolib->user_agent();
		echo '<pre>' . print_r($data, true) . '</pre>';
		
		$data = $this->geolib->ip_info();
		//$data = $this->geolib->ip_info("208.218.209.22");
		echo '<pre>' . print_r($data, true) . '</pre>';
		
		//$data = $this->geolib->convert_currency("USD", "ARS", 1);
		//echo 'USD 1 / $ ' . $data . '</pre>';
	}


}