<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	public function __construct() {
		parent::__construct();
	}


	public function valid_date($value) {
		$date = DateTime::createFromFormat('d/m/Y', $value);
		return ($date) ? true : false;
	}

	public function valid_time($value) {
		$time = DateTime::createFromFormat('H:i', $value);
		return ($time) ? true : false;
	}

	public function is_date_after_now($value) {
		$date = DateTime::createFromFormat('d/m/Y H:i', $value . ' 00:00');
		$now  = new DateTime();

		return ($date->format('U') > $now->format('U')) ? true : false;
	}
	
	public function valid_dni($value) {
		return (preg_match('/^(\d{1,8})$/', $value)) ? true : false;
	}
	
	public function valid_cuit($value) {
		return (preg_match('/^(\d{9,12})$/', $value)) ? true : false;
	}


	public function do_the_error($value) {
		return false;
	}
}