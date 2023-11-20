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
class Dashboard extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('ticket_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    

    public function index_get()
    {
	    $data = $this->ticket_model->getCantidadDeTickets(1, $parametros);
		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

}
