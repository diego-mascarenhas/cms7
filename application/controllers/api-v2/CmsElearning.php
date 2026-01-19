<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class CmsElearning extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->usuario = ($this->rest->user_id > 40000) ? $this->user_model->getUserInfo($this->rest->user_id) : $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function categorias_get($padre = null)
    {
		$this->load->model('cms-v2/elearning/categorias_model');
		$parametros['estado'] = 3;
		$parametros['padre'] = $padre;
		$data = $this->categorias_model->getCategorias($parametros);

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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

//---- COMIENZO CARRO 
    //VERIFICO EMAIL DE CONTACTO PARA LOGIN
    public function verificar_post()
    {
        $this->load->model('cms-v2/elearning/contacto_model');
	    $data = $this->contacto_model->verificarSiExiste($this->post('email'));
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
                'contacto' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

    //INGRESAR CONTACTO
    public function ingresar_contacto_post()
    {
        $this->load->model('cms-v2/elearning/contacto_model');
		$this->db->trans_begin();

		$data = $this->contacto_model->ingresarContacto($this->post());
		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

    //MODIFICAR CONTACTO
    public function modificar_contacto_post()
    {
        $this->load->model('cms-v2/elearning/contacto_model');
		$this->db->trans_begin();
		
		$data = $this->contacto_model->modificarContacto($this->post());

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

    //UPDATE VISITA
    public function update_visita_post()
    {
        $this->load->model('cms-v2/elearning/contacto_model');
		$this->db->trans_begin();

		$data = $this->contacto_model->updateUltimaVisita($this->post());
		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el contacto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

    public function tipo_contacto_get($id)
    {
        $this->load->model('cms-v2/elearning/contacto_model');
		$parametros['grupo'] = $this->usuario->grupo;
		$parametros['id_empresa'] = $this->usuario->id_empresa;
    	$data = $this->contacto_model->getTipoContacto($id, $parametros);
		
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

    public function detalle_contacto_get($id)
    {
        $this->load->model('cms-v2/elearning/contacto_model');
		$parametros['grupo'] = $this->usuario->grupo;
		$parametros['id_empresa'] = $this->usuario->id_empresa;
    	$data = $this->contacto_model->detalleContacto($id, $parametros);
		
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




	//DETALLE PEDIDO
    public function listado_pedidos_get($id, $estado = null)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
        $parametros['id_contacto'] = $id;
        $parametros['estado'] = $estado;
	    $data = $this->pedidos_model->getPedidos($parametros);

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

    public function listado_pedidos_cursos_get($id)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
        $parametros['id_contacto'] = $id;
	    $data = $this->pedidos_model->listadoPedidoItems($parametros);

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

	//DETALLE PEDIDO
    public function detalle_pedido_get($pedido, $contacto = null, $estado = null)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
        
        $parametros['id_pedido'] = $pedido;
        $parametros['id_contacto'] = $contacto;
        $parametros['estado'] = $estado;
        
	    $data = $this->pedidos_model->detallePedidoPublic($parametros);

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

	//DETALLE PEDIDO ITEMS
    public function listado_pedido_items_get($id, $estado = null)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');

        $parametros['id_pedido'] = $id;
        $parametros['pedido_estado'] = $estado;
        
	    $data = $this->pedidos_model->listadoPedidoItems($parametros);
		
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

	//INGRESAR PEDIDO ITEM
/*
	public function ingresar_pedido_post()
	{		
		$this->load->model('cms-v2/elearning/pedidos_model');
	    $data = $this->pedidos_model->ingresarPedido($this->post());
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
                'pedido' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
	  }
*/

	public function ingresar_pedido_post()
	{		
		$this->load->model('cms-v2/elearning/pedidos_model');
	    $data1 = $this->pedidos_model->verificarPedido($this->post());
	    {
		  if($data1 == 0)
		  {
		    $data = $this->pedidos_model->ingresarPedido($this->post());
			if (isset($data['error']))
	        {
	        	$data['error'] = array('error' => 1, 'tipo' => 'No se pudo ingresar el pedido.');
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
	                'pedido' => $data,
	            ], REST_Controller::HTTP_OK);
	        }
	        else
	        {
	            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
	        }
		  }
		  else
		  {
	        $data['error'] = array('error' => 2, 'tipo' => 'Ya hay un pedido ingresado con ese curso.');
	        $this->response([
                'status' => false,
                'message' => 'error',
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
		  }   
	    }
	}

    //ELIMINAR ITEM
    public function eliminar_item_post()
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->eliminarItemPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el producto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

	//LISTADO CUPONES PEDIDOS
    public function cantidad_pedido_get($id)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
	    $data = $this->pedidos_model->cantidadPedidoItems($id);

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


	//LISTADO CUPONES PEDIDOS
    public function listado_cupones_pedido_get($id)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
        $parametros['id_pedido'] = $id;
	    $data = $this->pedidos_model->listadoCuponesPedido($parametros);

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

	//DETALLE CUPON PEDIDO
    public function detalle_cupon_get($cupon, $id_pedido)
    {
	    // models
		$this->load->model('cms-v2/elearning/pedidos_model');
		$parametros['cupon'] = $cupon;
		$parametros['id_pedido'] = $id_pedido;
		$parametros['estado'] = 3;
		
		$data = $this->pedidos_model->detalleCupon($parametros);
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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//INGRESAR CUPON PEDIDO
	public function ingresar_cupon_post()
	{		
		$this->load->model('cms-v2/elearning/pedidos_model');
	    $data = $this->pedidos_model->ingresarCupon($this->post());
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
                'contacto' => $data,
            ], REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
	}

    //ELIMINAR CUPON
    public function eliminar_cupon_post()
    {
        $this->load->model('cms-v2/elearning/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->eliminarCuponPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el producto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

	public function finalizar_pedido_post()
	{		
		$this->load->model('cms-v2/elearning/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->cambiarEstadoPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function listado_mis_cursos_get($id, $id_tipo, $estado = null)
    {
        $this->load->model('cms-v2/elearning/pedidos_model');

        $parametros['id_contacto'] = $id;
        $parametros['id_tipo'] = $id_tipo;
        $parametros['estado'] = 0;
        
	    $data = $this->pedidos_model->listadoMisCursos($parametros);
		
		if (isset($data['error']))
        {
	        $data['error'] = 'No se pueden mostrar los cursos';
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

    //CONSULTAS
    public function listado_consultas_get($id, $contacto = null)
    {
        $this->load->model('landing_model');
        $parametros['id_contacto'] = $contacto;
		$data = $this->landing_model->getLandingConversiones($id, $parametros);
		
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


	//LISTADO CURSOS
    public function listado_cursos_get($categoria = null, $idioma)
    {
		$this->load->model('cms-v2/elearning/elearning_model');
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		
		$data = $this->elearning_model->getCursos($parametros);

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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//DETALLE CURSOS
    public function detalle_curso_get($id_curso, $idioma = null, $id_tipo = null, $id_pedido = null)
    {
	    // models
		$this->load->model('cms-v2/elearning/elearning_model');
		$parametros['estado'] = 3;
		$parametros['id_pedido'] = $id_pedido;
		$parametros['id_tipo'] = 2;
		
		$data = $this->elearning_model->getCursoDetalleIdioma($id_curso,$idioma,$parametros);
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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function verificar_certificado_get($id_contacto, $id_producto)
    {
		$this->load->model('cms-v2/elearning/pedidos_model');
		$parametros['id_contacto'] = $id_contacto;
		$parametros['id_producto'] = $id_producto;

		$data = $this->pedidos_model->verificarCertificado($parametros);
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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function archivo_get($id, $idioma)
    {
		$this->load->model('cms-v2/elearning/elearning_model');
		$data = $this->elearning_model->getArchivo($id, $idioma);

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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function cursos_profesores_get($id, $id_tipo, $idioma)
    {
		$this->load->model('cms-v2/elearning/elearning_model');
		$parametros['id'] = $id;
		$parametros['id_tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;

		$data = $this->elearning_model->getProfesoresCursos($parametros);
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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//REGISTRAR INGRESO A VIDEO
	public function registrar_ingreso_video_post()
	{
		$this->load->model('cms-v2/elearning/pedidos_model');
		$this->db->trans_begin();
		
		$data = $this->pedidos_model->registrarIngresoVideo($this->post());
		
		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$this->response([
				'status' => false,
				'message' => 'Error al registrar ingreso a video'
			], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
		}
		else
		{
			$this->db->trans_commit();
			if (isset($data['error']))
			{
				$this->response([
					'status' => false,
					'message' => $data['error']
				], REST_Controller::HTTP_BAD_REQUEST);
			}
			else
			{
				$this->response([
					'status' => true,
					'message' => 'Ingreso a video registrado correctamente',
					'data' => $data
				], REST_Controller::HTTP_OK);
			}
		}
	}

	public function certificar_post()
	{		
		// models
		$this->load->model('cms-v2/elearning/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarCertificado($this->post());

		// Actualizar fecha_completo_encuesta cuando se certifica
		if ($data && !isset($data['error']))
		{
			$variables_post = $this->post();
			if (isset($variables_post['id_tipo']) && $variables_post['id_tipo'] == 2)
			{
				// Para tipo 2 (empresa), actualizar en con_rel_pedido_contactos
				$sql = "UPDATE con_rel_pedido_contactos SET fecha_completo_encuesta = NOW() ";
				$sql .= "WHERE id_contacto = ? AND id_producto = ? AND fecha_completo_encuesta IS NULL";
				$this->db->query($sql, array($variables_post['id_contacto'], $variables_post['id_producto']));
			}
		}

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function listado_eventos_get($idioma, $filtro1=null, $filtro2=null)
    {
        $this->load->model('cms-v2/elearning/eventos_model');

        $parametros['idioma'] = $idioma;
        $parametros['filtro1'] = $filtro1;
        $parametros['estado'] = 3;

		    $data = $this->eventos_model->getEventosPublic($parametros);
		
		if (isset($data['error']))
        {
        	$data['error'] = array('error' => 1, 'tipo' => 'No se pudo traer el dato.');
	        $this->response([
                'status' => false,
                'message' => 'error',
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
   //---- FIN CARRO



	public function finalizarpedido_post()
	{		
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->finalizarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function finalizarpedidobonificado_post()
	{		
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->finalizarPedidoBonificado($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function pedidobenid_get($id = null)
    {
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->PedidoBeneficiarioId($id);
		
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

       
    public function detalleitemcompra_get($id = null)
    {
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detalleItemCompra($id);
		
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

    public function listadopedidos_get($id = null)
    {
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->listadoPedidos($id);
		
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

    public function totalpedido_get($id = null)
    {
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->totalPedido($id);
		
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

	public function ingresaritem_post()
	{		
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarItem($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function detallecupon_get($id = NULL)
	{		
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detalleCupon($id);
		
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

	public function ingresarcupon_post()
	{		
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarCupon($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el cupón, por favor intenta más tarde.';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}


	//CURSOS DESTACADOS
    public function cursosdestacados_get($limit, $idioma)
    {
		$this->load->model('cms-v2/elearning/elearning_model');
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		$parametros['limit'] = $limit;

		$data = $this->elearning_model->getCursosDestacados($parametros);
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
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}