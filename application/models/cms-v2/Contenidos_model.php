<?php defined('BASEPATH') or exit('No direct script access allowed');

class Contenidos_model extends CI_Model {

	public function listadoItems($id, $parametros = null)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.id_con_secciones, con_contenidos.user_alta, con_contenidos.fecha_alta, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.url, con_contenidos.orden, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id_tipo = 1";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.grupo = ? 
				AND con_contenidos.id_empresa = ?";
		
	/*
		if(!empty($filtro))
		{
			$sql .= " AND con_secciones.id = ".$filtro;
		}
		$sql .= " ORDER BY con_contenidos.id_con_secciones ASC, con_contenidos.orden ASC";
*/
	
		// permisos	
		if ((isset($this->usuario->perfil) && $this->usuario->perfil == 'reseller') || (isset($this->usuario->perfil) && $this->usuario->perfil == 'admin'))
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function listadoItemsPublic($filtro)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.id_con_secciones, con_contenidos.user_alta, con_contenidos.fecha_alta, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.url, con_contenidos.orden, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenidos.id_tipo = 1";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		if(!empty($filtro))
		{
			$sql .= " AND con_secciones.id = ".$filtro;
		}
		$sql .= " ORDER BY con_contenidos.id_con_secciones ASC, con_contenidos.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoPadres()
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion FROM con_secciones WHERE con_secciones.id_secciones_tipo = 1 AND con_secciones.estado > 0 ORDER BY id ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();

/* 		$padre[] = '--- Seleccione una opción ---'; */

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['seccion'];
		}
		return ($padre);
	}

	public function detalleItem($id)
	{
		$sql = "SELECT con_contenidos.id_con_secciones, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.destacado_slide, con_contenidos.estado as id_estado, con_contenido_items.id_contenido, con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.idioma, con_contenido_items.url, con_contenido_items.seo_titulo, con_contenido_items.seo_descripcion, con_contenido_items.seo_keywords, con_secciones.seccion, con_secciones.imagen as imagen_seccion, con_estados.estado, con_rel_contenidos_media_proyectos.id_media_proyecto as media_proyecto";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " LEFT JOIN con_rel_contenidos_media_proyectos ON con_rel_contenidos_media_proyectos.id_contenido = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	/* API */
	public function detalleItemUrl($url)
	{
		$sql = "SELECT con_contenidos.id_con_secciones, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.destacado_slide, con_contenidos.estado as id_estado, con_contenido_items.id_contenido, con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.idioma, con_contenido_items.url, con_contenido_items.seo_titulo, con_contenido_items.seo_descripcion, con_contenido_items.seo_keywords, con_secciones.seccion, con_estados.estado, con_rel_contenidos_media_proyectos.id_media_proyecto as media_proyecto";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " LEFT JOIN con_rel_contenidos_media_proyectos ON con_rel_contenidos_media_proyectos.id_contenido = con_contenidos.id";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenido_items.url = '$url'";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleCategoria($id)
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarItem($id)
	{
		$this->load->helper('date');

		if (empty($_POST['id']))
		{
			$datos['grupo'] = 511;
			$datos['id_empresa'] = 7358;
			$datos['id_tipo'] = 1;
			$datos['id_con_secciones'] = $this->input->post('categoria');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			if (!isset($res['error']))
			{
				//INSERTO CONTENIDO
				$insert = $this->db->insert('con_contenidos', $datos);
				$res['id'] = $this->db->insert_id();

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
				$idioma1['subtitulo'] = $this->input->post('subtitulo');
				$idioma1['contenido1'] = $this->input->post('contenido1');
				$idioma1['contenido2'] = $this->input->post('contenido2');
				$idioma1['seo_titulo'] = $this->input->post('seo_titulo');
				$idioma1['seo_keywords'] = $this->input->post('seo_keywords');
				$idioma1['seo_descripcion'] = $this->input->post('seo_descripcion');
				$idioma1['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$idioma1['user_alta'] = $this->usuario->id;
				
				$insert = $this->db->insert('con_contenido_items', $idioma1);

				if(!empty($this->input->post('media_proyecto')))
				{
					//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
					$sql = "SELECT con_rel_contenidos_media_proyectos.id";
					$sql .= " FROM con_rel_contenidos_media_proyectos";
					$sql .= " WHERE con_rel_contenidos_media_proyectos.id_contenido = ".$res['id'];
					$query = $this->db->query($sql);
					$res2 = $query->row_array();
					$id_eliminar = $res2['id'];
		
					if (isset($id_eliminar))
					{
						$where = "id_contenido = ".$res['id'];
						$delete = $this->db->delete('con_rel_contenidos_media_proyectos', $where);
					}
					$media['id_contenido'] = $res['id'];
					$media['id_media_proyecto'] = $this->input->post('media_proyecto');
			
					$insert2 = $this->db->insert('con_rel_contenidos_media_proyectos', $media);
				}
			}
		}	
		else
		{
			$datos['id_con_secciones'] = $this->input->post('categoria');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
	
			if (!isset($res['error']))
			{
				$id = $this->input->post('id');
				$where = "id = $id";
				$res = $this->db->update('con_contenidos', $datos, $where);
				
				//INSERTO IDIOMA 1
				$idioma1['id_contenido'] = $this->input->post('id');
				$idioma1['idioma'] = 'es';
				$idioma1['titulo'] = $this->input->post('titulo');
				$idioma1['subtitulo'] = $this->input->post('subtitulo');
				$idioma1['contenido1'] = $this->input->post('contenido1');
				$idioma1['contenido2'] = $this->input->post('contenido2');
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
				
				if(!empty($this->input->post('media_proyecto')))
				{
					//CHEQUEO SI TIENE RELACIONES EL CONTENIDO EN EL IDIOMA
					$sql = "SELECT con_rel_contenidos_media_proyectos.id";
					$sql .= " FROM con_rel_contenidos_media_proyectos";
					$sql .= " WHERE con_rel_contenidos_media_proyectos.id_contenido = ".$this->input->post('id');
					$query = $this->db->query($sql);
					$res2 = $query->row_array();
					$id_eliminar = $res2['id'];
		
					if (isset($id_eliminar))
					{
						$where = "id_contenido = ".$this->input->post('id');
						$delete = $this->db->delete('con_rel_contenidos_media_proyectos', $where);
					}
					$media['id_contenido'] = $this->input->post('id');
					$media['id_media_proyecto'] = $this->input->post('media_proyecto');
			
					$insert2 = $this->db->insert('con_rel_contenidos_media_proyectos', $media);
				}
			}
		}
		return ($res);
	}

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

	public function ingresarContenidoAdicional()
	{
		$datos['id_con_contenido'] = $this->input->post('id');
		$datos['id_tipo'] = $this->input->post('id_tipo');
		$datos['orden'] = $this->input->post('orden');
		$datos['titulo'] = $this->input->post('titulo');
		$datos['subtitulo'] = $this->input->post('subtitulo');
		$datos['contenido1'] = $this->input->post('contenido1');
		$datos['contenido2'] = $this->input->post('contenido2');
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
		$datos['contenido1'] = $this->input->post('contenido1');
		$datos['contenido2'] = $this->input->post('contenido2');
		$datos['estado'] = 3;
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		$where = "id = ".$this->input->post('id');
		$update = $this->db->update('con_contenido_items_adicionales', $datos, $where);
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

	public function duplicarItem($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_contenidos.*";
		$sql .= " FROM con_contenidos";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenidos.id = $id";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_tipo'] = 1;
			$datos['grupo'] = $res['grupo'];
			$datos['id_empresa'] = $res['id_empresa'];
			$datos['id_con_secciones'] = $res['id_con_secciones'];
			$datos['orden'] = $res['orden'];
			$datos['miniatura'] = $res['miniatura'];
			$datos['imagen'] = $res['imagen'];
			$datos['detalle'] = $res['detalle'];
			$datos['imagen_adicional'] = $res['imagen_adicional'];
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
			}
		}
		return ($res);
	}
	
	public function listadoMedia($id,$id_tipo)
	{
		$sql = "SELECT con_media.id, con_media.titulo, con_media.imagen, con_media.orden, con_media.fecha_alta, con_media.estado";
		$sql .= " FROM con_media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = con_media.id";
		$sql .= " WHERE con_media.estado > 0";
		$sql .= " AND con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND con_media.id_tipo_contenido = $id_tipo";
		$sql .= " ORDER BY orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoMediaCantidad($id, $id_tipo)
	{
		$sql = "SELECT con_media.id, con_media.titulo, con_media.imagen, con_media.orden, con_media.fecha_alta, con_media.estado";
		$sql .= " FROM con_media";
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = con_media.id";
		$sql .= " WHERE con_media.estado > 0";
		$sql .= " AND con_rel_contenidos_media.id_contenido = $id";
		$sql .= " AND con_media.id_tipo_contenido = $id_tipo";
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

    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirImagen($id, $imagen)
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

    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirOriginal($id, $imagen)
    {
		$x = date('YmdHis');
		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-imagen-'.url_title($_FILES['imagen_adicional']['name']);
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
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

	public function ordenarContenidos($items)
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

    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN DEL SLIDE
    function subirSlideshow($id, $imagen)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-'.$_FILES['imagen_slide']['name'];
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('imagen_slide'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

			$data['imagen'] = $upload_data['file_name'];
	        $data['titulo'] = $data['imagen'];
	        $data['id_tipo_contenido'] = 2;
	        $data['contenido1'] = $this->input->post('contenido1');
	        $data['estado'] = $this->input->post('estado');
			$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$data['user_alta'] = $this->usuario->id;

			$insert = $this->db->insert('con_media', $data);

			//Traigo el id ingresado
			$insert_id = $this->db->insert_id();
			
			//Relaciono contenido
			$relacionar['id_contenido'] = $this->input->post('id');
			$relacionar['id_media'] = $insert_id;
			
			$res = $this->db->insert('con_rel_contenidos_media', $relacionar);
	    }
	    return($res);
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
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";

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
}