<?php defined('BASEPATH') or exit('No direct script access allowed');

class Contacto_model extends CI_Model {

	public function getContactos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS contactos.id, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, contactos.estado as id_estado, contactos.username, COALESCE(contactos.celular, contactos.telefono, empresas.telefono) AS telefono, empresas.id AS id_empresa, empresas.empresa, UNIX_TIMESTAMP(CONVERT_TZ(contactos.ultima_visita, '+00:00', @@global.time_zone)) AS ultima_visita, contactos_extras.tipo_contacto, contactos_extras.razon_social,				
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
				LEFT JOIN contactos_extras ON contactos_extras.id_contacto = contactos.id
				WHERE contactos.grupo = ?
				AND contactos.area_privada != 6
			";
		
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
						
			if (!empty($parametros['tipo']))
			{
				$sql .= " AND contactos_extras.tipo_contacto = ?";
				$placeholders[] = $parametros['tipo'];
			}
			else
			{
				$sql .= " AND contactos_extras.tipo_contacto < 1";
			}

			if (!empty($parametros['id_contacto_padre']))
			{
				$sql .= " AND contactos_extras.id_contacto_padre = ?";
				$placeholders[] = $parametros['id_contacto_padre'];
			}

			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " nombre";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
/*
			$sql .= " LIMIT ?, ?";
			$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
			$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
*/
			
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function verificarSiExiste($email, $estado = null)
	{
		$sql = "SELECT id, username, estado FROM contactos";
		$sql .= " WHERE grupo = ?";
		$sql .= " AND id_empresa = ?"; 
		$sql .= " AND area_privada = 5"; 
		$sql .= " AND email = ?"; 
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $email;

		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
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
		if (isset($valores['avatar'])) $data['avatar'] = (!empty($valores['avatar'])) ? $valores['avatar'] : null;
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
		$data['username_alta'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('contactos', $data);
			$res = $this->db->insert_id();
			
			if($res)
			{
				$data1['fecha_alta'] = unix_to_human(now(), true, 'eu');
				$data1['username_alta'] = $this->usuario->id;
				$data1['id_contacto'] = $res;
				if (isset($valores['tipo_contacto'])) $data1['tipo_contacto'] = (!empty($valores['tipo_contacto'])) ? $valores['tipo_contacto'] : 0;
				if (isset($valores['id_contacto_padre'])) $data1['id_contacto_padre'] = (!empty($valores['id_contacto_padre'])) ? $valores['id_contacto_padre'] : null;
				if (isset($valores['empresa'])) $data1['razon_social'] = (!empty($valores['empresa'])) ? $valores['empresa'] : null;
				if (isset($valores['documento'])) $data1['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
				if (isset($valores['domicilio'])) $data1['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
				if (isset($valores['codigo_postal'])) $data1['codigo_postal'] = (!empty($valores['codigo_postal'])) ? $valores['codigo_postal'] : null;
				if (isset($valores['localidad'])) $data1['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
				if (isset($valores['provincia'])) $data1['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
				$insert = $this->db->insert('contactos_extras', $data1);
			}
		}
		return (!empty($res)) ? $res : null;
	}

	public function modificarContacto($valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		if (!empty($valores['nombre'])) $data['nombre'] = $valores['nombre'];
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;
		if (isset($valores['avatar'])) $data['avatar'] = (!empty($valores['avatar'])) ? $valores['avatar'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
		if (isset($valores['data'])) $data['data'] = (!empty($valores['data'])) ? $valores['data'] : null;
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->id;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('contactos', $data, array('id'=>$valores['id'], 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('contactos', $data, array('id'=>$valores['id'], 'id_empresa'=>$this->usuario->id_empresa));
		}

		if($res)
		{
			$data1['id_contacto'] = $valores['id'];
			if (isset($valores['empresa'])) $data1['razon_social'] = (!empty($valores['empresa'])) ? $valores['empresa'] : null;
			if (isset($valores['documento'])) $data1['documento'] = (!empty($valores['documento'])) ? $valores['documento'] : null;
			if (isset($valores['domicilio'])) $data1['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
			if (isset($valores['codigo_postal'])) $data1['codigo_postal'] = (!empty($valores['codigo_postal'])) ? $valores['codigo_postal'] : null;
			if (isset($valores['localidad'])) $data1['localidad'] = (!empty($valores['localidad'])) ? $valores['localidad'] : null;
			if (isset($valores['provincia'])) $data1['provincia'] = (!empty($valores['provincia'])) ? $valores['provincia'] : null;
			if (isset($valores['domicilio_entrega'])) $data1['domicilio_entrega'] = (!empty($valores['domicilio_entrega'])) ? $valores['domicilio_entrega'] : null;

			$sql = "SELECT id FROM contactos_extras WHERE id_contacto = ?"; 
			$placeholders[] = $valores['id'];
			$query = $this->db->query($sql, $placeholders);
			$contacto = $query->row_array();
			
			if($contacto['id'])
			{
				$data1['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
				$data1['username_modificacion'] = $this->usuario->id;
				$update = $this->db->update('contactos_extras', $data1, array('id_contacto'=>$valores['id']));
			}
			else
			{
				$data1['fecha_alta'] = unix_to_human(now(), true, 'eu');
				$data1['username_alta'] = $this->usuario->id;
				$insert = $this->db->insert('contactos_extras', $data1);
			}
		}

		return (!empty($res)) ? $res : null;
	}

	public function getTipoContacto($id, $parametros = null)
	{
		$sql = "SELECT contactos_extras.tipo_contacto, contactos_extras.id_contacto_padre FROM contactos_extras";
		$sql .= " LEFT JOIN contactos ON contactos.id = contactos_extras.id_contacto";
		$sql .= " WHERE contactos.grupo = ?";
		$placeholders[] = $this->usuario->grupo;
		$sql .= " AND contactos.id_empresa = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			if (isset($parametros['id_empresa']))
			{
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND contactos.area_privada = 5"; 
		$sql .= " AND contactos.id = ?"; 
		$placeholders[] = $id;
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		
		return (!empty($res)) ? $res : null;
	}

	public function detalleContacto($id, $parametros = null)
	{
		$sql = "SELECT contactos.id, contactos.nombre, contactos.apellido, contactos.email, contactos.avatar, contactos.celular, contactos.telefono, contactos.telefono, contactos.estado, contactos_extras.razon_social, contactos_extras.tipo_contacto, contactos_extras.domicilio, contactos_extras.localidad, contactos_extras.codigo_postal, contactos_extras.provincia, contactos_extras.documento, contactos_extras.domicilio_entrega FROM contactos";
		$sql .= " LEFT JOIN contactos_extras ON contactos_extras.id_contacto = contactos.id";
		$sql .= " WHERE contactos.grupo = ?";
		$placeholders[] = $this->usuario->grupo;
		$sql .= " AND contactos.id_empresa = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			if (isset($parametros['id_empresa']))
			{
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		$sql .= " AND contactos.area_privada = 5"; 
		$sql .= " AND contactos.id = ?"; 
		$placeholders[] = $id;
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		
		return (!empty($res)) ? $res : null;
	}
	
	public function getIdiomas($parametros = null)
	{
		$sql = "SELECT con_configuracion_idiomas.id, con_configuracion_idiomas.idioma, con_configuracion_idiomas.extension, con_configuracion_idiomas.orden";
		$sql .= " FROM con_configuracion_idiomas";
		$sql .= " WHERE con_configuracion_idiomas.grupo = ?";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_configuracion_idiomas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_configuracion_idiomas.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (!isset($res['error']))
		{
			$sql .= " AND con_configuracion_idiomas.estado = 3";
			$query = $this->db->query($sql, $placeholders);
		}
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	public function eliminarItem($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('contactos', $data, $where);
		return $res;
	}
		
//REVISAR
	public function updateUltimaVisita($variables)
	{
		$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
		$this->db->set('utc_ultima_visita', now());
		$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->db->set('estado', $variables['estado']);
	
		$this->db->where('id', $variables['id']);
		$res = $this->db->update('contactos');

		return (!empty($res)) ? $res : null;
	}
}