<?php defined('BASEPATH') or exit('No direct script access allowed');


class Newsletter_model extends CI_Model {

	public function getNewsletters($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS emailer_newsletters.id, emailer_newsletters.newsletter, emailer_newsletters.remitente, emailer_newsletters.email, emailer_newsletters.asunto, emailer_newsletters.enviar, IF(emailer_newsletters.estado=1, '', IF(emailer_newsletters.estado>1, emailer_newsletters.enviar, emailer_newsletters.inicio)) AS inicio, IF(emailer_newsletters.estado=5, emailer_newsletters.final, null) AS final, emailer_newsletters_envios_stats.suscriptores, GROUP_CONCAT(emailer_categorias.categoria SEPARATOR ', ') AS categorias,
				
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
				LEFT JOIN emailer_rel_newsletter_categorias ON emailer_newsletters.id = emailer_rel_newsletter_categorias.id_newsletter
				LEFT JOIN emailer_categorias ON emailer_categorias.id = emailer_rel_newsletter_categorias.id_categoria
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
			else
			{
				$sql .= " AND emailer_newsletters.estado > 0";
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
					SELECT emailer_newsletters.id, emailer_newsletters.newsletter, emailer_newsletters.remitente, emailer_newsletters.email, emailer_newsletters.asunto, emailer_newsletters.enviar, IF(emailer_newsletters.estado=1, '', IF(emailer_newsletters.estado>1, emailer_newsletters.enviar, emailer_newsletters.inicio)) AS inicio, IF(emailer_newsletters.estado=5, emailer_newsletters.final, null) AS final, emailer_newsletters_envios_stats.suscriptores, GROUP_CONCAT(emailer_categorias.categoria SEPARATOR ', ') AS categorias,
					
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
					LEFT JOIN emailer_rel_newsletter_categorias ON emailer_newsletters.id = emailer_rel_newsletter_categorias.id_newsletter
					LEFT JOIN emailer_categorias ON emailer_categorias.id = emailer_rel_newsletter_categorias.id_categoria
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
	
	
	public function getNewsletterDetalleRaw($id)
	{
		return $this->getNewsletterDetalle($id, array('modo'=>'raw'));
	}

	
	public function ingresarNewsletter($items)
	{
		$news['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$news['id_empresa'] = $items['id_empresa'];
			}
			else
			{
				$news['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$news['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$news['remitente'] = stripslashes(trim($items['remitente']));
		$news['email'] = $items['email'];
		if (isset($items['estado'])) $news['estado'] = $items['estado'];
		
		if (isset($items['respuesta_email']) && !empty($items['respuesta_email']))
		{
			$news['respuesta_email'] = $items['respuesta_email'];
			$news['respuesta_nombre'] = (isset($items['respuesta_nombre'])) ? stripslashes(trim($items['respuesta_nombre'])) : $items['respuesta_nombre'] = stripslashes(trim($news['remitente']));
		}
		
		$news['asunto'] = stripslashes(trim($items['asunto']));
		$news['newsletter'] = (!empty($items['newsletter'])) ? stripslashes(trim($items['newsletter'])) : $items['newsletter'] = $news['asunto'];
		$news['mensaje'] = $items['mensaje'];
		
		$news['enviar'] = (isset($items['enviar'])) ? now() : now()+60;

		
		$news['fecha_alta'] = now();
		$news['username_alta'] = $this->usuario->id;

		$insert = $this->db->insert('emailer_newsletters', $news);

		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}
	
	public function asociarCategorias($id_news, $items)
	{		
		if (is_array($items['categorias']))
		{
			$this->db->delete('emailer_rel_newsletter_categorias', array('id_newsletter' => $id_news));
			
			foreach ($items['categorias'] as $value)
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
			$data['id_categoria'] = $items;

			$this->db->insert('emailer_rel_newsletter_categorias', $data);
		}
	}


	public function modificarNewsletter($id, $items)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa']))
			{
				$news['id_empresa'] = $items['id_empresa'];
			}
			else
			{
				$news['id_empresa'] = $this->usuario->id_empresa;
			}
		}
		else
		{
			$news['id_empresa'] = $this->usuario->id_empresa;
		}
		
		$news['remitente'] = stripslashes(trim($items['remitente']));
		$news['email'] = $items['email'];
		if (isset($items['estado'])) $news['estado'] = $items['estado'];
		
		if (isset($items['respuesta_email']) && !empty($items['respuesta_email']))
		{
			$news['respuesta_email'] = $items['respuesta_email'];
			$news['respuesta_nombre'] = (isset($items['respuesta_nombre'])) ? stripslashes(trim($items['respuesta_nombre'])) : $items['respuesta_nombre'] = stripslashes(trim($news['remitente']));
		}
		elseif (isset($items['respuesta_email']))
		{
			$news['respuesta_email'] = null;
			$news['respuesta_nombre'] = null;
		}
		
		$news['asunto'] = stripslashes(trim($items['asunto']));
		$news['newsletter'] = (!empty($items['newsletter'])) ? stripslashes(trim($items['newsletter'])) : $items['newsletter'] = $news['asunto'];
		$news['mensaje'] = $items['mensaje'];
		
		$news['enviar'] = (isset($items['enviar'])) ? now() : now()+60;

		
		$news['fecha_modificacion'] = now();
		$news['username_modificacion'] = $this->usuario->id;
		
		$res = $this->db->update('emailer_newsletters', $news, array('id'=> $id));

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
				
				WHERE (emailer_newsletters.estado > 1 AND emailer_newsletters.estado < 4)
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
	
	
	
	

	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}