<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class CmsTienda extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        
        $this->usuario = ($this->rest->user_id > 40000) ? $this->user_model->getUserInfo($this->rest->user_id) : $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    //Configuración
    public function configuracion_get($nombre = null)
    {
	    // models
        $this->load->model('tienda_model');
        
	    if ($nombre)
		{
	        if ($nombre == null)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->tienda_model->detalleConfiguracion($nombre);
			}
		}
		else
		{
			$data = null;
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
         
    //Configuración por DOMINIO
    public function verificartienda_get($nombre)
    {
	    // models
        $this->load->model('tienda_model');
        
	    if ($nombre)
		{
	        if ($nombre == null)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->tienda_model->getTiendaDominio($nombre);
			}
		}
		else
		{
			$data = null;
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

    //VERIFICAR EMAIL CLIENTE TIENDA PRIVADA
    public function verificar_contacto_post()
    {
	    $valores['email'] = $this->input->post('email');
	    $valores['empresa'] = $this->input->post('empresa');
	    
        $this->load->model('tienda_model');
	    $data = $this->tienda_model->verificarContacto($valores);
		
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

    //VERIFICAR CONTACTO
    public function verificar_post()
    {
        $this->load->model('tienda_model');
        $grupo = 513;
        
	    $data = $this->tienda_model->verificarSiExiste($grupo, $this->post('username'));
		
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
        
    //MODIFICAR CONTACTO
 	public function modificar_contacto_post()
    {
 		$this->load->model('contacto_model');
 		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$id = $this->post('contacto');
		
	    $data = $this->contacto_model->modificarContacto($id, $this->post());
	    $data2 = $this->tienda_model->modificarContactoAdicionales($id, $this->post());

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

    //DETALLE CONTACTO
    public function contacto_detalle_get($id)
    {
 		$this->load->model('contacto_model');
        
	    $parametros['modo'] = 'raw';
	    $data = $this->contacto_model->getContactoDetalle($id, $parametros);
		
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

    //DETALLE CONTACTO
    public function contacto_detalle_adicionales_get($id)
    {
        $this->load->model('tienda_model');
        
	    $data = $this->tienda_model->getContactoAdicionales($id);
		
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

    //LISTADO PEDIDOS
    public function listado_pedidos_get($tienda, $id)
    {
	    $parametros['id_tienda'] = $tienda;
	    $parametros['id_contacto'] = $id;
	    
        $this->load->model('tienda_model');
        
		$data = $this->tienda_model->getPedidos($parametros);
		
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

   //DATOS MP
    public function datosmp_get($id_pedido)
    {
	    // models
        $this->load->model('tienda_model');
        
	    if ($id_pedido)
		{
	        if ($id_pedido == null)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->tienda_model->datosMP($id_pedido);
			}
		}
		else
		{
			$data = null;
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

	//LISTADO CATEGORIAS
    public function listadocategoriasmenu_get($id = null)
    {
		if(isset($id))
	    {
	    	// models
	    	$menu = 2;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getCategoriasPublic($id, 3, $menu);
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
    
    public function listadocategorias_get($id = null)
    {
		if(isset($id))
	    {
	    	// models
	    	$menu = 1;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getCategoriasPublic($id, 3,$menu);
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

	//LISTADO PRODUCTOS MENU
    public function listadoproductosmenu_get($tienda, $id_categoria)
    {
		if(isset($tienda))
	    {
	    	// models
	    	$menu = 1;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getProductosPublic($tienda, $id_categoria, $menu);
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

	//LISTADO PRODUCTOS
    public function listadoproductos_get($tienda, $id_categoria)
    {
		if(isset($tienda))
	    {
	    	// models
	    	$menu = 2;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getProductosPublic($tienda, $id_categoria, $menu);
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
    
	//LISTADO PRODUCTOS
    public function listadoproductospedido_get($tienda, $id_categoria, $id_pedido)
    {
		if(isset($tienda))
	    {
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getProductosPublicPedido($tienda, $id_categoria, $id_pedido);
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

	//LISTADO FOTOS
    public function listadofotos_get($id)
    {
		if(isset($id))
	    {
	    	// models
	    	$this->load->model('tienda_model');
			$proyecto['proyecto'] = $this->tienda_model->getProyectoFromProducto($id);
			$parametros['proyecto'] = $proyecto['proyecto']['id'];
			$parametros['estado'] = 3;
			$data['medias'] = $this->tienda_model->getMedias($parametros);
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

	//LISTADO PRODUCTOS DESTACADOS
    public function listadodestacados_get($tienda)
    {
		if(isset($tienda))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getDestacadosPublic($tienda);
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

	//LISTADO GRUPOS DE OPCIONES
    public function listadogrupos_get($tienda, $producto)
    {
		if(isset($tienda))
	    {
			$parametros['tienda'] = $tienda;
			$parametros['producto'] = $producto;
			$parametros['public'] = 1;
			$parametros['estado'] = 1;

	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getGrupos($parametros);
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

	//LISTADO OPCIONES
    public function listadoopciones_get($tienda, $grupo)
    {
		if(isset($tienda))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->getOpcionesPublic($tienda, $grupo);
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

   //BUSQUEDA
    public function buscar_post()
    {
	    // models
	    $this->load->model('tienda_model');
		$parametros['estado'] = 3;
		$parametros['ubicacion'] = $this->input->post('ubicacion');
		$parametros['categoria'] = $this->input->post('categoria');
		$parametros['idioma'] = $this->input->post('idioma');
		$parametros['producto'] = $this->input->post('producto');
		$parametros['tipo'] = $this->input->post('tipo');

    	$data = $this->tienda_model->getBusqueda($parametros);
		
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

	//DETALLE PAIS
    public function pais_get($pais)
    {
		if(isset($pais))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->detallePais($pais);
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

	//LISTADO RUBROS DE TIENDAS
    public function listadorubros_get($idioma = null)
    {
		if(isset($idioma))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->listadoRubros($idioma);
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

	//LISTADO PAISES
    public function listadopaises_get()
    {
    	// models
    	$this->load->model('tienda_model');
    	$data = $this->tienda_model->listadoPaises();
		
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
    
	//LISTADO PROVINCIAS DE TIENDAS POR EL PAIS
    public function listadoprovincias_get($pais)
    {
		if(isset($pais))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->listadoProvincias($pais);
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

	//LISTADO LOCALIDADES DE TIENDAS POR EL PAIS
    public function listadolocalidades_get($pais)
    {
		if(isset($pais))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->listadoLocalidades($pais);
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

	//LISTADO PLANES POR PAIS
    public function listadoplanes_get($pais)
    {
		if(isset($pais))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->listadoPlanes($pais);
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

	//LISTADO PLANES
    public function listadocuponespedido_get($id_pedido)
    {
    	// models
    	$this->load->model('tienda_model');
    	$data = $this->tienda_model->listadoCuponesPedido($id_pedido);
		
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

	//LISTADO SUCURSALES
    public function listadosucursales_get($id_tienda)
    {
    	// models
    	$this->load->model('tienda_model');
    	$data = $this->tienda_model->listadoSucursales($id_tienda);
		
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

	//LISTADO FORMAS DE PAGO
    public function listadoformaspagos_get($id_tienda)
    {
    	// models
    	$this->load->model('tienda_model');
    	$data = $this->tienda_model->listadoFormasPago($id_tienda);
		
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

	//LISTADO FORMAS DE ENTREGA
    public function listadoentregas_get($id_tienda)
    {
    	// models
    	$this->load->model('tienda_model');
    	$data = $this->tienda_model->listadoEnviosTienda($id_tienda);
		
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

	//DETALLE PRODUCTO MENU
    public function detalleproductomenu_get($id)
    {
		if(isset($id))
	    {
	    	// models
	    	$menu = 1;
	    	$estado = 3;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->detalleProducto($id, $estado, $menu);
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
	//DETALLE PRODUCTO
    public function detalleproducto_get($id)
    {
		if(isset($id))
	    {
	    	// models
	    	$menu = 2;
	    	$estado = 3;
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->detalleProducto($id, $estado, $menu);
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

	//DETALLE CONTACTO
    public function detallecontacto_get($username)
    {
		if(isset($username))
	    {
	    	// models
	    	$this->load->model('tienda_model');
	    	$data = $this->tienda_model->detalleContactoFromUsername($username);
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

	//DETALLE PEDIDO
    public function detallepedido_get($id, $estado, $id_contacto = null)
    {
		$this->load->model('tienda_model');
		$parametros['id'] = $id;
		if($estado > 0) { $parametros['estado'] = $estado; } else { $parametros['estado'] = 1; } 
		if($id_contacto != null) { $parametros['id_contacto'] = $id_contacto; }
		
		$data = $this->tienda_model->detallePedidoPublic($parametros);
		
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

	//DETALLE PEDIDO PAYPAL
    public function detallepedidopaypal_get($id)
    {
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detallePedidoPaypal($id);
		
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

	//DETALLE FINALIZAR PEDIDO
    public function detallefinalizarpedido_get($id, $estado, $tienda = null)
    {
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detalleFinalizarPedidoPublic($id,$estado);
		
		if($estado == 8)
		{
			$pedido = json_encode($data);
			json_encode($data);
			$insert = $this->tienda_model->ingresarDataPedido($id, $tienda, $pedido);		
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

	//DETALLE PEDIDO COOKIE
    public function detallepedidocookie_get($cookie, $id_tienda)
    {
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detallePedidoCookie($cookie, $id_tienda);
		
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
    public function detallepedidoitems_get($id = null)
    {
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detallePedidoItems($id);
		
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

	//DETALLE PEDIDO ITEMS MENSAJE
    public function detallepedidoitemsmensaje_get($id, $menu)
    {
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detallePedidoItemsPublic($id, $menu);
		
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

	//DETALLE CUPON
	public function detallecupon_get($cupon, $id_tienda)
	{		
		$this->load->model('tienda_model');
		$data = $this->tienda_model->detalleCupon($cupon, $id_tienda);
		
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

	//INGRESAR TIENDA
	public function ingresartienda_post()
	{
		$this->load->model('tienda_model');
		$this->load->library('form_validation');
		
		$post = $this->post();
		$post['tienda'] = strtolower(preg_replace('/\s+/', '', $this->post('tienda')));
		
		$this->form_validation->set_data($post);

		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		$this->form_validation->set_rules('tienda', 'Tienda', 'trim|min_length[3]|is_unique[contactos.username]', array('required' => 'Debe ingresar un nombre de tienda.', 'min_length' => 'Debe ingresar una tienda de al menos 3 caracteres.', 'is_unique' => 'El nombre de tienda ya está registrado. Ingrese uno diferente.'));
		$this->form_validation->set_rules('celular', 'Celular', 'trim|required');
		$this->form_validation->set_rules('pais', 'País', 'required');
		$this->form_validation->set_rules('categoria', 'Plan', 'required');
		$this->form_validation->set_rules('rubro', 'Rubro', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$post = $this->post();
			$post['tienda'] = strtolower(preg_replace('/\s+/', '', $this->post('tienda')));
		
			$data = $this->tienda_model->ingresarTienda($post);

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear la tienda, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
			}
		}
		
		$this->response($data);
	}
	
	//INGRESAR PEDIDO MASIVO
	public function compramasiva_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->compraMasiva($this->post());

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

	//INGRESAR PEDIDO
	public function ingresarpedido_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->ingresarPedido($this->post());

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

	//INGRESAR ITEM
	public function ingresaritem_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->ingresarItem($this->post());

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

	//INGRESAR CUPON
	public function ingresarcupon_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->ingresarCupon($this->post());

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

	//DUPLICAR PEDIDO
	public function duplicar_pedido_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->duplicarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el cup&oacute;n, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	//ELIMINAR PEDIDO
	public function eliminarpedido_post()
	{		
		// models
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->eliminarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el cup&oacute;n, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	//ELIMINAR ITEM
	public function eliminaritem_post()
	{		
		// models
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->eliminarItem($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el &iacute;tem, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	//ELIMINAR CUPON
	public function eliminarcupon_post()
	{		
		// models
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->eliminarCupon($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el cup&oacute;n, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function finalizarpedido_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->finalizarPedido($this->post());

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

	public function finalizarpedidomp_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->finalizarPedidoMP($this->post());

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
 
	public function finalizarpedidopaypal_post()
	{		
		$this->load->model('tienda_model');
		$this->db->trans_begin();
		$data = $this->tienda_model->finalizarPedidoPayPal($this->post());

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

   //BUSQUEDA EN TIENDA
    public function buscar_en_tienda_post()
    {
	    // models
	    $this->load->model('tienda_model');
		$parametros['estado'] = 3;
		$parametros['tienda'] = $this->input->post('tienda');
		$parametros['busqueda'] = $this->input->post('busqueda');

    	$data = $this->tienda_model->getBusquedaTienda($parametros);
		
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

	//TIENDAS MODELOS
	public function listadotiendas_get($id_tipo)
	{		
		$this->load->model('tienda_model');
		$parametros['id_tipo'] = $id_tipo;
		$parametros['estado'] = 3;
		$data = $this->tienda_model->getTiendasPublic($parametros);
		
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