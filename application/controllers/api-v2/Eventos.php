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
class Eventos extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('evento_model');
        
        $this->usuario = ($this->rest->user_id > 40000) ? $this->user_model->getUserInfo($this->rest->user_id) : $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 10000; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 10000; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    
    public function verificar_si_existe_post()
    {
	    $data = $this->evento_model->verificarSiExiste($this->usuario->grupo, $this->usuario->id_empresa, $this->post('email'));
		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => 'error',
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
	        //$this->evento_model->updateUltimaVisita($data['id']);
	        
            $this->response([
                'status' => true,
                'message' => 'ok',
                'contacto' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }
        
    public function verificar_eleccion_post()
    {
	    $data = $this->evento_model->verificarEleccion($this->usuario->grupo, $this->usuario->id_empresa, $this->post('email'));
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response([
                'status' => true,
                'message' => 'ok',
                'email' => $data['email']
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

    public function update_visita_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->updateUltimaVisita($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function eventogrupos_get()
    {
	    $data = $this->evento_model->getGrupos($this->usuario->grupo, $this->usuario->id_empresa);
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
	
	public function seleccionargrupo_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarGrupo($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function ingresarintegrantes_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarIntegrantes($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function detallegrupo_get($id)
    {
	    $data = $this->evento_model->detalleGrupo($this->usuario->grupo, $this->usuario->id_empresa, $id);
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
    
    public function modificarintegrantes_get($id)
    {
	    $data = $this->evento_model->updateIntegrantes($this->usuario->grupo, $this->usuario->id_empresa, $id);
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

	public function ingresarcontacto_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarContacto($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function modificarcontacto_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->modificarContacto($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function inscriptos_get()
    {
	    $data = $this->evento_model->getInscriptos($this->usuario->grupo, $this->usuario->id_empresa);
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

    public function paises_get()
    {
	    $data = $this->evento_model->getPaises();
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

    public function verificarrespuesta_post()
    {
	    $data = $this->evento_model->verificarRespuesta($this->post());

		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {	        
            $this->response([
                'status' => true,
                'message' => 'ok',
                'contacto' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

	public function ingresarrespuesta_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarRespuesta($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function login_post()
    {
	    $username = $this->input->post('user');
	    $password = $this->input->post('pass');
	    
	    if ($this->evento_model->userLogin($this->usuario->grupo, $this->usuario->id_empresa, $username, $password))
		{
			$data['contacto'] = $this->evento_model->getUserIdFromUsername($this->usuario->grupo, $this->usuario->id_empresa, $username);
	        $this->evento_model->updateLogin($data['contacto']['id']);
			//$data = (array) $this->evento_model->getContactoDetalle($id);
		}
		else
		{
			$data['error'] = 'Los datos son incorrectos.';
		}
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error'],
                'error' => $data['error']
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

    //DETALLE CONTACTO EVENTO
    public function perfil_get($id)
    {
	    $data = $this->evento_model->detalleContacto($this->usuario->grupo, $this->usuario->id_empresa, $id);
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

    //EVENTOS
    public function eventos_get($id)
    {
		$parametros['id_contacto'] = $id;
		$parametros['estado'] = 2;

	    $data = $this->evento_model->getEventos($parametros);
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

    //DETALLE EVENTO
    public function detalleevento_get($id)
    {
		$parametros['estado'] = 2;
	    $data = $this->evento_model->detalleEvento($id, $parametros);
	    
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

    //VERIFICAR CODIGO
    public function verificar_codigo_post()
    {
	    $data = $this->evento_model->verificarCodigo($this->usuario->grupo, $this->usuario->id_empresa, $this->post('codigo'), 2);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => 'error',
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response([
                'status' => true,
                'message' => 'ok',
                'evento' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

    //RELACIONAR CODIGO
    public function relacionar_codigo_post()
    {
	    $data = $this->evento_model->relacionarCodigo($this->post());
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => 'error',
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            
            $this->response([
                'status' => true,
                'message' => 'ok',
                'evento' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

    //PREGUNTAS SEGUN ID EVENTO
    public function preguntas_get($id_evento, $encuesta)
    {
		$parametros['id_evento'] = $id_evento;
		$parametros['estado'] = 2;
		$parametros['encuesta'] = $encuesta;

	    $data = $this->evento_model->getPreguntas($parametros);
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

    //RESPUESTAS SEGUN PREGUNTAS
    public function respuestas_get($id_pregunta)
    {
		$parametros['pregunta'] = $id_pregunta;
		$parametros['estado'] = 2;

	    $data = $this->evento_model->getRespuestas($parametros);
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

    //INGRESO RESPUESTAS 
    public function ingresardatos_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarDatos($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

    //INGRESO CERTIFICAR 
    public function certificar_post()
    {
		$this->db->trans_begin();

		$data = $this->evento_model->ingresarCertificar($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

	//ELIMINAR Y UNIFICAR CON LOGIN
	public function logincertificar_post()
    {
	    $username = $this->input->post('user');
	    $password = $this->input->post('pass');
	    
	    if ($this->evento_model->userLoginCertificar($this->usuario->grupo, $this->usuario->id_empresa, $username, $password))
		{
			$data['contacto'] = $this->evento_model->getUserIdFromUsername($this->usuario->grupo, $this->usuario->id_empresa, $username);
	        $this->evento_model->updateCerfificar($data['contacto']['id']);
			//$data = (array) $this->evento_model->getContactoDetalle($id);
		}
		else
		{
			$data['error'] = 'Los datos son incorrectos.';
		}
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error'],
                'error' => $data['error']
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