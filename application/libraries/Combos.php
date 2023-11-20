<?php defined('BASEPATH') or exit('No direct script access allowed');


class Combos
{
	private $_CI;

	public function __construct()
	{
		$this->_CI =& get_instance();
	}


	function categoriasGenerales($padre = null, $seleccionada = null, $niveles = 10, $nivel = null)
	{
		$sql = "
				SELECT id, categoria
				
				FROM categorias_generales
				
				WHERE grupo = ?
				AND estado > 0
			";
				
		$sql .= (isset($padre)) ? " AND padre = $padre" : " AND padre IS NULL";
		
		$sql .= " ORDER BY categoria ASC
		";
		
		$placeholders[] = $this->_CI->usuario->grupo;
		
		$query = $this->_CI->db->query($sql, $placeholders);
		
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		
		$nivel += 1;
	
		if ($niveles >= $nivel)
		{
			if ($query->num_rows())
			{
				foreach ($res as $row)
				{
					$select = ($seleccionada == $row['id']) ? true : false;
				        
				    $menu[] = array('id'=>$row['id'],
									'categoria'=>$row['categoria'],
									'seleccionada'=>$select,
									'nivel'=>$nivel,
									'hijos'=>$this->categoriasGenerales($row['id'], $seleccionada, $niveles, $nivel)
									);
				}
			}
		}
				
		return (!empty($menu)) ? $menu : null;
	}
	
	
	function categoriasGeneralesCombo($padre = null, $categoria_actual = null, $publicar = null, $nivel = null)
	{
		$combo = null;
		
		if (!isset($padre))
			{
			$sql = "
					SELECT id, categoria, padre
					
					FROM categorias_generales
					
					WHERE grupo = ?
					AND padre IS NULL
					AND estado > 0
					ORDER BY orden, categoria ASC
				";
			
			$placeholders[] = $this->_CI->usuario->grupo;
			
			$query = $this->_CI->db->query($sql, $placeholders);
		
		
			if ($query)
			{
				$res = $query->result_array();
			}
			
			if ($query->num_rows())
			{
				foreach ($res as $row)
				{
					$combo .= '<optgroup>';
					$combo .= "\r\n";
					$selected = ($categoria_actual == $row['id']) ? ' selected="selected"' : '';
					$combo .= '<option value="' . $row['id'] . '"' . $selected . '>' .  strtoupper($row['categoria']) . '</option>';
					$combo .= "\r\n";
					$combo .= $this->categoriasGeneralesCombo($row['id'], $categoria_actual, $publicar);
					$combo .= '</optgroup>';
					$combo .= "\r\n";
				}
			}	
		}
		
		else
		{
			$sql = "
					SELECT id, categoria, padre
					
					FROM categorias_generales
					
					WHERE padre = ?
					AND estado > 0
					
					ORDER BY padre, orden, categoria ASC
				";
			
			$placeholders[] = $padre;
			
			$query = $this->_CI->db->query($sql, $placeholders);
		
		
			if ($query)
			{
				$res = $query->result_array();
			}
			
			$nivel += 1;
			
			if ($query->num_rows())
			{    
				foreach ($res as $row)
				{
					$separador = null;
					
					for ($i = 1; $i <= $nivel; $i++) { $separador .= '&nbsp;&nbsp;'; }
						
					$selected = ($categoria_actual == $row['id']) ? ' selected="selected"' : '';
					
					$combo .= '<option value="' . $row['id'] . '"' . $selected . '>' . $separador . '- ' .  $row['categoria'] . '</option>';
					$combo .= "\r\n";
			        
					if (!empty($row['padre'])) $combo .= $this->categoriasGeneralesCombo($row['id'], $categoria_actual, $publicar, $nivel); 
				}   
			}
		}
			
		return (!empty($combo)) ? $combo : null;
	}
	
	
	function categoriasGestion($id_seccion = 1, $seleccionada = null)
	{
		$combo = null;
		
		$sql = "
				SELECT id, categorias_gestion.nombre_categoria AS categoria
				
				FROM categorias_gestion
				
				WHERE categorias_gestion.grupo = ?
				AND categorias_gestion.id_seccion = ?
				
				ORDER BY categorias_gestion.nombre_categoria ASC
			";
		
		$placeholders[] = $this->_CI->usuario->grupo;
		$placeholders[] = $id_seccion;
			
		$query = $this->_CI->db->query($sql, $placeholders);
		
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		if (!empty($res))
		{
			foreach ($res as $obj => $value)
			{
				$combo[$value['id']] = $value['categoria'];
			}
		}
		
		return (!empty($combo)) ? $combo : null;
	}
	
	
	function idiomas()
	{
		return array('es_AR'=>'Español', 'en_US'=>'English');
	}
	
	
	function perfiles()
	{
		return array(3=>'Administrador', 4=>'Usuario', 5=>'Invitado');
	}
	
	
	function categoriasGeneralesTipo($seleccionada = null)
	{
		$combo = null;
		
		$sql = "
				SELECT id, tipo
				
				FROM categorias_generales_tipo
				
				ORDER BY tipo ASC
			";
		
		$query = $this->_CI->db->query($sql);
		
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		if (!empty($res))
		{
			$combo[] = '--- Selecciona una opción ---';
			
			foreach ($res as $obj => $value)
			{
				$combo[$value['id']] = $value['tipo'];
			}
		}
		
		return (!empty($combo)) ? $combo : null;
	}
	
	
	function frecuenciasCombo($seleccionada = null)
	{
		$combo = null;
		
		$sql = "
				SELECT id, frecuencia
				
				FROM frecuencias
				
				ORDER BY id ASC
			";
		
		$query = $this->_CI->db->query($sql);
		
		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		if (!empty($res))
		{
			foreach ($res as $obj => $value)
			{
				$combo[$value['id']] = $value['frecuencia'];
			}
		}
		
		return (!empty($combo)) ? $combo : null;
	}
	
	
	function operaciones()
	{
		return array('C'=>'Compra', 'V'=>'Venta');
	}
	
	
	function seguridades()
	{
		return array(null=>'Sin seguridad', 'tls'=>'TLS', 'ssl'=>'SSL');
	}
	

}