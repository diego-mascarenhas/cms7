<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cupones_model extends CI_Model {

	public function getCupones($parametros = null)
	{
		$sql = "SELECT con_carro_cupones.*, con_estados.estado as tipo_estado";
		$sql .= " FROM con_carro_cupones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_carro_cupones.estado";
		$sql .= " WHERE con_carro_cupones.grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_carro_cupones.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND con_carro_cupones.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['id_contacto']))
		{
			$sql .= " AND con_carro_cupones.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_cupones.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_cupones.estado >= 0";
		}
		$sql .= " ORDER BY con_carro_cupones.fecha_alta DESC, con_carro_cupones.id ASC";

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();

		}
		return (!empty($res)) ? $res : null;
	}

	public function detalleCuponCMS($id, $parametros = null)
	{
		$sql = "SELECT con_carro_cupones.*";
		$sql .= " FROM con_carro_cupones";
		$sql .= " WHERE con_carro_cupones.grupo = ?";
		$sql .= " AND con_carro_cupones.id_empresa = ?";
		$sql .= " AND con_carro_cupones.id = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id;

		if (isset($parametros['estado']))
		{
			$sql .= " AND con_carro_cupones.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_carro_cupones.estado > 0";
		}
		if (!isset($res['error']))
		{
			$query = $this->db->query($sql, $placeholders);
		}
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function ingresarCuponCMS($variables)
	{
		$datos['grupo'] = $this->usuario->grupo;
		$datos['id_empresa'] = $this->usuario->id_empresa;
		if(isset($variables['id_contacto'])) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
		$datos['stock'] = $variables['stock'];
		$datos['cupon'] = trim($variables['cupon']);
		$datos['descuento'] = $variables['descuento'];
		$datos['fecha_vencimiento'] = date('Y-m-d', strtotime($variables['fecha_vencimiento']));
		$datos['estado'] = 3;
		$datos['fecha_alta'] = now();
		$datos['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('con_carro_cupones', $datos);
		$res['id'] = $this->db->insert_id();
			
		return (!empty($res)) ? $res : null;
	}

	public function modificarCupon($variables)
	{
		$datos['grupo'] = $this->usuario->grupo;
		$datos['id_empresa'] = $this->usuario->id_empresa;
		if(isset($variables['id_contacto'])) { $datos['id_contacto'] = $variables['id_contacto']; } else { $datos['id_contacto'] = null; }
		$datos['stock'] = $variables['stock'];
		$datos['cupon'] = trim($variables['cupon']);
		$datos['descuento'] = $variables['descuento'];
		$datos['fecha_vencimiento'] = date('Y-m-d', strtotime($variables['fecha_vencimiento']));
		$datos['estado'] = 3;
		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;

		$where = "id = ".$variables['id'];
		$res = $this->db->update('con_carro_cupones', $datos, $where);
		return (!empty($res)) ? $res : null;
	}
	
	public function detalleRelacionCupon($id)
	{
		$sql = "SELECT con_carro_rel_cupones_pedidos.id";
		$sql .= " FROM con_carro_rel_cupones_pedidos";
		$sql .= " WHERE con_carro_rel_cupones_pedidos.id_cupon = $id";
		$sql .= " ORDER BY con_carro_rel_cupones_pedidos.id ASC LIMIT 1";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}


	public function eliminarCupon($valores)
	{
		$data['estado'] = '-'.$valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id'];
		$res = $this->db->update('con_carro_cupones', $data, $where);
		return $res;
	}
}