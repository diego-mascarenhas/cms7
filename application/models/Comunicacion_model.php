<?php defined('BASEPATH') or exit('No direct script access allowed');


class Comunicacion_model extends CI_Model {

	public function getComunicaciones($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS comunicaciones.id, comunicaciones.id_tipo, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, COALESCE(comunicaciones.asunto, comunicaciones_templates.asunto) AS asunto, comunicaciones.enviado, comunicaciones.recibido, comunicaciones.vinculo,
				
					CASE
						WHEN comunicaciones.estado = 1 THEN 'label-plain'
						WHEN comunicaciones.estado = 2 THEN 'label-warning'
						WHEN comunicaciones.estado = 3 THEN 'label-primary'
						WHEN comunicaciones.estado = 4 THEN 'label-danger'
					END AS estado_ui_class,
					
					CASE
						WHEN comunicaciones.estado = 1 THEN 'Enviar'
						WHEN comunicaciones.estado = 2 THEN 'Enviado'
						WHEN comunicaciones.estado = 3 THEN 'Recibido'
						WHEN comunicaciones.estado = 4 THEN 'Error'
					END AS estado
				
				FROM comunicaciones
				LEFT JOIN contactos ON comunicaciones.id_contacto = contactos.id
				LEFT JOIN comunicaciones_templates ON comunicaciones.id_tipo = comunicaciones_templates.id_tipo AND comunicaciones_templates.grupo = comunicaciones.grupo AND comunicaciones_templates.idioma = contactos.idioma
		
				WHERE comunicaciones.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND comunicaciones.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND comunicaciones.estado > 0";
			}
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND contactos.id = ?";
				
