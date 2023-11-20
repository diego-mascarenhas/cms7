<?php defined('BASEPATH') or exit('No direct script access allowed');


class Tarea_model extends CI_Model {
	
	public function getTareas($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS tareas.id, tareas.titulo, tareas.descripcion, UNIX_TIMESTAMP(tareas.desde) AS desde, UNIX_TIMESTAMP(tareas.hasta) AS hasta, UNIX_TIMESTAMP(tareas.final) AS final, tareas.estado AS id_estado, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, tareas.id_proyecto, tareas.horas_designadas, tareas.horas_utilizadas, tareas.porcentaje_realizado,
				
					CASE
						WHEN tareas.estado = 1 OR tareas.estado = 2 THEN
							CASE
								WHEN tareas.hasta = CURDATE() THEN 'warning'
								WHEN tareas.hasta < CURDATE() THEN 'danger'
								WHEN (tareas.hasta > CURDATE() OR tareas.hasta IS NULL) THEN 'info'
							END
						
						WHEN tareas.estado = 3 THEN 'success'
					END AS estado_ui_class,
					
					CASE
						WHEN tareas.estado = 1 THEN 'Pendiente'
						WHEN tareas.estado = 2 THEN 'En curso'
						WHEN tareas.estado = 3 THEN 'Finalizada'
					END AS estado
					
				FROM tareas
				LEFT JOIN contactos ON tareas.id_contacto = contactos.id
				
				WHERE tareas.grupo = ?
				AND tareas.id_empresa = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND tareas.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id_empresa;
			
			$sql .= " AND tareas.id_contacto = ?"; $placeholders[] = $this->usuario->id;
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
				$sql .= " AND tareas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND tareas.estado > 0";
			}
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND tareas.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
			
			if (!empty($parametros['id_proyecto']))
			{
				$sql .= " AND tareas.id_proyecto = ?";
				$placeholders[] = $parametros['id_proyecto'];
			}
			
			if (!empty($parametros['dashboard']))
			{
				$sql .= " AND (tareas.desde = CURDATE() OR (tareas.desde <= CURDATE() AND tareas.estado = 1))";
			}
			
