<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    
    public function login_get()
    {
	    $username = $this->input->get('user');
	    $password = $this->input->get('pass');
	    
	    if ($this->user_model->userLogin($username, $password))
		{
			$id = $this->user_model->getUserIdFromUsername($username);
			$data = (array) $this->user_model->getUserInfo($id);
			
			if ($this->input->get('id_aplicacion') && $this->input->get('token')) $this->user_model->setToken($id, $this->input->get('id_aplicacion'), $this->input->get('token'));
		}
		else
		{
			$data['error'] = 'Error';
		}
		
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
    
    
    public function login_post()
    {
	    $username = $this->input->post('user');
	    $password = $this->input->post('pass');
	    
	    if ($this->user_model->userLogin($username, $password))
		{
			$id = $this->user_model->getUserIdFromUsername($username);
			$data = (array) $this->user_model->getUserInfo($id);
		}
		else
		{
			$data['error'] = 'Error';
		}
		
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
