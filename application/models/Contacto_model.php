<?php defined('BASEPATH') or exit('No direct script access allowed');


class Contacto_model extends CI_Model {

	public function getContactos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS contactos.id, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, contactos.username, COALESCE(contactos.celular, contactos.telefono, empresas.telefono) AS telefono, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(CONVERT_TZ(contactos.ultima_visita, '+00:00', @@global.time_zone)) AS ultima_visita,
				
					CASE
						WHEN contactos.area_privada = 2 THEN 'Reseller'
						WHEN contactos.area_privada = 3 THEN 'Administrador'
						WHEN contactos.area_privada = 4 THEN 'Usuario'
						WHEN contactos.area_privada = 5 THEN 'Invitado'
						WHEN contactos.area_privada = 6 THEN 'Emailer'
					END AS perfil,
				
					CASE
						WHEN contactos.estado = 1 THEN 'label-plain'
						WHEN contactos.estado = 2 THEN 'label-primary'
						WHEN contactos.estado = 3 THEN 'label-info'
						WHEN contactos.estado = 4 THEN 'label-danger'
						WHEN contactos.estado = 5 THEN 'label-warning'
						WHEN contactos.estado = 6 THEN 'label-success'
					END AS estado_ui_class,
					
					CASE
						WHEN contactos.estado = 1 THEN 'Inactivo'
						WHEN contactos.estado = 2 THEN 'Activo'
						WHEN contactos.estado = 3 THEN 'Online'
						WHEN contactos.estado = 4 THEN 'Bloqueado'
						WHEN contactos.estado = 5 THEN 'Vencido'
						WHEN contactos.estado = 6 THEN 'Sin Confirmar'
					END AS estado
				
				FROM contactos
				LEFT JOIN empresas ON contactos.id_empresa = empresas.id
		
				WHERE contactos.grupo = ?
				AND contactos.area_privada != 6
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
				$sql .= " AND contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND contactos.estado > 0";
			}
			
			if (!empty($parametros['id_empresa']))
			{
				$sql .= " AND contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
			
			if (!empty($parametros['id_perfil']))
			{
				$sql .= " AND contactos.area_privada = ?";
				$placeholders[] = $parametros['id_perfil'];
			}
			else
			{
				$sql .= " AND contactos.area_privada != 6";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (contactos.username REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR contactos.email LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " nombre";
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


	public function getContactoDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *, area_privada AS id_perfil
				FROM contactos
			";
		}
		else
		{
			$sql = "	
					SELECT contactos.id, contactos.nombre, contactos.apellido, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, COALESCE(contactos.celular, contactos.telefono, empresas.telefono) AS telefono, contactos.username, contactos.hash, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(CONVERT_TZ(contactos.ultima_visita, '+00:00', @@global.time_zone)) AS ultima_visita, contactos.ip, contactos.idioma, contactos.timezone,
					
					CASE
					   WHEN contactos.area_privada = 2 THEN 'Reseller'
					   WHEN contactos.area_privada = 3 THEN 'Administrador'
					   WHEN contactos.area_privada = 4 THEN 'Usuario'
					   WHEN contactos.area_privada = 5 THEN 'Invitado'
					   WHEN contactos.area_privada = 6 THEN 'Emailer'
					END AS perfil
					
					FROM contactos
					LEFT JOIN empresas ON contactos.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE contactos.grupo = ?
				AND contactos.estado > 0
				AND contactos.id = ?		
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
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
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
	
	
	public function getContactoDetalleRaw($id)
	{
		return $this->getContactoDetalle($id, array('modo'=>'raw'));
	}


	public function ingresarContacto($valores)
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
		
		if (!empty($valores['email']))
		{
			$data['email'] = $valores['email'];
		}
		else
		{
			$res['error'] = 'Debe especificar un email';
		}
		
		if (isset($valores['nombre'])) $data['nombre'] = (!empty($valores['nombre'])) ? $valores['nombre'] : null;
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		$data['hash'] = md5(uniqid());
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('contactos', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarContacto($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (!empty($valores['nombre'])) $data['nombre'] = $valores['nombre'];
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
				
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('contactos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('contactos', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}


	public function verificarkUsername($username, $id = null)
	{
		$this->db->select('id');
		$this->db->from('contactos');
		$this->db->where('username', trim($username));
		$this->db->where('id !=', $id);
		
		$query = $this->db->get();
		
		if ($query)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				$res = 'El nombre de usuario ya existe';
			}
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getContactosConServiciosActivos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, empresas.telefono, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(CONVERT_TZ(contactos.ultima_visita, '+00:00', @@global.time_zone)) AS ultima_visita,
				
					CASE
						WHEN contactos.area_privada = 2 THEN 'Reseller'
						WHEN contactos.area_privada = 3 THEN 'Administrador'
						WHEN contactos.area_privada = 4 THEN 'Usuario'
						WHEN contactos.area_privada = 5 THEN 'Invitado'
						WHEN contactos.area_privada = 6 THEN 'Emailer'
					END AS perfil,
				
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
				
				FROM servicios
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
		
				WHERE servicios.grupo = ?
				AND empresas.estado > 0
				AND contactos.estado > 0
				AND servicios.estado = ?
				AND servicios.operacion = ?
			
				GROUP BY contactos.id
			";
			
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
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
			$placeholders[] = (!empty($parametros['estado'])) ? $parametros['estado'] : 4;
			$placeholders[] = (!empty($parametros['operacion'])) ? $parametros['estado'] : 'V';
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (servicios.descripcion REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " contactos.ultima_visita";
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
	
	
	public function comboContactos($parametros = null)
	{
		$combo = null;
		
		$sql = "	
				SELECT contactos.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto
				
				FROM contactos
				LEFT JOIN empresas ON contactos.id_empresa = empresas.id
		
				WHERE contactos.grupo = ?
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
				$sql .= " AND contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND (contactos.estado = 2 OR contactos.estado = 3)";
			}
			
			if (!empty($parametros['id_empresa']))
			{
				$sql .= " AND contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
			
			if (!empty($parametros['id_perfil']))
			{
				$sql .= " AND contactos.area_privada = ?";
				$placeholders[] = $parametros['id_perfil'];
			}
			else
			{
				$sql .= " AND contactos.area_privada != 6";
			}
			
			if (!empty($parametros['username']))
			{
				$sql .= " AND contactos.username IS NOT NULL";
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
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['contacto'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeContactosActivos()
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM contactos WHERE (estado = 2 OR estado = 3) AND grupo = ?";
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
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
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeContactosOnline()
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM contactos WHERE estado = 3 AND grupo = ?";
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
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
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarContacto($id)
	{
		$sql = "
				SELECT contactos.id AS id_contacto, contactos.username, contactos.hash
				
				FROM contactos
				
				WHERE contactos.id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function crearHash()
	{
		$sql = "
				UPDATE contactos

				SET hash = MD5(username)
			
				WHERE username IS NOT NULL 
				AND hash IS NULL
			";
		
		$query = $this->db->query($sql);
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarHash($id, $hash)
	{
		$sql = "
				UPDATE contactos

				SET hash = MD5(?)
			
				WHERE id = ?
			";
		
		$query = $this->db->query($sql, array($hash, $id));
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}