				$placeholders[] = $parametros['id_contacto'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (contactos.username REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR comunicaciones.id REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR contactos.email LIKE '%" . $value . "%'";
				$sql .= " OR comunicaciones.id LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " comunicaciones.id";
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
	
	
	public function getComunicacionDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT *
					FROM comunicaciones
			";
		}
		else
		{
			$sql = "
					SELECT comunicaciones.id, comunicaciones.id_tipo, COALESCE(comunicaciones.asunto, comunicaciones_templates.asunto) AS asunto, contactos.id AS id_contacto, comunicaciones.data, COALESCE(comunicaciones_templates.remitente_nombre, grupos.remitente_nombre) AS remitente_nombre, COALESCE(comunicaciones_templates.remitente_email, grupos.remitente_email) AS remitente_email, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, comunicaciones_templates.url,  comunicaciones.enviado, comunicaciones.recibido, comunicaciones.vinculo,
					
						CASE
							WHEN comunicaciones.estado = 1 THEN 'label-plain'
							WHEN comunicaciones.estado = 2 THEN 'label-warning'
							WHEN comunicaciones.estado = 3 THEN 'label-primary'
							WHEN comunicaciones.estado = 4 THEN 'label-danger'
						END AS estado_ui_class,
						
						CASE
							WHEN comunicaciones.estado = 1 THEN 'Enviar'
							WHEN comunicaciones.estado = 2 THEN 'Enviado'
							WHEN comunicaciones.estado = 3 THEN 'Recibido'
							WHEN comunicaciones.estado = 4 THEN 'Error'
						END AS estado
					
					FROM comunicaciones
					LEFT JOIN contactos ON comunicaciones.id_contacto = contactos.id
					LEFT JOIN comunicaciones_templates ON comunicaciones.id_tipo = comunicaciones_templates.id_tipo AND comunicaciones_templates.grupo = comunicaciones.grupo AND comunicaciones_templates.idioma = contactos.idioma
					LEFT JOIN grupos ON comunicaciones_templates.grupo = grupos.id
				";
		}
		
		$sql .= " 
				WHERE comunicaciones.grupo = ?
				AND comunicaciones.estado > 0
				AND comunicaciones.id = ?	
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
			$sql .= " AND contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getComunicacionDetalleRaw($id)
	{
		return $this->getComunicacionDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getComunicacionTemplate($id)
	{
		$sql = "
				SELECT comunicaciones.id, COALESCE(comunicaciones.asunto, comunicaciones_templates.asunto) AS asunto, contactos.id AS id_contacto, comunicaciones.data, COALESCE(comunicaciones_templates.remitente_nombre, grupos.remitente_nombre) AS remitente_nombre, COALESCE(comunicaciones_templates.remitente_email, grupos.remitente_email) AS remitente_email, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, comunicaciones_templates.url
				
				FROM comunicaciones
				LEFT JOIN contactos ON comunicaciones.id_contacto = contactos.id
				LEFT JOIN comunicaciones_templates ON comunicaciones.id_tipo = comunicaciones_templates.id_tipo AND comunicaciones_templates.grupo = comunicaciones.grupo AND comunicaciones_templates.idioma = contactos.idioma
				LEFT JOIN grupos ON comunicaciones_templates.grupo = grupos.id

				WHERE comunicaciones.estado > 0
				AND comunicaciones.id = ?	
			";
			
			
		// consulta
		$query = $this->db->query($sql, array($id));
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
			
			$this->load->library('curl');
			$res['template'] = $this->curl->simple_post($res['url'], (isset($res['data'])) ? array_merge($res, json_decode($res['data'], true)) : $res);
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($id_contacto, $id_tipo, $id_referencia)
	{
		$sql = "
				SELECT true
				
				FROM comunicaciones
				
				WHERE id_contacto = ?
				AND id_tipo = ?
				AND id_referencia = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id_contacto, $id_tipo, $id_referencia));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	public function ingresarComunicacion($id_contacto, $id_tipo, $id_referencia = null, $data = null)
	{
		if (!$this->verificarSiExiste($id_contacto, $id_tipo, $id_referencia))
		{
			$sql = "SELECT grupo, username, hash FROM contactos WHERE id = ?";
			
			$query = $this->db->query($sql, array($id_contacto));
			
			if (!isset($res['error']) && $query)
			{
				$row = $query->row_array();
			}
			
			if (!isset($res['error']) && isset($row))
			{
				$comunicacion['grupo'] = $row['grupo'];
				$comunicacion['id_contacto'] = $id_contacto;
		
				$comunicacion['id_tipo'] = $id_tipo;
				$comunicacion['id_referencia'] = $id_referencia;
				$comunicacion['data'] = (!empty($data)) ? json_encode(array_merge($data, $row)) : json_encode($row);
		
				$insert = $this->db->insert('comunicaciones', $comunicacion);
		
				if (isset($insert))
				{
					$res['id'] = $this->db->insert_id();
				}
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicacion($id)
	{
		$this->load->config('smtp');
		$this->load->library('email', $this->config->item('smtp'));
		
		$track_url = base_url();
		
		$res = $this->getComunicacionTemplate($id);
		
		if (isset($res)) {
			$this->email->set_newline("\r\n");
			$this->email->from($res['remitente_email'], $res['remitente_nombre']);
			$this->email->to($res['email'], $res['contacto']);
			$this->email->subject($res['asunto']);
			$this->email->message(preg_replace('/(<body.*?(?=>)>)/i', '$1' . '<img src="' . $track_url . 'com' . $res['id'] . '.gif' . '" border="0" height="1" width="1" />', $res['template']));
			//if (isset($res['mensaje'])) $this->email->set_alt_message($res['mensaje']);
			
			$this->email->set_header('Track-ID', $res['id']);
			
			// Intentar enviar el email sin pasar el parámetro nodebug
			if (!$this->email->send()) {
				$comunicacion['debug'] = $this->email->print_debugger();
				$comunicacion['estado'] = 4; // Error
			} else {
				if (!$this->config->item('smtp')['nodebug']) {
					$comunicacion['debug'] = $this->email->print_debugger(array('headers', 'subject', 'body'));
				}
				
				$comunicacion['asunto'] = $res['asunto'];
				$comunicacion['enviado'] = now();
				$comunicacion['estado'] = 2; // Enviado
			}

			$res = $this->db->update('comunicaciones', $comunicacion, array('id'=>$res['id']));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicacionCopia($id, $to)
	{
		$this->load->config('smtp');
		$this->load->library('email', $this->config->item('smtp'));
		
		$res = $this->getComunicacionTemplate($id);
		
		$this->email->set_newline("\r\n");
		$this->email->from($res['remitente_email'], $res['remitente_nombre']);
		$this->email->to($to);
		$this->email->subject($res['asunto']);
		$this->email->message($res['template']);
		
		
		if (!$this->email->send($this->config->item('smtp')['nodebug']))
		{
			$res['debug'] = $this->email->print_debugger();
		}
		else
		{
			if (!$this->config->item('smtp')['nodebug'])  $this->email->print_debugger(array('headers', 'subject', 'body'));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicaciones($limite = 5)
	{
		$sql = "SELECT id FROM comunicaciones WHERE estado = 1 LIMIT ?";
		
		$query = $this->db->query($sql, array($limite));
		
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
	
	
	public function getCantidadDeComunicaciones($estado = 1)
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM comunicaciones WHERE estado = ? AND grupo = ?";
		
		$placeholders[] = $estado;
		$placeholders[] = $this->usuario->grupo;
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getComunicacionesStats($parametros = null)
	{
		$sql = "
				SELECT COUNT(comunicaciones.id) AS total, comunicaciones.id_tipo, comunicaciones_tipo.tipo, comunicaciones.asunto,
					(SELECT COUNT(*) FROM comunicaciones AS enviar WHERE enviar.id_tipo = comunicaciones.id_tipo AND estado = 1) AS enviar,
					(SELECT COUNT(*) FROM comunicaciones AS abiertos WHERE abiertos.id_tipo = comunicaciones.id_tipo AND estado = 3) AS abiertos
				
				FROM comunicaciones
				LEFT JOIN comunicaciones_tipo ON comunicaciones.id_tipo = comunicaciones_tipo.id
				LEFT JOIN contactos ON comunicaciones.id_contacto = contactos.id
				
				WHERE comunicaciones.grupo = ?
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
				$sql .= " AND comunicaciones.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND comunicaciones.estado > 0";
			}
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND contactos.id = ?";
				
				$placeholders[] = $parametros['id_contacto'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (contactos.username REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR comunicaciones.id REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR contactos.email LIKE '%" . $value . "%'";
				$sql .= " OR comunicaciones.id LIKE '%" . $value . "%') ";
			}
			
			// group
			$sql .= " GROUP BY comunicaciones.id_tipo";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " comunicaciones.id";
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
	
	
	public function track($id)
	{
		$comunicacion['recibido'] = now();
		$comunicacion['estado'] = 3;
		
		$res = $this->db->update('comunicaciones', $comunicacion, array('id'=>$id, 'estado'=>2));
		
		return (!empty($res)) ? $res : null;
	}
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}