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
class Comunicaciones extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('comunicacion_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    

    public function index_get($id = null)
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
				$data = $this->comunicacion_model->getComunicacionTemplate($id);
			}
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$parametros['id_contacto'] = $this->input->get('id_contacto');
			
	        $data = $this->comunicacion_model->getComunicaciones($parametros);
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
    
    
    public function index_put()
	{
		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());

		// set validation rules
		$this->form_validation->set_rules('id_contacto', 'Contacto', 'required|trim|integer');
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('id_servicio', 'Servicio', 'trim|integer');
		$this->form_validation->set_rules('id_area', 'Area', 'trim|required|integer');
		$this->form_validation->set_rules('asunto', 'Asunto', 'trim|required');
		$this->form_validation->set_rules('prioridad', 'Prioridad', 'trim|integer');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		$this->form_validation->set_rules('mensaje', 'Mensaje', 'trim|required');
		$this->form_validation->set_rules('visibilidad', 'Visibilidad', 'trim|integer');
		$this->form_validation->set_rules('id_origen', 'Origen', 'trim|integer');
		
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
			$this->db->trans_begin();
			
			// form values
			$valores = $this->put();
			$valores['id_origen'] = (isset($valores['id_origen'])) ? $valores['id_origen'] : 5;
			$valores['mensaje'] = nl2br($valores['mensaje']);

			$data = $this->comunicacion_model->ingresarTicket($valores);
			$data['item'] = $this->comunicacion_model->ingresarTicketItem($data['id'], $valores);
			if ($this->usuario->perfil != 'reseller') $data['id_contacto'] = $this->comunicacion_model->asociarContacto($data['id'], $this->usuario->id);
			if ($valores['id_contacto']) $this->comunicacion_model->asociarContacto($data['id'], $valores['id_contacto']);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$this->response([
	                'status' => false,
	                'message' => 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde'
	            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
			}
			else
			{
				$this->db->trans_commit();
				
				$contactos = $this->comunicacion_model->notificarNuevoTicket($data['item']['id']);
				
				$this->response([
	                'status' => true,
	                'id' => $data['item']['id'],
	                'id_ticket' => $data['id']
	            ], REST_Controller::HTTP_CREATED);
			}
		}
	}


}
