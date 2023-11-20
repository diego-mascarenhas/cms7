<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cupones_model extends CI_Model {

	public function listadoContenidos($estado)
	{
		$sql = "SELECT con_car_cupones.*, con_estados.estado as estado_cupon";
		$sql .= " FROM con_car_cupones, con_estados";
		$sql .= " WHERE con_car_cupones.estado > 0";
		$sql .= " AND con_car_cupones.estado = con_estados.id";
		$sql .= " AND con_car_cupones.id_empresa = 7358";
		$sql .= " ORDER BY con_car_cupones.id ASC";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleContenido($id)
	{
		$sql = "SELECT con_car_cupones.*";
		$sql .= " FROM con_car_cupones";
		$sql .= " WHERE con_car_cupones.estado > 0";
		$sql .= " AND con_car_cupones.id = $id";
		$sql .= " AND con_car_cupones.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function ingresarContenido($id)
	{
		$this->load->helper('date');

		$datos['id_empresa'] = 7358;
		$datos['id_contacto'] = 43242;
		$datos['cupon'] = $this->input->post('cupon');
		$datos['descuento'] = $this->input->post('descuento');
		$datos['fecha_vencimiento'] = date('Y-m-d', strtotime($this->input->post('fecha_vencimiento')));
		$datos['stock'] = $this->input->post('stock');
		$datos['estado'] = $this->input->post('estado');
	
		if (empty($this->input->post('id')))
		{
			//INSERTO CONTENIDO
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;
			$insert = $this->db->insert('con_car_cupones', $datos);
			$res['id'] = $this->db->insert_id();
		}
		else
		{
			//MODIFICO CONTENIDO
			$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_car_cupones', $datos, $where);
		}
		return ($res);
	}

	public function duplicarItem($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_car_cupones.*";
		$sql .= " FROM con_car_cupones";
		$sql .= " WHERE con_car_cupones.estado > 0";
		$sql .= " AND con_car_cupones.id = $id";
		$sql .= " AND con_car_cupones.id_empresa = 7358";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_empresa'] = 7358;
			$datos['id_contacto'] = 43242;
			$datos['cupon'] = $res['cupon'].'-copy';
			$datos['descuento'] = $res['descuento'];
			$datos['fecha_vencimiento'] = $res['fecha_vencimiento'];
			$datos['stock'] = $res['stock'];
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_car_cupones', $datos);
			$res2['id'] = $this->db->insert_id();
		}
		return ($res2);
	}

	public function eliminarContenido($id)
	{
		$this->load->helper('date');

		$datos['estado'] = '-3';
		$datos['fecha_modificacion'] = unix_to_human(time(), true, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;

		if (!isset($res['error']))
		{
			$id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_car_cupones', $datos, $where);
		}
		return ($res);
	}
}