<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Landings extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('landing_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    

    public function impresion_post() // view
	{
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id', 'ID Landing', 'required|trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
			
			$this->response([
                'status' => false,
                'message' => $data['form_errors']
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
		else
		{
			$data = $this->post();
		
			$this->landing_model->incrementar($data['id']);
			
			$this->response([
                'status' => true,
                'id' => $data['id']
            ], REST_Controller::HTTP_CREATED);
		}
		
		$this->response($data);
	}
	
	
	public function conversion_post() // thankyoupage
	{
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id', 'ID Landing', 'required|trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
			
			$this->response([
                'status' => false,
                'message' => $data['form_errors']
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
		else
		{
			$data = $this->post();
		
			$this->landing_model->incrementar($data['id'], 'conversiones');
			$stats = $this->landing_model->track($data['id'], $this->input->post());
			
			$this->response([
                'status' => true,
                'id' => $data['id']
            ], REST_Controller::HTTP_CREATED);
		}
		
		$this->response($data);
	}
	
	
}
