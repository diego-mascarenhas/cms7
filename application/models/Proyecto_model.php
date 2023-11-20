<?php defined('BASEPATH') or exit('No direct script access allowed');


class Proyecto_model extends CI_Model {

	public function getProyectos($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS proyectos.id, proyectos.numero_proyecto, proyectos.id_empresa, empresas.empresa AS empresa, proyectos.id_categoria, categorias_gestion.nombre_categoria AS categoria, proyectos.numero_proyecto, proyectos.titulo, proyectos.descripcion, proyectos.notas, proyectos.valor, proyectos.costo, proyectos.factura, proyectos.agencia, UNIX_TIMESTAMP(proyectos.desde) AS desde, UNIX_TIMESTAMP(proyectos.hasta) AS hasta, proyectos.responsable, proyectos.estado AS id_estado, proyectos_estado.estado, UNIX_TIMESTAMP(CONVERT_TZ(proyectos.fecha_alta, '+00:00', @@global.time_zone)) AS fecha_alta,
		
						CASE
						   WHEN proyectos.estado = 1 THEN 'label-warning'
						   WHEN proyectos.estado = 2 THEN 'label-success'
						   WHEN proyectos.estado = 3 THEN 'label-info'
						   WHEN proyectos.estado = 4 THEN 'label-info'
						   WHEN proyectos.estado = 5 THEN 'label-info'
						   WHEN proyectos.estado = 7 THEN 'label-primary'
						   WHEN proyectos.estado = 8 THEN 'label-warning'
						   WHEN proyectos.estado = 9 THEN 'label-primary'
						   WHEN proyectos.estado = 10 THEN 'label-warning'
						   WHEN proyectos.estado = 11 THEN 'label-primary'
						   WHEN proyectos.estado = 12 THEN 'label-plain'
						   WHEN proyectos.estado = 13 THEN 'label-plain'
						END AS estado_ui_class

					FROM proyectos
					LEFT JOIN proyectos_estado ON proyectos.estado = proyectos_estado.id
					LEFT JOIN empresas ON proyectos.id_empresa = empresas.id
					LEFT JOIN categorias_gestion ON proyectos.id_categoria = categorias_gestion.id

					WHERE empresas.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND proyectos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND proyectos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND proyectos.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (proyectos.titulo REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (proyectos.titulo LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " proyectos.fecha_alta";
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
	
	
	public function getProyectoDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *, UNIX_TIMESTAMP(proyectos.desde) AS desde, UNIX_TIMESTAMP(proyectos.hasta) AS hasta
				FROM proyectos
			";
		}
		else
		{
			$sql = "	
					SELECT SQL_CALC_FOUND_ROWS proyectos.id, proyectos.numero_proyecto, proyectos.id_empresa, empresas.empresa AS empresa, proyectos.id_categoria, categorias_gestion.nombre_categoria AS categoria, proyectos.numero_proyecto, proyectos.titulo, proyectos.descripcion, proyectos.notas, proyectos.valor, proyectos.costo, proyectos.factura, proyectos.agencia, UNIX_TIMESTAMP(proyectos.desde) AS desde, UNIX_TIMESTAMP(proyectos.hasta) AS hasta, proyectos.responsable, proyectos.estado AS id_estado, proyectos_estado.estado, UNIX_TIMESTAMP(CONVERT_TZ(proyectos.fecha_alta, '+00:00', @@global.time_zone)) AS fecha_alta,
		
						CASE
						   WHEN proyectos.estado = 1 THEN 'label-warning'
						   WHEN proyectos.estado = 2 THEN 'label-success'
						   WHEN proyectos.estado = 3 THEN 'label-info'
						   WHEN proyectos.estado = 4 THEN 'label-info'
						   WHEN proyectos.estado = 5 THEN 'label-info'
						   WHEN proyectos.estado = 7 THEN 'label-primary'
						   WHEN proyectos.estado = 8 THEN 'label-warning'
						   WHEN proyectos.estado = 9 THEN 'label-primary'
						   WHEN proyectos.estado = 10 THEN 'label-warning'
						   WHEN proyectos.estado = 11 THEN 'label-primary'
						   WHEN proyectos.estado = 12 THEN 'label-plain'
						   WHEN proyectos.estado = 13 THEN 'label-plain'
						END AS estado_ui_class

					FROM proyectos
					LEFT JOIN proyectos_estado ON proyectos.estado = proyectos_estado.id
					LEFT JOIN empresas ON proyectos.id_empresa = empresas.id
					LEFT JOIN categorias_gestion ON proyectos.id_categoria = categorias_gestion.id
				";
		}
		
		$sql .= " 
				WHERE proyectos.grupo = ?
				AND proyectos.estado > 0
				AND proyectos.id = ?		
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
			$sql .= " AND proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getProyectoDetalleRaw($id)
	{
		return $this->getProyectoDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarProyecto($valores)
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
		
		$data['numero_proyecto'] = $this->proyecto_model->ultimoProyecto()+1;
		if (!empty($valores['titulo'])) $data['titulo'] = $valores['titulo'];
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		
		if (isset($valores['desde'])) $data['desde'] = (!empty($valores['desde'])) ? date('Y-m-d', strtotime($valores['desde'])) : null;
		if (isset($valores['hasta'])) $data['hasta'] = (!empty($valores['hasta'])) ? date('Y-m-d', strtotime($valores['hasta'])) : null;
		
		if (isset($valores['descripcion']))
		{
			if (!empty($valores['descripcion']))
			{
				$data['descripcion'] = stripslashes(trim($valores['descripcion']));
			}
			else
			{
				$data['descripcion'] = null;
			}
		}
		
		if (isset($valores['valor'])) $data['valor'] = (!empty($valores['valor'])) ? $valores['valor'] : null;
		if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : 0.00;
		
		$data['responsable'] = $this->usuario->username;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('proyectos', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
		
	public function modificarProyecto($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (!empty($valores['titulo'])) $data['titulo'] = $valores['titulo'];
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		
		if (isset($valores['desde'])) $data['desde'] = (!empty($valores['desde'])) ? date('Y-m-d', strtotime($valores['desde'])) : null;
		if (isset($valores['hasta'])) $data['hasta'] = (!empty($valores['hasta'])) ? date('Y-m-d', strtotime($valores['hasta'])) : null;
		
		if (isset($valores['descripcion']))
		{
			if (!empty($valores['descripcion']))
			{
				$data['descripcion'] = stripslashes(trim($valores['descripcion']));
			}
			else
			{
				$data['descripcion'] = null;
			}
		}
		
		if (isset($valores['valor'])) $data['valor'] = (!empty($valores['valor'])) ? $valores['valor'] : null;
		if (isset($valores['descuento'])) $data['descuento'] = (!empty($valores['descuento'])) ? $valores['descuento'] : 0.00;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
				
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('proyectos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('proyectos', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function proyectosParaFacturar($limite = 5)
	{
		$sql = "
				SELECT proyectos.id, proyectos.grupo, proyectos.titulo AS descripcion, proyectos.id_categoria, proyectos.valor,
				ROUND(proyectos.valor*(proyectos.descuento+formas_pago.descuento)/100, 2) AS descuento, 'V' AS operacion, empresas_fiscales.id as id_empresa_fiscal,
				IF(proyectos.factura=1, empresas.id_factura_tipo, 5) AS id_factura_tipo,
				IF(proyectos.factura=1, empresas.id_forma_pago, 1) AS id_forma_pago,
				IF(proyectos.factura=1, facturas_tipo.impuesto, 0) AS impuesto,
				facturas_tipo.punto_de_venta, facturas_tipo.vencimiento_dias, COALESCE(proyectos.username_modificacion, proyectos.username_alta) AS username_alta

				FROM proyectos
				LEFT JOIN empresas_fiscales ON proyectos.id_empresa = empresas_fiscales.id_empresa
				LEFT JOIN empresas ON proyectos.id_empresa = empresas.id
				LEFT JOIN facturas_tipo ON empresas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN formas_pago ON empresas.id_forma_pago = formas_pago.id
				
				WHERE proyectos.estado = 11
				AND empresas_fiscales.estado > 0
				AND empresas.estado > 0
				
				GROUP BY proyectos.id
				
				ORDER BY proyectos.id ASC
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($limite));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function facturado($id)
	{
		$data['estado'] = 12;
		
		$res = $this->db->update('proyectos', $data, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ultimoProyecto()
	{
		$sql = "	
				SELECT MAX(numero_proyecto) AS numero_proyecto
				
				FROM proyectos

				WHERE proyectos.grupo = ?
			";
		
		$placeholders[] = $this->usuario->grupo;
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['numero_proyecto'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarProyecto($id)
	{
		$sql = "
				SELECT proyectos.id, empresas.empresa, contactos.id AS id_contacto, contactos.username, contactos.hash,
				proyectos.titulo, proyectos.descripcion, proyectos.valor, proyectos.descuento
				
				FROM proyectos
				LEFT JOIN empresas ON proyectos.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				
				WHERE proyectos.id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
			
			if (isset($res))
			{
				$this->db->update('proyectos', array('estado'=>4), array('id'=>$res['id']));
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comunicarProyectosAutorizados($limite = 5)
	{
		$sql = "
				SELECT proyectos.id
				
				FROM proyectos
				LEFT JOIN empresas ON proyectos.id_empresa = empresas.id
				
				WHERE proyectos.estado = 3
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
	
	
	public function comboProyectos($parametros = null, $null = null)
	{
		$sql = "	
				SELECT proyectos.id, proyectos.titulo
				
				FROM proyectos
		
				WHERE proyectos.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND proyectos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND proyectos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND proyectos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND proyectos.estado = 9";
			}
			
			if (!empty($parametros['id_empresa']))
			{
				$sql .= " AND proyectos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " proyectos.titulo";
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
			if (!empty($null)) $res[null] = $null;
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['titulo'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}