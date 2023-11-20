<?php defined('BASEPATH') or exit('No direct script access allowed');

class Categorias_model extends CI_Model {

	//LISTADO GENERAL
	public function getCategorias($parametros = null)
	{
		$sql = "SELECT id, categoria, padre, orden, estado,
				(SELECT categoria FROM con_car_productos_categorias AS padre WHERE padre.id = con_car_productos_categorias.padre) AS padre_nombre";
		$sql .= " FROM con_car_productos_categorias";
		$sql .= " WHERE grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		if (isset($parametros['padre']))
		{
			$sql .= " AND padre = ?";
			$placeholders[] = $parametros['padre'];
		}
		if (isset($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado >= 0";
		}
		$sql .= " ORDER BY orden ASC, id ASC";
		
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
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
			$sql .= " AND con_configuracion_idiomas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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

	//COMBO 
	public function comboCategorias($parametros = null)
	{
		$sql = "SELECT id, categoria FROM con_car_productos_categorias";
		$sql .= " WHERE grupo = ? AND id_empresa = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		
		if(isset($parametros['padre']))
		{
			$sql .= " AND con_car_productos_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}
		if(isset($parametros['hijos']))
		{
			$sql .= " AND con_car_productos_categorias.padre > 0";
		}


		$sql .= " AND estado > 0";
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['categoria'];
		}
		return (!empty($padre)) ? $padre : null;
	}

	//DETALLE
	public function detalleCategoria($id)
	{
		$sql = "SELECT id, categoria, uri, imagen, padre, orden, estado, idioma, seccion,
				(SELECT categoria FROM con_car_productos_categorias AS padre WHERE padre.id = con_car_productos_categorias.padre) AS padre_nombre,
				CASE
					WHEN estado = 1 THEN 'Inactivo'
					WHEN estado = 2 THEN 'Activo'
					END AS estado_nombre
				FROM con_car_productos_categorias";
		
		$sql .= " 
				WHERE grupo = ?
				AND estado > 0
				AND id = ?";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		
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
	public function ingresarCategoria($valores)
	{
		//TRAIGO ORDEN ANTERIOR SEGUN PADRE
		$sql = "SELECT id, orden";
		$sql .= " FROM con_car_productos_categorias";
		$sql .= " WHERE grupo = ?";
		$sql .= " AND id_empresa = ?";
		$sql .= " AND padre = ?";
		$sql .= " AND estado > 0";
		$sql .= " ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $valores['padre'];
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

		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['categoria'] = trim($valores['categoria']);
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_car_productos_categorias', $data);
		$res['id'] = $this->db->insert_id();
		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR 
	public function modificarCategoria($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['categoria'] = trim($valores['categoria']);
		$data['seccion'] = $valores['menu'];
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_car_productos_categorias', $data, $where);
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR 
	public function ingresarSeccion($valores)
	{
		//VERIFICO SI HAY SECCION CON ESA CATEGORIA
		$sql = "SELECT id, padre";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE grupo = ?";
		$sql .= " AND id_empresa = ?";
		$sql .= " AND categoria = ?";
		$sql .= " AND estado > 0";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $valores['id'];
		$query = $this->db->query($sql, $placeholders);
		$seccion = $query->row_array();
		
		if($valores['padre'] > 0)
		{
			$sql2 = "SELECT id, seccion";
			$sql2 .= " FROM con_secciones";
			$sql2 .= " WHERE grupo = ?";
			$sql2 .= " AND id_empresa = ?";
			$sql2 .= " AND categoria = ?";
			$sql2 .= " AND estado > 0";
			$placeholders2[] = $this->usuario->grupo;
			$placeholders2[] = $this->usuario->id_empresa;
			$placeholders2[] = $valores['padre'];
			$query = $this->db->query($sql2, $placeholders2);
			$padre = $query->row_array();
		}

		if(isset($padre['id'])) { $data['padre'] = $padre['id']; } else { $data['padre'] = null; }
		$data['seccion'] = trim($valores['categoria']);
		$data['descripcion'] = trim($valores['categoria']);
		$data['contenido1'] = trim($valores['categoria']);
		$data['url'] = url_title(convert_accented_characters(strtolower($valores['categoria'])));
		if ($valores['menu'] == 1) { $data['categoria'] = $valores['id']; } else { $data['categoria'] = null; }
		if ($valores['estado'] == 1) { $data['estado'] = 1; } else { $data['estado'] = 3; }
		if ($valores['estado'] == 1) { $data['publicar'] = 1; } else { $data['publicar'] = 3; }

		if($seccion['id'])
		{
			$data['fecha_modificacion'] = now();
			$data['user_modificacion'] = $this->usuario->id;
			$where= "id = ".$seccion['id'];
			$update = $this->db->update('con_secciones', $data, $where);
			$res['id'] = $seccion['id'];
		}
		else
		{
			$data['grupo'] = $this->usuario->grupo;
			$data['id_empresa'] = $this->usuario->id_empresa;
			$data['id_secciones_tipo'] = 1;
			if ($valores['menu'] == 1) { $data['categoria'] = $valores['id']; } else { $data['categoria'] = null; }
			$data['orden'] = 1;
			$data['idioma'] = $valores['idioma'];
			$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$data['user_alta'] = $this->usuario->id;
			$insert = $this->db->insert('con_secciones', $data);
			$res['id'] = $this->db->insert_id();
		}
		return (!empty($res)) ? $res : null;
	}

	//DUPLICAR 
	public function duplicarCategoria($id)
	{
		$sql = "SELECT *";
		$sql .= " FROM con_car_productos_categorias";
		$sql .= " WHERE con_car_productos_categorias.estado > 0";
		$sql .= " AND con_car_productos_categorias.id = ?";
		$placeholders[] = $id; 
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$data['grupo'] = $res['grupo'];
			$data['id_empresa'] = $res['id_empresa'];
			$data['idioma'] = $res['idioma'];
			$data['categoria'] = $res['categoria'].'-copy';
			$data['uri'] = $res['uri'].'-copy';
			$data['imagen'] = $res['imagen'];
			$data['padre'] = $res['padre'];
			$data['orden'] = $res['orden'];
			$data['orden'] = $res['orden'];
			$data['estado'] = 1; //Inactivo
			$data['fecha_alta'] = now();
			$data['username_alta'] = $this->usuario->id;

			$insert = $this->db->insert('con_car_productos_categorias', $data);
			$res['id'] = $this->db->insert_id();
		}
		return (!empty($res)) ? $res : null;
	}

	//ORDENAR
	public function ordenarCategorias($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_alta'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_car_productos_categorias', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		return (!empty($res)) ? $res : null;
	}
	
	//CAMBIAR PUBLICACION
	public function publicarCategoria($valores)
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
		$update = $this->db->update('con_car_productos_categorias', $data, $where);
		$whereproductos = "id_categoria = ".$valores['id'];
		$updateproductos = $this->db->update('con_car_productos', $data, $whereproductos);

		//MODIFICO PRODUCTOS ITEMS
		$sql1 = "SELECT id FROM con_car_productos WHERE id_categoria = ?";
		$placeholders1[] = $valores['id'];
		$query = $this->db->query($sql1, $placeholders1);
		$items = $query->result_array();

		foreach($items as $item)
		{
			$whereitem= "id = ".$item['id'];
			$updateitem = $this->db->update('con_car_productos_items', $data, $whereitem);
		}

		//VERIFICO SI TIENE SUBCATEGORIAS
		$sql = "SELECT id, estado FROM con_car_productos_categorias WHERE padre = ?";
		$placeholders[] = $valores['id'];
		$query = $this->db->query($sql, $placeholders);
		$hijos = $query->result_array();

		//MODIFICO SUBCATEGORIAS Y PRODUCTOS
		foreach($hijos as $hijo)
		{
			$wherehijo= "id = ".$hijo['id'];
			$update = $this->db->update('con_car_productos_categorias', $data, $wherehijo);
			$where2 = "id_categoria = ".$hijo['id'];
			$updatehijo = $this->db->update('con_car_productos', $data, $where2);
		}
		return ($updateproductos);
	}

	//ELIMINAR
	public function eliminarCategoria($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_car_productos_categorias', $data, $where);
		
		$whereproductos = "id_categoria = ".$valores['id'];
		$updateproductos = $this->db->update('con_car_productos', $data, $whereproductos);
		return $res;
	}
}