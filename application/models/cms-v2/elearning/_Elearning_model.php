<?php defined('BASEPATH') or exit('No direct script access allowed');

class Elearning_model extends CI_Model {

	public function getCursos($parametros = null)
	{
		$sql = "SELECT con_elearning_items.id, con_elearning_items.id_elearning, con_elearning_items.contenido1, con_elearning_items.uri,  con_elearning_items.titulo, con_elearning_items.puntaje, con_elearning_items.precio_oferta, con_elearning_items.precio, con_elearning_items.estado, con_elearning_items.destacado, con_elearning_items.contenido5, con_elearning_items.fecha_alta, con_elearning_items.subtitulo, con_elearning_categorias.id as id_categoria, con_elearning_categorias.padre as categoria_padre, con_elearning_categorias.categoria, con_elearning_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_elearning = con_elearning.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media";
		$sql .= " WHERE con_elearning.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_elearning.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['idioma']))
		{
			$sql .= " AND con_elearning_items.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		if (isset($parametros['id_categoria']))
		{
			if($parametros['id_categoria'] != 0)
			{
				$sql .= " AND con_elearning_categorias.id = ?";
				$placeholders[] = $parametros['id_categoria'];
			}
		}

		if (isset($parametros['padre']))
		{
			$sql .= " AND con_elearning_categorias.padre = ?";
			$placeholders[] = $parametros['padre'];
		}

		if ((isset($parametros['estado'])) && (isset($parametros['idioma'])))
		{
			$sql .= " AND con_elearning_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_rel_elearning_media.idioma = ?";
			$placeholders[] = $parametros['idioma'];
			$sql .= " GROUP BY con_elearning.id";
		}
		elseif ((isset($parametros['estado'])) && (!isset($parametros['idioma'])))
		{
			$sql .= " AND con_elearning_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " GROUP BY con_elearning.id";
		}
		else
		{
			$sql .= " AND con_elearning_items.estado >= 0";
			$sql .= " GROUP BY con_elearning.id";
		}
		
		if (isset($parametros['orden']))
		{
			switch($parametros['orden'])
			{
				case 0: $sql .= " ORDER BY con_elearning_items.orden ASC, con_elearning.id ASC"; break;
				case 1: $sql .= " ORDER BY con_elearning_items.precio ASC"; break;
				case 2: $sql .= " ORDER BY con_elearning_items.precio DESC"; break;
				case 3: $sql .= " ORDER BY con_elearning_items.titulo ASC"; break;
				case 4: $sql .= " ORDER BY con_elearning_items.titulo DESC"; break;
			}
		}
		else
		{
			$sql .= " ORDER BY con_elearning.orden ASC, con_elearning.id ASC";
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

	public function getCursosDestacados($parametros)
	{
		$sql = "SELECT con_elearning_items.id_elearning, con_elearning_items.titulo, con_elearning_items.puntaje, con_elearning_items.precio, con_elearning_items.contenido6, con_elearning_items.subtitulo, con_elearning_categorias.id as id_categoria, con_elearning_categorias.padre as categoria_padre, con_elearning_categorias.categoria, con_elearning_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 32 LIMIT 1) AS imagen";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_elearning = con_elearning.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media";
		$sql .= " WHERE con_elearning.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_elearning.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['idioma']))
		{
			$sql .= " AND con_elearning_items.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		if (isset($parametros['id_categoria']))
		{
			$sql .= " AND con_elearning_categorias.id = ?";
			$placeholders[] = $parametros['id_categoria'];
		}

		if (isset($parametros['seccion']))
		{
			$sql .= " AND con_elearning_categorias.seccion = ?";
			$placeholders[] = $parametros['seccion'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_elearning_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$sql .= " AND con_rel_elearning_media.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		else
		{
			$sql .= " AND con_elearning_items.estado >= 0";
		}
		$sql .= " AND con_elearning_items.destacado = 1";
		$sql .= " GROUP BY con_elearning.id";
		
		if (isset($parametros['orden']))
		{
			switch($parametros['orden'])
			{
				case 0: $sql .= " ORDER BY con_elearning_items.orden ASC, con_elearning.id ASC"; break;
				case 1: $sql .= " ORDER BY con_elearning_items.precio ASC"; break;
				case 2: $sql .= " ORDER BY con_elearning_items.precio DESC"; break;
				case 3: $sql .= " ORDER BY con_elearning_items.titulo ASC"; break;
				case 4: $sql .= " ORDER BY con_elearning_items.titulo DESC"; break;
			}
		}
		else
		{
			$sql .= " ORDER BY con_elearning_items.orden ASC, con_elearning.id ASC";
			
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
	
	public function getDetalleCurso($id, $parametros=null)
	{
		$sql = "SELECT con_elearning.*, con_elearning_categorias.id as id_categoria, con_elearning_categorias.categoria, con_elearning_categorias.padre";
		$sql .= " FROM con_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " WHERE con_elearning.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_elearning.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			$sql .= " AND con_elearning.id = $id";
			if (isset($parametros['estado']))
			{
				$sql .= " AND con_elearning.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND con_elearning.estado >= 0";
			}
		}
		
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getCursoDetalleIdioma($id, $idioma, $parametros = null)
	{
		if (!empty($parametros['id_pedido']))
		{
			if ($parametros['id_tipo'] == 2)
			{
				$sql = "SELECT con_elearning_items.*, con_elearning_items.id as id_item, con_elearning.id_evento, con_rel_pedido_contactos.certificado as certificar, con_elearning_categorias.id as id_categoria, con_elearning_categorias.categoria, con_elearning_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
				$sql .= " FROM con_elearning_items";
				$sql .= " LEFT JOIN con_carro_pedidos_items ON con_carro_pedidos_items.id_producto = con_elearning_items.id_elearning";
				$sql .= " LEFT JOIN con_rel_pedido_contactos ON con_rel_pedido_contactos.id_item = con_elearning_items.id";
			}
			else
			{
				$sql = "SELECT con_elearning_items.*, con_elearning_items.id as id_item, con_elearning.id_evento, con_carro_pedidos_items.certificado as certificar, con_elearning_categorias.id as id_categoria, con_elearning_categorias.categoria, con_elearning_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
				$sql .= " FROM con_elearning_items";
				$sql .= " LEFT JOIN con_carro_pedidos_items ON con_carro_pedidos_items.id_producto = con_elearning_items.id_elearning";
			}
		}
		else
		{
			$sql = "SELECT con_elearning_items.*, con_elearning_items.id as id_item, con_elearning.id_evento, con_elearning_categorias.id as id_categoria, con_elearning_categorias.categoria, con_elearning_categorias.seccion, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = 37 LIMIT 1) AS imagen";
			$sql .= " FROM con_elearning_items";
		}
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_elearning = con_elearning.id";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media";
		$sql .= " WHERE con_elearning.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_elearning.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_elearning_items.id_elearning = $id";
		$sql .= " AND con_elearning_items.idioma = '$idioma'";

		if (!empty($parametros['id_pedido']))
		{
			$sql .= " AND con_carro_pedidos_items.id_con_car_pedido = ?";
			$placeholders[] = $parametros['id_pedido'];
		}

		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_elearning.estado = ?";
			$sql .= " AND con_elearning_items.estado = ?";
			$placeholders[] = $parametros['estado'];
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_elearning.estado > 0";
			$sql .= " AND con_elearning_items.estado > 0";
		}

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR 
	public function ingresarCurso($valores)
	{
		$this->load->helper('text');
		$data1['grupo'] = $this->usuario->grupo;
		$data1['id_empresa'] = $this->usuario->id_empresa;
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['titulo'] = trim($valores['titulo']);
		if (!empty($valores['estado'])) $data1['estado'] = $valores['estado'];

		if(!empty($valores['orden']))
		{
			$sql = "SELECT id, orden";
			$sql .= " FROM con_elearning";
			$sql .= " WHERE grupo = ? AND id_empresa = ? AND id_categoria = ?";
			$sql .= " AND estado > 0";
			$sql .= " ORDER BY orden DESC LIMIT 1";
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $valores['id_categoria'];
			$query = $this->db->query($sql, $placeholders);
			$orden = $query->row_array();
	
			if($orden)
			{
				$data1['orden'] = $orden['orden']+1;
			}
			else
			{
				$data1['orden'] = 0;
			}
		}
		else
		{
			$data1['orden'] = $valores['orden'];
		}	

		$data1['fecha_alta'] = now();
		$data1['username_alta'] = $this->usuario->id;
		$insert = $this->db->insert('con_elearning', $data1);
		$res['id'] = $this->db->insert_id();
		
		if($res['id'])
		{
			$res['id'] = $this->db->insert_id();
			$idiomas = $this->getIdiomas();
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$data['id_elearning'] = $res['id'];
					$data['idioma'] = $idioma['extension'];
					$data['titulo'] = trim($valores['titulo_'.$extension]);

					if($valores['url_'.$extension])
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
					if(isset($valores['duracion_'.$extension])) { $data['duracion'] = $valores['duracion_'.$extension]; }
					if(isset($valores['fecha_'.$extension])) {$data['fecha'] = date('Y-m-d', strtotime($valores['fecha_'.$extension])); }
					if(isset($valores['profesores_'.$extension])) { $data['profesores'] = json_encode($valores['profesores_'.$extension]); }
					if(isset($valores['contenido1_'.$extension])) { $data['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $data['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $data['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $data['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['contenido5_'.$extension])) { $data['contenido5'] = $valores['contenido5_'.$extension]; }
					if(isset($valores['video_'.$extension])) { $data['video'] = $valores['video_'.$extension]; }
					if(isset($valores['link_'.$extension])) { $data['link'] = $valores['link_'.$extension]; }
					if(isset($valores['id_moneda_'.$extension])) { $data['id_moneda'] = $valores['id_moneda_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $data['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['precio_oferta_'.$extension])) { $data['precio_oferta'] = $valores['precio_oferta_'.$extension]; }
					if(isset($valores['descuento_'.$extension])) { $data['descuento'] = $valores['descuento_'.$extension]; }
					if(isset($valores['id_proyecto_'.$extension])) { $data['id_proyecto'] = $valores['id_proyecto_'.$extension]; }
					if(isset($valores['orden_'.$extension])) { $data['orden'] = $valores['orden_'.$extension]; }
					if(isset($valores['puntaje_'.$extension])) { $data['puntaje'] = $valores['puntaje_'.$extension]; }
					if(isset($valores['certificado_'.$extension])) { $data['certificado'] = $valores['certificado_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $data['seo_titulo'] = $valores['seo_titulo_'.$extension]; }
					if(isset($valores['seo_descripcion_'.$extension])) { $data['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; }
					if(isset($valores['destacado_slide_'.$extension])) { $data['destacado_slide'] = $valores['destacado_slide_'.$extension]; }
					if(isset($valores['destacado_adicional_'.$extension])) { $data['destacado_adicional'] = $valores['destacado_adicional_'.$extension]; }
					if(isset($valores['destacado_'.$extension])) { $data['destacado'] = $valores['destacado_'.$extension]; }
					if(isset($valores['estado_'.$extension])) { $data['estado'] = $valores['estado_'.$extension]; }
					$data['fecha_alta'] = now();
					$data['username_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_elearning_items', $data);
					$res['idioma_'.$extension] = $this->db->insert_id();
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	public function modificarCurso($id, $valores)
	{
		$this->load->helper('text');
		if (!empty($valores['id_categoria'])) $data1['id_categoria'] = $valores['id_categoria'];
		if (!empty($valores['titulo'])) $data1['titulo'] = trim($valores['titulo']);
		if (!empty($valores['estado'])) $data1['estado'] = $valores['estado'];
		if (!empty($valores['orden'])) $data1['orden'] = $valores['orden'];
		$data1['fecha_modificacion'] = now();
		$data1['username_modificacion'] = $this->usuario->id;
		$where1 = "id = ".$id;
		$res1 = $this->db->update('con_elearning', $data1, $where1);		

		if($res1)
		{
			$idiomas = $this->getIdiomas();
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];

				if($valores['titulo_'.$extension])
				{
					$data['id_elearning'] = $id;
					$data['idioma'] = $idioma['extension'];
					$data['titulo'] = trim($valores['titulo_'.$extension]);

					if($valores['url_'.$extension])
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
					if(isset($valores['duracion_'.$extension])) { $data['duracion'] = $valores['duracion_'.$extension]; }
					if(isset($valores['fecha_'.$extension])) {$data['fecha'] = date('Y-m-d', strtotime($valores['fecha_'.$extension])); }
					if(isset($valores['profesores_'.$extension])) { $data['profesores'] = json_encode($valores['profesores_'.$extension]); }
					if(isset($valores['contenido1_'.$extension])) { $data['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $data['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $data['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $data['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['contenido5_'.$extension])) { $data['contenido5'] = $valores['contenido5_'.$extension]; }
					if(isset($valores['video_'.$extension])) { $data['video'] = $valores['video_'.$extension]; }
					if(isset($valores['link_'.$extension])) { $data['link'] = $valores['link_'.$extension]; }
					if(isset($valores['id_moneda_'.$extension])) { $data['id_moneda'] = $valores['id_moneda_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $data['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['precio_oferta_'.$extension])) { $data['precio_oferta'] = $valores['precio_oferta_'.$extension]; }
					if(isset($valores['descuento_'.$extension])) { $data['descuento'] = $valores['descuento_'.$extension]; }
					if(isset($valores['id_proyecto_'.$extension])) { $data['id_proyecto'] = $valores['id_proyecto_'.$extension]; }
					if(isset($valores['orden_'.$extension])) { $data['orden'] = $valores['orden_'.$extension]; }
					if(isset($valores['puntaje_'.$extension])) { $data['puntaje'] = $valores['puntaje_'.$extension]; }
					if(isset($valores['certificado_'.$extension])) { $data['certificado'] = $valores['certificado_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $data['seo_titulo'] = $valores['seo_titulo_'.$extension]; }
					if(isset($valores['seo_descripcion_'.$extension])) { $data['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; }
					if(isset($valores['destacado_slide_'.$extension])) { $data['destacado_slide'] = $valores['destacado_slide_'.$extension]; }
					if(isset($valores['destacado_adicional_'.$extension])) { $data['destacado_adicional'] = $valores['destacado_adicional_'.$extension]; }
					if(isset($valores['destacado_'.$extension])) { $data['destacado'] = $valores['destacado_'.$extension]; }
					if(isset($valores['estado_'.$extension])) { $data['estado'] = $valores['estado_'.$extension]; }

					$sql = "SELECT id FROM con_elearning_items WHERE id_elearning = $id AND idioma = '$extension'";
					$query = $this->db->query($sql);
					$ingresado = $query->row_array();
	
					if (!isset($ingresado))
					{
						$data['id_elearning'] = $id;
						$data['fecha_alta'] = now();
						$data['username_alta'] = $this->usuario->id;
						$insert = $this->db->insert('con_elearning_items', $data);
						$res['idioma_'.$extension] = $this->db->insert_id();
					}
					else
					{
						$res['idioma_'.$extension] = $ingresado['id'];
						$data['fecha_modificacion'] = now();
						$data['username_modificacion'] = $this->usuario->id;
						$where = "id = ".$ingresado['id'];
						$update = $this->db->update('con_elearning_items', $data, $where);
					}
				}	
			}			
		}
		return (!empty($res)) ? $res : null;
	}

	public function asociarEvento($id, $id_evento)
	{
		$data['id_evento'] = $id_evento;
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$id;
		$res = $this->db->update('con_elearning', $data, $where);
		return (!empty($res)) ? $res : null;
	}

	public function ordenarCursos($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    $this->db->update('con_elearning_items', $data, array('id'=>$items[$i]));
		    $res[] = $i . ' ' . $items[$i];
		}
		return (!empty($res)) ? $res : null;
	}

	public function verificarUrl($url, $idioma)
	{
		$sql = "SELECT con_elearning_items.uri, con_elearning_items.id_elearning";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " WHERE con_elearning_items.uri = '".trim($url)."'";
		$sql .= " AND con_elearning_items.idioma = '$idioma'";
		$sql .= " AND con_elearning.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_elearning.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function verificarUrlTitulo($titulo, $idioma)
	{
		$sql = "SELECT con_elearning_items.uri, con_elearning_items.id_elearning";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " WHERE (con_elearning_items.uri = '".trim($titulo)."' || uri = '".trim($titulo)."-copy')";
		$sql .= " AND con_elearning_items.idioma = '$idioma'";
		$sql .= " AND con_elearning.grupo = ".$this->usuario->grupo;
		$sql .= " AND con_elearning.id_empresa = ".$this->usuario->id_empresa;
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

 	public function getProfesoresCursos($parametros)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.titulo, con_contenido_items_adicionales.imagen FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " LEFT JOIN con_rel_contenido_elearning ON con_rel_contenido_elearning.id_contenido_adicional = con_contenido_items_adicionales.id";
		$sql .= " WHERE con_contenidos.grupo = ?";
		$sql .= " AND con_contenidos.id_empresa = ?";
		$sql .= " AND con_rel_contenido_elearning.id_elearning = ?";
		$sql .= " AND con_contenido_items_adicionales.id_tipo = ?";
		$sql .= " AND con_contenido_items_adicionales.idioma = ?";
 		$sql .= " AND con_contenido_items_adicionales.estado = 3";
		$sql .= " ORDER BY con_contenido_items_adicionales.orden ASC";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $parametros['id'];
		$placeholders[] = $parametros['id_tipo'];
		$placeholders[] = $parametros['idioma'];

		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		
		return (!empty($res)) ? $res : null;
	}

 	public function comboProfesores($id, $id_tipo, $idioma)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.titulo FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " WHERE con_contenidos.grupo = ? AND con_contenidos.id_empresa = ?";
		$sql .= " AND con_contenido_items_adicionales.id_con_contenido = ? AND con_contenido_items_adicionales.id_tipo = ? AND con_contenido_items_adicionales.idioma = ?";
		$sql .= " AND con_contenido_items_adicionales.estado = 3";
		$sql .= " ORDER BY con_contenido_items_adicionales.orden ASC";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id;
		$placeholders[] = $id_tipo;
		$placeholders[] = $idioma;

		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		
		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['titulo'];
		}
		return (!empty($padre)) ? $padre : null;
	}
	
 	public function comboCursos($parametros = null)
	{
		$sql = "SELECT con_elearning_items.id, con_elearning_items.id_elearning, con_elearning_items.titulo";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " WHERE con_elearning.grupo = ?";
		$placeholders[] = $this->usuario->grupo;

		if ($this->usuario->perfil == 'reseller')
		{
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$sql .= " AND con_elearning.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['idioma']))
		{
			$sql .= " AND con_elearning_items.idioma = ?";
			$placeholders[] = $parametros['idioma'];
		}

		$sql .= " AND con_elearning_items.estado >= 0";
		$sql .= " GROUP BY con_elearning.id";
		$sql .= " ORDER BY con_elearning.orden ASC, con_elearning.id ASC";
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id_elearning']] = $valor['titulo'];
		}
		return (!empty($padre)) ? $padre : null;
	}

	public function asociarMedia($id, $proyecto, $idioma, $tipo)
	{
		$sql = "SELECT con_rel_elearning_media.id";
		$sql .= " FROM con_rel_elearning_media";
		$sql .= " LEFT JOIN media ON media.id = con_rel_elearning_media.id_media"; 
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = media.id"; 
		$sql .= " WHERE media.grupo = ".$this->usuario->grupo;
		$sql .= " AND media.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND con_rel_elearning_media.id_elearning = $proyecto"; 
		$sql .= " AND con_rel_elearning_media.idioma = '$idioma'";
		$sql .= " AND media_thumbs.id_tipo = $tipo";
		$query = $this->db->query($sql);
		$res = $query->row_array();	

		if (!isset($res['error']))
		{
			//BORRO LA RELACION ANTERIOR
			$this->db->where('id', $res['id']);
			$this->db->delete('con_rel_elearning_media'); 
		}
		$data['id_media'] = $id;
		$data['id_elearning'] = $proyecto;
		$data['idioma'] = $idioma;
		$data['id_tipo'] = $tipo;
		
		if (!isset($res['error']))
		{
			$res = $this->db->insert('con_rel_elearning_media', $data);
		}
		return (!empty($res)) ? $res : null;
	}


//ver sin van
	//ASOCIAR MEDIA

	public function getMedia($id, $id_tipo, $idioma)
	{
		$sql = "SELECT media_thumbs.archivo FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_media = media.id";
		$sql .= " WHERE con_rel_elearning_media.id_elearning = $id";
		$sql .= " AND media_thumbs.id_tipo = $id_tipo";
		$sql .= " AND con_rel_elearning_media.idioma = '".$idioma."'";
		$sql .= " ORDER BY con_rel_elearning_media.id ASC";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function getArchivo($id, $idioma)
	{
		$sql = "SELECT media.archivo FROM media";
		$sql .= " LEFT JOIN con_rel_elearning_media ON con_rel_elearning_media.id_media = media.id";
		$sql .= " WHERE con_rel_elearning_media.id_elearning = $id";
		$sql .= " AND con_rel_elearning_media.idioma = '".$idioma."'";
		$sql .= " AND media.id_tipo = 9";
		$sql .= " ORDER BY con_rel_elearning_media.id ASC";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT con_elearning_items.*, con_elearning_categorias.id as id_categoria, con_elearning_categorias.padre as categoria_padre, con_elearning_categorias.categoria";
		$sql .= " FROM con_elearning_items";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_elearning_items.id_elearning";
		$sql .= " LEFT JOIN con_elearning_categorias ON con_elearning_categorias.id = con_elearning.id_categoria";
		$sql .= " WHERE con_elearning.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_elearning.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_elearning.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_elearning_items.estado = 2";
		$sql .= " AND con_elearning_items.titulo LIKE '%".$parametros['busqueda']."%'";
		$sql .= " ORDER BY con_elearning_items.titulo ASC";
		
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();
		return (!empty($res)) ? $res : null;
	}
	
	public function duplicarCurso($id)
	{
		$valores = $this->getDetalleCurso($id);
		$data1['grupo'] = $this->usuario->grupo;
		$data1['id_empresa'] = $this->usuario->id_empresa;
		$data1['id_categoria'] = $valores['id_categoria'];
		$data1['template'] = $valores['template'];
		$data1['titulo'] = $valores['titulo'].'-copy';
		$data1['estado'] = 1;
		$data1['fecha_alta'] = now();
		$data1['username_alta'] = $this->usuario->id;
		$insert1 = $this->db->insert('con_elearning', $data1);
		$res['id'] = $this->db->insert_id();

		if (!isset($res['error']))
		{			
			$idiomas = $this->getIdiomas();
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				$item = $this->getCursoDetalleIdioma($id, $extension);

				if($item)
				{
					$data['id_elearning'] = $res['id'];
					$data['idioma'] = $item['idioma'];
					$data['uri'] = $item['uri'].'-copy';
					$data['titulo'] = $item['titulo'].'-copy';
					$data['subtitulo'] = $item['subtitulo'];
					$data['contenido1'] = $item['contenido1'];
					$data['contenido2'] = $item['contenido2'];
					$data['contenido3'] = $item['contenido3'];
					$data['contenido4'] = $item['contenido4'];
					$data['contenido5'] = $item['contenido5'];
					$data['video'] = $item['video'];
					$data['link'] = $item['link'];
					$data['id_moneda'] = $item['id_moneda'];
					$data['precio'] = $item['precio'];
					$data['precio_oferta'] = $item['precio_oferta'];
					$data['descuento'] = $item['descuento'];
					$data['id_proyecto'] = $item['id_proyecto'];
					$data['orden'] = $item['orden'];
					$data['puntaje'] = $item['puntaje'];
					$data['seo_titulo'] = $item['seo_titulo'];
					$data['seo_descripcion'] = $item['seo_descripcion'];
					$data['destacado_slide'] = $item['destacado_slide'];
					$data['destacado_adicional'] = $item['destacado_adicional'];
					$data['destacado'] = $item['destacado'];
					$data['estado'] = 1;
					$data['fecha_alta'] = now();
					$data['username_alta'] = $this->usuario->id;
					$insert = $this->db->insert('con_elearning_items', $data);
				}
			}
		}
		return (!empty($res)) ? $res : null;
	}

	public function publicarCurso($valores)
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
		$where = "id = ".$valores['id'];
		$update = $this->db->update('con_elearning_items', $data, $where);
		return (!empty($update)) ? $update : null;
	}

	public function updateImagen($id, $imagen, $extension, $id_imagen_tipo)
	{
		$sql = "SELECT media_thumbs.archivo";
		$sql .= " FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " WHERE media_thumbs.referencia = $imagen";
		$sql .= " AND media_thumbs.id_tipo = $id_imagen_tipo";
		$query = $this->db->query($sql);
		$res = $query->row_array();
		
		$data['imagen'] = $res['archivo'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$res2 = $this->db->update('con_elearning_items', $data, array("id" => $id, "idioma" => $extension));

		return (!empty($res2)) ? $res2 : null;
	}
	
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

	public function eliminarCurso($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_elearning', $data, $where);
		$sql = "SELECT estado";
		$sql .= " FROM con_elearning_items";
		$sql .= " WHERE id_elearning = ".$valores['id'];
		$query = $this->db->query($sql);
		$items = $query->result_array();
		
		foreach($items as $item)
		{
			$data['estado'] = '-'.$item['estado'];
			$where_item = "id_elearning = ".$valores['id'];
			$res_item = $this->db->update('con_elearning_items', $data, $where_item);
		}
		return (!empty($res)) ? $res : null;
	}
	//FIN PARA VERIFICAR SI VAN
}