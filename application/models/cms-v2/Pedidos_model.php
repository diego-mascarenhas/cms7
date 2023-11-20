<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

	/* API */
	public function listadoPedidos($id)
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.fecha_alta, con_car_pedidos.regalar, con_car_pedidos.total, con_car_pedidos.estado as id_estado, con_car_pedidos_estados.estado";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_car_pedidos_estados ON con_car_pedidos_estados.id = con_car_pedidos.estado";
		$sql .= " WHERE con_car_pedidos.id_contacto = $id";
		$sql .= " AND con_car_pedidos.estado > 0";
		$sql .= " AND con_car_pedidos.regalar != 2";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " ORDER BY con_car_pedidos.id ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoMisCursos($id)
	{
		$sql = "SELECT con_car_pedidos_items.id, con_car_pedidos_items.id_producto as id_contenido, con_car_pedidos.id_contacto, con_car_pedidos_items.id_con_car_pedido, con_car_pedidos_items.certificado, con_car_pedidos_items.estado, con_contenidos.imagen, con_contenido_items.titulo, con_contenido_items.contenido1";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " LEFT JOIN con_car_pedidos ON con_car_pedidos.id = con_car_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_car_pedidos_items.id_producto";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_car_pedidos_items.id_producto";
		$sql .= " WHERE con_car_pedidos.id_contacto = $id";
		$sql .= " AND con_car_pedidos.regalar != 1";
		$sql .= " AND con_car_pedidos_items.estado = 3";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " ORDER BY con_car_pedidos_items.id ASC";
		
 		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleItemCompra($id)
	{
		$sql = "SELECT con_car_pedidos_items.id, con_car_pedidos_items.certificado";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " WHERE con_car_pedidos_items.id = $id";
		$sql .= " AND con_car_pedidos_items.estado = 3";
		
 		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detallePedidoId($id)
	{
		$sql = "SELECT con_car_pedidos.*";
		$sql .= " FROM con_car_pedidos";
		$sql .= " WHERE con_car_pedidos.estado > 0";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " AND con_car_pedidos.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function PedidoBeneficiarioId($id)
	{
		$sql = "SELECT con_car_pedidos.id, con_contactos.nombre, con_contactos.apellido, con_contactos.email";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_contactos ON con_contactos.id = con_car_pedidos.id_contacto";
		$sql .= " WHERE con_car_pedidos.estado > 0";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " AND con_car_pedidos.padre = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function listadoPedidoItems($id)
	{
		$sql = "SELECT con_car_pedidos_items.*, con_car_pedidos.id_contacto, con_contenidos.imagen, con_contenido_items.titulo, con_contenido_items.url, con_contenido_items.contenido1";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " LEFT JOIN con_car_pedidos ON con_car_pedidos.id = con_car_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_car_pedidos_items.id_producto";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_car_pedidos_items.id_producto";
		$sql .= " WHERE con_car_pedidos_items.id_con_car_pedido = $id";
		$sql .= " AND con_car_pedidos_items.estado = 3";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " ORDER BY con_car_pedidos_items.id ASC";
		
 		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoPedidoItemsUser($id)
	{
		$sql = "SELECT con_car_pedidos_items.*, con_car_pedidos.id_contacto, con_contenidos.imagen, con_contenido_items.titulo, con_contenido_items.url, con_contenido_items.contenido1";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " LEFT JOIN con_car_pedidos ON con_car_pedidos.id = con_car_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_car_pedidos_items.id_producto";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_car_pedidos_items.id_producto";
		$sql .= " WHERE con_car_pedidos.id_contacto = $id";
		$sql .= " AND con_car_pedidos_items.estado = 3";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " ORDER BY con_car_pedidos_items.id ASC";
		
 		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detallePedido($id)
	{
		$sql = "SELECT con_car_pedidos.*, con_car_pedidos.estado as id_estado, con_car_pedidos_estados.estado, con_contactos.nombre, con_contactos.apellido";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_car_pedidos_estados ON con_car_pedidos_estados.id = con_car_pedidos.estado";
		$sql .= " LEFT JOIN con_contactos ON con_contactos.id = con_car_pedidos.id_contacto";
		$sql .= " WHERE con_car_pedidos.estado > 0";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " AND con_car_pedidos.id_contacto = $id";
		$sql .= " AND con_car_pedidos.estado = 5 ORDER BY id ASC LIMIT 1";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarCertificado($variables)
	{
		$this->load->helper('date');
		$datos['certificado'] = $variables['certificado'];
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $variables['id_contacto'];

		$where = "id = ".$variables['id'];
		$res = $this->db->update('con_car_pedidos_items', $datos, $where);
		return ($res);
	}

	public function totalPedido($id)
	{
		//BORRO ROW DEL ITEM
		$sql = "SELECT SUM(con_car_pedidos_items.subtotal) as subtotal";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " LEFT JOIN con_car_pedidos ON con_car_pedidos.id = con_car_pedidos_items.id_con_car_pedido";
/* 		$sql .= " WHERE con_car_pedidos.id_empresa = 7358"; */
		$sql .= " WHERE con_car_pedidos.id = $id";
		$sql .= " AND con_car_pedidos_items.estado = 3";
		$sql .= " AND con_car_pedidos.estado = 5";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	

	public function ingresarPedido($variables)
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.estado";
		$sql .= " FROM con_car_pedidos";
/* 		$sql .= " WHERE con_car_pedidos.id_empresa = 7358"; */
		$sql .= " WHERE con_car_pedidos.id_contacto = ".$variables['id_contacto'];
		$sql .= " AND con_car_pedidos.estado = 5";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		if(!$res)
		{
			$sql = "SELECT con_contenido_items.precio, con_contenido_items.precioUsd";
			$sql .= " FROM con_contenido_items";
			$sql .= " WHERE con_contenido_items.id_contenido = ".$variables['id_producto'];
			$query = $this->db->query($sql);
			$res7 = $query->row_array();
			
			$sql = "SELECT con_contactos.pais";
			$sql .= " FROM con_contactos";
/* 			$sql .= " AND con_contactos.id_empresa = 7358"; */
			$sql .= " WHERE con_contactos.id = ".$variables['id_contacto'];
			$query = $this->db->query($sql);
			$res8 = $query->row_array();

			if($res8['pais'] == 'ar') 
			{ 
				$item['subtotal'] = $res7['precio']; 
			} 
			else 
			{ 
				$item['subtotal'] = $res7['precioUsd']; 
			}

			//INSERTO PEDIDO
			//$datospedido['id_empresa'] = 7358;
			$datospedido['id_contacto'] = $variables['id_contacto'];
			$datospedido['id_forma_pago'] = 1;
			$datospedido['regalar'] = 0; 
			$datospedido['terminos'] = 0;
			$datospedido['total'] = $item['subtotal'];
			$datospedido['subtotal'] = $item['subtotal'];
			$datospedido['estado'] = 5;
			$datospedido['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datospedido['user_alta'] = $variables['id_contacto'];
	
			$insert = $this->db->insert('con_car_pedidos', $datospedido);
			$res2['id'] = $this->db->insert_id();
			
			if ($res2['id'])
			{				
				$datos['id_con_car_pedido'] = $res2['id'];
				$datos['id_producto'] = $variables['id_producto'];
				$datos['cantidad'] = 1;
				$datos['subtotal'] = $item['subtotal'];
				$datos['certificado'] = 0;
				$datos['estado'] = 3;
				$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$datos['user_alta'] = $variables['id_contacto'];
				$res3 = $this->db->insert('con_car_pedidos_items', $datos);
			}
		}
		return ($res3);
	}



	public function ingresarItem($variables)
	{
		$this->load->helper('date');

		$sql = "SELECT con_contenido_items.precio, con_contenido_items.precioUsd";
		$sql .= " FROM con_contenido_items";
		$sql .= " WHERE con_contenido_items.id_contenido = ".$variables['id_producto'];
		$query = $this->db->query($sql);
		$res = $query->row_array();

		if($variables['pais'] == 'argentina') 
		{ 
			$item['subtotal'] = $res['precio']; 
		} 
		else 
		{ 
			$item['subtotal'] = $res['precioUsd']; 
		}

		$datos['id_con_car_pedido'] = $variables['id_pedido']; 
		$datos['id_producto'] = $variables['id_producto'];
		$datos['cantidad'] = $variables['cantidad'];
		$datos['subtotal'] = $item['subtotal'];
		$datos['certificado'] = 0;
		$datos['estado'] = 3;
		$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_alta'] = $variables['id_contacto'];

		$insert = $this->db->insert('con_car_pedidos_items', $datos);
		$res2['id'] = $this->db->insert_id();
		
		if($res2['id'])
		{
			$sql = "SELECT SUM(con_car_pedidos_items.subtotal) as total";
			$sql .= " FROM con_car_pedidos_items";
			$sql .= " WHERE con_car_pedidos_items.id_con_car_pedido = ".$datos['id_con_car_pedido'];
			$sql .= " AND con_car_pedidos_items.estado = 3";
			$query = $this->db->query($sql);
			$res3 = $query->row_array();

			$datos2['subtotal'] = $res3['total'];
			$datos2['total'] = $res3['total'];
			$datos2['descuento'] = 0;
			$datos2['descuento_monto'] = 0;
			$datos2['cupon'] = '';
			$datos2['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos2['user_modificacion'] = $variables['id_contacto'];

			$where2 = "id = ".$datos['id_con_car_pedido'];
			$res4 = $this->db->update('con_car_pedidos', $datos2, $where2);
		}
		return ($res4);
	}

	public function eliminarItem($variables)
	{
		$sql = "SELECT id_con_car_pedido as id_pedido";
		$sql .= " FROM con_car_pedidos_items";
		$sql .= " WHERE con_car_pedidos_items.id = ".$variables['id'];
		$query = $this->db->query($sql);
		$pedido = $query->row_array();

		$id = $variables['id'];
		$tester = $this->db->where('id', $id);
		$res= $this->db->delete('con_car_pedidos_items'); 

		if($res)
		{
			$sql = "SELECT COALESCE(SUM(con_car_pedidos_items.subtotal), 0) as total, con_car_pedidos.cupon";
			$sql .= " FROM con_car_pedidos_items";
			$sql .= " LEFT JOIN con_car_pedidos ON con_car_pedidos.id = con_car_pedidos_items.id_con_car_pedido";
/* 			$sql .= " WHERE con_car_pedidos.id_empresa = 7358"; */
			$sql .= " WHERE con_car_pedidos_items.id_con_car_pedido = ".$pedido['id_pedido'];
			$sql .= " AND con_car_pedidos_items.estado = 3";
			$sql .= " AND con_car_pedidos.estado = 5";
			$query = $this->db->query($sql);
			$res2 = $query->row_array();

			$datos['subtotal'] = $res2['total'];
			$datos['total'] = $res2['total'];
			$datos['descuento'] = 0;
			$datos['descuento_monto'] = 0;
			$datos['cupon'] = '';
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $variables['id_contacto'];
			
			$where = "id = ".$pedido['id_pedido'];
			$res3 = $this->db->update('con_car_pedidos', $datos, $where);

			if($res3)
			{
				$sql = "SELECT con_car_cupones.id, con_car_cupones.stock";
				$sql .= " FROM con_car_cupones";
				$sql .= " WHERE con_car_cupones.id_empresa = 7358";
				$sql .= " AND con_car_cupones.cupon = '".$res2['cupon']."'";
				
				$query = $this->db->query($sql);
				$res4 = $query->row_array();

				$datoscupon['stock'] = $res4['stock']+1;
				$datoscupon['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
				$datoscupon['user_modificacion'] = $variables['id_contacto'];
				$wherecupon = "id = ".$res4['id'];
				$res5 = $this->db->update('con_car_cupones', $datoscupon, $wherecupon);
			}
			
		}
		return ($res3);
	}

	public function detalleCupon($id)
	{
		$sql = "SELECT con_car_cupones.id, con_car_cupones.cupon";
		$sql .= " FROM con_car_cupones";
/* 		$sql .= " WHERE con_car_cupones.id_empresa = 7358"; */
		$sql .= " WHERE con_car_cupones.fecha_vencimiento > NOW()";
		$sql .= " AND con_car_cupones.estado = 3";
		$sql .= " AND con_car_cupones.stock > 0";
		$sql .= " AND con_car_cupones.cupon = '$id'";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarCupon($variables)
	{
		$this->load->helper('date');

		$sql = "SELECT con_car_cupones.id, con_car_cupones.cupon, con_car_cupones.descuento, con_car_cupones.stock";
		$sql .= " FROM con_car_cupones";
/* 		$sql .= " WHERE con_car_cupones.id_empresa = 7358"; */
		$sql .= " WHERE con_car_cupones.fecha_vencimiento > NOW()";
		$sql .= " AND con_car_cupones.estado = 3";
		$sql .= " AND con_car_cupones.stock > 0";
		$sql .= " AND con_car_cupones.cupon = '".$variables['cupon']."'";
		$query = $this->db->query($sql);
		$res = $query->row_array();
		
		if($res)
		{
			$sql = "SELECT SUM(con_car_pedidos_items.subtotal) as subtotal";
			$sql .= " FROM con_car_pedidos_items";
			$sql .= " WHERE con_car_pedidos_items.id_con_car_pedido = ".$variables['id_pedido'];
			$sql .= " AND con_car_pedidos_items.estado = 3";
			$query = $this->db->query($sql);
			$res2 = $query->row_array();

			$datos['subtotal'] = $res2['subtotal'];
			$datos['cupon'] = $variables['cupon'];
			$datos['descuento'] = $res['descuento'];
			$datos['descuento_monto'] = ($datos['subtotal']*$res['descuento'])/100;
			$datos['total'] = $datos['subtotal']-$datos['descuento_monto'];
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $variables['id_contacto'];
			
			$where = "id = ".$variables['id_pedido'];
			$res3 = $this->db->update('con_car_pedidos', $datos, $where);

			//CAMBIO STOCK DE CUPON
			if($res3)
			{
				$datoscupon['stock'] = $res['stock']-1;
				$datoscupon['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
				$datoscupon['user_modificacion'] = $variables['id_contacto'];
				$wherecupon = "id = ".$res['id'];
				$res4 = $this->db->update('con_car_cupones', $datoscupon, $wherecupon);
			}
		}
		else
		{
			$sql = "SELECT SUM(con_car_pedidos_items.subtotal) as subtotal";
			$sql .= " FROM con_car_pedidos_items";
			$sql .= " WHERE con_car_pedidos_items.id_con_car_pedido = ".$variables['id_pedido'];
			$sql .= " AND con_car_pedidos_items.estado = 3";
			$query = $this->db->query($sql);
			$res2 = $query->row_array();

			$datos['subtotal'] = $res2['subtotal'];
			$datos['total'] = $res2['subtotal'];
			$datos['descuento'] = 0;
			$datos['descuento_monto'] = null;
			$datos['cupon'] = null;
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $variables['id_contacto'];
			
			$where = "id = ".$variables['id_pedido'];
			$res3 = $this->db->update('con_car_pedidos', $datos, $where);
		}
		return ($res3);
	}

	public function finalizarPedido($variables)
	{
		//CAMBIO EL ESTADO DEL PEDIDO A PAGADO
		$datos['estado'] = '2';
		$where = "id = ".$variables['id_pedido'];
		$res = $this->db->update('con_car_pedidos', $datos, $where);
        
        return (!empty($res)) ? $res : null;
	}
	
	public function finalizarPedidoBonificado($variables)
	{
		//CAMBIO EL ESTADO DEL PEDIDO A BONIFICADO
		$datos['estado'] = '7';
		$where = "id = ".$variables['id_pedido'];
		$res = $this->db->update('con_car_pedidos', $datos, $where);
        
        return (!empty($res)) ? $res : null;
	}

	public function duplicarPedido($variables)
	{
		$this->load->helper('date');
	
		//INSERTO PEDIDO PARA REGALAR
		$datosmod['regalar'] = 1;
		$datosmod['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datosmod['user_modificacion'] = $variables['padre'];

		$wheremodificar = "id = ".$variables['id_pedido'];
		$modificar = $this->db->update('con_car_pedidos', $datosmod, $wheremodificar);

		//TRAIGO DATOS DE PEDIDO
		$sql = "SELECT con_car_pedidos.*";
		$sql .= " FROM con_car_pedidos";
		$sql .= " WHERE con_car_pedidos.estado = 2";
/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " AND con_car_pedidos.id = ".$variables['id_pedido'];
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
/* 			$datospedido['id_empresa'] = 7358; */
			$datospedido['id_contacto'] = $variables['id_contacto'];
			$datospedido['id_forma_pago'] = 1;
			$datospedido['regalar'] = 2;
			$datospedido['id_medio_envio'] = 1;
			$datospedido['padre'] = $variables['id_pedido'];
			$datospedido['terminos'] = 0;
			$datospedido['total'] = 0;
			$datospedido['subtotal'] = 0;
			$datospedido['estado'] = 6;
			$datospedido['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datospedido['user_alta'] = $variables['padre'];
	
			//INSERTO PEDIDO
			$insert = $this->db->insert('con_car_pedidos', $datospedido);
			$res2['id'] = $this->db->insert_id();

			//INSERTO ITEMS PEDIO
			$sql = "SELECT con_car_pedidos_items.*";
			$sql .= " FROM con_car_pedidos_items";
			$sql .= " WHERE con_car_pedidos_items.estado = 3";
			$sql .= " AND con_car_pedidos_items.id_con_car_pedido = ".$variables['id_pedido'];
		
			$query = $this->db->query($sql);
			$items = $query->result_array();
			
			foreach ($items as $item)
			{
				$datos['id_con_car_pedido'] = $res2['id']; 
				$datos['id_producto'] = $item['id_producto'];
				$datos['cantidad'] = 1;
				$datos['subtotal'] = 0;
				$datos['certificado'] = 0;
				$datos['estado'] = 3;
				$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$datos['user_alta'] = $variables['padre'];
		
				$insert = $this->db->insert('con_car_pedidos_items', $datos);
			}
		}
		return ($res2);
	}
	/* FIN API */


	public function listadoPedidosCms()
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.id_forma_pago, con_car_pedidos.fecha_alta, con_car_pedidos.regalar, con_car_pedidos.total as monto, con_car_pedidos.estado as id_estado, con_car_pedidos_estados.estado, con_contactos.email as usuario";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_car_pedidos_estados ON con_car_pedidos_estados.id = con_car_pedidos.estado";
		$sql .= " LEFT JOIN con_contactos ON con_contactos.id = con_car_pedidos.id_contacto";

		if($this->input->get('estado'))
		{
			$sql .= " WHERE con_car_pedidos.estado = ".$this->input->get('estado');
		}
		else
		{
			$sql .= " WHERE con_car_pedidos.estado > 0";
		}

		if($this->input->get('moneda'))
		{
			$sql .= " AND con_contactos.pais = '".$this->input->get('moneda')."'";
		}

/* 		$sql .= " AND con_car_pedidos.id_empresa = 7358"; */
		$sql .= " AND con_car_pedidos.regalar != 2";
		$sql .= " ORDER BY con_car_pedidos.id ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detallePedidoUsuario($id)
	{
		$sql = "SELECT con_contactos.*";
		$sql .= " FROM con_contactos";
		$sql .= " WHERE con_contactos.id = $id";
/* 		$sql .= " AND con_contactos.id_empresa = 7358"; */
		
 		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
}