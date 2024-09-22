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
class Procesos extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
		$this->load->model('sys_model');
		
		$this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    

    public function index_put()
	{
		// form values
		$data = $this->sys_model->ingresarProceso($this->usuario->id, $this->put());
		
		if (!$this->put())
		{
			$this->response(null, REST_Controller::HTTP_NO_CONTENT);
		}
		elseif ($data = $this->sys_model->ingresarProceso($this->usuario->id, $this->put()))
		{
			$this->response([
				'data' => $data
			], REST_Controller::HTTP_CREATED);
		}
		else
		{
			$this->response(null, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
	}


}
