<?php defined('BASEPATH') or exit('No direct script access allowed');


class Multimedia_model extends CI_Model {

	public function getMedias($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS media.id, media.grupo, media.id_empresa, media.id_tipo, media_tipo.tipo, media.peso, media.nombre, media.archivo, media.peso, media.fecha_alta, media.username_alta, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.id AS id_contacto, empresas.empresa, media.estado AS id_estado, media.stream AS id_stream,
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 1 LIMIT 1) AS thumb,
				
					CASE
						WHEN media.stream = 1 THEN 'Storage'
						WHEN media.stream = 2 THEN 'On demand'
						WHEN media.stream = 3 THEN 'Adaptative'
					END AS stream,
				
					CASE
						WHEN media.estado = 1 THEN 'label-plain'
						WHEN media.estado = 2 THEN 'label-primary'
						WHEN media.estado = 3 THEN 'label-info'
					END AS estado_ui_class,
					
					CASE
						WHEN media.estado = 1 THEN 'Inactivo'
						WHEN media.estado = 2 THEN 'Activo'
						WHEN media.estado = 3 THEN 'PÃºblico'
					END AS estado
				
				FROM media
				LEFT JOIN empresas ON media.id_empresa = empresas.id
				LEFT JOIN contactos ON media.username_alta = contactos.id
				LEFT JOIN media_tipo ON media.id_tipo = media_tipo.id
				LEFT JOIN media_rel_proyectos ON media_rel_proyectos.id_media = media.id
				LEFT JOIN media_proyectos ON media_rel_proyectos.id_proyecto = media_proyectos.id
				LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_proyecto = media_rel_proyectos.id_proyecto
		
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
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND (media.estado > 1 OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id;
			$sql .= " AND (media_proyectos.estado > 1 OR media.estado = 3 OR media_proyectos.username_alta = ? OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.estado = 3 OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.estado > 1";
			$sql .= " AND (media_proyectos.estado > 1 OR media.estado = 3)";
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.estado = 3)"; $placeholders[] = $this->usuario->id;
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
			
			if (!empty($parametros['tipo']) && $parametros['tipo'] != 'todos')
			{
				$sql .= " AND media_tipo.tipo = ?";
				$placeholders[] = $parametros['tipo'];
			}
			
			if (!empty($parametros['padre']))
			{
				$sql .= " AND media_proyectos.padre = ?";
				$placeholders[] = $parametros['padre'];
			}
			
			if (!empty($parametros['proyecto']))
			{
				$sql .= " AND media_rel_proyectos.id_proyecto = ?";
				$placeholders[] = $parametros['proyecto'];
			}
			
