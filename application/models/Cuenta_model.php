<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cuenta_model extends CI_Model {

	public function getCuentas($parametros = null)
	{
		$sql = "
				SELECT cuentas.id, cuentas.titular, cuentas.cbu, cuentas.id_empresa, empresas.empresa, documentos_tipo.documento_tipo, UNIX_TIMESTAMP(CONVERT_TZ(cuentas.fecha_alta, '+00:00', @@global.time_zone)) AS fecha_alta,
				
					CASE
					   WHEN cuentas.estado = 1 THEN 'label-primary'
					   WHEN cuentas.estado = 2 THEN 'label-success'
					END AS estado_ui_class,
					
					CASE
					   WHEN cuentas.estado = 1 THEN 'Cuenta del cliente'
					   WHEN cuentas.estado = 2 THEN 'Cuenta Propia'
					END AS estado
				
				FROM cuentas
				
				LEFT JOIN empresas ON cuentas.id_empresa = empresas.id
				LEFT JOIN documentos_tipo ON cuentas.id_documento_tipo = documentos_tipo.id
				
				WHERE cuentas.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND cuentas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND cuentas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND cuentas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND cuentas.estado > 0";
				$sql .= " AND empresas.estado = 1";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (cuentas.titular REGEXP '" . $value . "'";
				$sql .= " OR cuentas.cbu REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (cuentas.titular LIKE '%" . $value . "%'";
				$sql .= " OR cuentas.cbu LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " empresas.empresa";
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
	
	
	public function getCuentaDetalle($id)
	{
		$sql = "
				SELECT cuentas.id, cuentas.nombre_cuenta AS cuenta, cuentas.titular, cuentas.numero_cuenta, documentos_tipo.documento_tipo, cuentas.numero_documento AS documento, cuentas.cbu, cuentas.cbu26, cuentas_tipo.cuenta_tipo, empresas.empresa, cuentas.id_empresa, bancos.nombre_banco AS banco, cuentas_estado.estado, cuentas.username_alta, UNIX_TIMESTAMP(CONVERT_TZ(cuentas.fecha_alta, '+00:00', @@global.time_zone)) AS fecha_alta, cuentas.username_modificacion, UNIX_TIMESTAMP(CONVERT_TZ(cuentas.fecha_modificacion, '+00:00', @@global.time_zone)) AS fecha_modificacion 
				
				FROM cuentas
				
				LEFT JOIN empresas ON cuentas.id_empresa = empresas.id
				LEFT JOIN bancos ON cuentas.id_banco = bancos.id
				LEFT JOIN cuentas_tipo ON cuentas.id_cuenta_tipo = cuentas_tipo.id
				LEFT JOIN documentos_tipo ON cuentas.id_documento_tipo = documentos_tipo.id
				LEFT JOIN cuentas_estado ON cuentas.estado = cuentas_estado.id
				
				WHERE cuentas.grupo = ?
				AND cuentas.id = ?
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
			$sql .= " AND cuentas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function ingresarCuenta($valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			$data['grupo'] = $this->usuario->grupo;
			$data['id_empresa'] = (isset($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$data['grupo'] = $this->usuario->grupo;
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['titular'] = $valores['titular'];
		$data['numero_cuenta'] = $valores['numero_cuenta'];
		$data['id_documento_tipo'] = $valores['id_documento_tipo'];
		$data['numero_documento'] = $valores['numero_documento'];
		$data['cbu'] = $valores['cbu'];
		$data['cbu26'] = $valores['cbu26'];
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		$insert = $this->db->insert('cuentas', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarCuenta($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			$data['grupo'] = $this->usuario->grupo;
			if (isset($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (isset($valores['titular'])) $data['titular'] = $valores['titular'];
		if (isset($valores['numero_cuenta'])) $data['numero_cuenta'] = $valores['numero_cuenta'];
		if (isset($valores['id_documento_tipo'])) $data['id_documento_tipo'] = $valores['id_documento_tipo'];
		if (isset($valores['numero_documento'])) $data['numero_documento'] = $valores['numero_documento'];
		if (isset($valores['cbu'])) $data['cbu'] = $valores['cbu'];
		if (isset($valores['cbu26'])) $data['cbu26'] = $valores['cbu26'];
		
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;

		$res = $this->db->update('cuentas', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}


	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}