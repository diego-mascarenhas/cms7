<?php defined('BASEPATH') or exit('No direct script access allowed');


class Emailer_model extends CI_Model {

	public function getNewsletters($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_newsletters.id, empresas.empresa, empresas.id AS id_empresa, empresas.empresa AS remitente, empresas.email, emailer_newsletters.asunto, emailer_newsletters_envios_stats.suscriptores, emailer_listas.lista, emailer_newsletters.desde, emailer_newsletters.hasta, 
				
					CASE
					   WHEN emailer_newsletters.estado = 1 THEN 'Nuevo'
					   WHEN emailer_newsletters.estado = 2 THEN 'Programado'
					   WHEN emailer_newsletters.estado = 3 THEN 'Enviando'
					   WHEN emailer_newsletters.estado = 4 THEN 'Detenido'
					   WHEN emailer_newsletters.estado = 5 THEN 'Finalizado'
					   WHEN emailer_newsletters.estado = 6 THEN 'Iniciando envío'
					   WHEN emailer_newsletters.estado = 7 THEN 'En pausa'
					END AS estado,
					
					CASE
					   WHEN emailer_newsletters.estado = 1 THEN 'label-warning'
					   WHEN emailer_newsletters.estado = 2 THEN 'label-info'
					   WHEN emailer_newsletters.estado = 3 THEN 'label-primary'
					   WHEN emailer_newsletters.estado = 4 THEN 'label-danger'
					   WHEN emailer_newsletters.estado = 5 THEN 'label-plain'
					   WHEN emailer_newsletters.estado = 6 THEN 'label-primary'
					   WHEN emailer_newsletters.estado = 7 THEN 'label-info'
					END AS estado_ui_class
					
				FROM emailer_newsletters
				LEFT JOIN empresas ON emailer_newsletters.id_empresa = empresas.id
				LEFT JOIN emailer_listas ON emailer_newsletters.id_lista = emailer_listas.id
				LEFT JOIN emailer_newsletters_envios_stats ON emailer_newsletters.id = emailer_newsletters_envios_stats.id_newsletter
				
				WHERE emailer_newsletters.grupo = ?
				AND emailer_newsletters.estado > 0
				
				#GROUP BY emailer_newsletters.id
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_newsletters.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_newsletters.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_newsletters.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (emailer_newsletters.remitente REGEXP '" . $value . "'";
				$sql .= " OR emailer_newsletters.asunto REGEXP '" . $value . "'";
				$sql .= " OR emailer_newsletters.newsletter REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (emailer_newsletters.remitente LIKE '%" . $value . "%'";
				$sql .= " OR emailer_newsletters.asunto LIKE '%" . $value . "%'";
				$sql .= " OR emailer_newsletters.newsletter LIKE '%" . $value . "%') ";
			}
			
			// group
			$sql .= " GROUP BY emailer_newsletters.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_newsletters.fecha_alta";
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


	public function getNewsletterDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
		            SELECT emailer_newsletters.*
					FROM emailer_newsletters
		        ";
		}
		else
		{
			$sql = "
					SELECT emailer_newsletters.id, empresas.empresa AS remitente, empresas.email, emailer_newsletters.asunto, emailer_newsletters.id_template, emailer_newsletters_envios_stats.suscriptores, emailer_listas.lista, emailer_listas.id AS id_lista, emailer_newsletters.desde, emailer_newsletters.hasta, 
					
						CASE
						   WHEN emailer_newsletters.estado = 1 THEN 'Nuevo'
						   WHEN emailer_newsletters.estado = 2 THEN 'Programado'
						   WHEN emailer_newsletters.estado = 3 THEN 'Enviando'
						   WHEN emailer_newsletters.estado = 4 THEN 'Detenido'
						   WHEN emailer_newsletters.estado = 5 THEN 'Finalizado'
						   WHEN emailer_newsletters.estado = 6 THEN 'Crítica'
						   WHEN emailer_newsletters.estado = 7 THEN 'Crítica'
						END AS estado,
						
						CASE
						   WHEN emailer_newsletters.estado = 1 THEN 'label-danger'
						   WHEN emailer_newsletters.estado = 2 THEN 'label-warning'
						   WHEN emailer_newsletters.estado = 3 THEN 'label-information'
						   WHEN emailer_newsletters.estado = 4 THEN 'label-primary'
						   WHEN emailer_newsletters.estado = 5 THEN 'label-primary'
						   WHEN emailer_newsletters.estado = 6 THEN 'label-primary'
						   WHEN emailer_newsletters.estado = 7 THEN 'label-primary'
						END AS estado_ui_class
						
					FROM emailer_newsletters
					LEFT JOIN empresas ON emailer_newsletters.id_empresa = empresas.id
					LEFT JOIN emailer_listas ON emailer_newsletters.id_lista = emailer_listas.id
					LEFT JOIN emailer_newsletters_envios_stats ON emailer_newsletters.id = emailer_newsletters_envios_stats.id_newsletter
				";
		}
		
