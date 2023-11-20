<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    //TOTAL PEDIDOS
	public function totalPedidos($parametros = null)
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_carro_pedidos";
		$sql .= " WHERE grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado > 0";
		}

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();

		}
		return (!empty($res)) ? $res : null;
	}

    //TOTAL PRODUCTOS
	public function totalProductos($parametros = null)
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_car_productos";
		$sql .= " WHERE grupo = ?";

		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		if (isset($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado >= 0";
		}

		$query = $this->db->query($sql, $placeholders);
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();

		}
		return (!empty($res)) ? $res : null;
	}
}