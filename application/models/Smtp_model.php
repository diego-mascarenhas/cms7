<?php defined('BASEPATH') or exit('No direct script access allowed');


class Smtp_model extends CI_Model {

	public function getServidores($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_servidores.id, emailer_servidores.host, emailer_servidores.envios, emailer_servidores.errores, emailer_servidores.mailq, emailer_servidores.fecha, emailer_servidores.estado AS id_estado,
				
					CASE
					   WHEN emailer_servidores.estado = 1 THEN 'Inactivo'
					   WHEN emailer_servidores.estado = 2 THEN 'Activo'
					   WHEN emailer_servidores.estado = 3 THEN 'Offline'
					   WHEN emailer_servidores.estado = 4 THEN 'Online'
					   WHEN emailer_servidores.estado = 5 THEN 'Pausado'
					END AS estado,
					
					CASE
					   WHEN emailer_servidores.estado = 1 THEN 'label-plain'
					   WHEN emailer_servidores.estado = 2 THEN 'label-warning'
					   WHEN emailer_servidores.estado = 3 THEN 'label-danger'
					   WHEN emailer_servidores.estado = 4 THEN 'label-primary'
					   WHEN emailer_servidores.estado = 5 THEN 'label-warning'
					END AS estado_ui_class
					
				FROM emailer_servidores
				
				WHERE 1
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			//$sql .= " AND emailer_servidores.grupo = ?";
			//$placeholders[] = $this->usuario->grupo;
			
/*
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_servidores.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
*/
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			//$sql .= " AND emailer_servidores.grupo = ?";
			//$placeholders[] = $this->usuario->grupo;
			
			//$sql .= " AND emailer_servidores.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_servidores.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND emailer_servidores.estado > 0";
			}
			
