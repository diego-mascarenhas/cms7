<?php defined('BASEPATH') or exit('No direct script access allowed');


class Contabilidad_model extends CI_Model {

	public function getMovimientos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS movimientos.id, movimientos.fecha AS fecha_movimiento, movimientos.id_empresa, movimientos.valor, empresas.empresa, formas_pago.forma_pago, formas_pago.id AS id_forma_pago, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0')) AS comprobante, cuentas.nombre_cuenta AS cuenta, movimientos.id_factura, sys_monedas.simbolo, movimientos.estado AS id_estado, facturas.fecha AS fecha_factura,

					CASE
					   WHEN movimientos.estado = 1 THEN 'label-warning'
					   WHEN movimientos.estado = 2 THEN 'label-primary'
					   WHEN movimientos.estado = 3 THEN 'label-warning'
					   WHEN movimientos.estado = 4 THEN 'label-danger'
					   WHEN movimientos.estado = 5 THEN 'label-info'
					   WHEN movimientos.estado = 6 THEN 'label-danger'
					   WHEN movimientos.estado = 7 THEN 'label-info'
					   WHEN movimientos.estado = 8 THEN 'label-warning'
					   WHEN movimientos.estado = 9 THEN 'label-warning'
					   WHEN movimientos.estado = 10 THEN 'label-danger'
					   WHEN movimientos.estado = 11 THEN 'label-danger'
					   WHEN movimientos.estado = 12 THEN 'label-danger'
					   WHEN movimientos.estado = 13 THEN 'label-danger'
					END AS estado_ui_class,
					
					
					CASE
					   WHEN movimientos.estado = 1 THEN 'En progreso'
					   WHEN movimientos.estado = 2 THEN 'Aprobado'
					   WHEN movimientos.estado = 3 THEN 'Pendiente'
					   WHEN movimientos.estado = 4 THEN 'Rechazado'
					   WHEN movimientos.estado = 5 THEN 'Reintegrado'
					   WHEN movimientos.estado = 6 THEN 'Cancelado'
					   WHEN movimientos.estado = 7 THEN 'En mediación'
					   WHEN movimientos.estado = 8 THEN 'Devuelto'
					   WHEN movimientos.estado = 9 THEN 'Falta de fondos'
					   WHEN movimientos.estado = 10 THEN 'Cuenta cerrada'
					   WHEN movimientos.estado = 11 THEN 'Cuenta inexistente'
					   WHEN movimientos.estado = 12 THEN 'Baja del servicio'
					   WHEN movimientos.estado = 13 THEN 'Sin especificar'
					END AS estado,
					
					CASE
					   WHEN movimientos.transaccion = 'G' THEN 'gasto'
					   WHEN movimientos.transaccion = 'I' THEN 'ingreso'
					END AS operacion,
					
					CASE
					   WHEN movimientos.transaccion = 'G' THEN 'badge-danger'
					   WHEN movimientos.transaccion = 'I' THEN 'badge-success'
					END AS operacion_ui_class
					
				FROM movimientos
				LEFT JOIN empresas ON movimientos.id_empresa = empresas.id
				LEFT JOIN cuentas ON movimientos.id_cuenta = cuentas.id
				LEFT JOIN formas_pago ON movimientos.id_forma_pago = formas_pago.id
				LEFT JOIN facturas ON movimientos.id_factura = facturas.id
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN movimientos_estado ON movimientos_estado.id = movimientos.estado
				LEFT JOIN sys_monedas ON cuentas.id_moneda = sys_monedas.id
				
				WHERE movimientos.grupo = ?
				AND facturas.saldo > 0
			";
	
	
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND movimientos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND movimientos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND movimientos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND movimientos.estado > 0";
			}
			
			if (!empty($parametros['id_factura']))
			{
				$sql .= " AND movimientos.id_factura = ?";
				$placeholders[] = $parametros['id_factura'];
			}
			
			if (!empty($parametros['id_forma_pago']))
			{
				$sql .= " AND movimientos.id_forma_pago = ?";
				$placeholders[] = $parametros['id_forma_pago'];
			}
			
			if (!empty($parametros['desde']))
			{
				$sql .= " AND facturas.fecha >= ?";
				$placeholders[] = $parametros['desde'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (facturas.numero_factura REGEXP '" . $value . "'";
				$sql .= " OR movimientos.id_externo REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (facturas.numero_factura LIKE '%" . $value . "%'";
				$sql .= " OR movimientos.id_externo LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : "  movimientos.fecha DESC, movimientos.id";
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
		
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}
	
	
}