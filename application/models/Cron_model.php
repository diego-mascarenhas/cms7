<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cron_model extends CI_Model {
	
	public function getCrons($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS sys_crons.id, sys_crons.tipo, sys_crons.url, sys_crons.intervalo, sys_crons.fecha_modificacion,
					
					CASE
					   WHEN sys_crons.estado = 1 THEN 'inactivo'
					   WHEN sys_crons.estado = 2 THEN 'activo'
					   WHEN sys_crons.estado = 3 THEN 'ejecutandose'
					END AS estado,
					
					CASE
					   WHEN sys_crons.estado = 1 THEN 'label-plain'
					   WHEN sys_crons.estado = 2 THEN 'label-primary'
					   WHEN sys_crons.estado = 3 THEN 'label-warning'
					END AS estado_ui_class
					
				FROM sys_crons
				
				WHERE 1
			";
						
						
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND sys_crons.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND sys_crons.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (sys_crons.tipo REGEXP '" . $value . "'";
				$sql .= " OR sys_crons.url REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (sys_crons.tipo LIKE '%" . $value . "%'";
				$sql .= " OR sys_crons.url LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " sys_crons.estado DESC, sys_crons.fecha_modificacion";
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
	
	
	public function ejecutarCron($id)
	{
		$sql = "SELECT url, data FROM sys_crons WHERE id = ? AND estado = 2";
		
		$query = $this->db->query($sql, array($id));
		
		if ($query)
		{
			$row = $query->row_array();
			
			if ($row)
			{
				$this->db->update('sys_crons', array('estado'=>3, 'fecha_modificacion'=>now()), array('id'=>$id, 'estado'=>2));
				
				$this->load->library('curl');
				$res = $this->curl->simple_post($row['url'], json_decode($row['data']));
				
				$this->db->update('sys_crons', array('estado'=>2, 'fecha_modificacion'=>now()), array('id'=>$id, 'estado'=>3));
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ejecutarCrons()
	{
		$this->db->update('sys_crons', array('estado'=>3, 'fecha_modificacion'=>now()), array('id'=>1, 'estado'=>2));

		if ($this->db->affected_rows())
		{	
			$sql = "SELECT id, tipo, url FROM sys_crons WHERE estado = 2 AND fecha_modificacion+intervalo < UNIX_TIMESTAMP(NOW())";
			
			$query = $this->db->query($sql);
			
			if ($query)
			{
				$res = $query->result_array();
				
				foreach ($res as $obj)
				{
					$this->ejecutarCron($obj['id']);
				}
			}
			
			$this->db->update('sys_crons', array('estado'=>2), array('id'=>1, 'estado'=>3));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function destrabarCrons()
	{
		$sql = "UPDATE sys_crons SET estado = 2 WHERE estado = 3 AND fecha_modificacion+IFNULL(intervalo, 600) < UNIX_TIMESTAMP(NOW())";
		
		$res = $this->db->query($sql);
		
		return (!empty($res)) ? $res : null;
	}
		

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}