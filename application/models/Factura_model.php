<?php defined('BASEPATH') or exit('No direct script access allowed');


class Factura_model extends CI_Model {

	public function getFacturas($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS facturas.id, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0')) AS comprobante, IF(facturas.cae_numero, CONCAT('https://cms.revisionalpha.com/pdfs/', md5(CONCAT(facturas.grupo,facturas.id)), '.pdf'), NULL) as link, empresas_fiscales.id as id_empresa_fiscal, empresas_fiscales.razon_social, facturas.fecha AS fecha_real, UNIX_TIMESTAMP(facturas.fecha) AS fecha, facturas.vencimiento AS vencimiento_real, UNIX_TIMESTAMP(facturas.vencimiento) AS vencimiento, facturas.total_neto, facturas.saldo, UNIX_TIMESTAMP(facturas.enviado) AS enviado, UNIX_TIMESTAMP(facturas.recibido) AS recibido, empresas.empresa, empresas.id as id_empresa, formas_pago.forma_pago, facturas.id_forma_pago, sys_monedas.simbolo, facturas.padre,
	
					CASE
						WHEN facturas.estado = 1 THEN 'label-warning'
						WHEN facturas.estado = 2 THEN 'label-primary'
						WHEN facturas.estado = 3 THEN 'label-danger'
						WHEN facturas.estado = 4 THEN 'label-plain'
						WHEN facturas.estado = 5 THEN 'label-plain'
						WHEN facturas.estado = 6 THEN 'label-plain'
						WHEN facturas.estado = 7 THEN 'label-danger'
						WHEN facturas.estado = 8 THEN 'label-info'
					END AS estado_ui_class,
					
					CASE
						WHEN facturas.estado = 1 THEN 'Imprimir'
						WHEN facturas.estado = 2 THEN 'Impresa'
						WHEN facturas.estado = 3 THEN 'Anulada'
						WHEN facturas.estado = 4 THEN 'Nota de crédito'
						WHEN facturas.estado = 5 THEN 'Bonificada'
						WHEN facturas.estado = 6 THEN 'Bonificada (Nota de crédito)'
						WHEN facturas.estado = 7 THEN 'Error'
						WHEN facturas.estado = 8 THEN 'Nueva'
					END AS estado,
					
					CASE
						WHEN facturas.operacion = 'C' THEN 'compra'
						WHEN facturas.operacion = 'V' THEN 'venta'
					END AS operacion,
					
					CASE
						WHEN facturas.operacion = 'C' THEN 'badge-danger'
						WHEN facturas.operacion = 'V' THEN 'badge-success'
					END AS operacion_ui_class
					
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				LEFT JOIN formas_pago ON facturas.id_forma_pago = formas_pago.id
				
				WHERE facturas.grupo = ?
				AND empresas.estado > 1
			";
	
	
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND empresas.id = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND empresas.id = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND facturas.estado = ?";
				$placeholders[] = $parametros['estado'];
				
				// Para nuevas facturas (estado=8) no filtramos por saldo
				if ($parametros['estado'] == 8) {
					$parametros['pendiente'] = false;
				}
			}
			elseif (empty($parametros['search']))
			{
				$sql .= "
						AND (((facturas.estado = 1 OR facturas.estado = 2 OR facturas.estado = 7) AND facturas.saldo > 0) OR facturas.estado = 8)
						AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
						AND facturas.padre IS NULL
					";
			}
			
			if (!empty($parametros['id_empresa_fiscal']))
			{
				$sql .= " AND facturas.id_empresa_fiscal = ?";
				$placeholders[] = $parametros['id_empresa_fiscal'];
			}
			
			if (!empty($parametros['pendiente']))
			{
				$sql .= "
						AND facturas.saldo > 0
						AND (((facturas.estado = 1 OR facturas.estado = 2 OR facturas.estado = 7) AND facturas.saldo > 0) OR facturas.estado = 8)
						AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
						AND facturas.padre IS NULL
					";
			}
			
			// Exclude credit notes (estado = 4)
			if (!empty($parametros['excluir_notas']))
			{
				$sql .= " AND facturas.estado != 4";
			}
			
