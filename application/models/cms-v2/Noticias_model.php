<?php defined('BASEPATH') or exit('No direct script access allowed');

class Noticias_model extends CI_Model {

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
		$sql .= " AND con_secciones.id_secciones_tipo = 5";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " ORDER BY con_contenidos.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	/* 	Fin Usado por API también  */

	public function listadoCategoriasItems($filtro)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.url, con_contenidos.imagen, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_secciones.id_secciones_tipo = 5";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_secciones.id = ".$filtro;
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " ORDER BY con_contenidos.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function listadoPadres()
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion FROM con_secciones WHERE con_secciones.estado = 3 AND con_secciones.id_secciones_tipo = 5 ORDER BY con_secciones.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();

/* 		$padre[] = '--- Seleccione una opción ---'; */

		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['seccion'];
		}
		return ($padre);
	}

	public function detalleContenido($id)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.destacado_slide, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id = $id";

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

	/* API */
	public function detalleContenidoUrl($url)
	{		
		$sql = "SELECT con_contenidos.id, con_contenidos.imagen, con_contenidos.miniatura, con_contenidos.imagen_adicional, con_contenidos.puntaje, con_contenido_items.id as id_item, con_contenido_items.url, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.precio, con_contenido_items.precioUsd, con_contenido_items.descuento, con_contenido_items.seo_titulo, con_contenido_items.seo_keywords, con_contenido_items.seo_descripcion";
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
/* 		$sql .= " LEFT JOIN con_contenidos ON con_media.id_contenido = con_contenidos.id"; */
/* 		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_contenido = con_contenidos.id"; */
		$sql .= " LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = con_media.id";
		$sql .= " WHERE con_media.estado > 0";
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

	public function ordenarNoticias($items)
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

	public function ordenarCategorias($items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$data['user_modificacion'] = $this->usuario->id;
		    
		    $this->db->update('con_secciones', $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function ingresarContenido($id)
	{
		$this->load->helper('date');

		if (empty($this->input->post('id')))
		{
			$datos['id_tipo'] = 5;
			$datos['id_con_secciones'] = $this->input->post('seccion');
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
			$datos['id_tipo'] = 5;
			$datos['id_con_secciones'] = $this->input->post('seccion');
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
		}
		return ($res);
	}

	/* Categorias */
	public function listadoCategorias($estado)
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion, con_secciones.fecha_alta, con_secciones.estado as id_estado, con_estados.estado";
		$sql .= " FROM con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_secciones.estado";
		if($estado == 3)
		{
			$sql .= " WHERE con_secciones.estado = 3";
		}
		else
		{
			$sql .= " WHERE con_secciones.estado > 0";
		}
		$sql .= " AND con_secciones.id_secciones_tipo = 5";
		$sql .= " AND con_secciones.idioma = 'es'";
		$sql .= " ORDER BY con_secciones.orden ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleCategoria($id)
	{
		$sql = "SELECT con_secciones.*";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarCategoria()
	{
		$this->load->helper('date');

		if (empty($this->input->post('id')))
		{
			$datos['id_secciones_tipo'] = 5;
			$datos['idioma'] = 'es';
			$datos['seccion'] = $this->input->post('seccion');
			$datos['orden'] = $this->input->post('orden');
			$datos['seo_titulo'] = $this->input->post('seo_titulo');
			$datos['seo_keywords'] = $this->input->post('seo_keywords');
			$datos['seo_descripcion'] = $this->input->post('seo_descripcion');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;
	
			$res = $this->db->insert('con_secciones', $datos);
		}
		else
		{
			$datos['id_secciones_tipo'] = 5;
			$datos['idioma'] = 'es';
			$datos['seccion'] = $this->input->post('seccion');
			$datos['orden'] = $this->input->post('orden');
			$datos['seo_titulo'] = $this->input->post('seo_titulo');
			$datos['seo_keywords'] = $this->input->post('seo_keywords');
			$datos['seo_descripcion'] = $this->input->post('seo_descripcion');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
	
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_secciones', $datos, $where);
		}
		return ($res);
	}

	public function duplicarCategoria($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_secciones.*";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id = $id";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_secciones_tipo'] = 5;
			$datos['idioma'] = $res['idioma'];
			$datos['seccion'] = $res['seccion'].' copy';
			$datos['descripcion'] = $res['descripcion'];
			$datos['orden'] = $res['orden'];
			$datos['seo_titulo'] = $res['seo_titulo'];
			$datos['seo_keywords'] = $res['seo_keywords'];
			$datos['seo_descripcion'] = $res['seo_descripcion'];
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_secciones', $datos);
		}
		return ($res);
	}
	
	public function eliminarCategoria($id)
	{
		$this->load->helper('date');

		$datos['estado'] = '-3';
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_secciones', $datos, $where);
		}
		return ($res);
	}
	/* Fin Categorias */
	
	
	public function duplicarItem($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_contenidos.*";
		$sql .= " FROM con_contenidos";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_contenidos.id = $id";
		$sql .= " AND con_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_tipo'] = 5;
			$datos['id_con_secciones'] = $res['id_con_secciones'];
			$datos['orden'] = $res['orden'];
			$datos['miniatura'] = $res['miniatura'];
			$datos['imagen'] = $res['imagen'];
			$datos['detalle'] = $res['detalle'];
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
			}

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