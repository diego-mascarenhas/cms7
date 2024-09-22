<?php defined('BASEPATH') or exit('No direct script access allowed');

class Servicios_model extends CI_Model {

	public function getServicios($parametros = null)
	{			
		$sql = "SELECT con_servicios.filtro1, con_servicio_items.id, con_servicio_items.uri, con_servicio_items.id_servicio, con_servicio_items.titulo, con_servicio_items.puntaje, con_servicio_items.precio, con_servicio_items.estado, con_servicio_items.texto_adicional, con_servicio_items.destacado, con_servicio_items.contenido1, con_servicio_items.contenido5, con_servicio_items.fecha_alta, con_servicio_items.subtitulo, con_servicio_items.id_proyecto, con_servicios_categorias.id as id_categoria, con_servicios_categorias.imagen as categoria_imagen, con_servicios_categorias.padre as categoria_padre, con_servicios_categorias.categoria, con_servicios_categorias.color, con_servicios_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 32 LIMIT 1) AS imagen";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
		$sql .= " LEFT JOIN con_rel_servicios_media ON con_rel_servicios_media.id_contenido = con_servicios.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_servicios_media.id_media";
		$sql .= " WHERE con_servicios.grupo = ?";

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
		
		if (isset($parametros['idioma']))
		{
			$sql .= " AND con_servicio_items.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_servicios_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['seccion']))
		{
			$sql .= " AND con_servicios_categorias.seccion = ?";
			$placeholders[] = $parametros['seccion'];
		}

		if (isset($parametros['padre']))
		{
			$sql .= " AND con_servicios_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		if ((isset($parametros['estado'])) && (isset($parametros['idioma'])))
		{
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_rel_servicios_media.idioma = ?";
			$placeholders[] = $parametros['idioma'];
			if (isset($parametros['id_tipo']))
			{
				$sql .= " AND con_rel_servicios_media.id_tipo = ?";
				$placeholders[] = $parametros['id_tipo'];
			}
			
			$sql .= " GROUP BY con_servicios.id";
		}
		elseif ((isset($parametros['estado'])) && (!isset($parametros['idioma'])))
		{
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " GROUP BY con_servicios.id";
		}
		else
		{
			$sql .= " AND con_servicio_items.estado >= 0";
			if (isset($parametros['id_tipo']))
			{
				$sql .= " AND con_rel_servicios_media.id_tipo = ?";
				$placeholders[] = $parametros['id_tipo'];
			}
			$sql .= " GROUP BY con_servicios.id";
		}
		
		if (isset($parametros['orden']))
		{
			switch($parametros['orden'])
			{
				case 0: $sql .= " ORDER BY con_servicio_items.orden ASC, con_servicios.id ASC"; break;
				case 1: $sql .= " ORDER BY con_servicio_items.precio ASC"; break;
				case 2: $sql .= " ORDER BY con_servicio_items.precio DESC"; break;
				case 3: $sql .= " ORDER BY con_servicio_items.titulo ASC"; break;
				case 4: $sql .= " ORDER BY con_servicio_items.titulo DESC"; break;
			}
		}
		else
		{
			$sql .= " ORDER BY con_servicios.orden ASC, con_servicios.id ASC";
			
		}
		
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

	//LISTADO DESTACADOS
	public function getServiciosDestacados($parametros)
	{
		$sql = "SELECT con_servicio_items.id_servicio, con_servicio_items.titulo, con_servicio_items.puntaje, con_servicio_items.precio, con_servicio_items.texto_adicional, con_servicio_items.contenido6, con_servicio_items.subtitulo, con_servicios_categorias.id as id_categoria, con_servicios_categorias.padre as categoria_padre, con_servicios_categorias.categoria, con_servicios_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 32 LIMIT 1) AS imagen";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
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
		
		if (isset($parametros['idioma']))
		{
			$sql .= " AND con_servicio_items.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_servicios_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['seccion']))
		{
			$sql .= " AND con_servicios_categorias.seccion = ?";
			$placeholders[] = $parametros['seccion'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_rel_servicios_media.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		else
		{
			$sql .= " AND con_servicio_items.estado >= 0";
		}
		$sql .= " AND con_servicio_items.destacado = 1";
		$sql .= " GROUP BY con_servicios.id";
		
		if (isset($parametros['orden']))
		{
			switch($parametros['orden'])
			{
				case 0: $sql .= " ORDER BY con_servicio_items.orden ASC, con_servicios.id ASC"; break;
				case 1: $sql .= " ORDER BY con_servicio_items.precio ASC"; break;
				case 2: $sql .= " ORDER BY con_servicio_items.precio DESC"; break;
				case 3: $sql .= " ORDER BY con_servicio_items.titulo ASC"; break;
				case 4: $sql .= " ORDER BY con_servicio_items.titulo DESC"; break;
			}
		}
		else
		{
			$sql .= " ORDER BY con_servicio_items.orden ASC, con_servicios.id ASC";
			
		}
		
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
	
	//LISTADO RELACION SERVICIOS-SERVICIOS 
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
		
		$sql .= " AND con_rel_contenido_servicios.id_contenido = ?";
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

	//LISTADO RELACION DESTINOS-SERVICIOS 
	public function listadoRelacionadosAdicionales($parametros)
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
	
	//DETALLE
	public function getDetalleServicio($id, $parametros=null)
	{
		$sql = "SELECT con_servicios.*, con_servicios_categorias.id as id_categoria, con_servicios_categorias.categoria, con_servicios_categorias.padre";
		$sql .= " FROM con_servicios";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
		$sql .= " WHERE con_servicios.grupo = ?";
		
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
		
		if (!isset($res['error']))
		{
			$sql .= " AND con_servicios.id = $id";
			if (isset($parametros['estado']))
			{
				$sql .= " AND con_servicios.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND con_servicios.estado >= 0";
			}
		}
		
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getServicioDetalleIdioma($parametros = null)
	{
		$sql = "SELECT con_servicio_items.*, con_servicio_items.id as id_item, con_servicios_categorias.id as id_categoria, con_servicios_categorias.categoria, con_servicios_categorias.seccion";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
		$sql .= " WHERE con_servicios.grupo = ?";

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
		$sql .= " AND con_servicio_items.id_servicio = ?";
		$placeholders[] = $parametros['id'];
		$sql .= " AND con_servicio_items.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		if (!empty($parametros['filtro1']))
		{
			$sql .= " AND con_servicios.filtro1 = ?";
			$placeholders[] = $parametros['filtro1'];
		}
		
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_servicios.estado = ?";
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_servicios.estado > 0";
			$sql .= " AND con_servicio_items.estado > 0";
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function getServicioDetalleFiltros($parametros = null)
	{
		$sql = "SELECT con_servicio_items.*, con_servicio_items.id as id_item, con_servicios_categorias.id as id_categoria, con_servicios_categorias.categoria, con_servicios_categorias.seccion";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
		$sql .= " WHERE con_servicios.grupo = ?";

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
		$sql .= " AND con_servicio_items.filtro1 = ?";
		$placeholders[] = $parametros['id'];
		$sql .= " AND con_servicio_items.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		if (!empty($parametros['filtro2']))
		{
			$sql .= " AND con_servicios.filtro2 = ?";
			$placeholders[] = $parametros['filtro2'];
		}
		
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_servicios.estado = ?";
			$sql .= " AND con_servicio_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_servicios.estado > 0";
			$sql .= " AND con_servicio_items.estado > 0";
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	//INGRESAR 
	public function ingresarServicio($valores)
	{
		$this->load->helper('text');
		//INGRESO SERVICIO GENERAL
		$data1['grupo'] = $this->usuario->grupo;
		$data1['id_empresa'] = $this->usuario->id_empresa;
		$data1['id_tipo'] = $valores['id_tipo'];
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['titulo'] = trim($valores['titulo']);
		if(isset($valores['filtro1'])) { $data1['filtro1'] = $valores['filtro1']; }
		if(isset($valores['filtro2'])) { $data1['filtro2'] = $valores['filtro2']; }
		if(isset($valores['color'])) { $data1['color'] = $valores['color']; }
		if(isset($valores['template'])) { $data1['template'] = $valores['template']; }

		if(empty($valores['orden']))
		{
			$sql = "SELECT id, orden";
			$sql .= " FROM con_servicios";
			$sql .= " WHERE grupo = ? AND id_empresa = ? AND id_categoria = ?";
			$sql .= " AND estado > 0";
			$sql .= " ORDER BY orden DESC LIMIT 1";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $valores['id_categoria'];
			$query = $this->db->query($sql, $placeholders);
			$orden = $query->row_array();
	
			if($orden) { $data1['orden'] = $orden['orden']+1; } else { $data1['orden'] = 0; }
		}
		else
		{
			$data1['orden'] = $valores['orden'];
		}	

		$data1['estado'] = $valores['estado'];
		$data1['fecha_alta'] = now();
		$data1['username_alta'] = $this->usuario->id;
		
		$insert = $this->db->insert('con_servicios', $data1);
		$res['id'] = $this->db->insert_id();
		
		if($res['id'])
		{
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$data['id_servicio'] = $res['id'];
					$data['idioma'] = $idioma['extension'];
					$data['titulo'] = trim($valores['titulo_'.$extension]);
					//VERIFICO URL
					if(isset($valores['url_'.$extension]))
					{
						$url = $this->verificarUrl($valores['url_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))).'-copy'; } else { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))); } 
					}
					else
					{
						$url = $this->verificarUrlTitulo($valores['titulo_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))).'-copy'; } else { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))); } 
					}
					if(isset($valores['subtitulo_'.$extension])) { $data['subtitulo'] = $valores['subtitulo_'.$extension]; } else { $data['subtitulo'] = NULL; }
					if(isset($valores['texto_adicional_'.$extension])) { $data['texto_adicional'] = $valores['texto_adicional_'.$extension]; } else { $data['texto_adicional'] = NULL; }
					if(isset($valores['contenido1_'.$extension])) { $data['contenido1'] = $valores['contenido1_'.$extension]; } else { $data['contenido1'] = NULL; }
					if(isset($valores['contenido2_'.$extension])) { $data['contenido2'] = $valores['contenido2_'.$extension]; } else { $data['contenido2'] = NULL; }
					if(isset($valores['contenido3_'.$extension])) { $data['contenido3'] = $valores['contenido3_'.$extension]; } else { $data['contenido3'] = NULL; }
					if(isset($valores['contenido4_'.$extension])) { $data['contenido4'] = $valores['contenido4_'.$extension]; } else { $data['contenido4'] = NULL; }
					if(isset($valores['contenido5_'.$extension])) { $data['contenido5'] = $valores['contenido5_'.$extension]; } else { $data['contenido5'] = NULL; }
					if(isset($valores['contenido6_'.$extension])) { $data['contenido6'] = $valores['contenido6_'.$extension]; } else { $data['contenido6'] = NULL; }
					if(isset($valores['contenido7_'.$extension])) { $data['contenido7'] = $valores['contenido7_'.$extension]; } else { $data['contenido7'] = NULL; }
					if(isset($valores['contenido8_'.$extension])) { $data['contenido8'] = $valores['contenido8_'.$extension]; } else { $data['contenido8'] = NULL; }
					if(isset($valores['contenido9_'.$extension])) { $data['contenido9'] = $valores['contenido9_'.$extension]; }
					if(isset($valores['contenido10_'.$extension])) { $data['contenido10'] = $valores['contenido10_'.$extension]; }
					if(isset($valores['orden_'.$extension])) { $data['orden'] = $valores['orden_'.$extension]; } else { $data['orden'] = NULL; }
					if(isset($valores['id_proyecto_'.$extension])) { $data['id_proyecto'] = $valores['id_proyecto_'.$extension]; }
					if(isset($valores['video_'.$extension])) { $data['video'] = $valores['video_'.$extension]; }
					if(isset($valores['id_moneda_'.$extension])) { $data['id_moneda'] = $valores['id_moneda_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $data['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['descuento_'.$extension])) { $data['descuento'] = $valores['descuento_'.$extension]; }
					if(isset($valores['puntaje_'.$extension])) { $data['puntaje'] = $valores['puntaje_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $data['seo_titulo'] = $valores['seo_titulo_'.$extension]; } else { $data['seo_titulo'] = NULL; }
					if(isset($valores['seo_descripcion_'.$extension])) { $data['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; } else { $data['seo_descripcion'] = NULL; }
					if(isset($valores['destacado_slide_'.$extension])) { $data['destacado_slide'] = $valores['destacado_slide_'.$extension]; }
					$data['destacado'] = $valores['destacado_'.$extension];
					$data['estado'] = $valores['estado'];
					$data['fecha_alta'] = now();
					$data['username_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_servicio_items', $data);
					$res['idioma_'.$extension] = $this->db->insert_id();
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR 
	public function modificarServicio($id, $valores)
	{
		$this->load->helper('text');
		
		//INGRESO PRODUCTO GENERAL
		$data1['id_tipo'] = $valores['id_tipo'];
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['titulo'] = trim($valores['titulo']);
		if (!empty($valores['filtro1'])) $data1['filtro1'] = $valores['filtro1'];
		if (!empty($valores['filtro2'])) $data1['filtro2'] = $valores['filtro2'];
		if (!empty($valores['color'])) $data1['color'] = $valores['color'];
		if (!empty($valores['template'])) $data1['template'] = $valores['template'];
		if (!empty($valores['estado'])) $data1['estado'] = $valores['estado'];
		if (!empty($valores['orden'])) $data1['orden'] = $valores['orden'];

		$data1['estado'] = $valores['estado'];
		$data1['fecha_modificacion'] = now();
		$data1['username_modificacion'] = $this->usuario->id;
		$where1 = "id = ".$id;
		$res1 = $this->db->update('con_servicios', $data1, $where1);		

		if($res1)
		{
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$data['idioma'] = $idioma['extension'];
					$data['titulo'] = trim($valores['titulo_'.$extension]);
					//VERIFICO URL
					if(isset($valores['url_'.$extension]))
					{
						$url = $this->verificarUrl($valores['url_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))).'-copy'; } else { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['url_'.$extension]))); } 
					}
					else
					{
						$url = $this->verificarUrlTitulo($valores['titulo_'.$extension], $idioma['extension']);
						if(!empty($url['url'])) { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))).'-copy'; } else { $data['uri'] = strtolower(convert_accented_characters(url_title($valores['titulo_'.$extension]))); } 
					}

					if(isset($valores['subtitulo_'.$extension])) { $data['subtitulo'] = $valores['subtitulo_'.$extension]; }
					if(isset($valores['texto_adicional_'.$extension])) { $data['texto_adicional'] = $valores['texto_adicional_'.$extension]; }
					if(isset($valores['contenido1_'.$extension])) { $data['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $data['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $data['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $data['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['contenido5_'.$extension])) { $data['contenido5'] = $valores['contenido5_'.$extension]; }
					if(isset($valores['contenido6_'.$extension])) { $data['contenido6'] = $valores['contenido6_'.$extension]; }
					if(isset($valores['contenido7_'.$extension])) { $data['contenido7'] = $valores['contenido7_'.$extension]; }
					if(isset($valores['contenido8_'.$extension])) { $data['contenido8'] = $valores['contenido8_'.$extension]; }
					if(isset($valores['contenido9_'.$extension])) { $data['contenido9'] = $valores['contenido9_'.$extension]; }
					if(isset($valores['contenido10_'.$extension])) { $data['contenido10'] = $valores['contenido10_'.$extension]; }
					if(isset($valores['orden_'.$extension])) { $data['orden'] = $valores['orden_'.$extension]; } else { $data['orden'] = NULL; }
					if(isset($valores['id_proyecto_'.$extension])) { $data['id_proyecto'] = $valores['id_proyecto_'.$extension]; }
					if(isset($valores['video_'.$extension])) { $data['video'] = $valores['video_'.$extension]; }
					if(isset($valores['id_moneda_'.$extension])) { $data['id_moneda'] = $valores['id_moneda_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $data['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['descuento_'.$extension])) { $data['descuento'] = $valores['descuento_'.$extension]; }
					if(isset($valores['puntaje_'.$extension])) { $data['puntaje'] = $valores['puntaje_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $data['seo_titulo'] = $valores['seo_titulo_'.$extension]; }
					if(isset($valores['seo_descripcion_'.$extension])) { $data['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; }
					if(isset($valores['destacado_slide_'.$extension])) { $data['destacado_slide'] = $valores['destacado_slide_'.$extension]; }
					$data['destacado'] = $valores['destacado_'.$extension];
					$data['estado'] = $valores['estado'];

					//CHEQUEO E INGRESO IDIOMA
					$sql = "SELECT id FROM con_servicio_items WHERE id_servicio = $id AND idioma = '$extension'";
					$query = $this->db->query($sql);
					$ingresado = $query->row_array();
	
					if (!isset($ingresado))
					{
						$data['id_servicio'] = $id;
						$data['fecha_alta'] = now();
						$data['username_alta'] = $this->usuario->id;
						$insert = $this->db->insert('con_servicio_items', $data);
						$res['idioma_'.$extension] = $this->db->insert_id();
					}
					else
					{
						$res['idioma_'.$extension] = $ingresado['id'];
						$data['fecha_modificacion'] = now();
						$data['username_modificacion'] = $this->usuario->id;
						$where = "id = ".$ingresado['id'];
						$update = $this->db->update('con_servicio_items', $data, $where);
					}
				}	
			}			
		}
		return (!empty($res)) ? $res : null;
	}

	//ORDENAR
	public function ordenarServicios($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_servicio_items', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		return (!empty($res)) ? $res : null;
	}

	public function relacionarServicio($valores)
	{
		if (!empty($valores['id']))
		{
			$idiomas = $this->getIdiomas();
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
				$sql = "SELECT con_rel_contenido_servicios.id";
				$sql .= " FROM con_rel_contenido_servicios";
				$sql .= " WHERE con_rel_contenido_servicios.id_contenido = ".$valores['id'];
				$sql .= " AND con_rel_contenido_servicios.idioma = '$extension'";
				$query = $this->db->query($sql);
				$res = $query->row_array();
				$id_eliminar = $res['id'];
	
				if (isset($id_eliminar))
				{
					$where = "id_contenido = ".$valores['id']." AND idioma = '$extension'";
					$delete = $this->db->delete('con_rel_contenido_servicios', $where);
				}

				if(!empty($valores['relaciones_'.$extension]))
				{
					foreach ($valores['relaciones_'.$extension] as $relacionado)
					{
						$datos['id_contenido'] = $valores['id'];
						$datos['id_servicio'] = $relacionado;
						$datos['idioma'] = $extension;
						$insert = $this->db->insert('con_rel_contenido_servicios', $datos);
					}
				}	
			}
		}
		return ($valores['id']);
	}

	//VERIFICAR URL
	public function verificarUrl($url, $idioma)
	{
		$sql = "SELECT con_servicio_items.uri, con_servicio_items.id_servicio";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " WHERE con_servicio_items.uri = '".trim($url)."'";
		$sql .= " AND con_servicio_items.idioma = '$idioma'";
		$sql .= " AND con_servicios.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_servicios.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function verificarUrlTitulo($titulo, $idioma)
	{
		$sql = "SELECT con_servicio_items.uri, con_servicio_items.id_servicio";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " WHERE (con_servicio_items.uri = '".trim($titulo)."' || uri = '".trim($titulo)."-copy')";
		$sql .= " AND con_servicio_items.idioma = '$idioma'";
		$sql .= " AND con_servicios.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_servicios.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function getDetalleServicioAdicional($id)
	{
		$sql = "SELECT con_servicio_items_adicionales.*";
		$sql .= " FROM con_servicio_items_adicionales";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items_adicionales.id_con_servicio";
		$sql .= " WHERE con_servicio_items_adicionales.id = $id";
		$sql .= " AND con_servicios.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_servicios.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND con_servicio_items_adicionales.estado > 0";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function getServicioAdicionalIdioma($parametros = null)
	{
		$sql = "SELECT con_servicio_items_adicionales.id, con_servicio_items_adicionales.id_tipo, con_servicio_items_adicionales.sin_texto, con_servicio_items_adicionales.id_con_servicio, con_servicio_items_adicionales.estado, con_servicio_items_adicionales.idioma, con_servicio_items_adicionales.titulo, con_servicio_items_adicionales.subtitulo, con_servicio_items_adicionales.orden, con_servicio_items_adicionales.texto_adicional, con_servicio_items_adicionales.contenido1, con_servicio_items_adicionales.contenido2, con_servicio_items_adicionales.contenido3, con_servicio_items_adicionales.contenido4, con_servicio_items_adicionales.contenido5, con_servicio_items_adicionales.contenido6, con_servicio_items_adicionales.contenido7, con_servicio_items_adicionales.imagen, con_servicio_items_adicionales.archivo, con_servicio_items_adicionales.id_proyecto, con_servicio_items_adicionales.seo_titulo, con_servicio_items_adicionales.seo_descripcion, con_servicio_items_adicionales.fecha_alta";
		$sql .= " FROM con_servicio_items_adicionales";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_servicio_items_adicionales.id_tipo";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items_adicionales.id_con_servicio";
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

		$sql .= " AND con_servicio_items_adicionales.id_con_servicio = ?";
		$placeholders[] = $parametros['id'];

		if (!empty($parametros['id_tipo']))
		{
			$sql .= " AND con_servicio_items_adicionales.id_tipo = ?";
			$placeholders[] = $parametros['id_tipo'];
		}
		$sql .= " AND con_servicio_items_adicionales.idioma = ?";
		$placeholders[] = $parametros['idioma'];

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_servicios.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_servicio_items_adicionales.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_servicio_items_adicionales.estado > 0";
		}
		
		$sql .= " ORDER BY con_servicio_items_adicionales.orden ASC";
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : $res;
	}

	function comboProyectos($padre = null)
	{
		$sql = "SELECT media_proyectos.id, media_proyectos.padre, media_proyectos.proyecto AS descripcion
				FROM media_proyectos
				WHERE media_proyectos.grupo = ?
				AND media_proyectos.estado > 1
				";
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
		if (isset($padre))
		{
			$sql .= " AND media_proyectos.padre = ?"; $placeholders[] = $padre;
		}
		else
		{
			$sql .= " AND media_proyectos.padre IS NULL";
		}
		if (!isset($res['error']))
		{
			$sql .= " ORDER BY media_proyectos.orden ASC";
			$query = $this->db->query($sql, $placeholders);
		}
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}

	//INGRESO CONTENIDO ADICIONAL
	public function ingresarContenidoAdicional($valores)
	{
		$this->load->helper('date');
		$datos['id_tipo'] = $valores['id_tipo'];
		$datos['id_con_servicio'] = $valores['id_contenido'];
		$datos['idioma'] = $valores['idioma'];
		$datos['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $datos['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(!empty($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; }
		if(!empty($valores['contenido3'])) { $datos['contenido3'] = $valores['contenido3']; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
		if(isset($valores['id_proyecto'])) { $datos['id_proyecto'] = $valores['id_proyecto']; }
		if(!empty($valores['seo_titulo'])) { $datos['seo_titulo'] = $valores['seo_titulo']; }
		if(!empty($valores['seo_keywords'])) { $datos['seo_keywords'] = $valores['seo_keywords']; }
		if(!empty($valores['seo_descripcion'])) { $datos['seo_descripcion'] = $valores['seo_descripcion']; }
		$datos['estado'] = $valores['estado'];
		$datos['orden'] = $valores['orden'];
		$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_alta'] = $this->usuario->id;
		$insert = $this->db->insert('con_servicio_items_adicionales', $datos);
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
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(!empty($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; }
		if(!empty($valores['contenido3'])) { $datos['contenido3'] = $valores['contenido3']; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
		if(isset($valores['id_proyecto'])) { $datos['id_proyecto'] = $valores['id_proyecto']; }
		if(!empty($valores['seo_titulo'])) { $datos['seo_titulo'] = $valores['seo_titulo']; }
		if(!empty($valores['seo_keywords'])) { $datos['seo_keywords'] = $valores['seo_keywords']; }
		if(!empty($valores['seo_descripcion'])) { $datos['seo_descripcion'] = $valores['seo_descripcion']; }
		$datos['estado'] = $valores['estado'];
		$datos['orden'] = $valores['orden'];
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where= "id = $id";
		$update = $this->db->update('con_servicio_items_adicionales', $datos, $where);
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
		$res = $this->db->update('con_servicio_items_adicionales', $data, $where);

		return (!empty($res)) ? $res : null;
	}

	//INGRESO CONTENIDO ADICIONAL
/*
	public function modificarContenidoAdicional($id, $valores)
	{
		$this->load->helper('date');
		if(isset($valores['id_tipo'])) { $datos['id_tipo'] = $valores['id_tipo']; }
		$datos['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $datos['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(!empty($valores['contenido2'])) { $datos['contenido2'] = $valores['contenido2']; }
		if(!empty($valores['contenido3'])) { $datos['contenido3'] = $valores['contenido3']; }
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
*/


//ver sin van
	//ASOCIAR MEDIA
	public function asociarMedia($id, $proyecto, $idioma, $tipo)
	{
		//VERIFICO QUE NO HAYA OTRO MEDIA IGUAL RELACIONADO
		$sql = "SELECT con_rel_servicios_media.id";
		$sql .= " FROM con_rel_servicios_media";
		$sql .= " LEFT JOIN media ON media.id = con_rel_servicios_media.id_media"; 
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id"; 
		$sql .= " WHERE media.grupo = ".$this->usuario->grupo;
		$sql .= " AND media.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND con_rel_servicios_media.id_contenido = $proyecto"; 
		$sql .= " AND con_rel_servicios_media.idioma = '$idioma'";
		$sql .= " AND media_thumbs.id_tipo = $tipo";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();	

		if (!isset($res['error']))
		{
			//BORRO LA RELACION ANTERIOR
			$this->db->where('id', $res['id']);
			$this->db->delete('con_rel_servicios_media'); 
		}

		$data['id_media'] = $id;
		$data['id_contenido'] = $proyecto;
		$data['idioma'] = $idioma;
		$data['id_tipo'] = $tipo;
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('con_rel_servicios_media', $data);
		}

		return (!empty($res)) ? $res : null;
	}

	public function getMedia($parametros = null)
	{
		$sql = "SELECT media.id, media_thumbs.archivo FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " LEFT JOIN con_rel_servicios_media ON con_rel_servicios_media.id_media = media.id";
		$sql .= " WHERE con_rel_servicios_media.id_contenido = ?";
		$sql .= " AND media_thumbs.id_tipo = ?";
		$sql .= " AND con_rel_servicios_media.idioma = ?";
		$sql .= " ORDER BY con_rel_servicios_media.id ASC";
		$placeholders[] = $parametros['id'];
		$placeholders[] = $parametros['id_tipo'];
		$placeholders[] = $parametros['idioma'];

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function getArchivo($parametros = null)
	{
		$sql = "SELECT media.archivo, media.nombre FROM media";
		$sql .= " LEFT JOIN con_rel_servicios_media ON con_rel_servicios_media.id_media = media.id";
		$sql .= " WHERE con_rel_servicios_media.id_contenido = ?";
		$sql .= " AND con_rel_servicios_media.idioma = ?";
		$sql .= " AND media.id_tipo = 9";
		$sql .= " ORDER BY con_rel_servicios_media.id DESC LIMIT 1";
		$placeholders[] = $parametros['id'];
		$placeholders[] = $parametros['idioma'];

		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT con_servicio_items.*, con_servicios_categorias.id as id_categoria, con_servicios_categorias.padre as categoria_padre, con_servicios_categorias.categoria";
		$sql .= " FROM con_servicio_items";
		$sql .= " LEFT JOIN con_servicios ON con_servicios.id = con_servicio_items.id_servicio";
		$sql .= " LEFT JOIN con_servicios_categorias ON con_servicios_categorias.id = con_servicios.id_categoria";
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

		$sql .= " AND con_servicio_items.estado = 2";
		$sql .= " AND con_servicio_items.titulo LIKE '%".$parametros['busqueda']."%'";
		$sql .= " ORDER BY con_servicio_items.titulo ASC";
		
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}
	
	//DUPLICAR 
	public function duplicarServicio($id)
	{
		$valores = $this->getDetalleServicio($id);
	
		$data1['grupo'] = $this->usuario->grupo;
		$data1['id_empresa'] = $this->usuario->id_empresa;
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['template'] = $valores['template'];
		$data1['titulo'] = $valores['titulo'].'-copy';
		$data1['estado'] = 1;
		$data1['fecha_alta'] = now();
		$data1['username_alta'] = $this->usuario->id;

		$insert1 = $this->db->insert('con_servicios', $data1);
		$res['id'] = $this->db->insert_id();

		if (!isset($res['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMAS
			foreach($idiomas as $idioma)
			{
				$parametros['idioma'] = $idioma['extension'];
				$parametros['id'] = $id;
				$item = $this->getServicioDetalleIdioma($parametros);

				if($item)
				{
					$data['id_servicio'] = $res['id'];
					$data['idioma'] = $item['idioma'];
					$data['uri'] = $item['uri'].'-copy';
					$data['titulo'] = $item['titulo'].'-copy';
					$data['subtitulo'] = $item['subtitulo'];
					$data['texto_adicional'] = $item['texto_adicional'];
					$data['contenido1'] = $item['contenido1'];
					$data['contenido2'] = $item['contenido2'];
					$data['contenido3'] = $item['contenido3'];
					$data['contenido4'] = $item['contenido4'];
					$data['contenido5'] = $item['contenido5'];
					$data['contenido6'] = $item['contenido6'];
					$data['contenido7'] = $item['contenido7'];
					$data['contenido8'] = $item['contenido8'];
					$data['contenido9'] = $item['contenido9'];
					$data['contenido10'] = $item['contenido10'];
					$data['orden'] = $item['orden'];
					$data['video'] = $item['video'];
					$data['id_moneda'] = $item['id_moneda'];
					$data['precio'] = $item['precio'];
					$data['descuento'] = $item['descuento'];
					$data['puntaje'] = $item['puntaje'];
					$data['seo_titulo'] = $item['seo_titulo'];
					$data['seo_descripcion'] = $item['seo_descripcion'];
					$data['destacado_slide'] = $item['destacado_slide'];
					$data['destacado'] = $item['destacado'];
					$data['estado'] = $item['estado'];
					$data['fecha_alta'] = now();
					$data['username_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_servicio_items', $data);
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	//CAMBIAR PUBLICACION
	public function publicarServicio($valores)
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
		$update = $this->db->update('con_servicio_items', $data, $where);

		return (!empty($update)) ? $update : null;
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
		$res2 = $this->db->update('con_servicio_items', $data, array("id" => $id, "idioma" => $extension));

		return (!empty($res2)) ? $res2 : null;
	}
	
	//ORDENAR GENERAL
	public function ordenarItems($items, $tabla)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update($tabla, $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	//ORDENAR GENERAL
	public function ordenarItemsInformacion($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_servicio_items_adicionales', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	//ELIMINAR
	public function eliminarServicio($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_servicios', $data, $where);
		
		$sql = "SELECT estado";
		$sql .= " FROM con_servicio_items";
		$sql .= " WHERE id_servicio = ".$valores['id'];
		$query = $this->db->query($sql);
		$items = $query->result_array();
		
		foreach($items as $item)
		{
			$data['estado'] = '-'.$item['estado'];
			$where_item = "id_servicio = ".$valores['id'];
			$res_item = $this->db->update('con_servicio_items', $data, $where_item);
		}

		return (!empty($res)) ? $res : null;
	}
	//FIN PARA VERIFICAR SI VAN

	public function eliminarInformacion($id = null)
	{
		$this->load->helper('date');
		$datos['estado'] = '-'.$this->input->post('estado');
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;
		if (!isset($res['error']))
		{
			$where = "id = ".$this->input->post('id');
			$res = $this->db->update('con_servicio_items_adicionales', $datos, $where);
		}		
		return ($res);
	}
}