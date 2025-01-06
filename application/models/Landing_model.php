<?php defined('BASEPATH') or exit('No direct script access allowed');


class Landing_model extends CI_Model {

	public function getLandings($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS landings.id, landings.titulo, landings.codigo, landings.codigo_gracias, landings.url, landings.impresiones, landings.conversiones, landings.estado AS id_estado, landings.fecha_alta, empresas.id AS id_empresa, empresas.empresa,
				
					CASE
						WHEN landings.estado = 1 THEN 'label-plain'
						WHEN landings.estado = 2 THEN 'label-primary'
						WHEN landings.estado = 3 THEN 'label-info'
					END AS estado_ui_class,
					
					CASE
						WHEN landings.estado = 1 THEN 'Inactivo'
						WHEN landings.estado = 2 THEN 'Borrador'
						WHEN landings.estado = 3 THEN 'Activo'
					END AS estado
				
				FROM landings
				LEFT JOIN empresas ON landings.id_empresa = empresas.id
		
				WHERE landings.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND landings.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND landings.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND landings.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND landings.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (landings.titulo REGEXP '" . $value . "'";
				$sql .= " OR landings.url REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (landings.titulo LIKE '%" . $value . "%'";
				$sql .= " OR landings.url LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " titulo";
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
	
	
	public function getLandingDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *
				FROM landings
			";
		}
		else
		{
			$sql = "	
					SELECT landings.id, landings.id_contacto, landings.titulo, landings.codigo, landings.codigo_gracias, landings.url, landings.estado AS id_estado, landings.fecha_alta, empresas.id AS id_empresa, empresas.empresa,
				
						CASE
							WHEN landings.estado = 1 THEN 'label-plain'
							WHEN landings.estado = 2 THEN 'label-primary'
							WHEN landings.estado = 3 THEN 'label-info'
						END AS estado_ui_class,
						
						CASE
							WHEN landings.estado = 1 THEN 'Inactivo'
							WHEN landings.estado = 2 THEN 'Borrador'
							WHEN landings.estado = 3 THEN 'Activo'
						END AS estado
					
					FROM landings
					LEFT JOIN empresas ON landings.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE landings.grupo = ?
				AND landings.estado > 0
				AND landings.id = ?		
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
			$sql .= " AND landings.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND landings.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getLandingDetalleRaw($id)
	{
		return $this->getLandingDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarLanding($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa;
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (!empty($valores['titulo']))
		{
			$data['titulo'] = $valores['titulo'];
		}
		else
		{
			$res['error'] = 'Debe especificar un título';
		}
		
		if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;
		if (isset($valores['codigo_gracias'])) $data['codigo_gracias'] = (!empty($valores['codigo_gracias'])) ? $valores['codigo_gracias'] : null;
		if (isset($valores['url'])) $data['url'] = (!empty($valores['url'])) ? $valores['url'] : null;

		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('landings', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarLanding($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (!empty($valores['titulo'])) $data['titulo'] = $valores['titulo'];
		if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;
		if (isset($valores['codigo_gracias'])) $data['codigo_gracias'] = (!empty($valores['codigo_gracias'])) ? $valores['codigo_gracias'] : null;
		if (isset($valores['url'])) $data['url'] = (!empty($valores['url'])) ? $valores['url'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
				
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('landings', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('landings', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getLandingConversiones($id)
	{
		$sql = "	
				SELECT FROM_UNIXTIME(landings_stats.fecha_alta) as fecha, landings_stats.* 
				
				FROM landings_stats
				LEFT JOIN landings ON landings_stats.id_landing = landings.id
				LEFT JOIN contactos ON landings_stats.id_contacto = contactos.id
		
				WHERE landings_stats.id_landing = ?
				AND landings.grupo = ?
			";
		
		$placeholders[] = $id;
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND landings.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND landings.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND landings_stats.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		if (!isset($res['error']))
		{
			// orden
			$sql .= " ORDER BY landings_stats.id DESC";

			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarConversion($id)
	{
		$sql = "
				SELECT landings_stats.*, landings.id_contacto, landings.titulo, empresas.empresa
				
				FROM landings_stats
				LEFT JOIN landings ON landings_stats.id_landing = landings.id
				LEFT JOIN empresas ON landings.id_empresa = empresas.id
		
				WHERE landings_stats.id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}
	
	
	// Site Landing
	public function getLandingDetallePublico($id)
	{
		$sql = " 	
				SELECT *
				FROM landings
			
				WHERE estado = 3
				AND landings.id = ?		
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getLandingIdFromUrl($url)
	{	
		$sql = "SELECT id FROM landings WHERE url = ?";
		
		$query = $this->db->query($sql, array($url));
		
		$res = $query->row_array()['id'];
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function incrementar($id, $tipo='impresiones')
	{
		$this->db->where('id', $id);
		$this->db->set($tipo, $tipo . '+1', false);

		$res = $this->db->update('landings');
		
		return (!empty($res)) ? true : false;
	}
	
	
	public function track($id, $valores, $id_contacto=null)
	{
		$data['id_landing'] = $id;
		if (isset($id_contacto)) $data['id_contacto'] = $id_contacto;
		if (isset($valores['id_contacto'])) $data['id_contacto'] = $valores['id_contacto'];
		$data['data'] = json_encode($valores);
		
		$data['fecha_alta'] = now();
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('landings_stats', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	function provincias($id_pais)
	{
		$sql = "SELECT sys_provincias.id, sys_provincias.provincia AS descripcion
				FROM sys_provincias
				WHERE sys_provincias.id_pais = ?
				AND estado = 2
				ORDER BY provincia ASC
			";

		$query = $this->db->query($sql, $id_pais);
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
}