			if (!empty($parametros['sidebar']))
			{
				$sql .= " AND tareas.estado != 3";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (tareas.titulo REGEXP '" . $value . "'";
				$sql .= " OR tareas.descripcion REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (tareas.titulo LIKE '%" . $value . "%'";
				$sql .= " OR tareas.descripcion LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " FIELD(tareas.estado,1,2) ASC, -tareas.hasta DESC, tareas.hasta";
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
	
	
	public function getTareaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT tareas.id, tareas.titulo, tareas.descripcion, UNIX_TIMESTAMP(tareas.desde) AS desde, UNIX_TIMESTAMP(tareas.hasta) AS hasta, UNIX_TIMESTAMP(tareas.final) AS final, tareas.estado, tareas.id_contacto, tareas.id_proyecto, tareas.horas_designadas, tareas.horas_utilizadas, tareas.porcentaje_realizado
		
					FROM tareas
				";
		}
		else
		{
			$sql = "
					SELECT tareas.id, tareas.titulo, tareas.descripcion, UNIX_TIMESTAMP(tareas.desde) AS desde, UNIX_TIMESTAMP(tareas.hasta) AS hasta, UNIX_TIMESTAMP(tareas.final) AS final, tareas.estado AS id_estado, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, tareas.id_contacto, tareas.id_proyecto, tareas.horas_designadas, tareas.horas_utilizadas, tareas.porcentaje_realizado
						
					FROM tareas
					LEFT JOIN contactos ON tareas.id_contacto = contactos.id
				";
		}
		
		$sql .= "
				WHERE tareas.estado > 0
				AND tareas.grupo = ?
				AND tareas.id = ?
			";
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			if (isset($parametros['id_contacto']))
			{
				$sql .= " AND tareas.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			$sql .= " AND tareas.id_contacto = ?";
			$placeholders[] = $this->usuario->id;
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
	
	
	public function getTareaDetalleRaw($id)
	{
		return $this->getTareaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function verificarSiExiste($titulo, $id_contacto)
	{
		$sql = "
				SELECT true
				
				FROM tareas
				
				WHERE titulo = ?
				AND id_contacto = ?
				AND desde = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($titulo, $id_contacto, date('Y-m-d')));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	public function ingresarTarea($valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa;
		$data['id_contacto'] = $valores['id_contacto'];
		
		$data['titulo'] = $valores['titulo'];
		
		if (!empty($valores['descripcion']))
		{
			$data['descripcion'] = stripslashes(trim($valores['descripcion']));
		}
		else
		{
			$data['descripcion'] = null;
		}
		
		if (!empty($valores['desde']))
		{
			if (strtotime($valores['desde']) < strtotime('today UTC'))
			{
			    $data['desde'] = date('Y-m-d', strtotime('today UTC'));
			}
			else
			{
				$data['desde'] = date('Y-m-d', strtotime($valores['desde']));

			}
		}
		else
		{
			$data['desde'] = date('Y-m-d');
		}
		
		if (isset($valores['hasta']))
		{
			if (!empty($valores['hasta']))
			{
				if (strtotime($valores['hasta']) < strtotime('today UTC'))
				{
				    $data['hasta'] = date('Y-m-d', strtotime('today UTC'));
				}
				else
				{
					$data['hasta'] = date('Y-m-d', strtotime($valores['hasta']));
	
				}
			}
			else
			{
				$data['hasta'] = null;
			}
		}
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		if (isset($valores['id_proyecto'])) $data['id_proyecto'] = (!empty($valores['id_proyecto'])) ? $valores['id_proyecto'] : null;
		if (isset($valores['horas_designadas'])) $data['horas_designadas'] = (!empty($valores['horas_designadas'])) ? $valores['horas_designadas'] : null;
		if (isset($valores['horas_utilizadas'])) $data['horas_utilizadas'] = (!empty($valores['horas_utilizadas'])) ? $valores['horas_utilizadas'] : null;
		if (isset($valores['porcentaje_realizado'])) $data['porcentaje_realizado'] = (!empty($valores['porcentaje_realizado'])) ? $valores['porcentaje_realizado'] : null;
		
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = (!empty($valores['username_alta'])) ? $valores['username_alta'] : $this->usuario->username;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tareas', $data);

			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarTarea($id, $valores)
	{
		if (isset($valores['id_contacto'])) $data['id_contacto'] = (!empty($valores['id_contacto'])) ? $valores['id_contacto'] : null;
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		
		if (isset($valores['descripcion']))
		{
			if (!empty($valores['descripcion']))
			{
				$data['descripcion'] = stripslashes(trim($valores['descripcion']));
			}
			else
			{
				$data['descripcion'] = null;
			}
		}
		
		
		if (!empty($valores['desde']))
		{
			if (strtotime($valores['desde']) < strtotime('today UTC'))
			{
			    $data['desde'] = date('Y-m-d', strtotime('today UTC'));
			}
			else
			{
				$data['desde'] = date('Y-m-d', strtotime($valores['desde']));

			}
		}
		else
		{
			$data['desde'] = date('Y-m-d');
		}
		
		if (isset($valores['hasta']))
		{
			if (!empty($valores['hasta']))
			{
				if (strtotime($valores['hasta']) < strtotime('today UTC'))
				{
				    $data['hasta'] = date('Y-m-d', strtotime('today UTC'));
				}
				else
				{
					$data['hasta'] = date('Y-m-d', strtotime($valores['hasta']));
	
				}
			}
			else
			{
				$data['hasta'] = null;
			}
		}
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		if (isset($valores['id_proyecto'])) $data['id_proyecto'] = (!empty($valores['id_proyecto'])) ? $valores['id_proyecto'] : null;
		if (isset($valores['horas_designadas'])) $data['horas_designadas'] = (!empty($valores['horas_designadas'])) ? $valores['horas_designadas'] : null;
		if (isset($valores['horas_utilizadas'])) $data['horas_utilizadas'] = (!empty($valores['horas_utilizadas'])) ? $valores['horas_utilizadas'] : null;
		if (isset($valores['porcentaje_realizado'])) $data['porcentaje_realizado'] = (!empty($valores['porcentaje_realizado'])) ? $valores['porcentaje_realizado'] : null;
		
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;
		

		if (!isset($res['error']))
		{
			$res = $this->db->update('tareas', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa, 'grupo'=>$this->usuario->grupo));
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getEstado($id)
	{
		$sql = "SELECT estado FROM tareas WHERE id = ?";
		
		$placeholders[] = $id;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['estado'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function cambiarEstado($id)
	{
		$data['estado'] = $this->getEstado($id);
		
		if ($data['estado'] == 2)
		{
			$data['estado'] = 1;
			$data['final'] = null;
		}
		else
		{
			$data['estado'] = 2;
			$data['final'] = date('Y-m-d', strtotime('today UTC'));
		}
		
		$res = $this->db->update('tareas', $data, array('id'=> $id)); // CORREGIR PERMISOS

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeTareas($estado)
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM tareas WHERE estado = ? AND id_contacto = ?";
		
		$placeholders[] = $estado;
		$placeholders[] = $this->usuario->id;
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeTareasVencidas()
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM tareas WHERE estado = 1 AND id_contacto = ? AND tareas.hasta < CURDATE()";
		
		$placeholders[] = $this->usuario->id;
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ordenar($tipo, $items)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			switch ($tipo)
			{
				case 'todo':
					$data['estado'] = 1;
					$data['final'] = null;
					break;
				case 'inprogress':
					$data['estado'] = 2;
					$data['final'] = null;
					break;
				case 'completed':
					$data['estado'] = 3;
					$data['final'] = date('Y-m-d', strtotime('today UTC'));
					break;
				default:
					$data['estado'] = 1;
					break;
			}
			
			$data['orden'] = $i;
			
			$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$data['username_modificacion'] = $this->usuario->username;
		    
		    $this->db->update('tareas', $data, array('id'=>$items[$i], 'id_empresa'=>$this->usuario->id_empresa, 'grupo'=>$this->usuario->grupo));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarTareas($id_contacto, $estado)
	{
		$sql = "
				SELECT tareas.id, tareas.titulo, tareas.descripcion, UNIX_TIMESTAMP(tareas.desde) AS desde, UNIX_TIMESTAMP(tareas.hasta) AS hasta, UNIX_TIMESTAMP(tareas.final) AS final, tareas.estado AS id_estado, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, tareas.id_proyecto, tareas.horas_designadas, tareas.horas_utilizadas, tareas.porcentaje_realizado,
				
					CASE
						WHEN tareas.estado = 1 OR tareas.estado = 2 THEN
							CASE
								WHEN tareas.hasta = CURDATE() THEN 'warning'
								WHEN tareas.hasta < CURDATE() THEN 'danger'
								WHEN (tareas.hasta > CURDATE() OR tareas.hasta IS NULL) THEN 'info'
							END
						
						WHEN tareas.estado = 3 THEN 'success'
					END AS estado_ui_class,
					
					CASE
						WHEN tareas.estado = 1 THEN 'Pendiente'
						WHEN tareas.estado = 2 THEN 'En curso'
						WHEN tareas.estado = 3 THEN 'Finalizada'
					END AS estado
					
				FROM tareas
				LEFT JOIN contactos ON tareas.id_contacto = contactos.id
				
				WHERE tareas.id_contacto = ?
				AND tareas.estado = ?
				
				ORDER BY tareas.orden ASC;
			";
						
		
		// filtros
		$placeholders[] = $id_contacto;
		$placeholders[] = $estado;
			
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
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}