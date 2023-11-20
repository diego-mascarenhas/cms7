<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cms_model extends CI_Model {

	public function getContenidos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS contenido.*, contenido.titulo, contenido.subtitulo, contenido.fecha_alta, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, categorias.categoria,
				
					CASE
						WHEN contenido.estado = 1 THEN 'Borrador'
						WHEN contenido.estado = 2 THEN 'Publicado'
						WHEN contenido.estado = 3 THEN 'Esperando autorización'
					END AS estado,
					
					CASE
						WHEN contenido.estado = 1 THEN 'label-info'
						WHEN contenido.estado = 2 THEN 'label-primary'
						WHEN contenido.estado = 3 THEN 'label-warning'
					END AS estado_ui_class
				
				FROM contenido
				LEFT JOIN contactos ON contenido.username_alta = contactos.id
				LEFT JOIN categorias ON contenido.categoria = categorias.id
		
				WHERE contenido.grupo = ?
				AND contenido.id_empresa = ?
			";
		
		
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
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND contenido.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND contenido.estado > 0";
			}
			
			if (!empty($parametros['categoria']))
			{
				$sql .= " AND contenido.categoria = ?";
				$placeholders[] = $parametros['categoria'];
			}
			
			if (!empty($parametros['campo1']))
			{
				$value = str_replace(',', '|', trim($parametros['campo1']));
				$sql .= " AND contenido.campo1 REGEXP '" . $value . "'";
				
			}
			
			if (!empty($parametros['campo2']))
			{
				$value = str_replace(',', '|', trim($parametros['campo2']));
				$sql .= " AND contenido.campo2 REGEXP '" . $value . "'";
				
			}
			
			if (!empty($parametros['campo9']))
			{
				$sql .= " AND contenido.campo9 = ?";
				$placeholders[] = $parametros['campo9'];
			}
			
			if (!empty($parametros['data2']))
			{
				$sql .= " AND contenido.data2 LIKE '%" . $parametros['data2'] . "%'";
				
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (contenido.titulo REGEXP '" . $value . "'";
				$sql .= " OR contenido.subtitulo REGEXP '" . $value . "'";
				$sql .= " OR contenido.descripcion REGEXP '" . $value . "'";
				$sql .= " OR contenido.vinculo1 REGEXP '" . $value . "'";
				$sql .= " OR contenido.vinculo2 REGEXP '" . $value . "') ";
			}
			
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (contenido.titulo LIKE '%" . $value . "%'";
				$sql .= " OR contenido.subtitulo LIKE '%" . $value . "%'";
				$sql .= " OR contenido.descripcion LIKE '%" . $value . "%'";
				$sql .= " OR contenido.vinculo1 LIKE '%" . $value . "%'";
				$sql .= " OR contenido.vinculo2 LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " titulo";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// limite
			if ($this->load->is_loaded('pagination'))
			{
				$sql .= " LIMIT ?, ?";
				$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
				$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
			}
			
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
				SELECT *
				FROM contenido
			";
		}
		else
		{
			$sql = "	
					SELECT contenido.id, contenido.titulo, contenido.subtitulo, contenido.descripcion, contenido.vinculo1,
				
					CASE
						WHEN contenido.estado = 1 THEN 'Borrador'
						WHEN contenido.estado = 2 THEN 'Publicado'
						WHEN contenido.estado = 3 THEN 'Esperando autorización'
					END AS estado,
					
					CASE
						WHEN contenido.estado = 1 THEN 'label-info'
						WHEN contenido.estado = 2 THEN 'label-primary'
						WHEN contenido.estado = 3 THEN 'label-warning'
					END AS estado_ui_class
					
					FROM contenido
				";
		}
		
		$sql .= " 
				WHERE contenido.grupo = ?
				AND contenido.id_empresa = ?
				AND contenido.estado > 0
				AND contenido.id = ?
			";
		
		
		// permisos
		if ($this->usuario->perfil == 'reseller' || $this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $id;
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
	
	
	public function ingresarContenido($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		
		$data['categoria'] = $valores['categoria'];

		if (!empty($valores['titulo']))
		{
			$data['titulo'] = $valores['titulo'];
		}
		else
		{
			$res['error'] = 'Debe especificar un título';
		}
		
		if (isset($valores['subtitulo'])) $data['subtitulo'] = (!empty($valores['subtitulo'])) ? $valores['subtitulo'] : null;
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? $valores['descripcion'] : null;

		if (isset($valores['campo1'])) $data['campo1'] = (!empty($valores['campo1'])) ? $valores['campo1'] : null;
		if (isset($valores['campo2'])) $data['campo2'] = (!empty($valores['campo2'])) ? $valores['campo2'] : null;
		if (isset($valores['campo3'])) $data['campo3'] = (!empty($valores['campo3'])) ? $valores['campo3'] : null;
		if (isset($valores['campo4'])) $data['campo4'] = (!empty($valores['campo4'])) ? $valores['campo4'] : null;
		if (isset($valores['campo5'])) $data['campo5'] = (!empty($valores['campo5'])) ? $valores['campo5'] : null;
		if (isset($valores['campo6'])) $data['campo6'] = (!empty($valores['campo6'])) ? $valores['campo6'] : null;
		if (isset($valores['campo7'])) $data['campo7'] = (!empty($valores['campo7'])) ? $valores['campo7'] : null;
		if (isset($valores['campo8'])) $data['campo8'] = (!empty($valores['campo8'])) ? $valores['campo8'] : null;
		if (isset($valores['campo9'])) $data['campo9'] = (!empty($valores['campo9'])) ? $valores['campo9'] : null;
		if (isset($valores['campo10'])) $data['campo10'] = (!empty($valores['campo10'])) ? $valores['campo10'] : null;
		
		if (isset($valores['vinculo1'])) $data['vinculo1'] = (!empty($valores['vinculo1'])) ? $valores['vinculo1'] : null;
		if (isset($valores['vinculo2'])) $data['vinculo2'] = (!empty($valores['vinculo2'])) ? $valores['vinculo2'] : null;
		if (isset($valores['vinculo3'])) $data['vinculo3'] = (!empty($valores['vinculo3'])) ? $valores['vinculo3'] : null;
		if (isset($valores['vinculo4'])) $data['vinculo4'] = (!empty($valores['vinculo4'])) ? $valores['vinculo4'] : null;
		if (isset($valores['vinculo5'])) $data['vinculo5'] = (!empty($valores['vinculo5'])) ? $valores['vinculo5'] : null;
		
		if (isset($valores['media_proyecto1'])) $data['media_proyecto1'] = (!empty($valores['media_proyecto1'])) ? $valores['media_proyecto1'] : null;
		if (isset($valores['media_proyecto2'])) $data['media_proyecto2'] = (!empty($valores['media_proyecto2'])) ? $valores['media_proyecto2'] : null;
		
		if (isset($valores['data1'])) $data['data1'] = (!empty($valores['data1'])) ? $valores['data1'] : null;
		if (isset($valores['data2'])) $data['data2'] = (!empty($valores['data2'])) ? $valores['data2'] : null;
		if (isset($valores['data3'])) $data['data3'] = (!empty($valores['data3'])) ? $valores['data3'] : null;
		if (isset($valores['data4'])) $data['data4'] = (!empty($valores['data4'])) ? $valores['data4'] : null;
		if (isset($valores['data5'])) $data['data5'] = (!empty($valores['data5'])) ? $valores['data5'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
				
		$data['fecha_alta'] = (!empty($valores['fecha_alta'])) ? $valores['fecha_alta'] : now();
		$data['username_alta'] = $this->usuario->id;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('contenido', $data);

			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarContenido($id, $valores)
	{
		if (isset($valores['titulo']))
		{
			if (!empty($valores['titulo']))
			{
				$data['titulo'] = $valores['titulo'];
			}
			else
			{
				$res['error'] = 'Debe especificar un título';
			}
		}
		
		if (isset($valores['subtitulo'])) $data['subtitulo'] = (!empty($valores['subtitulo'])) ? $valores['subtitulo'] : null;
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? $valores['descripcion'] : null;

		if (isset($valores['campo1'])) $data['campo1'] = (!empty($valores['campo1'])) ? $valores['campo1'] : null;
		if (isset($valores['campo2'])) $data['campo2'] = (!empty($valores['campo2'])) ? $valores['campo2'] : null;
		if (isset($valores['campo3'])) $data['campo3'] = (!empty($valores['campo3'])) ? $valores['campo3'] : null;
		if (isset($valores['campo4'])) $data['campo4'] = (!empty($valores['campo4'])) ? $valores['campo4'] : null;
		if (isset($valores['campo5'])) $data['campo5'] = (!empty($valores['campo5'])) ? $valores['campo5'] : null;
		if (isset($valores['campo6'])) $data['campo6'] = (!empty($valores['campo6'])) ? $valores['campo6'] : null;
		if (isset($valores['campo7'])) $data['campo7'] = (!empty($valores['campo7'])) ? $valores['campo7'] : null;
		if (isset($valores['campo8'])) $data['campo8'] = (!empty($valores['campo8'])) ? $valores['campo8'] : null;
		if (isset($valores['campo9'])) $data['campo9'] = (!empty($valores['campo9'])) ? $valores['campo9'] : null;
		if (isset($valores['campo10'])) $data['campo10'] = (!empty($valores['campo10'])) ? $valores['campo10'] : null;
		
		if (isset($valores['vinculo1'])) $data['vinculo1'] = (!empty($valores['vinculo1'])) ? $valores['vinculo1'] : null;
		if (isset($valores['vinculo2'])) $data['vinculo2'] = (!empty($valores['vinculo2'])) ? $valores['vinculo2'] : null;
		if (isset($valores['vinculo3'])) $data['vinculo3'] = (!empty($valores['vinculo3'])) ? $valores['vinculo3'] : null;
		if (isset($valores['vinculo4'])) $data['vinculo4'] = (!empty($valores['vinculo4'])) ? $valores['vinculo4'] : null;
		if (isset($valores['vinculo5'])) $data['vinculo5'] = (!empty($valores['vinculo5'])) ? $valores['vinculo5'] : null;
		
		if (isset($valores['media_proyecto1'])) $data['media_proyecto1'] = (!empty($valores['media_proyecto1'])) ? $valores['media_proyecto1'] : null;
		if (isset($valores['media_proyecto2'])) $data['media_proyecto2'] = (!empty($valores['media_proyecto2'])) ? $valores['media_proyecto2'] : null;
		
		if (isset($valores['data1'])) $data['data1'] = (!empty($valores['data1'])) ? $valores['data1'] : null;
		if (isset($valores['data2'])) $data['data2'] = (!empty($valores['data2'])) ? $valores['data2'] : null;
		if (isset($valores['data3'])) $data['data3'] = (!empty($valores['data3'])) ? $valores['data3'] : null;
		if (isset($valores['data4'])) $data['data4'] = (!empty($valores['data4'])) ? $valores['data4'] : null;
		if (isset($valores['data5'])) $data['data5'] = (!empty($valores['data5'])) ? $valores['data5'] : null;
		
		if (isset($valores['imagen1'])) $data['imagen1'] = $valores['imagen1'];
		if (isset($valores['imagen2'])) $data['imagen2'] = $valores['imagen2'];
		if (isset($valores['imagen3'])) $data['imagen3'] = $valores['imagen3'];
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

				
		if (!isset($res['error']))
		{
			$res = $this->db->update('contenido', $data, array('id'=> $id));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function menu($padre = null, $seleccionada = false, $niveles = 10, $nivel = null)
	{
		$sql = "SELECT categorias.id, categorias.categoria";
		$sql .= " FROM categorias";
		$sql .= " WHERE categorias.grupo = ?";
		$sql .= " AND categorias.id_empresa = ?";
		$sql .= (isset($padre)) ? " AND padre = $padre" : " AND padre IS NULL";
		$sql .= " ORDER BY orden ASC";
		
		// consulta
		$query = $this->db->query($sql, array($this->usuario->grupo, $this->usuario->id_empresa));
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				
				$res[] = array(
								'id'=>$row['id'],
								'item'=>$row['categoria'],
								'uri'=>'cms/contenidos/categoria/' . $row['id'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->menu($row['id'], $seleccionada, $niveles, $nivel)
								);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function categoriasCombo($padre = null, $categoria_actual = null, $publicar = null, $nivel = null)
	{
		$combo = null;
		
		if (!isset($padre))
			{
			$sql = "
					SELECT id, categoria, padre
					
					FROM categorias
					
					WHERE grupo = ?
					AND id_empresa = ?
					AND padre IS NULL
					
					ORDER BY orden, categoria ASC
				";
			
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			
			$query = $this->db->query($sql, $placeholders);
		
		
			if ($query)
			{
				$res = $query->result_array();
			}
			
			if ($query->num_rows())
			{
				foreach ($res as $row)
				{
					$combo .= '<optgroup>';
					$combo .= "\r\n";
					$selected = ($categoria_actual == $row['id']) ? ' selected="selected"' : '';
					$combo .= '<option value="' . $row['id'] . '"' . $selected . '>' .  strtoupper($row['categoria']) . '</option>';
					$combo .= "\r\n";
					$combo .= $this->categoriasCombo($row['id'], $categoria_actual, $publicar);
					$combo .= '</optgroup>';
					$combo .= "\r\n";
				}
			}	
		}
		
		else
		{
			$sql = "
					SELECT id, categoria, padre
					
					FROM categorias
					
					WHERE padre = ?
					AND id_empresa = ?
					
					ORDER BY padre, orden, categoria ASC
				";
			
			$placeholders[] = $padre;
			$placeholders[] = $this->usuario->id_empresa;
			
			$query = $this->db->query($sql, $placeholders);
		
		
			if ($query)
			{
				$res = $query->result_array();
			}
			
			$nivel += 1;
			
			if ($query->num_rows())
			{    
				foreach ($res as $row)
				{
					$separador = null;
					
					for ($i = 1; $i <= $nivel; $i++) { $separador .= '&nbsp;&nbsp;'; }
						
					$selected = ($categoria_actual == $row['id']) ? ' selected="selected"' : '';
					
					$combo .= '<option value="' . $row['id'] . '"' . $selected . '>' . $separador . '- ' .  $row['categoria'] . '</option>';
					$combo .= "\r\n";
			        
					if (!empty($row['padre'])) $combo .= $this->categoriasCombo($row['id'], $categoria_actual, $publicar, $nivel); 
				}   
			}
		}
			
		return (!empty($combo)) ? $combo : null;
	}
	
	
	public function getCategorias($parametros = null)
	{
		$sql = "	
				SELECT categorias.id, categorias.categoria
					
				FROM categorias
				
				WHERE grupo = ?
				AND id_empresa = ?
			";
		
		
		// permisos
		if ($this->usuario->perfil == 'reseller' || $this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
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
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCategoriaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *
				FROM categorias
			";
		}
		else
		{
			$sql = "	
					SELECT categorias.id, categorias.categoria
					
					FROM categorias
				";
		}
		
		$sql .= " 
				WHERE categorias.grupo = ?
				AND categorias.id_empresa = ?
				AND categorias.id = ?
			";
		
		
		// permisos
		if ($this->usuario->perfil == 'reseller' || $this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $id;
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
		
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}