<?php defined('BASEPATH') or exit('No direct script access allowed');


class Movimiento_model extends CI_Model {

	public function getMovimientos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS movimientos.id, UNIX_TIMESTAMP(movimientos.fecha) AS fecha, movimientos.id_empresa, movimientos.valor, empresas.empresa, formas_pago.forma_pago, formas_pago.id AS id_forma_pago, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0')) AS comprobante, cuentas.nombre_cuenta AS cuenta, movimientos.id_factura, sys_monedas.simbolo, movimientos.estado AS id_estado,

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
					   WHEN movimientos.estado = 20 THEN 'label-danger'
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
					   WHEN movimientos.estado = 20 THEN 'Moneda diferente'
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
	
	
	public function getMovimientoDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT movimientos.*, UNIX_TIMESTAMP(movimientos.fecha) AS fecha
					
					FROM movimientos
					LEFT JOIN empresas ON movimientos.id_empresa = empresas.id
					
					WHERE movimientos.grupo = ?
					AND movimientos.id = ?
				";
		}
		else
		{
			$sql = "	
					SELECT movimientos.id, UNIX_TIMESTAMP(movimientos.fecha) AS fecha, movimientos.id_empresa, movimientos.valor, empresas.empresa, formas_pago.forma_pago, formas_pago.id AS id_forma_pago, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(facturas.numero_factura, 8, '0')) as comprobante, cuentas.nombre_cuenta AS cuenta, movimientos.id_factura, sys_monedas.simbolo, movimientos.estado AS id_estado,
	
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
						   WHEN movimientos.estado = 20 THEN 'label-danger'
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
						   WHEN movimientos.estado = 20 THEN 'Moneda diferente'
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
					AND movimientos.id = ?
				";
		}
		
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
			$sql .= " AND movimientos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getMovimientoDetalleRaw($id)
	{
		return $this->getMovimientoDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarMovimiento($valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		$data['id_empresa'] = (!empty($valores['id_empresa'])) ? ($valores['id_empresa'] == 'transferencia') ? null : $valores['id_empresa'] : $this->usuario->id_empresa;
		
		$data['id_factura'] = (!empty($valores['id_factura'])) ? $valores['id_factura'] : null;
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['transaccion'])) $data['transaccion'] = (!empty($valores['transaccion'])) ? $valores['transaccion'] : null;
		
		$data['valor'] = $valores['valor'];
		$data['fecha'] = (!empty($valores['fecha'])) ? date('Y-m-d', strtotime($valores['fecha'])) : date('Y-m-d', strtotime('today UTC'));
		$data['id_cuenta'] = $valores['id_cuenta'];
		$data['id_externo'] = (!empty($valores['id_externo'])) ? $valores['id_externo'] : null;
		$data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 2;

		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = (!empty($valores['username_alta'])) ? $valores['username_alta'] : $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$this->db->insert('movimientos', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarMovimiento($id, $valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		
		if (isset($valores['id_factura'])) $data['id_factura'] = (!empty($valores['id_factura'])) ? $valores['id_factura'] : null;
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['transaccion'])) $data['transaccion'] = (!empty($valores['transaccion'])) ? $valores['transaccion'] : null;
		
		if (isset($valores['valor'])) $data['valor'] = $valores['valor'];
		if (isset($valores['fecha'])) $data['fecha'] = date('Y-m-d', strtotime($valores['fecha']));
		if (isset($valores['id_cuenta'])) $data['id_cuenta'] = $valores['id_cuenta'];
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];

		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = (!empty($valores['username_modificacion'])) ? $valores['username_modificacion'] : $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$res = $this->db->update('movimientos', $data, array('id'=>$id, 'grupo'=>$data['grupo']));
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMovimientosMensuales($parametros = null)
	{
		$sql = "
				SELECT DATE_FORMAT(fecha_alta, '%Y-%m') AS fecha, SUM(valor) AS valor
				
				FROM movimientos
				
				WHERE grupo = ?
				AND movimientos.id_empresa = ?
				AND movimientos.estado = 2
				
				AND fecha_alta >= DATE(NOW()) - INTERVAL ? MONTH
				
				GROUP BY YEAR(fecha_alta), MONTH(fecha_alta)
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$placeholders[] = $parametros['id_empresa'];
			}
			else
			{
				$placeholders[] = $this->usuario->id_empresa;
			}
			
			$placeholders[] = (isset($parametros['intervalo'])) ? $parametros['intervalo'] : 12;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$placeholders[] = $this->usuario->id_empresa;
			
			$placeholders[] = (isset($parametros['intervalo'])) ? $parametros['intervalo'] : 12;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
	
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getBalance($parametros = null)
	{

		$sql = "
				SELECT facturas.id, facturas.id AS id_factura, 'FACT' AS tipo, UNIX_TIMESTAMP(facturas.fecha) AS fecha, facturas.operacion, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', IF(facturas.numero_factura, lpad(facturas.numero_factura, 8, '0'), '********')) AS comprobante, facturas.id_forma_pago AS id_forma_pago, 'Factura' AS descripcion, facturas.total_neto AS valor, facturas.saldo, facturas.estado, IF((SELECT id FROM facturas AS nota WHERE nota.padre = facturas.id), true, false) AS tiene_nota, facturas_tipo.id_afip, sys_monedas.simbolo
				
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				
				WHERE facturas.grupo = ?
				AND empresas_fiscales.id_empresa = ?
				AND (facturas.estado = 2 OR facturas.estado = 4)
				
				UNION
				
				SELECT movimientos.id, movimientos.id_factura, 'MOV' AS tipo, UNIX_TIMESTAMP(movimientos.fecha) AS fecha, movimientos.transaccion AS operacion,
				IF(movimientos.id_factura, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', IF(facturas.numero_factura, lpad(facturas.numero_factura, 8, '0'), '********')) , NULL) AS comprobante,
				
				movimientos.id_forma_pago AS id_forma_pago, formas_pago.forma_pago AS descripcion, movimientos.valor, NULL AS saldo, movimientos.estado, NULL AS tiene_nota, NULL AS id_afip, sys_monedas.simbolo
				
				FROM movimientos
				LEFT JOIN formas_pago ON movimientos.id_forma_pago = formas_pago.id
				LEFT JOIN cuentas ON movimientos.id_cuenta = cuentas.id
				LEFT JOIN sys_monedas ON cuentas.id_moneda = sys_monedas.id
				
				LEFT JOIN facturas ON movimientos.id_factura = facturas.id
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				
				WHERE movimientos.grupo = ?
				AND movimientos.id_empresa = ?
				AND movimientos.estado = 2
				
				ORDER BY fecha ASC, tipo ASC, id ASC
			";
			
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$placeholders[] = $parametros['id_empresa'];
			}
			else
			{
				$placeholders[] = $this->usuario->id_empresa;
			}
			
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$placeholders[] = $parametros['id_empresa'];
			}
			else
			{
				$placeholders[] = $this->usuario->id_empresa;
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$placeholders[] = $this->usuario->id_empresa;
			
			$placeholders[] = $this->usuario->grupo;
			
			$placeholders[] = $this->usuario->id_empresa;
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


		$subtotal = null;
		$color_operacion = null;
		$items = null;

		foreach ($query->result_array() AS $row)
		{
			// Nota de crédito de factura de venta (Contempla viejas de CMS5)
			if ($row['operacion'] == 'V' && ($row['estado'] == 4 || $row['id_afip'] == 3 || $row['id_afip'] == 8))
			{
				$subtotal += $row['valor'];
				$color_operacion = 'text-success';
				$row['descripcion'] = 'Nota de cr&eacute;dito';
			}

			// Factura de venta
			elseif ($row['operacion'] == 'V' && $row['estado'] == 2)
			{
				$subtotal -= $row['valor'];
				$color_operacion = 'text-dark';
				$row['descripcion'] = 'Factura';
			}

			// Ingreso del cliente
			elseif ($row['operacion'] == 'I')
			{
				$subtotal += $row['valor'];
				$color_operacion = 'text-dark';
			}

			// Gasto del cliente
			elseif ($row['operacion'] == 'G')
			{
				$subtotal -= $row['valor'];
				$color_operacion = 'text-danger';
			}

			// Nota de crédito de factura de venta (Contempla viejas de CMS5)
			elseif ($row['operacion'] == 'C' && ($row['estado'] == 4 || $row['id_afip'] == 3 || $row['id_afip'] == 8))
			{
				$subtotal -= $row['valor'];
				$color_operacion = 'text-danger';
				$row['descripcion'] = 'Nota de cr&eacute;dito de venta';
			}
			
			// Factura de compra
			elseif ($row['operacion'] == 'C' && $row['estado'] == 2)
			{
				$subtotal += $row['valor'];
				$color_operacion = 'text-danger';
				$row['descripcion'] = 'Factura de compra';
			}

			if (number_format($subtotal, 2) == '-0.00') : $subtotal = 0; endif;

			if ($subtotal >= 0) : $subtotal_class = 'text-primary'; elseif ($subtotal < 0) : $subtotal_class = 'text-danger'; else : $subtotal_class = null; endif;

			/* Items de facturas */
			if ($row['tipo'] == 'FACT')
			{
				$sql_items = "SELECT id, id_factura, descripcion, valor, descuento";
				$sql_items .= " FROM facturas_items";
				$sql_items .= " WHERE id_factura = ?";

				$query = $this->db->query($sql_items, array(
						$row['id']
					));

				if ($query)
				{
					$items = $query->result_array();
				}
			}

			else
			{
				$items = array();
			}
			
			$estado = (!$row['tiene_nota'] && $row['saldo'] > 0) ? 1 : 2;

			$res[] = array(	'fecha'=>$row['fecha'],
							'id'=>$row['id'],
							'tipo'=>$row['tipo'],
							'id_factura'=>$row['id_factura'],
							'comprobante'=>$row['comprobante'],
							'id_forma_pago'=>$row['id_forma_pago'],
							'descripcion'=>$row['descripcion'],
							'tiene_nota'=>$row['tiene_nota'],
							'operacion'=>$row['operacion'],
							'operacion_ui_class'=>$color_operacion,
							'simbolo'=>$row['simbolo'],
							'valor'=>number_format($row['valor'], 2, ',', '.'),
							'saldo'=>$row['saldo'],
							'subtotal'=>number_format($subtotal, 2, ',', '.'),
							'subtotal_ui_class'=>$subtotal_class,
							'estado'=>$estado,
							'items'=>$items
						);
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getIngresosDeHoy()
	{
		$sql = "SELECT SUM(valor) AS valor FROM movimientos WHERE grupo = ? AND transaccion = 'I' AND DATE_FORMAT(fecha_alta, '%Y-%m-%d') = CURDATE() AND estado = 2";
		
		// consulta
		$query = $this->db->query($sql, array($this->usuario->grupo));
		
		if ($query)
		{
			$row = $query->row_array();
			
			$res = ($row['valor']) ? $row['valor'] : 0;
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getMonedaCambio($id)
	{
		$sql = "SELECT cambio FROM sys_monedas WHERE id = ?";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		if ($query)
		{
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarkPagoExterno($username, $id)
	{
		$sql = "
				SELECT true
				
				FROM movimientos
				
				WHERE username_alta = ?
				AND movimientos.id_externo = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array(
				$username,
				$id
			));


		if ($query)
		{
			$res = $query->row();
		}

		return (!empty($res)) ? true : false;
	}
	
	
	public function getEstadoIdFromEstado($estado)
	{
		switch ($estado)
		{
			case 'in_process':
			case 'Created':
				$estado = 1;
				break;
			case 'approved':
			case 'Completed':
			case 'Processed':
			case 'Voided':
				$estado = 2;
				break;
			case 'pending':
			case 'Pending':
				$estado = 3;
				break;
			case 'rejected':
			case 'Denied':
				$estado = 4;
				break;
			case 'refunded':
			case 'Refunded':
				$estado = 5;
				break;
			case 'cancelled':
			case 'Canceled_Reversal':
				$estado = 6;
				break;
			case 'in_mediation':
				$estado = 7;
				break;
			case 'charged_back':
			case 'Reversed':
				$estado = 8;
				break;
			case 'falta_de_fondos':
				$estado = 9;
				break;
			case 'cuenta_cerrada':
				$estado = 10;
				break;
			case 'cuenta_inexistente':
				$estado = 11;
				break;
			case 'baja_del_servicio':
				$estado = 12;
				break;
			case 'moneda_diferente':
				$estado = 20;
				break;	
			case 'Expired':
				$estado = 14;
				break;
			case 'Failed':
				$estado = 15;
				break;
			default:
				$estado = 13;
				break;
		}
		
		return $estado;
	}
	
	
	public function getMovimientoIdFromIdExterno($id_externo)
	{	
		$sql = "
				SELECT movimientos.id
				
				FROM movimientos
				
				WHERE movimientos.id_externo = ?
			";

		// consulta
		$query = $this->db->query($sql, array(
				$id_externo
			));


		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getEstado($id)
	{
		$sql = "SELECT estado FROM movimientos WHERE id = ?";
		
		$placeholders[] = $id;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['estado'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function conciliarPago($id)
	{
		$data['estado'] = $this->getEstado($id);
		
		if ($data['estado'] != 2)
		{
			$data['estado'] = 2;
			
			$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$data['username_modificacion'] = (!empty($valores['username_modificacion'])) ? $valores['username_modificacion'] : $this->usuario->username;
		}
		
		$res = $this->db->update('movimientos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo)); // CORREGIR PERMISOS

		return (!empty($res)) ? $res : null;
	}
	
	
	function convertir_moneda($id, $valor)
	{
		$res = $this->db->update('sys_monedas', array('cambio'=>$valor), array('id'=>$id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboCuentas($parametros = null)
	{
		$combo = null;
		
		$sql = "	
				SELECT cuentas.id, cuentas.nombre_cuenta AS valor
				
				FROM cuentas
		
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
				$sql .= " AND cuentas.estado > 1";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " cuentas.nombre_cuenta";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
		
		
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['valor'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function generarDebito()
	{	
		$sql = "
				SELECT empresas.codigo, 
					   empresas.empresa, 
					   empresas.id AS id_empresa, 
					   cuentas.cbu26 as cbu, 
					   UNIX_TIMESTAMP(CONVERT_TZ(facturas.fecha, '-03:00', @@global.time_zone)) AS fecha, 
					   COUNT(facturas.id) AS cantidad, 
					   SUM(IF(nota.total_neto, facturas.saldo-nota.total_neto, facturas.saldo)) AS saldo
				
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN cuentas ON cuentas.id_empresa = empresas.id
				LEFT JOIN facturas AS nota ON nota.padre = facturas.id
				
				WHERE 1
				#AND facturas.grupo = ?
				AND empresas.estado > 0
				AND facturas.operacion = 'V'
				AND (facturas.id_forma_pago = 5 OR facturas.id_forma_pago = 15)
				AND facturas.total_neto <= facturas.saldo
				AND facturas.estado = 2
				AND facturas.id NOT IN (SELECT id FROM facturas WHERE nota.padre = facturas.id)
				AND MONTH(facturas.fecha) = MONTH(CURRENT_DATE())  -- Solo facturas del mes actual
				AND YEAR(facturas.fecha) = YEAR(CURRENT_DATE())    -- Del año actual
				
				GROUP BY empresas.codigo, facturas.id_moneda
				ORDER BY empresas.codigo ASC
			";

		
		// consulta
		$query = $this->db->query($sql);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function totalDebito()
	{	
		$sql = "
				SELECT SUM(IF(nota.total_neto, facturas.saldo-nota.total_neto, facturas.saldo)) AS total
				
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN facturas AS nota ON nota.padre = facturas.id
				
				WHERE 1
				#AND facturas.grupo = ?
				AND empresas.estado > 0
				AND facturas.operacion = 'V'
				AND (facturas.id_forma_pago = 5 OR facturas.id_forma_pago = 15)
				AND facturas.total_neto <= facturas.saldo
				AND facturas.estado = 2
				AND facturas.id NOT IN (SELECT id FROM facturas WHERE nota.padre = facturas.id)
				AND MONTH(facturas.fecha) = MONTH(CURRENT_DATE())  -- Solo facturas del mes actual
				AND YEAR(facturas.fecha) = YEAR(CURRENT_DATE())    -- Del año actual
			";

		
		// consulta
		$query = $this->db->query($sql);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['total'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function totalCuentas()
	{	
		$sql = "
				SELECT SUM(movimientos.valor)

					- (SELECT SUM(movimientos.valor)
						FROM movimientos, cuentas AS cuentas_aux
						WHERE 1
						AND movimientos.id_cuenta =  cuentas_aux.id
						AND movimientos.transaccion = 'G'
						AND cuentas_aux.estado = 2
						AND movimientos.estado = 2
						AND movimientos.id_cuenta = cuentas.id
					) AS total,
				
				
				cuentas.nombre_cuenta AS cuenta, movimientos.id_cuenta
				
				FROM movimientos, cuentas
				
				WHERE movimientos.grupo = ?
				AND movimientos.id_cuenta =  cuentas.id
				AND movimientos.transaccion = 'I'
				AND cuentas.estado = 2
				AND movimientos.estado = 2
				
				GROUP BY movimientos.id_cuenta
			";

		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function totalIva()
	{	
		$sql = "
				SELECT SUM(facturas.IMP105)+SUM(facturas.IMP210)+SUM(facturas.IMP270) AS iva,
				
					CASE
					   WHEN facturas.operacion = 'C' THEN 'Compra'
					   WHEN facturas.operacion = 'V' THEN 'Venta'
					END AS operacion

				FROM facturas

				WHERE facturas.grupo = ?
				AND DATE_FORMAT(facturas.fecha_presentacion, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')

				GROUP BY facturas.operacion
			";

		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function totalHasta($id_cuenta, $transaccion = 'I', $hasta = null)
	{	
		$sql = "
				SELECT SUM(valor) AS total
				
				FROM movimientos
				
				WHERE movimientos.grupo = ?
				AND movimientos.id_cuenta = ?
				AND movimientos.estado = 2
			";

		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders[] = $id_cuenta;
			
			// filtros
			if ($transaccion == 'I')
			{
				$sql .= " AND movimientos.transaccion = 'I'";
			}
			else
			{
				$sql .= " AND movimientos.transaccion = 'G'";
			}
			
			if (!empty($hasta))
			{
				$sql .= " AND movimientos.fecha < '$hasta'";
			}
			
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['total'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function reporteCuenta($id_cuenta, $desde, $hasta)
	{	
		$sql = "
				SELECT movimientos.id, UNIX_TIMESTAMP(movimientos.fecha) AS fecha, empresas.empresa, movimientos.id_empresa, movimientos.transaccion, movimientos.valor, movimientos.observaciones, movimientos.estado, movimientos.username_alta, movimientos.username_modificacion, movimientos.estado AS id_estado,
				
					CASE
					   WHEN movimientos.estado = 1 THEN 'label-warning'
					   WHEN movimientos.estado = 2 THEN 'label-primary'
					   WHEN movimientos.estado = 3 THEN 'label-warning'
					   WHEN movimientos.estado = 4 THEN 'label-danger'
					   WHEN movimientos.estado = 5 THEN 'label-info'
					   WHEN movimientos.estado = 6 THEN 'label-danger'
					   WHEN movimientos.estado = 7 THEN 'label-info'
					   WHEN movimientos.estado = 8 THEN 'label-warning'
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
					END AS estado
				
				FROM movimientos
				LEFT JOIN empresas ON movimientos.id_empresa = empresas.id
				
				WHERE movimientos.grupo = ?
				AND movimientos.id_cuenta = ?
				AND movimientos.fecha BETWEEN '$desde' AND '$hasta'
				AND movimientos.estado > 0
				
				ORDER BY movimientos.fecha, movimientos.id ASC
			";

		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			$placeholders[] = $id_cuenta;
			
			// filtros
			if (!empty($hasta))
			{
			//	$sql .= " AND movimientos.fecha < '$hasta'";
			}
			
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($id_externo)
	{
		$sql = "
				SELECT movimientos.id_externo
				
				FROM movimientos
				
				WHERE movimientos.id_externo = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id_externo));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	public function getIdUltimoMovimiento($parametros = null)
	{
		$sql = " 	
				SELECT movimientos.id
				
				FROM movimientos
				
				WHERE movimientos.grupo = ?
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
		
		// orden
		$sql .= " ORDER BY id DESC";
		
			
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
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}
	
	
}