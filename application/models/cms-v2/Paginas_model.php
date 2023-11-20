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
		
		// consulta
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
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, con_contenidos.estado, media.id as id_media, (SELECT media_thumbs.archivo FROM media_thumbs WHERE media_thumbs.referencia = media.id AND media_thumbs.id_tipo = $id_imagen LIMIT 1) AS imagen_breadcrumb";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN media ON media.id = con_rel_contenidos_media.id_media";
		$sql .= " WHERE con_contenidos.grupo = ?";
		$sql .= " AND con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";

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

	public function getBusqueda($parametros = null)
	{
		$sql = "SELECT con_contenido_items.id, con_contenido_items.idioma, con_contenido_items.titulo, con_contenido_items.url, con_contenido_items.contenido1, con_secciones.seccion, con_contenidos.estado";
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

		$sql .= " AND con_contenido_items.idioma = '".$parametros['idioma']."'";
		$sql .= " AND con_secciones.id_secciones_tipo = 9";
		$sql .= " AND con_contenido_items.titulo LIKE '%".$parametros['busqueda']."%'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		$sql .= " ORDER BY con_contenido_items.titulo ASC";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
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
		$sql .= " WHERE con_contenido_items.id_contenido = $id";
		$sql .= " AND con_contenido_items.idioma = '$idioma'";
		$sql .= " AND con_rel_contenidos_media.id_media = id_media";
		$sql .= " AND con_rel_contenidos_media.idioma = '$idioma'";
		$sql .= " ORDER BY con_rel_contenidos_media.id DESC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	//MODIFICAR CONTENIDO
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
					if(isset($valores['subtitulo_'.$extension])) { $item['subtitulo'] = $valores['subtitulo_'.$extension]; }
					if(isset($valores['texto_adicional_'.$extension])) { $item['texto_adicional'] = $valores['texto_adicional_'.$extension]; }
					if(isset($valores['contenido1_'.$extension])) { $item['contenido1'] = $valores['contenido1_'.$extension]; }
					if(isset($valores['contenido2_'.$extension])) { $item['contenido2'] = $valores['contenido2_'.$extension]; }
					if(isset($valores['contenido3_'.$extension])) { $item['contenido3'] = $valores['contenido3_'.$extension]; }
					if(isset($valores['contenido4_'.$extension])) { $item['contenido4'] = $valores['contenido4_'.$extension]; }
					if(isset($valores['contenido5_'.$extension])) { $item['contenido5'] = $valores['contenido5_'.$extension]; }
					if(isset($valores['contenido6_'.$extension])) { $item['contenido6'] = $valores['contenido6_'.$extension]; }
					if(isset($valores['contenido7_'.$extension])) { $item['contenido7'] = $valores['contenido7_'.$extension]; }
		
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


	//ASOCIAR MEDIA
	public function asociarMedia($id, $proyecto, $idioma)
	{
		$sql = "SELECT con_rel_contenidos_media.id FROM con_rel_contenidos_media WHERE id_contenido = $proyecto AND idioma = '$idioma'";
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
					$item['seo_keywords'] = $valores['seo_keywords_'.$extension];
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
	
	public function getContenidoAdicionalIdioma($id, $id_tipo, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items_adicionales.*";
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
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND con_contenido_items_adicionales.id_con_contenido = $id";

		if($id_tipo)
		{
			$sql .= " AND con_contenido_items_adicionales.id_tipo = $id_tipo";
		}
		$sql .= " AND con_contenido_items_adicionales.idioma = '$idioma'";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$sql .= " AND con_contenido_items_adicionales.estado = 3";
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
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
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
		if(isset($valores['texto_adicional'])) { $datos['texto_adicional'] = $valores['texto_adicional']; }
		if(!empty($valores['contenido1'])) { $datos['contenido1'] = $valores['contenido1']; }
		if(isset($valores['sin_texto'])) { $datos['sin_texto'] = $valores['sin_texto']; }
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
	public function ingresarArchivo($id, $imagen, $medidas, $id_imagen_tipo = null)
	{
		//Medidas de la imagen
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

		//INGRESO IMAGEN
		$data['imagen'] = $res['archivo'];
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
	
	public function getPaginaDetalleRaw($id)
	{
		return $this->getPaginaDetalle($id, array('modo'=>'raw'));
	}

	public function getPaginaDetalleIdioma($id, $idioma, $parametros = null)
	{
		$sql = "SELECT con_contenido_items.*, con_contenidos.grupo, con_contenidos.id_empresa";
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
		$sql .= " AND estado > 0";

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
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