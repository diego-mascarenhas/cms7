<?php defined('BASEPATH') or exit('No direct script access allowed');

class Paginas_model extends CI_Model {

	//API
	function menu($padre = null, $seleccionada = false, $niveles = 10, $nivel = null)
	{
		$sql = "SELECT con_secciones.id, con_secciones.url, con_secciones.seccion, con_secciones.descripcion, con_secciones.contenido1, con_secciones.categoria, con_secciones.padre as id_padre, ";
		$sql .= (isset($padre))  ? "(SELECT seccion FROM con_secciones WHERE con_secciones.id = id_padre) AS padre"  : " con_secciones.seccion as padre";
		$sql .= " FROM con_secciones				
				WHERE con_secciones.estado = 3
				AND con_secciones.publicar = 3
				AND con_secciones.id_secciones_tipo = 1
				AND con_secciones.grupo = ". $this->usuario->grupo ."
				AND con_secciones.id_empresa = ".$this->usuario->id_empresa;
		
		$sql .= (isset($padre)) ? " AND con_secciones.padre = $padre" : " AND con_secciones.padre IS NULL";
		$sql .= " ORDER BY con_secciones.orden ASC";
		
		$query = $this->db->query($sql);
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				$res[] = array(
								'id'=>$row['id'],
								'seccion'=>$row['seccion'],
								'descripcion'=>$row['descripcion'],
								'contenido1'=>$row['contenido1'],
								'categoria'=>$row['categoria'],
								'url'=>$row['url'],
								'id_padre'=>$row['id_padre'],
								'padre'=>$row['padre'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->menu($row['id'], $seleccionada, $niveles, $nivel)
								);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}

	public function getEncabezado($id, $idioma, $id_imagen)
	{
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenidos.estado, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = $id_imagen LIMIT 1) AS imagen_breadcrumb";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " WHERE con_contenidos.grupo = ?";
		$sql .= " AND con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getEncabezadoAdicional($parametros)
	{
		$id_imagen = $parametros['id_imagen'];
		$sql = "SELECT con_contenido_items_adicionales.titulo, media.id as id_media,(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = $id_imagen LIMIT 1) AS imagen_breadcrumb";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items_adicionales.id";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " WHERE con_contenidos.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_contenido_items_adicionales.id = ?";
		$placeholders[] = $parametros['id'];
		$sql .= " AND con_contenido_items_adicionales.id_con_contenido = ?";
		$placeholders[] = $parametros['id_contenido'];
		$sql .= " AND con_contenido_items_adicionales.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_rel_contenidos_media.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_rel_contenidos_media.id_tipo = ?";
		$placeholders[] = $parametros['id_imagen'];

		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ? LIMIT 1";
			$placeholders[] = $parametros['estado'];
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, con_contenido_items.url, con_contenido_items.contenido1, con_secciones.seccion, con_contenidos.estado";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " WHERE con_contenidos.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_contenido_items.idioma = '".$parametros['idioma']."'";
		$sql .= " AND con_secciones.id_secciones_tipo = 9";
		$sql .= " AND con_contenido_items.titulo LIKE '%".$parametros['busqueda']."%'";

		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		$sql .= " ORDER BY con_contenido_items.titulo ASC";

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	function getPaginasPublic($parametros)
	{
		$sql = "SELECT con_contenidos.id_con_secciones, con_secciones.url, con_contenido_items.titulo, con_contenido_items.texto_adicional, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 12 LIMIT 1) AS imagen
		FROM con_contenidos				
		LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones
		LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id
		LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id
		LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media
		WHERE con_contenidos.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		
		$sql .= " AND con_rel_contenidos_media.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		
		if (isset($parametros['padre']))
		{
			$sql .= " AND con_secciones.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		$query = $this->db->query($sql, $placeholders);							
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	function getPaginasInternas($parametros)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_secciones.url, con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.texto_adicional, con_contenido_items.contenido1, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = ".$parametros['imagen_tipo']." LIMIT 1) AS imagen
		FROM con_contenidos				
		LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones
		LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id
		LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id
		LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media
		WHERE con_contenidos.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_rel_contenidos_media.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_rel_contenidos_media.id_tipo = ?";
		$placeholders[] = $parametros['imagen_tipo'];
		
		if (isset($parametros['padre']))
		{
			$sql .= " AND con_secciones.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		$query = $this->db->query($sql, $placeholders);							
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}
	//FIN API

	
	function getPaginas($padre = null, $seleccionada = false, $niveles = 10, $nivel = null, $parametros = null)
	{
		$sql = "SELECT con_secciones.id, con_secciones.estado, con_secciones.url, con_secciones.seccion, con_secciones.categoria,  con_contenidos.id as id_contenido, con_secciones.padre as id_padre, (SELECT seccion FROM con_secciones WHERE con_secciones.id = id_padre) as padre
				FROM con_secciones				
				LEFT JOIN con_contenidos ON con_contenidos.id_con_secciones = con_secciones.id
				WHERE con_secciones.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_secciones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= "AND con_secciones.estado > 0
				AND con_secciones.id_secciones_tipo = 1";
		$sql .= (isset($padre)) ? " AND con_secciones.padre = $padre" : " AND con_secciones.padre IS NULL";
		
		$sql .= " ORDER BY con_secciones.orden ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				
				$res[] = array(
								'id'=>$row['id'],
								'id_contenido'=>$row['id_contenido'],
								'seccion'=>$row['seccion'],
								'url'=>$row['url'],
								'estado'=>$row['estado'],
								'categoria'=>$row['categoria'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->getPaginas($row['id'], $seleccionada, $niveles, $nivel)
								);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
/*
	function getPaginas($padre = null, $seleccionada = false, $niveles = 10, $nivel = null, $parametros = null)
	{
		$sql = "SELECT con_secciones.id, con_secciones.publicar, con_secciones.estado, con_secciones.url, con_secciones.seccion, con_secciones.categoria,  con_contenidos.id as id_contenido, con_secciones.padre as id_padre, (SELECT seccion FROM con_secciones WHERE con_secciones.id = id_padre) as padre
				FROM con_secciones				
				LEFT JOIN con_contenidos ON con_contenidos.id_con_secciones = con_secciones.id
				WHERE con_secciones.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_secciones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= "AND con_secciones.estado > 0
				AND con_secciones.id_secciones_tipo = 1";
		$sql .= (isset($padre)) ? " AND con_secciones.padre = $padre" : " AND con_secciones.padre IS NULL";
		
		$sql .= " ORDER BY con_secciones.orden ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				
				$res[] = array(
								'id'=>$row['id'],
								'id_contenido'=>$row['id_contenido'],
								'seccion'=>$row['seccion'],
								'url'=>$row['url'],
								'estado'=>$row['estado'],
								'publicar'=>$row['publicar'],
								'categoria'=>$row['categoria'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->getPaginas($row['id'], $seleccionada, $niveles, $nivel)
								);
			}
		}
			
		return (!empty($res)) ? $res : null;
	}
*/
	
	public function getIdiomas($parametros = null)
	{
		$sql = "SELECT con_configuracion_idiomas.id, con_configuracion_idiomas.idioma, con_configuracion_idiomas.extension, con_configuracion_idiomas.orden";
		$sql .= " FROM con_configuracion_idiomas";
		$sql .= " WHERE con_configuracion_idiomas.grupo = ?";
		
		// permisos
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
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " con_configuracion_idiomas.id";
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

	public function getMedia($id, $idioma, $id_tipo)
	{
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = $id_tipo LIMIT 1) AS imagen_breadcrumb";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id";
		$sql .= " WHERE con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";
		$sql .= " AND media_thumbs.id_tipo = $id_tipo";
		$sql .= " ORDER BY con_rel_contenidos_media.id DESC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getMedia2($id, $idioma, $id_tipo)
	{
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, media.id as id_media, media_thumbs.archivo as imagen";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id";
		$sql .= " WHERE con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";
		$sql .= " AND media_thumbs.id_tipo = $id_tipo";
		$sql .= " ORDER BY con_rel_contenidos_media.id DESC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getArchivo($id, $idioma)
	{
		$sql = "SELECT media.nombre, media.archivo FROM media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND con_rel_contenidos_media.idioma = '".$idioma."'";
		$sql .= " AND media.id_tipo = 9";
		$sql .= " ORDER BY con_rel_contenidos_media.id ASC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR CONTENIDO CON Y SIN TEMPLATE
	public function modificarPagina($id, $valores)
	{
		$this->load->helper('date');

		//MODIFICAR CONTENIDO GENERAL
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$wherecon = "id = $id";
		$updatecon = $this->db->update('con_contenidos', $data, $wherecon);

		if (!isset($updatecon['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$item['idioma'] = $idioma['extension'];
					$item['titulo'] = $valores['titulo_'.$extension];
					if(isset($valores['url_'.$extension])) { $item['url'] = $valores['url_'.$extension]; }
					if(isset($valores['subtitulo_'.$extension])) { $item['subtitulo'] = $valores['subtitulo_'.$extension]; } else { $item['subtitulo'] = NULL; }
					if(isset($valores['texto_adicional_'.$extension])) { $item['texto_adicional'] = $valores['texto_adicional_'.$extension]; } else { $item['texto_adicional'] = NULL; }
					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; } else { $item['contenido1'] = NULL; }
					if(isset($valores['contenido2_'.$extension])) { $item['contenido2'] = $valores['contenido2_'.$extension]; } else { $item['contenido2'] = NULL; }
					if(isset($valores['contenido3_'.$extension])) { $item['contenido3'] = $valores['contenido3_'.$extension]; } else { $item['contenido3'] = NULL; }
					if(isset($valores['contenido4_'.$extension])) { $item['contenido4'] = $valores['contenido4_'.$extension]; } else { $item['contenido4'] = NULL; }
					if(isset($valores['contenido5_'.$extension])) { $item['contenido5'] = $valores['contenido5_'.$extension]; } else { $item['contenido5'] = NULL; }
					if(isset($valores['contenido6_'.$extension])) { $item['contenido6'] = $valores['contenido6_'.$extension]; } else { $item['contenido6'] = NULL; }
					if(isset($valores['contenido7_'.$extension])) { $item['contenido7'] = $valores['contenido7_'.$extension]; } else { $item['contenido7'] = NULL; }
		
					//CHEQUEO E INGRESO IDIOMA
					$sql = "SELECT id FROM con_contenido_items WHERE id_contenido = $id AND idioma = '$extension'";
					$query = $this->db->query($sql);
					$ingresado = $query->row_array();
	
					if (!isset($ingresado))
					{
						if( (isset($valores['template'])) && ($valores['template'] == 1) )
						{
							//MODIFICAR CONTENIDO TEMPLATE
							$data['id_contenido'] = $id;
							$data['idioma'] = $extension;
							$data['titulo'] = $valores['titulo_'.$extension];
							$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
							$data['user_alta'] = $this->usuario->id;
							$data['data'] = json_encode($item);
							$insert = $this->db->insert('con_contenido_items', $data);
						}
						else
						{
							//MODIFICAR CONTENIDO ANTERIOR
							$item['id_contenido'] = $id;
							$item['idioma'] = $extension;
							$item['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
							$item['user_alta'] = $this->usuario->id;
							$insert = $this->db->insert('con_contenido_items', $item);
						}
						$res['idioma_'.$extension] = $this->db->insert_id();
					}
					else
					{
						if( (isset($valores['template'])) && ($valores['template'] == 1) )
						{
							//MODIFICAR CONTENIDO TEMPLATE
							$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
							$data['user_modificacion'] = $this->usuario->id;
							$data['titulo'] = $valores['titulo_'.$extension];
							$data['data'] = json_encode($item);
							$where = "id = ".$ingresado['id'];
							$update = $this->db->update('con_contenido_items', $data, $where);
						}
						else
						{
							//MODIFICAR CONTENIDO ANTERIOR
							$item['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
							$item['user_modificacion'] = $this->usuario->id;
							$where = "id = ".$ingresado['id'];
							$update = $this->db->update('con_contenido_items', $item, $where);
						}
						$res['idioma_'.$extension] = $ingresado['id'];
					}
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	//ASOCIAR MEDIA
	public function asociarMedia($id, $proyecto, $tipo, $idioma)
	{
		$sql = "SELECT con_rel_contenidos_media.id";
		$sql .= " FROM con_rel_contenidos_media";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $proyecto";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";
		$sql .= " AND media_thumbs.id_tipo = $tipo";
		$query = $this->db->query($sql);
		$res = $query->row_array();	

		if (!isset($res['error']))
		{
			//BORRO LA RELACION ANTERIOR
			$this->db->where('id', $res['id']);
			$this->db->delete('con_rel_contenidos_media'); 
		}

		$data['id_media'] = $id;
		$data['id_contenido'] = $proyecto;
		$data['idioma'] = $idioma;
		$data['id_tipo'] = $tipo;
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('con_rel_contenidos_media', $data);
		}

		return (!empty($res)) ? $res : null;
	}

	public function modificarSeccion($id = null, $valores)
	{
		$this->load->helper('date');

		//MODIFICAR CONTENIDO GENERAL
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$wherecon = "id = $id";
		$updatecon = $this->db->update('con_contenidos', $data, $wherecon);	

		if (!isset($updatecon['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['seo_titulo_'.$extension])
				{
					$item['seo_titulo'] = $valores['seo_titulo_'.$extension];
					if(isset($valores['url_'.$extension])) { $item['url'] = $valores['url_'.$extension]; }
					if(isset($valores['seo_keywords_'.$extension])) { $item['seo_keywords'] = $valores['seo_keywords_'.$extension]; }
					$item['seo_descripcion'] = $valores['seo_descripcion_'.$extension];

					//CHEQUEO E INGRESO IDIOMA
					$sql = "SELECT id FROM con_contenido_items WHERE id_contenido = $id AND idioma = '$extension'";
					$query = $this->db->query($sql);
					$ingresado = $query->row_array();
	
					if (!isset($ingresado))
					{
						$item['id_contenido'] = $id;
						$item['idioma'] = $extension;
						$item['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
						$item['user_alta'] = $this->usuario->id;
						$insert = $this->db->insert('con_contenido_items', $item);
						$res['idioma_'.$extension] = $this->db->insert_id();
					}
					else
					{
						$res['idioma_'.$extension] = $ingresado['id'];
						$item['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
						$item['user_modificacion'] = $this->usuario->id;
						$where = "id = ".$ingresado['id'];
						$update = $this->db->update('con_contenido_items', $item, $where);
					}
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}
	
	//CAMBIAR PUBLICACION
	public function publicarSeccion()
	{
		$this->load->helper('date');

		//MODIFICAR CONTENIDO
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if ($this->input->post('estado') == 3)
		{
			$datos['estado'] = 1;
		}		
		else
		{
			$datos['estado'] = 3;
		}		

		if (!isset($res['error']))
		{
			//MODIFICO SECCIONES Y CONTENIDO
			$where= "id = ".$this->input->post('id');
			$update = $this->db->update('con_secciones', $datos, $where);
			$wherecon = "id_con_secciones = ".$this->input->post('id');
			$updatecon = $this->db->update('con_contenidos', $datos, $wherecon);

			//VERIFICO SI TIENE SUBSECCIONES
			$sql = "SELECT id, estado";
			$sql .= " FROM con_secciones";
			$sql .= " WHERE padre = ".$this->input->post('id');
			$query = $this->db->query($sql);
			$hijos = $query->result_array();

			//MODIFICO SECCIONES INTERNAS Y CONTENIDO
			foreach($hijos as $hijo)
			{
				$wherehijo= "id = ".$hijo['id'];
				$update = $this->db->update('con_secciones', $datos, $wherehijo);
				$where2 = "id_con_secciones = ".$hijo['id'];
				$updatehijo = $this->db->update('con_contenidos', $datos, $where2);
			}
		}
		return ($updatecon);
	}
	
	public function getContenidoAdicionalIdioma($parametros = null)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.id_tipo, con_contenido_items_adicionales.orden, con_contenido_items_adicionales.sin_texto, con_contenido_items_adicionales.id_con_contenido, con_contenido_items_adicionales.estado, con_contenido_items_adicionales.idioma, con_contenido_items_adicionales.titulo, con_contenido_items_adicionales.subtitulo, con_contenido_items_adicionales.orden, con_contenido_items_adicionales.texto_adicional, con_contenido_items_adicionales.contenido1, con_contenido_items_adicionales.contenido2, con_contenido_items_adicionales.contenido3, con_contenido_items_adicionales.contenido4, con_contenido_items_adicionales.contenido5, con_contenido_items_adicionales.contenido6, con_contenido_items_adicionales.contenido7, con_contenido_items_adicionales.imagen, con_contenido_items_adicionales.archivo, con_contenido_items_adicionales.id_proyecto, con_contenido_items_adicionales.seo_titulo, con_contenido_items_adicionales.seo_descripcion, con_contenido_items_adicionales.fecha_alta";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenido_items_adicionales.id_tipo";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_contenido_items_adicionales.id_con_contenido = ?";
		$placeholders[] = $parametros['id'];

		if (!empty($parametros['id_tipo']))
		{
			$sql .= " AND con_contenido_items_adicionales.id_tipo = ?";
			$placeholders[] = $parametros['id_tipo'];
		}
		$sql .= " AND con_contenido_items_adicionales.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_contenido_items_adicionales.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_contenido_items_adicionales.estado > 0";
		}
		
		$sql .= " ORDER BY con_contenido_items_adicionales.orden ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : $res;
	}

	//INGRESO CONTENIDO ADICIONAL
	public function ingresarContenidoAdicional($valores)
	{
		$this->load->helper('date');

		$datos['id_tipo'] = $valores['id_tipo'];
		$datos['id_con_contenido'] = $valores['id_contenido'];
		$datos['idioma'] = $valores['idioma'];
		$datos['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $datos['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; } else { $datos['texto_adicional'] = NULL; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; } else { $datos['contenido1'] = NULL; }
		if(!empty($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; } else { $datos['contenido2'] = NULL; }
		if(!empty($valores['contenido3'])) { $datos['contenido3'] = $valores['contenido3']; } else { $datos['contenido3'] = NULL; }
		if(!empty($valores['contenido4'])) { $datos['contenido4'] = $valores['contenido4']; } else { $datos['contenido4'] = NULL; }
		if(!empty($valores['contenido5'])) { $datos['contenido5'] = $valores['contenido5']; } else { $datos['contenido5'] = NULL; }
		if(!empty($valores['contenido6'])) { $datos['contenido6'] = $valores['contenido6']; } else { $datos['contenido6'] = NULL; }
		if(!empty($valores['contenido7'])) { $datos['contenido7'] = $valores['contenido7']; } else { $datos['contenido7'] = NULL; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
		if(isset($valores['media_proyecto'])) { $datos['id_proyecto'] = $valores['media_proyecto']; }
		if(!empty($valores['seo_titulo'])) { $datos['seo_titulo'] = $valores['seo_titulo']; } else { $datos['seo_titulo'] = NULL; }
		if(!empty($valores['seo_keywords'])) { $datos['seo_keywords'] = $valores['seo_keywords']; } else { $datos['seo_keywords'] = NULL; }
		if(!empty($valores['seo_descripcion'])) { $datos['seo_descripcion'] = $valores['seo_descripcion']; } else { $datos['seo_descripcion'] = NULL; }

		$datos['estado'] = $valores['estado'];
		$datos['orden'] = $valores['orden'];
		$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_contenido_items_adicionales', $datos);
		$res['id'] = $this->db->insert_id();

		return ($res);
	}

	//INGRESO CONTENIDO ADICIONAL
	public function modificarContenidoAdicional($id, $valores)
	{
		$this->load->helper('date');

		if(isset($valores['id_tipo'])) { $datos['id_tipo'] = $valores['id_tipo']; }
		$datos['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $datos['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; } else { $datos['contenido4'] = NULL; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(!empty($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; }
		if(!empty($valores['contenido3'])) { $datos['contenido3'] = $valores['contenido3']; }
		if(!empty($valores['contenido4'])) { $datos['contenido4'] = $valores['contenido4']; } else { $datos['contenido4'] = NULL; }
		if(!empty($valores['contenido5'])) { $datos['contenido5'] = $valores['contenido5']; } else { $datos['contenido5'] = NULL; }
		if(!empty($valores['contenido6'])) { $datos['contenido6'] = $valores['contenido6']; } else { $datos['contenido6'] = NULL; }
		if(!empty($valores['contenido7'])) { $datos['contenido7'] = $valores['contenido7']; } else { $datos['contenido7'] = NULL; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
		if(isset($valores['media_proyecto'])) { $datos['id_proyecto'] = $valores['media_proyecto']; }
		if(!empty($valores['seo_titulo'])) { $datos['seo_titulo'] = $valores['seo_titulo']; }
		if(!empty($valores['seo_keywords'])) { $datos['seo_keywords'] = $valores['seo_keywords']; }
		if(!empty($valores['seo_descripcion'])) { $datos['seo_descripcion'] = $valores['seo_descripcion']; }
		$datos['estado'] = $valores['estado'];
		$datos['orden'] = $valores['orden'];
		if(isset($valores['fecha_alta'])) { $datos['fecha_alta'] = $valores['fecha_alta']; }
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where= "id = $id";
		$update = $this->db->update('con_contenido_items_adicionales', $datos, $where);
		return ($update);
	}

	//INGRESAR IMAGEN EN CONTENIDOS ADICIONALES
	public function ingresarArchivo($id, $imagen, $medidas = null, $id_imagen_tipo = null)
	{
		if($id_imagen_tipo == 9)
		{
			$sql = "SELECT media.archivo";
			$sql .= " FROM media";
			$sql .= " WHERE media.id = $imagen";
			$sql .= " AND media.id_tipo = 9";
			$query = $this->db->query($sql);
			$res = $query->row_array();

			$data['archivo'] = $res['archivo'];
		}
		else
		{
			$medidas = explode('x', $medidas);
			$ancho = $medidas[0];
			$alto = $medidas[1];
	
			$sql = "SELECT media_thumbs.archivo";
			$sql .= " FROM media_thumbs";
			$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
			$sql .= " WHERE media_thumbs.referencia = $imagen";
			$sql .= " AND media_thumbs.ancho = $ancho";
			$sql .= " AND media_thumbs.alto = $alto";
			if($id_imagen_tipo) { $sql .= " AND media_thumbs.id_tipo = $id_imagen_tipo"; }
	
			$query = $this->db->query($sql);
			$res = $query->row_array();
			$data['imagen'] = $res['archivo'];
		}
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$where = "id = $id";
		$res = $this->db->update('con_contenido_items_adicionales', $data, $where);

		return (!empty($res)) ? $res : null;
	}
	//PROBADA	
	
	public function getPaginaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT con_contenidos.*, con_secciones.seccion, con_secciones.padre as padre_seccion
				FROM con_contenidos
			";
		}
		else
		{
			$sql = "SELECT con_contenidos.id, con_contenidos.titulo, con_contenidos.descripcion, con_contenidos.fecha_alta, con_secciones.seccion, con_secciones.padre as padre_seccion 
					FROM con_contenidos
				";
		}
		
		$sql .= " 
				LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones
				WHERE con_contenidos.grupo = ?
				AND con_contenidos.estado > 0
				AND con_contenidos.id = ?		
			";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	public function getPaginaDetalleRaw($id)
	{
		return $this->getPaginaDetalle($id, array('modo'=>'raw'));
	}

	public function getPaginaDetalleIdioma($id, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items.*";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//DATA CONTENIDO
	public function getPaginaDataIdioma($id, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items.data";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getPaginaIdiomaTemplate($id, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items.data, con_contenido_items.imagen, con_contenido_items.archivo";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function getPaginaDetalleUrlIdioma($url, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items.*, con_contenidos.id_con_secciones, con_secciones.padre";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items.url = '".$url."'";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getAdicionalDetalleIdioma($id, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items_adicionales.*, con_contenidos.grupo, con_contenidos.id_empresa";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items_adicionales.id_contenido = $id";
		$sql .= " AND con_contenido_items_adicionales.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenido_items_adicionales.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getContenidoAdicionalUrlIdioma($parametros = null)
	{
		$sql = "SELECT con_contenido_items_adicionales.*, con_contenidos.id as id_contenido, con_contenidos.grupo, con_contenidos.id_empresa";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_contenidos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_contenido_items_adicionales.subtitulo = ?";
		$placeholders[] = $parametros['url'];
		$sql .= " AND con_contenido_items_adicionales.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenido_items_adicionales.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getDetalleCategoria($id, $parametros = null)
	{
		$sql = "SELECT * FROM con_secciones";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id_con_secciones = con_secciones.id
		 WHERE con_secciones.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_secciones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND con_contenidos.id = $id";
		$sql .= " AND con_contenidos.estado > 0";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getCategoriasAdicionales($padre, $parametros = null)
	{
		$sql = "SELECT *";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE con_secciones.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_secciones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND padre = $padre";
		$sql .= " AND id_secciones_tipo = 8";
		$sql .= " AND estado > 0 ORDER BY con_secciones.orden ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getDetalleAdicionalIdioma($parametros)
/* 	public function getDetalleAdicionalIdioma($id, $idioma) */
	{
		$sql = "SELECT *";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " WHERE id = ?";
		$sql .= " AND idioma = ?";
		$sql .= " AND estado > 0";
		$placeholders[] = $parametros['id_contendio'];
		$placeholders[] = $parametros['idioma'];
		

		$query = $this->db->query($sql, $placeholders);
/* 		$query = $this->db->query($sql); */
		$res = $query->row_array();
		return ($res);
	}

	public function getDetalleContenidoAdicional($id)
	{
		$sql = "SELECT *";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " WHERE id = $id";
		$sql .= " AND estado > 0";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getContenidoAdicionalPadre($id, $padre, $idioma)
	{
		$sql = "SELECT con_contenido_items_adicionales.*, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 15 LIMIT 1) AS imagen";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenido_items_adicionales.id_tipo";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items_adicionales.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " WHERE con_contenido_items_adicionales.id_con_contenido = $id";
		$sql .= " AND con_secciones.padre = $padre";
		$sql .= " AND con_contenido_items_adicionales.idioma = '$idioma'";
		$sql .= " AND con_contenido_items_adicionales.estado > 0";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function getRelacionados($parametros)
	{
		$sql = "SELECT con_servicio_items.id, con_servicio_items.idioma, con_servicio_items.id_servicio, con_servicio_items.titulo, con_servicio_items.puntaje, con_servicio_items.precio, con_servicio_items.texto_adicional, con_servicio_items.contenido5, con_servicio_items.subtitulo, con_servicios_categorias.id as id_categoria, con_servicios_categorias.padre as categoria_padre, con_servicios_categorias.categoria, con_servicios_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 32 LIMIT 1) AS imagen";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_rel_contenido_servicios ON con_rel_contenido_servicios.id_servicio = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
		$sql .= " LEFT JOIN con_rel_servicios_media ON con_rel_servicios_media.id_contenido = con_servicios.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_servicios_media.id_media";
		$sql .= " WHERE con_servicios.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_servicios.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_servicios.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND con_rel_contenido_servicios.id_contenido_adicional = ?";
		$placeholders[] = $parametros['id'];
		$sql .= " AND con_servicio_items.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_rel_contenido_servicios.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_rel_servicios_media.idioma = ?";
			$placeholders[] = $parametros['idioma'];
			$sql .= " GROUP BY con_servicios.id";
		}
		
		else
		{
			$sql .= " ORDER BY con_servicio_items.orden ASC, con_servicio_items.id_servicio ASC";
			
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function relacionarContenidosAdicional($valores)
	{
		if (!empty($valores['id']))
		{
			$sql = "SELECT con_rel_contenidos.id";
			$sql .= " FROM con_rel_contenidos";
			$sql .= " WHERE con_rel_contenidos.grupo = ?";

			if ($this->usuario->perfil == 'reseller')
			{
				$placeholders[] = $this->usuario->grupo;
				if (isset($parametros['id_empresa']))
				{
					$sql .= " AND con_rel_contenidos.id_empresa = ?";
					$placeholders[] = $parametros['id_empresa'];
				}
			}
			elseif ($this->usuario->perfil == 'admin')
			{
				$placeholders[] = $this->usuario->grupo;
				$sql .= " AND con_rel_contenidos.id_empresa = ?"; 
				$placeholders[] = $this->usuario->id_empresa;
			}
			else
			{
				$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
			}
		
			$sql .= " AND con_rel_contenidos.id_contenido_adicional = ".$valores['id'];
			$sql .= " AND con_rel_contenidos.idioma = '".$valores['idioma']."'";
			$query = $this->db->query($sql,$placeholders);
			$res = $query->row_array();
			$id_eliminar = $res['id'];

			if (isset($id_eliminar))
			{
				$where = "id_contenido_adicional = ".$valores['id']." AND idioma = '".$valores['idioma']."'";
				$delete = $this->db->delete('con_rel_contenidos', $where);
			}

			if(!empty($valores['relaciones']))
			{
				foreach ($valores['relaciones'] as $relacionado)
				{
					$datos['grupo'] = $this->usuario->grupo;
					$datos['id_empresa'] = $this->usuario->id_empresa;
					$datos['id_contenido_adicional'] = $valores['id'];
					$datos['id_contenido_relacionado'] = $relacionado;
					$datos['idioma'] = $valores['idioma'];
					$insert = $this->db->insert('con_rel_contenidos', $datos);
				}
			}	
		}
		return ($valores['id']);
	}

	public function relacionarServicio($valores)
	{
		if (!empty($valores['id']))
		{
			$sql = "SELECT con_rel_contenido_servicios.id";
			$sql .= " FROM con_rel_contenido_servicios";
			$sql .= " WHERE con_rel_contenido_servicios.id_contenido_adicional = ".$valores['id'];
			$sql .= " AND con_rel_contenido_servicios.idioma = '".$valores['idioma']."'";
			$query = $this->db->query($sql);
			$res = $query->row_array();
			$id_eliminar = $res['id'];

			if (isset($id_eliminar))
			{
				$where = "id_contenido_adicional = ".$valores['id']." AND idioma = '".$valores['idioma']."'";
				$delete = $this->db->delete('con_rel_contenido_servicios', $where);
			}

			if(!empty($valores['relaciones']))
			{
				foreach ($valores['relaciones'] as $relacionado)
				{
					$datos['id_contenido_adicional'] = $valores['id'];
					$datos['id_servicio'] = $relacionado;
					$datos['idioma'] = $valores['idioma'];
					$insert = $this->db->insert('con_rel_contenido_servicios', $datos);
				}
			}	
		}
		return ($valores['id']);
	}

	public function getInformacionRelacionados($parametros)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.id_tipo, con_contenido_items_adicionales.idioma, con_contenido_items_adicionales.titulo,con_contenido_items_adicionales.texto_adicional, con_contenido_items_adicionales.subtitulo, con_contenido_items_adicionales.imagen";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " LEFT JOIN con_rel_contenidos ON con_rel_contenidos.id_contenido_relacionado = con_contenido_items_adicionales.id";
		$sql .= " WHERE con_contenidos.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND con_rel_contenidos.id_contenido_adicional = ?";
		$placeholders[] = $parametros['id'];
		$sql .= " AND con_rel_contenidos.idioma = ?";
		$placeholders[] = $parametros['idioma'];
		$sql .= " AND con_contenido_items_adicionales.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_contenido_items_adicionales.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		
		else
		{
			$sql .= " ORDER BY con_contenido_items_adicionales.orden ASC";
			
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function eliminarInformacion($id = null)
	{
		$this->load->helper('date');

		$datos['estado'] = '-'.$this->input->post('estado');
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$where = "id = ".$this->input->post('id');
			$res = $this->db->update('con_contenido_items_adicionales', $datos, $where);
		}		

		return ($res);
	}

	function comboPaises($estado = null)
	{
		$sql = "SELECT sys_paises.id, sys_paises.pais AS descripcion FROM sys_paises";
		if(isset($estado))
		{
			$sql .= " WHERE estado >= 1";
		}
		
		$sql .= " ORDER BY pais ASC";
		
		$query = $this->db->query($sql);
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		if (!empty($row))
		{
			if(!isset($estado))
			{ 
				$res[] = '--- Selecciona un país ---';
				foreach ($row as $obj => $value)
				{
					$res[$value['id']] = $value['descripcion'];
				}
			}
			else
			{
				$res = $row;
			}
		}
		return (!empty($res)) ? $res : null;
	}

	//ORDENAR GENERAL
	public function ordenarItems($items, $tabla)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$data['user_modificacion'] = $this->usuario->id;
		    
		    $this->db->update($tabla, $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
}