			if (!empty($parametros['tag']))
			{
				$sql .= " AND media.id IN (SELECT id_referencia FROM sys_rel_tags WHERE id_tipo = 70 AND id_tag = ?)";
				$placeholders[] = $parametros['tag'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (media.nombre REGEXP '" . $value . "'";
				$sql .= " OR media.archivo REGEXP '" . $value . "'";
				$sql .= " OR contactos.username REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "'";
				$sql .= " OR media.id REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (media.nombre LIKE '%" . $value . "%'";
				$sql .= " OR media.archivo LIKE '%" . $value . "%'";
				$sql .= " OR contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%'";
				$sql .= " OR media.id LIKE '%" . $value . "%') ";
			}
			
			// group
			$sql .= " GROUP BY media.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " nombre";
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
		
		return (!empty($res)) ? $res : null;
	}


	public function getMediaDetalle($id, $parametros = null)
	{
		if (isset($parametros['modo']) && $parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT media.*, media_tipo.tipo, media_tipo.mime, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 3 LIMIT 1) AS thumb,
				
				(SELECT GROUP_CONCAT(tag SEPARATOR ', ')
					FROM sys_tags
					LEFT JOIN sys_rel_tags ON sys_rel_tags.id_tag = sys_tags.id AND sys_rel_tags.id_tipo = 70
				WHERE id_referencia = media.id) AS tags
				
				FROM media
				LEFT JOIN media_rel_proyectos ON media_rel_proyectos.id_media = media.id
				LEFT JOIN media_proyectos ON media_rel_proyectos.id_proyecto = media_proyectos.id
				LEFT JOIN media_tipo ON media.id_tipo = media_tipo.id
				LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_proyecto = media_rel_proyectos.id_proyecto
			";
		}
		else
		{
			$sql = "	
					SELECT media.id, MD5(CONCAT(media.id, media.username_alta)) AS uid, media.grupo, media.id_empresa, media.id_tipo, media_tipo.tipo, media_tipo.mime, media.nombre, media.descripcion, media.archivo, media.peso, media.fecha_alta, media.username_alta, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, media.stream, media.estado AS id_estado,
						(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 3 LIMIT 1) AS thumb,
						
						(SELECT GROUP_CONCAT(tag SEPARATOR ', ')
							FROM sys_tags
							LEFT JOIN sys_rel_tags ON sys_rel_tags.id_tag = sys_tags.id AND sys_rel_tags.id_tipo = 70
						WHERE id_referencia = media.id) AS tags,
						
						CASE
							WHEN media.estado = 1 THEN 'label-plain'
							WHEN media.estado = 2 THEN 'label-primary'
							WHEN media.estado = 3 THEN 'label-info'
						END AS estado_ui_class,
						
						CASE
							WHEN media.estado = 1 THEN 'Inactivo'
							WHEN media.estado = 2 THEN 'Activo'
							WHEN media.estado = 3 THEN 'PÃºblico'
						END AS estado
					
					FROM media
					LEFT JOIN empresas ON media.id_empresa = empresas.id
					LEFT JOIN contactos ON media.username_alta = contactos.id
					LEFT JOIN media_tipo ON media.id_tipo = media_tipo.id
					LEFT JOIN media_rel_proyectos ON media_rel_proyectos.id_media = media.id
					LEFT JOIN media_proyectos ON media_rel_proyectos.id_proyecto = media_proyectos.id
					LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_proyecto = media_rel_proyectos.id_proyecto
				";
		}
		
		$sql .= " 
				WHERE media.grupo = ?
				AND media.estado > 0
				AND media.id = ?		
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
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			$sql .= " AND (media.estado > 1 OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id;
			$sql .= " AND (media_proyectos.estado > 1 OR media.estado = 3 OR media_proyectos.username_alta = ? OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.username_alta = ? OR media.estado = 3)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			$sql .= " AND media.estado > 1";
			$sql .= " AND (media_proyectos.estado > 1 OR media.estado = 3)";
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.estado = 3)"; $placeholders[] = $this->usuario->id;
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
	
	
	public function getMediaDetalleRaw($id)
	{
		return $this->getMediaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getMediaTipoId($extension)
	{
		$sql = " 	
				SELECT id
				
				FROM media_tipo
				
				WHERE media_tipo.extension = ?
			";
		
		$placeholders[] = str_replace('.', '', $extension);
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaTipo($extension)
	{
		$sql = " 	
				SELECT tipo
				
				FROM media_tipo
				
				WHERE media_tipo.extension = ?
			";
		
		$placeholders[] = str_replace('.', '', $extension);
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['tipo'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaArchivosPermitidos()
	{
		$sql = " 	
				SELECT extension
				
				FROM media_tipo
				
				WHERE media_tipo.estado = 2
			";
		
		// consulta
		$query = $this->db->query($sql);
		
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
			
			foreach ($row as $obj => $value)
			{
				$res[] = $value['extension'];
			}
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaArchivos()
	{
		$sql = " 	
				SELECT COUNT(id) AS cantidad
				
				FROM media
				
				WHERE media.grupo = ?
				AND media.estado > 0
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
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			//$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			//$sql .= " AND media_proyectos_rel_contactos.id_contacto = ?"; $placeholders[] = $this->usuario->id;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaProyectos()
	{
		$sql = " 	
				SELECT COUNT(*) AS cantidad
				
				FROM media_proyectos
				
				WHERE media_proyectos.grupo = ?
				AND media_proyectos.estado > 0
			";
		
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
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos.estado > 1";
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos.estado > 1";
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function comboProyectos($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT media_proyectos.id, media_proyectos.proyecto AS descripcion
				
				FROM media_proyectos
				
				WHERE media_proyectos.grupo = ?
				AND media_proyectos.estado > 1
				";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " media_proyectos.proyecto";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			$res[] = '--- Selecciona una opciÃ³n ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaEspacio()
	{
		$sql = " 	
				SELECT SUM(peso) AS peso
				
				FROM media
				
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
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			//$sql .= " AND (media_proyectos_rel_contactos.id_contacto = ? OR media.username_alta = ?)"; $placeholders[] = $this->usuario->id; $placeholders[] = $this->usuario->id;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			//$sql .= " AND media_proyectos_rel_contactos.id_contacto = ?"; $placeholders[] = $this->usuario->id;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['peso'];
		}

		return (!empty($res)) ? $res : null;
	}

		
	public function ingresarMedia($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa; // VALIDAR QUE LA EMPRESA SEA DEL GRUPO
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['id_tipo'] = $valores['id_tipo'];
		$data['nombre'] = $valores['nombre'];
		$data['archivo'] = $valores['archivo'];
		$data['peso'] = (!empty($valores['peso'])) ? $valores['peso'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		$data['fecha_alta'] = (!empty($valores['fecha_alta'])) ? $valores['fecha_alta'] : now();
		$data['username_alta'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$insert = $this->db->insert('media', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getThumbDetalle($id_tipo, $referencia)
	{
		$sql = " 	
				SELECT *
				
				FROM media_thumbs
				
				WHERE media_thumbs.id_tipo = ?
				AND media_thumbs.referencia = ?
			";
		
		$placeholders[] = $id_tipo;
		$placeholders[] = $referencia;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarThumb($id_tipo, $id, $valores)
{
	if (empty($valores['archivo'])) {
		log_message('error', 'El valor de archivo no fue proporcionado a ingresarThumb');
		return null; // o lanzá un error controlado si lo preferís
	}

	$data['id_tipo'] = $id_tipo;
	$data['referencia'] = $id;
	$data['archivo'] = $valores['archivo'];
	$data['ancho'] = $valores['ancho'] ?? null;
	$data['alto'] = $valores['alto'] ?? null;
	if (isset($valores['peso'])) $data['peso'] = $valores['peso'];

	$thumb = $this->getThumbDetalle($id_tipo, $id);

	if ($this->db->delete('media_thumbs', array('id_tipo'=>$id_tipo, 'referencia'=>$id)))
	{
		$this->db->insert('media_thumbs', $data);
		$res['id'] = $this->db->insert_id();
	}
	else
	{
		$res['id'] = $thumb['id'];
	}

	return (!empty($res)) ? $res : null;
}

	
	
	function menuProyectos($padre = null, $seleccionada = null, $niveles = 10, $nivel = null, $parametros = null)
	{
		$sql = "
				SELECT media_proyectos.id, media_proyectos.proyecto, media_proyectos.estado, (SELECT COUNT(*) FROM media_rel_proyectos WHERE id_proyecto = media_proyectos.id) as cantidad, 
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 4 LIMIT 1) AS thumb,
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 5 LIMIT 1) AS image
				
				FROM media_proyectos
				LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_proyecto = media_proyectos.id
				
				WHERE media_proyectos.grupo = ?
				";
				
		$sql .= (isset($padre)) ? " AND media_proyectos.padre = $padre" : " AND media_proyectos.padre IS NULL";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos_rel_contactos.id_contacto = ?"; $placeholders[] = $this->usuario->id;
			$sql .= " AND media_proyectos.estado > 1";
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos.username_alta = ?"; $placeholders[] = $this->usuario->id;
			$sql .= " AND media_proyectos.estado > 1";
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
				$sql .= " AND media_proyectos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND media_proyectos.estado > 0";
			}
			
			// group
			$sql .= " GROUP BY media_proyectos.id";
			
			// orden
			$sql .= " ORDER BY media_proyectos.proyecto ASC"; 
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
				
			if ($query && $niveles >= ++$nivel)
			{	
				foreach($query->result_array() as $row)
				{
					$select = ($seleccionada == $row['id']) ? true : false;
					
					$res[] = array(
									'id'=>$row['id'],
									'proyecto'=>$row['proyecto'],
									'seleccionada'=>$select,
									'nivel'=>$nivel,
									'hijos'=>$this->menuProyectos($row['id'], $seleccionada, $niveles, $nivel, $parametros),
									'estado'=>$row['estado'],
									'cantidad'=>$row['cantidad'],
									'thumb'=>$row['thumb']
									);
				}
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function menuProyectosActivos()
	{
		return $this->menuProyectos(null, null, 10, null, array('estado'=>2));
	}
	

	function breadcrumbDetalle($id)
	{
		$sql = "
				SELECT media_proyectos.id, media_proyectos.proyecto, media_proyectos.padre
				
				FROM media_proyectos
				LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_proyecto = media_proyectos.id
				
				WHERE media_proyectos.id = ?
				AND media_proyectos.grupo = ?
				";
				
		$placeholders[] = $id;
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos_rel_contactos.id_contacto = ?"; $placeholders[] = $this->usuario->id;
		}
		elseif ($this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			$sql .= " AND media_proyectos.username_alta = ?"; $placeholders[] = $this->usuario->id;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
			
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function breadcrumbs_inverse($id, $padre = null)
	{
		$res[] = $data = $this->breadcrumbDetalle($id);
		
		while ($data['padre'])
		{
			$res[] = $data = $this->breadcrumbDetalle($data['padre']);
		}		
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function breadcrumbs($id)
	{
		$res = array_reverse($this->breadcrumbs_inverse($id));			
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getProyectos($parametros = null)
	{
		$sql = "	
				SELECT media_proyectos.id, media_proyectos.proyecto, media_proyectos.padre, media_proyectos.comentario, media_proyectos.estado as id_estado,
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 4 LIMIT 1) AS thumb,
					(SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 5 LIMIT 1) AS imagen,
				
					CASE
					   WHEN media_proyectos.estado = 1 THEN 'Inactivo'
					   WHEN media_proyectos.estado = 2 THEN 'Activo'
					END AS estado
				
				FROM media_proyectos
				LEFT JOIN empresas ON media_proyectos.id_empresa = empresas.id

				WHERE media_proyectos.grupo = ?
			";
		
		
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
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND media_proyectos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND media_proyectos.estado > 0";
			}
			
			if (!empty($parametros['tag']))
			{
				$sql .= " AND media_proyectos.id IN (SELECT id_referencia FROM sys_rel_tags WHERE id_tipo = 71 AND id_tag = ?)";
				$placeholders[] = $parametros['tag'];
			}
			
			if (!empty($parametros['padre']))
			{
				$sql .= " AND media_proyectos.padre = ?";
				$placeholders[] = $parametros['padre'];
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " media_proyectos.proyecto";
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
	
	
	public function getProyectoDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT media_proyectos.*, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 4 LIMIT 1) AS thumb,
					
						(SELECT GROUP_CONCAT(tag SEPARATOR ', ')
							FROM sys_tags
							LEFT JOIN sys_rel_tags ON sys_rel_tags.id_tag = sys_tags.id AND sys_rel_tags.id_tipo = 71
						WHERE id_referencia = media_proyectos.id) AS tags
					
					FROM media_proyectos
					LEFT JOIN empresas ON media_proyectos.id_empresa = empresas.id
				";
		}
		else
		{
			$sql = "	
					SELECT media_proyectos.id, media_proyectos.proyecto, media_proyectos.padre, media_proyectos.estado as id_estado, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media_proyectos.id AND media_thumbs.id_tipo = 4 LIMIT 1) AS thumb,
						
						(SELECT GROUP_CONCAT(tag SEPARATOR ', ')
							FROM sys_tags
							LEFT JOIN sys_rel_tags ON sys_rel_tags.id_tag = sys_tags.id AND sys_rel_tags.id_tipo = 71
						WHERE id_referencia = media_proyectos.id) AS tags,
					
						CASE
						   WHEN media_proyectos.estado = 1 THEN 'Inactivo'
						   WHEN media_proyectos.estado = 2 THEN 'Activo'
						END AS estado
					
					FROM media_proyectos
					LEFT JOIN empresas ON media_proyectos.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE media_proyectos.grupo = ?
				AND media_proyectos.estado > 0
				AND media_proyectos.id = ?		
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
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND media_proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getProyectoDetalleRaw($id)
	{
		return $this->getProyectoDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getMediaFromUid($id)
	{
		$sql = "
				SELECT media.grupo, media.id_empresa, media.nombre, media.archivo, media_tipo.mime, media_tipo.tipo, media.stream, media.estado, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 3 LIMIT 1) AS thumb
					
				FROM media AS media
				LEFT JOIN media_tipo ON media.id_tipo = media_tipo.id
			
				WHERE MD5(CONCAT(media.id, media.username_alta)) = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		if ($query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMediaIdFromArchivo($archivo, $buscar_eliminados = true)
	{
		$sql = "
				SELECT media.id
					
				FROM media
			
				WHERE archivo = ?
			";
		
		$placeholders[] = $archivo;
		if ($buscar_eliminados == false) $sql .= " AND media.estado > 0";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['id'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getPeso($id)
	{
		$sql = "
				SELECT media.peso
					
				FROM media
			
				WHERE id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		if ($query)
		{
			$res = $query->row_array()['peso'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarProyecto($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : null; // VALIDAR QUE LA EMPRESA SEA DEL GRUPO
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['proyecto'] = $valores['proyecto'];
		if (isset($valores['destacado'])) $data['destacado'] = (!empty($valores['destacado'])) ? $valores['destacado'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : NULL;
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('media_proyectos', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarProyecto($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : null; // VALIDAR QUE LA EMPRESA SEA DEL GRUPO
		}
		
		$data['proyecto'] = $valores['proyecto'];
		if (isset($valores['comentario'])) $data['comentario'] = (!empty($valores['comentario'])) ? $valores['comentario'] : NULL;
		if (isset($valores['destacado'])) $data['destacado'] = (!empty($valores['destacado'])) ? $valores['destacado'] : 0;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : NULL;
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			if ($this->usuario->perfil == 'reseller')
			{
				$res = $this->db->update('media_proyectos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
			}
			else
			{
				$res = $this->db->update('media_proyectos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getProyectosAsociados($id_media)
	{
		$sql = "
				SELECT media_rel_proyectos.id_proyecto, media_rel_proyectos.orden
				
					FROM media_rel_proyectos
				
					WHERE media_rel_proyectos.id_media = ?
			";
				
		// consulta
		$query = $this->db->query($sql, array($id_media));
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getUsuariosAsociadosAlProyecto($id_proyecto)
	{
		$sql = "
				SELECT contactos.id, contactos.avatar, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.username, contactos.email, UNIX_TIMESTAMP(CONVERT_TZ(contactos.ultima_visita, '+00:00', @@global.time_zone)) AS ultima_visita, empresas.empresa
				
					FROM contactos
					LEFT JOIN empresas ON empresas.id = contactos.id_empresa
					LEFT JOIN media_proyectos_rel_contactos ON media_proyectos_rel_contactos.id_contacto = contactos.id
					
					WHERE media_proyectos_rel_contactos.id_proyecto = ?
			";
				
		// consulta
		$query = $this->db->query($sql, array($id_proyecto));
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function eliminarUsuariosAsociadosAlProyecto($id)
	{
		$res = $this->db->delete('media_proyectos_rel_contactos', array('id_proyecto' => $id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function asociarUsuarioAlProyecto($id, $contacto)
	{
		$data['id_proyecto'] = $id;
		$data['id_contacto'] = $contacto;
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('media_proyectos_rel_contactos', $data);
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function asociarMedia($id, $proyecto)
	{
		$data['id_media'] = $id;
		$data['id_proyecto'] = $proyecto;
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('media_rel_proyectos', $data);
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function eliminarAsociacionDeMedia($id)
	{
		$res = $this->db->delete('media_rel_proyectos', array('id_media' => $id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarMedia($id, $valores)
	{
		if (isset($valores['nombre'])) $data['nombre'] = $valores['nombre'];
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? $valores['descripcion'] : NULL;
		if (isset($valores['peso'])) $data['peso'] = (!empty($valores['peso'])) ? $valores['peso'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		if (isset($valores['stream'])) $data['stream'] = (!empty($valores['stream'])) ? $valores['stream'] : null;
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			if ($this->usuario->perfil == 'reseller')
			{
				$res = $this->db->update('media', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
			}
			else
			{
				$res = $this->db->update('media', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}

}