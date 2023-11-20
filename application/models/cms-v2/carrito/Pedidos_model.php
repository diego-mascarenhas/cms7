<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

	//LISTADO GENERAL
	public function getPedidos($parametros = null)
	{
		$sql = "SELECT con_carro_pedidos.*, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.email) AS contacto,";
		$sql .= " CASE
						WHEN con_carro_pedidos.estado = 1 THEN 'En proceso de compra'
						WHEN con_carro_pedidos.estado = 2 THEN 'Pagado'
						WHEN con_carro_pedidos.estado = 3 THEN 'Enviado al cliente'
						WHEN con_carro_pedidos.estado = 4 THEN 'Recibido por el cliente'
						WHEN con_carro_pedidos.estado = 5 THEN 'Pendiente'
						WHEN con_carro_pedidos.estado = 6 THEN 'Regalado'
						WHEN con_carro_pedidos.estado = 7 THEN 'Bonificado'
						WHEN con_carro_pedidos.estado = 8 THEN 'Cancelado'
					END AS tipo_estado";
		$sql .= " FROM con_carro_pedidos";
		$sql .= " LEFT JOIN contactos ON contactos.id = con_carro_pedidos.id_contacto";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_carro_pedidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_carro_pedidos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_pedidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_pedidos.estado >= 0";
		}
		$sql .= " ORDER BY con_carro_pedidos.fecha_alta DESC, con_carro_pedidos.id ASC";

		if (isset($parametros['limit']))
		{
			$sql .= " LIMIT ?";
			$placeholders[] = $parametros['limit'];
		}

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	//DETALLE ITEM PEDIDO
	public function detalleItemPedido($parametros)
	{
		$sql = "SELECT con_carro_pedidos_items.id, con_carro_pedidos_items.id_con_car_pedido as id_pedido, con_carro_pedidos_items.cantidad";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " LEFT JOIN con_carro_pedidos_items ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " WHERE con_carro_pedidos_items.grupo = ?";
		$sql .= " AND con_carro_pedidos_items.id_empresa = ?";
		$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";
		$sql .= " AND con_carro_pedidos_items.id_producto = ?";
		
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id_pedido'];
		$placeholders[] = $parametros['id_producto'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_pedidos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_pedidos_items.estado > 0";
		}
		if (!isset($res['error']))
		{
			$query = $this->db->query($sql, $placeholders);
		}
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	//DETALLE PEDIDO PUBLIC
	public function detallePedidoPublic($parametros)
	{
		$sql = "SELECT id, subtotal, descuento, descuento_monto, envio, impuestos, cupon, total, observaciones";
		$sql .= " FROM con_carro_pedidos";
		$sql .= " WHERE grupo = ?";
		$sql .= " AND id_empresa = ?";
		$sql .= " AND id = ?";
		
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id_pedido'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado > 0";
		}
		if (!isset($res['error']))
		{
			$query = $this->db->query($sql, $placeholders);
		}
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	//DETALLE PEDIDO
	public function detallePedido($parametros)
	{
		$sql = "SELECT con_carro_pedidos.*, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.email) AS contacto, contactos.email,contactos.celular,";
		$sql .= " CASE
						WHEN con_carro_pedidos.estado = 1 THEN 'En proceso de compra'
						WHEN con_carro_pedidos.estado = 2 THEN 'Pagado'
						WHEN con_carro_pedidos.estado = 3 THEN 'Enviado al cliente'
						WHEN con_carro_pedidos.estado = 4 THEN 'Recibido por el cliente'
						WHEN con_carro_pedidos.estado = 5 THEN 'Pendiente'
						WHEN con_carro_pedidos.estado = 6 THEN 'Regalado'
						WHEN con_carro_pedidos.estado = 7 THEN 'Bonificado'
						WHEN con_carro_pedidos.estado = 8 THEN 'Cancelado'
					END AS tipo_estado";
		$sql .= " FROM con_carro_pedidos";
		$sql .= " LEFT JOIN contactos ON contactos.id = con_carro_pedidos.id_contacto";		
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$sql .= " AND con_carro_pedidos.id = ?";
		
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id_pedido'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_pedidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_pedidos.estado > 0";
		}
		if (!isset($res['error']))
		{
			$query = $this->db->query($sql, $placeholders);
		}
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}


	public function listadoPedidoItems($parametros)
	{
		$sql = "SELECT con_carro_pedidos_items.id, con_carro_pedidos_items.cantidad, con_carro_pedidos_items.subtotal, con_car_productos_items.codigo, con_car_productos_items.imagen, con_car_productos_items.titulo, con_car_productos_items.precio_oferta, con_car_productos_items.precio, con_car_productos_items.uri, con_car_productos_items.id_producto as id_producto, con_car_productos.id_categoria, con_car_productos_categorias.categoria";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_car_productos_items ON con_car_productos_items.id = con_carro_pedidos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";

		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id_pedido'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_pedidos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_pedidos_items.estado >= 0";
		}

		$sql .= " ORDER BY con_carro_pedidos_items.id ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function cantidadPedidoItems($id_pedido)
	{
		$sql = "SELECT COUNT(con_carro_pedidos_items.id) as cantidad";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id_pedido;

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	//COMBO ESTADOS
	public function comboEstados()
	{
		$sql = "SELECT id, estado";
		$sql .= " FROM con_car_pedidos_estados";
		$sql .= " WHERE id > 1";
		$sql .= " ORDER BY id ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['estado'];
		}
		return (!empty($padre)) ? $padre : null;
	}

	//INGRESAR PEDIDO
	public function ingresarPedido($variables)
	{
		//INSERTO PEDIDO
		$datos['grupo'] = $this->usuario->grupo;
		$datos['id_empresa'] = $this->usuario->id_empresa;
		if(isset($variables['id_contacto'])) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
		if(isset($variables['id_contacto_direccion'])) { $datos['id_contacto_direccion'] = $variables['id_contacto_direccion']; } else { $datos['id_contacto_direccion'] = null; }
		$datos['cookie'] = $variables['cookie'];
		if(isset($variables['padre'])) { $datos['padre'] = $variables['padre']; } else { $datos['padre'] = 0; }
		if(isset($variables['regalar'])) { $datos['regalar'] = $variables['regalar']; } else { $datos['regalar'] = 0; }
		if(isset($variables['id_forma_pago'])) { $datos['id_forma_pago'] = $variables['id_forma_pago']; } else { $datos['id_forma_pago'] = 9; }
		if(isset($variables['id_medio_envio'])) { $datos['id_medio_envio'] = $variables['id_medio_envio']; } else { $datos['id_medio_envio'] = 2; }
		$datos['terminos'] = $variables['terminos'];
		$datos['estado'] = 1;
		$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$datos['fecha_alta_utc'] = now();
		$datos['user_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_carro_pedidos', $datos);
		$res['id'] = $this->db->insert_id();
		
		//SI INGRESA EL PEDIDO IGNRESO EL ITEM
		if($res['id'])
		{
			$variables['id_con_car_pedido'] = $res['id'];
			$res2 = $this->ingresarItemPedido($variables);
		}
		return (!empty($res)) ? $res : null;
	}

	public function ingresarItemPedido($variables)
	{
		//SELECCIONO TOTAL ITEMS
		$sql = "SELECT id, estado";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " WHERE id_con_car_pedido = ".$variables['id_con_car_pedido'];
		$sql .= " AND id_producto = ".$variables['id_producto_item'];
		$sql .= " AND estado = 2";
		$query = $this->db->query($sql);
		$item = $query->row_array();

		if($item['id'])
		{
			$datos['cantidad'] = $variables['cantidad'];
			if(isset($variables['peso'])) { $datos['peso'] = $variables['peso']; } else { $datos['peso'] = null; }
			if(isset($variables['descuento'])) { $datos['descuento'] = $variables['descuento']; } else { $datos['descuento'] = null; }
			$datos['subtotal'] = $variables['precio']*$variables['cantidad'];
			if(isset($variables['certificado'])) { $datos['certificado'] = $variables['certificado']; } else { $datos['certificado'] = 0; }
			$datos['estado'] = 2;
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
	
			$where = "id = ".$item['id'];
			$res2 = $this->db->update('con_carro_pedidos_items', $datos, $where);
			$res['id'] = $this->db->insert_id();
		}
		else
		{
			//INSERTO ITEM DEL PEDIDO
			$datos['id_con_car_pedido'] = $variables['id_con_car_pedido'];
			$datos['id_producto'] = $variables['id_producto_item'];
			$datos['cantidad'] = $variables['cantidad'];
			if(isset($variables['peso'])) { $datos['peso'] = $variables['peso']; } else { $datos['peso'] = null; }
			if(isset($variables['descuento'])) { $datos['descuento'] = $variables['descuento']; } else { $datos['descuento'] = null; }
			$datos['subtotal'] = $variables['precio']*$variables['cantidad'];
			if(isset($variables['certificado'])) { $datos['certificado'] = $variables['certificado']; } else { $datos['certificado'] = 0; }
			$datos['estado'] = 2;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['fecha_alta_utc'] = now();
			$datos['user_alta'] = $this->usuario->id;
	
			$insert = $this->db->insert('con_carro_pedidos_items', $datos);
			$res['id'] = $this->db->insert_id();
		}
		
		if ($this->sumarTotales($variables['id_con_car_pedido']))
		{
			return ($res);
		}
		else
		{
			return false;
		}
	}

	//INGRESAR PEDIDO
	public function modificarPedido($id, $variables)
	{
		//MODIFICO PEDIDO
		if(isset($variables['id_contacto'])) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
		if(isset($variables['id_contacto_direccion'])) { $datos['id_contacto_direccion'] = $variables['id_contacto_direccion']; } else { $datos['id_contacto_direccion'] = null; }
		if(isset($variables['padre'])) { $datos['padre'] = $variables['padre']; } else { $datos['padre'] = 0; }
		if(isset($variables['regalar'])) { $datos['regalar'] = $variables['regalar']; } else { $datos['regalar'] = 0; }
		if(isset($variables['observaciones'])) { $datos['observaciones'] = $variables['observaciones']; }
		if(isset($variables['terminos'])) { $datos['terminos'] = $variables['terminos']; }
		if(isset($variables['estado'])) { $datos['estado'] = $variables['estado']; }
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where = "id = $id";
		$res = $this->db->update('con_carro_pedidos', $datos, $where);
		return (!empty($res)) ? $res : null;
	}

	//CAMBIAR ESTADO PEDIDO
	public function cambiarEstadoPedido($id, $estado)
	{
		//MODIFICO ESTADO PEDIDO
		$datos['estado'] = $estado;
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where = "id = $id";
		$res = $this->db->update('con_carro_pedidos', $datos, $where);
		return (!empty($res)) ? $res : null;
	}

	public function eliminarItemPedido($variables)
	{
		$borrar = $this->db->where('id', $variables['id']);
		$res = $this->db->delete('con_carro_pedidos_items'); 
		
		if ($this->sumarTotales($variables['id_pedido']))
		{
			return ($res);
		}
		else
		{
			return false;
		}		
	}

	//SUMO LOS TOTALES PARA EL PEDIDO
	public function sumarTotales($id_pedido)
	{
		//SELECCIONO TOTAL ITEMS
		$sql = "SELECT SUM(con_carro_pedidos_items.subtotal) as subtotal";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " WHERE con_carro_pedidos_items.id_con_car_pedido = $id_pedido";
		$sql .= " AND con_carro_pedidos_items.estado = 2";
		$query = $this->db->query($sql);
		$subtotal = $query->row_array();
		
		$datos['subtotal'] = $subtotal['subtotal'];
		$datos['descuento'] = 0;
		$datos['total'] = $subtotal['subtotal'];
		$datos['fecha_modificacion'] = now();
		$datos['user_modificacion'] = $this->usuario->id;
		$where = "id = $id_pedido";
		$res = $this->db->update('con_carro_pedidos', $datos, $where);
		
		return ($subtotal);
	}

	public function cancelarPedido($id)
	{
		//BORRO ITEMS
		$sql = "SELECT con_carro_pedidos_items.id";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";
		$sql .= " AND con_carro_pedidos.estado < 6";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id;
		$query = $this->db->query($sql, $placeholders);
		$items = $query->result_array();

		foreach($items as $item)
		{
			$where = $this->db->where('id', $item['id']);
			$res = $this->db->delete('con_carro_pedidos_items'); 
		}

		//BORRO PEDIDO
		$borrar = $this->db->where('id', $id);
		$res2 = $this->db->delete('con_carro_pedidos'); 
		
		return (!empty($res2)) ? $res2 : null;
	}

}