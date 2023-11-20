<?php defined('BASEPATH') or exit('No direct script access allowed');

class Regalos_model extends CI_Model {

	/* API */
	public function ingresarPedido($variables)
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.estado";
		$sql .= " FROM con_car_pedidos";
		$sql .= " WHERE con_car_pedidos.id_empresa = 7358";
		$sql .= " AND con_car_pedidos.id_contacto = ".$variables['id_contacto'];
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
			$datospedido['id_empresa'] = 7358;
			$datospedido['id_contacto'] = $variables['id_contacto'];
			$datospedido['id_forma_pago'] = 1;
			$datospedido['terminos'] = 1;
			$datospedido['regalar'] = 1;
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
}