			if (!empty($parametros['operacion']))
			{
				$sql .= " AND facturas.operacion = ?";
				$placeholders[] = $parametros['operacion'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (facturas.numero_factura REGEXP '" . $value . "'";
				$sql .= " OR empresas_fiscales.razon_social REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (facturas.numero_factura LIKE '%" . $value . "%'";
				$sql .= " OR empresas_fiscales.razon_social LIKE '%" . $value . "%'";
				$sql .= " OR empresas_fiscales.cuit LIKE '%" . $value . "%'";
				$sql .= " OR facturas.total_neto LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " FIELD(facturas.estado,7,8,1,2), facturas.vencimiento, facturas.id";
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


	public function getFacturaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT facturas.*, facturas_tipo.id_afip, facturas_tipo.cuit AS afip_cuit, empresas_fiscales.cuit
					FROM facturas
					LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
					LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
					LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				";
		}
		else
		{
			$sql = " 	
					SELECT SQL_CALC_FOUND_ROWS facturas.id, facturas.grupo, facturas.id_factura_tipo, facturas_tipo.id_afip, facturas_tipo.cuit AS afip_cuit, facturas.operacion, facturas.cae_numero, facturas.numero_talonario, facturas.numero_factura, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0')) AS comprobante, IF(facturas.cae_numero, CONCAT('https://cms.revisionalpha.com/pdfs/', md5(CONCAT(facturas.grupo,facturas.id)), '.pdf'), NULL) as link, IF(facturas.cae_numero, CONCAT('https://cms.revisionalpha.com/pdfs/descargar/', md5(CONCAT(facturas.grupo,facturas.id))), NULL) as descargar, empresas_fiscales.id as id_empresa_fiscal, empresas_fiscales.razon_social, facturas.fecha AS fecha_real, UNIX_TIMESTAMP(facturas.fecha) AS fecha, facturas.vencimiento AS vencimiento_real, UNIX_TIMESTAMP(facturas.vencimiento) AS vencimiento, facturas.bruto, facturas.descuento,
					facturas.SUBTOTAL105 AS subtotal105, facturas.IMP105 AS imp105, facturas.NO_GRAVADOS105 AS no_gravados105,
					facturas.SUBTOTAL210 AS subtotal210, facturas.IMP210 AS imp210, facturas.NO_GRAVADOS210 AS no_gravados210,
					facturas.SUBTOTAL270 AS subtotal270, facturas.IMP270 AS imp270, facturas.NO_GRAVADOS270 AS no_gravados275,
					facturas.EXENTO AS exento, facturas.RETENCION_IVA AS retencio_iva, facturas.RETENCION_IIBB AS retencion_iibb, facturas.RETENCIONES_GENERALES AS retenciones_generales, facturas.PERCEPCION_IIBB AS percepcion_iibb,
					facturas.total_neto, facturas.saldo, UNIX_TIMESTAMP(facturas.recibido) AS recibido, empresas.empresa, empresas.id as id_empresa, formas_pago.forma_pago, facturas.id_forma_pago, sys_monedas.simbolo, sys_monedas.codigo AS moneda_codigo, facturas.estado AS id_estado, facturas.error,
		
						CASE
							WHEN facturas.estado = 1 THEN 'label-warning'
							WHEN facturas.estado = 2 THEN 'label-primary'
							WHEN facturas.estado = 3 THEN 'label-danger'
							WHEN facturas.estado = 4 THEN 'label-plain'
							WHEN facturas.estado = 5 THEN 'label-plain'
							WHEN facturas.estado = 6 THEN 'label-plain'
							WHEN facturas.estado = 7 THEN 'label-danger'
							WHEN facturas.estado = 8 THEN 'label-info'
						END AS estado_ui_class,
						
						CASE
							WHEN facturas.estado = 1 THEN 'Imprimir'
							WHEN facturas.estado = 2 THEN 'Impresa'
							WHEN facturas.estado = 3 THEN 'Anulada'
							WHEN facturas.estado = 4 THEN 'Nota de crédito'
							WHEN facturas.estado = 5 THEN 'Bonificada'
							WHEN facturas.estado = 6 THEN 'Bonificada (Nota de crédito)'
							WHEN facturas.estado = 7 THEN 'Error'
							WHEN facturas.estado = 8 THEN 'Nueva'
						END AS estado,
						
						CASE
							WHEN facturas.operacion = 'C' THEN 'badge-danger'
							WHEN facturas.operacion = 'V' THEN 'badge-success'
						END AS operacion_ui_class
						
					FROM facturas
					LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
					LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
					LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
					LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
					LEFT JOIN formas_pago ON facturas.id_forma_pago = formas_pago.id
				";
		}
		
		$sql .= "
				WHERE facturas.grupo = ?
				AND facturas.id = ?
				AND empresas.estado > 0
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
			$sql .= " AND empresas.id = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Sin permisos para acceder a la información de esta factura';
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
	
	
	public function getFacturaDetalleRaw($id)
	{
		return $this->getFacturaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getFacturaItems($id_factura)
	{
		$sql = " 	
				SELECT facturas_items.id, facturas_items.id_categoria, facturas_items.descripcion, facturas_items.valor, facturas_items.descuento, facturas_tipo.impuesto AS impuesto, ROUND((facturas_items.valor-facturas_items.descuento)*facturas_tipo.impuesto/100+(facturas_items.valor-facturas_items.descuento), 2) AS total_neto
				FROM facturas_items, facturas, facturas_tipo
				WHERE facturas_items.id_factura = facturas.id
				AND facturas.id_factura_tipo = facturas_tipo.id
				AND facturas_items.grupo = ?
				AND facturas_items.id_factura = ?
			";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id_factura;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id_factura;
		}
		else
		{
			$res['error'] = 'Sin permisos para acceder a la información de esta factura';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
					
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaItemDetalle($id)
	{
		$sql = " 	
				SELECT facturas_items.id, facturas_items.id_factura, facturas_items.id_categoria, facturas_items.descripcion, facturas_items.valor, facturas_items.descuento
				
				FROM facturas_items
				
				WHERE facturas_items.grupo = ?
				AND facturas_items.id = ?
			";
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		}
		else
		{
			$res['error'] = 'Sin permisos para acceder a la información de esta factura';
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
	
	
	public function ingresarFacturaItems($valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		$data['id_factura'] = $valores['id_factura'];
		if (isset($valores['id_categoria'])) $data['id_categoria'] = $valores['id_categoria'];
		
		$data['descripcion'] = $valores['descripcion'];
		$data['descuento'] = $valores['descuento'];
		$data['valor'] = $valores['valor'];

		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = (!empty($valores['username_alta'])) ? $valores['username_alta'] : $this->usuario->id;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('facturas_items', $data);

			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarFacturaItem($id, $valores)
	{
		if (!empty($valores['id_categoria'])) $data['id_categoria'] = $valores['id_categoria'];

		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? $valores['descripcion'] : NULL;
		
		$data['descuento'] = (!empty($valores['descuento'])) ? ($valores['descuento'] > $valores['valor']) ? $valores['valor'] : $valores['descuento'] : 0;
		$data['valor'] = (!empty($valores['valor'])) ? $valores['valor'] : 0;
				
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;
		
		$res = $this->db->update('facturas_items', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaTotal($id)
	{
		$sql = "
				SELECT facturas.total_neto AS total
				
				FROM facturas
				
				WHERE facturas.id = ?
			";

		
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));


		if ($query)
		{
			$res = $query->row_array()['total'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaMovimientos($id)
	{
		$sql = "
				SELECT SUM(valor) AS valor
				
				FROM movimientos
				
				WHERE id_factura = ?
				
				AND movimientos.estado = 2
			";

		
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));


		if ($query)
		{
			$res = $query->row_array()['valor'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarFacturaSaldo($id)
	{
		$data['saldo'] = $this->getFacturaTotal($id) - $this->getFacturaMovimientos($id);

		if (!isset($res['error']))
		{
			$res = $this->db->update('facturas', $data, array('id'=>$id));
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaSaldo($id, $parametros = null)
	{
		$sql = "	
				SELECT facturas.saldo AS valor
				
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				
				WHERE facturas.grupo = ?
				AND facturas.id = ?
			";

		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $id;
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND empresas_fiscales.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$sql .= " AND empresas_fiscales.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);


		if ($query)
		{
			$res = $query->row_array()['valor'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarFactura($id)
	{
		$sql = "
				SELECT facturas.id, empresas.empresa, contactos.id AS id_contacto, contactos.username, contactos.hash, facturas.id_factura_tipo, facturas_tipo.factura_tipo as tipo, facturas.total_neto, facturas.saldo, facturas.id_forma_pago, facturas_notificaciones.notificacion, sys_monedas.moneda, sys_monedas.simbolo, 
				UNIX_TIMESTAMP(facturas.fecha) AS fecha,
				UNIX_TIMESTAMP(facturas.vencimiento) AS vencimiento,
				CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(facturas.numero_factura, 8, '0')) as comprobante,
				IF(facturas.cae_numero, CONCAT('https://cms.revisionalpha.com/pdfs/', md5(CONCAT(facturas.grupo,facturas.id)), '.pdf'), NULL) as link
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				LEFT JOIN facturas_notificaciones ON DATE_FORMAT(facturas_notificaciones.fecha, '%Y-%m') = DATE_FORMAT(facturas.fecha_alta, '%Y-%m')
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				
				WHERE facturas.id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
			
			if (isset($res))
			{
				$this->db->update('facturas', array('enviado'=>unix_to_human(now(), true, 'eu')), array('id'=>$res['id']));
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarFacturasNuevas($limite = 5)
	{
		$sql = "
				SELECT facturas.id
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND facturas.saldo > 0
				AND facturas.enviado IS NULL
				AND empresas.estado > 0
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($limite));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarFacturasAVencer()
	{
		$sql = "
				SELECT facturas.id
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 0
				AND facturas.saldo > 0
				
				AND (facturas.id_factura_tipo = 5 OR facturas.id_factura_tipo = 15 OR facturas.id_factura_tipo = 16 OR facturas.id_factura_tipo = 30 OR facturas.id_factura_tipo = 31) # FACTURA S, FACTURA A, FACTURA B
				AND (facturas.id_forma_pago != 5 AND facturas.id_forma_pago != 12) # DEBITO, MERCADO PAGO SUSCRIPCION
				
				AND facturas.vencimiento = DATE_ADD(CURDATE(), INTERVAL +2 DAY)
			";
		
		$query = $this->db->query($sql);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarFacturasVencidas($dias_vencidas = 2)
	{
		$sql = "
				SELECT facturas.id
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 0
				AND facturas.saldo > 0
				
				AND (facturas.id_factura_tipo = 5 OR facturas.id_factura_tipo = 15 OR facturas.id_factura_tipo = 16 OR facturas.id_factura_tipo = 30 OR facturas.id_factura_tipo = 31) # FACTURA S, FACTURA A, FACTURA B
				AND (facturas.id_forma_pago != 5 AND facturas.id_forma_pago != 12) # DEBITO, MERCADO PAGO SUSCRIPCION
				
				AND facturas.vencimiento = DATE_ADD(CURDATE(), INTERVAL -? DAY)
				
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
			";
		
		$query = $this->db->query($sql, array($dias_vencidas));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarSuspensionDeServicios($dias = 45)
	{
		$sql = "
				SELECT DATE_FORMAT(facturas.vencimiento, '%Y%m%d') AS id, empresas.empresa, contactos.id AS id_contacto, contactos.username, contactos.hash, SUM(facturas.saldo) AS parcial, facturas.vencimiento, sys_monedas.simbolo,
								(SELECT COUNT(*)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.operacion = 'V'
													AND facturas.estado = 2
													AND empresas.estado > 0
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS cantidad,
								(SELECT SUM(facturas.saldo)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.operacion = 'V'
													AND facturas.estado = 2
													AND empresas.estado > 0
													#AND empresas.id_categoria = 2
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS saldo
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 0
				AND facturas.saldo > 0
				AND (facturas.vencimiento = DATE_ADD(CURDATE(), INTERVAL -? DAY))
				AND (facturas.id_factura_tipo = 5 OR facturas.id_factura_tipo = 15 OR facturas.id_factura_tipo = 16 OR facturas.id_factura_tipo = 30 OR facturas.id_factura_tipo = 31) # FACTURA S, FACTURA A, FACTURA B
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
				
				GROUP BY empresas.id
			";
		
		$query = $this->db->query($sql, array($dias));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCantidadDeFacturas($estado)
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM facturas WHERE estado = ? AND grupo = ?";
		
		$placeholders[] = $estado;
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND empresas.id = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaMercadoPagoRecurrente($id_empresa, $valor)
	{
		$sql = "
				SELECT facturas.id
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 0
				AND facturas.saldo = ?
				AND facturas.id_forma_pago = 12 # MERCADO PAGO (SUSCRIPCION)
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
				AND empresas.id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array(
				$valor,
				$id_empresa
			));


		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFacturaIdFromMovimientoId($id)
	{	
		$sql = "
				SELECT movimientos.id_factura AS id
				
				FROM movimientos
				
				WHERE movimientos.id = ?
			";

		// consulta
		$query = $this->db->query($sql, array(
				$id
			));


		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($grupo, $operacion, $id_factura_tipo, $id_forma_pago, $id_moneda, $id_empresa_fiscal, $estado = 8, $numero_talonario = null, $numero_factura = null)
	{
		$sql = "
				SELECT id
				
				FROM facturas
				
				WHERE grupo = ?
				AND operacion = ?
				AND id_factura_tipo = ?
				AND id_forma_pago = ?
				AND id_moneda = ?
				AND id_empresa_fiscal = ?
				AND estado = ?
				AND total_neto = saldo
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $operacion;
		$placeholders[] = $id_factura_tipo;
		$placeholders[] = $id_forma_pago;
		$placeholders[] = $id_moneda;
		$placeholders[] = $id_empresa_fiscal;
		$placeholders[] = $estado;
		
		
		// filtros
		if (!empty($numero_talonario))
		{
			$sql .= " AND numero_talonario = ?";
			$placeholders[] = $numero_talonario;
		}
		
		if (!empty($numero_factura))
		{
			$sql .= " AND numero_factura = ?";
			$placeholders[] = $numero_factura;
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}


	public function verificarFacturaMyDomain($numero_factura)
	{
		$sql = "
				SELECT id
				
				FROM facturas
				
				WHERE numero_factura = ?
			";

		$placeholders[] = $numero_factura;
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ultima($grupo, $operacion, $id_factura_tipo, $numero_talonario)
	{
		$sql = "
				SELECT numero_factura
				
				FROM facturas
				
				WHERE grupo = ?
				AND operacion = ?
				AND id_factura_tipo = ?
				AND estado = 2
				AND numero_talonario = ?
				
				ORDER BY id DESC
			";
			
		// consulta
		$query = $this->db->query($sql, array($grupo, $operacion, $id_factura_tipo, $numero_talonario));
		
		if ($query)
		{
			$res = $query->row_array()['numero_factura'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function anterior($grupo, $operacion, $id_factura_tipo, $numero_talonario, $id)
	{
		$sql = "
				SELECT id
				
				FROM facturas
				
				WHERE grupo = ?
				AND operacion = ?
				AND id_factura_tipo = ?
				AND estado = 2
				AND numero_talonario = ?
				AND id < ?
				
				ORDER BY id DESC
			";
			
		// consulta
		$query = $this->db->query($sql, array($grupo, $operacion, $id_factura_tipo, $numero_talonario, $id));
		
		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function siguiente($grupo, $operacion, $id_factura_tipo, $numero_talonario, $id)
	{
		$sql = "
				SELECT id
				
				FROM facturas
				
				WHERE grupo = ?
				AND operacion = ?
				AND id_factura_tipo = ?
				AND estado = 2
				AND numero_talonario = ?
				AND id > ?
				
				ORDER BY id ASC
			";
			
		// consulta
		$query = $this->db->query($sql, array($grupo, $operacion, $id_factura_tipo, $numero_talonario, $id));
		
		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarFactura($valores)
	{
		$data['grupo'] = (!empty($valores['grupo'])) ? $valores['grupo'] : $this->usuario->grupo;
		$data['id_empresa_fiscal'] = $valores['id_empresa_fiscal'];
		$data['id_factura_tipo'] = $valores['id_factura_tipo'];
		$data['id_forma_pago'] = $valores['id_forma_pago'];
		$data['id_moneda'] = $valores['id_moneda'];
		$data['cambio'] = $this->getCambioMoneda($valores['id_moneda']);
		$data['operacion'] = $valores['operacion'];
		$data['numero_talonario'] = (isset($valores['numero_talonario'])) ? $valores['numero_talonario'] : $valores['punto_de_venta'];
		$data['numero_factura'] = (!empty($valores['numero_factura'])) ? $valores['numero_factura'] : null;
		if ($valores['id_factura_tipo'] == 5 && $data['operacion'] == 'V') $data['numero_factura'] = $this->ultima($data['grupo'], $data['operacion'], $data['id_factura_tipo'], $data['numero_talonario'])+1;
		$data['fecha'] = (!empty($valores['fecha'])) ? date('Y-m-d', strtotime($valores['fecha'])) : date('Y-m-d');
		$data['fecha_presentacion'] = (!empty($valores['presentacion'])) ? date('Y-m-d', strtotime($valores['presentacion'])) : date('Y-m', strtotime($data['fecha'])) . '-01';
		
		if (isset($valores['vencimiento']))
		{
			$data['vencimiento'] = (!empty($valores['vencimiento'])) ? date('Y-m-d', strtotime($valores['vencimiento'])) : null;
		}
		elseif (isset($valores['vencimiento_dias']))
		{
			$data['vencimiento'] = date('Y-m-d', strtotime('+' . $valores['vencimiento_dias'] . ' DAY', strtotime($data['fecha'])));
		}

		$data['condicion'] = 'C';
		
		if (isset($valores['padre'])) $data['padre'] = $valores['padre'];
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		// valores sin calcular
		if (isset($valores['bruto'])) $data['bruto'] = (!empty($valores['bruto'])) ? $valores['bruto'] : 0;
		if (isset($valores['SUBTOTAL105'])) $data['SUBTOTAL105'] = (!empty($valores['SUBTOTAL105'])) ? $valores['SUBTOTAL105'] : 0;
		if (isset($valores['IMP105'])) $data['IMP105'] = (!empty($valores['IMP105'])) ? $valores['IMP105'] : 0;
		if (isset($valores['NO_GRAVADOS105'])) $data['NO_GRAVADOS105'] = (!empty($valores['NO_GRAVADOS105'])) ? $valores['NO_GRAVADOS105'] : 0;
		if (isset($valores['SUBTOTAL210'])) $data['SUBTOTAL210'] = (!empty($valores['SUBTOTAL210'])) ? $valores['SUBTOTAL210'] : null;
		if (isset($valores['IMP210'])) $data['IMP210'] = (!empty($valores['IMP210'])) ? $valores['IMP210'] : 0;
		if (isset($valores['NO_GRAVADOS210'])) $data['NO_GRAVADOS210'] = (!empty($valores['NO_GRAVADOS210'])) ? $valores['NO_GRAVADOS210'] : 0;
		if (isset($valores['SUBTOTAL270'])) $data['SUBTOTAL270'] = (!empty($valores['SUBTOTAL270'])) ? $valores['SUBTOTAL270'] : 0;
		if (isset($valores['IMP270'])) $data['IMP270'] = (!empty($valores['IMP270'])) ? $valores['IMP270'] : 0;
		if (isset($valores['NO_GRAVADOS270'])) $data['NO_GRAVADOS270'] = (!empty($valores['NO_GRAVADOS270'])) ? $valores['NO_GRAVADOS270'] : 0;	
		if (isset($valores['EXENTO'])) $data['EXENTO'] = (!empty($valores['EXENTO'])) ? $valores['EXENTO'] : 0;
		if (isset($valores['RETENCION_IVA'])) $data['RETENCION_IVA'] = (!empty($valores['RETENCION_IVA'])) ? $valores['RETENCION_IVA'] : 0;
		if (isset($valores['RETENCION_IIBB'])) $data['RETENCION_IIBB'] = (!empty($valores['RETENCION_IIBB'])) ? $valores['RETENCION_IIBB'] : 0;
		if (isset($valores['RETENCIONES_GENERALES'])) $data['RETENCIONES_GENERALES'] = (!empty($valores['RETENCIONES_GENERALES'])) ? $valores['RETENCIONES_GENERALES'] : 0;
		if (isset($valores['PERCEPCION_IIBB'])) $data['PERCEPCION_IIBB'] = (!empty($valores['PERCEPCION_IIBB'])) ? $valores['PERCEPCION_IIBB'] : 0;
		if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : 0;
		if (isset($valores['total_neto'])) $data['total_neto'] = (!empty($valores['total_neto'])) ? $valores['total_neto'] : 0;
		$data['saldo'] = (!empty($valores['saldo'])) ? $valores['saldo'] : $data['total_neto'];
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = (!empty($valores['username_alta'])) ? $valores['username_alta'] : $this->usuario->id;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('facturas', $data);

			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarFactura($id, $valores)
	{
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['numero_talonario'])) $data['numero_talonario'] = (!empty($valores['numero_talonario'])) ? $valores['numero_talonario'] : null;
		if (isset($valores['numero_factura'])) $data['numero_factura'] = (!empty($valores['numero_factura'])) ? $valores['numero_factura'] : null;
		if (isset($valores['cae_numero'])) $data['cae_numero'] = (!empty($valores['cae_numero'])) ? $valores['cae_numero'] : null;
		if (isset($valores['cae_vencimiento'])) $data['cae_vencimiento'] =  date('Ymd', strtotime($valores['cae_vencimiento']));
		
		if (isset($valores['error'])) $data['error'] = (!empty($valores['error'])) ? $valores['error'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = (!empty($valores['username_modificacion'])) ? $valores['username_modificacion'] : $this->usuario->id;


		if (!isset($res['error']))
		{
			$res = $this->db->update('facturas', $data, array('id'=>$id));
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getNotaDeCreditoFacturaTipoId($id_afip = null, $cuit = null)
	{
		$sql = "
				SELECT facturas_tipo.id

				FROM facturas_tipo

				WHERE facturas_tipo.id_afip = ?
				AND facturas_tipo.cuit = ?
				AND facturas_tipo.grupo = ?
			";
		
		$query = $this->db->query($sql, array($id_afip, $cuit, $this->usuario->grupo));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarNodaDeCredito($id)
	{
		$factura = $this->getFacturaDetalleRaw($id);
		
		if (isset($factura['id']) && $factura['total_neto'] == $factura['saldo'])
		{
			$this->db->trans_begin();
			
			unset($factura['id_categoria']);
			unset($factura['numero_factura']);
				
			switch ($factura['id_afip'])
			{
				case 1:
					$factura['id_afip'] = 3;
					break;
				case 6:
					$factura['id_afip'] = 8;
					break;
				default:
					$factura['id_afip'] = 0;
					break;
			}
			
			$factura['id_factura_tipo'] = $this->getNotaDeCreditoFacturaTipoId($factura['id_afip'], $factura['afip_cuit']);
			unset($factura['id_afip']);
			unset($factura['afip_cuit']);
			unset($factura['cuit']);
			
			$factura['fecha'] = date('Y-m-d');
			$factura['fecha_presentacion'] = date('Y-m', strtotime($factura['fecha'])) . '-01';
			unset($factura['vencimiento']);
			
			$factura['saldo'] = 0;
			$factura['padre'] = $factura['id'];
			unset($factura['id']);
			
			unset($factura['observaciones']);
			unset($factura['cae_numero']);
			unset($factura['cae_vencimiento']);
			unset($factura['enviado']);
			unset($factura['recibido']);
			unset($factura['codigo']);
			unset($factura['error']);
			
			$factura['estado'] = 1;
		
			$factura['fecha_alta'] = unix_to_human(now(), true, 'eu');
			$factura['username_alta'] = $this->usuario->username;
			
			unset($factura['fecha_modificacion']);
			unset($factura['username_modificacion']);
			
			$this->db->update('facturas', array('saldo'=>0), array('id'=>$factura['padre']));
			
			$this->db->insert('facturas', $factura);
			$id_factura = $this->db->insert_id();
			
			
			// Items
			if (!empty($factura['padre']))
			{
				$factura['items'] = $this->getFacturaItems($factura['padre']);

				if (!empty($factura['items']))
				{
					foreach($factura['items'] as $item)
					{
						$nota_items = array();

						$nota_items['grupo'] = $factura['grupo'];
						$nota_items['id_factura'] = $id_factura;

						$nota_items['id_categoria'] = $item['id_categoria'];
						$nota_items['descripcion'] = 'Nota de cr&eacute;dito por ' . $item['descripcion'];
						$nota_items['valor'] = $item['valor'];
						$nota_items['descuento'] = $item['descuento'];

						$nota_items['fecha_alta'] = unix_to_human(now(), true, 'eu');
						$nota_items['username_alta'] = $this->usuario->username;

						$this->ingresarFacturaItems($nota_items);
					}
				}
			}
			
			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$res['error'] = 'Ha habido un problema y no se pudo ingresar la nota de crédito, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
				
				$res['id'] = $id_factura;
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function confeccionarFactura($id)
	{
		$sql = "
				SELECT SUM(facturas_items.valor) AS valor, SUM(facturas_items.descuento) AS descuento, facturas_tipo.impuesto
				
				FROM facturas_items
				LEFT JOIN facturas ON facturas_items.id_factura = facturas.id
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				
				WHERE facturas_items.id_factura = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));
			
		if ($query)
		{
			$row = $query->row_array();
		}
		
		
		$data['bruto'] = $row['valor'];
		$data['descuento'] = $row['descuento']; 
		$data['SUBTOTAL210'] = $data['bruto'] - $row['descuento'];
		$data['IMP210'] =  $row['impuesto'] * ($data['SUBTOTAL210']) / 100;
		$data['total_neto'] = $data['SUBTOTAL210'] + $data['IMP210'];
		$data['saldo'] = $data['total_neto'];
		
		$data['estado'] = 1;
		
		if (!isset($res['error']))
		{
			$data['update'] = $this->db->update('facturas', $data, array('id'=>$id));
			
			$data['id'] = $id;
		}

		return (!empty($data)) ? $data : null;
	}
	
	
	public function facturasParaConfeccionar($intervalo = 7200, $limite = 50)
	{
		$sql = "
				SELECT facturas.id

				FROM facturas

				WHERE facturas.estado = 8
				AND DATE_SUB(NOW(), INTERVAL ? SECOND) > facturas.fecha_alta
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($intervalo, $limite));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function facturasCobradasPorDebito($id_empresa)
	{
		$sql = "
				SELECT facturas.id, facturas.saldo, facturas.grupo
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 0
				AND facturas.saldo > 0
				AND (facturas.id_forma_pago = 5 OR facturas.id_forma_pago = 15) # DEBITO GRUPO 502, 505
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
				AND empresas.id = ?
				
				ORDER BY facturas.id ASC
			";
			
		// consulta
		$query = $this->db->query($sql, array($id_empresa));
		
		if ($query)
		{
			$res = $query->result_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function exportar($frecuencia = 'mensual', $ano, $periodo, $operacion)
	{
		$sql = "
				SELECT facturas.fecha, facturas.vencimiento, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0')) AS comprobante, empresas_fiscales.razon_social, REPLACE(empresas_fiscales.cuit, '-', ''), condiciones_iva.condicion_iva,
				
					CASE
						WHEN facturas.id_moneda = 1 THEN 'ARG'
						WHEN facturas.id_moneda = 2 THEN 'USD'
					END AS moneda, facturas.cambio, formas_pago.forma_pago, 
					
				facturas.bruto, facturas.descuento, facturas.SUBTOTAL105, facturas.IMP105, facturas.NO_GRAVADOS105, facturas.SUBTOTAL210, facturas.IMP210, facturas.NO_GRAVADOS210, facturas.SUBTOTAL270, facturas.IMP270, facturas.NO_GRAVADOS270,
				EXENTO, RETENCION_IVA, RETENCION_IIBB, RETENCIONES_GENERALES, PERCEPCION_IIBB,
				IF(facturas.padre, CONCAT('-', facturas.total_neto), facturas.total_neto),
				
					(SELECT SUM(movimientos.valor) as valor
						FROM movimientos
						WHERE movimientos.id_factura = facturas.id
						AND movimientos.estado = 2
			";
			
			if ($frecuencia == 'mensual')
			{
				$sql .= "			
						AND MONTH(movimientos.fecha) = ?
						AND YEAR(movimientos.fecha) = ?
					";
			}
			else
			{
				$sql .= "			
						AND QUARTER(movimientos.fecha) = ?
						AND YEAR(movimientos.fecha) = ?
					";
			}
			
			$sql .= "
					) AS pago,
					
				facturas.saldo

				FROM facturas, facturas_tipo, empresas_fiscales, condiciones_iva, formas_pago

				WHERE facturas.grupo = ?
			";
			
			if ($frecuencia == 'mensual')
			{
				$sql .= "
						AND MONTH(facturas.fecha_presentacion) = ?
						AND YEAR(facturas.fecha_presentacion) = ?
					";
			}
			else
			{
				$sql .= "			
						AND QUARTER(facturas.fecha_presentacion) = ?
						AND YEAR(facturas.fecha_presentacion) = ?
					";
			}
			
			$sql .= "
				AND facturas.id_factura_tipo = facturas_tipo.id
				AND facturas.id_empresa_fiscal = empresas_fiscales.id
				AND empresas_fiscales.id_condicion_iva = condiciones_iva.id
				AND facturas.id_forma_pago = formas_pago.id
				AND facturas.operacion = ?
				AND facturas.estado > 0
				#AND facturas_tipo.id_afip IS NOT NULL
			"; // fecha, vencimiento, comprobante, razon_social, cuit, condicion_iva, moneda, cambio, forma_pago, bruto, descuento, SUBTOTAL105, IMP105, NO_GRAVADOS105, SUBTOTAL210, IMP210, NO_GRAVADOS210, SUBTOTAL270, IMP270, NO_GRAVADOS270, EXENTO, RETENCION_IVA, RETENCION_IIBB, RETENCIONES_GENERALES, PERCEPCION_IIBB, total_neto, pago, saldo
		
		
		// permisos	
		if (!$this->usuario->perfil == 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			$placeholders[] = $periodo;
			$placeholders[] = $ano;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $periodo;
			$placeholders[] = $ano;
			$placeholders[] = $operacion;
			
			// orden
			$sql .= " ORDER BY facturas_tipo.factura_tipo, facturas.fecha, facturas.numero_factura ASC";
			
			// consulta
			$res = $this->db->query($sql, $placeholders);
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboFacturas($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT facturas.id, CONCAT(facturas_tipo.factura_tipo, ' ', lpad(facturas.numero_talonario, 4, '0'), '-', lpad(IF(facturas.numero_factura, facturas.numero_factura, '********'), 8, '0'),  ' (', sys_monedas.simbolo, facturas.saldo, ')') AS valor
				
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				
				WHERE facturas.estado = 2
				AND facturas.saldo > 0
				AND facturas.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND empresas_fiscales.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND empresas_fiscales.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " facturas.fecha";
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
			$res[] = '--- Selecciona una opción ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['valor'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboFacturasTipo($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT facturas_tipo.id, facturas_tipo.factura_tipo  AS valor
				
				FROM facturas_tipo
				
				WHERE facturas_tipo.estado > 1
				AND facturas_tipo.grupo = ?
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
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " facturas_tipo.factura_tipo";
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
			$res[] = '--- Selecciona una opción ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['valor'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCambioMoneda($id)
	{
		$this->db->select('cambio');
		$this->db->from('sys_monedas');
		$this->db->where('id', $id);
		
		// consulta
		$query = $this->db->get();
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cambio'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function cambiarEstado($id, $estado)
	{
		$res = $this->db->update('facturas', array('estado'=>$estado), array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function eliminar($id)
	{
		$res = $this->db->delete('facturas_items', array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}
	
	public function getTotalCompras($parametros = null)
	{
		$sql = "
				SELECT IFNULL(SUM(facturas.saldo), 0) AS total
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				WHERE facturas.grupo = ?
				AND facturas.operacion = 'C'
				AND facturas.estado = 2
				AND facturas.saldo > 0
				AND empresas.estado > 1
			";
		
		$placeholders[] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'admin') {
			$sql .= " AND empresas.id = ?";
			$placeholders[] = $this->usuario->id_empresa;
		}
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query) {
			$res = $query->row_array()['total'];
		}
		
		return (!empty($res)) ? $res : 0;
	}
	
	public function getTotalVentas($parametros = null)
	{
		$sql = "
				SELECT IFNULL(SUM(facturas.saldo), 0) AS total
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				WHERE facturas.grupo = ?
				AND facturas.operacion = 'V'
				AND facturas.estado = 2
				AND facturas.saldo > 0
				AND empresas.estado > 1
			";
		
		$placeholders[] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'admin') {
			$sql .= " AND empresas.id = ?";
			$placeholders[] = $this->usuario->id_empresa;
		}
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query) {
			$res = $query->row_array()['total'];
		}
		
		return (!empty($res)) ? $res : 0;
	}
	
	public function getTotalFacturado($parametros = null)
	{
		// Current month data
		$sql = "
				SELECT 
					SUM(CASE WHEN facturas.operacion = 'C' THEN facturas.total_neto * facturas.cambio ELSE 0 END) AS total_compras,
					SUM(CASE WHEN facturas.operacion = 'V' THEN facturas.total_neto * facturas.cambio ELSE 0 END) AS total_ventas,
					SUM(CASE WHEN facturas.operacion = 'C' THEN facturas.saldo * facturas.cambio ELSE 0 END) AS pendiente_pago,
					SUM(CASE WHEN facturas.operacion = 'V' THEN facturas.saldo * facturas.cambio ELSE 0 END) AS pendiente_cobro,
					SUM(CASE WHEN facturas.operacion = 'V' THEN (facturas.total_neto - facturas.saldo) * facturas.cambio ELSE 0 END) AS pagos_recibidos,
					SUM(CASE WHEN facturas.operacion = 'C' THEN (facturas.total_neto - facturas.saldo) * facturas.cambio ELSE 0 END) AS pagos_realizados
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				WHERE facturas.grupo = ?
				AND (facturas.estado = 1 OR facturas.estado = 2)
				AND empresas.estado > 1
				AND MONTH(facturas.fecha) = MONTH(CURRENT_DATE())
				AND YEAR(facturas.fecha) = YEAR(CURRENT_DATE())
			";
		
		$placeholders[] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'admin') {
			$sql .= " AND empresas.id = ?";
			$placeholders[] = $this->usuario->id_empresa;
		}
		
		$query = $this->db->query($sql, $placeholders);
		
		if ($query) {
			$res = $query->row_array();
		}
		
		// Previous month data
		$sql_anterior = "
				SELECT 
					SUM(CASE WHEN facturas.operacion = 'C' THEN facturas.total_neto * facturas.cambio ELSE 0 END) AS total_compras_anterior,
					SUM(CASE WHEN facturas.operacion = 'V' THEN facturas.total_neto * facturas.cambio ELSE 0 END) AS total_ventas_anterior,
					SUM(CASE WHEN facturas.operacion = 'C' THEN facturas.saldo * facturas.cambio ELSE 0 END) AS pendiente_pago_anterior,
					SUM(CASE WHEN facturas.operacion = 'V' THEN facturas.saldo * facturas.cambio ELSE 0 END) AS pendiente_cobro_anterior,
					SUM(CASE WHEN facturas.operacion = 'V' THEN (facturas.total_neto - facturas.saldo) * facturas.cambio ELSE 0 END) AS pagos_recibidos_anterior,
					SUM(CASE WHEN facturas.operacion = 'C' THEN (facturas.total_neto - facturas.saldo) * facturas.cambio ELSE 0 END) AS pagos_realizados_anterior
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
				WHERE facturas.grupo = ?
				AND (facturas.estado = 1 OR facturas.estado = 2)
				AND empresas.estado > 1
				AND (
					(MONTH(facturas.fecha) = MONTH(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)) 
					AND YEAR(facturas.fecha) = YEAR(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)))
					OR
					(MONTH(facturas.fecha) = 12 AND MONTH(CURRENT_DATE()) = 1
					AND YEAR(facturas.fecha) = YEAR(CURRENT_DATE()) - 1)
				)
			";
		
		$placeholders_anterior[] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'admin') {
			$sql_anterior .= " AND empresas.id = ?";
			$placeholders_anterior[] = $this->usuario->id_empresa;
		}
		
		$query_anterior = $this->db->query($sql_anterior, $placeholders_anterior);
		
		if ($query_anterior) {
			$res_anterior = $query_anterior->row_array();
			
			// Merge results
			if (!empty($res) && !empty($res_anterior)) {
				$res = array_merge($res, $res_anterior);
			} elseif (!empty($res_anterior)) {
				$res = $res_anterior;
			}
		}
		
		return (!empty($res)) ? $res : null;
	}

}