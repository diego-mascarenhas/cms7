<?php defined('BASEPATH') or exit('No direct script access allowed');


class Empresa_model extends CI_Model {

	public function getEmpresas($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS empresas.id, empresas.empresa, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, empresas.telefono, referidos.empresa AS referido, referidos.id AS id_referido, empresas.username_alta, UNIX_TIMESTAMP(empresas.fecha_alta) AS fecha_alta,
				
					(SELECT GROUP_CONCAT(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) SEPARATOR ', ')
					FROM contactos
					LEFT JOIN empresas_rel_contactos ON empresas_rel_contactos.id_contacto = contactos.id
					WHERE empresas_rel_contactos.id_empresa = empresas.id) AS agentes,
				
					CASE
						WHEN empresas.estado = 1 THEN 'label-plain'
						WHEN empresas.estado = 2 THEN 'label-primary'
						WHEN empresas.estado = 3 THEN 'label-warning'
						WHEN empresas.estado = 4 THEN 'label-plain'
						WHEN empresas.estado = 5 THEN 'label-info'
						WHEN empresas.estado = 6 THEN 'label-success'
						WHEN empresas.estado = 7 THEN 'label-danger'
					END AS estado_ui_class,
					
					CASE
						WHEN empresas.estado = 1 THEN 'Inactivo'
						WHEN empresas.estado = 2 THEN 'Activo'
						WHEN empresas.estado = 3 THEN 'Prospecto'
						WHEN empresas.estado = 4 THEN 'Nuevo'
						WHEN empresas.estado = 5 THEN 'Asignado'
						WHEN empresas.estado = 6 THEN 'Contactado'
						WHEN empresas.estado = 7 THEN 'Esperando Respuesta'
					END AS estado

				FROM empresas
				
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				LEFT JOIN empresas AS referidos ON referidos.id = empresas.referido

