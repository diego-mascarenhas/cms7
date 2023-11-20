<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class CmsCarrito extends REST_Controller {

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

    //LISTADO PRODUCTOS
    public function productos_get($categoria, $padre)
    {
        //Cargo el modelo
        $this->load->model('cms-v2/carrito/productos_model');
        
        if($categoria > 0) { $parametros['id_categoria'] = $categoria; }
        if($padre > 0) { $parametros['padre'] = $padre; }
        $parametros['estado'] = 2;
        $parametros['id_empresa'] = $this->usuario->id_empresa;
        
	    $data = $this->productos_model->getProductos($parametros);
	    
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

    public function destacados_get($categoria, $padre)
    {
        //Cargo el modelo
        $this->load->model('cms-v2/carrito/productos_model');
        
        if($categoria > 0) { $parametros['id_categoria'] = $categoria; }
        if($padre > 0) { $parametros['padre'] = $padre; }
        $parametros['estado'] = 2;
        $parametros['id_empresa'] = $this->usuario->id_empresa;
        
	    $data = $this->productos_model->getProductosDestacados($parametros);
	    
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

    //LISTADO PRODUCTOS OFERTAS
    public function ofertas_get($categoria, $padre, $destacado)
    {
        //Cargo el modelo
        $this->load->model('cms-v2/carrito/productos_model');
        
        if($categoria > 0) { $parametros['id_categoria'] = $categoria; }
        if($padre > 0) { $parametros['padre'] = $padre; }
        if($destacado > 0) { $parametros['destacado'] = 1; }
        $parametros['estado'] = 2;
        $parametros['id_empresa'] = $this->usuario->id_empresa;
        
	    $data = $this->productos_model->getProductosOferta($parametros);
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
	
    //ORDENAR PRODUCTOS
    public function productos_ordenar_get($categoria, $padre, $orden)
    {
        //Cargo el modelo
        $this->load->model('cms-v2/carrito/productos_model');
        
        if($categoria > 0) { $parametros['id_categoria'] = $categoria; }
        if($padre > 0) { $parametros['padre'] = $padre; }
        $parametros['orden'] = $orden;
        $parametros['estado'] = 2;
        $parametros['id_empresa'] = $this->usuario->id_empresa;
        
	    $data = $this->productos_model->getProductos($parametros);
	    
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

    //DETALLE PRODUCTOS
    public function producto_detalle_get($categoria, $producto)
    {
        //Cargo el modelo
        $this->load->model('cms-v2/carrito/productos_model');
        
        $parametros['id_categoria'] = $categoria;
        $parametros['id'] = $producto;
        $parametros['estado'] = 2;
        $parametros['idioma'] = 'es';
        
	    $data = $this->productos_model->detalleProducto($parametros);
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

    public function productos_buscar_post()
    {
	    // models
        $this->load->model('cms-v2/carrito/productos_model');
		$parametros['busqueda'] = $this->post('busqueda');

    	$data = $this->productos_model->getBusqueda($parametros);
		
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

    //VERIFICO EMAIL DE CONTACTO
    public function verificar_post()
    {
        $this->load->model('cms-v2/carrito/contacto_model');

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
        $this->load->model('cms-v2/carrito/contacto_model');
	    /* $this->load->model('contacto_model'); */
		$this->db->trans_begin();

		$data = $this->contacto_model->ingresarContacto($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el contacto, por favor intenta más tarde';
		}
		else
		{
			$valores['username'] = $this->usuario->id_empresa.$data;
			$modificar = $this->contacto_model->modificarContacto($data, $valores);
			$this->db->trans_commit();
		}
		$this->response($data);
    }

    //INGRESAR PEDIDO
    public function ingresar_pedido_post()
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->ingresarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
		}
		else
		{
			$this->db->trans_commit();
			$this->response($data, REST_Controller::HTTP_OK);
		}
    }

    //INGRESAR ITEM
    public function ingresar_item_post()
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->ingresarItemPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
			$this->response($data, REST_Controller::HTTP_OK);
		}
/* 		$this->response($data); */
    }

    //MODIFICAR PEDIDO
    public function modificar_pedido_post()
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->modificarPedido($this->post('id'), $this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
		}
		else
		{
			$this->db->trans_commit();
			$this->response($data, REST_Controller::HTTP_OK);
		}
/* 		$this->response($data); */
    }

	//DETALLE PEDIDO
    public function detalle_pedido_get($pedido, $estado)
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
        
        $parametros['id_pedido'] = $pedido;
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

	//DETALLE ITEM DE PEDIDO
    public function detalle_item_pedido_get($pedido, $producto)
    {
        $this->load->model('cms-v2/carrito/pedidos_model');

        $parametros['id_pedido'] = $pedido;
        $parametros['id'] = $producto;
        $parametros['estado'] = 2;
        
	    $data = $this->pedidos_model->detalleItemPedido($parametros);
		
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
    public function listado_pedido_items_get($id)
    {
        $this->load->model('cms-v2/carrito/pedidos_model');

        $parametros['id_pedido'] = $id;
        $parametros['estado'] = 2;
        
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

    //MODIFICAR ITEM
    public function modificar_item_post()
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
		$this->db->trans_begin();
	    $data = $this->pedidos_model->modificarItemPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo modificar el producto, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
    }

    //ELIMINAR ITEM
    public function eliminar_item_post()
    {
        $this->load->model('cms-v2/carrito/pedidos_model');
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


	//CANCELAR PEDIDO
    public function cancelar_pedido_get($id)
    {
        $this->load->model('cms-v2/carrito/pedidos_model');

	    $data = $this->pedidos_model->cancelarPedido($id);
		
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