			if (!empty($parametros['mailq']))
			{
				$sql .= " AND emailer_servidores.mailq > ?";
				$placeholders[] = $parametros['mailq'];
			}
			elseif (!empty($parametros['mailq_menor']))
			{
				$sql .= " AND emailer_servidores.mailq < ?";
				$placeholders[] = $parametros['mailq_menor'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (emailer_servidores.id REGEXP '" . $value . "'";
				$sql .= " OR emailer_servidores.host REGEXP '" . $value . "'";
				$sql .= " OR emailer_servidores.user REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (emailer_servidores.id LIKE '%" . $value . "%'";
				$sql .= " OR emailer_servidores.host LIKE '%" . $value . "%'";
				$sql .= " OR emailer_servidores.user LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_servidores.mailq";
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
	
	
	public function getServidorDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT *
		
					FROM emailer_servidores
				";
		}
		else
		{
			$sql = "
					SELECT emailer_servidores.id, emailer_servidores.host, emailer_servidores.envios, emailer_servidores.errores, emailer_servidores.mailq, emailer_servidores.user, emailer_servidores.pass, emailer_servidores.puerto, emailer_servidores.seguridad, emailer_servidores.fecha,
				
					CASE
					   WHEN emailer_servidores.estado = 1 THEN 'Inactivo'
					   WHEN emailer_servidores.estado = 2 THEN 'Activo'
					   WHEN emailer_servidores.estado = 3 THEN 'Offline'
					   WHEN emailer_servidores.estado = 4 THEN 'Online'
					   WHEN emailer_servidores.estado = 5 THEN 'Pausado'
					END AS estado,
					
					CASE
					   WHEN emailer_servidores.estado = 1 THEN 'label-plain'
					   WHEN emailer_servidores.estado = 2 THEN 'label-warning'
					   WHEN emailer_servidores.estado = 3 THEN 'label-danger'
					   WHEN emailer_servidores.estado = 4 THEN 'label-primary'
					   WHEN emailer_servidores.estado = 5 THEN 'label-warning'
					END AS estado_ui_class
					
				FROM emailer_servidores
				";
		}
		
		$sql .= "
				WHERE emailer_servidores.id = ?
			";
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
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
	
	
	public function getServidorDetalleRaw($id)
	{
		return $this->getServidorDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getServidorEnvio($id)
	{
		$sql = "
				SELECT 'smtp' AS protocol, emailer_servidores.host AS smtp_host, emailer_servidores.puerto AS smtp_port, emailer_servidores.seguridad AS smtp_crypto, emailer_servidores.user AS smtp_user, emailer_servidores.pass AS smtp_pass, 'html' AS mailtype, 'utf-8' AS charset, 1 AS wordwrap, 1 AS nodebug
				
			FROM emailer_servidores
			
			WHERE emailer_servidores.id = ?
		";
		

		// consulta
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarServidor($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		$data['host'] = $valores['host'];
		$data['user'] = $valores['user'];
		$data['pass'] = $valores['pass'];
		if (isset($valores['seguridad'])) $data['seguridad'] = (!empty($valores['seguridad'])) ? $valores['seguridad'] : null;
		if (isset($valores['puerto'])) $data['puerto'] = (!empty($valores['puerto'])) ? $valores['puerto'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('emailer_servidores', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarServidor($id, $valores)
	{
		if (!empty($valores['host'])) $data['host'] = $valores['host'];
		if (isset($valores['user'])) $data['user'] = (!empty($valores['user'])) ? $valores['user'] : null;
		if (isset($valores['pass'])) $data['pass'] = (!empty($valores['pass'])) ? $valores['pass'] : null;
		if (isset($valores['seguridad'])) $data['seguridad'] = (!empty($valores['seguridad'])) ? $valores['seguridad'] : null;
		if (isset($valores['puerto'])) $data['puerto'] = (!empty($valores['puerto'])) ? $valores['puerto'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		
		if (!isset($res['error']))
		{
			$res = $this->db->update('emailer_servidores', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function probarSmtp($id, $parametros = null)
	{
		$res = $this->getServidorDetalle($id, array('modo'=>'raw'));
		
		$config['smtp'] = array('protocol' => 'smtp',
						'smtp_host' => $res['host'],
						'smtp_port' => $res['puerto'],
						'smtp_crypto' => $res['seguridad'],
						'smtp_user' => $res['user'],
						'smtp_pass' => $res['pass'],
						'mailtype' => 'html',
						'charset' => 'utf-8',
						'wordwrap' => TRUE
						);
						
		$this->load->library('email', $config['smtp']);
		
		
		$this->email->set_newline("\r\n");
		$this->email->from($res['user'], 'CMS+');
		$this->email->to($this->usuario->email, $this->usuario->contacto);
		$this->email->subject('CMS+ Prueba de sistema (' . $res['host'] . ')');
		$this->email->message('Esta es una prueba desde CMS+');
		
		if (!$this->email->send())
		{
			$res['error'] = $this->email->print_debugger();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	public function getSmtpIdFromHost($host)
	{	
		$this->db->select('id');
		$this->db->from('emailer_servidores');
		$this->db->where('host', $host);

		return $this->db->get()->row('id');	
	}
	
	
	public function getSmtpActivos($cantidad)
	{	
		$sql = "
				SELECT emailer_servidores.id
					
				FROM emailer_servidores
				
				WHERE emailer_servidores.estado = 4
				AND emailer_servidores.mailq > ?
			";
		
		$placeholders[] = $cantidad;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		$res = $query->result_array();
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getSmtpPausados($cantidad)
	{	
		$sql = "
				SELECT emailer_servidores.id
					
				FROM emailer_servidores
				
				WHERE emailer_servidores.estado = 5
				AND emailer_servidores.mailq < ?
			";
		
		$placeholders[] = $cantidad;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		$res = $query->result_array();
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarSmtp($id, $valores)
	{
		if (isset($valores['mailq'])) $data['mailq'] = $valores['mailq'];
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha'] = unix_to_human(now(), true, 'eu');
				
		$res = $this->db->update('emailer_servidores', $data, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function sync($id, $valores)
	{
		if (isset($valores['mailq'])) $data['mailq'] = $valores['mailq'];
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
				
		$res = $this->db->update('emailer_servidores', $data, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarServidoresMailq() // DEPRECATED
	{
		$data = $this->getServidores();
		
		foreach ($data as $obj)
		{
			$res = $this->actualizarServidorMailq($obj['host']);
		}
		
		return $res;
	}
	
	
	public function actualizarServidorMailq($ip) // DEPRECATED
	{
		$this->load->library('curl');
		
		$res = $this->curl->simple_get('http://' . $ip . '/stats.json');
		
		return $res;
	}
	
	
	public function actualizarMailq($id, $cantidad)
	{
		$data['mailq'] = $cantidad;
		
		$data['fecha'] = unix_to_human(now(), true, 'eu');
				
		$res = $this->db->update('emailer_servidores', $data, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMailsEnCola()
	{	
		$this->db->select_sum('mailq');
		$this->db->from('emailer_servidores');
		$this->db->where('estado >', 3);

		return $this->db->get()->row('mailq');	
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}