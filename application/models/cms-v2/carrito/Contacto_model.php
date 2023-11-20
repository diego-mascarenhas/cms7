<?php defined('BASEPATH') or exit('No direct script access allowed');

class Contacto_model extends CI_Model {

	public function verificarSiExiste($email)
	{
		$sql = "SELECT id, nombre, apellido, telefono FROM contactos";
		$sql .= " WHERE grupo = ?";
		$sql .= " AND id_empresa = ?"; 
		$sql .= " AND area_privada = 5"; 
		$sql .= " AND email = ?"; 
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $email;

		// consulta
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
		if (isset($valores['celular'])) $data['celular'] = (!empty($valores['celular'])) ? $valores['celular'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		$data['hash'] = md5(uniqid());
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
		if (isset($valores['data'])) $data['data'] = (!empty($valores['data'])) ? $valores['data'] : null;

		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $valores['usuario'];

		
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
		if (isset($valores['data'])) $data['data'] = (!empty($valores['data'])) ? $valores['data'] : null;

		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = 'danieldelorbo';
		
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
	
}
