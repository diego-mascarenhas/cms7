<?php defined('BASEPATH') or exit('No direct script access allowed');


class Servicio_model extends CI_Model {
	
	public function getServicios($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS servicios.id, COALESCE(servicios.descripcion, IFNULL(trim(CONCAT(categorias_generales.descripcion, ' ', UPPER(COALESCE(servicios_hosting.domain, tienda_configuracion.titulo)))), categorias_generales.descripcion)) AS descripcion, servicios.host, categorias_generales.id_tipo, servicios_hosting.domain, servicios.autosuspender,
				
					@valor:=IF(servicios.valor>0, servicios.valor, IF(categorias_generales.valor>0, categorias_generales.valor, 0))*servicios.frecuencia AS valor,
					@descuento_servicio:=IF(servicios.descuento>0, servicios.descuento, 0) AS descuento_servicio,
					@descuento_forma_pago:=IF(formas_pago.descuento>0, formas_pago.descuento, 0) AS descuento_forma_pago,
					@descuento_frecuencia:=IF(frecuencias.descuento>0, frecuencias.descuento, 0) AS descuento_frecuencia,
					@descuento_porcentaje:=IF(@descuento_servicio+@descuento_forma_pago+@descuento_frecuencia > 100, 100, @descuento_servicio+@descuento_forma_pago+@descuento_frecuencia) AS descuento_porcentaje,
					@descuento:=ROUND(@valor*@descuento_porcentaje/100, 2) AS descuento,
					@total:=ROUND(@valor-@descuento, 2) AS total,
					ROUND(@total*0.21, 2) as iva,
					ROUND(@total*1.21, 2) as total_neto,
					
					servicios.id_empresa, empresas.empresa, frecuencias.frecuencia, servicios_estado.estado, categorias_generales.categoria, (SELECT categoria FROM categorias_generales AS padre WHERE padre.id = categorias_generales.padre) AS categoria_padre, UNIX_TIMESTAMP(servicios.fecha_alta) AS fecha_alta, UNIX_TIMESTAMP(servicios.proxima) AS proxima, sys_monedas.simbolo, servicios.estado AS id_estado,
					
					CASE
					   WHEN servicios.estado = 1 THEN 'label-plain'
					   WHEN servicios.estado = 2 THEN 'label-danger'
					   WHEN servicios.estado = 3 THEN 'label-warning'
					   WHEN servicios.estado = 4 THEN 'label-primary'
					END AS estado_ui_class,
					
					CASE
					   WHEN servicios.operacion = 'C' THEN 'compra'
					   WHEN servicios.operacion = 'V' THEN 'venta'
					END AS operacion,
					
					CASE
					   WHEN servicios.operacion = 'C' THEN 'badge-danger'
					   WHEN servicios.operacion = 'V' THEN 'badge-success'
					END AS operacion_ui_class
					
				FROM servicios
				
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN frecuencias ON servicios.frecuencia = frecuencias.id
				LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN formas_pago ON COALESCE(servicios.id_forma_pago, empresas.id_forma_pago) = formas_pago.id
				LEFT JOIN sys_monedas ON servicios.id_moneda = sys_monedas.id
				LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN tienda_configuracion ON tienda_configuracion.id_servicio = servicios.id
				
				WHERE servicios.grupo = ?
			";
						
						
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND servicios.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND servicios.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND servicios.estado > 0";
				$sql .= " AND empresas.estado > 0";
			}
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND servicios.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
			
			if (isset($parametros['tipo']) && $parametros['tipo'] == 'hosting')
			{
				$sql .= " AND (categorias_generales.id_tipo = 1 OR categorias_generales.id_tipo = 2)";
			}
			
