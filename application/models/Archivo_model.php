<?php defined('BASEPATH') or exit('No direct script access allowed');


class Archivo_model extends CI_Model {
	
	public function getArchivos($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS archivos.id, archivos.nombre, archivos.archivo, archivos.fecha_alta, contactos.username AS contacto, archivos.id_referencia, sys_items.item, sys_items.uri
				
				FROM archivos
				LEFT JOIN contactos ON archivos.username_alta = contactos.id
				LEFT JOIN sys_items ON archivos.id_referencia = sys_items.id
				
				WHERE archivos.grupo = ?
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
				$sql .= " AND archivos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND archivos.estado > 0";
			}
			
			if (!empty($parametros['id_padre']))
			{
				$sql .= " AND archivos.id_padre = ?";
				$placeholders[] = $parametros['id_padre'];
			}
			
			if (!empty($parametros['id_referencia']))
			{
				$sql .= " AND archivos.id_referencia = ?";
				$placeholders[] = $parametros['id_referencia'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (archivos.nombre REGEXP '" . $value . "'";
				$sql .= " OR archivos.archivo REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (archivos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR archivos.archivo LIKE '%" . $value . "%') ";
			}
			
			// group
			$sql .= " GROUP BY archivos.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " archivos.id";
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
	
	
	public function ingresar($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if (!empty($valores['id_referencia'])) $data['id_referencia'] =  $valores['id_referencia'];
		if (!empty($valores['id_padre'])) $data['id_padre'] =  $valores['id_padre'];
		
		$data['id_tipo'] = $valores['id_tipo'];
		$data['nombre'] = $valores['nombre'];
		$data['archivo'] = $valores['archivo'];
		$data['peso'] = (!empty($valores['peso'])) ? $valores['peso'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		$data['fecha_alta'] = (!empty($valores['fecha_alta'])) ? $valores['fecha_alta'] : now();
		$data['username_alta'] = $this->usuario->id;
	
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('archivos', $data);
			
			$res['id'] = $this->db->insert_id();
		}
	
		return (!empty($res)) ? $res : null;
	}


}