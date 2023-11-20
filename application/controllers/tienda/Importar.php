<?php defined('BASEPATH') or exit('No direct script access allowed');


class Importar extends MY_Controller {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        if (!$this->is_logged_in('reseller') || $this->usuario->grupo <= 513)
		{
			redirect(base_url('user/login'));
		}
    }
    
    
    public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			//models
			$this->load->model('tienda_importacion_model');
			
			
			$data = $this->tienda_importacion_model->getTiendasRemota();
			
			echo '<pre>' . print_r($data, true) . '</pre>';
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function tienda($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			$this->load->model('empresa_model');
			$this->load->model('servicio_model');
			$this->load->model('contacto_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getTiendaDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			
/*
			[id] => 8
		    [horario] => De 12 a 15hs y de 20 a 20:30hs
		    [direccion] => Calle
		    [numero] => 1256
		    [envio] => 60
		    [permiteenvio] => 1
		    [aconvenir] => 0
		    [telefono] => 4214386
		    [whatsapp] => 543413661548
		    [sucursal1] => 
		    [whatsapp1] => 
		    [sucursal2] => 
		    [whatsapp2] => 
		    [sucursal3] => 
		    [whatsapp3] => 
		    [negocio] => Negocio Ejemplo
		    [nombreCorto] => ejemplo
		    [efectivo] => 1
		    [debito] => 1
		    [retiro] => 1
		    [descuentoRetiro] => 10
		    [qr] => 1
		    [linkDePago] => 0
		    [clienteMP] => 4903997262534693
		    [claveMP] => cE8CFtTbvugJyTpssOXrvtK2EBElyDiz
		    [email] => contacto@pedimosfacil.com
		    [localidad] => Rosario
		    [provincia] => Santa Fe
		    [pais] => 
		    [rubro] => cervecerias
		    [estado] => 3
		    [observaciones] => EnvÃ­os dentro de Pellegrini, el RÃ­o y OroÃ±o
*/

			// db CMS
			$this->db = $this->load->database('default', true);
			
			
			// Empresa
			$empresa['telefono'] = $data['telefono'];
			$empresa['empresa'] = utf8_decode($data['negocio']);
			$empresa['email'] = (!empty($data['email'])) ? $data['email'] : 'contacto@pedimosfacil.com';
			
			if (!$tienda['id_empresa'] = $this->empresa_model->verificarSiExiste($empresa['empresa']))
			{
				$empresa['referido'] = 7459;
			
				$tienda['id_empresa'] = $this->empresa_model->ingresarEmpresa($empresa);
				
				// Servicio
				$servicio['id_empresa'] = $tienda['id_empresa'];
				$servicio['id_categoria'] = 2102;
				$servicio['descuento'] = 100.00;
				$servicio['username'] = 43376;
				
				$tienda['id_servicio'] = $this->servicio_model->ingresarServicio($servicio);
			}
			else
			{
				// Usuario
				$usuario['id_empresa'] = $tienda['id_empresa'];
				$usuario['nombre'] = $data['nombre'];
				$usuario['username'] = $data['nombre'];
				$usuario['email'] = (!empty($data['email'])) ? $data['email'] : 'contacto@pedimosfacil.com';
				$usuario['area_privada'] = 3;
				$usuario['estado'] = 2;
				
				echo '<pre>' . print_r($usuario, true) . '</pre>';
				
				
				if (!$this->contacto_model->verificarkUsername($usuario['username']))
				{
					$empresa['id_contacto'] = $this->contacto_model->ingresarContacto($usuario);
				}
				
				$this->empresa_model->modificarEmpresa($tienda['id_empresa'], $empresa);
			}


			// Tienda
			$tienda['id'] = $data['id'];
			$tienda['email'] = (!empty($data['email'])) ? $data['email'] : 'contacto@pedimosfacil.com';
			$tienda['telefono'] = $data['telefono'];
			$tienda['celular'] = $data['whatsapp'];
			$tienda['titulo'] = $data['nombreCorto'];
			
			$tienda['domicilio'] = utf8_decode($data['direccion']);
			$tienda['numero'] = $data['numero'];
			$tienda['localidad'] = utf8_decode($data['localidad']);
			$tienda['provincia'] = utf8_decode($data['provincia']);
			$tienda['pais'] = utf8_decode($data['pais']);
			
			$tienda['clienteMP'] = $data['clienteMP'];
			$tienda['claveMP'] = $data['claveMP']; // Tiene que estar encriptada
			
			switch ($data['rubro'])
			{
				case 'restaurantes':
					$tienda['id_rubro'] = 1;
					break;
				case 'cervecerias':
					$tienda['id_rubro'] = 2;
					break;
				case 'mercados':
					$tienda['id_rubro'] = 3;
					break;
				case 'bebidas':
					$tienda['id_rubro'] = 4;
					break;
				case 'farmacias':
					$tienda['id_rubro'] = 5;
					break;
				case 'mascotas':
					$tienda['id_rubro'] = 6;
					break;
				case 'tiendas':
					$tienda['id_rubro'] = 7;
					break;
				case 'belleza':
					$tienda['id_rubro'] = 8;
					break;
				case 'hogar':
					$tienda['id_rubro'] = 9;
					break;
				case 'carnes':
					$tienda['id_rubro'] = 10;
					break;
				case 'bebes':
					$tienda['id_rubro'] = 11;
					break;
				case 'kioskos':
					$tienda['id_rubro'] = 12;
					break;
				case 'electronica':
					$tienda['id_rubro'] = 13;
					break;
				default:
					$tienda['id_rubro'] = 14;
					break;
			}
			
			echo '<pre>' . print_r($tienda, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExiste($data['id']))
			{
				$this->tienda_importacion_model->ingresarTienda($tienda);
			}
			else
			{
				$this->tienda_importacion_model->modificarTienda($id, $tienda);
			}
			
			
			// Formas de pago
			if ($data['efectivo'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDePago($data['id'], 1)) $this->tienda_importacion_model->ingresarFormaDePago(array('id_tienda'=>$data['id'], 'id_forma_pago'=>1));

			if ($data['debito'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDePago($data['id'], 2)) $this->tienda_importacion_model->ingresarFormaDePago(array('id_tienda'=>$data['id'], 'id_forma_pago'=>2));

			if ($data['qr'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDePago($data['id'], 3)) $this->tienda_importacion_model->ingresarFormaDePago(array('id_tienda'=>$data['id'], 'id_forma_pago'=>3));

			if ($data['clienteMP'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDePago($data['id'], 4)) $this->tienda_importacion_model->ingresarFormaDePago(array('id_tienda'=>$data['id'], 'id_forma_pago'=>4));

			if ($data['linkDePago'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDePago($data['id'], 5)) $this->tienda_importacion_model->ingresarFormaDePago(array('id_tienda'=>$data['id'], 'id_forma_pago'=>5));
			
			
			// Sucursales
			$sucursal['id_tienda'] = $data['id'];
			
			$sucursal['titulo'] = 'Casa central';
			$sucursal['contenido1'] = $data['horario'];
			
			$sucursal['domicilio'] = (!empty($data['direccion'])) ? utf8_decode($data['direccion']) : null;
			$sucursal['numero'] = $data['numero'];
			$sucursal['localidad'] = utf8_decode($data['localidad']);
			$sucursal['provincia'] = utf8_decode($data['provincia']);
			
			switch ($tienda['pais'])
			{
				case (preg_match('/Uruguay/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 2;
					break;
				case (preg_match('/Bolivia/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 3;
					break;
				case (preg_match('/Paraguay/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 4;
					break;
				case (preg_match('/Perú/', $tienda['pais']) ? true : false):
				case (preg_match('/PerÃÂº/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 5;
					break;
				case (preg_match('/Ecuador/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 6;
					break;
				case (preg_match('/Chile/', $tienda['pais']) ? true : false):
				case (preg_match('/Chule/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 7;
					break;
				case (preg_match('/Colombia/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 8;
					break;
				case 'México':
				case 'MÃÂ©xico':
				case (preg_match('/México/', $tienda['pais']) ? true : false):
				case (preg_match('/MÃÂ©xico/', $tienda['pais']) ? true : false):
					$sucursal['pais'] = 9;
					break;
				default:
					$sucursal['pais'] = 1;
					break;
			}
			
			$sucursal['envio'] = $data['permiteenvio'];
			$sucursal['costo_envio'] = $data['envio'];
			$sucursal['recibir_email'] = $data['nombreCorto'];
			$sucursal['email'] = (!empty($data['email'])) ? $data['email'] : 'contacto@pedimosfacil.com';
			
			$sucursal['telefono'] = $data['telefono'];
			$sucursal['celular'] = $data['whatsapp'];
			
			$sucursal['estado'] = 2;
			
			$sucursal['orden'] = 1;
			
			if (!$existe1 = $this->tienda_importacion_model->verificarSiExisteSucursal($data['id'], 1))
			{
				$this->tienda_importacion_model->ingresarSucursal($sucursal);
			}
			else
			{
				$this->tienda_importacion_model->modificarSucursal($existe1, $sucursal);
			}
			
			if (!empty($data['sucursal1']))
			{
				$sucursal['titulo'] = utf8_decode($data['sucursal1']);
				$sucursal['contenido1'] = $data['horario'];
				$tienda['celular'] = $data['whatsapp1'];
			
				$sucursal['domicilio'] = null;
				$sucursal['orden'] = 2;
				
				if (!$existe2 = $this->tienda_importacion_model->verificarSiExisteSucursal($data['id'], 2))
				{
					$this->tienda_importacion_model->ingresarSucursal($sucursal);
				}
				else
				{
					$this->tienda_importacion_model->modificarSucursal($existe2, $sucursal);
				}
				
			}
			
			if (!empty($data['sucursal2']))
			{
				$sucursal['titulo'] = utf8_decode($data['sucursal2']);
				$sucursal['contenido1'] = $data['horario'];
				$tienda['celular'] = $data['whatsapp2'];
			
				$sucursal['domicilio'] = null;
				$sucursal['orden'] = 3;
				
				if (!$existe3 = $this->tienda_importacion_model->verificarSiExisteSucursal($data['id'], 3))
				{
					$this->tienda_importacion_model->ingresarSucursal($sucursal);
				}
				else
				{
					$this->tienda_importacion_model->modificarSucursal($existe3, $sucursal);
				}
				
			}
			
			if (!empty($data['sucursal3']))
			{
				$sucursal['titulo'] = utf8_decode($data['sucursal3']);
				$sucursal['contenido1'] = $data['horario'];
				$tienda['celular'] = $data['whatsapp3'];
			
				$sucursal['domicilio'] = null;
				$sucursal['orden'] = 4;
				
				if (!$existe4 = $this->tienda_importacion_model->verificarSiExisteSucursal($data['id'], 4))
				{
					$this->tienda_importacion_model->ingresarSucursal($sucursal);
				}
				else
				{
					$this->tienda_importacion_model->modificarSucursal($existe4, $sucursal);
				}
				
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function categoria($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getCategoriaDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Categoria
			$categoria['id'] = $data['id'];
			$categoria['id_tienda'] = $data['id_negocio'];
			$categoria['categoria'] = utf8_decode($data['nombre']);
			
			$categoria['orden'] = $data['orden'];
			$categoria['delivery'] = $data['delivery'];
			$categoria['estado'] = ($data['activo'] > 0) ? 3 : 1;
			
			echo '<pre>' . print_r($categoria, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExisteCategoria($data['id']))
			{
				$this->tienda_importacion_model->ingresarCategoria($categoria);
			}
			else
			{
				$this->tienda_importacion_model->modificarCategoria($id, $categoria);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function producto($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getProductoDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Producto
			$producto['id'] = $data['id'];
			$producto['id_tienda'] = $data['id_negocio'];
			$producto['id_categoria'] = $data['categoria'];
			$producto['titulo'] = utf8_decode($data['name']);
			$producto['contenido1'] = utf8_decode($data['descripcion']);
			
			if ($data['precioOferta'] > 0 && $data['precioOferta'] > $data['price'])
			{
				$producto['precio'] = $data['precioOferta'];
				$producto['precio_oferta'] = $data['price'];
			}
			elseif ($data['price'] > 0)
			{
				$producto['precio'] = $data['price'];
			}
			else
			{
				$data['activo'] = 0;
			}
			
			if ($data['precioSalon'] > 0) $producto['precio_local'] = $data['precioSalon'];
			
			$producto['orden'] = $data['orden'];
			$producto['destacado'] = $data['destacado'];
			$producto['estado'] = ($data['activo'] == 1) ? 3 : 1;
			
			echo '<pre>' . print_r($producto, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExisteProducto($data['id']))
			{
				$this->tienda_importacion_model->ingresarProducto($producto);
			}
			else
			{
				$this->tienda_importacion_model->modificarProducto($id, $producto);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function opciones_grupo($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getOpcionGrupoDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Opciones Grupo
			$opciones_grupos['id'] = $data['id'];
			$opciones_grupos['id_tienda'] = $data['id_negocio'];
			$opciones_grupos['opcion_grupo'] = utf8_decode($data['nombre']);
			
			$opciones_grupos['cantidad'] = $data['cantidad'];
			$opciones_grupos['estado'] = ($data['activo'] == 1) ? 2 : 1;
			
			echo '<pre>' . print_r($opciones_grupos, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExisteOpcionGrupo($data['id']))
			{
				$this->tienda_importacion_model->ingresarOpcionGrupo($opciones_grupos);
			}
			else
			{
				$this->tienda_importacion_model->modificarOpcionGrupo($id, $opciones_grupos);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function opcion($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getOpcionDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Opción
			$opcion['id'] = $data['id'];
			$opcion['id_tienda'] = $data['id_negocio'];
			$opcion['opcion'] = utf8_decode($data['nombre']);
			
			$opcion['id_opcion_grupo'] = $data['id_grupo'];
			
			$opcion['precio'] = $data['precio'];
			$opcion['estado'] = ($data['activo'] == 1) ? 2 : 1;
			
			echo '<pre>' . print_r($opcion, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExisteOpcion($data['id']))
			{
				$this->tienda_importacion_model->ingresarOpcion($opcion);
			}
			else
			{
				$this->tienda_importacion_model->modificarOpcion($id, $opcion);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function producto_rel_opciones_grupo($id_producto, $id_opciones_grupo)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getProductoRelOpcionGrupoDetalleRemota($id_producto, $id_opciones_grupo);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Producto Rel Opciones Grupo
			$producto_rel_opcion_grupo['id_tienda'] = $data['id_negocio'];
			$producto_rel_opcion_grupo['id_producto'] = $data['id_producto'];
			$producto_rel_opcion_grupo['id_opcion_grupo'] = $data['id_grupo'];
			
			echo '<pre>' . print_r($producto_rel_opcion_grupo, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExisteProdcutoRelOpcionGrupo($producto_rel_opcion_grupo['id_producto'], $producto_rel_opcion_grupo['id_opcion_grupo']))
			{
				$this->tienda_importacion_model->ingresarProductoRelOpcionGrupo($producto_rel_opcion_grupo);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function pedido($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getPedidoDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);


			// Pedido
			$pedido['id'] = $data['id'];
			$pedido['id_tienda'] = $data['id_negocio'];
			$pedido['id_sucursal'] = ($data['id_sucursal'] > 0) ? $data['id_sucursal'] : 1;
			
			$pedido['celular'] = $data['telefono'];
			$pedido['email'] = $data['client_email'];
			
			$pedido['domicilio'] = utf8_decode($data['direccion']);
			$pedido['observaciones'] = utf8_decode($data['observaciones']);
			
			$pedido['id_forma_pago'] = $data['id_forma_pago'];
			$pedido['id_medio_envio'] = $data['id_forma_envio'];
			
			$pedido['items_anteriores'] = utf8_decode($data['detalle']);
			$pedido['envio'] = $data['costo_envio'];
			$pedido['total'] = $data['total'];
			
			$pedido['estado'] = (isset($data['estado'])) ? $data['estado'] : 1;
			
			$pedido['fecha_alta'] = $data['created_at'];
			$pedido['fecha_alta_utc'] = strtotime($data['created_at']);
			
			echo '<pre>' . print_r($pedido, true) . '</pre>';
			
			
			if (!$this->tienda_importacion_model->verificarSiExistePedido($data['id']))
			{
				$this->tienda_importacion_model->ingresarPedido($pedido);
			}
			else
			{
				$this->tienda_importacion_model->modificarPedido($id, $pedido);
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function envio($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			//models
			$this->load->model('tienda_importacion_model');
			
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			$data = $this->tienda_importacion_model->getTiendaDetalleRemota($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			

			// db CMS
			$this->db = $this->load->database('default', true);
			

			// Tienda
			$tienda['id'] = $data['id'];
			
			
			// Formas de envío (No se terminó)
			//if ($data['xxx'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDeEnvio($data['id'], 1)) $this->tienda_importacion_model->ingresarFormaDeEnvio(array('id_tienda'=>$data['id'], 'id_envio'=>1));

			//if ($data['xxx'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDeEnvio($data['id'], 2)) $this->tienda_importacion_model->ingresarFormaDeEnvio(array('id_tienda'=>$data['id'], 'id_envio'=>2));

			//if ($data['xxx'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDeEnvio($data['id'], 3)) $this->tienda_importacion_model->ingresarFormaDeEnvio(array('id_tienda'=>$data['id'], 'id_envio'=>3));

			//if ($data['xxx'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDeEnvio($data['id'], 4)) $this->tienda_importacion_model->ingresarFormaDeEnvio(array('id_tienda'=>$data['id'], 'id_envio'=>4));

			//if ($data['xxx'] == 1 && !$this->tienda_importacion_model->verificarSiExisteFormaDeEnvio($data['id'], 5)) $this->tienda_importacion_model->ingresarFormaDeEnvio(array('id_tienda'=>$data['id'], 'id_envio'=>5));
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function todo()
	{
		if ($this->is_logged_in('reseller'))
		{
			// db Pedimos Fácil
			$this->db = $this->load->database('pedimosfacil', true);
			
			//models
			$this->load->model('tienda_importacion_model');
			
			
			$tiendas = $this->tienda_importacion_model->getTiendasRemota();
			
			$categorias = $this->tienda_importacion_model->getCategoriasRemota();
			
			$productos = $this->tienda_importacion_model->getProductosRemota();
			
			$opciones_grupos = $this->tienda_importacion_model->getOpcionesGruposRemota();
			
			$opciones = $this->tienda_importacion_model->getOpcionesRemota();
			
			$producto_rel_opciones_grupo = $this->tienda_importacion_model->getProductoRelOpcionesGrupoRemota();
			
			$pedidos = $this->tienda_importacion_model->getPedidosRemota();
			
			
/*
			// Tiendas
			foreach ($tiendas as $obj)
			{
				echo $obj['id'] . ': ' . $obj['negocio'] . '<br>';
				
				$this->tienda($obj['id']);
			}
			
			
			// Categorias
			foreach ($categorias as $obj)
			{
				echo $obj['id'] . ': ' . $obj['nombre'] . '<br>';
				
				$this->categoria($obj['id']);
			}
			
			
			// Productos
			foreach ($productos as $obj)
			{
				echo $obj['id'] . ': ' . $obj['name'] . '<br>';
				
				$this->producto($obj['id']);
			}


			// Opciones Grupos
			foreach ($opciones_grupos as $obj)
			{
				echo $obj['id'] . ': ' . $obj['nombre'] . '<br>';
				
				$this->opciones_grupo($obj['id']);
			}
			

			// Opciones
			foreach ($opciones as $obj)
			{
				echo $obj['id'] . ': ' . $obj['nombre'] . '<br>';
				
				$this->opcion($obj['id']);
			}


			// Productos Rel Opciones Grupo
			foreach ($producto_rel_opciones_grupo as $obj)
			{
				echo $obj['id_producto'] . ' <> ' . $obj['id_grupo'] . '<br>';
				
				$this->producto_rel_opciones_grupo($obj['id_producto'], $obj['id_grupo']);
			}
			

			// Pedidos
			foreach ($pedidos as $obj)
			{
				echo $obj['id'] . ': ' . $obj['client_email'] . '<br>';
				
				$this->pedido($obj['id']);
			}
*/

			// Formas de Envío (No se terminó)
/*
			foreach ($tiendas as $obj)
			{
				echo $obj['id'] . ': ' . $obj['negocio'] . '<br>';
				
				$this->envio($obj['id']);
			}
*/
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}