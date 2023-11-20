<?php defined('BASEPATH') or exit('No direct script access allowed');


class Configuracion_model extends CI_Model {

	public function getConfig($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS sys_config.id, sys_config.value, sys_config.id_tipo, sys_config_tipo.tipo, sys_config_tipo.key, empresas.id AS id_empresa, empresas.empresa
				
					FROM sys_config
					LEFT JOIN sys_config_tipo ON sys_config.id_tipo = sys_config_tipo.id
					LEFT JOIN empresas ON sys_config.id_empresa = empresas.id

				WHERE sys_config.grupo = ?
			";
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND sys_config.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		else
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND sys_config.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (sys_config_tipo.tipo REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "'";
				$sql .= " OR sys_config_tipo.value REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (sys_config_tipo.tipo LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%'";
				$sql .= " OR sys_config_tipo.value LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " sys_config.id";
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
	
	
	public function getConfigDetalle($id)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *
				FROM sys_config
				WHERE sys_config.grupo = ?
				AND sys_config.id = ?
			";
		}
		else
		{
			$sql = "
				SELECT sys_config.id, sys_config.value, sys_config.id_tipo, sys_config_tipo.tipo, empresas.id AS id_empresa, empresas.empresa
				
					FROM sys_config
					LEFT JOIN sys_config_tipo ON sys_config.id_tipo = sys_config_tipo.id
					LEFT JOIN empresas ON sys_config.id_empresa = empresas.id

				WHERE sys_config.grupo = ?
				AND sys_config.id = ?
			";
		}
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND sys_config.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		else		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			$sql .= " AND sys_config.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		
			
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getConfigDetalleRaw($id)
	{
		return $this->getConfigDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function verificarSiExiste($id_empresa, $id_tipo)
	{
		$sql = "
				SELECT id
				
				FROM sys_config
				
				WHERE grupo = ?
				AND id_empresa = ?
				AND id_tipo = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($this->usuario->grupo, $id_empresa, $id_tipo));
		
		return $query->row_array()['id'];
	}
	
	
	public function ingresarConfig($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : null; // VALIDAR QUE LA EMPRESA SEA DEL GRUPO
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (!empty($valores['id_tipo'])) $data['id_tipo'] = $valores['id_tipo'];
		if (isset($valores['value'])) $data['value'] = (!empty($valores['value'])) ? $valores['value'] : null;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('sys_config', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
		
	public function modificarConfig($id, $valores)
	{
		if (!empty($valores['id_tipo'])) $data['id_tipo'] = $valores['id_tipo'];
		if (isset($valores['value'])) $data['value'] = (!empty($valores['value'])) ? $valores['value'] : null;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('sys_config', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('sys_config', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCategorias($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS categorias_gestion.id, categorias_gestion.nombre_categoria AS categoria
				
				FROM categorias_gestion
		
				WHERE categorias_gestion.grupo = ?
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
				$sql .= " AND categorias_gestion.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND categorias_gestion.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (categorias_gestion.nombre_categoria REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (categorias_gestion.nombre_categoria LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " categoria";
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


	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}