		$sql .= " 
				WHERE emailer_newsletters.grupo = ?
				AND emailer_newsletters.estado > 0
				AND emailer_newsletters.id = ?
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
			$sql .= " AND emailer_newsletters.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getNewsletterDetalleEnvio($id)
	{
		$sql = "
				SELECT emailer_newsletters.id, emailer_newsletters.grupo, emailer_newsletters.id_empresa, empresas.empresa AS remitente, empresas.email, emailer_newsletters.asunto, emailer_newsletters.id_template, emailer_newsletters.id_lista, emailer_newsletters.desde, emailer_newsletters.hasta
								
				FROM emailer_newsletters
				LEFT JOIN empresas ON emailer_newsletters.id_empresa = empresas.id

			WHERE emailer_newsletters.estado > 0
			AND emailer_newsletters.id = ?
		";
		
		
		// consulta
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getNewsletterDetalleRaw($id)
	{
		return $this->getNewsletterDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getNewsletterStats($id)
	{
		$sql = "
				SELECT emailer_newsletters_envios_estados.estado, COUNT(emailer_newsletters_envios.id) as cantidad
				FROM emailer_newsletters_envios_estados
				LEFT JOIN emailer_newsletters_envios ON emailer_newsletters_envios.estado = emailer_newsletters_envios_estados.id
				
				WHERE emailer_newsletters_envios.id_newsletter = ?
				GROUP BY emailer_newsletters_envios_estados.estado
		";
		
		
		// consulta
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);
		
				
		if (!isset($res['error']) && $query)
		{
			$results = $query->result_array();
			
			foreach ($results as $row)
			{
				$res[$row['estado']] = $row['cantidad'];
			}
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getNewsletterDestinatariosStats($id)
	{
		$sql = "
				SELECT COUNT(contactos.id) AS cantidad, SUBSTRING_INDEX(contactos.email, '@', -1) AS dominio
				FROM emailer_newsletters_envios
				LEFT JOIN contactos ON emailer_newsletters_envios.id_contacto = contactos.id
				
				WHERE emailer_newsletters_envios.id_newsletter = ?
				
				GROUP BY SUBSTRING_INDEX(contactos.email, '@', -1)
				ORDER BY cantidad DESC
				LIMIT 5
		";
		
		
		// consulta
		$placeholders[] = $id;
		
		$query = $this->db->query($sql, $placeholders);
		
				
		if (!isset($res['error']) && $query)
		{
			$results = $query->result_array();
			
			foreach ($results as $row)
			{
				$res[$row['dominio']] = $row['cantidad'];
			}
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarNewsletter($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['asunto'] = stripslashes(trim($valores['asunto']));
		$data['mensaje'] = $valores['mensaje'];
		
		if (isset($valores['id_lista'])) $data['id_lista'] = (!empty($valores['id_lista'])) ? $valores['id_lista'] : null;
		if (isset($valores['id_template'])) $data['id_template'] = (!empty($valores['id_template'])) ? $valores['id_template'] : null;
		
		if (isset($valores['desde'])) $data['desde'] = (!empty($valores['desde'])) ? strtotime('+3 HOURS', strtotime($valores['desde'])) : null;
		if (isset($valores['hasta'])) $data['hasta'] = (!empty($valores['hasta'])) ? strtotime('+3 HOURS', strtotime($valores['hasta'])) : null;
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('emailer_newsletters', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function modificarNewsletter($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		$data['asunto'] = stripslashes(trim($valores['asunto']));
		$data['mensaje'] = $valores['mensaje'];
		
		if (isset($valores['id_lista'])) $data['id_lista'] = (!empty($valores['id_lista'])) ? $valores['id_lista'] : null;
		if (isset($valores['id_template'])) $data['id_template'] = (!empty($valores['id_template'])) ? $valores['id_template'] : null;
		
		if (isset($valores['desde'])) $data['desde'] = (!empty($valores['desde'])) ? strtotime('+3 HOURS', strtotime($valores['desde'])) : null;
		if (isset($valores['hasta'])) $data['hasta'] = (!empty($valores['hasta'])) ? strtotime('+3 HOURS', strtotime($valores['hasta'])) : null;
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		$res = $this->db->update('emailer_newsletters', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function asociarCategorias($id_news, $valores)
	{		
		if (is_array($valores['categorias']))
		{
			$this->db->delete('emailer_rel_newsletter_categorias', array('id_newsletter' => $id_news));
			
			foreach ($valores['categorias'] as $value)
			{						
				if (!empty($value))
				{
					$data[] = array('id_newsletter'=>$id_news,
									'id_categoria'=>$value
									);
				}
				
				$this->db->insert_batch('emailer_rel_newsletter_categorias', $data);
			}
		}
		else
		{
			$data['id_newsletter'] = $id_news;
			$data['id_categoria'] = $valores;

			$this->db->insert('emailer_rel_newsletter_categorias', $data);
		}
	}
	
	
	public function getNewslettersCategorias($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_categorias.id, emailer_categorias.categoria, empresas.empresa, empresas.id AS id_empresa, emailer_categorias.fecha_alta, emailer_categorias.estado AS id_estado,
				
					CASE
					   WHEN emailer_categorias.estado = 1 THEN 'Inactiva'
					   WHEN emailer_categorias.estado = 2 THEN 'Activa'
					END AS estado,
					
					CASE
					   WHEN emailer_categorias.estado = 1 THEN 'label-danger'
					   WHEN emailer_categorias.estado = 2 THEN 'label-primary'
					END AS estado_ui_class
					
				FROM emailer_categorias
				LEFT JOIN empresas ON emailer_categorias.id_empresa = empresas.id
				
				WHERE emailer_categorias.grupo = ?
				AND emailer_categorias.estado > 0
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_categorias.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_categorias.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_categorias.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND emailer_categorias.categoria REGEXP '" . $value . "'";
			}
			
			// group
			$sql .= " GROUP BY emailer_categorias.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_categorias.categoria";
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
	
	
	public function getNewslettersCategoriaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
					SELECT *
					FROM emailer_categorias
			";
		}
		else
		{
			$sql = "
					SELECT emailer_categorias.id, emailer_categorias.categoria, emailer_categorias.fecha_alta, emailer_categorias.estado AS id_estado,
					
						CASE
						   WHEN emailer_categorias.estado = 1 THEN 'Inactiva'
						   WHEN emailer_categorias.estado = 2 THEN 'Activa'
						END AS estado,
						
						CASE
						   WHEN emailer_categorias.estado = 1 THEN 'label-danger'
						   WHEN emailer_categorias.estado = 2 THEN 'label-primary'
						END AS estado_ui_class
						
					FROM emailer_categorias 
				";
		}
		
		$sql .= "
				WHERE emailer_categorias.grupo = ?
				AND emailer_categorias.estado > 0
				AND emailer_categorias.id = ?
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_categorias.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			$sql .= " AND emailer_categorias.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getNewslettersCategoriaDetalleRaw($id)
	{
		return $this->getNewslettersCategoriaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarNewsletterCategoria($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['categoria'] = stripslashes(trim($valores['categoria']));
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('emailer_categorias', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarNewsletterCategoria($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['categoria'] = stripslashes(trim($valores['categoria']));
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		$res = $this->db->update('emailer_categorias', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getNewslettersListas($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_listas.id, emailer_listas.lista, empresas.empresa, empresas.id AS id_empresa, emailer_listas.fecha_alta, emailer_listas.estado AS id_estado,
				
					CASE
					   WHEN emailer_listas.estado = 1 THEN 'Inactiva'
					   WHEN emailer_listas.estado = 2 THEN 'Activa'
					END AS estado,
					
					CASE
					   WHEN emailer_listas.estado = 1 THEN 'label-danger'
					   WHEN emailer_listas.estado = 2 THEN 'label-primary'
					END AS estado_ui_class
					
				FROM emailer_listas
				LEFT JOIN empresas ON emailer_listas.id_empresa = empresas.id
				
				WHERE emailer_listas.grupo = ?
				AND emailer_listas.estado > 0
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_listas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_listas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_listas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND emailer_listas.categoria REGEXP '" . $value . "'";
			}
			
			// group
			$sql .= " GROUP BY emailer_listas.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_listas.lista";
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
	
	
	public function getNewslettersListaDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw' || $parametros['modo'] == 'envio')
		{
			$sql = " 	
					SELECT *
					FROM emailer_listas
			";
		}
		else
		{
			$sql = "
					SELECT emailer_listas.id, emailer_listas.lista, emailer_listas.filtros, emailer_listas.fecha_alta, emailer_listas.estado AS id_estado,
					
						CASE
						   WHEN emailer_listas.estado = 1 THEN 'Inactiva'
						   WHEN emailer_listas.estado = 2 THEN 'Activa'
						END AS estado,
						
						CASE
						   WHEN emailer_listas.estado = 1 THEN 'label-danger'
						   WHEN emailer_listas.estado = 2 THEN 'label-primary'
						END AS estado_ui_class
						
					FROM emailer_listas
				";
		}
		
		
		// permisos
		if ($parametros['modo'] == 'envio')
		{
			$sql .= "
					WHERE emailer_listas.id = ?
				";
			
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'reseller')
		{
			$sql .= "
					WHERE emailer_listas.grupo = ?
					AND emailer_listas.estado > 0
					AND emailer_listas.id = ?
				";
			
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_listas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
			
			$placeholders[] = $id;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_listas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
			
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
	
	
	public function getNewslettersListaDetalleRaw($id)
	{
		return $this->getNewslettersListaDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getNewslettersListaDetalleEnvio($id)
	{
		return $this->getNewslettersListaDetalle($id, array('modo'=>'envio'));
	}
	
	
	public function ingresarNewsletterLista($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['lista'] = stripslashes(trim($valores['lista']));
		if (isset($valores['filtros'])) $data['filtros'] = (!empty($valores['filtros'])) ? $valores['filtros'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('emailer_listas', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarNewsletterLista($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['lista'] = stripslashes(trim($valores['lista']));
		if (isset($valores['filtros'])) $data['filtros'] = (!empty($valores['filtros'])) ? $valores['filtros'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		$res = $this->db->update('emailer_listas', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	

	public function getNewslettersStats($parametros = null)
	{
		$sql = "
				SELECT sys_grupos.grupo, emailer_newsletters.grupo AS id_grupo, emailer_newsletters.id, emailer_newsletters.newsletter, emailer_newsletters.remitente, emailer_newsletters.email, emailer_newsletters.asunto,
				
				UNIX_TIMESTAMP(CONVERT_TZ(emailer_newsletters.enviar, '+00:00', @@global.time_zone)) AS enviar,
				
				UNIX_TIMESTAMP(CONVERT_TZ(IF(emailer_newsletters.estado=1, '', IF(emailer_newsletters.estado>1, emailer_newsletters.enviar, emailer_newsletters.inicio)), '+00:00', @@global.time_zone)) AS inicio,
				
				UNIX_TIMESTAMP(CONVERT_TZ(IF(emailer_newsletters.estado=5, emailer_newsletters.final, null), '+00:00', @@global.time_zone)) AS final,
				
				emailer_newsletters_envios_stats.suscriptores, emailer_newsletters_envios_stats.restantes, emailer_newsletters_envios_stats.fallidos, emailer_newsletters_envios_stats.enviados, emailer_newsletters_envios_stats.rechazados, emailer_newsletters_envios_stats.recibidos, emailer_newsletters_envios_stats.abiertos, emailer_newsletters_envios_stats.desuscriptos, emailer_newsletters_envios_stats.clicks, emailer_newsletters_envios_stats.aberturas, emailer_newsletters_envios_stats.ratio,
				
					CASE
					   WHEN emailer_newsletters.estado = 1 THEN 'Nuevo'
					   WHEN emailer_newsletters.estado = 2 THEN 'Programado'
					   WHEN emailer_newsletters.estado = 3 THEN 'Enviando'
					   WHEN emailer_newsletters.estado = 4 THEN 'Detenido'
					   WHEN emailer_newsletters.estado = 5 THEN 'Finalizado'
					   WHEN emailer_newsletters.estado = 6 THEN 'Iniciando envío'
					   WHEN emailer_newsletters.estado = 7 THEN 'En pausa'
					END AS estado,
					
					CASE
					   WHEN emailer_newsletters.estado = 1 THEN 'label-warning'
					   WHEN emailer_newsletters.estado = 2 THEN 'label-info'
					   WHEN emailer_newsletters.estado = 3 THEN 'label-primary'
					   WHEN emailer_newsletters.estado = 4 THEN 'label-danger'
					   WHEN emailer_newsletters.estado = 5 THEN 'label-plain'
					   WHEN emailer_newsletters.estado = 6 THEN 'label-primary'
					   WHEN emailer_newsletters.estado = 7 THEN 'label-info'
					END AS estado_ui_class
					
				FROM emailer_newsletters
				LEFT JOIN emailer_newsletters_envios_stats ON emailer_newsletters.id = emailer_newsletters_envios_stats.id_newsletter
				LEFT JOIN sys_grupos ON emailer_newsletters.grupo = sys_grupos.id
				
				WHERE emailer_newsletters.estado > 0
			";
		
			
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND emailer_newsletters.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND emailer_newsletters.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (emailer_newsletters.remitente REGEXP '" . $value . "'";
				$sql .= " OR emailer_newsletters.asunto REGEXP '" . $value . "'";
				$sql .= " OR emailer_newsletters.newsletter REGEXP '" . $value . "') ";
			}
			
			// group
			//$sql .= " GROUP BY emailer_newsletters.id";
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " FIELD(emailer_newsletters.estado,3,2,4,5,1)";
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
	
	
	public function getNewslettersEnviosErrores($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_newsletters_envios.id_newsletter, IF(emailer_newsletters_envios.enviado, 'Rechazado', 'Fallido') AS estado, IF(emailer_newsletters_envios.enviado, 'label-danger', 'label-warning') AS estado_ui_class, emailer_newsletters_envios.email, emailer_newsletters_envios.error, emailer_newsletters_envios.id_error, emailer_servidores.host
									
				FROM emailer_newsletters_envios
				LEFT JOIN emailer_servidores ON emailer_newsletters_envios.id_smtp = emailer_servidores.id
				
				#WHERE enviado IS NULL AND error IS NOT NULL
				WHERE error IS NOT NULL
			";
		
			
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['search']))
			{
				$sql .= " AND emailer_newsletters_envios.id_newsletter = ?";
				$placeholders[] = $parametros['search'];
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_newsletters_envios.id";
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
	
	
	public function getCantidadDeemailesRestantes()
	{
		$sql = "
				SELECT COUNT(envios.id) AS cantidad
				FROM emailer_newsletters_envios AS envios
				LEFT JOIN emailer_newsletters ON envios.id_newsletter = emailer_newsletters.id
				WHERE envios.enviado IS NULL
				AND envios.error IS NULL
				AND envios.id_error IS NULL
				AND emailer_newsletters.estado = 3
			";
		
		
		// consulta
		$query = $this->db->query($sql);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['cantidad'];
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getContactoCategorias($id)
	{
		$sql = "
				SELECT emailer_categorias.id, emailer_categorias.categoria, emailer_categorias.fecha_alta, emailer_categorias.estado AS id_estado,
				
					CASE
					   WHEN emailer_categorias.estado = 1 THEN 'Inactiva'
					   WHEN emailer_categorias.estado = 2 THEN 'Activa'
					END AS estado,
					
					CASE
					   WHEN emailer_categorias.estado = 1 THEN 'label-danger'
					   WHEN emailer_categorias.estado = 2 THEN 'label-primary'
					END AS estado_ui_class
					
				FROM emailer_categorias
				LEFT JOIN emailer_rel_contactos_categorias ON emailer_categorias.id = emailer_rel_contactos_categorias.id_categoria
				
				WHERE emailer_categorias.grupo = ?
				AND emailer_rel_contactos_categorias.id_contacto = ?
				AND emailer_categorias.estado > 0
			";
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_categorias.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			$sql .= " AND emailer_categorias.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// orden
			$sql .= " ORDER BY emailer_categorias.categoria DESC";
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function listaDinamica($parametros = null, $id_newsletter = null)
	{
		if (isset($id_newsletter))
		{
			$sql = "
	            	SELECT SQL_CALC_FOUND_ROWS contactos.id, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, contactos.username, COALESCE(contactos.celular, contactos.telefono, empresas.telefono) AS telefono
					
					FROM contactos
					LEFT JOIN empresas ON contactos.id_empresa = empresas.id
					LEFT JOIN servicios ON servicios.id_empresa = empresas.id
					
					WHERE contactos.email IS NOT NULL
					AND contactos.grupo = ?
	        ";
		}
		else
		{
			$sql = "
					SELECT SQL_CALC_FOUND_ROWS contactos.id, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, contactos.email, contactos.username, COALESCE(contactos.celular, contactos.telefono, empresas.telefono) AS telefono, empresas.id AS id_empresa, empresas.empresa, 
				
						CASE
							WHEN contactos.estado = 1 THEN 'label-plain'
							WHEN contactos.estado = 2 THEN 'label-primary'
							WHEN contactos.estado = 3 THEN 'label-info'
							WHEN contactos.estado = 4 THEN 'label-danger'
							WHEN contactos.estado = 5 THEN 'label-warning'
							WHEN contactos.estado = 6 THEN 'label-success'
						END AS estado_ui_class,
						
						CASE
							WHEN contactos.estado = 1 THEN 'Inactivo'
							WHEN contactos.estado = 2 THEN 'Activo'
							WHEN contactos.estado = 3 THEN 'Online'
							WHEN contactos.estado = 4 THEN 'Bloqueado'
							WHEN contactos.estado = 5 THEN 'Vencido'
							WHEN contactos.estado = 6 THEN 'Sin Confirmar'
						END AS estado
					
					FROM contactos
					LEFT JOIN empresas ON contactos.id_empresa = empresas.id
					LEFT JOIN servicios ON servicios.id_empresa = empresas.id
			
					WHERE contactos.email IS NOT NULL
					AND contactos.grupo = ?
				";
		}
		
			
		// permisos
		$placeholders[] = $parametros['grupo'];
		
		if (isset($parametros['id_empresa']))
		{
			$sql .= " AND contactos.id_empresa = ?";
			$placeholders[] = $parametros['id_empresa'];
		}
		
		
		if (!isset($res['error']))
		{
			// filtros
			// Enviado
			if (!empty($id_newsletter))
			{
				$sql .= " AND contactos.id NOT IN (SELECT contactos.id FROM emailer_newsletters_envios WHERE emailer_newsletters_envios.id_newsletter = ? AND emailer_newsletters_envios.id_contacto = contactos.id)";
				$placeholders[] = $id_newsletter;
			}
			
			// Categoría del contacto
			if (!empty($parametros['contactos_categorias']))
			{
				$contactos_categorias = implode(',', $parametros['contactos_categorias']);
				
				$sql .= " AND ? IN (SELECT id_categoria FROM emailer_rel_contactos_categorias WHERE emailer_rel_contactos_categorias.id_contacto = contactos.id)";
				$placeholders[] = $contactos_categorias;
			}
			
			
			// Estado del contacto
			if (!empty($parametros['contactos_estados']))
			{
				$sql .= " AND (";
				
				$i = 0;
				$cantidad = count($parametros['contactos_estados']);
				
				foreach ($parametros['contactos_estados'] as $obj)
				{
					$sql .= " contactos.estado = ? ";
					$placeholders[] = $obj;
					
					if ($i != $cantidad-1) $sql .= " OR ";
					
					$i++;
				}
				
				$sql .= " )";
			}
			else
			{
				$sql .= " AND contactos.estado > 0";
			}


			// Categoría del servicio
			if (!empty($parametros['servicios_categorias']))
			{
				$servicios_categorias = implode(',', $parametros['servicios_categorias']);
				
				$sql .= " AND ? IN (SELECT id_categoria FROM servicios WHERE servicios.id_empresa = empresas.id AND servicios.estado = 4)";
				$placeholders[] = $servicios_categorias;
			}
			
			
			// Estado del servicio
			if (!empty($parametros['servicios_estados']))
			{
				$sql .= " AND (";
				
				$i = 0;
				$cantidad = count($parametros['servicios_estados']);
				
				foreach ($parametros['servicios_estados'] as $obj)
				{
					$sql .= " servicios.estado = ? ";
					$placeholders[] = $obj;
					
					if ($i != $cantidad-1) $sql .= " OR ";
					
					$i++;
				}
				
				$sql .= " )";
			}
			else
			{
				$sql .= " AND servicios.estado > 0";
			}
			

			// group
			$sql .= " GROUP BY contactos.id";
			
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
	
	
	// cmsMailer
	public function getSuscriptoresActivos($parametros = null)
	{
		$sql = "
				SELECT admin_contactos.id, admin_contactos.nombre, admin_contactos.apellido, admin_contactos.email, admin_contactos.estado
					
				FROM admin_contactos
				
				WHERE admin_contactos.grupo != 502
				AND admin_contactos.estado > 1
				AND admin_contactos.email NOT IN (SELECT contactos.email FROM contactos WHERE contactos.email = admin_contactos.email AND contactos.grupo = 502)
			";
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['id']))
			{
				$sql .= " AND admin_contactos.id > ?";
				$placeholders[] = $parametros['id'];
			}
			
			if (!empty($parametros['estado']))
			{
				$sql .= " AND admin_contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			
			// orden
			$sql .= " ORDER BY admin_contactos.id ASC";
			
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
	

	public function verificarSiExiste($email, $grupo)
	{
		$sql = "
				SELECT id
					
				FROM contactos
				
				WHERE grupo = ?
				AND email = ?
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $email;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id'];
		}		

		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarSuscriptor($valores)
	{
		$data['grupo'] = $valores['grupo'];
		$data['id_empresa'] = $valores['id_empresa'];
		
		if (!empty($valores['email']))
		{
			$data['email'] = $valores['email'];
		}
		else
		{
			$res['error'] = 'Debe especificar un email';
		}
		
		if (isset($valores['nombre'])) $data['nombre'] = (!empty($valores['nombre'])) ? $valores['nombre'] : null;
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;

		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : 6;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 1;
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = 80;

		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('contactos', $data);
			
			$res = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExisteEnLaCategoria($id_contacto, $id_categoria)
	{
		$sql = "
				SELECT true
					
				FROM emailer_rel_contactos_categorias
				
				WHERE id_contacto = ?
				AND id_categoria = ?
			";
			
		$placeholders[] = $id_contacto;
		$placeholders[] = $id_categoria;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}
	
	
	public function agregarSuscriptorALaCategoria($id_contacto, $id_categoria, $grupo)
	{
		$data['grupo'] = $grupo;
		$data['id_contacto'] = $id_contacto;
		$data['id_categoria'] = $id_categoria;
		
		
		$this->db->insert('emailer_rel_contactos_categorias', $data);
	}
	
	
	public function ultimoSuscriptorDeLaCategoria($id_categoria)
	{
		$sql = "	
				SELECT ultimo AS id_contacto
				
				FROM emailer_categorias_stats

				WHERE id_categoria = ?
			";
		
		$placeholders[] = $id_categoria;
		
		if (!isset($res['error']))
		{
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array()['id_contacto'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function setUltimoSuscriptorDeLaCategoria($id_contacto, $id_categoria)
	{	
		$data['ultimo'] = $id_contacto;;
		
		$res = $this->db->update('emailer_categorias_stats', $data, array('id_categoria'=>$id_categoria));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getTemplates($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_templates.id, emailer_templates.template, empresas.empresa, empresas.id AS id_empresa, emailer_templates.fecha_alta,
				
					CASE
					   WHEN emailer_templates.estado = 1 THEN 'Inactivo'
					   WHEN emailer_templates.estado = 2 THEN 'Activo'
					END AS estado,
					
					CASE
					   WHEN emailer_templates.estado = 1 THEN 'label-plain'
					   WHEN emailer_templates.estado = 2 THEN 'label-primary'
					END AS estado_ui_class
					
				FROM emailer_templates
				LEFT JOIN empresas ON emailer_templates.id_empresa = empresas.id
				
				WHERE emailer_templates.grupo = ?
				AND emailer_templates.estado > 0
			";
		
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND emailer_templates.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_templates.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_templates.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (emailer_templates.template REGEXP '" . $value . "'";
				$sql .= " OR emailer_templates.codigo REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (emailer_templates.template LIKE '%" . $value . "%'";
				$sql .= " OR emailer_templates.codigo LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_templates.fecha_alta";
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


	public function getTemplateDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
	            SELECT emailer_templates.*
				FROM emailer_templates
	        ";
		}
		else
		{
			$sql = "
					SELECT SQL_CALC_FOUND_ROWS emailer_templates.id, emailer_templates.template, empresas.empresa,
				
						CASE
						   WHEN emailer_templates.estado = 1 THEN 'Inactivo'
						   WHEN emailer_templates.estado = 2 THEN 'Activo'
						END AS estado,
						
						CASE
						   WHEN emailer_templates.estado = 1 THEN 'label-plain'
						   WHEN emailer_templates.estado = 2 THEN 'label-primary'
						END AS estado_ui_class
						
					FROM emailer_templates
					LEFT JOIN empresas ON emailer_templates.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE emailer_templates.grupo = ?
				AND emailer_templates.estado > 0
				AND emailer_templates.id = ?
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
			$sql .= " AND emailer_templates.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getTemplateDetalleRaw($id)
	{
		return $this->getTemplateDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarTemplate($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$data['template'] = stripslashes(trim($valores['template']));
		$data['codigo'] = $valores['codigo'];
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('emailer_templates', $data);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}


	public function modificarTemplate($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$data['id_empresa'] = $valores['id_empresa'];
			}
			else
			{
				$data['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (isset($valores['template'])) $data['template'] = (!empty($valores['template'])) ? stripslashes(trim($valores['template'])) : null;
		if (isset($valores['codigo'])) $data['codigo'] = (!empty($valores['codigo'])) ? $valores['codigo'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = $valores['estado'];
		
		
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		$res = $this->db->update('emailer_templates', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	
	
	function comboListas($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT emailer_listas.id, emailer_listas.lista AS descripcion
				
				FROM emailer_listas
				
				WHERE emailer_listas.grupo = ?
			";
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_listas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_listas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND emailer_listas.estado > 0";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_listas.lista";
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
	
	
	function comboTemplates($parametros = null)
	{
		$combo = null;
		
		$sql = "
				SELECT emailer_templates.id, emailer_templates.template AS descripcion
				
				FROM emailer_templates
				
				WHERE emailer_templates.grupo = ?
			";
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND emailer_templates.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND emailer_templates.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND emailer_templates.estado > 0";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " emailer_templates.template";
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
	
	
	public function getNewslettersParaEnviar($limite = 5)
	{
		$sql = "SELECT id
		
				FROM emailer_newsletters
				
				WHERE desde <= UNIX_TIMESTAMP(NOW())
				AND hasta >= UNIX_TIMESTAMP(NOW())-86400
				
				LIMIT ?";
		
		$query = $this->db->query($sql, array($limite));
		
		if ($query)
		{
			$res = $query->result_array();
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarEnvio($grupo, $id_newsletter, $id_contacto, $smtp = null)
	{
		$data['grupo'] = $grupo;
		$data['id_newsletter'] = $id_newsletter;
		$data['id_contacto'] = $id_contacto;
		$data['id_smtp'] = $smtp;

		$data['enviar'] = now();
		$data['estado'] = 2;

		$insert = $this->db->insert('emailer_newsletters_envios', $data);

		if (isset($insert))
		{
			$res = $this->db->insert_id();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function marcarEnvioComoEnviado($id)
	{
		$data['enviado'] = now();
		$data['estado'] = 3;

		$res = $this->db->update('emailer_newsletters_envios', $data, array('id'=> $id));

		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function marcarEnvioComoFallido($id)
	{
		$data['estado'] = 6;

		$res = $this->db->update('emailer_newsletters_envios', $data, array('id'=> $id));

		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function track($id)
	{
		$data['recibido'] = now();
		$data['estado'] = 4;
		
		$res = $this->db->update('emailer_newsletters_envios', $data, array('id'=>$id, 'estado'=>3));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}