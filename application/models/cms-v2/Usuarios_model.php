<?php defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

	public function listadoUsuarios()
	{
		$sql = "SELECT con_contactos.*, con_contactos_estados.estado as estado_original";
		$sql .= " FROM con_contactos";
		$sql .= " LEFT JOIN con_contactos_estados ON con_contactos_estados.id = con_contactos.estado";
		$sql .= " WHERE con_contactos.estado > 0 AND con_contactos.area_privada > 3 AND id_empresa = 7358";
		$sql .= " AND con_contactos.id_empresa = 7358";
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleUsuario($id)
	{
		$sql = "SELECT con_contactos.* FROM con_contactos WHERE con_contactos.estado > 0 AND con_contactos.id_empresa = 7358 AND con_contactos.id = $id";
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarUsuario($id)
	{
		$this->load->helper('date');

		if (empty($_POST['id']))
		{
			$datos['grupo'] = $this->usuario->grupo;
			$datos['id_empresa'] = $this->usuario->id_empresa;
			$datos['nombre'] = $_POST['nombre'];
			$datos['apellido'] = $_POST['apellido'];
			$datos['domicilio'] = $_POST['domicilio'];
			$datos['codigo_postal'] = $_POST['codigo_postal'];
			$datos['localidad'] = $_POST['localidad'];
			$datos['provincia'] = $_POST['provincia'];
			$datos['pais'] = $_POST['pais'];
			$datos['telefono'] = $_POST['telefono'];
			$datos['celular'] = $_POST['celular'];
			$datos['email'] = trim($_POST['email']);
			$datos['documento'] = $_POST['documento'];
			$datos['username'] = trim($_POST['username']);
			$datos['password'] = md5($_POST['password']);
			$datos['hash'] = md5($_POST['password']);
			$datos['area_privada'] = $_POST['area_privada'];
			if($_POST['area_privada'] == 3)
			$datos['estado'] = $_POST['estado'];
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['username_alta'] = $this->usuario->id;
	
			if (!isset($res['error']))
			{
				$insert = $this->db->insert('con_contactos', $datos);
				$res['id'] = $this->db->insert_id();
			}
		}
		else
		{
			$datos['nombre'] = $_POST['nombre'];
			$datos['apellido'] = $_POST['apellido'];
			$datos['domicilio'] = $_POST['domicilio'];
			$datos['codigo_postal'] = $_POST['codigo_postal'];
			$datos['localidad'] = $_POST['localidad'];
			$datos['provincia'] = $_POST['provincia'];
			$datos['pais'] = $_POST['pais'];
			$datos['telefono'] = $_POST['telefono'];
			$datos['celular'] = $_POST['celular'];
			$datos['email'] = trim($_POST['email']);
			$datos['documento'] = $_POST['documento'];
			$datos['username'] = trim($_POST['username']);
			if ($this->input->post('password'))
			{
				$datos['password'] = md5($_POST['password']);
			}

			$datos['area_privada'] = $_POST['area_privada'];

			$datos['estado'] = $_POST['estado'];
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['username_modificacion'] = $this->usuario->id;
	
			if (!isset($res['error']))
			{
				$id = $_POST['id'];
				$where = "id = $id";
				$res = $this->db->update('con_contactos', $datos, $where);
			}
		}
		return ($res);
	}

	public function listadoPedidos($id)
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.id_contacto, con_car_pedidos.fecha_alta, con_car_pedidos.estado, con_car_pedidos_estados.estado, con_contactos.nombre, con_contactos.apellido FROM con_car_pedidos, con_car_pedidos_estados, con_contactos WHERE con_car_pedidos.estado > 0 AND con_car_pedidos.estado = con_car_pedidos_estados.id AND con_car_pedidos.id_contacto = con_contactos.id";
		$sql .= " AND con_car_pedidos.id_empresa = 7358 AND con_car_pedidos.id_contacto = $id";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	function listadoFavoritos($id)
	{
		$sql = "SELECT con_contenido_items.id_contenido, con_contenido_items.titulo, con_contenidos.imagen";
		$sql .= " FROM con_contenido_items";
		$sql .= " LEFT JOIN con_contenidos ON con_contenidos.id = con_contenido_items.id_contenido";
		$sql .= " LEFT JOIN con_favoritos ON con_favoritos.id_con_contenido = con_contenido_items.id_contenido";
		$sql .= " WHERE con_favoritos.id_con_contacto = $id";
		$sql .= " AND con_contenidos.estado = 3";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function eliminarUsuario($id)
	{
		$this->load->helper('date');

		$sql = "SELECT con_contactos.id, con_contactos.estado FROM con_contactos WHERE con_contactos.id_empresa = 7358 AND con_contactos.id = ".$_POST['id'];
		$query = $this->db->query($sql);
		$res = $query->row_array();

		$datos['estado'] = "-".$res['estado'];		
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
		 	$id = $_POST['id'];
			$where = "id = $id";
			$res = $this->db->update('con_contactos', $datos, $where);
		}
		return ($res);
	}
}