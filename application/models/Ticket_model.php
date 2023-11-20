<?php defined('BASEPATH') or exit('No direct script access allowed');


class Ticket_model extends CI_Model {

	public function getTickets($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS DISTINCT(tickets.id), tickets.asunto, tickets.prioridad, tickets.prioridad AS id_prioridad, SEC_TO_TIME(tickets.inicio) as inicio, tickets.fecha_alta, tickets.username_alta, tickets_areas.area, tickets.id_area, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, empresas.empresa, tickets.id_empresa, tickets_estados.estado, tickets.estado AS id_estado, (SELECT COUNT(*)-1 FROM tickets_items WHERE tickets_items.id_ticket = tickets.id) AS respuestas, grupos.grupo,
					(SELECT COUNT(*)
					FROM tickets_items_adjuntos
					LEFT JOIN tickets_items AS adjuntos ON tickets_items_adjuntos.id_ticket_item = adjuntos.id
					WHERE adjuntos.id_ticket = tickets.id) AS adjuntos,
					
					@cantidad_de_agentes:=(SELECT COUNT(*) FROM tickets_rel_contactos WHERE tickets_rel_contactos.id_ticket = tickets.id AND EXISTS (SELECT true FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = tickets_rel_contactos.id_contacto)) AS agentes_cantidad,
				
					CASE
						WHEN tickets.inicio < 15*60 THEN ROUND(100/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 30*60 THEN ROUND(90/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 60*60 THEN ROUND(80/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 90*60 THEN ROUND(70/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 120*60 THEN ROUND(60/@cantidad_de_agentes, 2)
						ELSE ROUND(50/@cantidad_de_agentes, 2)
					END AS efectividad,
				
					(SELECT GROUP_CONCAT(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) SEPARATOR ', ')
					FROM contactos
					LEFT JOIN tickets_rel_contactos ON tickets_rel_contactos.id_contacto = contactos.id
					WHERE tickets_rel_contactos.id_ticket = tickets.id) AS agentes,
									
					CASE
					   WHEN tickets.estado = 1 THEN 'label-danger'
					   WHEN tickets.estado = 2 THEN 'label-warning'
					   WHEN tickets.estado = 3 THEN 'label-primary'
					   WHEN tickets.estado = 4 THEN 'label-danger'
					   WHEN tickets.estado = 5 THEN 'label-success'
					   WHEN tickets.estado = 6 THEN 'label-info'
					   WHEN tickets.estado = 7 THEN 'label-plain'
					END AS estado_ui_class,
					
					CASE
					   WHEN tickets.prioridad = 1 THEN 'Normal'
					   WHEN tickets.prioridad = 2 THEN 'Alta'
					   WHEN tickets.prioridad = 3 THEN 'Urgente'
					   WHEN tickets.prioridad = 4 THEN 'Crítica'
					END AS prioridad,
					
					CASE
					   WHEN tickets.prioridad = 1 THEN 'badge-info'
					   WHEN tickets.prioridad = 2 THEN 'badge-success'
					   WHEN tickets.prioridad = 3 THEN 'badge-warning'
					   WHEN tickets.prioridad = 4 THEN 'badge-danger'
					END AS prioridad_ui_class
				
				FROM tickets
				
				LEFT JOIN tickets_agentes_rel_areas ON tickets.id_area = tickets_agentes_rel_areas.id_area
				LEFT JOIN tickets_rel_contactos ON tickets.id = tickets_rel_contactos.id_ticket
				LEFT JOIN tickets_areas ON tickets.id_area = tickets_areas.id
				LEFT JOIN contactos ON tickets.username_alta = contactos.id
				LEFT JOIN empresas ON tickets.id_empresa = empresas.id
				LEFT JOIN tickets_estados ON tickets.estado = tickets_estados.id
				LEFT JOIN grupos ON tickets.grupo = grupos.id
				
				WHERE 1
			";
		
			
		// filtros
		if (!empty($parametros['estado']))
		{
			if ($parametros['estado'] == 'abiertos')
			{
				$sql .= " AND tickets.estado != 7";
			}
			else
			{
				$sql .= " AND tickets.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
		}
		else
		{
			$sql .= " AND tickets.estado > 0";
		}
		
		if (!empty($parametros['id_contacto']))
		{
			$sql .= " AND (tickets_rel_contactos.id_contacto = ? OR tickets.username_alta = ?)";
			
			$placeholders[] = $parametros['id_contacto'];
			$placeholders[] = $parametros['id_contacto'];
		}
		else
		{
			$sql .= " AND (tickets.id_empresa = ? OR tickets_rel_contactos.id_contacto = ? OR (tickets_agentes_rel_areas.id_contacto = ? AND tickets.grupo = tickets_agentes_rel_areas.grupo))";
			
			$placeholders[] = $this->usuario->id_empresa;
			$placeholders[] = $this->usuario->id;
			$placeholders[] = $this->usuario->id;
		}
		
		// busqueda
		if (!empty($parametros['filtrar']))
		{
			$value = str_replace(' ', '|', trim($parametros['filtrar']));
			
			$sql .= " AND (tickets.asunto REGEXP '" . $value . "'";
			$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
			$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
			$sql .= " OR contactos.username REGEXP '" . $value . "'";
			$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
		}
		
		if (!empty($parametros['search']))
		{
			$value = trim($parametros['search']);
			
			$sql .= " AND (tickets.asunto LIKE '%" . $value . "%'";
			$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
			$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
			$sql .= " OR contactos.username LIKE '%" . $value . "%'";
			$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
		}
		
		// orden
		$sql .= " ORDER BY";
		$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " FIELD(tickets.estado,2,3,4,5,6,7,1) ASC, tickets.fecha_alta";
		$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " DESC";
		
		// limite
		$sql .= " LIMIT ?, ?";
		$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
		$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}


	public function getTicketDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT tickets.*
					
					FROM tickets
					LEFT JOIN tickets_agentes_rel_areas ON tickets.id_area = tickets_agentes_rel_areas.id_area
					LEFT JOIN tickets_rel_contactos ON tickets.id = tickets_rel_contactos.id_ticket
				";
		}
		else
		{
			$sql = "
		            SELECT tickets.*, COALESCE(servicios.descripcion, categorias_generales.descripcion) AS servicio, empresas.empresa, servicios_hosting.id AS hosting, tickets_estados.estado, tickets.estado AS id_estado, tickets_areas.area,
		            
			            CASE
						   WHEN tickets.estado = 1 THEN 'label-danger'
						   WHEN tickets.estado = 2 THEN 'label-warning'
						   WHEN tickets.estado = 3 THEN 'label-primary'
						   WHEN tickets.estado = 4 THEN 'label-danger'
						   WHEN tickets.estado = 5 THEN 'label-success'
						   WHEN tickets.estado = 6 THEN 'label-info'
						   WHEN tickets.estado = 7 THEN 'label-plain'
						END AS estado_ui_class
		            
					FROM tickets
					LEFT JOIN tickets_agentes_rel_areas ON tickets.id_area = tickets_agentes_rel_areas.id_area
					LEFT JOIN tickets_areas ON tickets.id_area = tickets_areas.id
					LEFT JOIN tickets_rel_contactos ON tickets.id = tickets_rel_contactos.id_ticket
					LEFT JOIN servicios ON tickets.id_servicio = servicios.id
					LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
					LEFT JOIN empresas ON tickets.id_empresa = empresas.id
					LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
					LEFT JOIN tickets_estados ON tickets.estado = tickets_estados.id
		        ";
	    }
	    
	    $sql .= "
	    		WHERE (tickets.id_empresa = ? OR tickets_rel_contactos.id_contacto = ? OR tickets_agentes_rel_areas.id_contacto = ? OR tickets.username_alta = ?)
					
				AND tickets.id = ?
			";

		$query = $this->db->query($sql, array(
				$this->usuario->id_empresa,
				$this->usuario->id,
				$this->usuario->id,
				$this->usuario->id,
				$id
			));

		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getTicketDetalleRaw($id)
	{
		return $this->getTicketDetalle($id, array('modo'=>'raw'));
	}


	public function getTicketItems($id)
	{
		$sql = "
	            SELECT tickets_items.*, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.avatar,
	            
	            	CASE
					   WHEN tickets_items.id_origen = 1 THEN 'fa fa-envelope-o'
					   WHEN tickets_items.id_origen = 2 THEN 'fa fa-globe'
					   WHEN tickets_items.id_origen = 3 THEN 'fa fa-edit'
					   WHEN tickets_items.id_origen = 4 THEN 'fa fa-phone'
					   WHEN tickets_items.id_origen = 5 THEN 'fa fa-android'
					END AS origen_ui_class
				
				FROM tickets_items
				LEFT JOIN contactos ON tickets_items.id_contacto = contactos.id
				
				WHERE (tickets_items.visibilidad = 0 OR EXISTS (SELECT true FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = ? OR tickets_items.id_contacto = ?))
				AND tickets_items.id_ticket = ?
			";

		$query = $this->db->query($sql, array(
				$this->usuario->id,
				$this->usuario->id,
				$id
			));

		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getTicketItemAdjuntos($id)
	{
		$sql = "
	            SELECT tickets_items_adjuntos.nombre, tickets_items_adjuntos.archivo
	            
	            FROM tickets_items_adjuntos
				
				WHERE tickets_items_adjuntos.id_ticket_item = ?
			";

		$query = $this->db->query($sql, array(
				$id
			));

		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}


	public function ingresarTicket($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (isset($valores['id_servicio']) && $this->verificarServicio($valores['id_servicio'], $data['id_empresa'])) $data['id_servicio'] = $valores['id_servicio'];
		if (!empty($valores['id_area'])) $data['id_area'] = $valores['id_area'];
		$data['asunto'] = trim($valores['asunto']);
		if (!empty($valores['prioridad'])) $data['prioridad'] = $valores['prioridad'];
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = (!empty($valores['id_contacto'])) ? $valores['id_contacto'] : $this->usuario->id;

		$insert = $this->db->insert('tickets', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function ingresarTicketItem($id, $valores)
	{
		$item['id_ticket'] = $id;
		$item['id_contacto'] = (!empty($valores['id_contacto'])) ? $valores['id_contacto'] : $this->usuario->id;
		if (!empty($valores['id_origen'])) $item['id_origen'] = $valores['id_origen'];
		$item['mensaje'] = trim($valores['mensaje']);
		if (isset($valores['visibilidad'])) $item['visibilidad'] = $valores['visibilidad'];
		
		$item['fecha_alta'] = now();

		$insert = $this->db->insert('tickets_items', $item);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarTicket($id, $valores)
	{
		if (isset($valores['id_servicio'])) $data['id_servicio'] = (!empty($valores['id_servicio'])) ? $valores['id_servicio'] : null;
		if (isset($valores['id_area']) && !empty($valores['id_area'])) $data['id_area'] = $valores['id_area'];
		if (isset($valores['asunto']) && !empty($valores['asunto'])) $data['asunto'] = trim($valores['asunto']);
		if (isset($valores['prioridad']) && !empty($valores['prioridad'])) $data['prioridad'] = $valores['prioridad'];
		if (isset($valores['estado']) && !empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;

		$res = $this->db->update('tickets', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function asociarContacto($id_ticket, $id_contacto)
	{
		$item['id_ticket'] = $id_ticket;
		$item['id_contacto'] = $id_contacto;

		$insert = $this->db->insert('tickets_rel_contactos', $item);

		if ($insert)
		{
			$res['id'] = $item['id_contacto'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarAsociacionDeContacto($id_ticket, $id_contacto)
	{
		$sql = "
				SELECT true
				FROM tickets_rel_contactos
				WHERE id_ticket = ?
				AND id_contacto = ?
			";
		
		
		$query = $this->db->query($sql, array(
				$id_ticket,
				$id_contacto
			));

		if ($query->row_array())
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	public function getAgentesArea()
	{
		$sql = " 
				SELECT grupos.id AS id_grupo, grupos.grupo, contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, tickets_areas.area, tickets_agentes_rel_areas.nivel
	
				FROM tickets_agentes_rel_areas
				LEFT JOIN contactos ON tickets_agentes_rel_areas.id_contacto = contactos.id
				LEFT JOIN grupos ON tickets_agentes_rel_areas.grupo = grupos.id
				LEFT JOIN tickets_areas ON tickets_agentes_rel_areas.id_area = tickets_areas.id
	
				ORDER BY grupos.grupo, contactos.nombre, tickets_areas.area
			";
				
		$query = $this->db->query($sql);

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAgentes()
	{
		$sql = " 
				SELECT tickets_agentes_rel_areas.grupo, contactos.id, contactos.username
	
				FROM tickets_agentes_rel_areas
				LEFT JOIN contactos ON tickets_agentes_rel_areas.id_contacto = contactos.id
				
				GROUP BY tickets_agentes_rel_areas.id_contacto
	
				ORDER BY tickets_agentes_rel_areas.id_contacto
			";
				
		$query = $this->db->query($sql);

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function esAgente()
	{
		$sql = " 
				SELECT true AS agente
	
				FROM tickets_agentes_rel_areas
				
				WHERE tickets_agentes_rel_areas.id_contacto = ?
				AND tickets_agentes_rel_areas.grupo = ?
			";
		
		$placeholders[] = $this->usuario->id;
		$placeholders[] = $this->usuario->grupo;
		
		$query = $this->db->query($sql, $placeholders);

		if ($query)
		{
			$res = $query->row_array()['agente'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getTicketContactosAsociados($id)
	{
		$sql = "
	            SELECT contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, contactos.avatar, contactos.ip,
	            	
	            	CASE
						WHEN contactos.estado = 1 THEN 'label-plain'
						WHEN contactos.estado = 2 THEN 'label-primary'
						WHEN contactos.estado = 3 THEN 'label-info'
						WHEN contactos.estado = 4 THEN 'label-danger'
						WHEN contactos.estado = 5 THEN 'label-warning'
					END AS estado_ui_class,
					
					CASE
						WHEN contactos.estado = 1 THEN 'Inactivo'
						WHEN contactos.estado = 2 THEN 'Activo'
						WHEN contactos.estado = 3 THEN 'Online'
						WHEN contactos.estado = 4 THEN 'Bloqueado'
						WHEN contactos.estado = 5 THEN 'Vencido'
					END AS estado
	            
				FROM tickets_rel_contactos
				LEFT JOIN contactos ON tickets_rel_contactos.id_contacto = contactos.id
				
				WHERE tickets_rel_contactos.id_ticket = ?
	        ";

		$query = $this->db->query($sql, array(
				$id
			));

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getContactosAsociadosRespuesta($id_item)
	{
		$sql = "
	            SELECT contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email
	            	            
				FROM tickets_rel_contactos
				LEFT JOIN contactos ON tickets_rel_contactos.id_contacto = contactos.id
				LEFT JOIN tickets_items ON tickets_rel_contactos.id_ticket = tickets_items.id_ticket
				
				WHERE (tickets_items.visibilidad = 0 OR EXISTS (SELECT true FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = contactos.id OR tickets_items.id_contacto = contactos.id))
				AND tickets_items.id = ?
			";

		$query = $this->db->query($sql, array(
				$id_item
			));

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAgentesDisponibles($id, $nivel)
	{
		$sql = " 
				SELECT contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email
				FROM tickets_agentes_rel_areas
				LEFT JOIN contactos ON tickets_agentes_rel_areas.id_contacto = contactos.id
				LEFT JOIN tickets_areas ON tickets_agentes_rel_areas.id_area = tickets_areas.id
				LEFT JOIN tickets ON tickets.id_area = tickets_areas.id
	
				WHERE tickets_agentes_rel_areas.grupo = tickets.grupo
				AND contactos.estado > 1
				AND tickets.id = ?
				AND tickets_agentes_rel_areas.nivel = ?
				
				ORDER BY contactos.nombre
			";
				
		$query = $this->db->query($sql, array(
				$id,
				$nivel
			));

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboAgentes($parametros = null, $null = null)
	{
		$sql = "	
				SELECT contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto
	
				FROM contactos
				
				WHERE contactos.id IN (SELECT id_contacto FROM tickets_agentes_rel_areas)
			";
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND (contactos.estado = ? OR contactos.estado = ?)";
				$placeholders[] = 2;
				$placeholders[] = 3;
			}
			
			if (!empty($parametros['grupo']))
			{
				$sql .= " AND contactos.grupo = ?";
				$placeholders[] = $parametros['grupo'];
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " contactos.nombre";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
		
		
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			if (!empty($null)) $res[null] = $null;
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['contacto'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAlertaTemplate($id)
	{
		$sql = " 
				SELECT tickets_items_stats.id, tickets.id AS id_ticket,
				CONCAT(IF(tickets.estado=2, 'Nuevo Ticket ', 'Nueva Respuesta '), '[#', tickets.grupo, '-', tickets.id_area, '-', tickets.id, ']: ', tickets.asunto) AS codigo, tickets.asunto, tickets_items.mensaje, grupos.template_tickets AS url, grupos.remitente_nombre, grupos.remitente_email, tickets_items.id_contacto,
				trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, contactos.username, contactos.hash, tickets_areas.area,
				trim(CONCAT(autores.nombre, ' ', IFNULL(autores.apellido, ''))) AS autor,
				IF ((SELECT COUNT(*) FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = contactos.id), 1, 0) AS agente
	
				FROM tickets_items_stats
				LEFT JOIN tickets_items ON tickets_items_stats.id_tickets_item = tickets_items.id
				LEFT JOIN tickets ON tickets_items.id_ticket = tickets.id
				LEFT JOIN grupos ON tickets.grupo = grupos.id
				LEFT JOIN contactos ON tickets_items_stats.id_contacto = contactos.id
				LEFT JOIN contactos AS autores ON tickets_items.id_contacto = autores.id
				LEFT JOIN tickets_areas ON tickets.id_area = tickets_areas.id
	
				WHERE tickets_items_stats.id = ?
			";
				
		$query = $this->db->query($sql, array($id));

		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	

	public function notificarNuevoTicket($id_item, $nivel = 1)
	{
		$res['contactos'] = $this->getAgentesDisponibles($this->db->query("SELECT id_ticket FROM tickets_items WHERE id = ?", array($id_item))->row()->id_ticket, $nivel);
		
		if (isset($res['contactos']))
		{
			foreach ($res['contactos'] as $contacto)
			{
				if ($contacto['id'] != $this->usuario->id)
				{
					$this->ingresarComunicacion($contacto['id'], $id_item, $nivel);
				}
			}
		}
		
		return (!empty($res['contactos'])) ? $res['contactos'] : null;
	}


	public function notificarNuevaRespuesta($id_item, $nivel = 1)
	{
		$res['contactos'] = $this->getContactosAsociadosRespuesta($id_item);
		
		if (isset($res['contactos']))
		{
			foreach ($res['contactos'] as $contacto)
			{
				if ($contacto['id'] != $this->usuario->id)
				{
					$this->ingresarComunicacion($contacto['id'], $id_item, $nivel);
				}
			}
		}
		
		return (!empty($res['contactos'])) ? $res['contactos'] : null;
	}
	
	
	public function notificarAsignacion($id_item, $id_contacto)
	{
		$comunicacion['id_contacto'] = $id_contacto;
		$comunicacion['id_tickets_item'] = $id_item;
	
		$insert = $this->db->insert('tickets_items_stats', $comunicacion);
	
		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarComunicacion($id_contacto, $id_item, $nivel)
	{
		$comunicacion['id_contacto'] = $id_contacto;
		$comunicacion['id_tickets_item'] = $id_item;
		$comunicacion['nivel'] = $nivel;
	
		if (!$this->db->query("SELECT COUNT(*) AS count FROM tickets_items_stats WHERE id_contacto = ? AND id_tickets_item = ? AND nivel = ?", array($comunicacion['id_contacto'], $comunicacion['id_tickets_item'], $comunicacion['nivel']))->row()->count)
		{
			$insert = $this->db->insert('tickets_items_stats', $comunicacion);
	
			if ($insert)
			{
				$res['id'] = $this->db->insert_id();
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarComunicaciones($intervalo = 0, $nivel = 1, $prioridad = null)
	{
		$sql = "
				SELECT tickets.id
				
				FROM tickets
				
				WHERE 1
				AND tickets.estado = 2
				AND tickets.fecha_alta+? < UNIX_TIMESTAMP(NOW())
			";
			
		// filtros
		$placeholders[] = $intervalo;
		
		if (!empty($prioridad))
		{
			$sql .= " AND tickets.prioridad = ?";
			$placeholders[] = $prioridad;
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array();
			
			if ($res)
			{
				$agentes = $this->getAgentesDisponibles($res['id'], $nivel);
				
				if (isset($agentes))
				{
					foreach ($agentes as $obj)
					{
						$res['comunicacion'][] = $this->ingresarComunicacion($obj['id'], $this->db->query("SELECT id FROM tickets_items WHERE id_ticket = ?", array($res['id']))->row()->id, $nivel);
					}
				}
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicacion($id)
	{
		$this->load->library('curl');
		
		$this->load->config('smtp');
		$this->load->library('email', $this->config->item('smtp'));
		
		$res = $this->getAlertaTemplate($id);
		
		if (isset($res))
		{
			$res['template'] = $this->curl->simple_post($res['url'], $res);
				
			$this->email->set_newline("\r\n");
			$this->email->from($res['remitente_email'], $res['remitente_nombre']);
			$this->email->to($res['email'], $res['contacto']);
			$this->email->subject($res['codigo']);
			$this->email->message(preg_replace('/(<body.*?(?=>)>)/i', '$1' . '<img src="' . base_url('ticket' . $res['id'] . '.gif') . '" border="0" height="1" width="1" />', $res['template']));
			//if (isset($res['mensaje'])) $this->email->set_alt_message($res['mensaje']);
			
			$this->email->set_header('Track-ID', $res['id']);
			
			
			if (!$this->email->send($this->config->item('smtp')['nodebug']))
			{
				$comunicacion['debug'] = $this->email->print_debugger();
			}
			else
			{
				if (!$this->config->item('smtp')['nodebug']) $comunicacion['debug'] = $this->email->print_debugger(array('headers', 'subject', 'body'));
				
				$comunicacion['enviado'] = now();
			}
	
			$res = $this->db->update('tickets_items_stats', $comunicacion, array('id'=>$res['id']));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicaciones($intervalo = 0)
	{
		$sql = "
				SELECT tickets_items_stats.id
				
				FROM tickets_items_stats
				LEFT JOIN tickets_items ON tickets_items_stats.id_tickets_item = tickets_items.id
				
				WHERE 1
				AND enviado IS NULL
				AND tickets_items.fecha_alta+? < UNIX_TIMESTAMP(NOW())
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($intervalo, 10));
		
		if ($query)
		{
			$res = $query->result_array();
			
			foreach ($res as $obj)
			{
				$this->enviarComunicacion($obj['id']);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function notificarTicketsSinEnviar($limite = 5)
	{
		$sql = "SELECT id FROM tickets_items WHERE enviado IS NULL LIMIT ?";
		
		$query = $this->db->query($sql, array($limite));
		
		if ($query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeTickets($estado)
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM tickets WHERE estado = ?";
		
		$placeholders[] = $estado;
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarServicio($id, $id_empresa)
	{
		$sql = "
				SELECT true
				FROM servicios
				WHERE id = ?
				AND id_empresa = ?
			";
		
		
		$query = $this->db->query($sql, array(
				$id,
				$id_empresa
			));

		if ($query->row_array())
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	function cambiarEstado($id, $estado)
	{
		$res = $this->db->update('tickets', array('estado'=>$estado), array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	function setInicio($id)
	{
		//$this->db->where('inicio IS NULL');
		$res = $this->db->update('tickets', array('inicio'=>$this->getInicio($id)), array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	function getInicio($id)
	{
		$sql = "SELECT (SELECT tickets_items.fecha_alta
						FROM tickets_items
						WHERE tickets_items.visibilidad = 0
						AND tickets_items.id_ticket = tickets.id
						LIMIT 1,1) - tickets.fecha_alta AS inicio
						
				FROM tickets
				
				WHERE 1
				
				AND id = ?
				";
		
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['inicio'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function actualizarInicios()
	{
		$sql = "
				SELECT tickets.id
				
				FROM tickets
				
				WHERE 1
				AND inicio IS NULL
				
				ORDER BY id DESC
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array(1000));
		
		if ($query)
		{
			$res = $query->result_array();
			
			foreach ($res as $obj)
			{
				$this->setInicio($obj['id']);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function getTicketsStats()
	{
		$sql = "
				SELECT TIME_FORMAT(SEC_TO_TIME(AVG(inicio)),'%Hh %im') AS inicio
				
				FROM tickets
				
				WHERE tickets.fecha_alta BETWEEN 
    				UNIX_TIMESTAMP(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 0 MONTH), '%Y-%m-01')) 
    				AND UNIX_TIMESTAMP(LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 0 MONTH)))
				AND inicio IS NOT NULL
			";
		
		$query = $this->db->query($sql);
		
		if ($query)
		{
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboTicketsEstados()
	{
		return array(1=>'Rechazado', 2=>'Nuevo', 3=>'Abierto', 4=>'Esperando Respuesta', 5=>'Elevado a Proveedor', 6=>'Plan de Acción', 7=>'Cerrado');
	}
	
	
	public function track($id)
	{
		$comunicacion['recibido'] = now();
		
		$this->db->where('recibido IS NULL');
		$res = $this->db->update('tickets_items_stats', $comunicacion, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAlertasCriticas($intervalo = 3600) // 1 Hora
	{
		$sql = "
				SELECT tickets.id

				FROM tickets
				
				WHERE 1
				AND tickets.prioridad >= 2
				AND tickets.estado = 2
				AND tickets.fecha_alta+? < UNIX_TIMESTAMP(NOW())
				AND alerta_voip = 0
			";
		
		$query = $this->db->query($sql, array($intervalo));
		
		if ($query)
		{
			$res = $query->result_array();
			
			foreach ($res as $obj)
			{
				$this->db->update('tickets', array('alerta_voip'=>1), array('id'=> $obj['id']));
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarTicketItemAdjunto($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		$data['id_ticket_item'] = $valores['id'];
		$data['id_tipo'] = $valores['id_tipo'];
		$data['nombre'] = $valores['nombre'];
		$data['archivo'] = $valores['archivo'];
		$data['peso'] = (!empty($valores['peso'])) ? $valores['peso'] : null;
		
		$data['fecha_alta'] = (!empty($valores['fecha_alta'])) ? $valores['fecha_alta'] : now();
		$data['username_alta'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$insert = $this->db->insert('tickets_items_adjuntos', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function getEfectividad($id)
	{
		$sql = "
				SELECT tickets.id, SEC_TO_TIME(tickets.inicio) as inicio, @cantidad_de_agentes:=(SELECT COUNT(*) FROM tickets_rel_contactos WHERE tickets_rel_contactos.id_ticket = tickets.id AND EXISTS (SELECT true FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = tickets_rel_contactos.id_contacto)) AS agentes,
				
					CASE
						WHEN tickets.inicio < 15*60 THEN 100/@cantidad_de_agentes
						WHEN tickets.inicio < 30*60 THEN 90/@cantidad_de_agentes
						WHEN tickets.inicio < 60*60 THEN 80/@cantidad_de_agentes
						WHEN tickets.inicio < 90*60 THEN 70/@cantidad_de_agentes
						WHEN tickets.inicio < 120*60 THEN 60/@cantidad_de_agentes
						ELSE 50/@cantidad_de_agentes
					END AS efectividad
				
				FROM tickets
				
				WHERE tickets.id = ?
			";
		
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);

		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function getTicketsMesFromuserId($id, $meses_antes = 0)
	{
		$sql = "
				SELECT tickets.id, tickets.asunto, SEC_TO_TIME(tickets.inicio) as inicio,
					@cantidad_de_agentes:=(SELECT COUNT(*) FROM tickets_rel_contactos WHERE tickets_rel_contactos.id_ticket = tickets.id AND EXISTS (SELECT true FROM tickets_agentes_rel_areas WHERE tickets_agentes_rel_areas.id_contacto = tickets_rel_contactos.id_contacto)) AS agentes_cantidad,
				
					CASE
						WHEN tickets.inicio < 15*60 THEN ROUND(100/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 30*60 THEN ROUND(90/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 60*60 THEN ROUND(80/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 90*60 THEN ROUND(70/@cantidad_de_agentes, 2)
						WHEN tickets.inicio < 120*60 THEN ROUND(60/@cantidad_de_agentes, 2)
						ELSE ROUND(50/@cantidad_de_agentes, 2)
					END AS efectividad
				
				FROM tickets
				LEFT JOIN tickets_rel_contactos ON tickets_rel_contactos.id_ticket = tickets.id
				
				WHERE tickets.estado = 7
				AND tickets.fecha_alta BETWEEN 
				    UNIX_TIMESTAMP(DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL ? MONTH), '%Y-%m-01')) 
				    AND UNIX_TIMESTAMP(LAST_DAY(DATE_SUB(CURDATE(), INTERVAL ? MONTH)))
				
				AND tickets_rel_contactos.id_contacto = ?
			";
		
		$placeholders[] = $meses_antes;
		$placeholders[] = $meses_antes;
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);

		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function clasificarTicket($texto)
	{
		$sql = "
				SELECT *
				
				FROM tickets_tipo
				
				WHERE ? REGEXP clave
				AND estado = 2
				AND clave IS NOT NULL
				AND id_area IS NOT NULL
			";
			
		$placeholders[] = $texto;
		
		$query = $this->db->query($sql, $placeholders);

		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function menu($padre = null, $seleccionada = false, $niveles = 10, $nivel = null, $estado = 2)
	{
		$sql = "
				SELECT tickets_tipo.*
				
				FROM tickets_tipo
				
				WHERE tickets_tipo.estado = ?
				";
		
		$sql .= (isset($padre)) ? " AND tickets_tipo.padre = $padre" : " AND tickets_tipo.padre IS NULL";
		
		$sql .= " ORDER BY tickets_tipo.orden ASC";
		
		// consulta
		$placeholders[] = $estado;
		
		$query = $this->db->query($sql, $placeholders);
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				
				$res[] = array(
								'id'=>$row['id'],
								'tipo'=>$row['tipo'],
								'mensaje'=>$row['mensaje'],
								'prioridad'=>$row['prioridad'],
								'id_area'=>$row['id_area'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->menu($row['id'], $seleccionada, $niveles, $nivel, $estado)
								);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}