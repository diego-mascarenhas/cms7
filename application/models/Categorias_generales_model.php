<?php defined('BASEPATH') or exit('No direct script access allowed');


class Categorias_generales_model extends CI_Model {

	public function getCategoriasGenerales($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS categorias_generales.id, categorias_generales.categoria, categorias_generales.descripcion, categorias_generales.id_tipo, sys_monedas.simbolo, categorias_generales.valor,
				categorias_generales.caracteristicas,
					(SELECT categoria FROM categorias_generales AS padre WHERE padre.id = categorias_generales.padre) AS padre,
					(SELECT COUNT(*) FROM servicios WHERE servicios.id_categoria = categorias_generales.id AND servicios.estado = 4) AS cantidad,
				
					CASE
					   WHEN categorias_generales.estado = 1 THEN 'label-plain'
					   WHEN categorias_generales.estado = 2 THEN 'label-primary'
					END AS estado_ui_class,
					
					CASE
						WHEN categorias_generales.estado = 1 THEN 'Inactivo'
						WHEN categorias_generales.estado = 2 THEN 'Activo'
					END AS estado
				
				FROM categorias_generales
				LEFT JOIN sys_monedas ON categorias_generales.id_moneda = sys_monedas.id
		
				WHERE categorias_generales.grupo = ?
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
				$sql .= " AND categorias_generales.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND categorias_generales.estado > 0";
			}
			
			if (!empty($parametros['id_tipo']))
			{
				$sql .= " AND categorias_generales.id_tipo = ?";
				$placeholders[] = $parametros['id_tipo'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (categorias_generales.categoria REGEXP '" . $value . "'";
				$sql .= " OR categorias_generales.descripcion REGEXP '" . $value . "'";
				$sql .= " OR categorias_generales.caracteristicas REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (categorias_generales.categoria LIKE '%" . $value . "%'";
				$sql .= " OR categorias_generales.descripcion LIKE '%" . $value . "%'";
				$sql .= " OR categorias_generales.caracteristicas LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " padre, orden";
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


	public function getCategoriaGeneralDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *
				FROM categorias_generales
			";
		}
		else
		{
			$sql = " 	
					SELECT categorias_generales.id, categorias_generales.categoria, frecuencias.frecuencia, categorias_generales.descripcion, categorias_generales.id_tipo, sys_monedas.moneda, sys_monedas.simbolo, categorias_generales.convertir, categorias_generales.valor, categorias_generales.descuento, categorias_generales.caracteristicas, categorias_generales.orden,
						(SELECT categoria FROM categorias_generales AS padre WHERE padre.id = categorias_generales.padre) AS padre,
						
						CASE
							WHEN categorias_generales.estado = 1 THEN 'Inactivo'
							WHEN categorias_generales.estado = 2 THEN 'Activo'
						END AS estado
				
					FROM categorias_generales
					LEFT JOIN frecuencias ON categorias_generales.frecuencia = frecuencias.id
					LEFT JOIN sys_monedas ON categorias_generales.id_moneda = sys_monedas.id
				";
		}
		
		$sql .= " 
				WHERE categorias_generales.grupo = ?
				AND categorias_generales.estado > 0
				AND categorias_generales.id = ?		
			";
		
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
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
	
	
	public function getCategoriaGeneralDetalleRaw($id)
	{
		return $this->getCategoriaGeneralDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarCategoriaGeneral($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		$data['categoria'] = trim($valores['categoria']);
		if (isset($valores['id_tipo'])) $data['id_tipo'] = (!empty($valores['id_tipo'])) ? $valores['id_tipo'] : null;
		
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? stripslashes(trim($valores['descripcion'])) : null;
		if (isset($valores['caracteristicas'])) $data['caracteristicas'] = (!empty($valores['caracteristicas'])) ? stripslashes(trim($valores['caracteristicas'])) : null;
		
		if (!empty($valores['id_moneda'])) $data['id_moneda'] = $valores['id_moneda'];
		$data['convertir'] = (!empty($valores['convertir']) && $data['id_moneda'] > 1) ? $valores['convertir'] : null;
		if (!empty($valores['valor'])) $data['valor'] = $valores['valor'];
		if (!empty($valores['descuento'])) $data['descuento'] = $valores['descuento'];
		if (!empty($valores['frecuencia'])) $data['frecuencia'] = $valores['frecuencia'];
		
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 0;
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->username;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('categorias_generales', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarCategoriaGeneral($id, $valores)
	{
		if (isset($valores['categoria'])) $data['categoria'] = (!empty($valores['categoria'])) ? $valores['categoria'] : null;
		if (isset($valores['id_tipo'])) $data['id_tipo'] = (!empty($valores['id_tipo'])) ? $valores['id_tipo'] : null;
		
		if (isset($valores['descripcion'])) $data['descripcion'] = (!empty($valores['descripcion'])) ? stripslashes(trim($valores['descripcion'])) : null;
		if (isset($valores['caracteristicas'])) $data['caracteristicas'] = (!empty($valores['caracteristicas'])) ? stripslashes(trim($valores['caracteristicas'])) : null;
		
		if (!empty($valores['id_moneda'])) $data['id_moneda'] = $valores['id_moneda'];
		$data['convertir'] = (!empty($valores['convertir']) && $data['id_moneda'] > 1) ? $valores['convertir'] : null;
		if (!empty($valores['valor'])) $data['valor'] = $valores['valor'];
		if (!empty($valores['descuento'])) $data['descuento'] = $valores['descuento'];
		if (!empty($valores['frecuencia'])) $data['frecuencia'] = $valores['frecuencia'];
		
		if (isset($valores['padre'])) $data['padre'] = (!empty($valores['padre'])) ? $valores['padre'] : null;
		if (isset($valores['orden'])) $data['orden'] = (!empty($valores['orden'])) ? $valores['orden'] : 0;
		
		if (!empty($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->username;

		
		// permisos	
		if (!isset($res['error']) && $this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('categorias_generales', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		return (!empty($res)) ? $res : null;
	}


	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}