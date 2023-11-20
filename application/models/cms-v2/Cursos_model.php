<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cursos_model extends CI_Model {

	/* 	Usado por API también  	*/
	public function listadoContenidos($estado)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.url, con_contenidos.imagen, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		if($estado == 3)
		{
			$sql .= " WHERE con_contenidos.estado = 3";
		}
		else
		{
			$sql .= " WHERE con_contenidos.estado > 0";
		}
		$sql .= " AND con_secciones.id = 3";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " ORDER BY con_contenidos.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	/* 	Fin Usado por API también  */

	public function listadoContenidosAdicionales($id, $id_tipo, $idioma,$estado)
	{
		$sql = "SELECT con_contenido_items_adicionales.*, con_estados.estado";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenido_items_adicionales.estado";
		$sql .= " WHERE con_contenido_items_adicionales.id_con_contenido = ".$id;
		$sql .= " AND con_contenido_items_adicionales.id_tipo = ".$id_tipo;
		$sql .= " AND con_contenido_items_adicionales.idioma = '".$idioma."'";
		if($estado == 3)
		{
			$sql .= " AND con_contenido_items_adicionales.estado = 3";
		}
		else
		{
			$sql .= " AND con_contenido_items_adicionales.estado > 0";
		}
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " ORDER BY con_contenido_items_adicionales.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleContenidoCms($id)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.imagen, con_contenidos.color, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.destacado_slide, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.contenido3, con_contenido_items.archivo1, con_contenido_items.archivo2, con_contenido_items.archivo3, con_contenido_items.url, con_secciones.seccion, con_estados.estado, con_rel_contenidos_media_proyectos.id_media_proyecto as media_proyecto";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " LEFT JOIN con_rel_contenidos_media_proyectos ON con_rel_contenidos_media_proyectos.id_contenido = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenidos.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleContenido($id)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.imagen, con_contenidos.color, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.destacado_slide, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.contenido3, con_contenido_items.archivo1, con_contenido_items.archivo2, con_contenido_items.archivo3, con_contenido_items.url, con_secciones.seccion, con_estados.estado, con_rel_contenidos_media_proyectos.id_media_proyecto as media_proyecto";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " LEFT JOIN con_rel_contenidos_media_proyectos ON con_rel_contenidos_media_proyectos.id_contenido = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenidos.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleContenidoAdicional($id)
	{
		$sql = "SELECT con_contenido_items_adicionales.id, con_contenido_items_adicionales.titulo, con_contenido_items_adicionales.subtitulo";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_contenido";
		$sql .= " WHERE con_contenido_items_adicionales.estado > 0";
		$sql .= " AND con_contenido_items_adicionales.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items_adicionales.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleContenidoItems($id)
	{
		$sql = "SELECT con_contenido_items.*, con_idiomas.idioma, con_monedas.moneda";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_idiomas ON con_idiomas.nomenclatura = con_contenido_items.idioma";
		$sql .= " LEFT JOIN con_monedas ON con_monedas.id = con_contenido_items.id_moneda";
		$sql .= " WHERE con_contenido_items.id_contenido = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detallePregunta($id)
	{
		$sql = "SELECT con_contenido_items_adicionales.*";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items_adicionales.id_con_contenido";
		$sql .= " WHERE con_contenido_items_adicionales.estado > 0";
		$sql .= " AND con_contenido_items_adicionales.idioma = 'es'";
		$sql .= " AND con_contenido_items_adicionales.id = $id";
		$sql .= " AND con_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	/* API */
	public function detalleContenidoUrl($url)
	{		
		$sql = "SELECT con_contenidos.id, con_contenidos.imagen,con_contenidos.color, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenido_items.id as id_item, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.contenido3, con_contenido_items.url as link, con_contenido_items.precio, con_contenido_items.precioUsd, con_contenido_items.descuento, con_contenido_items.seo_titulo, con_contenido_items.seo_keywords, con_contenido_items.seo_descripcion";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenido_items.url = '$url'";
		$sql .= " AND con_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function listadoRelacionadosApi($id)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.imagen, con_contenidos.puntaje, con_contenido_items.titulo, con_contenido_items.url";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_rel_contenidos ON con_rel_contenidos.id_contenido_relacionado = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenidos.id_tipo = 3";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_rel_contenidos.id_contenido_principal = $id";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_rel_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleFavorito($usuario, $curso)
	{
		$sql = "SELECT con_favoritos.id";
		$sql .= " FROM con_favoritos";
		$sql .= " WHERE con_favoritos.id_con_contacto = $usuario";
		$sql .= " AND con_favoritos.id_con_contenido = $curso";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function ingresarFavorito($variables2)
	{		
		if($variables2['favorito'] == 3)
		{
			$sql = "SELECT con_favoritos.id";
			$sql .= " FROM con_favoritos";
			$sql .= " WHERE con_favoritos.id_con_contacto = ".$variables2['id_contacto'];
			$sql .= " AND con_favoritos.id_con_contenido = ".$variables2['id_curso'];
			$query = $this->db->query($sql);
			$res = $query->row_array();
	
			if(!isset($res['id']))
			{
				$datos['id_con_contacto'] = $variables2['id_contacto'];
				$datos['id_con_contenido'] = $variables2['id_curso'];
				$insert = $this->db->insert('con_favoritos', $datos);
				$res['id'] = $this->db->insert_id();
			}
		}
		elseif($variables2['favorito'] == 1)
		{
			$sql = "SELECT con_favoritos.id";
			$sql .= " FROM con_favoritos";
			$sql .= " WHERE con_favoritos.id_con_contacto = ".$variables2['id_contacto'];
			$sql .= " AND con_favoritos.id_con_contenido = ".$variables2['id_curso'];
			$query = $this->db->query($sql);
			$res = $query->row_array();
	
			if(!isset($res['id']))
			{
				$datos['id_con_contacto'] = $variables2['id_contacto'];
				$datos['id_con_contenido'] = $variables2['id_curso'];
				$insert = $this->db->insert('con_favoritos', $datos);
				$res['id'] = $this->db->insert_id();
			}
			else
			{
				$where = "id = ".$res['id'];
				$res = $this->db->delete('con_favoritos', $where);
			}
		}
		return ($res);
	}
	/* 	Fin Usado por API también  */
	/* Fin API */

	public function detalleContenidoIdioma($id, $idioma)
	{
		$sql = "SELECT con_contenido_items.*";
		$sql .= " FROM con_contenido_items";
		$sql .= " WHERE con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function listadoMedia($id)
	{
		$sql = "SELECT con_media.id, con_media.titulo, con_media.miniatura, con_media.imagen,  con_media.fecha_alta, con_media.estado";
		$sql .= " FROM con_media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = con_media.id";
		$sql .= " WHERE con_media.estado > 0";
		$sql .= " AND con_rel_contenidos_media.id_contenido = $id";
		$sql .= " ORDER BY orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	
	public function listadoMediaApi($id)
	{
		$sql = "SELECT con_media.id, con_media.titulo, con_media.miniatura, con_media.imagen, con_media.orden, con_media.fecha_alta, con_media.estado";
		$sql .= " FROM con_media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = con_media.id";
		$sql .= " WHERE con_media.estado = 3";
		$sql .= " AND con_rel_contenidos_media.id_contenido = $id";
		$sql .= " ORDER BY orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	public function ordenarMedia($tipo, $items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$data['user_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_media', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function ordenarCurso($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$data['user_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_contenidos', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function ingresarContenido($id)
	{
		$this->load->helper('date');

		if (empty($this->input->post('id')))
		{
			$datos['id_tipo'] = 3;
			$datos['grupo'] = $this->usuario->grupo;
			$datos['id_empresa'] = $this->usuario->id_empresa;
			$datos['id_con_secciones'] = $this->input->post('seccion');
			$datos['puntaje'] = $this->input->post('puntaje');
			$datos['destacado'] = $this->input->post('destacado');
			$datos['color'] = $this->input->post('color');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;
	
			if (!isset($res['error']))
			{
				//INSERTO CONTENIDO
				$insert = $this->db->insert('con_contenidos', $datos);
				$res['id'] = $this->db->insert_id();

				//CHEQUEO IDIOMA 1
				if (!empty($this->input->post('titulo')))
				{					
					//VERIFICO URL
					if(!empty($this->input->post('url')))
					{
						$sql = "SELECT url";
						$sql .= " FROM con_contenido_items";
						$sql .= " WHERE (url = '".trim($this->input->post('url'))."' || url = '".trim($this->input->post('url1'))."-copy')";
						$sql .= " AND idioma = 'es'";
						$query = $this->db->query($sql);
						$url = $query->row_array();

						if(!empty($url['url'])) { $idioma1['url'] = url_title($this->input->post('url')).'-copy'; } else { $idioma1['url'] = url_title($this->input->post('url')); } 
					}
					else
					{
						$sql = "SELECT url";
						$sql .= " FROM con_contenido_items";
						$sql .= " WHERE (url = '".trim($this->input->post('title'))."' || url = '".trim($this->input->post('title'))."-copy')";
						$sql .= " AND idioma = 'es'";
						$query = $this->db->query($sql);
						$url = $query->row_array();
						
						if(!empty($url['url'])) { $idioma1['url'] = url_title($this->input->post('titulo')).'-copy'; } else { $idioma1['url'] = url_title($this->input->post('titulo')); } 
					}

					//INSERTO IDIOMA 1
					$idioma1['id_contenido'] = $res['id'];
					$idioma1['idioma'] = 'es';
					$idioma1['titulo'] = $this->input->post('titulo');
					$idioma1['contenido1'] = $this->input->post('contenido1');
					$idioma1['contenido2'] = $this->input->post('contenido2');
					if(!empty($this->input->post('contenido3'))) { $idioma1['contenido3'] = $this->input->post('contenido3'); }
					$idioma1['precio'] = $this->input->post('precio');
					$idioma1['precioUsd'] = $this->input->post('precioUsd');
					$idioma1['descuento'] = $this->input->post('descuento');
					$idioma1['seo_titulo'] = $this->input->post('seo_titulo');
					$idioma1['seo_keywords'] = $this->input->post('seo_keywords');
					$idioma1['seo_descripcion'] = $this->input->post('seo_descripcion');
					$idioma1['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
					$idioma1['user_alta'] = $this->usuario->id;
					
					$insert = $this->db->insert('con_contenido_items', $idioma1);
				}
			}	
		}
		else
		{
			$datos['id_tipo'] = 3;
			$datos['grupo'] = $this->usuario->grupo;
			$datos['id_empresa'] = $this->usuario->id_empresa;
			$datos['id_con_secciones'] = $this->input->post('seccion');
			$datos['puntaje'] = $this->input->post('puntaje');
			$datos['color'] = $this->input->post('color');
			$datos['destacado'] = $this->input->post('destacado');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
	
			if (!isset($res['error']))
			{
				$id = $this->input->post('id');
				$where = "id = $id";
				$res = $this->db->update('con_contenidos', $datos, $where);
				
				//CHEQUEO IDIOMA 1
				if (!empty($this->input->post('titulo')))
				{
					//INSERTO IDIOMA 1
					$idioma1['id_contenido'] = $this->input->post('id');
					$idioma1['idioma'] = 'es';
					$idioma1['titulo'] = $this->input->post('titulo');
					$idioma1['contenido1'] = $this->input->post('contenido1');
					$idioma1['contenido2'] = $this->input->post('contenido2');
					if(!empty($this->input->post('contenido3'))) { $idioma1['contenido3'] = $this->input->post('contenido3'); }
					$idioma1['precio'] = $this->input->post('precio');
					$idioma1['precioUsd'] = $this->input->post('precioUsd');
					$idioma1['descuento'] = $this->input->post('descuento');
					$idioma1['seo_titulo'] = $this->input->post('seo_titulo');
					$idioma1['seo_keywords'] = $this->input->post('seo_keywords');
					$idioma1['seo_descripcion'] = $this->input->post('seo_descripcion');
					$idioma1['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
					$idioma1['user_modificacion'] = $this->usuario->id;

					//CHEQUEO QUE ESTE INGRESADO EL IDIOMA 1
					$sql = "SELECT con_contenido_items.id";
					$sql .= " FROM con_contenido_items";
					$sql .= " WHERE con_contenido_items.id_contenido = $id";
					$sql .= " AND con_contenido_items.idioma = 'es'";

					$query = $this->db->query($sql);
					$res = $query->row_array();
					$idioma = $res['id'];

					if(!empty($this->input->post('url')))
					{
						//VERIFICO URL
						$sql = "SELECT url";
						$sql .= " FROM con_contenido_items";
						$sql .= " WHERE (url = '".trim($this->input->post('url'))."' || url = '".trim($this->input->post('url'))."-copy')";
						$sql .= " AND idioma = 'es'";
						$sql .= " AND id != ".$idioma;
						$query = $this->db->query($sql);
						$url = $query->row_array();

						if(!empty($url['url'])) { $idioma1['url'] = url_title($this->input->post('url')).'-copy'; } else { $idioma1['url'] = url_title($this->input->post('url')); } 
					}
					else
					{
						//VERIFICO URL
						$sql = "SELECT url";
						$sql .= " FROM con_contenido_items";
						$sql .= " WHERE (url = '".trim($this->input->post('title'))."' || url = '".trim($this->input->post('title'))."-copy')";
						$sql .= " AND idioma = 'es'";
						$sql .= " AND id != ".$idioma;
						$query = $this->db->query($sql);
						$url = $query->row_array();
						
						if(!empty($url['url'])) { $idioma1['url'] = url_title($this->input->post('titulo')).'-copy'; } else { $idioma1['url'] = url_title($this->input->post('titulo')); } 
					}

					if (isset($idioma))
					{
						$id = $this->input->post('id');
						$where = "id_contenido = $id AND id = $idioma";
						$update = $this->db->update('con_contenido_items', $idioma1, $where);
					}
					else
					{
						$insert = $this->db->insert('con_contenido_items', $idioma1);
					}
				}
			}
			
			//RELACIONO MEDIA
			//INSERTO CONTENIDO RELACIONADO
			$id = $this->input->post('id');
			//BORRO CAMPOS RELACIONADOS ANTERIORES

			//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
			$sql = "SELECT con_rel_contenidos_media_proyectos.id";
			$sql .= " FROM con_rel_contenidos_media_proyectos";
			$sql .= " WHERE con_rel_contenidos_media_proyectos.id_contenido = $id";
			$query = $this->db->query($sql);
			$res = $query->row_array();
			$id_eliminar = $res['id'];

			if (isset($id_eliminar))
			{
				$where = "id_contenido = $id";
				$delete = $this->db->delete('con_rel_contenidos_media_proyectos', $where);
			}
			$media['id_contenido'] = $this->input->post('id');
			$media['id_media_proyecto'] = $this->input->post('media_proyecto');
	
			$insert2 = $this->db->insert('con_rel_contenidos_media_proyectos', $media);
			//FIN RELACIONO MEDIA
		}
		return ($res);
	}

	public function ingresarContenidoAdicional()
	{
		$datos['id_con_contenido'] = $this->input->post('id');
		$datos['id_tipo'] = $this->input->post('id_tipo');
		$datos['orden'] = $this->input->post('orden');
		$datos['titulo'] = $this->input->post('titulo');
		$datos['subtitulo'] = $this->input->post('subtitulo');
		$datos['estado'] = 3;
		$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_contenido_items_adicionales', $datos);
	}

	public function modificarContenidoAdicional($id)
	{
		$datos['orden'] = $this->input->post('orden');
		$datos['titulo'] = $this->input->post('titulo');
		$datos['subtitulo'] = $this->input->post('subtitulo');
		$datos['estado'] = 3;
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where = "id = ".$this->input->post('id');
		$update = $this->db->update('con_contenido_items_adicionales', $datos, $where);
	}
	
	public function duplicarItem($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_contenidos.*";
		$sql .= " FROM con_contenidos";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id = $id";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_tipo'] = 3;
			$datos['grupo'] = $res['grupo'];
			$datos['id_empresa'] = $res['id_empresa'];
			$datos['id_con_secciones'] = $res['id_con_secciones'];
			$datos['orden'] = $res['orden'];
			$datos['miniatura'] = $res['miniatura'];
			$datos['imagen'] = $res['imagen'];
			$datos['detalle'] = $res['detalle'];
			$datos['color'] = $res['color'];
			$datos['imagen_adicional'] = $res['imagen_adicional'];
			$datos['puntaje'] = $res['puntaje'];
			$datos['destacado'] = $res['destacado'];
			$datos['destacado_slide'] = $res['destacado_slide'];
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_contenidos', $datos);
			$res2['id'] = $this->db->insert_id();
	
			$sql = "SELECT con_contenido_items.*";
			$sql .= " FROM con_contenido_items";
			$sql .= " WHERE con_contenido_items.idioma = 'es'";
			$sql .= " AND con_contenido_items.id_contenido = $id";
		
			$query = $this->db->query($sql);
			$idioma1 = $query->row_array();
			
			if (!isset($idioma1['error']))
			{
				$datos1['id_contenido'] = $res2['id'];
				$datos1['idioma'] = $idioma1['idioma'];
				$datos1['titulo'] = $idioma1['titulo'].' copy';
				$datos1['subtitulo'] = $idioma1['subtitulo'];
				$datos1['url'] = $idioma1['url'].'-copy';
				$datos1['texto_adicional'] = $idioma1['texto_adicional'];
				$datos1['contenido1'] = $idioma1['contenido1'];
				$datos1['contenido2'] = $idioma1['contenido2'];
				$datos1['contenido3'] = $idioma1['contenido3'];
				$datos1['contenido4'] = $idioma1['contenido4'];
				$datos1['contenido5'] = $idioma1['contenido5'];
				$datos1['contenido6'] = $idioma1['contenido6'];
				$datos1['contenido7'] = $idioma1['contenido7'];
				$datos1['id_moneda'] = $idioma1['id_moneda'];
				$datos1['precio'] = $idioma1['precio'];
				$datos1['precioUsd'] =$idioma1['precioUsd'];
				$datos1['descuento'] = $idioma1['descuento'];
				$datos1['imagen'] = $idioma1['imagen'];
				$datos1['seo_titulo'] = $idioma1['seo_titulo'];
				$datos1['seo_keywords'] = $idioma1['seo_keywords'];
				$datos1['seo_descripcion'] = $idioma1['seo_descripcion'];
				$datos1['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$datos1['user_alta'] = $this->usuario->id;
				
				$insert = $this->db->insert('con_contenido_items', $datos1);
				$res3['id'] = $this->db->insert_id();

				//DUPLICO ITEMS ADICIONALES 1
				$sql = "SELECT con_contenido_items_adicionales.*";
				$sql .= " FROM con_contenido_items_adicionales";
				$sql .= " WHERE con_contenido_items_adicionales.idioma = 'es'";
				$sql .= " AND con_contenido_items_adicionales.id_con_contenido = $id";
				$sql .= " AND con_contenido_items_adicionales.id_tipo = 1";
				$query = $this->db->query($sql);
				$adicionales = $query->result_array();
				
				foreach ($adicionales as $adicional)
				{
					$datos2['id_tipo'] = 1;
					$datos2['id_con_contenido'] = $res2['id'];
					$datos2['idioma'] = $adicional['idioma'];
					$datos2['titulo'] = $adicional['titulo'];
					$datos2['subtitulo'] = $adicional['subtitulo'];
					$datos2['contenido1'] = $adicional['contenido1'];
					$datos2['contenido2'] = $adicional['contenido2'];
					$datos2['contenido3'] = $adicional['contenido3'];
					$datos2['contenido4'] = $adicional['contenido4'];
					$datos2['contenido5'] = $adicional['contenido5'];
					$datos2['contenido6'] = $adicional['contenido6'];
					$datos2['contenido7'] = $adicional['contenido7'];
					$datos2['orden'] = $adicional['orden'];
					$datos2['estado'] = 3;
					$datos2['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
					$datos2['user_alta'] = $this->usuario->id;

					$insert = $this->db->insert('con_contenido_items_adicionales', $datos2);
				}

				//DUPLICO ITEMS ADICIONALES 2
				$sql2 = "SELECT con_contenido_items_adicionales.*";
				$sql2 .= " FROM con_contenido_items_adicionales";
				$sql2 .= " WHERE con_contenido_items_adicionales.idioma = 'es'";
				$sql2 .= " AND con_contenido_items_adicionales.id_con_contenido = $id";
				$sql2 .= " AND con_contenido_items_adicionales.id_tipo = 2";
				$query = $this->db->query($sql2);
				$adicionales2 = $query->result_array();
				
				foreach ($adicionales2 as $adicional2)
				{
					$datos3['id_tipo'] = 2;
					$datos3['id_con_contenido'] = $res2['id'];
					$datos3['idioma'] = $adicional2['idioma'];
					$datos3['titulo'] = $adicional2['titulo'];
					$datos3['subtitulo'] = $adicional2['subtitulo'];
					$datos3['contenido1'] = $adicional2['contenido1'];
					$datos3['contenido2'] = $adicional2['contenido2'];
					$datos3['contenido3'] = $adicional2['contenido3'];
					$datos3['contenido4'] = $adicional2['contenido4'];
					$datos3['contenido5'] = $adicional2['contenido5'];
					$datos3['contenido6'] = $adicional2['contenido6'];
					$datos3['contenido7'] = $adicional2['contenido7'];
					$datos3['orden'] = $adicional2['orden'];
					$datos3['estado'] = 3;
					$datos3['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
					$datos3['user_alta'] = $this->usuario->id;

					$insert = $this->db->insert('con_contenido_items_adicionales', $datos3);
				}
			}
		}
		return ($res);
	}

	public function ingresarPregunta($id)
	{
		$this->load->helper('date');

		if (empty($this->input->post('id')))
		{
			$datos['id_con_contenido'] = $this->input->post('id_contenido');
			$datos['id_tipo'] = 5;
			$datos['orden'] = $this->input->post('orden');
			$datos['titulo'] = $this->input->post('titulo');
			$datos['subtitulo'] = $this->input->post('subtitulo');
			$datos['contenido1'] = $this->input->post('contenido1');
			$datos['contenido2'] = $this->input->post('contenido2');
			$datos['contenido3'] = $this->input->post('contenido3');
			$datos['contenido4'] = $this->input->post('contenido4');
			$datos['contenido5'] = $this->input->post('contenido5');
			$datos['contenido6'] = $this->input->post('contenido6');
			$datos['contenido7'] = $this->input->post('contenido7');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;
	
			$res = $this->db->insert('con_contenido_items_adicionales', $datos);
		}
		else
		{
			$datos2['orden'] = $this->input->post('orden');
			$datos2['titulo'] = $this->input->post('titulo');
			$datos2['subtitulo'] = $this->input->post('subtitulo');
			$datos2['contenido1'] = $this->input->post('contenido1');
			$datos2['contenido2'] = $this->input->post('contenido2');
			$datos2['contenido3'] = $this->input->post('contenido3');
			$datos2['contenido4'] = $this->input->post('contenido4');
			$datos2['contenido5'] = $this->input->post('contenido5');
			$datos2['contenido6'] = $this->input->post('contenido6');
			$datos2['contenido7'] = $this->input->post('contenido7');
			$datos2['estado'] = $this->input->post('estado');
			$datos2['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos2['user_modificacion'] = $this->usuario->id;

			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenido_items_adicionales', $datos2, $where);
		}
		return ($res);
	}

 
	public function duplicarPregunta()
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_contenido_items_adicionales.*";
		$sql .= " FROM con_contenido_items_adicionales";
		$sql .= " WHERE con_contenido_items_adicionales.estado > 0";
		$sql .= " AND con_contenido_items_adicionales.id = ".$this->input->post('id');
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_con_contenido'] = $res['id_con_contenido'];
			$datos['id_tipo'] = 5;
			$datos['orden'] = $res['orden'];
			$datos['titulo'] = $res['titulo'].' copy';
			$datos['subtitulo'] = $res['subtitulo'];
			$datos['contenido1'] = $res['contenido1'];
			$datos['contenido2'] = $res['contenido2'];
			$datos['contenido3'] = $res['contenido3'];
			$datos['contenido4'] = $res['contenido4'];
			$datos['contenido5'] = $res['contenido5'];
			$datos['contenido6'] = $res['contenido6'];
			$datos['contenido7'] = $res['contenido7'];
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_contenido_items_adicionales', $datos);
		}
		return ($res);
	}


    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirMiniatura($id, $imagen)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-mapa-'.url_title($_FILES['miniatura']['name']);
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('miniatura'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['miniatura'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenidos', $data, $where);
        }
    }

    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirOriginal($id, $imagen)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-imagen-'.url_title($_FILES['image']['name']);
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('image'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['imagen'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenidos', $data, $where);
        }
    }

    //FUNCIÓN PARA INSERTAR LOS ARCHIVOS ARCHIVO 1
    function subirPDF($id, $archivo)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-pdf-'.url_title($_FILES['archivo1']['name']);
	    $config['allowed_types'] = 'pdf';
	    $config['max_size']= 10000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('archivo1'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['archivo1'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id_contenido = $id";
			$res = $this->db->update('con_contenido_items', $data, $where);
        }
    }

    //FUNCIÓN PARA INSERTAR LOS ARCHIVOS ARCHIVO 2
    function subirYapa($id, $archivo)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-yapa-'.url_title($_FILES['archivo2']['name']);
	    $config['allowed_types'] = 'pdf';
	    $config['max_size']= 10000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('archivo2'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['archivo2'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id_contenido = $id";
			$res = $this->db->update('con_contenido_items', $data, $where);
        }
    }

    //FUNCIÓN PARA INSERTAR LOS ARCHIVOS ARCHIVO 3
    function subirIngredientes($id, $archivo)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-ingredientes-'.url_title($_FILES['archivo3']['name']);
	    $config['allowed_types'] = 'pdf';
	    $config['max_size']= 16000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('archivo3'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['archivo3'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id_contenido = $id";
			$res = $this->db->update('con_contenido_items', $data, $where);
        }
    }
    
    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirImagenAdicional($id, $imagen_adicional)
    {
		$x = date('YmdHis');

		//CARGO IMAGEN
		$image_path_adicional = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path_adicional;
        $config['file_name'] = $x.'-breadcrumb-'.url_title($_FILES['imagen_adicional']['name']);
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2400;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('imagen_adicional'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['imagen_adicional'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenidos', $data, $where);
        }
    }

	public function eliminarMedia($id)
	{
		$this->load->helper('date');
		$this->load->helper('file');

		$datos['estado'] = '-3';
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_media', $datos, $where);

			//BORRO EL ARCHIVO
			$sql = "SELECT con_media.imagen";
			$sql .= " FROM con_media";
			$sql .= " WHERE con_media.id = ".$this->input->post('id');

			$query = $this->db->query($sql);
			$res = $query->row_array();
			$path = $res['imagen'];
			
			if (file_exists('./multimedia/511/7358/'.$path))
			{
				unlink('./multimedia/511/7358/'.$path);
			}
			
			//BORRO LA RELACION CON CONTENIDOS
			$tester = $this->db->where('id_media', $id);
			$this->db->delete('con_rel_contenidos_media'); 
		}
		return ($res);
	}

	public function listadoGaleriaRelacionar()
	{
		$sql = "SELECT con_media.id, con_media.imagen, con_media.titulo, con_media.fecha_alta as fecha";
		$sql .= " FROM con_media";
		$sql .= " WHERE con_media.estado = 3";
		$sql .= " GROUP BY con_media.id";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoRelacionados($id)
	{
		$sql = "SELECT con_rel_contenidos_media.id_contenido, con_rel_contenidos_media.id_media";
		$sql .= " FROM con_rel_contenidos_media";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $id";
	
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function relacionarGaleria($id)
	{
		//INSERTO CONTENIDO RELACIONADO
		$id = $this->input->post('id');

		foreach ($this->input->post('relaciones') as $relacionado)
		{
			//BORRO CAMPOS RELACIONADOS ANTERIORES
			$sql = "SELECT con_rel_contenidos_media.id";
			$sql .= " FROM con_rel_contenidos_media";
			$sql .= " WHERE con_rel_contenidos_media.id_media = $relacionado";
	
			$query = $this->db->query($sql);
			$res = $query->row_array();
			$id_eliminar = $res['id'];
			
			if (isset($id_eliminar))
			{
				$where = "id_media = $relacionado";
				$delete = $this->db->delete('con_rel_contenidos_media', $where);
			}

			$relacionar['id_contenido'] = $this->input->post('id');
			$relacionar['id_media'] = $relacionado;

			$insert = $this->db->insert('con_rel_contenidos_media', $relacionar);
		}
	}

	public function desasociarMedia($id)
	{
		$sql = "SELECT con_rel_contenidos_media.id";
		$sql .= " FROM con_rel_contenidos_media";
		$sql .= " WHERE con_rel_contenidos_media.id_media = ".$this->input->post('id');
		$sql .= " AND con_rel_contenidos_media.id_contenido = ".$this->input->post('id_contenido');

		$query = $this->db->query($sql);
		$res = $query->row_array();
		$id = $res['id'];

		if (!isset($res['error']))
		{
			//BORRO LA RELACION CON CONTENIDOS
			$this->db->where('id', $id);
			$this->db->delete('con_rel_contenidos_media'); 
		}
		return ($res);
	}

	public function listadoContenidosRelacionar()
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_secciones.id = 3";
		$sql .= " AND con_contenidos.id != ".$this->uri->segment(4);
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoContenidosRelacionados($id)
	{
		$sql = "SELECT con_rel_contenidos.id_contenido_relacionado, con_rel_contenidos.id";
		$sql .= " FROM con_rel_contenidos";
		$sql .= " WHERE con_rel_contenidos.id_contenido_principal = $id";
		$sql .= " AND con_rel_contenidos.grupo = 511";
		$sql .= " AND con_rel_contenidos.id_empresa = 7358";
	
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function relacionarContenido($id)
	{
		//VERIFICO ID DEL CONTENIDO
		if (!empty($this->input->post('id')))
		{
			
			//INSERTO CONTENIDO RELACIONADO
			$id = $this->input->post('id');
			//BORRO CAMPOS RELACIONADOS ANTERIORES

			//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
			$sql = "SELECT con_rel_contenidos.id";
			$sql .= " FROM con_rel_contenidos";
			$sql .= " WHERE con_rel_contenidos.id_contenido_principal = $id";

			$query = $this->db->query($sql);
			$res = $query->row_array();
			$id_eliminar = $res['id'];

			if (isset($id_eliminar))
			{
				$where = "id_contenido_principal = $id";
				$delete = $this->db->delete('con_rel_contenidos', $where);
			}

			if(!empty($this->input->post('relaciones')))
			{
				foreach ($this->input->post('relaciones') as $relacionado)
				{
					$datos['grupo'] = 511;
					$datos['id_empresa'] = 7358;
					$datos['id_contenido_principal'] = $this->input->post('id');
					$datos['id_contenido_relacionado'] = $relacionado;
			
					$insert = $this->db->insert('con_rel_contenidos', $datos);
				}
			}
		}
		else
		{
			echo "error";
		}
	}

	public function eliminarContenido($id)
	{
		$this->load->helper('date');

		$datos['estado'] = '-3';
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenidos', $datos, $where);
		}
		return ($res);
	}

	public function eliminarContenidoAdicional($id)
	{
		$this->load->helper('date');

		$datos['estado'] = '-3';
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_contenido_items_adicionales', $datos, $where);
		}
		return ($res);
	}
}