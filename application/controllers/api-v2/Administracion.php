<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Administracion extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('contacto_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    
    public function contactos_get($id = null)
    {
	    if ($id)
		{
			$id = (int) $id;

	        if ($id <= 0)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->contacto_model->getContactoDetalleRaw($id);
			}
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			$parametros['categoria'] = $this->input->get('categoria');
			
	        $data = $this->contacto_model->getContactos($parametros);
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
            $this->response(null, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    

}
