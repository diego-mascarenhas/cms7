<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Sys extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		//models
		$this->load->model('sys_model');
	}
	
	
	public function get_paises()
	{
		$data = $this->sys_model->comboPaises();
			    
	    echo json_encode($data);
	}
	
	
	public function get_provincias()
	{
		if ($this->input->post('id_pais'))
		{
			$data = $this->sys_model->comboProvincias($this->input->post('id_pais'));
			    
			echo json_encode($data);
		}
		else
		{
			echo true;
		}
	}
	
	
	public function get_localidades()
	{
		if ($this->input->post('id_provincia'))
		{
			$data = $this->sys_model->comboLocalidades($this->input->post('id_provincia'));
			    
			echo json_encode($data);
		}
		else
		{
			echo true;
		}
	}
	
	
}