				WHERE empresas.grupo = ?
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
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND empresas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND empresas.estado > 0";
			}
			
			if (isset($parametros['prospectos']))
			{
				$sql .= " AND (empresas.estado = 3 OR empresas.estado = 4 OR empresas.estado = 5 OR empresas.estado = 6 OR empresas.estado = 7)";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (referidos.empresa REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR empresas.codigo REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (referidos.empresa LIKE '%" . $value . "%'";
				$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR contactos.email LIKE '%" . $value . "%'";
				$sql .= " OR empresas.codigo LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " empresa";
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


	public function getEmpresaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT *
					FROM empresas
				";
		}
		else
		{
			$sql = " 	
					SELECT empresas.id, empresas.codigo, facturas_tipo.factura_tipo AS tipo_factura, empresas.id_factura_tipo, formas_pago.forma_pago, empresas.id_forma_pago, empresas.empresa, empresas.actividad, categorias_gestion.nombre_categoria AS categoria, empresas_fiscales.id AS id_empresa_fiscal, empresas_fiscales.razon_social, empresas_fiscales.cuit, empresas_fiscales.ingresos_brutos, condiciones_iva.condicion_iva, cuentas.id AS id_cuenta, cuentas.titular, cuentas.numero_documento AS documento, cuentas.cbu, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, empresas.telefono, empresas.whatsapp, referidos.id AS id_referido, referidos.empresa AS referido, empresas.username_alta, UNIX_TIMESTAMP(empresas.fecha_alta) AS fecha_alta, empresas.username_modificacion, UNIX_TIMESTAMP(empresas.fecha_modificacion) AS fecha_modificacion
					
					FROM empresas
					
					LEFT JOIN contactos ON empresas.id_contacto = contactos.id
					LEFT JOIN empresas as referidos ON referidos.id = empresas.referido
					LEFT JOIN categorias_gestion ON empresas.id_categoria = categorias_gestion.id
					LEFT JOIN servicios ON empresas.id = servicios.id_empresa
					LEFT JOIN facturas_tipo ON empresas.id_factura_tipo = facturas_tipo.id
					LEFT JOIN formas_pago ON empresas.id_forma_pago = formas_pago.id
					LEFT JOIN empresas_fiscales ON empresas.id = empresas_fiscales.id_empresa AND empresas_fiscales.estado = 1
					LEFT JOIN condiciones_iva ON empresas_fiscales.id_condicion_iva = condiciones_iva.id
					LEFT JOIN cuentas ON empresas.id = cuentas.id_empresa
				";
		}
		
		$sql .= "
				WHERE empresas.grupo = ?
				AND empresas.id = ?
				AND empresas.estado > 0
			";
		
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$query = $this->db->query($sql, array(
					$this->usuario->grupo,
					$id
				));
		}
		elseif ($this->usuario->id_empresa == $id)
		{
			$query = $this->db->query($sql, array(
					$this->usuario->grupo,
					$this->usuario->id_empresa
				));
		}
		else
		{
			$res['error'] = 'Sin permisos para acceder a la información de otra empresa';
		}
		
						
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getEmpresaDetalleRaw($id)
	{
		return $this->getEmpresaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getProspectos($parametros = null)
	{
		return $this->getEmpresas(array('prospectos'=>true, 'order_by'=>'fecha_alta', 'order'=>'DESC'));
	}


	public function ingresarEmpresa($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		$data['empresa'] = $valores['empresa'];
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		if (isset($valores['referido'])) $data['referido'] = (!empty($valores['referido'])) ? $valores['referido'] : null;
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['id_localidad'])) $data['id_localidad'] = (!empty($valores['id_localidad'])) ? $valores['id_localidad'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['whatsapp'])) $data['whatsapp'] = (!empty($valores['whatsapp'])) ? $valores['whatsapp'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['web'])) $data['web'] = (!empty($valores['web'])) ? $valores['web'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 3;

		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('empresas', $data);

			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarEmpresa($id, $valores)
	{
		if (isset($valores['empresa'])) $data['empresa'] = $valores['empresa'];
		if (isset($valores['id_categoria'])) $data['id_categoria'] = (!empty($valores['id_categoria'])) ? $valores['id_categoria'] : null;
		if (isset($valores['referido'])) $data['referido'] = (!empty($valores['referido'])) ? $valores['referido'] : null;
		if (isset($valores['domicilio'])) $data['domicilio'] = (!empty($valores['domicilio'])) ? $valores['domicilio'] : null;
		if (isset($valores['id_localidad'])) $data['id_localidad'] = (!empty($valores['id_localidad'])) ? $valores['id_localidad'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['whatsapp'])) $data['whatsapp'] = (!empty($valores['whatsapp'])) ? $valores['whatsapp'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['web'])) $data['web'] = (!empty($valores['web'])) ? $valores['web'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		
		if (isset($valores['id_contacto'])) $data['id_contacto'] = (!empty($valores['id_contacto'])) ? $valores['id_contacto'] : null;
		if (isset($valores['id_factura_tipo'])) $data['id_factura_tipo'] = (!empty($valores['id_factura_tipo'])) ? $valores['id_factura_tipo'] : null;
		if (isset($valores['id_forma_pago'])) $data['id_forma_pago'] = (!empty($valores['id_forma_pago'])) ? $valores['id_forma_pago'] : null;
		if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;

		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;


		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('empresas', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$res = $this->db->update('empresas', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		return (!empty($res)) ? $res : null;
	}


	public function validarPropiedadDeEmpresa($id)
	{
		$sql = "
				SELECT true
				FROM empresas
				WHERE empresas.grupo = ?
				AND empresas.id = ?
			";

		$query = $this->db->query($sql, array(
				$this->usuario->grupo,
				$id
			));


		if (!isset($res['error']) && $query)
		{
			$res = $query->row();
		}

		return (!empty($res)) ? true : false;
	}


	public function getEmpresasConServiciosActivos($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS empresas.id, empresas.empresa, contactos.id AS id_contacto, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, empresas.telefono, referidos.empresa AS referido, referidos.id AS id_referido

				FROM servicios
				
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				LEFT JOIN empresas as referidos ON referidos.id = empresas.referido

				WHERE servicios.grupo = ?
				AND servicios.estado = ?
				AND servicios.operacion = ?

				GROUP BY contactos.id
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
			// filtros
			$placeholders[] = (!empty($parametros['estado'])) ? $parametros['estado'] : 4;
			$placeholders[] = (!empty($parametros['operacion'])) ? $parametros['estado'] : 'V';
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (servicios.descripcion REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " empresa";
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
	
	
	public function getDatosFiscalesDetalle($id)
	{
		$sql = " 	
				SELECT empresas_fiscales.id_empresa, empresas_fiscales.id, empresas_fiscales.cuit, empresas_fiscales.ingresos_brutos, empresas_fiscales.id_condicion_iva, empresas_fiscales.razon_social
				
				FROM empresas_fiscales
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas_fiscales.id
				
				WHERE empresas_fiscales.grupo = ?
				AND empresas_fiscales.id = ?
				AND empresas_fiscales.estado > 0
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
			$sql .= " AND empresas_fiscales.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getDatosFiscalesIdFromIdEmpresa($id)
	{
		$sql = " 	
				SELECT empresas_fiscales.id
				
				FROM empresas
				LEFT JOIN empresas_fiscales ON empresas.id = empresas_fiscales.id_empresa AND empresas_fiscales.estado = 1
				
				WHERE empresas.grupo = ?
				AND empresas.id = ?
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
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarEmpresaFiscal($id, $valores)
	{
		if (isset($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		if (isset($valores['cuit'])) $data['cuit'] = (!empty($valores['cuit'])) ? $valores['cuit'] : null;
		if (isset($valores['ingresos_brutos'])) $data['ingresos_brutos'] = (!empty($valores['ingresos_brutos'])) ? $valores['ingresos_brutos'] : null;
		if (isset($valores['id_condicion_iva'])) $data['id_condicion_iva'] = (!empty($valores['id_condicion_iva'])) ? $valores['id_condicion_iva'] : null;
		if (isset($valores['razon_social'])) $data['razon_social'] = (!empty($valores['razon_social'])) ? $valores['razon_social'] : null;
		

		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;


		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('empresas_fiscales', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$res = $this->db->update('empresas_fiscales', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo, 'id_empresa'=>$this->usuario->id_empresa));
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		return (!empty($res)) ? $res : null;
	}


public function actualizarDatosFiscales($valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!isset($valores['id_empresa'])) $data['error'] = 'No se ha proporcionado el ID de la empresa';
			
			if (!isset($data['error']) && $valores['id_condicion_iva'] != 3)
			{
				$empresa_fiscal['id_condicion_iva'] = $valores['id_condicion_iva'];
	
				if ($valores['id_condicion_iva'] == 1)
				{
					$sql = "
							SELECT id
							FROM facturas_tipo
							WHERE id_afip = 1
							AND defecto = 1
							AND grupo = ?
						";
					
					$query = $this->db->query($sql, array(
							$this->usuario->grupo
						));
					
					if (!isset($res['error']) && $query)
					{
						$res = $query->row_array();
					}
					
					$empresa['id_factura_tipo'] = $res['id']; // FACTURA A
				}
				else
				{
					$sql = "
							SELECT id
							FROM facturas_tipo
							WHERE id_afip = 6
							AND defecto = 1
							AND grupo = ?
						";
					
					$query = $this->db->query($sql, array(
							$this->usuario->grupo
						));
					
					if (!isset($res['error']) && $query)
					{
						$res = $query->row_array();
					}
					
					$empresa['id_factura_tipo'] = $res['id']; // FACTURA B
				}
			}
			else 
			{
				$empresa_fiscal['id_condicion_iva'] = 3;
				
				$sql = "
						SELECT id
						FROM facturas_tipo
						WHERE id_afip = 6
						AND defecto = 1
						AND grupo = ?
					";
				
				$query = $this->db->query($sql, array(
						$this->usuario->grupo
					));
				
				if (!isset($res['error']) && $query)
				{
					$res = $query->row_array();
				}
				
				$empresa['id_factura_tipo'] = $res['id']; // FACTURA B
			}
			
			if (isset($valores['razon_social'])) $empresa_fiscal['razon_social'] = trim($valores['razon_social']);
	
	
			if (!isset($data['error']) && !empty($valores['cuit'])) // Validar formato de CUIT
			{
				$empresa_fiscal['cuit'] = trim($valores['cuit']);
				$empresa_fiscal['cuit'] = str_replace('-', '', $empresa_fiscal['cuit']);
				
	// 			if ($empresa_fiscal['id_condicion_iva'] == 3) // Consumidor Final
	// 			{
	// 				if (!preg_match( '/^(\d{1,8})$/', $empresa_fiscal['cuit']))
	// 				{
	// 				    $data['error'] = 'Introduzca un número de DNI válido.';
	// 				}
	// 			}
	// 
	// 			elseif ($empresa_fiscal['id_condicion_iva'] == 1 || $empresa_fiscal['id_condicion_iva'] == 2) // Responsable Inscripto, Monotributista
	// 			{
	// 				if (!preg_match( '/^(\d{9,12})$/', $empresa_fiscal['cuit']))
	// 				{
	// 				    $data['error'] = 'Introduzca un número de CUIT válido.';
	// 				}
	// 			}
			}
			else
			{
				$data['error'] = 'Introduzca un número de CUIT/DNI válido.';
			}
			
			
/*
			if (!isset($data['error']) && !empty($valores['cbu']))
			{
				if (!empty($valores['titular']) && !isset($data['error']))
				{
					$cuenta['titular'] = trim($valores['titular']);
				}
				else
				{
					$cuenta['titular'] = $userdata['display_name'];
				}
				
				if (!isset($data['error']) && !empty($valores['cuenta_documento'])) // Validar formato de DNI/CUIT
				{
					$cuenta['numero_documento'] = trim($valores['cuenta_documento']);
					$cuenta['numero_documento'] = str_replace('-', '', $cuenta['numero_documento']);
				
					if (!preg_match( '/^(\d{1,8})$/', $empresa_fiscal['cuit']) && !preg_match('/^(\d{1,11})$/', $empresa_fiscal['cuit']))
					{
					    $data['error'] = 'Introduzca un número de DNI/CUIT válido.';
					}
					else
					{
						$cuenta['id_documento_tipo'] = (strlen(str_replace('.', '', $cuenta['numero_documento'])) <= 8) ? 3 : 1;
					}
				}
				else
				{
					$data['error'] = 'Introduzca un número de DNI/CUIT válido.';
				}
				
				if (!isset($data['error']) && !empty($valores['cbu'])) // Validar formato de CBU
				{
					$cuenta['cbu'] = trim($valores['cbu']);
				}
				else
				{
					$data['error'] = 'Introduzca un número de CBU válido.';
				}
			}
*/
		}
		
		
		if (!isset($data['error']) && $this->usuario->perfil == 'reseller' && isset($valores['id_empresa']))
		{
			/* EMPRESA FISCAL */
			$empresa_fiscal['id_empresa'] = $valores['id_empresa'];

			if (empty($valores['id']))
			{
				$empresa_fiscal['grupo'] = $this->usuario->grupo;
				
				$empresa_fiscal['fecha_alta'] = unix_to_human(now(), true, 'eu');
				$empresa_fiscal['username_alta'] = $this->usuario->username;
				
				$this->db->insert('empresas_fiscales', $empresa_fiscal);
			}
			else
			{
				$sql = "
						SELECT cuit, id_condicion_iva
						FROM empresas_fiscales
						WHERE id = ?
					";
				
				$query = $this->db->query($sql, array(
						$valores['id']
					));
				
				if ($query)
				{
					$empresas_fiscales = $query->row_array();
				}
				
				
				if (str_replace('-', '', $empresas_fiscales['cuit']) != str_replace('-', '', $empresa_fiscal['cuit']) || $empresas_fiscales['id_condicion_iva'] != $empresa_fiscal['id_condicion_iva'])
				{
					$sql = "
							SELECT true FROM facturas
							WHERE id_empresa_fiscal = ?
						";
				
					$query = $this->db->query($sql, array(
							$valores['id']
						));
					
					if (!$query->row_array())
					{
						$empresa_fiscal['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
						$empresa_fiscal['username_modificacion'] = $this->usuario->username;
						
						$this->db->update('empresas_fiscales', $empresa_fiscal, array('id'=>$valores['id']));
					}
					else
					{
						$this->db->update('empresas_fiscales', array('estado'=>0), array('id'=>$valores['id']));
						
						$empresa_fiscal['grupo'] = $this->usuario->grupo;
						$empresa_fiscal['estado'] = 1;
				
						$empresa_fiscal['fecha_alta'] = unix_to_human(now(), true, 'eu');
						$empresa_fiscal['username_alta'] = $this->usuario->username;
						
						$this->db->insert('empresas_fiscales', $empresa_fiscal);
					}
				}
				elseif (!empty($empresa_fiscal['razon_social']))
				{
					$this->db->update('empresas_fiscales', array('razon_social'=>$empresa_fiscal['razon_social']), array('id'=>$valores['id']));
				}
			}
			
			
			/* CUENTA */
/*
			if (!isset($data['error']) && !empty($cuenta['titular']) && !empty($cuenta['numero_documento']) && !empty($cuenta['cbu']))
			{
				$cuenta['id_empresa'] = $valores['id_empresa'];
				
				if (empty($valores['id_cuenta']))
				{
					$cuenta['grupo'] = $this->usuario->grupo;
			
					$cuenta['fecha_alta'] = unix_to_human(now(), true, 'eu');
					$cuenta['username_alta'] = $this->usuario->username;
					
					$this->db->insert('cuentas', $cuenta);
				}
				else
				{
					$sql = "
							SELECT titular, numero_documento, cbu
							FROM cuentas
							WHERE id = ?
						";
					
					$query = $this->db->query($sql, array(
							$valores['id_cuenta']
						));
					
					$cuenta_original = $query->row_array();
					
					if (str_replace(' ', '', $cuenta_original['numero_documento']) != str_replace(' ', '', $cuenta['numero_documento']) || str_replace(' ', '', $cuenta_original['cbu']) != str_replace(' ', '', $cuenta['cbu']) || str_replace(' ', '', $cuenta_original['titular']) != str_replace(' ', '', $cuenta['titular']))
					{
						$cuenta['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
						$cuenta['username_modificacion'] = $this->usuario->username;
						
						$this->db->update('cuentas', $cuenta, array('id'=>$valores['id_cuenta']));
					}
				}
			}
*/
		}
		
		if (!isset($data['error'])) $data['ok'] = true;
		
		return (!empty($data)) ? $data : null;
	}
	
	
	public function getMiCuenta($parametros = null)
	{

		$sql = " 	
				SELECT empresas.id AS id_empresa, empresas.empresa, empresas.id_forma_pago, empresas.id_factura_tipo, contactos.id AS id_contacto, contactos.nombre, contactos.apellido, contactos.username, contactos.email, contactos.telefono, contactos.fecha_modificacion, empresas_fiscales.id AS id_empresa_fiscal, empresas_fiscales.cuit, empresas_fiscales.id_condicion_iva, empresas_fiscales.razon_social, cuentas.id AS id_cuenta, cuentas.titular, cuentas.cbu, cuentas.numero_documento AS cuenta_documento,
				
					CASE
						WHEN contactos.area_privada = 2 THEN 'reseller'
						WHEN contactos.area_privada = 3 THEN 'admin'
						WHEN contactos.area_privada = 4 THEN 'user'
						WHEN contactos.area_privada = 4 THEN 'guest'
					END AS perfil
				
				FROM contactos
				LEFT JOIN empresas ON contactos.id_empresa = empresas.id
				LEFT JOIN cuentas ON cuentas.id_empresa = empresas.id
				LEFT JOIN empresas_fiscales ON empresas.id = empresas_fiscales.id_empresa AND empresas_fiscales.estado = 1 
				
				WHERE empresas.grupo = ?
				AND contactos.id = ?
				AND empresas.estado > 0
			";

		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_contacto']))
			{
				$placeholders[] = $parametros['id_contacto'];
			}
			else
			{
				$placeholders[] = $this->usuario->id;
			}
		}
		elseif ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$placeholders[] = $this->usuario->id;
		}
		else
		{
			$res['error'] = 'Sin permisos para acceder a la información';
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
						
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarMiCuenta($valores)
	{

		if (!isset($data['error']) && !empty($valores['nombre']) && !empty($valores['apellido']))
		{
			$contacto['nombre'] = trim($valores['nombre']);
			$contacto['apellido'] = trim($valores['apellido']);
			
			$userdata['display_name'] = $contacto['nombre'] . ' ' . $contacto['apellido'];
		}
		else
		{
			$data['error'] = 'El nombre y el apellido no pueden estar vacíos.';
		}
		
		if (!empty($valores['telefono']) && !isset($data['error']))
		{
			$contacto['telefono'] = trim($valores['telefono']);
		}
		else
		{
			$contacto['telefono'] = null;
		}

		
		if (!isset($data['error']) && $this->usuario->perfil == 'admin')
		{		
			if (!isset($data['error']) && !empty($valores['empresa']))
			{
				$empresa['empresa'] = trim($valores['empresa']);
			}
			else
			{
				$empresa['empresa'] = $userdata['display_name'];
			}
		
			
			if (!isset($data['error']) && !empty($valores['id_forma_pago'])) $empresa['id_forma_pago'] = $valores['id_forma_pago'];
		
		
			if (!isset($data['error']) && $valores['id_condicion_iva'] != 3)
			{
				$empresa_fiscal['id_condicion_iva'] = $valores['id_condicion_iva'];
	
				if ($valores['id_condicion_iva'] == 1)
				{
					$sql = "
							SELECT id
							FROM facturas_tipo
							WHERE id_afip = 1
							AND defecto = 1
							AND grupo = ?
						";
					
					$query = $this->db->query($sql, array(
							$this->usuario->grupo
						));
					
					if (!isset($res['error']) && $query)
					{
						$res = $query->row_array();
					}
					
					$empresa['id_factura_tipo'] = $res['id']; // FACTURA A
				}
				else
				{
					$sql = "
							SELECT id
							FROM facturas_tipo
							WHERE id_afip = 6
							AND defecto = 1
							AND grupo = ?
						";
					
					$query = $this->db->query($sql, array(
							$this->usuario->grupo
						));
					
					if (!isset($res['error']) && $query)
					{
						$res = $query->row_array();
					}
					
					$empresa['id_factura_tipo'] = $res['id']; // FACTURA B
				}
				
				if ($valores['id_condicion_iva'] == 4 || $valores['id_condicion_iva'] == 1)
				{
					$empresa_fiscal['razon_social'] = trim($valores['razon_social']);
				}
				else
				{
					$empresa_fiscal['razon_social'] = $userdata['display_name'];
				}
	
			}
			else 
			{
				$empresa_fiscal['id_condicion_iva'] = 3;
				
				$sql = "
						SELECT id
						FROM facturas_tipo
						WHERE id_afip = 6
						AND defecto = 1
						AND grupo = ?
					";
				
				$query = $this->db->query($sql, array(
						$this->usuario->grupo
					));
				
				if (!isset($res['error']) && $query)
				{
					$res = $query->row_array();
				}
				
				$empresa['id_factura_tipo'] = $res['id']; // FACTURA B
				
				$empresa_fiscal['razon_social'] = $userdata['display_name'];
			}
	
	
			if (!isset($data['error']) && !empty($valores['cuit'])) // Validar formato de CUIT
			{
				$empresa_fiscal['cuit'] = trim($valores['cuit']);
				$empresa_fiscal['cuit'] = str_replace('-', '', $empresa_fiscal['cuit']);
				
				if ($empresa_fiscal['id_condicion_iva'] == 3)
				{
					if (!preg_match( '/^(\d{1,8})$/', $empresa_fiscal['cuit']))
					{
					    $data['error'] = 'Introduzca un número de DNI válido.';
					}
				}
	
				else
				{
					if (!preg_match( '/^(\d{9,12})$/', $empresa_fiscal['cuit']))
					{
					    $data['error'] = 'Introduzca un número de CUIT válido.';
					}
				}
			}
			else
			{
				$data['error'] = 'Introduzca un número de CUIT/DNI válido.';
			}
			
			
			if (!isset($data['error']) && !empty($valores['cbu']))
			{
				if (!empty($valores['titular']) && !isset($data['error']))
				{
					$cuenta['titular'] = trim($valores['titular']);
				}
				else
				{
					$cuenta['titular'] = $userdata['display_name'];
				}
				
				if (!isset($data['error']) && !empty($valores['cuenta_documento'])) // Validar formato de DNI/CUIT
				{
					$cuenta['numero_documento'] = trim($valores['cuenta_documento']);
					$cuenta['numero_documento'] = str_replace('-', '', $cuenta['numero_documento']);
				
					if (!preg_match( '/^(\d{1,8})$/', $empresa_fiscal['cuit']) && !preg_match('/^(\d{1,11})$/', $empresa_fiscal['cuit']))
					{
					    $data['error'] = 'Introduzca un número de DNI/CUIT válido.';
					}
					else
					{
						$cuenta['id_documento_tipo'] = (strlen(str_replace('.', '', $cuenta['numero_documento'])) <= 8) ? 3 : 1;
					}
				}
				else
				{
					$data['error'] = 'Introduzca un número de DNI/CUIT válido.';
				}
				
				if (!isset($data['error']) && !empty($valores['cbu'])) // Validar formato de CBU
				{
					$cuenta['cbu'] = trim($valores['cbu']);
				}
				else
				{
					$data['error'] = 'Introduzca un número de CBU válido.';
				}
			}
		}
		
		
		if (!isset($data['error']))
		{
			/* CONTACTO */
			$contacto['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$contacto['username_modificacion'] = $this->usuario->username;
			
			$this->db->update('contactos', $contacto, array('id'=>$this->usuario->id));
		}
		
		if (!isset($data['error']) && $this->usuario->perfil == 'admin')
		{
			/* EMPRESA */
			$empresa['id_contacto'] = $this->usuario->id;
			
			if (empty($this->usuario->id_empresa))
			{
				$empresa['grupo'] = $this->usuario->grupo;
				
				$empresa['fecha_alta'] = unix_to_human(now(), true, 'eu');
				$empresa['username_alta'] = $this->usuario->username;
			
				$this->db->insert('empresas', $empresa);
				
				$this->usuario->id_empresa = $this->db->insert_id();
				
				$this->db->update('contactos', array('id_empresa'=>$this->usuario->id_empresa), array('id'=>$this->usuario->id));
			}
			else
			{
				$empresa['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
				$empresa['username_modificacion'] = $this->usuario->username;
			
				$this->db->update('empresas', $empresa, array('id'=>$this->usuario->id_empresa));
			}
			
			
			/* EMPRESA FISCAL */
			$empresa_fiscal['id_empresa'] = $this->usuario->id_empresa;

			if (empty($valores['id_empresa_fiscal']))
			{
				$empresa_fiscal['grupo'] = $this->usuario->grupo;
				
				$empresa_fiscal['fecha_alta'] = unix_to_human(now(), true, 'eu');
				$empresa_fiscal['username_alta'] = $this->usuario->username;
				
				$this->db->insert('empresas_fiscales', $empresa_fiscal);
			}
			else
			{
				$sql = "
						SELECT cuit, id_condicion_iva
						FROM empresas_fiscales
						WHERE id = ?
					";
				
				$query = $this->db->query($sql, array(
						$valores['id_empresa_fiscal']
					));
				
				if ($query)
				{
					$empresas_fiscales = $query->row_array();
				}
				
				
				if (str_replace('-', '', $empresas_fiscales['cuit']) != str_replace('-', '', $empresa_fiscal['cuit']) || $empresas_fiscales['id_condicion_iva'] != $empresa_fiscal['id_condicion_iva'])
				{
					$sql = "
							SELECT true FROM facturas
							WHERE id_empresa_fiscal = ?
						";
				
					$query = $this->db->query($sql, array(
							$valores['id_empresa_fiscal']
						));
					
					if (!$query->row_array())
					{
						$empresa_fiscal['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
						$empresa_fiscal['username_modificacion'] = $this->usuario->username;
						
						$this->db->update('empresas_fiscales', $empresa_fiscal, array('id'=>$valores['id_empresa_fiscal']));
					}
					else
					{
						$this->db->update('empresas_fiscales', array('estado'=>0), array('id'=>$valores['id_empresa_fiscal']));
						
						$empresa_fiscal['grupo'] = $this->usuario->grupo;
						$empresa_fiscal['estado'] = 1;
				
						$empresa_fiscal['fecha_alta'] = unix_to_human(now(), true, 'eu');
						$empresa_fiscal['username_alta'] = $this->usuario->username;
						
						$this->db->insert('empresas_fiscales', $empresa_fiscal);
					}
				}
				elseif (!empty($empresa_fiscal['razon_social']))
				{
					$this->db->update('empresas_fiscales', array('razon_social'=>$empresa_fiscal['razon_social']), array('id'=>$valores['id_empresa_fiscal']));
				}
			}
			
			
			/* CUENTA */
			if (!isset($data['error']) && !empty($cuenta['titular']) && !empty($cuenta['numero_documento']) && !empty($cuenta['cbu']))
			{
				$cuenta['id_empresa'] = $valores['id_empresa'];
				
				if (empty($valores['id_cuenta']))
				{
					$cuenta['grupo'] = $this->usuario->grupo;
			
					$cuenta['fecha_alta'] = unix_to_human(now(), true, 'eu');
					$cuenta['username_alta'] = $this->usuario->username;
					
					$this->db->insert('cuentas', $cuenta);
				}
				else
				{
					$sql = "
							SELECT titular, numero_documento, cbu
							FROM cuentas
							WHERE id = ?
						";
					
					$query = $this->db->query($sql, array(
							$valores['id_cuenta']
						));
					
					$cuenta_original = $query->row_array();
					
					if (str_replace(' ', '', $cuenta_original['numero_documento']) != str_replace(' ', '', $cuenta['numero_documento']) || str_replace(' ', '', $cuenta_original['cbu']) != str_replace(' ', '', $cuenta['cbu']) || str_replace(' ', '', $cuenta_original['titular']) != str_replace(' ', '', $cuenta['titular']))
					{
						$cuenta['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
						$cuenta['username_modificacion'] = $this->usuario->username;
						
						$this->db->update('cuentas', $cuenta, array('id'=>$valores['id_cuenta']));
					}
				}
			}
		}
		
		if (!isset($data['error'])) $data['ok'] = true;
		
		return (!empty($data)) ? $data : null;
	}
	
	
	function comboEmpresas($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT empresas.id, empresas.empresa AS descripcion
				
				FROM empresas
				
				WHERE empresas.grupo = ?
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
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND empresas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND empresas.estado > 0";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " empresas.empresa";
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
	
	
	public function asociarContacto($id_empresa, $id_contacto)
	{
		$item['id_empresa'] = $id_empresa;
		$item['id_contacto'] = $id_contacto;
		$item['fecha_alta'] = now();

		$insert = $this->db->insert('empresas_rel_contactos', $item);

		if ($insert)
		{
			$res['id'] = $item['id_contacto'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function desasociarContacto($id_empresa, $id_contacto)
	{
		$res = $this->db->delete('empresas_rel_contactos', array('id_empresa'=>$id_empresa, 'id_contacto'=>$id_contacto));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarAsociacionDeContacto($id_empresa, $id_contacto)
	{
		$sql = "
				SELECT true
				FROM empresas_rel_contactos
				WHERE id_empresa = ?
				AND id_contacto = ?
			";
		
		
		$query = $this->db->query($sql, array(
				$id_empresa,
				$id_contacto
			));

		if ($query->row_array())
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	public function getEmpresaIdFromCodigo($grupo, $codigo)
	{	
		$this->db->select('id');
		$this->db->from('empresas');
		$this->db->where('grupo', $grupo);
		$this->db->where('codigo', $codigo);

		return $this->db->get()->row('id');	
	}
	
	
	public function getEmpresaIdFromFarcturaId($grupo, $id)
	{	
		$sql = "
				SELECT empresas_fiscales.id_empresa AS id
				
				FROM facturas
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				
				WHERE facturas.grupo = ?
				AND facturas.id = ?
			";

		// consulta
		$query = $this->db->query($sql, array(
				$grupo,
				$id
			));


		if ($query)
		{
			$res = $query->row_array()['id'];
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function empresasConDatosIncompletos($dias = 2)
	{
		$sql = "
				SELECT contactos.id, empresas.id AS id_empresa
				
				FROM empresas
				LEFT JOIN empresas_fiscales ON empresas_fiscales.id_empresa = empresas.id AND empresas_fiscales.estado = 1
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				
				WHERE empresas.estado > 0
				AND empresas_fiscales.id IS NULL
				AND EXISTS (SELECT * FROM servicios WHERE id_empresa = empresas.id AND servicios.estado >= 3 AND servicios.operacion = 'V')
				AND DATE_FORMAT(empresas.fecha_alta, '%Y-%m-%d') <= DATE_ADD(CURDATE(), INTERVAL -? DAY)
				AND contactos.email IS NOT NULL
				
				GROUP BY empresas.id
			";
			
		// consulta
		$query = $this->db->query($sql, array($dias));

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarDatosDeLaEmpresaIncompletos($id)
	{
		$sql = "
				SELECT true
				
				FROM empresas
				LEFT JOIN empresas_fiscales ON empresas_fiscales.id_empresa = empresas.id AND empresas_fiscales.estado = 1
				
				WHERE empresas.id = ?
				AND (empresas_fiscales.id IS NULL
				OR empresas.id_forma_pago IS NULL
				OR empresas.id_factura_tipo IS NULL)
			";
			
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));

		
		if ($query->row_array())
		{
			return true;	
		}
		else
		{
			return false;
		}
	}
	
	
	public function getEmpresaSaldo($id, $dias = 0)
	{
		$sql = "
				SELECT empresas.id, SUM(facturas.saldo) AS parcial, UNIX_TIMESTAMP(facturas.vencimiento) AS vencimiento,
								(SELECT COUNT(*)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.estado = 2
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS facturas,
								(SELECT SUM(facturas.saldo)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.estado = 2
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS saldo
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				
				WHERE empresas.id = ?
				AND facturas.estado = 2
				AND facturas.saldo > 0
				AND (facturas.vencimiento < DATE_ADD(CURDATE(), INTERVAL -? DAY))
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
			";
			
		// consulta
		$query = $this->db->query($sql, array($id, $dias));

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getEmpresasDeudoras($dias = 45)
	{
		$sql = "
				SELECT empresas.id, empresas.empresa, SUM(facturas.saldo) AS parcial, UNIX_TIMESTAMP(facturas.vencimiento) AS vencimiento, contactos.id AS id_contacto, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, (SELECT COUNT(*)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.operacion = 'V'
													AND facturas.estado = 2
													AND empresas.estado > 1
													#AND empresas.id_categoria = 2
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS facturas,
								(SELECT SUM(facturas.saldo)
													FROM facturas AS facturas
													LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
													WHERE empresas_fiscales.id_empresa = empresas.id
													AND facturas.operacion = 'V'
													AND facturas.estado = 2
													AND empresas.estado > 1
													#AND empresas.id_categoria = 2
													AND facturas.saldo > 0
													AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
													) AS saldo
				FROM facturas
				LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
				LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
				LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
				LEFT JOIN contactos ON empresas.id_contacto = contactos.id
				
				WHERE facturas.operacion = 'V'
				AND facturas.estado = 2
				AND empresas.estado > 1
				#AND empresas.id_categoria = 2
				AND facturas.saldo > 0
				AND (facturas.vencimiento < DATE_ADD(CURDATE(), INTERVAL -? DAY))
				#AND (facturas.id_factura_tipo = 5 OR facturas.id_factura_tipo = 15 OR facturas.id_factura_tipo = 16) # FACTURA S, FACTURA A, FACTURA B
				AND NOT EXISTS (SELECT * FROM facturas AS nota WHERE nota.padre = facturas.id)
				AND contactos.email IS NOT NULL

				AND empresas.grupo = ?
				
				GROUP BY empresas.id
				ORDER BY facturas.vencimiento, facturas.id ASC
			";

		// filtros
		$placeholders['dias'] = $dias;
		$placeholders['grupo'] = $this->usuario->grupo;
			
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function cambiarEstado($id, $estado)
	{
		$res = $this->db->update('empresas', array('estado'=>$estado), array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($empresa)
	{
		$sql = "
				SELECT id
				
				FROM empresas
				
				WHERE grupo = ?
				AND empresa = ?
			";
		
		
		// filtros
		$placeholders['grupo'] = $this->usuario->grupo;
		$placeholders['empresa'] = $empresa;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		$res = $query->row_array()['id'];

		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}