<?php defined('BASEPATH') or exit('No direct script access allowed');


class Mailbox_model extends CI_Model {
	
	public function getEmails($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS mailbox.id, mailbox.subject, mailbox.fromaddress, mailbox.enviado,
				
					CASE
						WHEN mailbox.estado = 1 THEN 'unread'
						WHEN mailbox.estado = 2 THEN 'read'
					END AS estado_ui_class,
					
					CASE
						WHEN mailbox.estado = 1 THEN 'Sin leer'
						WHEN mailbox.estado = 2 THEN 'Leído'
					END AS estado,
					
					CASE
						WHEN alertas_tipo.prioridad = 1 THEN 'danger'
						WHEN alertas_tipo.prioridad = 2 THEN 'warning'
						WHEN alertas_tipo.prioridad = 3 THEN 'success'
						WHEN alertas_tipo.prioridad = 4 THEN 'info'
						WHEN alertas_tipo.prioridad = 5 THEN 'plain'
					END AS prioridad_ui_class,
					
					CASE
						WHEN alertas_tipo.prioridad = 1 THEN 'Crítico'
						WHEN alertas_tipo.prioridad = 2 THEN 'Urgente'
						WHEN alertas_tipo.prioridad = 3 THEN 'Alta'
						WHEN alertas_tipo.prioridad = 4 THEN 'Normal'
						WHEN alertas_tipo.prioridad = 5 THEN 'Información'
					END AS prioridad
					
				FROM mailbox
				LEFT JOIN alertas_tipo ON mailbox.id_tipo = alertas_tipo.id
				
				WHERE 1
			";
						
						
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND mailbox.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND mailbox.estado > 0";
			}
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND mailbox.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
			
			if (!empty($parametros['prioridad']))
			{
				$sql .= " AND alertas_tipo.prioridad = ?";
				$placeholders[] = $parametros['prioridad'];
			}
			
			if (!empty($parametros['id_tipo']))
			{
				$sql .= " AND mailbox.id_tipo = ?";
				$placeholders[] = $parametros['id_tipo'];
			}
					
			if (!empty($parametros['filter']))
			{
				$sql .= " AND mailbox.subject LIKE '%" . $parametros['filter'] . "%'";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (mailbox.subject REGEXP '" . $value . "'";
				$sql .= " OR mailbox.fromaddress REGEXP '" . $value . "'";
				$sql .= " OR mailbox.toaddress REGEXP '" . $value . "'";
				$sql .= " OR mailbox.body REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (mailbox.subject LIKE '%" . $value . "%'";
				$sql .= " OR mailbox.fromaddress LIKE '%" . $value . "%'";
				$sql .= " OR mailbox.toaddress LIKE '%" . $value . "%'";
				$sql .= " OR mailbox.body LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " mailbox.enviado";
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
	
	
	public function getEmailDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT *
		
					FROM mailbox
				";
		}
		else
		{
			$sql = "
					SELECT mailbox.id, mailbox.subject, mailbox.fromaddress, mailbox.enviado, mailbox.body,
					
						CASE
							WHEN mailbox.estado = 1 THEN 'unread'
							WHEN mailbox.estado = 2 THEN 'read'
						END AS estado_ui_class,
						
						CASE
							WHEN mailbox.estado = 1 THEN 'Sin leer'
							WHEN mailbox.estado = 2 THEN 'Leído'
						END AS estado
						
					FROM mailbox
				";
		}
		
		$sql .= "
				WHERE mailbox.estado > 0
				AND mailbox.id = ?
			";
		

		// permisos	
		$placeholders[] = $id;
		
		
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
	
	
	public function getEmailDetalleRaw($id)
	{
		return $this->getEmailDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getEmailIdFromBlockedIp($ip)
	{	
		$this->db->select('id');
		$this->db->from('mailbox');
		$this->db->where('blocked_ip', $ip);

		return $this->db->get()->row('id');	
	}
	
	
	public function getEstado($id)
	{
		$sql = "SELECT estado FROM mailbox WHERE id = ?";
		
		$placeholders[] = $id;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['estado'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function marcarComoLeido($id)
	{
		$data['estado'] = $this->getEstado($id);
		
		if ($data['estado'] == 1)
		{
			$data['estado'] = 2;
			$data['recibido'] = now();
			$data['id_contacto'] = $this->usuario->id;
		}
		
		$res = $this->db->update('mailbox', $data, array('id'=> $id)); // CORREGIR PERMISOS

		return (!empty($res)) ? $res : null;
	}
	
	
	function eliminar($id)
	{
		$data['estado'] = $this->getEstado($id);
		
		$data['estado'] = $data['estado']*-1;
		
		$res = $this->db->update('mailbox', $data, array('id'=> $id)); // CORREGIR PERMISOS
		
		$data['recibido'] = now();
		$data['id_contacto'] = $this->usuario->id;
		$this->db->where('id_contacto IS NULL');
		$res = $this->db->update('mailbox', $data, array('id'=> $id)); // CORREGIR PERMISOS

		return (!empty($res)) ? $res : null;
	}
	
	
	public function eliminarEmailsAnteriores($intervalo = 172800, $prioridad = 3) // 48 Horas
	{
		$sql = "
				DELETE mailbox.*
				
				FROM mailbox
				LEFT JOIN alertas_tipo ON mailbox.id_tipo = alertas_tipo.id
				
				WHERE UNIX_TIMESTAMP(NOW())-mailbox.enviado > ?
				AND (alertas_tipo.prioridad >= ? OR mailbox.id_tipo IS NULL)
			";
		
		$res = $this->db->query($sql, array($intervalo, $prioridad));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeEmails($prioridad = null, $estado = null)
	{
		$sql = "SELECT COUNT(*) AS cantidad
		
				FROM mailbox
				LEFT JOIN alertas_tipo ON mailbox.id_tipo = alertas_tipo.id
				
				WHERE mailbox.estado > 0
			"; # AND grupo = ?
		
		$placeholders = null;
		
		if (isset($prioridad))
		{
			$sql .= " AND alertas_tipo.prioridad = ?";
			$placeholders[] = $prioridad;
		}
		
		if (isset($estado))
		{
			$sql .= " AND mailbox.estado = ?";
			$placeholders[] = $estado;
		}

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			//$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			//$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMaximaPrioridad()
	{
		$sql = "
				SELECT MIN(alertas_tipo.prioridad) AS prioridad

				FROM mailbox
				LEFT JOIN alertas_tipo ON mailbox.id_tipo = alertas_tipo.id
				
				WHERE mailbox.estado > 0
			";
		
		
		// consulta
		$query = $this->db->query($sql);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['prioridad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}