			if (isset($parametros['id_categoria']))
			{
				$sql .= " AND servicios.id_categoria = ?";
				$placeholders[] = $parametros['id_categoria'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (servicios.descripcion REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (servicios.descripcion LIKE '%" . $value . "%'";
				$sql .= " OR servicios_hosting.domain LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " FIELD(servicios.estado,3,2,4,1), proxima";
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
		
	
	public function getServicioDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT servicios.id, servicios.id_empresa, servicios.id_categoria, servicios.operacion, servicios.descripcion, servicios.host, servicios.id_moneda, servicios.valor, servicios.descuento, servicios.frecuencia, UNIX_TIMESTAMP(servicios.proxima) AS proxima, UNIX_TIMESTAMP(servicios.caduca) AS caduca, servicios.estado, servicios.id_forma_pago, servicios.convertir, servicios.autosuspender, categorias_generales_tipo.api
		
					FROM servicios
					LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
					LEFT JOIN categorias_generales_tipo ON categorias_generales.id_tipo = categorias_generales_tipo.id
				";
		}
		else
		{
			$sql = "
					SELECT servicios.id, servicios.id_empresa, servicios.id_categoria, servicios.operacion, servicios.descripcion, servicios.host, servicios.id_moneda, servicios.valor, servicios.descuento, servicios.frecuencia, UNIX_TIMESTAMP(servicios.proxima) AS proxima, UNIX_TIMESTAMP(servicios.caduca) AS caduca, servicios.estado, servicios.id_forma_pago, servicios.convertir, servicios.autosuspender, categorias_generales.id_tipo, servicios.ultima, categorias_generales.nombre_categoria AS categoria, servicios_hosting.id AS id_servicio_hosting, servicios_hosting.domain, servicios.id_forma_pago, formas_pago.forma_pago, servicios.convertir, servicios.autosuspender,
					
					
					@moneda:=IF(servicios.valor>0, IF(servicios.convertir, 1, servicios.id_moneda), IF(categorias_generales.convertir, 1, categorias_generales.id_moneda)) AS id_moneda,
					@cambio:=IF(servicios.valor>0, IF(servicios.convertir, (SELECT sys_monedas.cambio FROM sys_monedas WHERE sys_monedas.id = servicios.id_moneda), 1), IF(categorias_generales.convertir, (SELECT sys_monedas.cambio FROM sys_monedas WHERE sys_monedas.id = categorias_generales.id_moneda), 1)) AS cambio,
					
					@valor:=ROUND(IF(servicios.valor>0, servicios.valor, IF(categorias_generales.valor>0, categorias_generales.valor, 0))*servicios.frecuencia*@cambio, 2) AS valor,
					
					ROUND(@valor*(IF(servicios.descuento+formas_pago.descuento+frecuencias.descuento>=100, servicios.descuento, servicios.descuento+formas_pago.descuento+frecuencias.descuento))/100, 2) AS descuento,
					
					(SELECT sys_monedas.moneda FROM sys_monedas WHERE sys_monedas.id = @moneda) AS moneda,
					(SELECT sys_monedas.simbolo FROM sys_monedas WHERE sys_monedas.id = @moneda) AS simbolo,
					
					@descuento_servicio:=IF(servicios.descuento>0, servicios.descuento, 0) AS descuento_servicio,
					@descuento_forma_pago:=IF(formas_pago.descuento>0, formas_pago.descuento, 0) AS descuento_forma_pago,
					@descuento_frecuencia:=IF(frecuencias.descuento>0, frecuencias.descuento, 0) AS descuento_frecuencia,
					@descuento_porcentaje:=IF(@descuento_servicio+@descuento_forma_pago+@descuento_frecuencia > 100, 100, @descuento_servicio+@descuento_forma_pago+@descuento_frecuencia) AS descuento_porcentaje,
					@descuento:=ROUND(@valor*@descuento_porcentaje/100, 2) AS descuento,
					@total:=ROUND(@valor-@descuento, 2) AS total,
					ROUND(@total*0.21, 2) as iva,
					ROUND(@total*1.21, 2) as total_neto,
					
						CASE
						   WHEN servicios.operacion = 'C' THEN 'compra'
						   WHEN servicios.operacion = 'V' THEN 'venta'
						END AS operacion
		
					FROM servicios
					
					LEFT JOIN empresas ON servicios.id_empresa = empresas.id
					LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
					LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
					LEFT JOIN formas_pago ON COALESCE(servicios.id_forma_pago, empresas.id_forma_pago) = formas_pago.id
					LEFT JOIN frecuencias ON servicios.frecuencia = frecuencias.id
					LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
					LEFT JOIN tienda_configuracion ON tienda_configuracion.id_servicio = servicios.id
				";
		}
		
		$sql .= "
				WHERE servicios.estado > 0
				AND servicios.grupo = ?
				AND servicios.id = ?
			";
		

		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			$sql .= " AND servicios.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getServicioDetalleRaw($id)
	{
		return $this->getServicioDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarServicio($valores)
	{
		$servicio['grupo'] = $this->usuario->grupo;

		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$servicio['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$res['error'] = 'Debe especificar una empresa';
			}
		}
		else
		{
			$servicio['id_empresa'] = $this->usuario->id_empresa;
		}

		$servicio['id_categoria'] = $valores['id_categoria'];
		
		if (!empty($valores['operacion'])) $servicio['operacion'] = $valores['operacion'];

		if (!empty($valores['descripcion']))
		{
			$servicio['descripcion'] = stripslashes(trim($valores['descripcion']));
		}
		else
		{
			$servicio['descripcion'] = null;
		}

		if (!empty($valores['id_moneda'])) $servicio['id_moneda'] = $valores['id_moneda'];
		if (!empty($valores['valor'])) $servicio['valor'] = $valores['valor'];
		if (!empty($valores['descuento'])) $servicio['descuento'] = $valores['descuento'];
		if (!empty($valores['frecuencia'])) $servicio['frecuencia'] = $valores['frecuencia'];
		if (isset($valores['id_forma_pago'])) $servicio['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		$servicio['convertir'] = (!empty($valores['convertir']) && $servicio['id_moneda'] > 1) ? $valores['convertir'] : null;
		$servicio['autosuspender'] = (!empty($valores['autosuspender'])) ? $valores['autosuspender'] : null;
		
		if (!empty($valores['proxima']))
		{
			if (strtotime($valores['proxima']) < strtotime('today UTC'))
			{
			    $servicio['proxima'] = date('Y-m-d', strtotime('today UTC'));
			}
			else
			{
				$servicio['proxima'] = date('Y-m-d', strtotime($valores['proxima']));
			}
		}
		else
		{
			$servicio['proxima'] = date('Y-m-d', strtotime('+7 days'));
		}
		
		if (isset($valores['caduca']))
		{
			if (!empty($valores['caduca']))
			{
				if (strtotime($valores['caduca']) < strtotime('today UTC'))
				{
				    $servicio['caduca'] = date('Y-m-d', strtotime('today UTC'));
				}
				else
				{
					$servicio['caduca'] = date('Y-m-d', strtotime($valores['caduca']));
				}
			}
			else
			{
				$servicio['caduca'] = null;
			}
		}
		
		if (isset($valores['data'])) $servicio['data'] = (!empty($valores['data'])) ? $valores['data'] : null;
		
		if (!empty($valores['estado'])) $servicio['estado'] = $valores['estado'];
		
		$servicio['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$servicio['username_alta'] = (!empty($valores['username'])) ? $valores['username'] : $this->usuario->username;


		if (!isset($res['error']))
		{
			$insert = $this->db->insert('servicios', $servicio);

			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarServicio($id, $valores)
	{
		$servicio['id_categoria'] = $valores['id_categoria'];
		
		if (!empty($valores['operacion'])) $servicio['operacion'] = $valores['operacion'];
		
		if (isset($valores['descripcion']))
		{
			if (!empty($valores['descripcion']))
			{
				$servicio['descripcion'] = stripslashes(trim($valores['descripcion']));
			}
			else
			{
				$servicio['descripcion'] = null;
			}
		}
		
		if (!empty($valores['id_moneda'])) $servicio['id_moneda'] = $valores['id_moneda'];
		if (isset($valores['valor'])) $servicio['valor'] = (!empty($valores['valor'])) ? $valores['valor'] : null;
		if (isset($valores['descuento'])) $servicio['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : null;
		if (!empty($valores['frecuencia'])) $servicio['frecuencia'] = $valores['frecuencia'];
		if (isset($valores['id_forma_pago'])) $servicio['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		$servicio['convertir'] = (!empty($valores['convertir']) && $servicio['id_moneda'] > 1) ? $valores['convertir'] : null;
		$servicio['autosuspender'] = (!empty($valores['autosuspender'])) ? $valores['autosuspender'] : null;

		if (!empty($valores['proxima']))
		{
			if (strtotime($valores['proxima']) < strtotime('today UTC'))
			{
			    $servicio['proxima'] = date('Y-m-d', strtotime('today UTC'));
			}
			else
			{
				$servicio['proxima'] = date('Y-m-d', strtotime($valores['proxima']));
			}
		}
		
		if (isset($valores['caduca']))
		{
			if (!empty($valores['caduca']))
			{
				if (strtotime($valores['caduca']) < strtotime('today UTC'))
				{
				    $servicio['caduca'] = date('Y-m-d', strtotime('today UTC'));
				}
				else
				{
					$servicio['caduca'] = date('Y-m-d', strtotime($valores['caduca']));
					}
			}
			else
			{
				$servicio['caduca'] = null;
			}
		}
		
		if (isset($valores['data'])) $servicio['data'] = (!empty($valores['data'])) ? $valores['data'] : null;
		
		if (!empty($valores['estado'])) $servicio['estado'] = $valores['estado'];
		
		$servicio['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$servicio['username_modificacion'] = $this->usuario->username;

				
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('servicios', $servicio, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$res = $this->db->update('servicios', $servicio, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function serviciosParaFacturar($limite = 50)
	{
		$sql = "
				SELECT servicios.id, servicios.grupo, servicios.id_empresa, COALESCE(servicios.descripcion, IFNULL(trim(CONCAT(categorias_generales.descripcion, ' ', UPPER(COALESCE(servicios_hosting.domain, tienda_configuracion.titulo)))), categorias_generales.descripcion)) AS descripcion, servicios.ultima, servicios.proxima as actual, DATE_ADD(servicios.proxima, INTERVAL servicios.frecuencia MONTH) as proxima, servicios.id_categoria, servicios.caduca, servicios.operacion, servicios.frecuencia,
				
				IF(servicios.valor>0, IF(servicios.convertir, 1, servicios.id_moneda), IF(categorias_generales.convertir, 1, categorias_generales.id_moneda)) AS id_moneda,
				@cambio:=IF(servicios.valor>0, IF(servicios.convertir, (SELECT sys_monedas.cambio FROM sys_monedas WHERE sys_monedas.id = servicios.id_moneda), 1), IF(categorias_generales.convertir, (SELECT sys_monedas.cambio FROM sys_monedas WHERE sys_monedas.id = categorias_generales.id_moneda), 1)) AS cambio,
				
				@valor:=ROUND(IF(servicios.valor>0, servicios.valor, IF(categorias_generales.valor>0, categorias_generales.valor, 0))*servicios.frecuencia*@cambio, 2) AS valor,
				
				ROUND(@valor*(IF(servicios.descuento+formas_pago.descuento+frecuencias.descuento>=100, servicios.descuento, servicios.descuento+formas_pago.descuento+frecuencias.descuento))/100, 2) AS descuento,
				
				IF(servicios.id_forma_pago, servicios.id_forma_pago, empresas.id_forma_pago) AS id_forma_pago,
				
				empresas_fiscales.id as id_empresa_fiscal, empresas.id_factura_tipo, facturas_tipo.impuesto, facturas_tipo.punto_de_venta, facturas_tipo.vencimiento_dias, servicios.username_alta

				FROM servicios
				LEFT JOIN empresas_fiscales ON servicios.id_empresa = empresas_fiscales.id_empresa
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN facturas_tipo ON empresas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN formas_pago ON IF(servicios.id_forma_pago, servicios.id_forma_pago, empresas.id_forma_pago) = formas_pago.id
				LEFT JOIN frecuencias ON servicios.frecuencia = frecuencias.id
				LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN tienda_configuracion ON tienda_configuracion.id_servicio = servicios.id

				WHERE servicios.estado = 4
				AND (empresas.estado > 1 AND empresas.estado != 3)
				AND empresas_fiscales.estado > 0
				AND DATE_FORMAT(servicios.proxima, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
				AND DATE_FORMAT(servicios.proxima, '%Y-%m-%d') <= DATE_FORMAT(NOW(), '%Y-%m-%d')
				AND IF(servicios.valor>0, servicios.valor, IF(categorias_generales.valor>0, categorias_generales.valor, 0))*servicios.frecuencia > 0
				
				GROUP BY servicios.id
				
				ORDER BY empresas_fiscales.id ASC, servicios.descuento ASC, operacion ASC, servicios.id ASC
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($limite));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function facturado($id, $valores)
	{
		if (!empty($valores['ultima'])) $servicio['ultima'] = $valores['ultima'];
		if (!empty($valores['proxima'])) $servicio['proxima'] = $valores['proxima'];
		if (!empty($valores['estado'])) $servicio['estado'] = $valores['estado'];
		
		$res = $this->db->update('servicios', $servicio, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getServicioDescripcion($id)
	{
		$sql = "
				SELECT descripcion
				
				FROM categorias_generales
				
				WHERE id = ?
			";

		$query = $this->db->query($sql, array(
				$id)
		);

		if ($query)
		{
			$res = $query->row()->descripcion;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarServicio($id)
	{
		$sql = "
				SELECT servicios.id, empresas.empresa, contactos.id AS id_contacto, contactos.username, contactos.hash,
				
				COALESCE(servicios.descripcion, IFNULL(trim(CONCAT(categorias_generales.descripcion, ' ', UPPER(COALESCE(servicios_hosting.domain, tienda_configuracion.titulo)))), categorias_generales.descripcion)) AS descripcion, frecuencias.frecuencia, servicios.frecuencia AS id_frecuencia, UNIX_TIMESTAMP(servicios.fecha_alta) AS fecha_alta, UNIX_TIMESTAMP(servicios.proxima) AS proxima,
				
				categorias_generales.categoria, sys_monedas.moneda, sys_monedas.simbolo, 
		
				@valor:=IF(servicios.valor>0, servicios.valor, IF(categorias_generales.valor>0, categorias_generales.valor, 0))*servicios.frecuencia AS valor,
				@descuento_servicio:=IF(servicios.descuento>0, servicios.descuento, 0) AS descuento_servicio,
				@descuento_forma_pago:=IF(formas_pago.descuento>0, formas_pago.descuento, 0) AS descuento_forma_pago,
				@descuento_frecuencia:=IF(frecuencias.descuento>0, frecuencias.descuento, 0) AS descuento_frecuencia,
				@descuento_porcentaje:=IF(@descuento_servicio+@descuento_forma_pago+@descuento_frecuencia > 100, 100, @descuento_servicio+@descuento_forma_pago+@descuento_frecuencia) AS descuento_porcentaje,
				@descuento:=ROUND(@valor*@descuento_porcentaje/100, 2) AS descuento,
				@total:=ROUND(@valor-@descuento, 2) AS total,
				ROUND(@total*0.21, 2) as iva,
				ROUND(@total*1.21, 2) as total_neto
				
				FROM servicios
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				LEFT JOIN frecuencias ON servicios.frecuencia = frecuencias.id
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN formas_pago ON COALESCE(servicios.id_forma_pago, empresas.id_forma_pago) = formas_pago.id
				LEFT JOIN sys_monedas ON servicios.id_moneda = sys_monedas.id
				LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN tienda_configuracion ON tienda_configuracion.id_servicio = servicios.id
				
				WHERE servicios.id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboServicios($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT servicios.id, COALESCE(servicios.descripcion, IFNULL(trim(CONCAT(categorias_generales.descripcion, ' ', UPPER(COALESCE(servicios_hosting.domain, tienda_configuracion.titulo)))), categorias_generales.descripcion)) AS descripcion
				
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN servicios_hosting ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN tienda_configuracion ON tienda_configuracion.id_servicio = servicios.id
				
				WHERE servicios.grupo = ?
			";
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND servicios.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		else
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND servicios.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		
		if (!isset($res['error']))
		{
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " servicios.descripcion";
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
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboServiciosEstados()
	{
		return array(1=>'Suspendido', 2=>'Suspender', 3=>'Activar', 4=>'Activo');
	}
	
	
	public function getCantidadDeServicios($estado)
	{
		$sql = "SELECT COUNT(*) AS cantidad FROM servicios WHERE estado = ? AND grupo = ?";
		
		$placeholders[] = $estado;
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND servicios.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND servicios.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getServiciosParaSuspender()
	{
		$sql = "
				SELECT servicios.id, categorias_generales.id_tipo, categorias_generales.id AS id_categoria
				
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
			
				WHERE servicios.estado = 2
			";
		
		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getServiciosParaActivar()
	{
		$sql = "
				SELECT servicios.id, categorias_generales.id_tipo, categorias_generales.id AS id_categoria, categorias_generales.grupo, categorias_generales.descripcion, servicios.data, contactos.email
				
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
			
				WHERE servicios.estado = 3
				AND empresas.estado > 0
				AND (contactos.estado = 2 OR contactos.estado = 3)
			";
		
		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getServiciosCaducados()
	{
		$sql = "
				SELECT servicios.id, categorias_generales.id_tipo, categorias_generales.id AS id_categoria, servicios.caduca
				
				FROM servicios
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
			
				WHERE servicios.estado = 4
				#AND DATE_FORMAT(servicios.caduca, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
				AND DATE_FORMAT(servicios.caduca, '%Y-%m-%d') <= DATE_FORMAT(NOW(), '%Y-%m-%d')
			";
		
		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getServiciosActivosPorEmpresa($id)
	{
		$sql = "
				SELECT servicios.id, servicios.autosuspender
				
				FROM servicios
			
				WHERE servicios.estado = 4
				AND servicios.id_empresa = ?
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($id));
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getCategoriasCaracteristicas($id)
	{	
		$sql = "
				SELECT categorias_generales.caracteristicas
				
				FROM categorias_generales
			
				WHERE categorias_generales.id = ?
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($id));
			
		if ($query)
		{
			//$res = json_decode($query->row_array()['caracteristicas'], true);
			$res = json_decode($query->row_array()['caracteristicas'], true);
		}
		
		return (!empty($res)) ? $res : null;	
	}
	
	
	public function cambiarEstado($id, $estado)
	{
		$res = $this->db->update('servicios', array('estado'=>$estado), array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}