<?php defined('BASEPATH') or exit('No direct script access allowed');


class Sys_model extends CI_Model {

	function menu($grupo, $perfil = null, $usuario = null, $padre = null, $seleccionada = false, $niveles = 10, $nivel = null)
	{
		$sql = "SELECT sys_items.id, sys_items.item, sys_items.uri, sys_items.ui_class
				
				FROM sys_items
				LEFT JOIN sys_rel_perfiles_items ON sys_rel_perfiles_items.id_item = sys_items.id
				LEFT JOIN sys_rel_grupos_items ON sys_rel_grupos_items.id_item = sys_items.id
				
				WHERE sys_items.estado = 2
				AND sys_rel_perfiles_items.id_item = sys_items.id
				AND sys_rel_grupos_items.grupo = " . $grupo;
		
		if (isset($perfil)) $sql .= " AND sys_rel_perfiles_items.id_perfil = " . $perfil;
		if (isset($usuario)) $sql .= " AND sys_items.id NOT IN (SELECT id_item FROM sys_items_blacklist WHERE id_user = " . $usuario . ")";
		$sql .= (isset($padre)) ? " AND sys_items.padre = $padre" : " AND sys_items.padre IS NULL";
		
		$sql .= " ORDER BY sys_items.orden ASC";
		
		// consulta
		$query = $this->db->query($sql);
			
		if ($query && $niveles >= ++$nivel)
		{	
			foreach($query->result_array() as $row)
			{
				$select = ($seleccionada == $row['id']) ? true : false;
				
				$res[] = array(
								'grupo'=>$grupo,
								'id'=>$row['id'],
								'item'=>$row['item'],
								'uri'=>$row['uri'],
								'ui_class'=>$row['ui_class'],
								'seleccionada'=>$select,
								'nivel'=>$nivel,
								'hijos'=>$this->menu($grupo, $perfil, $usuario, $row['id'], $seleccionada, $niveles, $nivel)
								);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboPerfiles()
	{
		$sql = "
				SELECT sys_perfiles.id, sys_perfiles.perfil AS descripcion
				
				FROM sys_perfiles
				
				WHERE sys_perfiles.estado = 2
				AND sys_perfiles.id >= ?
				AND sys_perfiles.id > 2
				
				ORDER BY sys_perfiles.id ASC
			";

		
		// permisos	
		$placeholders[] = $this->usuario->id_perfil;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboConfigTipo()
	{
		$sql = "
				SELECT sys_config_tipo.id, sys_config_tipo.tipo AS descripcion
				
				FROM sys_config_tipo
				
				ORDER BY sys_config_tipo.tipo ASC
			";

		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboPaises()
	{
		$sql = "
				SELECT sys_paises.id, sys_paises.pais AS descripcion
				
				FROM sys_paises
				
				WHERE estado = 2
				
				ORDER BY pais ASC
			";

		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			$res[] = '--- Selecciona un país ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboProvincias($id_pais = null)
	{
		if (!isset($id_pais)) $res['error'] = 'No se especificó una país';
		
		$sql = "
				SELECT sys_provincias.id, sys_provincias.provincia AS descripcion
				
				FROM sys_provincias
				
				WHERE sys_provincias.id_pais = ?
				AND estado = 2
				
				ORDER BY provincia ASC
			";

		
		// consulta
		$query = $this->db->query($sql, $id_pais);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			$res[] = '--- Selecciona una provincia ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function comboLocalidades($id_provincia = null)
	{
		if (!isset($id_provincia)) $res['error'] = 'No se especificó una provincia';
		
		$sql = "
				SELECT sys_localidades.id, sys_localidades.localidad AS descripcion
				
				FROM sys_localidades
				
				WHERE sys_localidades.id_provincia = ?
				AND estado = 2
				
				ORDER BY localidad ASC
			";

		
		// consulta
		$query = $this->db->query($sql, $id_provincia);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			$res[] = '--- Selecciona una localidad ---';
			
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}

	
	function localidadProvinciaPais($id = null)
	{
		if (!isset($id)) $res['error'] = 'No se especificó una provincia';
		
		$sql = "
				SELECT sys_localidades.id AS id_localidad, sys_provincias.id AS id_provincia, sys_paises.id AS id_pais
				
				FROM sys_localidades
				LEFT JOIN sys_provincias ON sys_localidades.id_provincia = sys_provincias.id
				LEFT JOIN sys_paises ON sys_provincias.id_pais = sys_paises.id
				
				WHERE sys_localidades.id = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($id));
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	function comboCategoriasGenerales()
	{
		$sql = "
				SELECT categorias_generales.id, CONCAT(categorias_generales.categoria, ' (', categorias_generales_padre.categoria, ')') AS descripcion
				
				FROM categorias_generales
				LEFT JOIN categorias_generales AS categorias_generales_padre ON categorias_generales.padre = categorias_generales_padre.id
				
				WHERE categorias_generales.grupo = ?
				AND categorias_generales.estado = 2
				AND categorias_generales.padre IS NOT NULL
				
				ORDER BY categorias_generales.padre, categorias_generales.orden ASC
			";

		
		// permisos	
		$placeholders[] = $this->usuario->grupo;
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
			
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
	
	
	public function getMercadoPagoCredenciales($id)
	{
			$sql = "	
					SELECT grupos.id AS grupo, grupos.mp_user AS client_id, grupos.mp_pass AS client_secret, grupos.mp_cuenta AS id_cuenta, false AS use_access_token, false AS sandbox_mode
					
					FROM grupos
					
					WHERE grupos.id = ?
				";
		
		
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));


		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getPayPalCredenciales($id)
	{
			$sql = "	
					SELECT grupos.id AS grupo, grupos.paypal_email as paypal_business_email, grupos.paypal_cuenta AS id_cuenta, grupos.paypal_moneda AS paypal_currency_code, false AS paypal_sandbox_mode
					
					FROM grupos
					
					WHERE grupos.id = ?
				";
		
		
		// consulta
		$query = $this->db->query($sql, array(
				$id
			));


		if ($query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function track($tipo='A', $error=NULL, $referencia=NULL, $padre=NULL)
	{
		$data['tipo'] = $tipo;
		if (isset($error)) $data['log'] = $error;
		$data['url'] = $_SERVER['HTTP_HOST'];
		$data['uri'] = $_SERVER['REQUEST_URI'];
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$data['navegador'] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null;
		if (isset($this->usuario->id)) $data['id_user'] = $this->usuario->id;
		if (isset($referencia)) $data['id_referencia'] = $referencia;
		if (isset($padre)) $data['id_padre'] = $padre;
		$data['estado'] = 2;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('sys_logs', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getLogs($parametros)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS sys_logs.id, sys_logs.tipo, sys_logs.fecha, sys_logs.log, sys_logs.estado
				
				FROM sys_logs
				
				WHERE 1
			";
		
		
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND sys_logs.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND sys_logs.estado > 0";
			}
			
			if (!empty($parametros['tipo']))
			{
				$sql .= " AND sys_logs.tipo = ?";
				$placeholders[] = $parametros['tipo'];
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (sys_logs.id LIKE '%" . $value . "%'";
				$sql .= " OR sys_logs.log LIKE '%" . $value . "%'";
				$sql .= " OR sys_logs.uri LIKE '%" . $value . "%'";
				$sql .= " OR sys_logs.id_padre LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " sys_logs.id";
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
	
	
	public function getLog($tipo)
	{
		$sql = "	
				SELECT sys_logs.id, sys_logs.log
				
				FROM sys_logs
				
				WHERE sys_logs.estado = 2
				AND tipo = ?	
			";
		
		
		// consulta
		$placeholders['tipo'] = $tipo;
		
		$query = $this->db->query($sql, $placeholders);
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function finalizar($id)
	{
		$res = $this->db->update('sys_logs', array('estado'=>5), array('id'=>$id));
		
		return (!empty($res)) ? true : false;
	}
	
	
	public function getEstado($id, $tabla)
	{
		$this->db->select('estado');
		$this->db->from($tabla);
		$this->db->where('id', $id);
		
		// consulta
		$query = $this->db->get();
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['estado'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarFecha($id, $tabla)
	{
		$data['fecha'] = now();
			
		$res = $this->db->update($tabla, $data, array('id'=> $id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function eliminar($id, $tabla)
	{
		$data['estado'] = $this->getEstado($id, $tabla)*-1;
		
		$res = $this->db->update($tabla, $data, array('id'=> $id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarPropiedad($id, $tabla)
	{
		$this->db->select('*');
		$this->db->from($tabla);
		$this->db->where('id', $id);
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$this->db->where('grupo', $this->usuario->grupo);
		}
		else
		{
			$this->db->where('grupo', $this->usuario->grupo);
			
			if ($tabla == 'empresas')
			{
				$this->db->where('id', $this->usuario->id_empresa);
			}
			else
			{
				$this->db->where('id_empresa', $this->usuario->id_empresa);
			}
		}
		
		
		// consulta
		$query = $this->db->get();
		
		if (!isset($res['error']) && $query->row_array())
		{
			return true;	
		}
		else
		{
			return false;
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboFormasDePago($null = null)
	{
		if (!empty($null)) $res[null] = $null;
		$res[1] = 'Pago en efectivo';
		$res[2] = 'Transferencia';
		$res[5] = 'Débito Automático';
		$res[3] = 'Depósito Bancario';
		$res[10] = 'Tarjeta de crédito';
		$res[13] = 'Mercado Pago';
		//$res[12] = 'Mercado Pago (Suscripción)';
		$res[7] = 'PayPal';
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function comboCondicionesFiscales()
	{
		$res = array(3=>'Consumidor final', 2=>'Monotributista', 1=>'Responsable inscripto', 4=>'I.V.A. exento');
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function setAlerta($key, $value)
	{
		$data['alertas'] = $this->session->userdata('alertas');
		$data['alertas'][$key] = $value;

		foreach ($data['alertas'] as $key => $value)
		{
			if (empty($value)) unset($data['alertas'][$key]);
		}
			
		$this->session->set_userdata($data);
	}
	
	
	public function getTags($parametros = null)
	{
		$sql = "	
				SELECT sys_tags.id, sys_tags.tag
				
				FROM sys_tags
				
				WHERE sys_tags.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND sys_tags.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$sql .= " AND sys_tags.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND sys_tags.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND sys_tags.estado > 0";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " sys_tags.tag";
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
	
	
	public function getTagIdTag($tag)
	{	
		$this->db->select('id');
		$this->db->from('sys_tags');
		$this->db->where('grupo', $this->usuario->grupo);
		$this->db->where('id_empresa', $this->usuario->id_empresa);
		$this->db->where('tag', $tag);

		return $this->db->get()->row('id');	
	}
	
	
	public function asociarTags($id_tipo, $id_referencia, $tags)
	{
		if (is_array($tags))
		{
			$this->db->delete('sys_rel_tags', array('id_tipo' => $id_tipo, 'id_referencia' => $id_referencia));
			
			foreach (array_map('trim', $tags) as $value)
			{						
				if (!empty($value))
				{
					if (!is_integer($value)) $value = $this->getTagIdTag($value);
					
					if ($this->verificarPropiedad($value, 'sys_tags'))
					{
						$data[] = array('id_tipo'=>$id_tipo,
										'id_referencia'=>$id_referencia,
										'id_tag'=>$value
										);
					}
				}
			}
			
			if (isset($data)) $res = $this->db->insert_batch('sys_rel_tags', $data);
		}
/*
		else
		{
			$data['id_tipo'] = $id_tipo;
			$data['id_referencia'] = $id_referencia;
			$data['id_tag'] = $tags;

			$res = $this->db->insert('sys_rel_tags', $data);
		}
*/

		return (!empty($res)) ? $res : null;
	}
	
	
	function comboMonedas()
	{
		$sql = "
				SELECT sys_monedas.id, sys_monedas.moneda AS descripcion
				
				FROM sys_monedas
				
				WHERE estado = 2
			";
	
		
		// consulta
		$query = $this->db->query($sql);
			
		if (!isset($res['error']) && $query)
		{
			$row = $query->result_array();
		}
		
		if (!empty($row))
		{
			foreach ($row as $obj => $value)
			{
				$res[$value['id']] = $value['descripcion'];
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}
	
	
}