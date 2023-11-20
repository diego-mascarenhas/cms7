<?php defined('BASEPATH') or exit('No direct script access allowed');


class Nota_model extends CI_Model {
	
	public function getNotas($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS notas.id, notas.titulo, notas.descripcion, notas.fecha_alta, contactos.username AS contacto, contactos.avatar, responsable.username, notas.id_referencia, sys_items.item, sys_items.uri
				
				FROM notas
				LEFT JOIN contactos ON notas.username_alta = contactos.id
				LEFT JOIN contactos AS responsable ON notas.responsable = contactos.id
				LEFT JOIN sys_items ON notas.id_tipo = sys_items.id
				
				WHERE notas.grupo = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
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
				$sql .= " AND notas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND notas.estado > 0";
			}
			
			if (!empty($parametros['id_tipo']))
			{
				$sql .= " AND notas.id_tipo = ?";
				$placeholders[] = $parametros['id_tipo'];
			}
			
			if (!empty($parametros['id_referencia']))
			{
				$sql .= " AND notas.id_referencia = ?";
				$placeholders[] = $parametros['id_referencia'];
			}
			
			if (!empty($parametros['responsable']))
			{
				$sql .= " AND notas.responsable = ?";
				$placeholders[] = $parametros['responsable'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (notas.titulo REGEXP '" . $value . "'";
				$sql .= " OR notas.descripcion REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (notas.titulo LIKE '%" . $value . "%'";
				$sql .= " OR notas.descripcion LIKE '%" . $value . "%') ";
			}
			
			// group
			$sql .= " GROUP BY notas.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " notas.id";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " DESC";
			
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
	
	
	public function getNotaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT *
		
					FROM notas
				";
		}
		else
		{
			$sql = "
					SELECT notas.id, notas.titulo, notas.descripcion, notas.fecha_alta, notas.responsable
						
					FROM notas
				";
		}
		
		$sql .= "
				WHERE notas.estado > 0
				AND notas.grupo = ?
				AND notas.id = ?
			";
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
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
	
	
	public function getNotaDetalleRaw($id)
	{
		return $this->getNotaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarNota($valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		
		if (!empty($valores['id_tipo'])) $data['id_tipo'] =  $valores['id_tipo'];
		if (!empty($valores['id_referencia'])) $data['id_referencia'] =  $valores['id_referencia'];
		if (!empty($valores['responsable'])) $data['responsable'] =  $valores['responsable'];
		
		$data['titulo'] = $valores['titulo'];
		$data['descripcion'] = $valores['descripcion'];
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('notas', $data);

			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarNota($id, $valores)
	{
		if (isset($valores['responsable'])) $data['responsable'] = (!empty($valores['responsable'])) ? $valores['responsable'] : null;
		
		if (isset($valores['titulo'])) $data['titulo'] = (!empty($valores['titulo'])) ? $valores['titulo'] : null;
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? $valores['descripcion'] : null;
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		

		if (!isset($res['error']))
		{
			$res = $this->db->update('notas', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}

		return (!empty($res)) ? $res : null;
	}
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}