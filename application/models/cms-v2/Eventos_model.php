<?php defined('BASEPATH') or exit('No direct script access allowed');

class Eventos_model extends CI_Model {

	public function getContenidos($parametros = null)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.filtro1, con_contenidos.filtro2, con_contenidos.destacado, con_contenidos.destacado_modal, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.texto_adicional, con_contenidos.imagen, con_contenidos.orden, con_secciones.seccion, con_secciones_tipo.tipo,
					CASE
						WHEN con_contenidos.estado = 3 THEN 'label-primary'
						WHEN con_contenidos.estado = 1 THEN 'label-danger'
					END AS estado_ui_class,
					
					CASE
						WHEN con_contenidos.estado = 3 THEN 'Publicada'
						WHEN con_contenidos.estado = 1 THEN 'Borrador'
					END AS estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_secciones_tipo ON con_secciones_tipo.id = con_secciones.id_secciones_tipo";
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
		
		$sql .= " AND con_contenidos.id_tipo = 11";
		$sql .= " AND con_contenido_items.idioma = 'es'";

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
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " con_contenidos.id";
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

	public function getEventosPublic($parametros = null)
	{
		if(isset($parametros['imagen']))
		{
			$sql = "SELECT con_contenidos.fecha_alta, con_contenidos.filtro1, con_contenido_items.titulo, con_contenido_items.id_contenido as id_item, con_contenido_items.precio, con_contenido_items.texto_adicional, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.contenido3, con_contenido_items.contenido4, (SELECT media_thumbs.archivo FROM media_thumbs LEFT JOIN media ON media.id = media_thumbs.referencia LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id WHERE con_rel_contenidos_media.id_contenido = id_item AND media_thumbs.id_tipo = 19 AND con_rel_contenidos_media.idioma = '". $parametros['idioma']."' ORDER BY con_rel_contenidos_media.id ASC LIMIT 1) AS imagen";
			$sql .= " FROM con_contenidos";
			$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
			$sql .= " WHERE con_contenidos.grupo = ?";
		}
		else
		{
			$sql = "SELECT con_contenidos.fecha_alta,  con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.texto_adicional, con_contenido_items.contenido1, con_contenido_items.contenido4";
			$sql .= " FROM con_contenidos";
			$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
			$sql .= " WHERE con_contenidos.grupo = ?";
		}
		
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
		
		$sql .= " AND con_contenidos.id_tipo = 11";
		$sql .= " AND con_contenido_items.idioma = '". $parametros['idioma']."'";

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
		
		if (!empty($parametros['imagen']))
		{
			$sql .= " AND con_contenidos.filtro1 != 5";
		}

		if (!empty($parametros['destacado']))
		{
			$sql .= " AND con_contenidos.destacado = 1";
		}

		if (!empty($parametros['modal']))
		{
			$sql .= " AND con_contenidos.destacado_modal = 1";
			$sql .= " ORDER BY con_contenidos.id DESC LIMIT 1";
		}
		else
		{
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " con_contenidos.orden";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
		}
					
		// consulta
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

	public function detalleExtraCurso($id)
	{
		$sql = "SELECT con_contenidos.id, contactos.nombre, contactos.apellido, con_elearning_items.titulo FROM con_contenidos";
		$sql .= " LEFT JOIN contactos ON contactos.id = con_contenidos.filtro1";
		$sql .= " LEFT JOIN con_elearning ON con_elearning.id = con_contenidos.filtro2";
		$sql .= " LEFT JOIN con_elearning_items ON con_elearning_items.id_elearning = con_elearning.id";
		$sql .= " WHERE con_contenidos.id = $id";
		$sql .= " AND con_contenidos.estado > 0";
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}
	
		
	public function getMedia($id, $idioma)
	{
		$sql = "SELECT media_thumbs.archivo FROM media_thumbs";
		$sql .= " LEFT JOIN media ON media.id = media_thumbs.referencia";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id";
		$sql .= " WHERE con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND con_rel_contenidos_media.idioma = '".$idioma."'";
		$sql .= " AND media_thumbs.id_tipo = 18";
		$sql .= " ORDER BY con_rel_contenidos_media.id ASC";		

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return (!empty($res)) ? $res : null;
	}

	//INGRESO INFORMACION
	public function ingresarInformacion($valores)
	{
		$this->load->helper('date');

		//INSERTO CONTENIDO GENERAL
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_tipo'] = $valores['id_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		if(isset($valores['orden'])) { $data['orden'] = $valores['orden']; }
		if(isset($valores['destacado'])) { $data['destacado'] = $valores['destacado']; }
		if(isset($valores['destacado_slide'])) { $data['destacado_slide'] = $valores['destacado_slide']; }
		if(isset($valores['destacado_modal'])) { $data['destacado_modal'] = $valores['destacado_modal']; }
		$data['filtro1'] = $valores['filtro1'];
		if(isset($valores['filtro2'])) { $data['filtro2'] = $valores['filtro2']; }
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
		$data['user_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_contenidos', $data);
		$res['id'] = $this->db->insert_id();

		if (!isset($res['error']))
		{			
			$idiomas = $this->getIdiomas();

			//CHEQUEO E INGRESO IDIOMA 1
			foreach($idiomas as $idioma)
			{
				$extension = $idioma['extension'];
				if($valores['titulo_'.$extension])
				{
					$item['id_contenido'] = $res['id'];
					$item['idioma'] = $idioma['extension'];
					$item['titulo'] = $valores['titulo_'.$extension];
					$item['subtitulo'] = $valores['subtitulo_'.$extension];
					$item['texto_adicional'] = $valores['texto_adicional_'.$extension];
					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $item['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $item['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $item['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $item['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $item['seo_titulo'] = $valores['seo_titulo_'.$extension]; }
					if(isset($valores['seo_keywords_'.$extension])) { $item['seo_keywords'] = $valores['seo_keywords_'.$extension]; }
					if(isset($valores['seo_descripcion_'.$extension])) { $item['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; }
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
						if(!empty($valores['contenido4_'.$extension])) { $slide['subtitulo'] = $valores['contenido4_'.$extension]; }
						else { $slide['subtitulo'] = $valores['url_slide_'.$extension]; }
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

		//MODIFICAR CONTENIDO GENERAL
		$data['id_tipo'] = $valores['id_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		if(isset($valores['orden'])) { $data['orden'] = $valores['orden']; }
		if(isset($valores['destacado'])) { $data['destacado'] = $valores['destacado']; }
		if(isset($valores['destacado_slide'])) { $data['destacado_slide'] = $valores['destacado_slide']; }
		if(isset($valores['destacado_modal'])) { $data['destacado_modal'] = $valores['destacado_modal']; }
		$data['filtro1'] = $valores['filtro1'];
		if(isset($valores['filtro2'])) { $data['filtro2'] = $valores['filtro2']; }
		$data['estado'] = $valores['estado'];
		if(isset($valores['fecha_alta'])) { $data['fecha_alta'] = $valores['fecha_alta']; }
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
					$item['subtitulo'] = $valores['subtitulo_'.$extension];
					$item['texto_adicional'] = $valores['texto_adicional_'.$extension];
					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $item['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['precio_'.$extension])) { $item['precio'] = $valores['precio_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $item['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $item['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['seo_titulo_'.$extension])) { $item['seo_titulo'] = $valores['seo_titulo_'.$extension]; }
					if(isset($valores['seo_keywords_'.$extension])) { $item['seo_keywords'] = $valores['seo_keywords_'.$extension]; }
					if(isset($valores['seo_descripcion_'.$extension])) { $item['seo_descripcion'] = $valores['seo_descripcion_'.$extension]; }

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
						$res['id'] = $this->db->insert_id();
					
						//INGRESO SLIDE
						if($valores['destacado_slide'] == 1)
						{
							$slide['id_con_contenido'] = $valores['slide_id_contenido'];
							$slide['id_tipo'] = $valores['slide_tipo'];
							$slide['idioma'] = $idioma['extension'];
							$slide['titulo'] = $valores['titulo_'.$extension];
							if(!empty($valores['contenido4_'.$extension])) { $slide['subtitulo'] = $valores['contenido4_'.$extension]; }
							else { $slide['subtitulo'] = $valores['url_slide_'.$extension]; }
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
						$res['id'] = $ingresado['id'];
						if(isset($valores['fecha_alta'])) { $item['fecha_alta'] = $valores['fecha_alta']; }
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
							if(!empty($valores['contenido4_'.$extension])) { $slide['subtitulo'] = $valores['contenido4_'.$extension]; }
							else { $slide['subtitulo'] = $valores['url_slide_'.$extension]; }
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
	public function asociarMedia($id, $proyecto, $extension, $nombre = null)
	{
		//CHEQUEO E INGRESO IDIOMA
/*
		$sql = "SELECT id FROM con_contenido_items WHERE id_contenido = $proyecto AND idioma = '$extension'";
		$query = $this->db->query($sql);
		$ingresado = $query->row_array();
*/

		$data['id_media'] = $id;
		$data['id_contenido'] = $proyecto;
		$res = $this->db->insert('con_rel_contenidos_media', $data);

		return (!empty($res)) ? $res : null;
	}

	//DUPLICO CONTENIDO
	public function duplicarEventos($id)
	{
		$this->load->helper('date');
		$valores = $this->getContenidoDetalle($id, array('modo'=>'raw'));
		
		//INSERTO CONTENIDO GENERAL
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_tipo'] = $valores['id_tipo'];
		$data['id_con_secciones'] = $valores['id_con_secciones'];
		$data['orden'] = $valores['orden'];
		$data['destacado'] = $valores['destacado'];
		$data['destacado_slide'] = $valores['destacado_slide'];
		$data['destacado_modal'] = $valores['destacado_modal'];
		$data['filtro1'] = $valores['filtro1'];
		$data['filtro2'] = $valores['filtro2'];
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
				$valores1 = $this->getContenidoDetalleIdioma($id, $extension);

				if($valores1)
				{
					$idioma1['id_contenido'] = $res['id'];
					$idioma1['idioma'] = $valores1['idioma'];
					$idioma1['titulo'] = $valores1['titulo'].'-copy';
					$idioma1['subtitulo'] = $valores1['subtitulo'];
					$idioma1['texto_adicional'] = $valores1['texto_adicional'];
					if(isset($valores1['contenido1'])) { $idioma1['contenido1'] = $valores1['contenido1']; }
					$idioma1['precio'] = $valores1['precio'];
					$idioma1['contenido3'] = $valores1['contenido3'];
					$idioma1['contenido4'] = $valores1['contenido4'];
					$idioma1['contenido2'] = $valores1['contenido2'];
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

 	public function comboEmpresas($parametros = null)
	{
		$sql = "SELECT contactos.id, contactos.nombre, contactos.apellido, contactos_extras.tipo_contacto";
		$sql .= " FROM contactos";
		$sql .= " LEFT JOIN contactos_extras ON contactos_extras.id_contacto = contactos.id";
		$sql .= " WHERE contactos.grupo = ?";
		$placeholders[] = $this->usuario->grupo;

		if ($this->usuario->perfil == 'reseller')
		{
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$sql .= " AND contactos.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND contactos.estado >= 0";
		$sql .= " AND contactos_extras.tipo_contacto = 1";
		$sql .= " ORDER BY contactos.apellido ASC, contactos.nombre ASC";
		$query = $this->db->query($sql, $placeholders);
		$res = $query->result_array();

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['apellido'].' '.$valor['nombre'];
		}
		return (!empty($padre)) ? $padre : null;
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

	//ELIMINAR
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
//PROBADA
}