<?php defined('BASEPATH') or exit('No direct script access allowed');


class Tienda_importacion_model extends CI_Model {
	
	public function getTiendasRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM datos
				
				WHERE id != 8
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getTiendaDetalleRemota($id)
	{
		$sql = "
				SELECT datos.*, usuarios.nombre
					
				FROM datos
				LEFT JOIN usuarios ON datos.id = usuarios.id_negocio
				
				WHERE datos.id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarTienda($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		$data['id_rubro'] = $valores['id_rubro'];
		
		$data['id_empresa'] = $valores['id_empresa'];
		$data['id_servicio'] = $valores['id_servicio'];
		
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
				
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['numero'])) $data['numero'] = (!empty($valores['numero'])) ? $valores['numero'] : null;
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
		if (isset($valores['provincia'])) $data['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;

		if (isset($valores['clienteMP'])) $data['clienteMP'] = (!empty($valores['clienteMP'])) ? $valores['clienteMP'] : null;
		if (isset($valores['claveMP'])) $data['claveMP'] = (!empty($valores['claveMP'])) ? $valores['claveMP'] : null;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_configuracion', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarTienda($id, $valores)
	{
		if (isset($valores['id_rubro'])) $data['id_rubro'] = (!empty($valores['id_rubro'])) ? $valores['id_rubro'] : null;
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['numero'])) $data['numero'] = (!empty($valores['numero'])) ? $valores['numero'] : null;
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
		if (isset($valores['provincia'])) $data['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;

		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		if (isset($valores['clienteMP'])) $data['clienteMP'] = (!empty($valores['clienteMP'])) ? $valores['clienteMP'] : null;
		if (isset($valores['claveMP'])) $data['claveMP'] = (!empty($valores['claveMP'])) ? $valores['claveMP'] : null;
		

		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('tienda_configuracion', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$res = $this->db->update('tienda_configuracion', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_configuracion
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Sucursales
	public function ingresarSucursal($valores)
	{
		$data['id_tienda'] = $valores['id_tienda'];
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
		if (isset($valores['contenido2'])) $data['contenido2'] = (!empty($valores['contenido2'])) ? $valores['contenido2'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['numero'])) $data['numero'] = (!empty($valores['numero'])) ? $valores['numero'] : null;
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
		if (isset($valores['provincia'])) $data['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['envios'])) $data['envios'] = (!empty($valores['envios'])) ? $valores['envios'] : null;
		if (isset($valores['costo_envio'])) $data['costo_envio'] = (!empty($valores['costo_envio'])) ? $valores['costo_envio'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : null;
		if (isset($valores['recibir_email'])) $data['recibir_email'] = (!empty($valores['recibir_email'])) ? $valores['recibir_email'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;

		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;

		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_sucursales', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarSucursal($id, $valores)
	{
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
		if (isset($valores['contenido2'])) $data['contenido2'] = (!empty($valores['contenido2'])) ? $valores['contenido2'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['numero'])) $data['numero'] = (!empty($valores['numero'])) ? $valores['numero'] : null;
		if (isset($valores['localidad'])) $data['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
		if (isset($valores['provincia'])) $data['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['envios'])) $data['envios'] = (!empty($valores['envios'])) ? $valores['envios'] : null;
		if (isset($valores['costo_envio'])) $data['costo_envio'] = (!empty($valores['costo_envio'])) ? $valores['costo_envio'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : null;
		if (isset($valores['recibir_email'])) $data['recibir_email'] = (!empty($valores['recibir_email'])) ? $valores['recibir_email'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;

		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		

		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

		$res = $this->db->update('tienda_sucursales', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteSucursal($id_tienda, $orden)
	{
		$sql = "
				SELECT id
				
				FROM tienda_sucursales
				
				WHERE id_tienda = ?
				AND orden = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id_tienda, $orden));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	// Categorias
	public function getCategoriasRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM Categorias
				
				WHERE id_negocio != 0
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCategoriaDetalleRemota($id)
	{
		$sql = "
				SELECT *
					
				FROM Categorias
				
				WHERE id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarCategoria($valores)
	{
		
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['categoria'])) $data['categoria'] = (!empty($valores['categoria'])) ? $valores['categoria'] : null;

		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 1;
		if (isset($valores['delivery'])) $data['delivery'] = (!empty($valores['delivery'])) ? $valores['delivery'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_productos_categorias', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarCategoria($id, $valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['categoria'])) $data['categoria'] = (!empty($valores['categoria'])) ? $valores['categoria'] : null;

		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 1;
		if (isset($valores['delivery'])) $data['delivery'] = (!empty($valores['delivery'])) ? $valores['delivery'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		

		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		$res = $this->db->update('tienda_productos_categorias', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteCategoria($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_productos_categorias
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Productos
	public function getProductosRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM product
				
				WHERE categoria IN (SELECT id FROM Categorias WHERE id = categoria)
				AND id_negocio IN (SELECT id FROM datos WHERE id = id_negocio)
			";

						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getProductoDetalleRemota($id)
	{
		$sql = "
				SELECT *
					
				FROM product
				
				WHERE id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarProducto($valores)
	{
		
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
		
		$data['precio'] = (!empty($valores['precio'])) ? $valores['precio'] : null;
		$data['precio_oferta'] = (!empty($valores['precio_oferta'])) ? $valores['precio_oferta'] : null;
		$data['precio_local'] = (!empty($valores['precio_local'])) ? $valores['precio_local'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 1;
		if (isset($valores['destacado'])) $data['destacado'] = (!empty($valores['destacado'])) ? $valores['destacado'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_productos', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarProducto($id, $valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
		
		$data['precio'] = (!empty($valores['precio'])) ? $valores['precio'] : null;
		$data['precio_oferta'] = (!empty($valores['precio_oferta'])) ? $valores['precio_oferta'] : null;
		$data['precio_local'] = (!empty($valores['precio_local'])) ? $valores['precio_local'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 1;
		if (isset($valores['destacado'])) $data['destacado'] = (!empty($valores['destacado'])) ? $valores['destacado'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		

		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		$res = $this->db->update('tienda_productos', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteProducto($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_productos
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Opciones Grupos
	public function getOpcionesGruposRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM grupos_opciones
				
				WHERE id_negocio IN (SELECT id FROM datos WHERE id = id_negocio)
			";

						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getOpcionGrupoDetalleRemota($id)
	{
		$sql = "
				SELECT *
					
				FROM grupos_opciones
				
				WHERE id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarOpcionGrupo($valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['opcion_grupo'])) $data['opcion_grupo'] = (!empty($valores['opcion_grupo'])) ? $valores['opcion_grupo'] : null;

		if (isset($valores['cantidad'])) $data['cantidad'] = (!empty($valores['cantidad'])) ? $valores['cantidad'] : 1;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_opciones_grupos', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarOpcionGrupo($id, $valores)
	{
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['opcion_grupo'])) $data['opcion_grupo'] = (!empty($valores['opcion_grupo'])) ? $valores['opcion_grupo'] : null;

		if (isset($valores['cantidad'])) $data['cantidad'] = (!empty($valores['cantidad'])) ? $valores['cantidad'] : 1;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		$res = $this->db->update('tienda_opciones_grupos', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteOpcionGrupo($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_opciones_grupos
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Opciones
	public function getOpcionesRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM opciones
				
				WHERE id_negocio IN (SELECT id FROM datos WHERE id = id_negocio)
				AND id_grupo IN (SELECT id FROM grupos_opciones WHERE id = id_grupo)
				AND nombre != ''
			";

						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getOpcionDetalleRemota($id)
	{
		$sql = "
				SELECT *
					
				FROM opciones
				
				WHERE id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarOpcion($valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['id_opcion_grupo'])) $data['id_opcion_grupo'] = (!empty($valores['id_opcion_grupo'])) ? $valores['id_opcion_grupo'] : null;
		if (isset($valores['opcion'])) $data['opcion'] = (!empty($valores['opcion'])) ? $valores['opcion'] : null;

		if (isset($valores['precio'])) $data['precio'] = (!empty($valores['precio'])) ? $valores['precio'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_opciones', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarOpcion($id, $valores)
	{
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['id_opcion_grupo'])) $data['id_opcion_grupo'] = (!empty($valores['id_opcion_grupo'])) ? $valores['id_opcion_grupo'] : null;
		if (isset($valores['opcion'])) $data['opcion'] = (!empty($valores['opcion'])) ? $valores['opcion'] : null;

		if (isset($valores['precio'])) $data['precio'] = (!empty($valores['precio'])) ? $valores['precio'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		$res = $this->db->update('tienda_opciones', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteOpcion($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_opciones
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Productos relaciones Opciones Grupos
	public function getProductoRelOpcionesGrupoRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM relaciones_opciones
				
				WHERE id_negocio IN (SELECT id FROM datos WHERE id = id_negocio)
				AND id_producto IN (SELECT product.id FROM product, Categorias WHERE product.id = id_producto AND product.categoria = Categorias.id)
				AND id_grupo IN (SELECT id FROM grupos_opciones WHERE id = id_grupo)
				AND id_negocio != ''
			";

						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getProductoRelOpcionGrupoDetalleRemota($id_producto, $id_opcion_grupo)
	{
		$sql = "
				SELECT *
					
				FROM relaciones_opciones
				
				WHERE id_producto = ?
				AND id_grupo = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders[] = $id_producto;
			$placeholders[] = $id_opcion_grupo;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarProductoRelOpcionGrupo($valores)
	{
		$data['id_tienda'] = $valores['id_tienda'];
		
		$data['id_producto'] = $valores['id_producto'];
		$data['id_opcion_grupo'] = $valores['id_opcion_grupo'];

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_producto_rel_opciones_grupo', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteProdcutoRelOpcionGrupo($id_producto, $id_opcion_grupo)
	{
		$sql = "
				SELECT true
				
				FROM tienda_producto_rel_opciones_grupo
				
				WHERE id_producto = ?
				AND id_opcion_grupo = ?
			";
		
		$placeholders[] = $id_producto;
		$placeholders[] = $id_opcion_grupo;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Pedidos
	public function getPedidosRemota($parametros = null)
	{
		$sql = "
				SELECT *
					
				FROM cart
				
				WHERE id_negocio IN (SELECT id FROM datos WHERE id = id_negocio)
			";

						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getPedidoDetalleRemota($id)
	{
		$sql = "
				SELECT *
					
				FROM cart
				
				WHERE id = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders['id'] = $id;
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarPedido($valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		if (isset($valores['id_sucursal'])) $data['id_sucursal'] = (!empty($valores['id_sucursal'])) ? $valores['id_sucursal'] : null;
		
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['id_medio_envio'])) $data['id_medio_envio'] = (!empty($valores['id_medio_envio'])) ? $valores['id_medio_envio'] : null;
		
		if (isset($valores['items_anteriores'])) $data['items_anteriores'] = (!empty($valores['items_anteriores'])) ? $valores['items_anteriores'] : null;
		if (isset($valores['envio'])) $data['envio'] = (!empty($valores['envio'])) ? $valores['envio'] : null;
		if (isset($valores['total'])) $data['total'] = (!empty($valores['total'])) ? $valores['total'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		if (isset($valores['fecha_alta_utc'])) $data['fecha_alta_utc'] = (!empty($valores['fecha_alta_utc'])) ? $valores['fecha_alta_utc'] : now();
		
		if (isset($valores['fecha_alta'])) $data['fecha_alta'] = (!empty($valores['fecha_alta'])) ? $valores['fecha_alta'] : now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_pedidos', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarPedido($id, $valores)
	{
		if (isset($valores['id'])) $data['id'] = (!empty($valores['id'])) ? $valores['id'] : null;
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		if (isset($valores['id_sucursal'])) $data['id_sucursal'] = (!empty($valores['id_sucursal'])) ? $valores['id_sucursal'] : null;
		
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['id_medio_envio'])) $data['id_medio_envio'] = (!empty($valores['id_medio_envio'])) ? $valores['id_medio_envio'] : null;
		
		if (isset($valores['items_anteriores'])) $data['items_anteriores'] = (!empty($valores['items_anteriores'])) ? $valores['items_anteriores'] : null;
		if (isset($valores['envio'])) $data['envio'] = (!empty($valores['envio'])) ? $valores['envio'] : null;
		if (isset($valores['total'])) $data['total'] = (!empty($valores['total'])) ? $valores['total'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;


		$res = $this->db->update('tienda_pedidos', $data, array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExistePedido($id)
	{
		$sql = "
				SELECT true
				
				FROM tienda_pedidos
				
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Forma de pago
	public function ingresarFormaDePago($valores)
	{
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : null;
		if (isset($valores['recargo'])) $data['recargo'] = (!empty($valores['recargo'])) ? $valores['recargo'] : null;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_rel_forma_pago_tienda', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteFormaDePago($id_tienda, $id_forma_pago)
	{
		$sql = "
				SELECT true
				
				FROM tienda_rel_forma_pago_tienda
				
				WHERE id_tienda = ?
				AND id_forma_pago = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id_tienda, $id_forma_pago));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	// Forma de envío
	public function ingresarFormaDeEnvio($valores)
	{
		if (isset($valores['id_tienda'])) $data['id_tienda'] = (!empty($valores['id_tienda'])) ? $valores['id_tienda'] : null;
		
		if (isset($valores['id_envio'])) $data['id_envio'] = (!empty($valores['id_envio'])) ? $valores['id_envio'] : null;
		
		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		if (isset($valores['tipo'])) $data['tipo'] = (!empty($valores['tipo'])) ? $valores['tipo'] : null;
		if (isset($valores['recargo'])) $data['recargo'] = (!empty($valores['recargo'])) ? $valores['recargo'] : null;
		if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : null;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tienda_rel_envios_tienda', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteFormaDeEnvio($id_tienda, $id_envio)
	{
		$sql = "
				SELECT true
				
				FROM tienda_rel_envios_tienda
				
				WHERE id_tienda = ?
				AND id_envio = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id_tienda, $id_envio));
		
		return ($query->row_array()) ? true : false;
	}

}