<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos_model extends CI_Model {

	//LISTADO GENERAL
	public function getPedidos($parametros = null)
	{
		$sql = "SELECT con_carro_pedidos.*, contactos.nombre, contactos.apellido, contactos.email, contactos_extras.razon_social as empresa,";
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
		$sql .= " LEFT JOIN contactos_extras ON contactos_extras.id_contacto = con_carro_pedidos.id_contacto";
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
		
		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND con_carro_pedidos.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
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
		$sql .= " ORDER BY con_carro_pedidos.fecha_alta DESC, con_carro_pedidos.id DESC";

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

	public function getIdiomas($parametros = null)
	{
		$sql = "SELECT con_configuracion_idiomas.id, con_configuracion_idiomas.idioma, con_configuracion_idiomas.extension, con_configuracion_idiomas.orden";
		$sql .= " FROM con_configuracion_idiomas";
		$sql .= " WHERE con_configuracion_idiomas.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_configuracion_idiomas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_configuracion_idiomas.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			$sql .= " AND con_configuracion_idiomas.estado = 3";
			$query = $this->db->query($sql, $placeholders);
		}
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	//VERIFICAR PEDIDO
	public function verificarPedido($variables)
	{
		$sql = "SELECT con_carro_pedidos.id, con_carro_pedidos.estado";
		$sql .= " FROM con_carro_pedidos";
		$sql .= " LEFT JOIN con_carro_pedidos_items ON con_carro_pedidos_items.id_con_car_pedido = con_carro_pedidos.id";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$sql .= " AND con_carro_pedidos.id_contacto = ?";
		$sql .= " AND (con_carro_pedidos.estado = 1 || con_carro_pedidos.estado = 5)";
		$sql .= " AND con_carro_pedidos_items.id_producto = ?";
		$sql .= " AND con_carro_pedidos_items.estado = 2";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $variables['id_contacto'];
		$placeholders[] = $variables['id_curso'];
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		
		return (!empty($res)) ? 1 : 0;
	}
	

	//INGRESAR PEDIDO
	public function ingresarPedido($variables)
	{
		if(!isset($variables['id_pedido']))
		{
			$sql = "SELECT con_carro_pedidos.id, con_carro_pedidos.subtotal, con_carro_pedidos.total, con_carro_pedidos.estado";
			$sql .= " FROM con_carro_pedidos";
			$sql .= " WHERE con_carro_pedidos.grupo = ?";
			$sql .= " AND con_carro_pedidos.id_empresa = ?";
			$sql .= " AND con_carro_pedidos.id_contacto = ?";
			$sql .= " AND con_carro_pedidos.estado = 1";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $variables['id_contacto'];
			$query = $this->db->query($sql, $placeholders);
			$res = $query->row_array();
	
			//SI NO HAY PEDIDO CARGADO
			if(!$res['id'])
			{
				$datos['grupo'] = $this->usuario->grupo;
				$datos['id_empresa'] = $this->usuario->id_empresa;
				if(isset($variables['observaciones'])) { $datos['observaciones'] = $variables['observaciones']; } else { $datos['observaciones'] = null; }
				if(isset($variables['id_contacto'])) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
				$datos['estado'] = $variables['estado'];
				$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$datos['fecha_alta_utc'] = now();
				$datos['user_alta'] = $this->usuario->id;
				$insert = $this->db->insert('con_carro_pedidos', $datos);
				$res['id'] = $this->db->insert_id();
				
				if($res['id'])
				{
					$variables['id_con_car_pedido'] = $res['id'];
					if((isset($variables['masivo'])) &&  ($variables['masivo'] == 1))
					{
						foreach($variables['items'] as $curso)
						{
							$variables['id_curso'] = $curso;
							$variables['cantidad'] = 1;
							$res2 = $this->ingresarItemPedido($variables);
						}
					}
					else
					{
						$res2 = $this->ingresarItemPedido($variables);
					}
				}
			}
			//SI HAY PEDIDO CARGADO
			else
			{
				if(isset($variables['observaciones'])) { $datospedido['observaciones'] = $variables['observaciones']; } else { $datospedido['observaciones'] = null; }
				$datospedido['fecha_modificacion'] =  unix_to_human(time(), TRUE, 'eu');
				$datospedido['user_modificacion'] = $this->usuario->id;
				$where = "id = ".$res['id'];
				$update = $this->db->update('con_carro_pedidos', $datospedido, $where);
	
				$variables['id_con_car_pedido'] = $res['id'];
				if((isset($variables['masivo'])) &&  ($variables['masivo'] == 1))
				{
					foreach($variables['items'] as $curso)
					{
						$variables['id_curso'] = $curso;
						$variables['cantidad'] = 1;
						$res2 = $this->ingresarItemPedido($variables);
					}
				}
				else
				{
					$res2 = $this->ingresarItemPedido($variables);
				}
	
				$res2 = $this->ingresarItemPedido($variables);
				$res['id'] = $res['id'];
			}
		}
		else
		{
			if(isset($variables['observaciones'])) { $datospedido['observaciones'] = $variables['observaciones']; } else { $datospedido['observaciones'] = null; }
			$datospedido['fecha_modificacion'] =  unix_to_human(time(), TRUE, 'eu');
			$datospedido['user_modificacion'] = $this->usuario->id;
			$where = "id = ".$variables['id_pedido'];
			$update = $this->db->update('con_carro_pedidos', $datospedido, $where);
	
			$variables['id_con_car_pedido'] = $variables['id_pedido'];
			if((isset($variables['masivo'])) &&  ($variables['masivo'] == 1))
			{
				foreach($variables['items'] as $curso)
				{
					$variables['id_curso'] = $curso;
					$variables['cantidad'] = 1;
					$res2 = $this->ingresarItemPedido($variables);
				}
			}
			else
			{
				$res2 = $this->ingresarItemPedido($variables);
			}
			$res2 = $this->ingresarItemPedido($variables);
			$res['id'] = $variables['id_pedido'];
		}
		return (!empty($res)) ? $res : $res['error'];
/* 		return ($res); */
	}
	
	public function ingresarItemPedido($variables)
	{
		$sql1 = "SELECT precio, precio_oferta";
		$sql1 .= " FROM con_elearning_items";
		$sql1 .= " WHERE con_elearning_items.id_elearning = ".$variables['id_curso'];
		$sql1 .= " AND con_elearning_items.estado = 3";
		$query1 = $this->db->query($sql1);
		$curso = $query1->row_array();

		//SELECCIONO TOTAL ITEMS
		$sql = "SELECT id, estado";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " WHERE id_con_car_pedido = ".$variables['id_con_car_pedido'];
		$sql .= " AND id_producto = ".$variables['id_curso'];
		$sql .= " AND estado = 2";
		$query = $this->db->query($sql);
		$item = $query->row_array();

		if($item['id'])
		{
			$datos['cantidad'] = $variables['cantidad'];
			if(isset($variables['peso'])) { $datos['peso'] = $variables['peso']; } else { $datos['peso'] = null; }
			if(isset($variables['descuento'])) { $datos['descuento'] = $variables['descuento']; } else { $datos['descuento'] = null; }
			if($curso['precio_oferta'] > 0) { $datos['subtotal'] = $curso['precio_oferta']*$variables['cantidad']; } else { $datos['subtotal'] = $curso['precio']*$variables['cantidad']; }
			if(isset($variables['certificado'])) { $datos['certificado'] = $variables['certificado']; } else { $datos['certificado'] = 0; }
			$datos['estado'] = 2;
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
			$where = "id = ".$item['id'];
			$res2 = $this->db->update('con_carro_pedidos_items', $datos, $where);
			$res['id'] = $item['id'];
		}
		else
		{
			//INSERTO ITEM DEL PEDIDO
			$datos['id_con_car_pedido'] = $variables['id_con_car_pedido'];
			$datos['id_producto'] = $variables['id_curso'];
			$datos['cantidad'] = $variables['cantidad'];
			if(isset($variables['peso'])) { $datos['peso'] = $variables['peso']; } else { $datos['peso'] = null; }
			if(isset($variables['descuento'])) { $datos['descuento'] = $variables['descuento']; } else { $datos['descuento'] = null; }
			if($curso['precio_oferta'] > 0) { $datos['subtotal'] = $curso['precio_oferta']*$variables['cantidad']; } else { $datos['subtotal'] = $curso['precio']*$variables['cantidad']; }
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

	/* LISTADO DE ITEMS DE PEDIDO */
	public function listadoPedidoItems($parametros)
	{
		$sql = "SELECT con_carro_pedidos_items.id, con_carro_pedidos_items.cantidad, con_carro_pedidos_items.subtotal, con_elearning_items.id_elearning,  con_elearning_items.codigo, con_elearning_items.titulo, con_elearning_items.certificado, con_elearning_items.contenido1, con_elearning_items.precio_oferta, con_elearning_items.precio, con_elearning_items.uri, con_elearning_items.id_elearning as id_producto, con_elearning.id_categoria, con_elearning_categorias.categoria, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_elearning_items ON con_elearning_items.id_elearning = con_carro_pedidos_items.id_producto";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_elearning = con_elearning.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media";
		$sql .= " WHERE con_carro_pedidos.grupo = ?";
		$sql .= " AND con_carro_pedidos.id_empresa = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;

		if (isset($parametros['id_pedido']))
		{
			$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";
			$placeholders[] = $parametros['id_pedido'];
		}

		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND con_carro_pedidos.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		if (isset($parametros['pedido_estado']))
		{
			$sql .= " AND con_carro_pedidos_items.estado = ?";
			$placeholders[] = $parametros['pedido_estado'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_pedidos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_pedidos_items.estado > 0";
		}

		$sql .= " GROUP BY con_carro_pedidos_items.id";
		$sql .= " ORDER BY con_carro_pedidos_items.id ASC";

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function listadoCuponesPedido($parametros = null)
	{
		$sql = "SELECT con_carro_rel_cupones_pedidos.*, con_carro_cupones.cupon";
		$sql .= " FROM con_carro_rel_cupones_pedidos";
		$sql .= " LEFT JOIN con_carro_cupones ON con_carro_cupones.id = con_carro_rel_cupones_pedidos.id_cupon";
		$sql .= " WHERE con_carro_cupones.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_carro_cupones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_carro_cupones.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND con_carro_cupones.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_cupones.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_cupones.estado >= 0";
		}

		$sql .= " AND con_carro_rel_cupones_pedidos.id_pedido = ?";
		$placeholders[] = $parametros['id_pedido'];
		$sql .= " ORDER BY con_carro_cupones.cupon ASC";
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function detalleCupon($parametros)
	{
		$sql = "SELECT con_carro_cupones.cupon, con_carro_cupones.stock, con_carro_cupones.id, con_carro_rel_cupones_pedidos.id_pedido, con_carro_pedidos.id_contacto";
		$sql .= " FROM con_carro_cupones";
		$sql .= " LEFT JOIN con_carro_rel_cupones_pedidos ON con_carro_rel_cupones_pedidos.id_cupon = con_carro_cupones.id";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_rel_cupones_pedidos.id_pedido";
		$sql .= " WHERE con_carro_cupones.grupo = ?";
		$sql .= " AND con_carro_cupones.id_empresa = ?";
		$sql .= " AND con_carro_cupones.cupon = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['cupon'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_cupones.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_cupones.estado > 0";
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

	public function ingresarCupon($variables)
	{
		$sql = "SELECT con_carro_cupones.id, con_carro_cupones.cupon, con_carro_cupones.descuento, con_carro_cupones.stock";
		$sql .= " FROM con_carro_cupones";
		$sql .= " WHERE con_carro_cupones.grupo = ?";
		$sql .= " AND con_carro_cupones.id_empresa = ?";
		$sql .= " AND con_carro_cupones.id = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $variables['id_cupon'];
		$sql .= " AND con_carro_cupones.estado = 3";
		$sql .= " AND con_carro_cupones.stock > 0";

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		
		if($res['id'])
		{
			//CAMBIO STOCK
			$datos10['stock'] = $res['stock']-1;
			$where10 = "id = ".$res['id'];
			$res10 = $this->db->update('con_carro_cupones', $datos10, $where10);

			$datos1['id_cupon'] = $res['id'];
			$datos1['id_pedido'] = $variables['id_pedido'];
			$res3 = $this->db->insert('con_carro_rel_cupones_pedidos', $datos1);

			$sql = "SELECT SUM(con_carro_pedidos_items.subtotal) as subtotal";
			$sql .= " FROM con_carro_pedidos_items";
			$sql .= " WHERE con_carro_pedidos_items.id_con_car_pedido = ".$variables['id_pedido'];
			$sql .= " AND con_carro_pedidos_items.estado = 2";
			$query = $this->db->query($sql);
			$subtotal = $query->row_array();
			
			$sql = "SELECT SUM(con_carro_cupones.descuento) as descuento_total";
			$sql .= " FROM con_carro_cupones";
			$sql .= " LEFT JOIN con_carro_rel_cupones_pedidos ON con_carro_rel_cupones_pedidos.id_cupon = con_carro_cupones.id";
			$sql .= " WHERE con_carro_rel_cupones_pedidos.id_pedido = ".$variables['id_pedido'];
			$query = $this->db->query($sql);
			$descuento_total = $query->row_array();

			if($descuento_total['descuento_total'])
			{
				$datos['subtotal'] = $subtotal['subtotal'];
				$datos['descuento'] = ($datos['subtotal']*$descuento_total['descuento_total'])/100;
				//$datos['descuento_items'] = 10.00;
				$datos['total'] = $datos['subtotal']-$datos['descuento'];
				$datos['fecha_modificacion_utc'] = now();
				$datos['user_modificacion'] = $this->usuario->id;
				$where = "id = ".$variables['id_pedido'];
				$res3 = $this->db->update('con_carro_pedidos', $datos, $where);
			}
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function eliminarCuponPedido($variables)
	{
		$borrar = $this->db->where('id', $variables['id']);
		$res = $this->db->delete('con_carro_rel_cupones_pedidos'); 
		
		//CAMBIO STOCK
		$sql = "SELECT id, stock";
		$sql .= " FROM con_carro_cupones";
		$sql .= " WHERE id = ".$variables['id_cupon'];
		$query = $this->db->query($sql);
		$res2 = $query->row_array();
		
		if($res2['id'])
		{
			$datos10['stock'] = $res2['stock']+1;
			$where10 = "id = ".$variables['id_cupon'];
			$res10 = $this->db->update('con_carro_cupones', $datos10, $where10);
		}
		if ($this->sumarTotales($variables['id_pedido']))
		{
			return ($res);
		}
		else
		{
			return false;
		}		
	}

	//CAMBIAR ESTADO PEDIDO
	public function cambiarEstadoPedido($variables)
	{
		$datos['estado'] = $variables['estado'];
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where = "id = ".$variables['id'];
		$res = $this->db->update('con_carro_pedidos', $datos, $where);
		
		$sql = "SELECT id, estado";
		$sql .= " FROM con_carro_pedidos_items";
		$sql .= " WHERE id_con_car_pedido = ".$variables['id'];
		$query = $this->db->query($sql);
		$items = $query->row_array();

		if($items)
		{
			foreach($items as $item)
			{
				if(($variables['estado'] == 8) || ($variables['estado'] == 5))
				{
					$dato['estado'] = 1;
				}
				elseif(($variables['estado'] == 2) || ($variables['estado'] == 7))
				{
					$dato['estado'] = 2;
				}
				$dato['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
				$dato['user_modificacion'] = $this->usuario->id;
				$where1 = "id_con_car_pedido = ".$variables['id'];
				$update = $this->db->update('con_carro_pedidos_items', $dato, $where1);
			}
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
		$sql .= " AND con_carro_pedidos.estado != 7";

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function relacionarContactoPedido($valores)
	{
		if (!empty($valores['id_pedido']))
		{
			$sql = "SELECT con_carro_pedidos_items.id_producto";
			$sql .= " FROM con_carro_pedidos_items";
			$sql .= " WHERE con_carro_pedidos_items.id_con_car_pedido = ".$valores['id_pedido'];
			$sql .= " AND (con_carro_pedidos_items.estado = 2 || con_carro_pedidos_items.estado = 7)";
			$query = $this->db->query($sql);
			$items = $query->result_array();

			foreach($items as $item)
			{
				$sql = "SELECT id FROM con_rel_pedido_contactos";
				$sql .= " WHERE id_pedido = ".$valores['id_pedido'];
				$sql .= " AND id_contacto = ".$valores['id'];
				$sql .= " AND id_producto = ".$item['id_producto'];
				$query = $this->db->query($sql);
				$ingresado = $query->row_array();
	
				if(!$ingresado['id'])
				{
					$relacion['id_pedido'] = $valores['id_pedido'];
					$relacion['id_contacto'] = $valores['id'];
					$relacion['id_producto'] = $item['id_producto'];
					$insert = $this->db->insert('con_rel_pedido_contactos', $relacion);
				}
			}
		}
		return ($valores['id_pedido']);
	}

	public function eliminarContactoPedido($variables)
	{
		if (!empty($variables['id_pedido']))
		{
			$sql = "SELECT id FROM con_rel_pedido_contactos";
			$sql .= " WHERE id_pedido = ".$variables['id_pedido'];
			$sql .= " AND id_contacto = ".$variables['id'];
			$query = $this->db->query($sql);
			$ingresado = $query->row_array();

			if($ingresado['id'])
			{
				$borrar = $this->db->where('id', $ingresado['id']);
				$res = $this->db->delete('con_rel_pedido_contactos'); 
			}
		}
		return ($variables['id_pedido']);
	}

	public function getContactosPedido($id_pedido)
	{
		$sql = "SELECT contactos.nombre, contactos.apellido, contactos.id, contactos.email, contactos.estado as id_estado, contactos.ultima_visita,
				CASE
					WHEN contactos.estado = 1 THEN 'Inactivo'
					WHEN contactos.estado = 2 THEN 'Activo'
				END AS estado";
		$sql .= " FROM contactos";
		$sql .= " LEFT JOIN con_rel_pedido_contactos ON con_rel_pedido_contactos.id_contacto = contactos.id";
		$sql .= " WHERE con_rel_pedido_contactos.id_pedido = ".$id_pedido;
		$sql .= " GROUP BY contactos.id";
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	/* LISTADO DE ITEMS DE PEDIDO */
	public function listadoMisCursos($parametros)
	{
		if (isset($parametros['id_tipo']))
		{
			if($parametros['id_tipo'] == 2)
			{
				$sql1 = "SELECT contactos.id, contactos_extras.id_contacto_padre";
				$sql1 .= " FROM contactos";
				$sql1 .= " LEFT JOIN contactos_extras ON contactos_extras.id_contacto = contactos.id";
				$sql1 .= " WHERE contactos.id = ".$parametros['id_contacto'];
				$query1 = $this->db->query($sql1);
				$padre = $query1->row_array();

				$sql = "SELECT con_elearning_items.id_elearning, con_elearning_items.codigo, con_elearning_items.titulo, con_elearning_items.certificado as certificar, con_elearning_items.contenido1, con_elearning_items.precio_oferta, con_elearning_items.precio, con_elearning_items.uri, con_elearning_items.id_elearning as id_producto, con_elearning.id_categoria, con_elearning_categorias.categoria, con_carro_pedidos_items.id as id_item, con_rel_pedido_contactos.certificado, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
			}
			else
			{
				$sql = "SELECT con_elearning_items.id_elearning, con_elearning_items.codigo, con_elearning_items.titulo, con_elearning_items.certificado as certificar, con_elearning_items.contenido1, con_elearning_items.precio_oferta, con_elearning_items.precio, con_elearning_items.uri, con_elearning_items.id_elearning as id_producto, con_elearning.id_categoria, con_elearning_categorias.categoria, con_carro_pedidos_items.id as id_item, con_carro_pedidos_items.certificado, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
			}
		}
				
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_carro_pedidos_items ON con_carro_pedidos_items.id_producto = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_carro_pedidos ON con_carro_pedidos.id = con_carro_pedidos_items.id_con_car_pedido";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_elearning = con_elearning.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media";
		if (isset($parametros['id_tipo']))
		{
			if($parametros['id_tipo'] == 2)
			{
				$sql .= " LEFT JOIN con_rel_pedido_contactos ON con_rel_pedido_contactos.id_pedido = con_carro_pedidos.id";
			}
		}
		$sql .= " WHERE con_elearning.grupo = ?";
		$sql .= " AND con_elearning.id_empresa = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;

		if (isset($parametros['id_pedido']))
		{
			$sql .= " AND con_elearning.id_con_car_pedido = ?";
			$placeholders[] = $parametros['id_pedido'];
		}

		if (isset($parametros['id_contacto']))
		{
			if ( (isset($parametros['id_tipo'])) && ($parametros['id_tipo'] == 2) )
			{
				$sql .= " AND con_carro_pedidos.id_contacto = ?";
				$placeholders[] = $padre['id_contacto_padre'];

			}
			else
			{
				$sql .= " AND con_carro_pedidos.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
		}

		if ( (isset($parametros['id_tipo'])) && ($parametros['id_tipo'] == 2) )
		{
			$sql .= " AND con_rel_pedido_contactos.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		if (isset($parametros['estado']))
		{
			if ($parametros['estado'] == 0)
			{
				$sql .= " AND con_carro_pedidos.estado != 1";
				$sql .= " AND con_carro_pedidos.estado != 5";
				$sql .= " AND con_carro_pedidos.estado != 8";
			}
			else
			{
				$sql .= " AND con_carro_pedidos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
		}
		else
		{
			$sql .= " AND con_carro_pedidos_items.estado >= 0";
		}
		$sql .= " GROUP BY con_carro_pedidos_items.id";
		$sql .= " ORDER BY con_elearning_items.id ASC";

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	/* VERIFICAR CERTIFICADO */
	public function verificarCertificado($parametros)
	{
		$sql = "SELECT con_rel_pedido_contactos.*";
 		$sql .= " FROM con_rel_pedido_contactos";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_rel_pedido_contactos.id_producto";
		$sql .= " WHERE con_elearning.grupo = ?";
		$sql .= " AND con_elearning.id_empresa = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
 		$sql .= " AND con_rel_pedido_contactos.id_contacto = ?";
		$placeholders[] = $parametros['id_contacto'];
		$sql .= " AND con_rel_pedido_contactos.id_producto = ?";
		$placeholders[] = $parametros['id_producto'];
		$sql .= " ORDER BY id ASC LIMIT 1";

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function ingresarCertificado($variables)
	{
		if($variables['id_tipo'] == 2)
		{
			$sql = "SELECT con_rel_pedido_contactos.id, con_rel_pedido_contactos.id_producto, con_rel_pedido_contactos.certificado, con_carro_pedidos.id as id_pedido FROM con_carro_pedidos";
			$sql .= " LEFT JOIN con_rel_pedido_contactos ON con_rel_pedido_contactos.id_pedido = con_carro_pedidos.id";
			$sql .= " WHERE con_carro_pedidos.grupo = ?";
			$sql .= " AND con_carro_pedidos.id_empresa = ?";
			$sql .= " AND con_rel_pedido_contactos.id_contacto = ?";
			$sql .= " AND con_rel_pedido_contactos.id_producto = ?";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $variables['id_contacto'];
			$placeholders[] = $variables['id_producto'];
			$query = $this->db->query($sql, $placeholders);
			$items = $query->row_array();
			
			$datos['certificado'] = $variables['certificado'];
			$where = "id = ".$items['id'];
			$res = $this->db->update('con_rel_pedido_contactos', $datos, $where);
			return ($res);
		}
		else
		{
			$this->load->helper('date');
			$datos['certificado'] = $variables['certificado'];
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $variables['id_contacto'];
	
			$where = "id = ".$variables['id_item'];
			$res = $this->db->update('con_carro_pedidos_items', $datos, $where);
			return ($res);
		}
	}

//HASTA ACA USADAS






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
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;

		if ($parametros['id_pedido'] > 0)
		{
			$sql .= " AND id = ?";
			$placeholders[] = $parametros['id_pedido'];
		}
/*
		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}
*/

		if (isset($parametros['estado']))
		{
			if ($parametros['estado'] == 0)
			{
				$sql .= " AND (estado != 1 || estado != 5 || estado != 8)";
			}
			else
			{
				$sql .= " AND estado = ?";
				$placeholders[] = $parametros['estado'];
			}
		}
		else
		{
			$sql .= " AND (estado = 1 || estado = 5)";
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
		$sql = "SELECT con_carro_pedidos.*, contactos.nombre, contactos.apellido, contactos.email, contactos_extras.razon_social as empresa,";
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
		$sql .= " LEFT JOIN contactos_extras ON contactos_extras.id_contacto = con_carro_pedidos.id_contacto";		
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

	//MODIFICAR PEDIDO
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

	//REGISTRAR INGRESO A VIDEO
	public function registrarIngresoVideo($variables)
	{
		// Buscar el registro en con_rel_pedido_contactos
		$sql = "SELECT id FROM con_rel_pedido_contactos";
		$sql .= " WHERE id_pedido = ?";
		$sql .= " AND id_contacto = ?";
		$sql .= " AND id_producto = ?";
		$placeholders = array(
			$variables['id_pedido'],
			$variables['id_contacto'],
			$variables['id_producto']
		);
		$query = $this->db->query($sql, $placeholders);
		$registro = $query->row_array();

		if ($registro && $registro['id'])
		{
			// Actualizar fecha_ingreso_video solo si aún no está registrada
			$sql_check = "SELECT fecha_ingreso_video FROM con_rel_pedido_contactos WHERE id = ?";
			$query_check = $this->db->query($sql_check, array($registro['id']));
			$check = $query_check->row_array();
			
			if (!$check['fecha_ingreso_video'])
			{
				$datos['fecha_ingreso_video'] = date('Y-m-d H:i:s');
				$where = "id = " . $registro['id'];
				$res = $this->db->update('con_rel_pedido_contactos', $datos, $where);
				return ($res) ? array('id' => $registro['id'], 'actualizado' => 1) : array('error' => 'No se pudo actualizar');
			}
			else
			{
				return array('id' => $registro['id'], 'actualizado' => 0, 'mensaje' => 'Ya existe fecha de ingreso');
			}
		}
		else
		{
			return array('error' => 'No se encontró la relación pedido-contacto-producto');
		}
	}

	//OBTENER PROGRESO DE USUARIOS EN PEDIDO
	public function obtenerProgresoUsuarios($id_pedido)
	{
		$sql = "SELECT ";
		$sql .= " contactos.id as id_contacto,";
		$sql .= " contactos.nombre,";
		$sql .= " contactos.apellido,";
		$sql .= " contactos.email,";
		$sql .= " con_rel_pedido_contactos.id_producto,";
		$sql .= " con_rel_pedido_contactos.fecha_ingreso_video,";
		$sql .= " con_rel_pedido_contactos.fecha_completo_encuesta,";
		$sql .= " con_rel_pedido_contactos.certificado,";
		$sql .= " con_elearning_items.titulo as curso_titulo";
		$sql .= " FROM con_rel_pedido_contactos";
		$sql .= " LEFT JOIN contactos ON contactos.id = con_rel_pedido_contactos.id_contacto";
		$sql .= " LEFT JOIN con_elearning_items ON con_elearning_items.id_elearning = con_rel_pedido_contactos.id_producto";
		$sql .= " WHERE con_rel_pedido_contactos.id_pedido = ?";
		$sql .= " ORDER BY contactos.apellido ASC, contactos.nombre ASC";

		$placeholders = array($id_pedido);
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();

		return (!empty($res)) ? $res : null;
	}

}