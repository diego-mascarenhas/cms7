<?php defined('BASEPATH') or exit('No direct script access allowed');

class Tienda_model extends CI_Model {

	/* Usadas por la API */
	public function detalleConfiguracion($nombre = null, $id = null, $empresa = null)
	{
		$sql = "SELECT tienda_configuracion.*, tienda_configuracion.url AS dominio, IF(tienda_configuracion.url != '', CONCAT('https://', tienda_configuracion.url, '/'), grupos.url) AS url, empresas.empresa, tienda_monedas.simbolo, tienda_monedas.moneda as moneda_pais, tienda_monedas.nomenclatura";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN tienda_monedas ON tienda_monedas.id = tienda_configuracion.pais";
		$sql .= " LEFT JOIN empresas ON empresas.id = tienda_configuracion.id_empresa";
		$sql .= " LEFT JOIN grupos ON tienda_configuracion.grupo = grupos.id";
		if($nombre)
		{
			$sql .= " WHERE tienda_configuracion.titulo = '$nombre'";
		}
		elseif($id)
		{
			$sql .= " WHERE tienda_configuracion.id = $id";
		}
		else
		{
			$sql .= " WHERE tienda_configuracion.id_empresa = $empresa";
		}
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getTiendaDominio($nombre)
	{
		$sql = "SELECT id, titulo, privada";
		$sql .= " FROM tienda_configuracion";
		$sql .= " WHERE url = '$nombre'";
		$sql .= " AND estado > 0";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function verificarSiExiste($grupo, $username)
	{
		$sql = "SELECT * FROM contactos
				WHERE grupo = ?
				AND username = ?
				AND estado > 1
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $username;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}
	
	public function verificarContacto($valores)
	{
		$sql = "SELECT id, username, email, nombre, apellido, estado FROM contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = ?
			";
			
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $valores['empresa'];
		$placeholders[] = $valores['email'];

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function getContactoAdicionales($id)
	{
		$sql = "SELECT contactos_extras.* FROM contactos_extras";
		$sql .= " LEFT JOIN contactos ON contactos.id = contactos_extras.id_contacto";
		$sql .= " WHERE contactos.grupo = ?";
		$sql .= " AND contactos.estado > 0";
		$sql .= " AND contactos_extras.id_contacto = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			$sql .= " AND contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
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
	
	public function ingresarContactoAdicionales($id, $valores)
	{
		$data['id_contacto'] = $id;
		if (isset($valores['numero_cliente'])) $data['numero_cliente'] = (!empty($valores['numero_cliente'])) ? $valores['numero_cliente'] : null;
		if (isset($valores['id_condicion_iva'])) $data['id_condicion_iva'] = (!empty($valores['id_condicion_iva'])) ? $valores['id_condicion_iva'] : null;
		if (isset($valores['documento_tipo'])) $data['documento_tipo'] = (!empty($valores['documento_tipo'])) ? $valores['documento_tipo'] : null;
		if (isset($valores['documento'])) $data['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
		if (isset($valores['razon_social'])) $data['razon_social'] = (!empty($valores['razon_social'])) ? $valores['razon_social'] : null;
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['codigo_postal'])) $data['codigo_postal'] = (!empty($valores['codigo_postal'])) ? $valores['codigo_postal'] : null;
		if (isset($valores['id_localidad'])) $data['id_localidad'] = (!empty($valores['id_localidad'])) ? $valores['id_localidad'] : null;
/*
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['id_localidad'])) ? $valores['id_localidad'] : null;
		if (isset($valores['id_provincia'])) $data['provincia'] = (!empty($valores['id_provincia'])) ? $valores['id_provincia'] : null;
		if (isset($valores['id_pais'])) $data['pais'] = (!empty($valores['id_pais'])) ? $valores['id_pais'] : null;
*/
		if (isset($valores['domicilio_entrega'])) $data['domicilio_entrega'] = (!empty($valores['domicilio_entrega'])) ? $valores['domicilio_entrega'] : null;
		if (isset($valores['codigo_posta_entrega'])) $data['codigo_posta_entrega'] = (!empty($valores['codigo_posta_entrega'])) ? $valores['codigo_posta_entrega'] : null;
		if (isset($valores['id_localidad_entrega'])) $data['id_localidad_entrega'] = (!empty($valores['id_localidad_entrega'])) ? $valores['id_localidad_entrega'] : null;
/*
		if (isset($valores['localidad_entrega'])) $data['localidad_entrega'] = (!empty($valores['localidad_entrega'])) ? $valores['localidad_entrega'] : null;
		if (isset($valores['provincia_entrega'])) $data['provincia_entrega'] = (!empty($valores['provincia_entrega'])) ? $valores['provincia_entrega'] : null;
		if (isset($valores['pais_entrega'])) $data['pais_entrega'] = (!empty($valores['pais_entrega'])) ? $valores['pais_entrega'] : null;
*/
		if (isset($valores['condiciones'])) $data['condiciones'] = (!empty($valores['condiciones'])) ? $valores['condiciones'] : null;
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		if (!isset($res['error']))
		{
			$insert = $this->db->insert('contactos_extras', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}

	public function modificarContactoAdicionales($id, $valores)
	{
		if (isset($valores['numero_cliente'])) $data['numero_cliente'] = (!empty($valores['numero_cliente'])) ? $valores['numero_cliente'] : null;
		if (isset($valores['id_condicion_iva'])) $data['id_condicion_iva'] = (!empty($valores['id_condicion_iva'])) ? $valores['id_condicion_iva'] : null;
		if (isset($valores['documento_tipo'])) $data['documento_tipo'] = (!empty($valores['documento_tipo'])) ? $valores['documento_tipo'] : null;
		if (isset($valores['documento'])) $data['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
		if (isset($valores['razon_social'])) $data['razon_social'] = (!empty($valores['razon_social'])) ? $valores['razon_social'] : null;
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['codigo_postal'])) $data['codigo_postal'] = (!empty($valores['codigo_postal'])) ? $valores['codigo_postal'] : null;
		if (isset($valores['id_localidad'])) $data['id_localidad'] = (!empty($valores['id_localidad'])) ? $valores['id_localidad'] : null;
/*
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
		if (isset($valores['provincia'])) $data['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
*/
		if (isset($valores['domicilio_entrega'])) $data['domicilio_entrega'] = (!empty($valores['domicilio_entrega'])) ? $valores['domicilio_entrega'] : null;
		if (isset($valores['codigo_posta_entrega'])) $data['codigo_posta_entrega'] = (!empty($valores['codigo_posta_entrega'])) ? $valores['codigo_posta_entrega'] : null;
		if (isset($valores['id_localidad_entrega'])) $data['id_localidad_entrega'] = (!empty($valores['id_localidad_entrega'])) ? $valores['id_localidad_entrega'] : null;
/*
		if (isset($valores['localidad_entrega'])) $data['localidad_entrega'] = (!empty($valores['localidad_entrega'])) ? $valores['localidad_entrega'] : null;
		if (isset($valores['provincia_entrega'])) $data['provincia_entrega'] = (!empty($valores['provincia_entrega'])) ? $valores['provincia_entrega'] : null;
		if (isset($valores['pais_entrega'])) $data['pais_entrega'] = (!empty($valores['pais_entrega'])) ? $valores['pais_entrega'] : null;
*/
		if (isset($valores['condiciones'])) $data['condiciones'] = (!empty($valores['condiciones'])) ? $valores['condiciones'] : null;
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;

		if (!isset($res['error']))
		{
			$where = "id_contacto = ".$id;
			$res = $this->db->update('contactos_extras', $data, $where);
		}

		return (!empty($res)) ? $res : null;
	}

	public function getCategoriasPublic($id, $estado, $menu)
	{
		$sql = "SELECT tienda_productos_categorias.id, tienda_productos_categorias.categoria, tienda_productos_categorias.observaciones, tienda_productos_categorias.imagen, tienda_productos_categorias.orden";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if($menu == 1)
		{
			$sql .= " AND tienda_productos_categorias.delivery = 1";
		}
		else
		{
			$sql .= " AND tienda_productos_categorias.delivery >= 0";
		}
		
		$sql .= " AND tienda_productos_categorias.id_tienda = $id";
		$sql .= " AND tienda_productos_categorias.estado = $estado";
		$sql .= " ORDER BY tienda_productos_categorias.orden ASC, tienda_productos_categorias.id ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function getProductosPublic($tienda, $id_categoria, $menu)
	{
		if($menu == 1)
		{
			$sql = "SELECT tienda_productos.id, tienda_productos.titulo, tienda_productos.id_categoria, tienda_productos.contenido1, tienda_productos.stock, tienda_productos.codigo, tienda_productos.cantidad, tienda_productos.precio_local as precio, tienda_productos.precio_local_oferta as precio_oferta, tienda_productos.descuento, tienda_productos.imagen, tienda_productos_categorias.categoria";
		}
		else
		{
			$sql = "SELECT tienda_productos.id, tienda_productos.titulo, tienda_productos.id_categoria, tienda_productos.contenido1, tienda_productos.stock, tienda_productos.codigo, tienda_productos.cantidad, tienda_productos.precio, tienda_productos.precio_oferta, tienda_productos.descuento, tienda_productos.imagen, tienda_productos_categorias.categoria";
		}
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if($menu == 1)
		{
			$sql .= " AND tienda_productos.precio_local > 0";
		}
		$sql .= " AND precio > 0";
		$sql .= " AND tienda_productos_categorias.id_tienda = $tienda";

		if($id_categoria > 0)
		{
			$sql .= " AND tienda_productos_categorias.id = $id_categoria";
		}
		
		$sql .= " AND tienda_productos.estado = 3";
		$sql .= " ORDER BY tienda_productos.orden ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}
		
	public function getProductosPublicPedido($tienda, $id_categoria, $id_pedido)
	{
		$sql = "SELECT tienda_productos.id, tienda_productos.titulo, tienda_productos.id_categoria, tienda_productos.contenido1, tienda_productos.stock, tienda_productos.codigo, tienda_productos.cantidad, tienda_productos.precio, tienda_productos.precio_oferta, tienda_productos.descuento, tienda_productos.imagen, tienda_productos_categorias.categoria, (SELECT tienda_pedidos_items.cantidad FROM tienda_pedidos_items WHERE tienda_pedidos_items.id_producto = tienda_productos.id AND tienda_pedidos_items.id_pedido = $id_pedido) AS cantidad";
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND precio > 0";
		$sql .= " AND tienda_productos_categorias.id_tienda = $tienda";
		if($id_categoria > 0)
		{
			$sql .= " AND tienda_productos_categorias.id = $id_categoria";
		}
		$sql .= " AND tienda_productos.estado = 3";
		$sql .= " ORDER BY tienda_productos.orden ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}
	
	public function getDestacadosPublic($tienda)
	{
		$sql = "SELECT tienda_productos.id, tienda_productos.titulo, tienda_productos.imagen, tienda_productos.descuento, tienda_productos.precio, tienda_productos.precio_local_oferta, tienda_productos.id_categoria, tienda_productos_categorias.categoria";
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND tienda_productos_categorias.id_tienda = $tienda";
		$sql .= " AND tienda_productos.destacado = 1";
		$sql .= " AND tienda_productos.estado = 3";
		$sql .= " ORDER BY tienda_productos.orden ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}


	public function getMedias($parametros = null)
	{
		$sql = "SELECT media.nombre, media.archivo, media.estado AS id_estado, media.stream AS id_stream,
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 23 LIMIT 1) AS thumb
				FROM media
				LEFT JOIN media_rel_proyectos ON media_rel_proyectos.id_media = media.id
				LEFT JOIN media_proyectos ON media_rel_proyectos.id_proyecto = media_proyectos.id
				WHERE media.grupo = ?
			";
	
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND media.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}

		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}

		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND media.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND media.estado > 0";
			}
			if (!empty($parametros['proyecto']))
			{
				$sql .= " AND media_rel_proyectos.id_proyecto = ?";
				$placeholders[] = $parametros['proyecto'];
			}


			// group
			$sql .= " GROUP BY media.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " nombre";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

		
	public function getOpcionesPublic($tienda, $grupo)
	{
		$sql = "SELECT tienda_opciones.id, tienda_opciones.id_opcion_grupo, tienda_opciones_grupos.opcion_grupo, tienda_opciones_grupos.cantidad, tienda_opciones.opcion, tienda_opciones.precio, tienda_opciones.orden";
		$sql .= " FROM tienda_opciones";
		$sql .= " LEFT JOIN tienda_opciones_grupos ON tienda_opciones_grupos.id = tienda_opciones.id_opcion_grupo";
		$sql .= " WHERE tienda_opciones.id_opcion_grupo = $grupo";
		$sql .= " AND tienda_opciones.id_tienda = $tienda";
		$sql .= " AND tienda_opciones.estado = 2";
		$sql .= " ORDER BY tienda_opciones.orden ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function detallePedidoPublic($parametros)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.id_sucursal, tienda_pedidos.id_tienda_origen, tienda_pedidos.nombre, tienda_pedidos.celular, tienda_pedidos.email, tienda_pedidos.domicilio, tienda_pedidos.data, tienda_pedidos.observaciones, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_items, tienda_pedidos.envio, tienda_pedidos.total, tienda_medios_envios.medio_envio, tienda_formas_pago.forma_pago, tienda_sucursales.titulo as nombre_sucursal";
		$sql .= " FROM tienda_pedidos";
		$sql .= " LEFT JOIN tienda_medios_envios ON tienda_medios_envios.id = tienda_pedidos.id_medio_envio";
		$sql .= " LEFT JOIN tienda_formas_pago ON tienda_formas_pago.id = tienda_pedidos.id_forma_pago";
		$sql .= " LEFT JOIN tienda_sucursales ON tienda_sucursales.id = tienda_pedidos.id_sucursal";
		$sql .= " WHERE tienda_pedidos.id = ?";
		$sql .= " AND tienda_pedidos.estado = ?";
		$placeholders[] = $parametros['id'];
		$placeholders[] = $parametros['estado'];

		if(isset($parametros['id_contacto'])) 
		{
			$sql .= " AND tienda_pedidos.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return ($res);
	}


	public function detallePedidoPaypal($id)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.id_sucursal, tienda_pedidos.id_tienda_origen, tienda_pedidos.nombre, tienda_pedidos.celular, tienda_pedidos.email, tienda_pedidos.domicilio, tienda_pedidos.data, tienda_pedidos.observaciones, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_items, tienda_pedidos.envio, tienda_pedidos.total";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id = $id";
		$sql .= " AND tienda_pedidos.estado > 7";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleFinalizarPedidoPublic($id, $estado)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.id_sucursal, tienda_pedidos.id_tienda_origen, tienda_pedidos.numero_mesa, tienda_pedidos.nombre, tienda_pedidos.celular, tienda_pedidos.email, tienda_pedidos.domicilio, tienda_pedidos.observaciones, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_medios_envio, tienda_pedidos.descuento_items, tienda_pedidos.envio, tienda_pedidos.total, tienda_medios_envios.medio_envio, tienda_formas_pago.forma_pago, tienda_sucursales.titulo as nombre_sucursal, tienda_sucursales.email as tienda_email, tienda_sucursales.recibir_email, tienda_sucursales.celular as whatsapp, tienda_monedas.simbolo";
		$sql .= " FROM tienda_pedidos";
		$sql .= " LEFT JOIN tienda_medios_envios ON tienda_medios_envios.id = tienda_pedidos.id_medio_envio";
		$sql .= " LEFT JOIN tienda_formas_pago ON tienda_formas_pago.id = tienda_pedidos.id_forma_pago";
		$sql .= " LEFT JOIN tienda_sucursales ON tienda_sucursales.id = tienda_pedidos.id_sucursal";
		$sql .= " LEFT JOIN tienda_monedas ON tienda_monedas.id = tienda_sucursales.pais";
		$sql .= " WHERE tienda_pedidos.id = $id";
		$sql .= " AND tienda_pedidos.estado = $estado";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function datosMP($id_pedido)
	{
		$sql = "SELECT tienda_configuracion.titulo, tienda_configuracion.clienteMP, tienda_configuracion.claveMP, tienda_configuracion.email, tienda_configuracion.email_paypal";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN tienda_pedidos ON tienda_pedidos.id_tienda = tienda_configuracion.id";
		$sql .= " WHERE tienda_pedidos.id = $id_pedido";
		$sql .= " AND tienda_pedidos.estado = 3";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}
	/* Fin Usadas por la API */


	// Categorias
	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT tienda_configuracion.id, tienda_configuracion.titulo, tienda_configuracion.logo, tienda_configuracion.domicilio, tienda_configuracion.numero, tienda_configuracion.localidad, tienda_configuracion.provincia, empresas.empresa, servicios.id_categoria, tienda_rubros.rubro";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN empresas ON empresas.id = tienda_configuracion.id_empresa";
		$sql .= " LEFT JOIN servicios ON servicios.id = tienda_configuracion.id_servicio";
		$sql .= " LEFT JOIN tienda_rubros ON tienda_rubros.id = tienda_configuracion.id_rubro";
		$sql .= " LEFT JOIN categorias_generales ON categorias_generales.id = servicios.id_categoria";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['idioma']))
			{
				if($parametros['tipo'] == 1) 
				{
					switch($parametros['idioma'])
					{
						case 'ar': $sql .= " AND servicios.id_categoria = 2101"; break; //ARGENTINA
						case 'uy': $sql .= " AND servicios.id_categoria = 2103"; break; //URUGUAY
						case 'bo': $sql .= " AND servicios.id_categoria = 2104"; break; //BOLIVIA 
						case 'py': $sql .= " AND servicios.id_categoria = 2105"; break; //PARAGUAY 
						case 'pe': $sql .= " AND servicios.id_categoria = 2106"; break; //PERU 
						case 'ec': $sql .= " AND servicios.id_categoria = 2108"; break; //ECUADOR 
			 			case 'cl': $sql .= " AND servicios.id_categoria = 2107"; break; //CHILE 
						case 'co': $sql .= " AND servicios.id_categoria = 2109"; break; //COLOMBIA 
						case 'mx': $sql .= " AND servicios.id_categoria = 2110"; break; //MEXICO 
						case 'es': $sql .= " AND servicios.id_categoria = 2119"; break; //ESPANA 
					}
				}
				else
				{
					switch($parametros['idioma'])
					{
						case 'ar': $sql .= " AND servicios.id_categoria = 2102"; break; //ARGENTINA
						case 'uy': $sql .= " AND servicios.id_categoria = 2111"; break; //URUGUAY
						case 'bo': $sql .= " AND servicios.id_categoria = 2112"; break; //BOLIVIA 
						case 'py': $sql .= " AND servicios.id_categoria = 2113"; break; //PARAGUAY 
						case 'pe': $sql .= " AND servicios.id_categoria = 2114"; break; //PERU 
						case 'ec': $sql .= " AND servicios.id_categoria = 2116"; break; //ECUADOR 
			 			case 'cl': $sql .= " AND servicios.id_categoria = 2115"; break; //CHILE 
						case 'co': $sql .= " AND servicios.id_categoria = 2117"; break; //COLOMBIA 
						case 'mx': $sql .= " AND servicios.id_categoria = 2118"; break; //MEXICO 
						case 'es': $sql .= " AND servicios.id_categoria = 2120"; break; //ESPANA 
					}
				}
			}

			if (!empty($parametros['estado']))
			{
				$sql .= " AND tienda_configuracion.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tienda_configuracion.estado > 0";
			}
									
			if (!empty($parametros['categoria']))
			{
				$sql .= " AND tienda_configuracion.id_rubro = ?";
				$placeholders[] = $parametros['categoria'];
			}

/*
			if (!empty($parametros['producto']))
			{
				$sql .= " AND tienda_productos_categorias.categoria LIKE '%?%'";
				$placeholders[] = $parametros['producto'];
			}
*/

			// orden
			$sql .= " AND empresas.estado = 3";
			$sql .= " ORDER BY tienda_configuracion.titulo ASC";
				
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		
		return (!empty($res)) ? $res : null;
	}
	

	public function getBusquedaTienda($parametros = null)
	{
		$sql = "SELECT tienda_productos.*, tienda_productos_categorias.categoria";
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " WHERE tienda_productos_categorias.id_tienda = ".$parametros['tienda'];
		$sql .= " AND tienda_productos.estado = ".$parametros['estado'];
		$sql .= " AND tienda_productos.titulo LIKE '%".$parametros['busqueda']."%'";
		$sql .= " ORDER BY tienda_productos.titulo ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	// Categorias
	public function getCategorias($parametros = null)
	{
		$sql = "SELECT tienda_productos_categorias.*";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND tienda_productos_categorias.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tienda_productos_categorias.estado > 0";
			}
									
			// orden
			$sql .= " ORDER BY tienda_productos_categorias.orden ASC";
				
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getOpciones($parametros = null)
	{
		$sql = "SELECT tienda_opciones.id, tienda_opciones.id_opcion_grupo, tienda_opciones.opcion, tienda_opciones.precio, tienda_opciones.orden, tienda_opciones.estado";
		$sql .= " FROM tienda_opciones";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_opciones.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND tienda_opciones.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tienda_opciones.estado > 0";
			}
									
			$sql .= " AND tienda_opciones.id_opcion_grupo = ".$parametros['grupo'];
			$sql .= " AND tienda_opciones.id_tienda = ".$parametros['tienda'];
			
			// orden
			$sql .= " ORDER BY tienda_opciones.id ASC";

			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getPedidos($parametros)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.id_tienda_origen, DATE_FORMAT(tienda_pedidos.fecha_alta, '%d-%m-%Y') as fecha_alta, tienda_pedidos.total, tienda_pedidos.estado";
		$sql .= " FROM tienda_pedidos";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_pedidos.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND tienda_configuracion.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND tienda_pedidos.id_tienda = ?";
		$sql .= " AND tienda_pedidos.id_contacto = ?";
		$placeholders[] = $parametros['id_tienda'];
		$placeholders[] = $parametros['id_contacto'];

		$sql .= " AND tienda_pedidos.estado > 0";
		$sql .= " ORDER BY tienda_pedidos.id ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function listadoProductos($tienda, $id_categoria)
	{
		$sql = "SELECT tienda_productos.*, tienda_productos_categorias.categoria";
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " WHERE tienda_productos_categorias.id_tienda = $tienda";
		$sql .= " AND tienda_productos_categorias.id = $id_categoria";
		$sql .= " AND tienda_productos.estado = 3";
		$sql .= " ORDER BY tienda_productos.orden ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function getGrupos($parametros = null)
	{
		$sql = "SELECT tienda_opciones_grupos.id, tienda_opciones_grupos.opcion_grupo, tienda_opciones_grupos.cantidad, tienda_opciones_grupos.orden, tienda_opciones_grupos.estado";
		$sql .= " FROM tienda_opciones_grupos";
		if (!empty($parametros['producto']))
		{
			$sql .= " LEFT JOIN tienda_producto_rel_opciones_grupo ON tienda_producto_rel_opciones_grupo.id_opcion_grupo = tienda_opciones_grupos.id";
		}
		if (!empty($parametros['public']))
		{
			$sql .= " LEFT JOIN tienda_opciones ON tienda_opciones.id_opcion_grupo = tienda_opciones_grupos.id";
		}
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_opciones_grupos.id_tienda";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		if (!empty($parametros['producto']))
		{
			$sql .= " AND tienda_producto_rel_opciones_grupo.id_producto = ?";
			$sql .= " AND tienda_producto_rel_opciones_grupo.id_tienda = ".$parametros['tienda'];
			$placeholders[] = $parametros['producto'];
		}
		if (!empty($parametros['public']))
		{
			$sql .= " AND tienda_opciones.id_tienda =  ".$parametros['tienda'];
		}
		
		$sql .= " AND tienda_opciones_grupos.id_tienda = ".$parametros['tienda'];
 		$sql .= " AND tienda_opciones_grupos.estado > ".$parametros['estado'];
		$sql .= " ORDER BY tienda_opciones_grupos.orden ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	//DETALLE PAIS
	public function detallePais($pais)
	{
		$sql = "SELECT id, moneda, simbolo, nomenclatura";
		$sql .= " FROM tienda_monedas";
		$sql .= " WHERE extension = '$pais'";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		return (!empty($res)) ? $res : null;
	}

	public function listadoPaises()
	{
		$sql = "SELECT id, moneda";
		$sql .= " FROM tienda_monedas";
		$sql .= " WHERE grupo = ?";
		$sql .= " ORDER BY id ASC";
		$placeholders[] = $this->usuario->grupo;
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();

		$paises[0] = 'Seleccione pais';
		foreach ($res as $obj => $valor)
		{
			$paises[$valor['id']] = $valor['moneda'];
		}
		return ($paises);
	}

	//TRAIGO PROVINCIAS PARA EL BUSCADOR DEL HOME SI ESTA ACTIVA LA TIENDA Y ACTIVO EL SERVICIO
	public function listadoProvincias($pais)
	{
		$sql = "SELECT tienda_configuracion.id, tienda_configuracion.provincia";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN servicios ON servicios.id = tienda_configuracion.id_servicio";
		$sql .= " WHERE tienda_configuracion.pais = $pais";
		$sql .= " AND tienda_configuracion.estado = 3";
		$sql .= " AND servicios.estado = 2";
		$sql .= " GROUP BY tienda_configuracion.provincia";
		$sql .= " ORDER BY tienda_configuracion.provincia ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	//TRAIGO LOCALIDADES PARA EL AUTOCOMPLETE DEL HOME SI ESTA ACTIVA LA TIENDA Y ACTIVO EL SERVICIO
	public function listadoLocalidades($pais)
	{
		$sql = "SELECT tienda_configuracion.id, tienda_configuracion.localidad";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN servicios ON servicios.id = tienda_configuracion.id_servicio";
		$sql .= " WHERE tienda_configuracion.pais = $pais";
		$sql .= " AND tienda_configuracion.estado = 3";
		$sql .= " AND servicios.estado = 3";
		$sql .= " ORDER BY tienda_configuracion.provincia ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function listadoRubros($idioma)
	{
		$sql = "SELECT tienda_rubros.id, tienda_rubros.rubro";
		$sql .= " FROM tienda_rubros";
		$sql .= " WHERE tienda_rubros.idioma = '$idioma'";
		$sql .= " AND tienda_rubros.estado = 3";
		$sql .= " ORDER BY tienda_rubros.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$rubro[$valor['id']] = $valor['rubro'];
		}
		return ($rubro);
	}


	public function listadoPlanes($pais)
	{
		$sql = "SELECT id, categoria, descripcion, caracteristicas";
		$sql .= " FROM categorias_generales";
		$sql .= " WHERE grupo = ?";
		$placeholders[] = $this->usuario->grupo;
		$sql .= " AND id_tipo = 20";
		$sql .= " AND estado = 2";

		switch($pais)
		{
			case 1: $sql .= " AND (id = 2101 OR id = 2102 OR id = 3092)";break; //ARGENTINA
			case 2: $sql .= " AND (id = 2103 OR id = 2111 OR id = 3096)";break; //URUGUAY
			case 3: $sql .= " AND (id = 2104 OR id = 2112 OR id = 3098)";break; //BOLIVIA 
			case 4: $sql .= " AND (id = 2105 OR id = 2113 OR id = 3100)";break; //PARAGUAY 
			case 5: $sql .= " AND (id = 2106 OR id = 2114 OR id = 3102)";break; //PERU 
			case 6: $sql .= " AND (id = 2108 OR id = 2116 OR id = 3106)";break; //ECUADOR 
 			case 7: $sql .= " AND (id = 2107 OR id = 2115 OR id = 3104)";break; //CHILE 
			case 8: $sql .= " AND (id = 2109 OR id = 2117 OR id = 3108)";break; //COLOMBIA 
			case 9: $sql .= " AND (id = 2110 OR id = 2118 OR id = 3110)";break; //MEXICO 
			case 10: $sql .= " AND (id = 2119 OR id = 2120 OR id = 3094)";break; //ESPANA 
		}
		$sql .= " ORDER BY id ASC";
		
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function listadoCuponesPedido($id_pedido)
	{
		$sql = "SELECT tienda_rel_cupones_pedidos.*, tienda_cupones.cupon ";
		$sql .= " FROM tienda_rel_cupones_pedidos";
		$sql .= " LEFT JOIN tienda_cupones ON tienda_cupones.id = tienda_rel_cupones_pedidos.id_cupon";
		$sql .= " WHERE tienda_rel_cupones_pedidos.id_pedido = $id_pedido";
		$sql .= " AND tienda_rel_cupones_pedidos.id_cupon = tienda_cupones.id";
		$sql .= " ORDER BY tienda_cupones.cupon ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoSucursales($id_tienda)
	{
		$sql = "SELECT id, titulo, domicilio, numero, localidad, provincia, pais, celular, recibir_email, orden, email";
		$sql .= " FROM tienda_sucursales";
		$sql .= " WHERE id_tienda = $id_tienda";
		$sql .= " AND estado = 2";
		$sql .= " ORDER BY titulo ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoFormasPago($id_tienda)
	{
		$sql = "SELECT tienda_formas_pago.*, tienda_rel_forma_pago_tienda.tipo, tienda_rel_forma_pago_tienda.descuento, tienda_rel_forma_pago_tienda.recargo";
		$sql .= " FROM tienda_formas_pago";
		$sql .= " LEFT JOIN tienda_rel_forma_pago_tienda ON tienda_rel_forma_pago_tienda.id_forma_pago = tienda_formas_pago.id";
		$sql .= " WHERE tienda_rel_forma_pago_tienda.id_tienda = $id_tienda";
		$sql .= " AND tienda_formas_pago.estado = 3";
		$sql .= " ORDER BY tienda_rel_forma_pago_tienda.id ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoMediosPago()
	{
		$sql = "SELECT tienda_formas_pago.*";
		$sql .= " FROM tienda_formas_pago";
		$sql .= " WHERE tienda_formas_pago.estado = 3";
		$sql .= " ORDER BY tienda_formas_pago.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoEnviosTienda($id_tienda)
	{
		$sql = "SELECT tienda_medios_envios.*, tienda_rel_envios_tienda.tipo, tienda_rel_envios_tienda.descuento, tienda_rel_envios_tienda.mmc,  tienda_rel_envios_tienda.recargo, tienda_rel_envios_tienda.orden";
		$sql .= " FROM tienda_medios_envios";
		$sql .= " LEFT JOIN tienda_rel_envios_tienda ON tienda_rel_envios_tienda.id_envio = tienda_medios_envios.id";
		$sql .= " WHERE tienda_rel_envios_tienda.id_tienda = $id_tienda";
		$sql .= " AND tienda_medios_envios.estado = 3";
		$sql .= " ORDER BY tienda_rel_envios_tienda.id ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoMediosEnvios()
	{
		$sql = "SELECT tienda_medios_envios.*";
		$sql .= " FROM tienda_medios_envios";
		$sql .= " WHERE tienda_medios_envios.estado = 3";
		$sql .= " ORDER BY tienda_medios_envios.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoPedidos($id_tienda, $periodicidad = null, $estado = null)
	{
		$day = date('Y-m-d');
		$tomorrow = date('Y-m-d',strtotime($day.'+ 1 day'));
		$week = date('Y-m-d',strtotime($day.'- 7 day'));
		$mes = date('m');

		$sql = "SELECT tienda_pedidos.*, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.email) AS contacto,";
		$sql .= " CASE
						WHEN tienda_pedidos.estado = 1 THEN 'Ingresado'
						WHEN tienda_pedidos.estado = 2 THEN 'Solicitado sin pagar'
						WHEN tienda_pedidos.estado = 3 THEN 'Pendiente Mercado Pago'
						WHEN tienda_pedidos.estado = 4 THEN 'Cancelado'
						WHEN tienda_pedidos.estado = 5 THEN 'Solicitado/Pagado MP'
						WHEN tienda_pedidos.estado = 6 THEN 'En proceso para entregar'
						WHEN tienda_pedidos.estado = 7 THEN 'Entregado al cliente'
						WHEN tienda_pedidos.estado = 8 THEN 'En proceso de Pago PayPal'
						WHEN tienda_pedidos.estado = 9 THEN 'Solicitado/Pagado PayPal'
						WHEN tienda_pedidos.estado = 10 THEN 'Cancelado PayPal'
						WHEN tienda_pedidos.estado = 11 THEN 'Pagado otros medios'

					END AS tipo_estado";
		$sql .= " FROM tienda_pedidos";
		$sql .= " LEFT JOIN contactos ON contactos.id = tienda_pedidos.id_contacto ";
		$sql .= " WHERE tienda_pedidos.id_tienda = $id_tienda";
		if($periodicidad)
		{
			switch($periodicidad)
			{
				case 'diario': $sql .= " AND DATE(tienda_pedidos.fecha_alta) = '$day'"; break;
				case 'semanal': $sql .= " AND tienda_pedidos.fecha_alta < '$tomorrow' AND tienda_pedidos.fecha_alta > '$week'"; break;
				case 'mensual': $sql .= " AND MONTH(tienda_pedidos.fecha_alta) = '$mes'"; break;
			}
		}

		if($estado)
		{
			switch($estado)
			{
				case 'recibidos': $sql .= " AND tienda_pedidos.estado > 0"; break;
				case 'entregados': $sql .= " AND tienda_pedidos.estado = 7"; break;
				case 'pendientes': $sql .= " AND (tienda_pedidos.estado != 1 && tienda_pedidos.estado != 7)"; break;
				case 'cancelados': $sql .= " AND tienda_pedidos.estado = 8"; break;
			}
		}
		else
		{
			$sql .= "  AND tienda_pedidos.estado > 1";
		}
		
		$sql .= " ORDER BY tienda_pedidos.fecha_alta DESC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	
	public function totalFacturacionMes($mes, $id_tienda, $estado = null)
	{
		$sql = "SELECT sum(total) as total";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE MONTH(fecha_alta) = '$mes'";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";
		if($estado)
		{
			$sql .= " AND tienda_pedidos.estado = $estado";
		}
		else
		{
			$sql .= " AND tienda_pedidos.estado > 1";
		}

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function porcentajesFacturasMes($mes, $id_tienda)
	{
		$yesterday = date('m', strtotime('-1 month'));

		$anterior = $this->totalFacturacionMes($yesterday, $id_tienda);
		$actual = $this->totalFacturacionMes($mes, $id_tienda);
		$porcentaje = $actual['total']-$anterior['total'];		
		$porcentaje = round($porcentaje*$anterior['total']/100, 2);
		return($porcentaje);
	}

	public function totalPedidosMes($fecha, $id_tienda)
	{
		$sql = "SELECT COUNT(*) as total, fecha_alta";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE MONTH(fecha_alta) = '$fecha'";
		$sql .= " AND tienda_pedidos.estado > 1";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalPedidosMesEstado($estado, $fecha, $id_tienda)
	{
		$sql = "SELECT COUNT(*) as total, fecha_alta";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE estado = $estado";
		$sql .= " AND MONTH(fecha_alta) = '$fecha'";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";

		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalPedidosDia($fecha, $id_tienda)
	{
		$sql = "SELECT COUNT(*) as total, fecha_alta";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE DATE(fecha_alta) = '$fecha'";
		$sql .= " AND tienda_pedidos.estado > 1";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalPedidosEstado($estado, $fecha, $id_tienda)
	{
		$sql = "SELECT COUNT(*) as total, fecha_alta";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE estado = $estado";
		$sql .= " AND DATE(fecha_alta) = '$fecha'";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function porcentajesPedidosMes($estado = null, $id_tienda)
	{
		$day = date('m');
		$yesterday = date('m', strtotime('-1 month'));
		
		//SI TRAE ESTADO
		if ($estado)
		{
			$ayer = $this->totalPedidosMesEstado($estado, $yesterday, $id_tienda);
			$hoy = $this->totalPedidosMesEstado($estado, $day, $id_tienda);
			$porcentaje = $hoy['total']-$ayer['total'];
			$porcentaje = $porcentaje*$ayer['total']/100;
		}
		else
		{
			$ayer = $this->totalPedidosMes($yesterday, $id_tienda);
			$hoy = $this->totalPedidosMes($day, $id_tienda);
			$porcentaje = $hoy['total']-$ayer['total'];
			$porcentaje = $porcentaje*$ayer['total']/100;
		}		
		return($porcentaje);
	}

	public function porcentajesPedidos($estado = null, $id_tienda)
	{
		$day = date('Y-m-d');
		$yesterday = date('Y-m-d',strtotime($day.'- 1 day'));
		
		//SI TRAE ESTADO
		if ($estado)
		{
			$ayer = $this->totalPedidosEstado($estado, $yesterday, $id_tienda);
			$hoy = $this->totalPedidosEstado($estado, $day, $id_tienda);
			$porcentaje = $hoy['total']-$ayer['total'];
			$porcentaje = $porcentaje*$ayer['total']/100;
		}
		else
		{
			$ayer = $this->totalPedidosDia($yesterday, $id_tienda);
			$hoy = $this->totalPedidosDia($day, $id_tienda);
			$porcentaje = $hoy['total']-$ayer['total'];
			$porcentaje = $porcentaje*$ayer['total']/100;
		}		
		return($porcentaje);
	}
	
	public function totalClientesMes($fecha, $id_tienda)
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM contactos";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id_empresa = contactos.id_empresa";
		$sql .= " WHERE contactos.grupo = ?";
		$sql .= " AND contactos.id_empresa = ?";
		$sql .= " AND contactos.area_privada = 5";
		$sql .= " AND MONTH(contactos.fecha_alta) = '$fecha'";
		$sql .= " AND contactos.estado > 0";
		$sql .= " AND tienda_configuracion.id = $id_tienda";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleProducto($id, $estado, $menu = null)
	{
		if($menu == 1)
		{
			$sql = "SELECT tienda_productos.id, tienda_productos.imagen, tienda_productos.titulo, tienda_productos.uri, tienda_productos.contenido1, tienda_productos.contenido2, tienda_productos.stock, tienda_productos.cantidad, tienda_productos.codigo, tienda_productos.estado,tienda_productos.precio_local as precio, tienda_productos.precio_local_oferta as precio_oferta, tienda_productos.cantidad, tienda_productos.orden, tienda_productos.galeria, tienda_productos.destacado, tienda_productos_categorias.id as id_categoria, tienda_productos_categorias.categoria";
		}
		else
		{
			$sql = "SELECT tienda_productos.id, tienda_productos.imagen, tienda_productos.titulo, tienda_productos.uri, tienda_productos.contenido1, tienda_productos.contenido2, tienda_productos.stock, tienda_productos.cantidad, tienda_productos.codigo, tienda_productos.estado, tienda_productos.precio, tienda_productos.precio_oferta, tienda_productos.precio_local, tienda_productos.precio_local_oferta, tienda_productos.cantidad, tienda_productos.orden, tienda_productos.galeria, tienda_productos.destacado,tienda_productos_categorias.id as id_categoria, tienda_productos_categorias.categoria";
		}
		$sql .= " FROM tienda_productos";
		$sql .= " LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria";
		$sql .= " WHERE tienda_productos.id =$id";
		
		
		if($estado == 3)
		{
			$sql .= " AND tienda_productos.estado = 3";
		}
		else
		{
			$sql .= " AND tienda_productos.estado > 0";
		}

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleContactoFromUsername($username)
	{
		$sql = "SELECT contactos.id, contactos.nombre, contactos.celular, contactos.apellido, contactos.email, contactos.username, contactos.password, empresas.empresa";
		$sql .= " FROM contactos";
		$sql .= " LEFT JOIN empresas ON empresas.id = contactos.id_empresa";
		$sql .= " WHERE contactos.username ='$username'";
		$sql .= " AND contactos.estado > 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function detallePedido($id, $estado = null)
	{
		$sql = "SELECT tienda_pedidos.*, ";
		$sql .= " CASE
						WHEN tienda_pedidos.estado = 1 THEN 'Ingresado'
						WHEN tienda_pedidos.estado = 2 THEN 'Solicitado sin pagar'
						WHEN tienda_pedidos.estado = 3 THEN 'Pendiente Mercado Pago'
						WHEN tienda_pedidos.estado = 4 THEN 'Cancelado'
						WHEN tienda_pedidos.estado = 5 THEN 'Solicitado/Pagado MP'
						WHEN tienda_pedidos.estado = 6 THEN 'En proceso para entregar'
						WHEN tienda_pedidos.estado = 7 THEN 'Entregado al cliente'
						WHEN tienda_pedidos.estado = 8 THEN 'En proceso de Pago PayPal'
						WHEN tienda_pedidos.estado = 9 THEN 'Solicitado/Pagado PayPal'
						WHEN tienda_pedidos.estado = 10 THEN 'Cancelado PayPal'
						WHEN tienda_pedidos.estado = 11 THEN 'Pagado otros medios'
					END AS tipo_estado";

		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id = $id";
		if($estado)
		{
			$sql .= " AND tienda_pedidos.estado = estado";
		}

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detallePedidoCookie($cookie, $id_tienda)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.cookie, tienda_pedidos.estado, tienda_pedidos.total";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.cookie = '".$cookie."'";
		$sql .= " AND tienda_pedidos.id_tienda = $id_tienda";
		$sql .= " AND tienda_pedidos.estado = 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detallePedidoItems($id_pedido)
	{
		$sql = "SELECT tienda_pedidos_items.id, tienda_pedidos_items.id_producto, tienda_pedidos_items.detalle, tienda_pedidos_items.cantidad, tienda_pedidos_items.subtotal, tienda_pedidos_items.username_alta, tienda_productos.precio, tienda_productos.precio_local, tienda_productos.titulo, tienda_productos.codigo, tienda_productos.contenido1, tienda_productos.imagen";
		$sql .= " FROM tienda_pedidos_items";
		$sql .= " LEFT JOIN tienda_productos ON tienda_productos.id = tienda_pedidos_items.id_producto";
		$sql .= " WHERE tienda_pedidos_items.id_pedido = $id_pedido";
		$sql .= " AND tienda_pedidos_items.estado = 3";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	
	public function detallePedidoItemsPublic($id_pedido, $menu)
	{
		if($menu == 1)
		{
			$sql = "SELECT tienda_productos.titulo as Producto, tienda_productos.codigo, tienda_productos.precio, tienda_productos.precio_oferta, tienda_pedidos_items.subtotal as precio_item_pedido, tienda_pedidos_items.cantidad, tienda_pedidos_items.detalle as opciones";
		} 
		else
		{
			$sql = "SELECT tienda_productos.titulo as Producto, tienda_productos.codigo, tienda_productos.precio_local as precio, tienda_productos.precio_local_oferta as precio_oferta, tienda_pedidos_items.subtotal as precio_item_pedido, tienda_pedidos_items.cantidad, tienda_pedidos_items.detalle as opciones";
		}
		$sql .= " FROM tienda_pedidos_items";
		$sql .= " LEFT JOIN tienda_productos ON tienda_productos.id = tienda_pedidos_items.id_producto";
		$sql .= " WHERE tienda_pedidos_items.id_pedido = $id_pedido";
		$sql .= " AND tienda_pedidos_items.estado = 3";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function cantidadPedidoItems($id_pedido)
	{
		$sql = "SELECT COUNT(tienda_pedidos_items.id) as cantidad";
		$sql .= " FROM tienda_pedidos_items";
		$sql .= " WHERE tienda_pedidos_items.id_pedido = $id_pedido";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleCupon($cupon, $id_tienda)
	{
		$sql = "SELECT tienda_cupones.id, tienda_cupones.cupon";
		$sql .= " FROM tienda_cupones";
		$sql .= " WHERE tienda_cupones.cupon ='".$cupon."'";
		$sql .= " AND tienda_cupones.id_tienda = $id_tienda";
		$sql .= " AND tienda_cupones.fecha_vencimiento > NOW()";
		$sql .= " AND tienda_cupones.estado = 3";
		$sql .= " AND tienda_cupones.cantidad > 0";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleSucursal($id)
	{
		$sql = "SELECT tienda_sucursales.*";
		$sql .= " FROM tienda_sucursales";
		$sql .= " WHERE tienda_sucursales.id = $id";
		$sql .= " AND tienda_sucursales.estado > 0";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarTienda($variables)
	{
		//INGRESO EMPRESA
		$data['grupo'] = $this->usuario->grupo;
		$data['empresa'] = $variables['username'];
		$data['referido'] = 7459;
		$data['pais'] = $variables['pais_empresa'];
		$data['telefono'] = $variables['celular'];
		if (isset($variables['id_categoria'])) $data['id_categoria'] = (!empty($variables['id_categoria'])) ? $variables['id_categoria'] : null;
		if (isset($variables['web'])) $data['web'] = (!empty($variables['web'])) ? $variables['web'] : null;
		if (isset($variables['observaciones'])) $data['observaciones'] = (!empty($variables['observaciones'])) ? $variables['observaciones'] : null;
		if (isset($variables['estado'])) $data['estado'] = (!empty($variables['estado'])) ? $variables['estado'] : 3;
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = 'pedimos';

		if (!isset($res['error']))
		{
			$insert = $this->db->insert('empresas', $data);
			$res['id'] = $this->db->insert_id();
		}
		
		//SI INGRESA EMPRESA
		if ($res['id'])
		{
			//INGRESO EMPRESA FISCAL
			$datafiscal['grupo'] = $this->usuario->grupo;
			$datafiscal['id_empresa'] = $res['id'];
			$datafiscal['razon_social'] = $variables['nombre'].' '.$variables['apellido'];
			if (isset($variables['ingresos_brutos'])) $datafiscal['ingresos_brutos'] = (!empty($variables['ingresos_brutos'])) ? $variables['ingresos_brutos'] : null;
			$datafiscal['pais'] = $variables['pais_empresa'];
			if (isset($variables['estado'])) $datafiscal['estado'] = (!empty($variables['estado'])) ? $variables['estado'] : 1;
			$datafiscal['fecha_alta'] = unix_to_human(now(), true, 'eu');
			$datafiscal['username_alta'] = 'pedimos';
	
			$insert = $this->db->insert('empresas_fiscales', $datafiscal);

			//INGRESO CONTACTO
			$datacontacto['grupo'] = $this->usuario->grupo;
			$datacontacto['id_empresa'] = $res['id'];
			$datacontacto['nombre'] = $variables['nombre'];
			$datacontacto['apellido'] = $variables['apellido'];
			$datacontacto['celular'] = $variables['celular'];
			$datacontacto['email'] = $variables['email'];
			$datacontacto['area_privada'] = $variables['area_privada'];
			$datacontacto['username'] = $variables['username'];
			if (isset($variables['telefono'])) $datacontacto['telefono'] = (!empty($variables['telefono'])) ? $variables['telefono'] : null;
			if (isset($variables['sexo'])) $datacontacto['sexo'] = (!empty($variables['sexo'])) ? $variables['sexo'] : null;
			if (isset($variables['estado'])) $datacontacto['estado'] = (!empty($variables['estado'])) ? $variables['estado'] : null;
			if (!empty($variables['password'])) $datacontacto['password'] = md5($variables['password']);
			$datacontacto['hash'] = md5(uniqid());
			if (isset($variables['timezone'])) $datacontacto['timezone'] = (!empty($variables['timezone'])) ? $variables['timezone'] : null;
			if (isset($variables['idioma'])) $datacontacto['idioma'] = (!empty($variables['idioma'])) ? $variables['idioma'] : null;
			$datacontacto['fecha_alta'] = unix_to_human(now(), true, 'eu');
			$datacontacto['username_alta'] = 'pedimos';
			
			if (!isset($res['error']))
			{
				$insert = $this->db->insert('contactos', $datacontacto);
				$res2['id'] = $this->db->insert_id();
			}
		}

		//INGRESO SERVICIO
		if ($res2['id'])
		{
			$sql = "SELECT id, descripcion, id_moneda, valor, descuento, frecuencia";
			$sql .= " FROM categorias_generales";
			$sql .= " WHERE id = ".$variables['categoria'];
			$sql .= " AND estado = 2";
			
			$query = $this->db->query($sql);
			$categoria = $query->row_array();

			$dataservicio['grupo'] = $this->usuario->grupo;
			$dataservicio['id_empresa'] = $res['id'];
			$dataservicio['id_categoria'] = $categoria['id'];
			$dataservicio['operacion'] = 'V';
			$dataservicio['descripcion'] = $categoria['descripcion'];
			$dataservicio['id_moneda'] = $categoria['id_moneda'];
			$dataservicio['valor'] = $categoria['valor'];
			$dataservicio['descuento'] = $categoria['descuento'];
			$dataservicio['estado'] = 3;
			$dataservicio['fecha_alta'] = unix_to_human(now(), true, 'eu');
			$dataservicio['username_alta'] = 'pedimos';

			$insert = $this->db->insert('servicios', $dataservicio);
			$res3['id'] = $this->db->insert_id();
		}

		//INGRESO TIENDA
		if ($res3['id'])
		{
			$datatienda['grupo'] = $this->usuario->grupo;
			$datatienda['id_empresa'] = $res['id'];
			$datatienda['id_servicio'] = $res3['id'];
			$datatienda['titulo'] = $variables['tienda'];
			$datatienda['id_rubro'] = $variables['rubro'];
			$datatienda['celular'] = $variables['celular'];
			$datatienda['email'] = $variables['email'];
			$datatienda['pais'] = $variables['pais'];
			$datatienda['estado'] = 3;
			$datatienda['fecha_alta'] = now();
			$datatienda['username_alta'] = 'pedimos';
			
			$insert2 = $this->db->insert('tienda_configuracion', $datatienda);
			$res4['id'] = $this->db->insert_id();
		}

		//INGRESO SUCURSAL
		if ($res4['id'])
		{
			$sucursal['id_tienda'] = $res4['id'];
			$sucursal['titulo'] = 'Casa Principal';
			$sucursal['celular'] = $variables['celular'];
			$sucursal['email'] = $variables['email'];
			$sucursal['pais'] = $variables['pais'];
			$sucursal['orden'] = 1;
			$sucursal['estado'] = 2;
			$sucursal['fecha_alta'] = now();
			$sucursal['username_alta'] = 'pedimos';
			
			$insertsucursal = $this->db->insert('tienda_sucursales', $sucursal);
			$res5['id'] = $this->db->insert_id();
		}
		return ($res3['id']);
	}
	
	public function ingresarPedido($variables)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_items, tienda_pedidos.total, tienda_pedidos.estado";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id_tienda = ".$variables['id_tienda'];
		$sql .= " AND tienda_pedidos.cookie = '".$variables['cookie']."'";
		$sql .= " AND tienda_pedidos.estado = 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI NO HAY PEDIDO CARGADO
		if(!$res['id'])
		{
			//SELECCIONO SUCURSAL
			/*
			$sql = "SELECT tienda_sucursales.id, tienda_sucursales.titulo";
			$sql .= " FROM tienda_sucursales";
			$sql .= " WHERE tienda_sucursales.id_tienda = ".$variables['id_tienda'];
			$sql .= " ORDER BY tienda_sucursales.id ASC LIMIT 1";
	
			$query = $this->db->query($sql);
			$sucursal = $query->row_array();
			*/

			//INSERTO PEDIDO
			$datos['id_tienda'] = $variables['id_tienda'];
			//Traigo Casa Matriz
			//$datos['id_sucursal'] = $sucursal['id'];
			if($variables['id_contacto']) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
			$datos['id_sucursal'] = 1;
			$datos['cookie'] = $variables['cookie'];
			$datos['id_tienda_origen'] = $variables['id_tienda_origen'];
			$datos['subtotal'] = 0;
			$datos['total'] = 0;
			$datos['descuento_items'] = 0;
			$datos['id_forma_pago'] = 1;
			$datos['id_medio_envio'] = 0; 
			$datos['terminos'] = 0;
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['fecha_alta_utc'] = now();
			$datos['username_alta'] = $variables['tienda'];
	
			$insert = $this->db->insert('tienda_pedidos', $datos);
			$res2 = $this->db->insert_id();		
		}

		//SI HAY PEDIDO CARGADO
		else
		{
			$datospedido['fecha_modificacion'] = now();
			$datospedido['username_modificacion'] = $variables['tienda'];
	
			$where = "id = ".$res['id'];
			$res2 = $this->db->update('tienda_pedidos', $datospedido, $where);
		}
		return ($res2);
	}

	public function modificarPedido($id, $variables)
	{
		$datos['estado'] = $variables['estado'];
		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->username;

		$where = "id = $id";
		$res = $this->db->update('tienda_pedidos', $datos, $where);

		return ($res);
	}
	
	public function duplicarPedido($variables)
	{
		//SELECCIONO PEDIDO
		$sql = "SELECT tienda_pedidos.*";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id_tienda = ?";
		$sql .= " AND tienda_pedidos.id = ?";
		$sql .= " AND tienda_pedidos.estado > 0";
		$placeholders[] = $variables['id_tienda'];
		$placeholders[] = $variables['id_pedido'];
		
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();

		if($res['id'])
		{
			//INGRESO PEDIDO
			$datos['id_tienda'] = $variables['id_tienda'];
			if($variables['id_contacto']) { $datos['id_contacto'] = $variables['id_contacto']; } 
			else { $datos['id_contacto'] = null; }
			$datos['id_sucursal'] = $res['id_sucursal'];
			$datos['cookie'] = $variables['cookie'];
			$datos['id_tienda_origen'] = $res['id_tienda_origen'];
			$datos['subtotal'] = 0;
			$datos['total'] = 0;
			$datos['descuento_items'] = 0;
			$datos['id_forma_pago'] = 1;
			$datos['id_medio_envio'] = 0; 
			$datos['terminos'] = 0;
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['fecha_alta_utc'] = now();
			$datos['username_alta'] = $res['username_alta'];
	
			$insert = $this->db->insert('tienda_pedidos', $datos);
			$res2['id'] = $this->db->insert_id();
			
			if($res2['id'])
			{
				//SELECCIONO ITEMS
				$sql = "SELECT tienda_pedidos_items.*";
				$sql .= " FROM tienda_pedidos_items";
				$sql .= " WHERE tienda_pedidos_items.id_pedido = ".$variables['id_pedido'];
				$sql .= " AND tienda_pedidos_items.estado = 3";
				$query = $this->db->query($sql);
				$items = $query->result_array();
				
				//INGRESO ITEMS
				foreach($items as $item)
				{
					if($variables['id_tienda_origen'] == 2)
					{
						$sql = "SELECT precio_local as precio, precio_local_oferta as precio_oferta";
					}
					else
					{
						$sql = "SELECT precio, precio_oferta";
					}
					$sql .= " FROM tienda_productos";
					$sql .= " WHERE tienda_productos.id = ".$item['id_producto'];
					$query = $this->db->query($sql);
					$producto = $query->row_array();

					$datos1['id_pedido'] = $res2['id'];
					$datos1['id_producto'] = $item['id_producto'];
					$datos1['cantidad'] = $item['cantidad'];
					if($producto['precio_oferta'] > 0){ $datos1['subtotal'] = $item['cantidad']*$producto['precio_oferta']; } 
					else {  $datos1['subtotal'] = $item['cantidad']*$producto['precio'];}
					$datos1['detalle'] = $item['detalle'];
					$datos1['estado'] = 3;
					$datos1['fecha_alta'] = now();
					$datos1['username_alta'] = $item['username_alta'];
					$res3 = $this->db->insert('tienda_pedidos_items', $datos1);
					$id_item['id'] = $this->db->insert_id();
					
					//INGRESO OPCIONES EN TABLA
					if($id_item['id'])
					{
						$sql = "SELECT tienda_rel_pedidos_item_opcion.*";
						$sql .= " FROM tienda_rel_pedidos_item_opcion";
						$sql .= " WHERE tienda_rel_pedidos_item_opcion.id_pedido_item = ".$item['id'];
						$query = $this->db->query($sql);
						$opciones1 = $query->result_array();

						if($opciones1)
						{
							foreach($opciones1 as $opcion)
							{
								$opciones['id_pedido_item'] = $id_item['id'];
								$opciones['id_grupo'] = $opcion['id_grupo'];
								$opciones['id_opcion'] = $opcion['id_opcion'];
								$insert = $this->db->insert('tienda_rel_pedidos_item_opcion', $opciones);
							}
						}
					}
				}
				//SUMO TOTALES
				$suma = $this->sumarTotales($res2['id'], $variables['tienda']);
			}
		}
		return (!empty($res2)) ? $res2 : null;
	}

	public function ingresarDataPedido($id, $tienda, $pedido)
	{
		//INGRESO DATA JSON EN PEDIDO CARGADO
		$data['data'] = $pedido;
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $tienda;

		$where = "id = $id";
		$update = $this->db->update('tienda_pedidos', $data, $where);

		//return ($res);
	}

	public function ingresarItem($variables)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_items, tienda_pedidos.total, tienda_pedidos.estado";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id_tienda = ".$variables['id_tienda'];
		$sql .= " AND tienda_pedidos.cookie = '".$variables['cookie']."'";
		$sql .= " AND tienda_pedidos.estado = 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI HAY PEDIDO CARGADO
		if($res['id'])
		{
			//INGRESO ITEM
			$datos['id_pedido'] = $res['id'];
			$datos['id_producto'] = $variables['id_producto'];
			$datos['cantidad'] = $variables['cantidad'];
			$datos['subtotal'] = $variables['precio']*$variables['cantidad'];
			$datos['estado'] = 3;
			$datos['fecha_alta'] = now();
			$datos['username_alta'] = $variables['tienda'];
			$res3 = $this->db->insert('tienda_pedidos_items', $datos);
			$id_item = $this->db->insert_id();

			//INGRESO OPCIONES EN TABLA
			if($id_item)
			{
				foreach($variables['opciones'] as $opcion)
				{
					$opciones['id_pedido_item'] = $id_item;
					$opciones['id_opcion'] = $opcion['opcion'];
					$opciones['id_grupo'] = $opcion['id_opcion_grupo'];
			
					$insert = $this->db->insert('tienda_rel_pedidos_item_opcion', $opciones);
				}
				
				//INGRESO DETALLE EN ITEM
				$sql = "SELECT tienda_opciones.opcion as detalle, tienda_opciones.id";
				$sql .= " FROM tienda_opciones";
				$sql .= " LEFT JOIN tienda_rel_pedidos_item_opcion ON tienda_rel_pedidos_item_opcion.id_opcion = tienda_opciones.id";
				$sql .= " WHERE tienda_rel_pedidos_item_opcion.id_pedido_item = $id_item";
		
				$query = $this->db->query($sql);
				$detalleitem = $query->result_array();

				$ids = array_column($detalleitem, 'detalle');
				$item['detalle'] = implode(',', $ids);

				$where = "id = $id_item";
				$modifcar = $this->db->update('tienda_pedidos_items', $item, $where);
			}
			
			if ($this->sumarTotales($res['id'], $variables['tienda']))
			{
				return ($res4);
			}
			else
			{
				return false;
			}
		}
	}

	public function ingresarCupon($variables)
	{
		$sql = "SELECT tienda_cupones.id, tienda_cupones.cupon, tienda_cupones.descuento, tienda_cupones.cantidad";
		$sql .= " FROM tienda_cupones";
		$sql .= " WHERE tienda_cupones.cupon ='".$variables['cupon']."'";
		$sql .= " AND tienda_cupones.id_tienda = ".$variables['id_tienda'];
		$sql .= " AND tienda_cupones.fecha_vencimiento > NOW()";
		$sql .= " AND tienda_cupones.estado = 3";
		$sql .= " AND tienda_cupones.cantidad > 0";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		
		if($res['id'])
		{
			//INGRESO RELACION DE CUPONES
			$sql = "SELECT id, id_cupon";
			$sql .= " FROM tienda_rel_cupones_pedidos";
			$sql .= " WHERE id_cupon = ".$res['id'];
			$sql .= " AND id_pedido = ".$variables['id_pedido'];
			$query = $this->db->query($sql);
			$res2 = $query->row_array();
			
			if(!$res2['id'])
			{
				//CAMBIO STOCK
				$datos10['cantidad'] = $res['cantidad']-1;
				$where10 = "id = ".$res['id'];
				$res10 = $this->db->update('tienda_cupones', $datos10, $where10);

				//INSERTO DESCUENTOS
				$datos1['id_cupon'] = $res['id'];
				$datos1['id_pedido'] = $variables['id_pedido'];
				$res3 = $this->db->insert('tienda_rel_cupones_pedidos', $datos1);

				$sql = "SELECT SUM(tienda_pedidos_items.subtotal) as subtotal";
				$sql .= " FROM tienda_pedidos_items";
				$sql .= " WHERE tienda_pedidos_items.id_pedido = ".$variables['id_pedido'];
				$sql .= " AND tienda_pedidos_items.estado = 3";
				$query = $this->db->query($sql);
				$subtotal = $query->row_array();
				
				$sql = "SELECT SUM(tienda_cupones.descuento) as descuento_total";
				$sql .= " FROM tienda_cupones";
				$sql .= " LEFT JOIN tienda_rel_cupones_pedidos ON tienda_rel_cupones_pedidos.id_cupon = tienda_cupones.id";
				$sql .= " WHERE tienda_rel_cupones_pedidos.id_pedido = ".$variables['id_pedido'];
				$query = $this->db->query($sql);
				$descuento_total = $query->row_array();

				if($descuento_total['descuento_total'])
				{
					$datos['subtotal'] = $subtotal['subtotal'];
					$datos['descuento'] = ($datos['subtotal']*$descuento_total['descuento_total'])/100;
					//$datos['descuento_items'] = 10.00;
					$datos['total'] = $datos['subtotal']-$datos['descuento_items']-$datos['descuento'];
					$datos['fecha_modificacion'] = now();
					$datos['username_modificacion'] = $variables['tienda'];
					$where = "id = ".$variables['id_pedido'];
					$res3 = $this->db->update('tienda_pedidos', $datos, $where);

				}
			}
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function finalizarPedido($variables)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.id_sucursal, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.total";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id = ".$variables['pedido'];
		$sql .= " AND tienda_pedidos.estado = 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI HAY PEDIDO CARGADO
		if($res['id'])
		{
			//MODIFICO PEDIDO
			$datos['id_sucursal'] = $variables['id_sucursal'];
			$datos['nombre'] = $variables['nombre'];
			if (isset($variables['celular'])) $datos['celular'] = (!empty($variables['celular'])) ? $variables['celular'] : null;
			if (isset($variables['email'])) $datos['email'] = (!empty($variables['email'])) ? $variables['email'] : null;
			if (isset($variables['domicilio'])) $datos['domicilio'] = (!empty($variables['domicilio'])) ? $variables['domicilio'] : null;
			if (isset($variables['numero_mesa'])) $datos['numero_mesa'] = (!empty($variables['numero_mesa'])) ? $variables['numero_mesa'] : null;
			if (isset($variables['envio'])) $datos['envio'] = (!empty($variables['envio'])) ? $variables['envio'] : null;
			if (isset($variables['descuento_medios_envio'])) $datos['descuento_medios_envio'] = (!empty($variables['descuento_medios_envio'])) ? $variables['descuento_medios_envio'] : null;
			$datos['total'] = $variables['costo_total'];
			$datos['observaciones'] = $variables['observaciones'];
			$datos['id_medio_envio'] = $variables['id_medio_envio'];
			$datos['id_forma_pago'] = $variables['id_forma_pago'];
			$datos['estado'] = $variables['estado']; //2 Solicitado sin pagar en efectivo - 3 Pendiente Mercado Pago
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $variables['tienda'];
			$where = "id = ".$variables['pedido'];
		    $res2 = $this->db->update('tienda_pedidos', $datos, $where);
		}
		return (!empty($res2)) ? $res2 : null;
	}

	public function finalizarPedidoMP($variables)
	{
		$sql = "SELECT id, id_sucursal";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE id = ".$variables['pedido'];
		$sql .= " AND estado = 3";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI HAY PEDIDO CARGADO
		if($res['id'])
		{
			//MODIFICO PEDIDO
			$datos['estado'] = 5; //2 Solicitado Pagado Mercado Pago
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $variables['tienda'];
			$where = "id = ".$variables['pedido'];
		    $res2 = $this->db->update('tienda_pedidos', $datos, $where);
		}
		return (!empty($res2)) ? $res2 : null;
	}

	public function finalizarPedidoPayPal($variables)
	{
		$sql = "SELECT id, id_sucursal";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE id = ".$variables['pedido'];
		$sql .= " AND estado = 8";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI HAY PEDIDO CARGADO
		if($res['id'])
		{
			if($variables['cancelar'] == 1)
			{
				$datos['estado'] = 10; // Cancelado PayPal
				$datos['observaciones_pago'] = 'Cancelaci&oacute;n de pedido del usuario';
			}
			else
			{
				//MODIFICO PEDIDO
				$datos['estado'] = 9; // Solicitado Pagado PayPal
				$datos['id_externo'] = $variables['id_externo'];
				$datos['observaciones_pago'] = $variables['observaciones'];
			}
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $variables['username_modificacion'];
			$where = "id = ".$variables['pedido'];
		    $res2 = $this->db->update('tienda_pedidos', $datos, $where);
		}
		return (!empty($res2)) ? $res2 : null;
	}

	public function eliminarPedido($variables)
	{
		$borrar = $this->db->where('id', $variables['id']);
		$res = $this->db->delete('tienda_pedidos'); 
		
		return (!empty($res)) ? $res : null;
	}

	public function eliminarItem($variables)
	{
		$borrar = $this->db->where('id', $variables['id']);
		$res = $this->db->delete('tienda_pedidos_items'); 
		
		if ($this->sumarTotales($variables['id_pedido'], $variables['tienda']))
		{
			return ($res);
		}
		else
		{
			return false;
		}		
	}

	public function eliminarCupon($variables)
	{
		$borrar = $this->db->where('id', $variables['id']);
		$res = $this->db->delete('tienda_rel_cupones_pedidos'); 
		
		//CAMBIO STOCK
		$sql = "SELECT id, cantidad";
		$sql .= " FROM tienda_cupones";
		$sql .= " WHERE id = ".$variables['id_cupon'];
		$query = $this->db->query($sql);
		$res2 = $query->row_array();
		
		if($res2['id'])
		{
			$datos10['cantidad'] = $res2['cantidad']+1;
			$where10 = "id = ".$variables['id_cupon'];
			$res10 = $this->db->update('tienda_cupones', $datos10, $where10);
		}
		
		//SUMO TOTALES
		if ($this->sumarTotales($variables['id_pedido'], $variables['tienda']))
		{
			return ($res);
		}
		else
		{
			return false;
		}		
	}

	//SUMO LOS TOTALES PARA EL PEDIDO
	public function sumarTotales($id_pedido, $tienda)
	{
		//SELECCIONO TOTAL ITEMS
		$sql2 = "SELECT SUM(tienda_pedidos_items.subtotal) as subtotal";
		$sql2 .= " FROM tienda_pedidos_items";
		$sql2 .= " WHERE tienda_pedidos_items.id_pedido = $id_pedido";
		$sql2 .= " AND tienda_pedidos_items.estado = 3";
		$query = $this->db->query($sql2);
		$subtotal = $query->row_array();
		
		//SELECCIONO DESCUENTOS
		$sqldescuento = "SELECT SUM(tienda_cupones.descuento) as descuento_total";
		$sqldescuento .= " FROM tienda_cupones";
		$sqldescuento .= " LEFT JOIN tienda_rel_cupones_pedidos ON tienda_rel_cupones_pedidos.id_cupon = tienda_cupones.id";
		$sqldescuento .= " WHERE tienda_rel_cupones_pedidos.id_pedido = $id_pedido";
		$query = $this->db->query($sqldescuento);
		$descuento_total = $query->row_array();

		if($descuento_total['descuento_total'])
		{
			$datossumas['subtotal'] = $subtotal['subtotal'];
			$datossumas['descuento'] = ($datossumas['subtotal']*$descuento_total['descuento_total'])/100;
			$datossumas['descuento_items'] = 0.00; //TRAER POR BASE
			$datossumas['total'] = $datossumas['subtotal']-$datossumas['descuento_items']-$datossumas['descuento'];
		}

		else
		{
			$datossumas['subtotal'] = $subtotal['subtotal'];
			$datossumas['descuento'] = 0;
			$datossumas['total'] = $subtotal['subtotal'];
		}

		$datossumas['fecha_modificacion'] = now();
		$datossumas['username_modificacion'] = $tienda;
		$wheresumas = "id = $id_pedido";
		$res4 = $this->db->update('tienda_pedidos', $datossumas, $wheresumas);
		
		return ($subtotal);
	}
	/* Fin Usadas por la API */


	public function listadoCategoriasUser($id)
	{
		$sql = "SELECT tienda_productos_categorias.id, tienda_productos_categorias.categoria";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos_categorias.id_tienda";
		$sql .= " WHERE tienda_configuracion.id = $id";
		$sql .= " AND tienda_productos_categorias.estado > 0";
		$sql .= " ORDER BY tienda_productos_categorias.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$rubro[$valor['id']] = $valor['categoria'];
		}
		return (!empty($rubro)) ? $rubro : null;
	}

	public function detalleCategoria($id)
	{
		$sql = "SELECT tienda_productos_categorias.*";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " WHERE tienda_productos_categorias.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function listadoClientes($id, $estado = NULL)
	{
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.estado, tienda_pedidos.nombre, tienda_pedidos.celular, tienda_pedidos.email, tienda_pedidos.domicilio, tienda_pedidos.observaciones";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id_tienda = $id";
		if($estado)
		{
			$sql .= " AND tienda_pedidos.estado = $estado";
		}
		else
		{
			$sql .= " AND tienda_pedidos.estado > 0";
		}
		$sql .= " ORDER BY tienda_pedidos.email ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function modificarTienda($valores)
	{
		if(isset($valores['id_rubro'])) { $datos['id_rubro'] = $valores['rubro']; }
		if(isset($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(isset($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; }
		if(isset($valores['estado'])) { $datos['estado'] = $valores['estado']; }
		if(isset($valores['telefono'])) { $datos['telefono'] = $valores['telefono']; }
		if(isset($valores['celular'])) { $datos['celular'] = $valores['celular']; }
		if(isset($valores['email'])) { $datos['email'] = $valores['email']; }
		if(isset($valores['recibir_email'])) { $datos['recibir_email'] = $valores['recibir_email']; }
		if(isset($valores['solicitar_email'])) { $datos['solicitar_email'] = $valores['solicitar_email']; }
		if(isset($valores['solicitar_telefono'])) { $datos['solicitar_telefono'] = $valores['solicitar_telefono']; }
		if(isset($valores['privada'])) { $datos['privada'] = $valores['privada']; }
		if(isset($valores['vista_productos'])) { $datos['vista_productos'] = $valores['vista_productos']; }
		if(isset($valores['domicilio'])) { $datos['domicilio'] = $valores['domicilio']; }
		if(isset($valores['numero'])) { $datos['numero'] = $valores['numero']; }
		if(isset($valores['localidad'])) { $datos['localidad'] = $valores['localidad']; }
		if(isset($valores['provincia'])) { $datos['provincia'] = $valores['provincia']; }
		if(isset($valores['pais'])) { $datos['pais'] = $valores['pais']; }
		if(isset($valores['url'])) { $datos['url'] = $valores['url']; }
		if(isset($valores['color1'])) { $datos['color1'] = $valores['color1']; }
		if(isset($valores['color2'])) { $datos['color2'] = $valores['color2']; }
		if(isset($valores['analytics'])) { $datos['analytics'] = $valores['analytics']; }
		if(isset($valores['email_MP'])) { $datos['email_MP'] = $valores['email_MP']; }
		if(isset($valores['clienteMP'])) { $datos['clienteMP'] = $valores['clienteMP']; }
		if(isset($valores['claveMP'])) { $datos['claveMP'] = $valores['claveMP']; }
		if(isset($valores['email_paypal'])) { $datos['email_paypal'] = $valores['email_paypal']; }
		if(isset($valores['facebook'])) { $datos['facebook'] = $valores['facebook']; }
		if(isset($valores['twitter'])) { $datos['twitter'] = $valores['twitter']; }
		if(isset($valores['instagram'])) { $datos['instagram'] = $valores['instagram']; }
		if(isset($valores['linkedin'])) { $datos['linkedin'] = $valores['linkedin']; }
		if(isset($valores['youtube'])) { $datos['youtube'] = $valores['youtube']; }
		if(isset($valores['costo_envio'])) { $datos['costo_envio'] = $valores['costo_envio']; }
		if(isset($valores['recibir_email'])) { $datos['recibir_email'] = $valores['recibir_email']; }
		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;

		$where = "id = ".$valores['id'];
		$update = $this->db->update('tienda_configuracion', $datos, $where);
		$res['id'] = $valores['id'];

		//INGRESO SUCURSAL
		if ($res['id'])
		{
			if(isset($valores['celular'])) { $sucursal['celular'] = $valores['celular']; }
			if(isset($valores['domicilio'])) { $sucursal['domicilio'] = $valores['domicilio']; }
			if(isset($valores['contenido1'])) { $sucursal['contenido1'] = $valores['contenido1']; }
			if(isset($valores['contenido2'])) { $sucursal['contenido2'] = $valores['contenido2']; }
			if(isset($valores['numero'])) { $sucursal['numero'] = $valores['numero']; }
			if(isset($valores['localidad'])) { $sucursal['localidad'] = $valores['localidad']; }
			if(isset($valores['provincia'])) { $sucursal['provincia'] = $valores['provincia']; }
			if(isset($valores['pais'])) { $sucursal['pais'] = $valores['pais']; }
			if(isset($valores['recibir_email'])) { $sucursal['recibir_email'] = $valores['recibir_email']; }
			if(isset($valores['estado'])) { if ($valores['estado'] == 3) { $sucursal['estado'] = 2; } else { $sucursal['estado'] = 1; } }
			$sucursal['fecha_modificacion'] = now();
			$sucursal['username_modificacion'] = $this->usuario->id;
			
			$wheresucursal = "id_tienda = ".$valores['id']." AND titulo = 'Casa central'";
			$updatesucursal = $this->db->update('tienda_sucursales', $sucursal, $wheresucursal);
		}
		return (!empty($res)) ? $res : null;
	}

	public function ingresarSucursal($id = NULL)
	{
		$datos['id_tienda'] = $this->input->post('id_tienda');
		$datos['titulo'] = $this->input->post('titulo');
		$datos['contenido1'] = $this->input->post('contenido1');
		$datos['orden'] = $this->input->post('orden');
		$datos['estado'] = $this->input->post('estado');
		$datos['telefono'] = $this->input->post('telefono');
		$datos['celular'] = $this->input->post('celular');
		$datos['email'] = $this->input->post('email');
		$datos['domicilio'] =  $this->input->post('domicilio');
		$datos['numero'] = $this->input->post('numero');
		$datos['localidad'] = $this->input->post('localidad');
		$datos['provincia'] = $this->input->post('provincia');
		$datos['pais'] = $this->input->post('pais');

		if (empty($this->input->post('id')))
		{
			$datos['fecha_alta'] = now();
			$datos['username_alta'] = $this->usuario->id;
	
			$insert = $this->db->insert('tienda_sucursales', $datos);
			$res['id'] = $this->db->insert_id();
		}
		else
		{
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $this->usuario->id;
			$where = "id = ".$this->input->post('id');
			$update = $this->db->update('tienda_sucursales', $datos, $where);
			$res['id'] = $this->input->post('id');
		}
		return (!empty($res)) ? $res : null;
	}

	public function ingresarCategoria($valores)
	{
		$datos['id_tienda'] = $valores['id_tienda'];
		$datos['categoria'] = $valores['categoria'];
		$datos['observaciones'] = $valores['observaciones'];
		$datos['estado'] = $valores['estado'];
		if(isset($valores['delivery'])) { $datos['delivery'] = $valores['delivery']; } else { $datos['delivery'] = 0;}

		//TRAIGO ORDEN ANTERIOR
		$sql = "SELECT id, orden";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " WHERE id_tienda = ?";
		$sql .= " AND estado > 0";
		$sql .= " ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $valores['id_tienda'];

		$query = $this->db->query($sql, $placeholders);
		$orden = $query->row_array();

		if($orden)
		{
			$datos['orden'] = $orden['orden']+1;
		}
		else
		{
			$datos['orden'] = 0;
		}
		
		$datos['fecha_alta'] = now();
		$datos['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('tienda_productos_categorias', $datos);
		$res['id'] = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	public function modificarCategoria($valores)
	{
		$datos['id_tienda'] = $valores['id_tienda'];
		$datos['categoria'] = $valores['categoria'];
		$datos['observaciones'] = $valores['observaciones'];
		$datos['estado'] = $valores['estado'];
		if(isset($valores['delivery'])) { $datos['delivery'] = $valores['delivery']; } else { $datos['delivery'] = 0;}

		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('tienda_productos_categorias', $datos, $where);
		
		return (!empty($res)) ? $res : null;
	}
	
	public function ingresarProducto($valores)
	{
		$datos['id_tienda'] = $valores['id_tienda'];
		$datos['id_categoria'] = $valores['id_categoria'];
		$datos['titulo'] = $valores['titulo'];
		$datos['uri'] = $valores['uri'];
		if(isset($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(isset($valores['codigo'])) { $datos['codigo'] = $valores['codigo']; }
		$datos['precio'] = $valores['precio'];
		if(isset($valores['precio_oferta'])) { $datos['precio_oferta'] = $valores['precio_oferta']; }
		if(isset($valores['precio_local'])) { $datos['precio_local'] = $valores['precio_local']; }
		if(isset($valores['precio_local_oferta'])) { $datos['precio_local_oferta'] = $valores['precio_local_oferta']; }
		$datos['galeria'] = $valores['galeria'];
		$datos['destacado'] = $valores['destacado'];
		$datos['estado'] = $valores['estado'];

		//TRAIGO ORDEN ANTERIOR
		$sql = "SELECT id, orden";
		$sql .= " FROM tienda_productos";
		$sql .= " WHERE id_tienda = ?";
		$sql .= " AND id_categoria = ?";
		$sql .= " AND estado > 0";
		$sql .= " ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $valores['id_tienda'];
		$placeholders[] = $valores['id_categoria'];
		
		$query = $this->db->query($sql, $placeholders);
		$orden = $query->row_array();

		if($orden)
		{
			$datos['orden'] = $orden['orden']+1;
		}
		else
		{
			$datos['orden'] = 0;
		}
			
		$datos['fecha_alta'] = now();
		$datos['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('tienda_productos', $datos);
		$res['id'] = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	public function modificarProducto($id, $valores)
	{
		$datos['id_tienda'] = $valores['id_tienda'];
		$datos['id_categoria'] = $valores['id_categoria'];
		$datos['titulo'] = $valores['titulo'];
		$datos['uri'] = $valores['uri'];
		if(isset($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(isset($valores['codigo'])) { $datos['codigo'] = $valores['codigo']; }
		$datos['precio'] = $valores['precio'];
		if(isset($valores['precio_oferta'])) { $datos['precio_oferta'] = $valores['precio_oferta']; }
		if(isset($valores['precio_local'])) { $datos['precio_local'] = $valores['precio_local']; }
		if(isset($valores['precio_local_oferta'])) { $datos['precio_local_oferta'] = $valores['precio_local_oferta']; }
		$datos['galeria'] = $valores['galeria'];
		$datos['destacado'] = $valores['destacado'];
		$datos['estado'] = $valores['estado'];

		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;
		$where = "id = $id";
		$res = $this->db->update('tienda_productos', $datos, $where);

		return (!empty($res)) ? $res : null;
	}
	
	//ACTUALIZACION MASIVA DE PRODUCTOS CON PORCENTAJE
    function actualizacionProducto($valores)
    {
		//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
		$sql = "SELECT tienda_productos.id, tienda_productos.precio, tienda_productos.precio_local_oferta, tienda_productos.precio_oferta, tienda_productos.precio_local";
		$sql .= " FROM tienda_productos";
		$sql .= " WHERE tienda_productos.id_categoria = ?";
		$placeholders[] = $valores['id_categoria'];
		
		$query = $this->db->query($sql, $placeholders);
		$productos = $query->result_array();
		
		$aumento = $valores['porcentaje'];
		$aumento_local = $valores['porcentaje_local'];
		$aumento_oferta = $valores['porcentaje_oferta'];
		$aumento_local_oferta = $valores['porcentaje_local_oferta'];
		
		foreach ($productos as $obj => $valor)
		{
			$datos['precio'] = ($valor['precio']*$aumento/100)+$valor['precio'];
			$datos['precio_oferta'] = ($valor['precio_oferta']*$aumento_oferta/100)+$valor['precio_oferta'];
			$datos['precio_local'] = ($valor['precio_local']*$aumento_local/100)+$valor['precio_local'];
			$datos['precio_local_oferta'] = ($valor['precio_local_oferta']*$aumento_local_oferta/100)+$valor['precio_local_oferta'];
	
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $this->usuario->id;
			$where = "id = ".$valor['id'];
			$res = $this->db->update('tienda_productos', $datos, $where);
		}
		return (!empty($res)) ? $res : null;
    }


	//COMPRA MASIVA DE PRODUCTOS DESDE FRONT
    function compraMasiva($valores)
    {
		$sql = "SELECT tienda_pedidos.id, tienda_pedidos.subtotal, tienda_pedidos.descuento, tienda_pedidos.descuento_items, tienda_pedidos.total, tienda_pedidos.estado";
		$sql .= " FROM tienda_pedidos";
		$sql .= " WHERE tienda_pedidos.id_tienda = ".$valores['id_tienda'];
		$sql .= " AND tienda_pedidos.cookie = '".$valores['cookie']."'";
		$sql .= " AND tienda_pedidos.estado = 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SI NO HAY PEDIDO CARGADO
		if(!$res['id'])
		{
			//INSERTO PEDIDO
			$datos['id_tienda'] = $valores['id_tienda'];
			if($valores['id_contacto']) { $datos['id_contacto'] = $valores['id_contacto']; } else { $datos['id_contacto'] = null; }
			$datos['id_sucursal'] = 1;
			$datos['cookie'] = $valores['cookie'];
			$datos['id_tienda_origen'] = $valores['id_tienda_origen'];
			$datos['subtotal'] = 0;
			$datos['total'] = 0;
			$datos['descuento_items'] = 0;
			$datos['id_forma_pago'] = 1;
			$datos['id_medio_envio'] = 0; 
			$datos['terminos'] = 0;
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['fecha_alta_utc'] = now();
			$datos['username_alta'] = $valores['tienda'];
	
			$insert = $this->db->insert('tienda_pedidos', $datos);
			$res2['id'] = $this->db->insert_id();		
		}
		//SI HAY PEDIDO CARGADO
		else
		{
			$datospedido['fecha_modificacion'] = now();
			$datospedido['username_modificacion'] = $valores['tienda'];
	
			$where = "id = ".$res['id'];
			$update = $this->db->update('tienda_pedidos', $datospedido, $where);
			$res2['id'] = $res['id'];		
		}

		//SI CARGA O MODIFICA EL PEDIDO
		if($res2)
		{
			//TRAIGO LOS IDS Y LOS CUENTO
			$array = $valores['productos']['id_producto'];
			$array = count($array);
	
			//TRAIGO TODOS LOS DATOS
			$array2 = $valores['productos'];
	
			//RECORRO EL ARRAY Y ASIGNO VALORES
			for ($i = 0; $i < $array; $i++) 
			{
				$sql = "SELECT tienda_pedidos_items.id, tienda_pedidos_items.estado";
				$sql .= " FROM tienda_pedidos_items";
				$sql .= " WHERE tienda_pedidos_items.id_pedido = ".$res2['id'];
				$sql .= " AND tienda_pedidos_items.id_producto = ".$array2['id_producto'][$i];
				$sql .= " AND tienda_pedidos_items.estado > 0";
				$query = $this->db->query($sql);
				$item = $query->row_array();

				if($item['id'])
				{
					if($array2['cantidad'][$i] > 0)
					{
						$items['cantidad'] = $array2['cantidad'][$i];
						$items['subtotal'] = $array2['precio'][$i]*$items['cantidad'];
						$items['fecha_modificacion'] = now();
						$items['username_modificacion'] = $array2['tiendaNombre'][$i];
						$where = "id = ".$item['id'];
						$res3 = $this->db->update('tienda_pedidos_items', $items, $where);
					}
					else
					{
						$where1 = $this->db->where('id', $item['id']);
						$borrar = $this->db->delete('tienda_pedidos_items'); 
					}
				}
				else
				{
					if($array2['cantidad'][$i] > 0)
					{
						$datos1['id_pedido'] = $res2['id'];
						$datos1['id_producto'] = $array2['id_producto'][$i];
						$datos1['cantidad'] = $array2['cantidad'][$i];
						$datos1['subtotal'] = $array2['precio'][$i]*$datos1['cantidad'];
						$datos1['estado'] = 3;
						$datos1['fecha_alta'] = now();
						$datos1['username_alta'] = $array2['tiendaNombre'][$i];
						$res3 = $this->db->insert('tienda_pedidos_items', $datos1);
					}
				}
			}
			if ($this->sumarTotales($res2['id'], $valores['tienda']))
			{
				return ($res2);
			}
			else
			{
				return false;
			}
		}
	}

	//ACTUALIZACION MASIVA DE PRODUCTOS DESDE FORMULARIO
    function actualizacionMasivaProducto($valores, $tienda, $categoria = null)
    {
		//TRAIGO LOS IDS Y LOS CUENTO
		$array = $valores['id'];
		$array = count($array);

		//TRAIGO TODOS LOS DATOS
		$array2 = $valores;
/* 		echo 'Cantidad: '.$array.'<br>'; */

		//RECORRO EL ARRAY Y ASIGNO VALORES
		for ($i = 0; $i < $array; $i++) 
		{
		    $datos['titulo'] = $array2['titulo'][$i];
		    $datos['id_categoria'] = $array2['id_categoria'][$i];
		    $datos['codigo'] = $array2['codigo'][$i];
		    $datos['precio'] = $array2['precio'][$i];
		    $datos['precio_oferta'] = $array2['precio_oferta'][$i];
		    $datos['estado'] = $array2['estado'][$i];
		    $datos['destacado'] = $array2['destacado'][$i];
			$datos['fecha_modificacion'] = now();
			$datos['username_modificacion'] = $this->usuario->id;
	
		    $id = $array2['id'][$i];
			$where = "id = $id AND id_tienda = $tienda";
			$res = $this->db->update('tienda_productos', $datos, $where);
		}
		return ($res);
	}


	//RELACIONO FORMAS DE PAGO
    function relacionarFormasPago($valores)
    {
		//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
		$sql = "SELECT tienda_rel_forma_pago_tienda.id";
		$sql .= " FROM tienda_rel_forma_pago_tienda";
		$sql .= " WHERE tienda_rel_forma_pago_tienda.id_tienda = ".$valores['id'];

		$query = $this->db->query($sql);
		$eliminar = $query->row_array();
		$id_eliminar = $eliminar['id'];

		//BORRO CAMPOS RELACIONADOS ANTERIORES
		if (isset($id_eliminar))
		{
			$where = "id_tienda = ".$valores['id'];
			$delete = $this->db->delete('tienda_rel_forma_pago_tienda', $where);
		}
		foreach ($this->input->post('relaciones') as $relacionado)
		{
			$datos2['id_tienda'] = $valores['id'];
			$datos2['id_forma_pago'] = $relacionado;
			$tipo = (substr('tipo'.$relacionado, 4));
			
			//VERIFICO SI ES DESCUENTO O RECARGO
			if ($tipo == $datos2['id_forma_pago'])
			{
				$datos2['tipo'] = $this->input->post('tipo'.$relacionado);
				if ($datos2['tipo'] == 20) 
				{
					$datos2['descuento'] = $this->input->post('porcentaje'.$relacionado);
					$datos2['tipo'] = 20;
					$datos2['recargo'] = null;
				}
				elseif ($datos2['tipo'] == 21)
				{
				    $datos2['recargo'] = $this->input->post('porcentaje'.$relacionado);
					$datos2['tipo'] = 21;
					$datos2['descuento'] = null;
				}
				else
				{
					$datos2['tipo'] = null;
					$datos2['recargo'] = null;
					$datos2['descuento'] = null;
				}
			} 	
			$res = $this->db->insert('tienda_rel_forma_pago_tienda', $datos2);
		}
 		return (!empty($res)) ? $res : null;
   }

	//RELACIONO ENVIOS
    function relacionarEnvios($valores)
    {
		//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
		$sql = "SELECT tienda_rel_envios_tienda.id";
		$sql .= " FROM tienda_rel_envios_tienda";
		$sql .= " WHERE tienda_rel_envios_tienda.id_tienda = ".$valores['id'];

		$query = $this->db->query($sql);
		$eliminar = $query->row_array();
		$id_eliminar = $eliminar['id'];

		//BORRO CAMPOS RELACIONADOS ANTERIORES
		if (isset($id_eliminar))
		{
			$where = "id_tienda = ".$valores['id'];
			$delete = $this->db->delete('tienda_rel_envios_tienda', $where);
		}

		foreach ($valores['relacionesenvios'] as $relacionado)
		{
			$datos2['id_tienda'] = $valores['id'];
			$datos2['id_envio'] = $relacionado;
			$tipo = (substr('envio'.$relacionado, 5));

			//VERIFICO SI ES DESCUENTO O RECARGO
			if ($tipo == $datos2['id_envio'])
			{
				$datos2['tipo'] = $this->input->post('envio'.$relacionado);
				$datos2['mmc'] = $this->input->post('mmc'.$relacionado); 
				if ($datos2['tipo'] == 20) 
				{
					$datos2['descuento'] = $this->input->post('valor'.$relacionado);
					$datos2['tipo'] = 20;
					$datos2['recargo'] = null;
				}
				elseif ($datos2['tipo'] == 21)
				{
				    $datos2['recargo'] = $this->input->post('valor'.$relacionado);
					$datos2['tipo'] = 21;
					$datos2['descuento'] = null;
				}
				else
				{
					$datos2['tipo'] = null;
					$datos2['recargo'] = null;
					$datos2['descuento'] = null;
				}
			} 	
			$res = $this->db->insert('tienda_rel_envios_tienda', $datos2);
		}
		return (!empty($res)) ? $res : null;
    }

	public function ingresarCuponCMS()
	{
		$datos['id_tienda'] = $this->input->post('id_tienda');
		$datos['cupon'] = $this->input->post('cupon');
		$datos['descuento'] = $this->input->post('descuento');
		$datos['fecha_vencimiento'] = $this->input->post('fecha_vencimiento');
		$datos['cantidad'] = $this->input->post('cantidad');
		$datos['estado'] = $this->input->post('estado');
		
		$datos['fecha_alta'] = now();
		$datos['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('tienda_cupones', $datos);
		$res['id'] = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	public function modificarCupon($id)
	{
		$datos['cupon'] = $this->input->post('cupon');
		$datos['descuento'] = $this->input->post('descuento');
		$date = date_create($this->input->post('fecha_vencimiento'));
		$datos['fecha_vencimiento'] = date_format($date, 'Y-m-d');
		$datos['cantidad'] = $this->input->post('cantidad');
		$datos['estado'] = $this->input->post('estado');

		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;

		$where = "id = ".$this->input->post('id');
		$res = $this->db->update('tienda_cupones', $datos, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//DETALLE DE PROYECTO
	public function getProyectoFromProducto($id)
	{
		$sql = "SELECT media_proyectos.id, media_proyectos.proyecto, media_proyectos.estado";
		$sql .= " FROM media_proyectos";
		$sql .= " LEFT JOIN tienda_rel_productos_proyectos ON tienda_rel_productos_proyectos.id_media_proyecto = media_proyectos.id";
		$sql .= " WHERE media_proyectos.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND media_proyectos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND tienda_rel_productos_proyectos.id_producto = $id";
		$sql .= " AND media_proyectos.estado > 0";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();

		}
		return (!empty($res)) ? $res : null;
	}
	
	//RELACIONO PROYECTO
    function relacionarProyecto($proyecto, $producto)
    {
		//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
		$sql = "SELECT tienda_rel_productos_proyectos.id";
		$sql .= " FROM tienda_rel_productos_proyectos";
		$sql .= " WHERE tienda_rel_productos_proyectos.id_producto = $producto";

		$query = $this->db->query($sql);
		$eliminar = $query->row_array();

		//BORRO CAMPOS RELACIONADOS ANTERIORES
		if (isset($eliminar['id']))
		{
			$where = "id = ".$eliminar['id'];
			$delete = $this->db->delete('tienda_rel_productos_proyectos', $where);
		}

		$datos['id_producto'] = $producto;
		$datos['id_media_proyecto'] = $proyecto;

		$res = $this->db->insert('tienda_rel_productos_proyectos', $datos);

		return (!empty($res)) ? $res : null;
    }

	//RELACIONO IMAGEN CON PROYECTO
    function relacionarImagen($proyecto, $imagen)
    {
		$datos['id_proyecto'] = $proyecto;
		$datos['id_media'] = $imagen;

		$res = $this->db->insert('media_rel_proyectos', $datos);

		return (!empty($res)) ? $res : null;
    }

	//BORRAR GENERAL
	public function eliminarItems($id, $tabla)
	{
		$datos['estado'] = '-'.$this->input->post('estado');
		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->username;
		$where = "id = ".$this->input->post('id');
		$res = $this->db->update($tabla, $datos, $where);
		
		return $res;
	}

	//ORDENAR GENERAL
	public function ordenarItems($items, $tabla)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update($tabla, $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function listadoCupones($id_tienda)
	{
		$sql = "SELECT tienda_cupones.*, ";
		$sql .= " CASE
						WHEN tienda_cupones.estado = 1 THEN 'Inactivo'
						WHEN tienda_cupones.estado = 3 THEN 'Activo'
					END AS tipo_estado";
		$sql .= " FROM tienda_cupones";
		$sql .= " WHERE tienda_cupones.id_tienda = $id_tienda";
		$sql .= " AND tienda_cupones.estado > 0";
		$sql .= " ORDER BY tienda_cupones.id ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleCuponCMS($id)
	{
		$sql = "SELECT tienda_cupones.*";
		$sql .= " FROM tienda_cupones";
		$sql .= " WHERE tienda_cupones.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function detalleRelacionCupon($id)
	{
		$sql = "SELECT tienda_rel_cupones_pedidos.id";
		$sql .= " FROM tienda_rel_cupones_pedidos";
		$sql .= " WHERE tienda_rel_cupones_pedidos.id_cupon = $id";
		$sql .= " ORDER BY tienda_rel_cupones_pedidos.id ASC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getSucursales($id_tienda)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS tienda_sucursales.id, tienda_sucursales.titulo, tienda_sucursales.domicilio, tienda_sucursales.numero,  tienda_sucursales.celular, tienda_sucursales.orden, tienda_sucursales.estado															
				FROM tienda_sucursales
				WHERE tienda_sucursales.id_tienda = $id_tienda";
		$sql .= " AND tienda_sucursales.estado > 0";
		$sql .= " AND tienda_sucursales.orden > 0";
		$sql .= " ORDER BY tienda_sucursales.titulo ASC";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function detalleGrupo($id)
	{
		$sql = "SELECT tienda_opciones_grupos.id,  tienda_opciones_grupos.opcion_grupo,  tienda_opciones_grupos.cantidad,  tienda_opciones_grupos.orden, tienda_opciones_grupos.estado";
		$sql .= " FROM tienda_opciones_grupos";
		$sql .= " WHERE tienda_opciones_grupos.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function ingresarGrupo($id = null)
	{
		//TRAIGO ORDEN ANTERIOR
		$sql = "SELECT id, orden";
		$sql .= " FROM tienda_opciones_grupos";
		$sql .= " WHERE id_tienda = ".$this->input->post('id_tienda');
		$sql .= " AND estado = 2";
		$sql .= " ORDER BY orden DESC LIMIT 1";
		$query = $this->db->query($sql);
		$orden = $query->row_array();

		if($orden)
		{
			$data['orden'] = $orden['orden']+1;
		}
		else
		{
			$data['orden'] = 1;
		}
			
		$data['id_tienda'] = $this->input->post('id_tienda');
		$data['opcion_grupo'] = $this->input->post('opcion_grupo');
		$data['cantidad'] = $this->input->post('cantidad');
		$data['estado'] = $this->input->post('estado');
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('tienda_opciones_grupos', $data);
		$res['id'] = $this->db->insert_id();

		//RELACIONO GRUPO CON TIENDA SI SE INSERTA DESDE PRODUCTO
	    if($id)
	    {
			$relacion['id_tienda'] = $this->input->post('id_tienda');
			$relacion['id_producto'] = $id;
			$relacion['id_opcion_grupo'] = $res['id'];
	
			$rel = $this->db->insert('tienda_producto_rel_opciones_grupo', $relacion);
	    }

		return (!empty($res)) ? $res : null;
	}

	public function modificarGrupo($id)
	{
		$datos['id_tienda'] = $this->input->post('id_tienda');
		$datos['opcion_grupo'] = $this->input->post('opcion_grupo');
		$datos['cantidad'] = $this->input->post('cantidad');
		$datos['estado'] = $this->input->post('estado');

		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$this->input->post('id');
		$res = $this->db->update('tienda_opciones_grupos', $datos, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//RELACIONO FORMAS DE PAGO
    function relacionarGrupo($id)
    {
		//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
		$sql = "SELECT tienda_producto_rel_opciones_grupo.id_producto";
		$sql .= " FROM tienda_producto_rel_opciones_grupo";
		$sql .= " WHERE tienda_producto_rel_opciones_grupo.id_producto = $id";

		$query = $this->db->query($sql);
		$eliminar = $query->row_array();
		$id_eliminar = $eliminar['id_producto'];

		//BORRO CAMPOS RELACIONADOS ANTERIORES
		if (isset($id_eliminar))
		{
			$where = "id_producto = $id";
			$delete = $this->db->delete('tienda_producto_rel_opciones_grupo', $where);
		}

		foreach ($this->input->post('relacionesgrupos') as $relacionado)
		{
			$datos2['id_tienda'] = $this->input->post('id_tienda');
			$datos2['id_producto'] = $id;
			$datos2['id_opcion_grupo'] = $relacionado;
	
			$res = $this->db->insert('tienda_producto_rel_opciones_grupo', $datos2);
		}
		return (!empty($res)) ? $res : null;
    }

	public function detalleItemGrupo($id)
	{
		$sql = "SELECT tienda_opciones.id, tienda_opciones.id_opcion_grupo, tienda_opciones.opcion, tienda_opciones.precio, tienda_opciones.estado";
		$sql .= " FROM tienda_opciones";
		$sql .= " WHERE tienda_opciones.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function ingresarItemGrupo($id = null)
	{
		//TRAIGO ORDEN ANTERIOR
		$sql = "SELECT id, orden";
		$sql .= " FROM tienda_opciones";
		$sql .= " WHERE id_tienda = ".$this->input->post('id_tienda');
		$sql .= " AND id_opcion_grupo = ".$this->input->post('id_opcion_grupo');
		$sql .= " AND estado = 2";
		$sql .= " ORDER BY orden DESC LIMIT 1";
		$query = $this->db->query($sql);
		$orden = $query->row_array();

		if($orden)
		{
			$data['orden'] = $orden['orden']+1;
		}
		else
		{
			$data['orden'] = 1;
		}
			
		$data['id_tienda'] = $this->input->post('id_tienda');
		$data['id_opcion_grupo'] = $this->input->post('id_opcion_grupo');
		$data['opcion'] = $this->input->post('opcion');
		$data['precio'] = $this->input->post('precio');
		$data['estado'] = $this->input->post('estado');
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('tienda_opciones', $data);
		$res['id'] = $this->db->insert_id();
		return (!empty($res)) ? $res : null;
	}

	public function modificarItemGrupo($id)
	{
		$data['opcion'] = $this->input->post('opcion');
		$data['precio'] = $this->input->post('precio');
		$data['estado'] = $this->input->post('estado');

		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$this->input->post('id');
		$res = $this->db->update('tienda_opciones', $data, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//CAMBIO DE ESTADO DE GRUPOS/ITEMS
	public function cambiarEstado($id, $tabla)
	{
		$sql = "SELECT estado";
		$sql .= " FROM $tabla";
		$sql .= " WHERE id = $id";
		$query = $this->db->query($sql);
		$res = $query->row_array();

		if($tabla == 'tienda_productos')
		{
			if($res['estado'] == 1) { $data['estado'] = 3; } else { $data['estado'] = 1; } 
		} 
		else
		{
			if($res['estado'] == 1) { $data['estado'] = 2; } else { $data['estado'] = 1; } 
		}
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = $id";
		$res = $this->db->update($tabla, $data, $where);
		
		return (!empty($res)) ? $res : null;
	}

	public function getProductos($id = null, $parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS tienda_productos.id, tienda_productos.id_categoria, tienda_productos.titulo, tienda_productos.contenido1, tienda_productos.codigo, tienda_productos.imagen,  tienda_productos.precio, tienda_productos.precio_oferta, tienda_productos.precio_local, tienda_productos.precio_local_oferta, tienda_productos.destacado, tienda_productos.estado AS id_estado, tienda_productos_categorias.categoria
				FROM tienda_productos
				LEFT JOIN tienda_productos_categorias ON tienda_productos_categorias.id = tienda_productos.id_categoria
				LEFT JOIN tienda_configuracion ON tienda_configuracion.id = tienda_productos.id_tienda
				WHERE tienda_configuracion.grupo = ".$this->usuario->grupo;
		$sql .= " AND tienda_productos.id_tienda = $id";
		$sql .= " AND tienda_productos.estado > 0";
		
		if($parametros)
		{
			$sql .= " AND tienda_productos.id_categoria = $parametros";
		}
				
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}
	

	function comboTiendas($parametros = null)
	{
		$combo = null;
		$sql = "
				SELECT tienda_configuracion.id, tienda_configuracion.titulo AS tienda, tienda_configuracion.id_rubro, tienda_rubros.rubro
				FROM tienda_configuracion
				LEFT JOIN tienda_rubros ON tienda_rubros.id = tienda_configuracion.id_rubro
				WHERE tienda_configuracion.grupo = ?
			";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			if (!empty($parametros['estado']))
			{
				$sql .= " AND tienda_configuracion.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tienda_configuracion.estado > 0";
			}
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " titulo";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			$query = $this->db->query($sql, $placeholders);
		}
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			$res[] = '--- Selecciona una opción ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['tienda'].' ('.$value['rubro'].')';
			}
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function duplicarTienda($variables)
	{
		//SELECCIONO CATEGORIAS DE TIENDA
		$sql = "SELECT tienda_productos_categorias.*";
		$sql .= " FROM tienda_productos_categorias";
		$sql .= " WHERE tienda_productos_categorias.id_tienda = ?";
		$sql .= " AND tienda_productos_categorias.estado = ?";
		$placeholders1[] = $variables['origen'];
		$placeholders1[] = 3;
		$query = $this->db->query($sql, $placeholders1);
		$categorias = $query->result_array();

		if($categorias)
		{
			//INGRESO CATEGORIAS
			foreach($categorias as $categoria)
			{
				$datos1['id_tienda'] = $variables['destino'];
				$datos1['categoria'] = $categoria['categoria'];
				$datos1['uri'] = $categoria['uri'];
				$datos1['observaciones'] = $categoria['observaciones'];
				$datos1['imagen'] = $categoria['imagen'];
				$datos1['orden'] = $categoria['orden'];
				$datos1['delivery'] = $categoria['delivery'];
				$datos1['estado'] = $categoria['estado'];
				$datos1['fecha_alta'] = now();
				$datos1['username_alta'] = $this->usuario->id;
				$res3 = $this->db->insert('tienda_productos_categorias', $datos1);
				$id_item['id'] = $this->db->insert_id();
					
				//INGRESO PRODUCTOS DE LA CATEGORIA
				if($id_item['id'])
				{
					$sql = "SELECT *";
					$sql .= " FROM tienda_productos";
					$sql .= " WHERE id_categoria = ".$categoria['id'];
					$sql .= " AND estado = 3";
					$query = $this->db->query($sql);
					$productos = $query->result_array();

					if(!empty($productos))
					{
						foreach($productos as $producto)
						{
							$datos['id_tienda'] = $variables['destino'];
							$datos['id_categoria'] = $id_item['id'];
							$datos['titulo'] = $producto['titulo'];
							$datos['uri'] = $producto['uri'];
							$datos['contenido1'] = $producto['contenido1'];
							$datos['contenido2'] = $producto['contenido2'];
							$datos['stock'] = $producto['stock'];
							$datos['cantidad'] = $producto['cantidad'];
							$datos['maximo_por_opcion'] = $producto['maximo_por_opcion'];
							$datos['codigo'] = $producto['codigo'];
							$datos['precio'] = $producto['precio'];
							$datos['precio_delivery'] = $producto['precio_delivery'];
							$datos['precio_oferta'] = $producto['precio_oferta'];
							$datos['precio_local'] = $producto['precio_local'];
							$datos['precio_local_oferta'] = $producto['precio_local_oferta'];
							$datos['descuento'] = $producto['descuento'];
							$datos['imagen'] = $producto['imagen'];
							$datos['padre'] = $producto['padre'];
							$datos['orden'] = $producto['orden'];
							$datos['destacado'] = $producto['destacado'];
							$datos['galeria'] = $producto['galeria'];
							$datos['estado'] = $producto['estado'];
							$datos['fecha_alta'] = now();
							$datos['username_alta'] = $this->usuario->id;
							$insert = $this->db->insert('tienda_productos', $datos);
							$res['id_producto'] = $this->db->insert_id();
						}
					}
				}
			}
		}
		return (!empty($id_item)) ? $id_item : null;
	}
	
	public function getTiendasPublic($parametros = null)
	{
		$sql = "SELECT tienda_configuracion.id, tienda_configuracion.titulo, tienda_configuracion.estado AS id_estado, tienda_rubros.rubro";
		$sql .= " FROM tienda_configuracion";
		$sql .= " LEFT JOIN tienda_rubros ON tienda_rubros.id = tienda_configuracion.id_rubro";
		$sql .= " WHERE tienda_configuracion.grupo = ?";
		$placeholders[] = $this->usuario->grupo;
		
		if (!empty($parametros['estado']))
		{
			$sql .= " AND tienda_configuracion.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND tienda_configuracion.estado > 0";
		}

		if (!empty($parametros['id_tipo']))
		{
			$sql .= " AND tienda_configuracion.id_tipo = ?";
			$placeholders[] = $parametros['id_tipo'];
		}

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function getTiendas($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS tienda_configuracion.id, tienda_configuracion.titulo, tienda_configuracion.estado AS id_estado, tienda_rubros.rubro, tienda_estados.estado															
				FROM tienda_configuracion
				LEFT JOIN tienda_rubros ON tienda_rubros.id = tienda_configuracion.id_rubro
				LEFT JOIN tienda_estados ON tienda_estados.id = tienda_configuracion.estado
				WHERE tienda_configuracion.grupo = ".$this->usuario->grupo;
				
				$query = $this->db->query($sql);
				$res = $query->result_array();
	
		
		// permisos	
/*
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND tienda_configuracion.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND tienda_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND tienda_configuracion.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tienda_configuracion.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (tienda_configuracion.titulo REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (tienda_configuracion.titulo LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " titulo";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// limite
			$sql .= " LIMIT ?, ?";
			$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
			$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
*/
		
		return (!empty($res)) ? $res : null;
	}
	
    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function updateImagen($id, $id_media, $tipo, $tabla)
    {
		//SELECCIONO IMAGEN
		$sql = "SELECT media_thumbs.archivo";
		$sql .= " FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " WHERE media_thumbs.referencia = $id_media";
		$sql .= " AND media_thumbs.id_tipo = $tipo";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//INGRESO IMAGEN
		switch($tipo)
		{
			case 10: $data['logo'] = $res['archivo'];break;
			case 11: $data['imagen'] = $res['archivo'];break;
			case 21: $data['imagen'] = $res['archivo'];break;
			case 22: $data['imagen'] = $res['archivo'];break;
		}

		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = $id";
		$res = $this->db->update($tabla, $data, $where);

		return (!empty($res)) ? $res : null;
	}
	
	
	public function cambiarEstadoFromServicio($id_servicio, $estado)
	{
		$res = $this->db->update('tienda_configuracion', array('estado'=>$estado), array('id_servicio'=>$id_servicio));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function qr($id)
	{
		$sql = "
				SELECT tienda_configuracion.id, tienda_configuracion.titulo, tienda_configuracion.grupo,  IF(tienda_configuracion.url != '', CONCAT('https://', tienda_configuracion.url, '/'), grupos.url) AS url
					
				FROM tienda_configuracion
				LEFT JOIN grupos ON tienda_configuracion.grupo = grupos.id
				
				WHERE tienda_configuracion.id = ?
			";
						
		
		// consulta				
		$placeholders[] = $id;
		$query = $this->db->query($sql, $placeholders);
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}

}