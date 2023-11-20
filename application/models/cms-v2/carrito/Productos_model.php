<?php defined('BASEPATH') or exit('No direct script access allowed');

class Productos_model extends CI_Model {

	//LISTADO GENERAL
	public function getProductos($parametros = null)
	{
		$sql = "SELECT con_car_productos_items.*, con_car_productos_categorias.id as id_categoria, con_car_productos_categorias.padre as categoria_padre, con_car_productos_categorias.categoria";
		$sql .= " FROM con_car_productos_items";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_car_productos.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_car_productos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_car_productos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_car_productos_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['padre']))
		{
			$sql .= " AND con_car_productos_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_car_productos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_car_productos_items.estado >= 0";
		}
		
		if (isset($parametros['orden']))
		{
			switch($parametros['orden'])
			{
				case 0: $sql .= " ORDER BY con_car_productos_items.orden ASC, con_car_productos.id ASC"; break;
				case 1: $sql .= " ORDER BY con_car_productos_items.precio ASC"; break;
				case 2: $sql .= " ORDER BY con_car_productos_items.precio DESC"; break;
				case 3: $sql .= " ORDER BY con_car_productos_items.titulo ASC"; break;
				case 4: $sql .= " ORDER BY con_car_productos_items.titulo DESC"; break;
			}
		}
		else
		{
			$sql .= " ORDER BY con_car_productos_items.orden ASC, con_car_productos.id ASC";
			
		}
		
		if (isset($parametros['limit']))
		{
			$sql .= " LIMIT ?";
			$placeholders[] = $parametros['limit'];
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	//DESTACADOS
	public function getProductosDestacados($parametros = null)
	{
		$sql = "SELECT con_car_productos_items.*, con_car_productos_categorias.id as id_categoria, con_car_productos_categorias.padre as categoria_padre, con_car_productos_categorias.categoria";
		$sql .= " FROM con_car_productos_items";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_car_productos.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_car_productos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_car_productos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_car_productos_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['padre']))
		{
			$sql .= " AND con_car_productos_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_car_productos_categorias.estado = ?";
			$sql .= " AND con_car_productos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_car_productos_items.estado >= 0";
		}

		$sql .= " AND con_car_productos_items.destacado = 1";
		$sql .= " ORDER BY RAND()";		

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	//LISTADO GENERAL
	public function getProductosOferta($parametros = null)
	{
		$sql = "SELECT con_car_productos_items.*, con_car_productos_categorias.id as id_categoria, con_car_productos_categorias.padre as categoria_padre, con_car_productos_categorias.categoria";
		$sql .= " FROM con_car_productos_items";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_car_productos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_car_productos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_car_productos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_car_productos_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['padre']))
		{
			$sql .= " AND con_car_productos_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		if (isset($parametros['destacado']))
		{
			$sql .= " AND con_car_productos_items.destacado = ?";
			$placeholders[] = $parametros['destacado'];
		}
		
		if (isset($parametros['estado']))
		{
			$sql .= " AND con_car_productos_categorias.estado = ?";
			$sql .= " AND con_car_productos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_car_productos_items.estado >= 0";
		}
		$sql .= " AND con_car_productos_items.precio_oferta > 0";

		if (isset($parametros['destacado']))
		{
			$sql .= " ORDER BY con_car_productos_items.orden ASC, con_car_productos.id ASC";
		}
		else
		{
			$sql .= " ORDER BY con_car_productos_items.orden ASC, con_car_productos.id ASC";
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}
	
	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT con_car_productos_items.*, con_car_productos_categorias.id as id_categoria, con_car_productos_categorias.padre as categoria_padre, con_car_productos_categorias.categoria";
		$sql .= " FROM con_car_productos_items";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_car_productos.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_car_productos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_car_productos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_car_productos_items.estado = 2";
		$sql .= " AND con_car_productos_items.titulo LIKE '%".$parametros['busqueda']."%'";
		$sql .= " ORDER BY con_car_productos_items.titulo ASC";
		
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	//IDIOMAS DEL SITIO
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
	
	//DETALLE
	public function detalleProducto($parametros)
	{
		$sql = "SELECT con_car_productos_items.*, con_car_productos_categorias.id as id_categoria, con_car_productos_categorias.categoria, con_car_productos_categorias.padre";
		$sql .= " FROM con_car_productos_items";
		$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
		$sql .= " LEFT JOIN con_car_productos_categorias ON con_car_productos_categorias.id = con_car_productos.id_categoria";
		$sql .= " WHERE con_car_productos.grupo = ? AND con_car_productos.id_empresa = ? AND con_car_productos.id = ?";
		$sql .= " AND con_car_productos_items.idioma = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id'];
		$placeholders[] = $parametros['idioma'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_car_productos_items.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_car_productos_items.estado >= 0";
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

	//INGRESAR 
	public function ingresarProducto($valores)
	{
		$this->load->helper('text');
		
		//INGRESO PRODUCTO GENERAL
		$data1['grupo'] = $this->usuario->grupo;
		$data1['id_empresa'] = $this->usuario->id_empresa;
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['titulo'] = trim($valores['titulo']);
		if (!empty($valores['estado'])) $data1['estado'] = $valores['estado'];
		$data1['fecha_alta'] = now();
		$data1['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_car_productos', $data1);
		$res['id'] = $this->db->insert_id();
		
		if($res['id'])
		{
			//TRAIGO ORDEN ANTERIOR SEGUN PADRE
			$sql = "SELECT con_car_productos_items.id, con_car_productos_items.orden";
			$sql .= " FROM con_car_productos_items";
			$sql .= " LEFT JOIN con_car_productos ON con_car_productos.id = con_car_productos_items.id_producto";
			$sql .= " WHERE con_car_productos.grupo = ? AND con_car_productos.id_empresa = ? AND con_car_productos.id_categoria = ?";
			$sql .= " AND con_car_productos_items.estado > 0";
			$sql .= " ORDER BY con_car_productos_items.orden DESC LIMIT 1";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $valores['id_categoria'];
			$query = $this->db->query($sql, $placeholders);
			$orden = $query->row_array();
	
			if($orden)
			{
				$data['orden'] = $orden['orden']+1;
			}
			else
			{
				$data['orden'] = 0;
			}
	
			$data['id_producto'] = $res['id'];
			if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
			$data['uri'] = trim(strtolower(url_title(convert_accented_characters($valores['titulo']))));
			$data['titulo'] = trim($valores['titulo']);
			if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
			if (isset($valores['contenido2'])) $data['contenido2'] = (!empty($valores['contenido2'])) ? $valores['contenido2'] : null;
			if (isset($valores['stock'])) $data['stock'] = (!empty($valores['stock'])) ? $valores['stock'] : null;
			$data['precio'] = $valores['precio'];
			if (isset($valores['precio_oferta'])) $data['precio_oferta'] = (!empty($valores['precio_oferta'])) ? $valores['precio_oferta'] : null;
			if (isset($valores['precio_adicional'])) $data['precio_adicional'] = (!empty($valores['precio_adicional'])) ? $valores['precio_adicional'] : null;
			if (isset($valores['precio_adicional_oferta'])) $data['precio_adicional_oferta'] = (!empty($valores['precio_adicional_oferta'])) ? $valores['precio_adicional_oferta'] : null;
			if (isset($valores['cantidad'])) $data['cantidad'] = (!empty($valores['cantidad'])) ? $valores['cantidad'] : null;
			if (isset($valores['maximo_por_opcion'])) $data['maximo_por_opcion'] = (!empty($valores['maximo_por_opcion'])) ? $valores['maximo_por_opcion'] : null;
			if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : null;
			if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
			if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;
			if (isset($valores['galeria'])) $data['galeria'] = (!empty($valores['galeria'])) ? $valores['galeria'] : null;
			$data['destacado'] = $valores['destacado'];
			$data['estado'] = $valores['estado'];
			$data['fecha_alta'] = now();
			$data['username_alta'] = $this->usuario->id;
	
			$insert = $this->db->insert('con_car_productos_items', $data);
			$res2['id'] = $this->db->insert_id();
		}
		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR 
	public function modificarProducto($valores)
	{
		$this->load->helper('text');
		
		//INGRESO PRODUCTO GENERAL
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['titulo'] = trim($valores['titulo']);
		if (!empty($valores['estado'])) $data1['estado'] = $valores['estado'];
		$data1['fecha_modificacion'] = now();
		$data1['username_modificacion'] = $this->usuario->id;

		$where1 = "id = ".$valores['id_producto'];
		$res = $this->db->update('con_car_productos', $data1, $where1);		

		if($res)
		{
			$data['uri'] = trim(strtolower(url_title(convert_accented_characters($valores['titulo']))));
			$data['titulo'] = trim($valores['titulo']);
			if (isset($valores['contenido1'])) $data['contenido1'] = (!empty($valores['contenido1'])) ? $valores['contenido1'] : null;
			if (isset($valores['contenido2'])) $data['contenido2'] = (!empty($valores['contenido2'])) ? $valores['contenido2'] : null;
			if (isset($valores['stock'])) $data['stock'] = (!empty($valores['stock'])) ? $valores['stock'] : null;
			$data['precio'] = $valores['precio'];
			if (isset($valores['precio_oferta'])) $data['precio_oferta'] = (!empty($valores['precio_oferta'])) ? $valores['precio_oferta'] : null;
			if (isset($valores['precio_adicional'])) $data['precio_adicional'] = (!empty($valores['precio_adicional'])) ? $valores['precio_adicional'] : null;
			if (isset($valores['precio_adicional_oferta'])) $data['precio_adicional_oferta'] = (!empty($valores['precio_adicional_oferta'])) ? $valores['precio_adicional_oferta'] : null;
			if (isset($valores['cantidad'])) $data['cantidad'] = (!empty($valores['cantidad'])) ? $valores['cantidad'] : null;
			if (isset($valores['maximo_por_opcion'])) $data['maximo_por_opcion'] = (!empty($valores['maximo_por_opcion'])) ? $valores['maximo_por_opcion'] : null;
			if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : null;
			if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
			if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;
			if (isset($valores['galeria'])) $data['galeria'] = (!empty($valores['galeria'])) ? $valores['galeria'] : null;
			$data['destacado'] = $valores['destacado'];
			$data['estado'] = $valores['estado'];
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
	
			$where = "id = ".$valores['id'];
			$update = $this->db->update('con_car_productos_items', $data, $where);		
		}
		return (!empty($res)) ? $res : null;
	}

	//DUPLICAR 
	public function duplicarProducto($id)
	{
		$sql = "SELECT *";
		$sql .= " FROM con_car_productos";
		$sql .= " WHERE con_car_productos.estado > 0";
		$sql .= " AND con_car_productos.id = ?";
		$placeholders[] = $id; 
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$data1['grupo'] = $this->usuario->grupo;
			$data1['id_empresa'] = $this->usuario->id_empresa;
			$data1['id_categoria'] = $res['id_categoria'];
			$data1['template'] = $res['template'];
			$data1['titulo'] = $res['titulo'].'-copy';
			$data1['estado'] = 1;
			$data1['fecha_alta'] = now();
			$data1['username_alta'] = $this->usuario->id;
	
			$insert1 = $this->db->insert('con_car_productos', $data1);
			$producto['id'] = $this->db->insert_id();

			if($producto['id'])
			{
				$sql = "SELECT *";
				$sql .= " FROM con_car_productos_items";
				$sql .= " WHERE id_producto = $id";
				$sql .= " AND estado > 0";
				$query = $this->db->query($sql);
				$items = $query->result_array();
				
				foreach($items as $item)
				{
					$data['id_producto'] = $producto['id'];
					$data['idioma'] = $item['idioma'];
					$data['uri'] = $item['uri'].'-copy';
					$data['titulo'] = $item['titulo'].'-copy';
					$data['contenido1'] = $item['contenido1'];
					$data['contenido2'] = $item['contenido2'];
					$data['stock'] = $item['stock'];
					$data['precio'] = $item['precio'];
					$data['precio_oferta'] = $item['precio_oferta'];
					$data['precio_adicional'] = $item['precio_adicional'];
					$data['precio_adicional_oferta'] = $item['precio_adicional_oferta'];
					$data['cantidad'] = $item['cantidad'];
					$data['maximo_por_opcion'] = $item['maximo_por_opcion'];
					$data['descuento'] = $item['descuento'];
					$data['padre'] = $item['padre'];
					$data['codigo'] = $item['codigo'];
					$data['galeria'] = $item['galeria'];
					$data['destacado'] = $item['destacado'];
					$data['estado'] = $item['estado'];
					$data['fecha_alta'] = now();
					$data['username_alta'] = $this->usuario->id;
			
					$insert = $this->db->insert('con_car_productos_items', $data);
					$producto_item['id'] = $this->db->insert_id();
				}
			}
		}
		return (!empty($producto)) ? $producto : null;
	}

	//ORDENAR
	public function ordenarProductos($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_alta'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_car_productos_items', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		return (!empty($res)) ? $res : null;
	}

	//CAMBIAR PUBLICACION
	public function publicarProducto($valores)
	{
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		if ($valores['estado'] == 2)
		{
			$data['estado'] = 1;
		}		
		else
		{
			$data['estado'] = 2;
		}		

		//MODIFICO CATEGORIAS Y PRODUCTOS
		$where = "id = ".$valores['id'];
		$update = $this->db->update('con_car_productos_items', $data, $where);

		return (!empty($update)) ? $update : null;
	}

	//ACTUALIZACION MASIVA
	public function actualizarProductos($valores)
	{
		$this->load->helper('text');

		//TRAIGO LOS IDS Y LOS CUENTO
		$array = $valores['id'];
		$array = count($array);

		//TRAIGO TODOS LOS DATOS
		$array2 = $valores;

		//RECORRO EL ARRAY Y ASIGNO VALORES
		for ($i = 0; $i < $array; $i++) 
		{
			//MODIFICO PRODUCTO GENERAL
			$data1['id_categoria'] = $array2['id_categoria'][$i];
			$data1['titulo'] = trim($array2['titulo'][$i]);
			$where1 = "id = ".$array2['id_producto'][$i];
			$res = $this->db->update('con_car_productos', $data1, $where1);
			
			//MODIFICO ITEM
			$data['codigo'] = $array2['codigo'][$i];
			$data['titulo'] = trim($array2['titulo'][$i]);
			$data['contenido1'] = $array2['contenido1'][$i];
			$data['uri'] = trim(strtolower(url_title(convert_accented_characters($array2['titulo'][$i]))));
		    $data['precio'] = $array2['precio'][$i];
		    $data['precio_oferta'] = $array2['precio_oferta'][$i];
		    $data['estado'] = $array2['estado'][$i];
		    $data['destacado'] = $array2['destacado'][$i];
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
	
			$where = "id = ".$array2['id'][$i];
			$res = $this->db->update('con_car_productos_items', $data, $where);
		}
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR IMAGEN EN CONTENIDOS
	public function updateImagen($id, $imagen, $extension, $id_imagen_tipo)
	{
		$sql = "SELECT media_thumbs.archivo";
		$sql .= " FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " WHERE media_thumbs.referencia = $imagen";
		$sql .= " AND media_thumbs.id_tipo = $id_imagen_tipo";
		$query = $this->db->query($sql);
		$res = $query->row_array();
		
		//INGRESO IMAGEN
		$data['imagen'] = $res['archivo'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$res2 = $this->db->update('con_car_productos_items', $data, array("id" => $id, "idioma" => $extension));

		return (!empty($res2)) ? $res2 : null;
	}
	
	//ELIMINAR
	public function eliminarProducto($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_car_productos_items', $data, $where);
		
		return (!empty($res)) ? $res : null;
	}
}