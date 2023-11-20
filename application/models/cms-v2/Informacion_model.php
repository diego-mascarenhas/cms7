<?php defined('BASEPATH') or exit('No direct script access allowed');

class Informacion_model extends CI_Model {

	public function getContenidos($parametros = null)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.destacado, con_contenidos.estado as id_estado, con_contenido_items.titulo,  con_contenido_items.contenido1, con_contenido_items.url, con_contenidos.imagen, con_contenidos.orden, con_secciones.seccion, con_secciones_tipo.tipo,
					CASE
						WHEN con_contenidos.estado = 3 THEN 'label-primary'
						WHEN con_contenidos.estado = 1 THEN 'label-danger'
					END AS estado_ui_class,
					
					CASE
						WHEN con_contenidos.estado = 3 THEN 'Publicada'
						WHEN con_contenidos.estado = 1 THEN 'Borrador'
					END AS estado, con_contenidos.orden,media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 19 LIMIT 1) AS imagen";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_secciones_tipo ON con_secciones_tipo.id = con_secciones.id_secciones_tipo";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
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
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND con_contenidos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND con_contenidos.estado > 0";
			}
						
			if (!empty($parametros['tipo']))
			{
				$sql .= " AND con_contenidos.id_tipo = ?";
				$placeholders[] = $parametros['tipo'];
			}
			else
			{
				$sql .= " AND (con_contenidos.id_tipo = 9 || con_contenidos.id_tipo = 10)";
			}

			if (!empty($parametros['idioma']))
			{
				$sql .= " AND con_contenido_items.idioma = '".$parametros['idioma']."'";
			}
			else
			{
				$sql .= " AND con_contenido_items.idioma = 'es'";
			}

		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_rel_contenidos_media.idioma = 'es'";
		
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " con_contenidos.fecha_alta";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " DESC";
			$sql .= (!empty($parametros['limit'])) ? " LIMIT " . $parametros['limit'] : "";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getContenidosTotalesPublic($parametros = null)
	{
		$sql = "SELECT COUNT(con_contenido_items.id) as total";
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

		if (!empty($parametros['tipo']))
		{
			$sql .= " AND con_contenidos.id_tipo = ?";
			$placeholders[] = $parametros['tipo'];
		}
		$sql .= " AND con_contenidos.estado = 3";

		if (!empty($parametros['categoria']))
		{
			$sql .= " AND con_contenidos.id_con_secciones = ?";
			$placeholders[] = $parametros['categoria'];
		}

		$sql .= " AND con_contenido_items.idioma = '". $parametros['idioma']."'";

		if (!empty($parametros['filtro1']))
		{
			$sql .= " AND con_contenidos.id > ?";
			$placeholders[] = $parametros['filtro1'];
		}
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getContenidosPublic($parametros = null)
	{
		if($parametros['tipo'] == 9)
		{
			$sql = "SELECT con_contenido_items.id,con_contenidos.id as id_contenido, con_contenidos.id_tipo, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.estado, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.imagen as miembro, con_contenido_items.url, con_contenidos.orden,media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 19 LIMIT 1) AS imagen";
		}
		else 
		{
			$sql = "SELECT con_contenido_items.id,con_contenidos.id as id_contenido, con_contenidos.id_tipo, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.estado, con_contenido_items.titulo,  con_contenido_items.contenido1, media.id as id_media, (SELECT media.archivo FROM media WHERE media.id = id_media AND media.id_tipo = 9 LIMIT 1) AS archivo1";
		}

			$sql .= " FROM con_contenido_items";
			$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
			$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id";
			$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
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

		if (!empty($parametros['tipo']))
		{
			$sql .= " AND con_contenidos.id_tipo = ?";
			$placeholders[] = $parametros['tipo'];
		}

		$sql .= " AND con_contenidos.estado = 3";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_contenido_items.idioma = '". $parametros['idioma']."'";
		$sql .= " AND con_rel_contenidos_media.idioma = '". $parametros['idioma']."'";

		if (!empty($parametros['categoria']))
		{
			$sql .= " AND con_contenidos.id_con_secciones = ?";
			$placeholders[] = $parametros['categoria'];
		}

		if (!empty($parametros['destacado']))
		{
			$sql .= " AND con_contenidos.destacado = ?";
			$placeholders[] = $parametros['destacado'];
			$sql .= " ORDER BY con_contenidos.orden DESC, con_contenidos.fecha_alta DESC LIMIT 40";
		}
		else
		{
			$sql .= " ORDER BY con_contenidos.orden ASC, con_contenidos.fecha_alta ASC LIMIT 40";

		}
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getContenidosPublicDos($parametros = null)
	{
		if($parametros['tipo'] == 9)
		{
			$sql = "SELECT con_contenido_items.id,con_contenidos.id as id_contenido, con_contenidos.id_tipo, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.estado, con_contenido_items.titulo,  con_contenido_items.contenido1, con_contenido_items.url, con_contenidos.orden,media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 19 LIMIT 1) AS imagen";
		}
		else 
		{
			$sql = "SELECT con_contenido_items.id,con_contenidos.id as id_contenido, con_contenidos.id_tipo, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.estado, con_contenido_items.titulo,  con_contenido_items.contenido1, media.id as id_media, (SELECT media.archivo FROM media WHERE media.id = id_media AND media.id_tipo = 9 LIMIT 1) AS archivo1";
		}

			$sql .= " FROM con_contenido_items";
			$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
			$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items.id";
			$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
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

		if (!empty($parametros['tipo']))
		{
			$sql .= " AND con_contenidos.id_tipo = ?";
			$placeholders[] = $parametros['tipo'];
		}

		$sql .= " AND con_contenidos.estado = 3";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_contenido_items.idioma = '". $parametros['idioma']."'";
		

        if (!empty($parametros['offset']))
		{
			$sql .= " ORDER BY con_contenidos.id ASC LIMIT 10 OFFSET ".$parametros['offset'];
		}
		else
		{	
			$sql .= " ORDER BY con_contenidos.id D LIMIT 10";
		}
		

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getDetallePublic($idioma, $url)
	{
		$sql = "SELECT con_contenido_items.titulo, con_contenido_items.contenido1, seo_titulo, seo_keywords, seo_descripcion, archivo1, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 14 LIMIT 1) AS imagen";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
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

		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_contenido_items.url = '$url'";
		$sql .= " AND con_contenidos.estado = 3";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
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

	public function getInstituciones($tipo, $idioma)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.contenido1";
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
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_contenido_items_adicionales.id_con_contenido = $tipo";
		$sql .= " AND con_contenido_items_adicionales.idioma = '$idioma'";
		$sql .= " AND con_contenido_items_adicionales.estado > 0";
		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		$sql .= " ORDER BY con_contenido_items_adicionales.contenido1 ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['contenido1'];
		}
		return ($padre);

		return (!empty($res)) ? $res : null;
	}
	
	public function getCategorias($parametros = null)
	{
		$sql = "SELECT con_secciones.*, con_secciones_tipo.tipo as padre,
					CASE
						WHEN con_secciones.estado = 3 THEN 'label-primary'
						WHEN con_secciones.estado = 1 THEN 'label-danger'
					END AS estado_ui_class,
					CASE
						WHEN con_secciones.estado = 3 THEN 'Publicada'
						WHEN con_secciones.estado = 1 THEN 'Borrador'
					END AS estado_tipo";
		$sql .= " FROM con_secciones";
		$sql .= " LEFT JOIN con_secciones_tipo ON con_secciones_tipo.id = con_secciones.id_secciones_tipo";
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
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND con_secciones.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND con_secciones.estado > 0";
			}
						
			if (!empty($parametros['tipo']))
			{
				$sql .= " AND con_secciones.id_secciones_tipo = ?";
				$placeholders[] = $parametros['tipo'];
			}
			else
			{
				$sql .= " AND (con_secciones.id_secciones_tipo = 9 || con_secciones.id_secciones_tipo = 10)";
			}
			
			// orden
			$sql .= " ORDER BY con_secciones.id_secciones_tipo ASC, ";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " seccion";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
						
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		
		if(isset($parametros['combo']))
		{
			foreach ($res as $obj => $valor)
			{
				$padre[$valor['id']] = $valor['padre'].' - '.$valor['seccion'];
			}
			return ($padre);
		}
		else
		{
			return (!empty($res)) ? $res : null;
		}

	}
	
	public function getMedia($id, $idioma)
	{
		$sql = "SELECT media_thumbs.archivo FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND media_thumbs.id_tipo = 18";
		$sql .= " AND con_rel_contenidos_media.idioma = '".$idioma."'";
		$sql .= " ORDER BY con_rel_contenidos_media.id ASC";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function getArchivo($id, $idioma)
	{
		$sql = "SELECT media.archivo FROM media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND con_rel_contenidos_media.idioma = '".$idioma."'";
		$sql .= " AND media.id_tipo = 9";
		$sql .= " ORDER BY con_rel_contenidos_media.id ASC";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	//INGRESO INFORMACION
	public function ingresarInformacion($valores)
	{
		$this->load->helper('date');
		
		$sql = "SELECT con_secciones.id_secciones_tipo, con_secciones.seccion FROM con_secciones WHERE con_secciones.id = ".$valores['id_con_secciones'];
		$query = $this->db->query($sql);
		$id_tipo = $query->row_array();

		//INSERTO CONTENIDO GENERAL
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_tipo'] = $id_tipo['id_secciones_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		$data['filtro1'] = $valores['filtro1'];
		$data['orden'] = $valores['orden'];
		$data['destacado'] = $valores['destacado'];
		$data['destacado_slide'] = $valores['destacado_slide'];
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_contenidos', $data);
		$res['id'] = $this->db->insert_id();

		if (!isset($res['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$item['id_contenido'] = $res['id'];
					$item['idioma'] = $idioma['extension'];
					$item['titulo'] = $valores['titulo_'.$extension];
					
					//VERIFICO URL
					if($valores['url_'.$extension])
					{
						$url = $this->verificarUrl($valores['url_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $item['url'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))).'-copy'; } else { $item['url'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))); } 
					}
					else
					{
						$url = $this->verificarUrlTitulo($valores['titulo_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $item['url'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))).'-copy'; } else { $item['url'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))); } 
					}

					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; }
					
					if ($valores['filtro1'] != 323)
					{			
						$sqlthumb = "SELECT media_thumbs.archivo 
						FROM media_thumbs 
						LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media_thumbs.referencia 
						WHERE con_rel_contenidos_media.id_contenido = ".$valores['filtro1'];
						$querythumb = $this->db->query($sqlthumb);
						$thumb = $querythumb->row_array();
						$item['imagen'] = $thumb['archivo'];
					}

					$item['seo_titulo'] = $valores['seo_titulo_'.$extension];
					$item['seo_keywords'] = $valores['seo_keywords_'.$extension];
					$item['seo_descripcion'] = $valores['seo_descripcion_'.$extension];
					$item['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
					$item['user_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_contenido_items', $item);
					$res['idioma_'.$extension] = $this->db->insert_id();
					
					//INGRESO SLIDE
					if($valores['destacado_slide'] == 1)
					{
						$slide['id_con_contenido'] = $valores['slide_id_contenido'];
						$slide['id_tipo'] = $valores['slide_tipo'];
						$slide['idioma'] = $idioma['extension'];
						$slide['titulo'] = $valores['titulo_'.$extension];
						$slide['subtitulo'] = $valores['url_slide_'.$extension].'/'.$item['url'];
						if(isset($valores['contenido1_'.$extension])) { $slide['contenido1'] = $valores['contenido1_'.$extension]; }
						$slide['orden'] = $valores['orden'];
						$slide['estado'] = $valores['estado'];
						$slide['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
						$slide['user_alta'] = $this->usuario->id;
						$insert = $this->db->insert('con_contenido_items_adicionales', $slide);
						$res['slide'.$extension] = $this->db->insert_id();
						
						//INGRESO RELACION
						$relacion['grupo'] = $this->usuario->grupo;
						$relacion['id_empresa'] = $this->usuario->id_empresa;
						$relacion['id_contenido_principal']= $res['id'];
						$relacion['id_contenido_relacionado']= $res['slide'.$extension];
						$relacion['idioma']= $idioma['extension'];
						$insert = $this->db->insert('con_rel_contenidos', $relacion);
					}
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	public function modificarInformacion($id, $valores)
	{
		$this->load->helper('date');

		$sql = "SELECT con_secciones.id_secciones_tipo, con_secciones.seccion FROM con_secciones WHERE con_secciones.id = ".$valores['id_con_secciones'];
		$query = $this->db->query($sql);
		$id_tipo = $query->row_array();

		//MODIFICAR CONTENIDO GENERAL
		$data['id_tipo'] = $id_tipo['id_secciones_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		$data['filtro1'] = $valores['filtro1'];
		$data['orden'] = $valores['orden'];
		$data['destacado'] = $valores['destacado'];
		$data['destacado_slide'] = $valores['destacado_slide'];
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = $valores['fecha_alta'];
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$wherecon = "id = $id";
		$upadtecon = $this->db->update('con_contenidos', $data, $wherecon);

		if (!isset($res['error']))
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
					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; }
	
					//VERIFICO URL
					if($valores['url_'.$extension])
					{
						$url = $this->verificarUrl($valores['url_'.$extension], $idioma['extension']);
						if((!empty($url['url'])) && ($url['id_contenido'] != $id)) { $item['url'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))).'-copy'; } else { $item['url'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))); } 
					}
					else
					{
						$url = $this->verificarUrlTitulo($valores['titulo_'.$extension], $idioma['extension']);
						if((!empty($url['url'])) && ($url['id_contenido'] != $id)) { $item['url'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))).'-copy'; } else { $item['url'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))); } 
					}
	
					$item['seo_titulo'] = $valores['seo_titulo_'.$extension];
					$item['seo_keywords'] = $valores['seo_keywords_'.$extension];
					$item['seo_descripcion'] = $valores['seo_descripcion_'.$extension];
	
					if ($valores['filtro1'] != 323)
					{			
						$sqlthumb = "SELECT media_thumbs.archivo 
						FROM media_thumbs 
						LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media_thumbs.referencia 
						WHERE con_rel_contenidos_media.id_contenido = ".$valores['filtro1'];
						$querythumb = $this->db->query($sqlthumb);
						$thumb = $querythumb->row_array();
						$item['imagen'] = $thumb['archivo'];
					}

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

						//INGRESO SLIDE
						if($valores['destacado_slide'] == 1)
						{
							$slide['id_con_contenido'] = $valores['slide_id_contenido'];
							$slide['id_tipo'] = $valores['slide_tipo'];
							$slide['idioma'] = $idioma['extension'];
							$slide['titulo'] = $valores['titulo_'.$extension];
							$slide['subtitulo'] = $valores['url_slide_'.$extension].'/'.$item['url'];
							if(isset($valores['imagen_slide_'.$extension])) { $slide['imagen'] = $valores['imagen_slide_'.$extension]; }
							if(isset($valores['contenido1_'.$extension])) { $slide['contenido1'] = $valores['contenido1_'.$extension]; }
							$slide['orden'] = $valores['orden'];
							$slide['estado'] = $valores['estado'];
							$slide['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
							$slide['user_alta'] = $this->usuario->id;
							$insert = $this->db->insert('con_contenido_items_adicionales', $slide);
							$res['slide'.$extension] = $this->db->insert_id();
							
							//INGRESO RELACION
							$relacion['grupo'] = $this->usuario->grupo;
							$relacion['id_empresa'] = $this->usuario->id_empresa;
							$relacion['id_contenido_principal']= $id;
							$relacion['id_contenido_relacionado']= $res['slide'.$extension];
							$relacion['idioma']= $idioma['extension'];
							$insert = $this->db->insert('con_rel_contenidos', $relacion);
						}
					}
					else
					{
						$res['idioma_'.$extension] = $ingresado['id'];
						$item['fecha_alta'] = $valores['fecha_alta'];
						$item['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
						$item['user_modificacion'] = $this->usuario->id;
						$where = "id = ".$ingresado['id'];
						$update = $this->db->update('con_contenido_items', $item, $where);
	
						//INGRESO SLIDE
						if($valores['destacado_slide'] == 1)
						{
							$sql = "SELECT con_rel_contenidos.id_contenido_relacionado as id FROM con_rel_contenidos 
							LEFT JOIN  con_contenido_items_adicionales ON con_contenido_items_adicionales.id = con_rel_contenidos.id_contenido_relacionado
							 WHERE con_rel_contenidos.id_contenido_principal = $id 
							 AND con_contenido_items_adicionales.id_tipo = 8
							 AND con_rel_contenidos.idioma = '$extension'
							 AND con_contenido_items_adicionales.idioma = '$extension'";
							$query = $this->db->query($sql);
							$item_adicional = $query->row_array();	
					
							$slide['id_con_contenido'] = $valores['slide_id_contenido'];
							$slide['id_tipo'] = $valores['slide_tipo'];
							$slide['idioma'] = $idioma['extension'];
							$slide['titulo'] = $valores['titulo_'.$extension];
							$slide['subtitulo'] = $valores['url_slide_'.$extension].'/'.$item['url'];
							if(isset($valores['imagen_slide_'.$extension])) { $slide['imagen'] = $valores['imagen_slide_'.$extension]; }
							if(isset($valores['contenido1_'.$extension])) { $slide['contenido1'] = $valores['contenido1_'.$extension]; }
							$slide['orden'] = $valores['orden'];
							$slide['estado'] = $valores['estado'];

							if(!isset($item_adicional))
							{
								$slide['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
								$slide['user_alta'] = $this->usuario->id;
								$insert = $this->db->insert('con_contenido_items_adicionales', $slide);
								$res['slide'.$extension] = $this->db->insert_id();

								//INGRESO RELACION
								$relacion['grupo'] = $this->usuario->grupo;
								$relacion['id_empresa'] = $this->usuario->id_empresa;
								$relacion['id_contenido_principal']= $id;
								$relacion['id_contenido_relacionado']= $res['slide'.$extension];
								$relacion['idioma']= $idioma['extension'];
								$insert = $this->db->insert('con_rel_contenidos', $relacion);
							}
							else
							{
								$slide['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
								$slide['user_modificacion'] = $this->usuario->id;
								$whereslide = "id = ".$item_adicional['id'];
								$updateslide = $this->db->update('con_contenido_items_adicionales', $slide, $whereslide);
								$res['slide'.$extension] = $item_adicional['id'];
							}
						}

						//CAMBIO ESTADO DE SLIDE SI ESTABA ACTIVO Y SE DESACTIVO
						else
						{
							$sql = "SELECT con_rel_contenidos.id_contenido_relacionado as id FROM con_rel_contenidos 
							LEFT JOIN  con_contenido_items_adicionales ON con_contenido_items_adicionales.id = con_rel_contenidos.id_contenido_relacionado
							 WHERE con_rel_contenidos.id_contenido_principal = $id 
							 AND con_contenido_items_adicionales.id_tipo = 8
							 AND con_rel_contenidos.idioma = '$extension'
							 AND con_contenido_items_adicionales.idioma = '$extension'";
							$query = $this->db->query($sql);
							$item_estado = $query->row_array();	
							
							if(isset($item_estado))
							{
								$slide['estado'] = 1;
								$slide['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
								$slide['user_modificacion'] = $this->usuario->id;
								$whereslide = "id = ".$item_estado['id'];
								$updateslide = $this->db->update('con_contenido_items_adicionales', $slide, $whereslide);
							}
						}
					}
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}
	
	//ASOCIAR MEDIA
	public function asociarMedia($id, $proyecto, $idioma, $tipo)
	{
		//VERIFICO QUE NO HAYA OTRO MEDIA IGUAL RELACIONADO
		$sql = "SELECT con_rel_contenidos_media.id";
		$sql .= " FROM con_rel_contenidos_media";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media"; 
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id"; 
		$sql .= " WHERE media.grupo = ".$this->usuario->grupo;
		$sql .= " AND media.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND con_rel_contenidos_media.id_contenido = $proyecto"; 
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
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('con_rel_contenidos_media', $data);
		}

		return (!empty($res)) ? $res : null;
	}

	//INGRESAR ARCHIVO EN CONTENIDOS
	public function ingresarArchivo($id, $proyecto, $nombre)
	{
		if((substr($nombre, -4, 1)) == 1)
		{
			$data['archivo1'] = $proyecto;
		}
		else
		{
			$data['archivo2'] = $proyecto;
		}

		//CHEQUEO E INGRESO IDIOMAS
		$idioma = substr($nombre, -2);

		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$where = "id_contenido = $id AND idioma = '$idioma'";
		$res = $this->db->update('con_contenido_items', $data, $where);

		return (!empty($res)) ? $res : null;
	}

	//INGRESAR IMAGEN EN CONTENIDOS ADICIONALES
	public function ingresarMedia($id, $imagen, $extension, $tipo)
	{
		//SELECCIONO IMAGEN
		$sql = "SELECT media_thumbs.archivo";
		$sql .= " FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " WHERE media_thumbs.referencia = $imagen";
		$sql .= " AND media_thumbs.id_tipo = $tipo";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		//SELECCIONO CONTENIDO
		$sql1 = "SELECT con_rel_contenidos.id_contenido_relacionado as id";
		$sql1 .= " FROM con_rel_contenidos";
		$sql1 .= " WHERE con_rel_contenidos.id_contenido_principal = $id";
		$sql1 .= " AND con_rel_contenidos.idioma = '$extension'";

		$query = $this->db->query($sql1);
		$relacionado = $query->row_array();
		
		//INGRESO IMAGEN
		$data['imagen'] = $res['archivo'];
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;
		$where = "id = ".$relacionado['id'];
		$res = $this->db->update('con_contenido_items_adicionales', $data, $where);

		return (!empty($res)) ? $res : null;
	}

	//DUPLICO CONTENIDO
	public function duplicarInformacion($id)
	{
		$this->load->helper('date');
		$valores = $this->getContenidoDetalle($id, array('modo'=>'raw'));
		
		//INSERTO CONTENIDO GENERAL
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_tipo'] = $valores['id_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		$data['filtro1'] = $valores['filtro1'];
		$data['orden'] = $valores['orden'];
		$data['destacado'] = $valores['destacado'];
		$data['destacado_slide'] = $valores['destacado_slide'];
		$data['estado'] = 1;
		$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_alta'] = $this->usuario->id;
		$insert = $this->db->insert('con_contenidos', $data);
		$res['id'] = $this->db->insert_id();

		if (!isset($res['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				$valores1 = $this->getContenidoDetalleIdioma($id, $extension);

				if($valores1)
				{
					$idioma1['id_contenido'] = $res['id'];
					$idioma1['idioma'] = $valores1['idioma'];
					$idioma1['titulo'] = $valores1['titulo'].'-copy';
					if(isset($valores1['contenido1'])) { $idioma1['contenido1'] = $valores1['contenido1']; }
					$idioma1['imagen'] = $valores1['imagen'];
					$idioma1['seo_titulo'] = $valores1['seo_titulo'];
					$idioma1['seo_keywords'] = $valores1['seo_keywords'];
					$idioma1['seo_descripcion'] = $valores1['seo_descripcion'];
					$idioma1['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
					$idioma1['user_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_contenido_items', $idioma1);
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	public function getContenidoDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT con_contenidos.*, con_secciones.seccion
				FROM con_contenidos
			";
		}
		else
		{
			$sql = "SELECT con_contenidos.id, con_contenidos.titulo, con_contenidos.descripcion, con_contenidos.fecha_alta, con_secciones.seccion 
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
	
	public function getContenidoDetalleRaw($id)
	{
		return $this->getContenidoDetalle($id, array('modo'=>'raw'));
	}

	public function getContenidoDetalleIdioma($id, $idioma)
	{
		$sql = "SELECT con_contenido_items.*, con_contenido_items.id as id_item";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE con_contenidos.grupo = ?";
		$sql .= " AND id_contenido = $id";
		$sql .= " AND idioma = '$idioma'";

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

	public function getContenidoRelacionado($id, $idioma)
	{
		$sql = "SELECT id_contenido_relacionado FROM con_rel_contenidos WHERE id_contenido_principal = $id AND idioma = '$idioma'";
		$query = $this->db->query($sql);
		$res = $query->row_array();	

		return (!empty($res)) ? $res : null;
	}
	
	//INGRESO CATEGORIA
	public function ingresarCategoria($valores)
	{
		$this->load->helper('date');

		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_secciones_tipo'] = $valores['id_secciones_tipo'];
		$data['seccion'] = $valores['seccion'];
		$data['descripcion'] = $valores['descripcion'];
		$data['contenido1'] = $valores['contenido1'];
		$data['estado'] = $valores['estado'];
		$data['orden'] = $valores['orden'];
		$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_alta'] = $this->usuario->id;
		
		$insert = $this->db->insert('con_secciones', $data);
		$res['id'] = $this->db->insert_id();

		return ($res);
	}

	//MODIFICAR CATEGORIA
	public function modificarCategoria($id, $valores)
	{
		$this->load->helper('date');

		//MODIFICAR CONTENIDO GENERAL
		$data['id_secciones_tipo'] = $valores['id_secciones_tipo'];
		$data['seccion'] = $valores['seccion'];
		$data['descripcion'] = $valores['descripcion'];
		$data['contenido1'] = $valores['contenido1'];
		$data['estado'] = $valores['estado'];
		$data['orden'] = $valores['orden'];
		$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_modificacion'] = $this->usuario->id;

		$where = "id = $id";
		$res = $this->db->update('con_secciones', $data, $where);

		return ($res);
	}
	
	public function getCategoriaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT con_secciones.*, con_secciones_tipo.tipo as padre
				FROM con_secciones
			";
		}
		else
		{
			$sql = "SELECT con_secciones.id, con_secciones.seccion, con_secciones.descripcion, con_secciones.fecha_alta, con_secciones_tipo.tipo as padre 
					FROM con_secciones
				";
		}
		
		$sql .= " 
				LEFT JOIN con_secciones_tipo ON con_secciones_tipo.id = con_secciones.id_secciones_tipo
				WHERE con_secciones.grupo = ?
				AND con_secciones.estado > 0
				AND con_secciones.id = ?		
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
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND con_secciones.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	public function getCategoriaDetalleRaw($id)
	{
		return $this->getCategoriaDetalle($id, array('modo'=>'raw'));
	}

	//TRAIGO TIPO DE CATEGORIA
	public function getCategoriaTipoDetalle($id, $parametros = null)
	{
		$sql = "SELECT con_secciones_tipo.id, con_secciones_tipo.tipo 
				FROM con_secciones_tipo
				WHERE con_secciones_tipo.grupo = ?
				AND con_secciones_tipo.id = ?		
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
			$sql .= " AND con_secciones_tipo.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')	
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND con_secciones_tipo.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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

	//DUPLICO CATEGORIA
	public function duplicarCategoria($id)
	{
		$this->load->helper('date');

		$valores = $this->getCategoriaDetalle($id, array('modo'=>'raw'));

		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_secciones_tipo'] = $valores['id_secciones_tipo'];
		$data['seccion'] = $valores['seccion'].'-copy';
		$data['descripcion'] = $valores['descripcion'];
		$data['contenido1'] = $valores['contenido1'];
		$data['estado'] = 1;
		$data['orden'] = $valores['orden'];
		$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_alta'] = $this->usuario->id;
		
		$insert = $this->db->insert('con_secciones', $data);
		$res['id'] = $this->db->insert_id();

		return ($res);
	}

	//VERIFICAR URL
	public function verificarUrl($url, $idioma)
	{
		$sql = "SELECT con_contenido_items.url, con_contenido_items.id_contenido";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE con_contenido_items.url = '".trim($url)."'";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_contenidos.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_contenidos.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function verificarUrlTitulo($titulo, $idioma)
	{
		$sql = "SELECT con_contenido_items.url, con_contenido_items.id_contenido";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " WHERE (con_contenido_items.url = '".trim($titulo)."' || url = '".trim($titulo)."-copy')";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_contenidos.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_contenidos.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
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

	public function getItems($id)
	{
		$sql = "SELECT id, titulo";
		$sql .= " FROM con_contenido_items";
		$sql .= " WHERE id_contenido = $id";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}

	public function eliminarItem($id = null, $tabla)
	{
		$this->load->helper('date');

		$datos['estado'] = '-'.$this->input->post('estado');
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$where = "id = ".$this->input->post('id');
			$res = $this->db->update($tabla, $datos, $where);
		}		

		//ELIMINO RELACION DE SLIDE
		$idiomas = $this->getIdiomas();

		//CHEQUEO IDIOMAS
		foreach($idiomas as $idioma)
		{
			$extension = $idioma['extension'];

			$sql = "SELECT con_rel_contenidos.id_contenido_relacionado as id, con_contenido_items_adicionales.estado FROM con_rel_contenidos 
			LEFT JOIN  con_contenido_items_adicionales ON con_contenido_items_adicionales.id = con_rel_contenidos.id_contenido_relacionado
			 WHERE con_rel_contenidos.id_contenido_principal = ".$this->input->post('id')."
			 AND con_contenido_items_adicionales.id_tipo = 8
			 AND con_rel_contenidos.idioma = '$extension'
			 AND con_contenido_items_adicionales.idioma = '$extension'";
			$query = $this->db->query($sql);
			$item_adicional = $query->row_array();	
	
			if(isset($item_adicional))
			{
				$slide['estado'] = '-'.$item_adicional['estado'];
				$slide['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
				$slide['user_modificacion'] = $this->usuario->id;
				$whereslide = "id = ".$item_adicional['id'];
				$updateslide = $this->db->update('con_contenido_items_adicionales', $slide, $whereslide);
			}
		}
		return ($res);
	}	
}
	//PROBADA	