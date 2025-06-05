<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hosting_model extends CI_Model {

	public function getCredenciales($id)
	{
		$sql = "	
				SELECT hosting_servidores.ip, hosting_servidores.user, hosting_servidores.pass
				
				FROM hosting_servidores
				
				WHERE hosting_servidores.id = ?
				AND hosting_servidores.pass IS NOT NULL
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
	
	
	public function getWhmCredenciales($id)
	{
		$sql = "	
				SELECT grupos.whm_id_servidor AS id_servidor, grupos.whm_host AS ip, grupos.whm_user AS user, grupos.whm_pass AS pass
				
				FROM grupos
				
				WHERE grupos.id = ?	
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
	
	
	public function getServerIdFromIp($ip)
	{	
		$this->db->select('id');
		$this->db->from('hosting_servidores');
		$this->db->where('ip', $ip);

		return $this->db->get()->row('id');	
	}
	
	
	public function getServerIdFromUser($user)
	{	
		$this->db->select('id_servidor');
		$this->db->from('servicios_hosting');
		$this->db->where('user', $user);

		return $this->db->get()->row('id_servidor');	
	}
	
	
	public function getIps($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS hosting_servidores_ips.id, hosting_servidores_ips.ip, hosting_servidores_ips.descripcion, hosting_servidores_ips.observaciones, hosting_servidores.servidor, hosting_servidores_ips.reputacion, hosting_servidores_ips.blacklist, hosting_servidores_ips.blacklist_data, 
				
					CASE
					   WHEN hosting_servidores_ips.estado = 1 THEN 'Inactivo'
					   WHEN hosting_servidores_ips.estado = 2 THEN 'Suspender'
					   WHEN hosting_servidores_ips.estado = 3 THEN 'Activar'
					   WHEN hosting_servidores_ips.estado = 4 THEN 'Activo'
					END AS estado,
					
					CASE
					   WHEN hosting_servidores_ips.estado = 1 THEN 'label-plain'
					   WHEN hosting_servidores_ips.estado = 2 THEN 'label-danger'
					   WHEN hosting_servidores_ips.estado = 3 THEN 'label-warning'
					   WHEN hosting_servidores_ips.estado = 4 THEN 'label-primary'
					END AS estado_ui_class
				
				FROM hosting_servidores_ips
				LEFT JOIN hosting_servidores ON hosting_servidores_ips.id_servidor = hosting_servidores.id
		
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
				$sql .= " AND hosting_servidores_ips.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND hosting_servidores_ips.estado > 0";
			}
			
			if (!empty($parametros['blacklist']))
			{
				$sql .= " AND hosting_servidores_ips.blacklist = ?";
				$placeholders[] = $parametros['blacklist'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (hosting_servidores_ips.ip REGEXP '" . $value . "'";
				$sql .= " OR hosting_servidores_ips.descripcion REGEXP '" . $value . "'";
				$sql .= " OR hosting_servidores.servidor REGEXP '" . $value . "'";
				$sql .= " OR hosting_servidores_ips.observaciones REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (hosting_servidores_ips.ip LIKE '%" . $value . "%'";
				$sql .= " OR hosting_servidores_ips.descripcion LIKE '%" . $value . "%'";
				$sql .= " OR hosting_servidores.servidor LIKE '%" . $value . "%'";
				$sql .= " OR hosting_servidores_ips.observaciones LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " hosting_servidores_ips.ip";
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
	
	
	public function getPlanes($parametros = null)
	{
		$sql = "
				SELECT SQL_CALC_FOUND_ROWS servicios_hosting.id, servicios_hosting.id_servicio, servicios_hosting.ip, servicios_hosting.user, servicios_hosting.domain, servicios_hosting.diskused, servicios_hosting.disklimit, servicios_hosting.bandwidthused, servicios_hosting.bandwidthlimit, hosting_servidores.servidor, servicios_hosting.fecha, servicios.estado AS id_estado, servicios_estado.estado, categorias_generales.categoria AS plan, empresas.empresa, empresas.id AS id_empresa, categorias_generales.categoria AS plan,
				
				@diskused_porcentaje:=IF(diskused>=disklimit, 99, ROUND(diskused*100/disklimit)) AS diskused_porcentaje,
				@bandwidthused_porcentaje:=IF(bandwidthlimit>0, IF(bandwidthused>=bandwidthlimit, 99, ROUND(bandwidthused*100/bandwidthlimit)), 0) AS bandwidthused_porcentaje,
				
				@porcentaje:=ROUND(IF(@diskused_porcentaje>=@bandwidthused_porcentaje, @diskused_porcentaje, @bandwidthused_porcentaje)) AS porcentaje,
				
					CASE
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 1 THEN 'label-plain'
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended IS NULL THEN 'label-danger'
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 2 THEN 'label-plain'
					   WHEN servicios.estado = 2 THEN 'label-danger'
					   WHEN servicios.estado = 3 THEN 'label-success'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended IS NULL THEN 'label-primary'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 1 THEN 'label-danger'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 2 THEN 'label-warning'
					END AS estado_ui_class,
				
					CASE
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 1 THEN 'Suspendido'
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended IS NULL THEN 'No se factura pero está activo'
					   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 2 THEN 'Eliminado'
					   WHEN servicios.estado = 2 THEN 'Suspender'
					   WHEN servicios.estado = 3 THEN 'Activar'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended IS NULL THEN 'Activo'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 1 THEN 'Se factura y no está activo'
					   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 2 THEN 'No está vinculado'
					END AS estado,
					
					CASE
					   WHEN @porcentaje > 95 THEN 'progress-bar-danger'
					   WHEN @porcentaje > 80 THEN 'progress-bar-warning'
					END AS progress_ui_class
									
				FROM servicios_hosting
				LEFT JOIN servicios ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN hosting_servidores ON hosting_servidores.id = servicios_hosting.id_servidor
				
				WHERE servicios.grupo = ?
				AND empresas.estado > 0
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
		elseif ($this->usuario->perfil == 'admin')
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
			}
			
			if (!empty($parametros['dashboard']))
			{
				$sql .= " AND diskused > 0 AND bandwidthused > 0";
				$sql .= " AND servicios.estado = 4";
				$sql .= " AND ((IF(diskused>=disklimit, 99, ROUND(diskused*100/disklimit)) > 80 OR IF(bandwidthlimit>0, IF(bandwidthused>=bandwidthlimit, 99, ROUND(bandwidthused*100/bandwidthlimit)), 0) > 80) OR servicios_hosting.ip IS NULL)";
			}
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (servicios_hosting.ip REGEXP '" . $value . "'";
				$sql .= " OR servicios_hosting.domain REGEXP '" . $value . "'";
				$sql .= " OR servicios_hosting.rdns REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " porcentaje";
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
	
	
	public function getPlanDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = "
					SELECT servicios_hosting.*
		
					FROM servicios_hosting
					LEFT JOIN servicios ON servicios_hosting.id_servicio = servicios.id
					LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
					LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
					LEFT JOIN empresas ON servicios.id_empresa = empresas.id
					LEFT JOIN hosting_servidores ON hosting_servidores.id = servicios_hosting.id_servidor
					
					WHERE servicios.grupo = ?
					AND empresas.estado > 0
					AND servicios_hosting.id = ?
				";
		}
		else
		{
			$sql = "
					SELECT servicios_hosting.id, servicios_hosting.id_servicio, servicios_hosting.ip, servicios_hosting.user, servicios_hosting.domain, servicios_hosting.diskused, servicios_hosting.disklimit, servicios_hosting.bandwidthused, servicios_hosting.bandwidthlimit, hosting_servidores.servidor, servicios_hosting.unix_startdate AS fecha_alta, servicios_hosting.fecha, servicios.estado AS id_estado, servicios_estado.estado, categorias_generales.categoria AS plan, empresas.empresa, empresas.id AS id_empresa, categorias_generales.categoria AS plan, servicios_hosting.id_servidor,
					
					@diskused_porcentaje:=IF(diskused>=disklimit, 99, ROUND(diskused*100/disklimit)) AS diskused_porcentaje,
					@bandwidthused_porcentaje:=IF(bandwidthlimit>0, IF(bandwidthused>=bandwidthlimit, 99, ROUND(bandwidthused*100/bandwidthlimit)), 0) AS bandwidthused_porcentaje,
					
					@porcentaje:=ROUND(IF(@diskused_porcentaje>=@bandwidthused_porcentaje, @diskused_porcentaje, @bandwidthused_porcentaje)) AS porcentaje,
					
						CASE
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 1 THEN 'label-plain'
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended IS NULL THEN 'label-danger'
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 2 THEN 'label-plain'
						   WHEN servicios.estado = 2 THEN 'label-danger'
						   WHEN servicios.estado = 3 THEN 'label-success'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended IS NULL THEN 'label-primary'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 1 THEN 'label-danger'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 2 THEN 'label-warning'
						END AS estado_ui_class,
					
						CASE
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 1 THEN 'Suspendido'
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended IS NULL THEN 'No se factura pero está activo'
						   WHEN servicios.estado = 1 AND servicios_hosting.suspended = 2 THEN 'Eliminado'
						   WHEN servicios.estado = 2 THEN 'Suspender'
						   WHEN servicios.estado = 3 THEN 'Activar'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended IS NULL THEN 'Activo'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 1 THEN 'Se factura y no está activo'
						   WHEN servicios.estado = 4 AND servicios_hosting.suspended = 2 THEN 'Se factura y está eliminado'
						END AS estado,
						
						CASE
						   WHEN @porcentaje > 95 THEN 'progress-bar-danger'
						   WHEN @porcentaje > 80 THEN 'progress-bar-warning'
						END AS progress_ui_class,
						
						CASE
						   WHEN @diskused_porcentaje > 95 THEN 'progress-bar-danger'
						   WHEN @diskused_porcentaje > 80 THEN 'progress-bar-warning'
						END AS diskused_progress_ui_class,
						
						CASE
						   WHEN @bandwidthused_porcentaje > 95 THEN 'progress-bar-danger'
						   WHEN @bandwidthused_porcentaje > 80 THEN 'progress-bar-warning'
						END AS bandwidthused_progress_ui_class
						
										
					FROM servicios_hosting
					LEFT JOIN servicios ON servicios_hosting.id_servicio = servicios.id
					LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
					LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
					LEFT JOIN empresas ON servicios.id_empresa = empresas.id
					LEFT JOIN hosting_servidores ON hosting_servidores.id = servicios_hosting.id_servidor
					
					WHERE servicios.grupo = ?
					AND empresas.estado > 0
					AND servicios_hosting.id = ?
				";
		}


		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $id;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND servicios.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
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
	
	
	public function getPlanDetalleRaw($id)
	{
		return $this->getPlanDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function getExtrasFromServiciosId($id)
	{	
		$sql = "
				SELECT servicios_hosting.id_servidor, servicios_hosting.user
				
				FROM servicios_hosting
			
				WHERE servicios_hosting.id_servicio = ?
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($id));
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;	
	}
	
	
	public function getPlanesParaActualizar($intervalo = 3600, $limite = 10)
	{
		$sql = "
				SELECT servicios_hosting.id, servicios_hosting.id_servidor, servicios_hosting.user
				
				FROM servicios_hosting
				LEFT JOIN servicios ON servicios_hosting.id_servicio = servicios.id
			
				WHERE servicios_hosting.fecha+? < UNIX_TIMESTAMP(NOW())
				AND servicios_hosting.id_servidor IS NOT NULL
				AND servicios.estado > 0
				
				AND servicios_hosting.id NOT IN (SELECT id_padre FROM sys_logs WHERE id_padre = servicios_hosting.id AND id_referencia = 60)
				
				ORDER BY fecha ASC
				
				LIMIT ?
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($intervalo, $limite));
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getPlanParaActualizar($id)
	{
		$sql = "
				SELECT servicios_hosting.id, servicios_hosting.id_servidor, servicios_hosting.user
				
				FROM servicios_hosting
			
				WHERE servicios_hosting.id_servidor IS NOT NULL
				AND servicios_hosting.id = ?
			";
		
		
		// consulta
		$query = $this->db->query($sql, array($id));
			
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function mxToolboxStats($MonitorUID = null)
	{
		$apiUrl = 'https://api.mxtoolbox.com/api/v1/Monitor/' . $MonitorUID;
		$apiKey = 'df72565c-a2c6-4d97-940d-f4a084980008';
		
		$this->load->library('curl');
		$res = json_decode($this->curl->simple_get($apiUrl, array('Authorization'=>$apiKey)), true);
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarMxToolboxStats()
	{
		$dashboard['blacklist'] = null;
		
		$res = $this->mxToolboxStats();
		
		foreach ($res as $obj)
		{
			if ($obj['Action']['Command'] == 'blacklist')
			{
				$data['reputacion'] = $obj['MxRep'];
				
				if ($obj['Failing'])
				{
					$data['blacklist'] = 1;
					$data['blacklist_data'] = json_encode($obj['Failing']);
					
					++$dashboard['blacklist'];
				}
				else
				{
					$data['blacklist'] = 0;
					$data['blacklist_data'] = null;
				}
				
				$this->db->update('hosting_servidores_ips', $data, array('ip'=>$obj['Action']['Address']));
			}
		}
		
		$this->db->update('dashboard', array('blacklist'=>$dashboard['blacklist'], 'blacklist_fecha_modificacion'=>now()));
		
		return (!empty($res)) ? true : false;
	}
	
	
	public function getNagiosStatsRaw()
	{
		$this->load->library('curl');
		$res = $this->curl->simple_get('http://tanatos.revisionalpha.net/statusJson.php');
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarServidoresEstado()
	{
		$res = json_decode($this->getNagiosStatsRaw(), true);
		
		foreach ($res['hosts'] as $obj)
		{
			$data['alerta'] = $obj['current_state'];
			
			$this->db->update('hosting_servidores_ips', $data, array('host'=>$obj['host_name'], 'alerta !='=>$obj['current_state']));
						
			if ($obj['plugin_output'] != 'CRITICAL - Socket timeout after 10 seconds') $this->actualizarNagiosAlertas($obj);
		}
		
		foreach ($res['services'] as $hosts)
		{
			foreach ($hosts as $obj)
			{
				if ($obj['plugin_output'] != 'CRITICAL - Socket timeout after 10 seconds') $this->actualizarNagiosAlertas($obj);
			}
		}
	}
	
	
	public function actualizarNagiosData()
	{
		$res = json_decode($this->getNagiosStatsRaw(), true);
		
		foreach ($res['hosts'] as $obj)
		{
			$this->db->update('hosting_servidores_ips', array('data'=>json_encode($obj)), array('host'=>$obj['host_name']));
		}
	}
	
	
	public function getNagiosStatsLive($parametros = null)
	{
		$data = json_decode($this->getNagiosStatsRaw(), true);
		// programStatus
		// hosts
		// services
		
		if (!empty($data['services']))
		{
			$servicios = array();
			
			foreach ($data['services'] as $hosts)
			{
				foreach ($hosts as $service)
				{
					if ($service['host_name'] == $parametros['host'])
					{	
						switch ($service['current_state'])
						{
							case 1:
								$estado_ui_class = 'warning';
								$estado = 'Advertencia';
								break;
							case 2:
								$estado_ui_class = 'danger';
								$estado = 'Crítico';
								break;
							default:
								$estado_ui_class = 'primary';
								$estado = 'Normal';
								$break;
						}
						
						$res[] = array(	'host'=>$service['host_name'],
										'tipo'=>$service['service_description'],
										'descripcion'=>$service['plugin_output'],
										'estado'=>$estado,
										'id_estado'=>$service['current_state'],
										'estado_ui_class'=>$estado_ui_class
									);
					}
				}
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarNagiosAlertas($obj)
	{
		$data = array();
		
		if ($obj['current_state'] > 0)
		{
			$data['id_externo'] = $obj['current_problem_id'];
			$data['host'] = $obj['host_name'];
			$data['tipo'] = (isset($obj['service_description'])) ? $obj['service_description'] : $obj['check_command'];
			$data['error'] = $obj['plugin_output'];
			$data['estado'] = $obj['current_state']; // current_state: 0 (ok!), 2 (Urgente), 3 (Crítico)
			$data['fecha_modificacion'] = $obj['last_check'];
			
			if (!$this->db->query("SELECT COUNT(*) AS count FROM alertas WHERE id_externo = ?", array($obj['current_problem_id']))->row()->count)
			{
				$data['fecha_alta'] = now();
				
				$this->ingresarAlerta($data);
			}
			else
			{
				$res = $this->db->update('alertas', $data, array('id_externo'=>$obj['current_problem_id'], 'fecha_modificacion !='=>$obj['last_check']));
			}
		}
		else
		{
			$sql = "
					UPDATE alertas
					SET estado = 0,
					tiempo_caido = ? - inicio
					WHERE id_externo = ?
					AND estado > 0
					AND fecha_modificacion != ?
				";
				
			// update legacy
			$res = $this->db->query($sql, array($obj['last_check'], $obj['last_problem_id'], $obj['last_check']));
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAlertas($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS alertas.id, alertas.host, alertas.tipo, alertas.error, alertas.id_contacto, alertas.fecha_alta,
				
					CASE
					   WHEN alertas.estado = 0 THEN 'Normal'
					   WHEN alertas.estado = 1 THEN 'Advertencia'
					   WHEN alertas.estado = 2 THEN 'Crítico'
					END AS estado,
					
					CASE
					   WHEN alertas.estado = 0 THEN 'primary'
					   WHEN alertas.estado = 1 THEN 'warning'
					   WHEN alertas.estado = 2 THEN 'danger'
					END AS estado_ui_class
				
				FROM alertas
		
				WHERE 1
			";
		
		
		if (!isset($res['error']))
		{
			// filtros
			if (!empty($parametros['estado']))
			{
				$sql .= " AND alertas.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND alertas.estado > 0";
			}
			
			if (!empty($parametros['host']))
			{
				$sql .= " AND alertas.host = ?";
				$placeholders[] = $parametros['host'];
			}
			
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (alertas.host REGEXP '" . $value . "'";
				$sql .= " OR alertas.tipo REGEXP '" . $value . "'";
				$sql .= " OR alertas.error REGEXP '" . $value . "') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " alertas.host";
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
	
	
	public function getAlertasStats($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS alertas_stats.id, IFNULL(trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))), contactos.username) AS contacto, enviado, recibido, alertas.host, alertas.tipo, alertas.fecha_alta
				
				FROM alertas_stats
				LEFT JOIN contactos ON alertas_stats.id_contacto = contactos.id
				LEFT JOIN alertas ON alertas_stats.id_alerta = alertas.id
		
				WHERE 1
			";
		
		
		// permisos	
		if ($this->usuario->perfil != 'reseller')
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// busqueda
			if (!empty($parametros['search']))
			{
				$value = str_replace(' ', '|', trim($parametros['search']));
				
				$sql .= " AND (contactos.username REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR alertas.host REGEXP '" . $value . "') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " alertas_stats.id";
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
	
	
	public function getAlertaTemplate($id)
	{
		$sql = " 
				SELECT alertas_stats.id, alertas_stats.id_alerta, alertas_stats.id_contacto, 'CMS+' AS remitente_nombre, 'noreply@revisionalpha.com' AS remitente_email, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, contactos.email, contactos.username, contactos.hash, 'https://www.revisionalpha.com/templates/comunicaciones/alertas.php' AS url, alertas.host, alertas.tipo, alertas.error
				
				FROM alertas_stats
				LEFT JOIN alertas ON alertas_stats.id_alerta = alertas.id
				LEFT JOIN contactos ON alertas_stats.id_contacto = contactos.id
				
				WHERE alertas_stats.id = ?
			";

		// consulta
		$query = $this->db->query($sql, array($id));
		
				
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
			
			$this->load->library('curl');
			$res['template'] = $this->curl->simple_post($res['url'], $res);
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAlertasTotales($parametros = null)
	{
		$res['urgente'] = $this->db->query("SELECT COUNT(*) AS count FROM alertas WHERE estado = 1")->row()->count;
		$res['critico'] = $this->db->query("SELECT COUNT(*) AS count FROM alertas WHERE estado = 2")->row()->count;
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarAlerta($valores, $nivel = 1)
	{
		$insert = $this->db->insert('alertas', $valores);
		
		if ($insert)
		{
			$res['id'] = $this->db->insert_id();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarComunicacion($id_contacto, $id_alerta, $nivel)
	{
		$comunicacion['id_contacto'] = $id_contacto;
		$comunicacion['id_alerta'] = $id_alerta;
		$comunicacion['nivel'] = $nivel;
	
		if (!$this->db->query("SELECT COUNT(*) AS count FROM alertas_stats WHERE id_contacto = ? AND id_alerta = ? AND nivel = ?", array($comunicacion['id_contacto'], $comunicacion['id_alerta'], $comunicacion['nivel']))->row()->count)
		{
			$insert = $this->db->insert('alertas_stats', $comunicacion);
	
			if ($insert)
			{
				$res['id'] = $this->db->insert_id();
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function ingresarComunicaciones($intervalo = 0, $nivel = 1)
	{
		$sql = "
				SELECT alertas.id
				
				FROM alertas
				
				WHERE 1
				AND alertas.inicio IS NULL
				AND alertas.estado > 0
				AND alertas.fecha_alta+? < UNIX_TIMESTAMP(NOW())
			";
		
		$query = $this->db->query($sql, array($intervalo));
		
		if ($query)
		{
			$res = $query->row_array();
			
			if ($res)
			{
				foreach ($this->getAgentes($nivel) as $obj)
				{
					$this->ingresarComunicacion($obj, $res['id'], $nivel);
				}
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicacion($id)
	{
		$this->load->config('smtp');
		$this->load->library('email', $this->config->item('smtp'));
			
		$res = $this->getAlertaTemplate($id);
		
		$this->email->set_newline("\r\n");
		$this->email->from($res['remitente_email'], $res['remitente_nombre']);
		$this->email->to($res['email'], $res['contacto']);
		$this->email->subject('CMS+ ALERTA! ' . strtoupper($res['host']));
		$this->email->message(preg_replace('/(<body.*?(?=>)>)/i', '$1' . '<img src="' . base_url('alerta' . $res['id'] . '.gif') . '" border="0" height="1" width="1" />', $res['template']));
		//if (isset($res['mensaje'])) $this->email->set_alt_message($res['mensaje']);
		
		$this->email->set_header('Track-ID', $res['id']);
		
		
		if (!$this->email->send($this->config->item('smtp')['nodebug']))
		{
			$comunicacion['debug'] = $this->email->print_debugger();
		}
		else
		{
			if (!$this->config->item('smtp')['nodebug']) $comunicacion['debug'] = $this->email->print_debugger(array('headers', 'subject', 'body'));
			
			$comunicacion['enviado'] = now();
		}

		$res = $this->db->update('alertas_stats', $comunicacion, array('id'=>$res['id']));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function enviarComunicaciones($intervalo = 0)
	{
		$sql = "
				SELECT alertas_stats.id
				
				FROM alertas_stats
				LEFT JOIN alertas ON alertas_stats.id_alerta = alertas.id
				
				WHERE 1
				AND enviado IS NULL
				AND alertas.fecha_alta+? < UNIX_TIMESTAMP(NOW())
				
				LIMIT ?
			";
		
		$query = $this->db->query($sql, array($intervalo, 10));
		
		if ($query)
		{
			$res = $query->result_array();
			
			foreach ($res as $obj)
			{
				$this->enviarComunicacion($obj['id']);
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAlertasCriticas($intervalo = 3600) // 1 Hora
	{
		$sql = "
				SELECT alertas.id

				FROM alertas
				
				WHERE 1
				AND alertas.inicio IS NULL
				AND alertas.estado > 0
				AND alertas.fecha_alta+? < UNIX_TIMESTAMP(NOW())
				AND alerta_voip = 0
			";
		
		$query = $this->db->query($sql, array($intervalo));
		
		if ($query)
		{
			$res = $query->result_array();
			
			foreach ($res as $obj)
			{
				$this->db->update('alertas', array('alerta_voip'=>1), array('id'=> $obj['id']));
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getAgentes($nivel=null) // CORREGIR
	{
		// magoo	=> 475
		// Danny	=> 42526
		// Marce	=> 42648
		// Carla	=> 42835
		// Alex		=> 42489
		// Hugo		=> 42913
		// Barbie	=> 42924
		// Iván		=> 43145
		// Pablo	=> 43209
		// Brenda	=> 43358
		
		switch ($nivel)
		{
			case 1:
				$res = array(42526);
				break;
			case 2:
				$res = array(43209);
				break;
			case 3:
				$res = array(475);
				break;
			default:
				$res = array(42526, 475, 43209);
				break;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function tomoLaGuardia($id)
	{
		$data['id_contacto_alertas'] = $id;

		$res = $this->db->update('grupos', $data, array('id'=>$this->usuario->grupo));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getContactoDeGuardiaId()
	{
		$sql = "
				SELECT id_contacto_alertas

				FROM grupos
				
				WHERE id = ?
			";
		
		$query = $this->db->query($sql, array($this->usuario->grupo));
		
		$res = $query->row()->id_contacto_alertas;
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function getContactoDeGuardiaCelular($id)
	{
		$sql = "
				SELECT celular

				FROM contactos
				
				WHERE id = ?
			";
		
		$query = $this->db->query($sql, array($id));
		
		$res = $query->row()->celular;
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function yoMeEncargo($id)
	{
		$data['id_contacto'] = $this->usuario->id;
		$data['inicio'] = now();

		$this->db->where('id_contacto IS NULL');
		$res = $this->db->update('alertas', $data, array('id'=> $id));

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getIpFromDomain($domain)
	{
		$ip = gethostbyname($domain);
		
		if ($domain != $ip) $res = $ip;

		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarIpDePlan($id, $ip = null)
	{
		$data['ip'] = (isset($ip)) ? $ip : null;

		$res = $this->db->update('servicios_hosting', $data, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarIPsDePlanes()
	{
		$sql = "SELECT id, domain FROM servicios_hosting WHERE 1"; //ORDER BY fecha ASC
		
		$query = $this->db->query($sql);
		
		if ($query)
		{
			$row = $query->result_array();
			
			foreach ($row as $obj)
			{
				$res = $this->actualizarIpDePlan($obj['id'], $this->getIpFromDomain($obj['domain']));
				
				echo '<pre>' . print_r($res, true) . '</pre>';
			}
		}
				
		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarSiExiste($user)
	{
		$sql = "
				SELECT true
				
				FROM servicios_hosting
				
				WHERE user = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($user));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	function ingresarAccount($valores)
	{
		if (isset($valores['id_servicio'])) $data['id_servicio'] = $valores['id_servicio'];
		
		if (isset($valores['id_servidor'])) $data['id_servidor'] = (!empty($valores['id_servidor'])) ? $valores['id_servidor'] : null;
		
		$data['user'] = $valores['user'];
		$data['ip'] = $valores['ip'];
		$data['domain'] = $valores['domain'];
		
		if (isset($valores['backup'])) $data['backup'] = (!empty($valores['backup'])) ? $valores['backup'] : null;
		
		if (isset($valores['owner'])) $data['owner'] = (!empty($valores['owner'])) ? $valores['owner'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['plan'])) $data['plan'] = (!empty($valores['plan'])) ? $valores['plan'] : null;
		if (isset($valores['partition'])) $data['partition'] = (!empty($valores['partition'])) ? $valores['partition'] : null;
		if (isset($valores['shell'])) $data['shell'] = (!empty($valores['shell'])) ? $valores['shell'] : null;
		if (isset($valores['theme'])) $data['theme'] = (!empty($valores['theme'])) ? $valores['theme'] : null;
		
		if (isset($valores['unix_startdate'])) $data['unix_startdate'] = (!empty($valores['unix_startdate'])) ? $valores['unix_startdate'] : null;
		if (isset($valores['suspended'])) $data['suspended'] = (!empty($valores['suspended'])) ? $valores['suspended'] : null;
		if (isset($valores['suspendreason'])) $data['suspendreason'] = (!empty($valores['suspendreason'])) ? $valores['suspendreason'] : null;
		if (isset($valores['suspendtime'])) $data['suspendtime'] = (!empty($valores['suspendtime'])) ? $valores['suspendtime'] : null;
		if (isset($valores['is_locked'])) $data['is_locked'] = (!empty($valores['is_locked'])) ? $valores['is_locked'] : null;
		
		if (isset($valores['maxaddons'])) $data['maxaddons'] = (!empty($valores['maxaddons'])) ? $valores['maxaddons'] : null;
		if (isset($valores['maxsub'])) $data['maxsub'] = (!empty($valores['maxsub'])) ? $valores['maxsub'] : null;
		if (isset($valores['maxpop'])) $data['maxpop'] = (!empty($valores['maxpop'])) ? $valores['maxpop'] : null;
		if (isset($valores['maxparked'])) $data['maxparked'] = (!empty($valores['maxparked'])) ? $valores['maxparked'] : null;
		if (isset($valores['maxsql'])) $data['maxsql'] = (!empty($valores['maxsql'])) ? $valores['maxsql'] : null;
		if (isset($valores['maxlst'])) $data['maxlst'] = (!empty($valores['maxlst'])) ? $valores['maxlst'] : null;
		if (isset($valores['maxftp'])) $data['maxftp'] = (!empty($valores['maxftp'])) ? $valores['maxftp'] : null;
		
		if (isset($valores['inodesused'])) $data['inodesused'] = (!empty($valores['inodesused'])) ? $valores['inodesused'] : null;
		if (isset($valores['inodeslimit'])) $data['inodeslimit'] = (!empty($valores['inodeslimit'])) ? $valores['inodeslimit'] : null;
		
		if (isset($valores['max_email_per_hour'])) $data['max_email_per_hour'] = (!empty($valores['max_email_per_hour'])) ? $valores['max_email_per_hour'] : null;
		if (isset($valores['min_defer_fail_to_trigger_protection'])) $data['min_defer_fail_to_trigger_protection'] = (!empty($valores['min_defer_fail_to_trigger_protection'])) ? $valores['min_defer_fail_to_trigger_protection'] : null;
		if (isset($valores['max_defer_fail_percentage'])) $data['max_defer_fail_percentage'] = (!empty($valores['max_defer_fail_percentage'])) ? $valores['max_defer_fail_percentage'] : null;
		if (isset($valores['outgoing_mail_hold'])) $data['outgoing_mail_hold'] = (!empty($valores['outgoing_mail_hold'])) ? $valores['outgoing_mail_hold'] : null;
		if (isset($valores['outgoing_mail_suspended'])) $data['outgoing_mail_suspended'] = (!empty($valores['outgoing_mail_suspended'])) ? $valores['outgoing_mail_suspended'] : null;
		
		
		$data['fecha'] = now();
		
		$res = $this->db->insert('servicios_hosting', $data);
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function modificarAccount($user, $valores)
	{
		if (isset($valores['id_servidor'])) $data['id_servidor'] = (!empty($valores['id_servidor'])) ? $valores['id_servidor'] : null;
		
		if (!empty($valores['ip'])) $data['ip'] = $valores['ip'];
		if (!empty($valores['domain'])) $data['domain'] = $valores['domain'];
		
		if (isset($valores['backup'])) $data['backup'] = (!empty($valores['backup'])) ? $valores['backup'] : null;
		
		if (isset($valores['owner'])) $data['owner'] = (!empty($valores['owner'])) ? $valores['owner'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['plan'])) $data['plan'] = (!empty($valores['plan'])) ? $valores['plan'] : null;
		if (isset($valores['partition'])) $data['partition'] = (!empty($valores['partition'])) ? $valores['partition'] : null;
		if (isset($valores['shell'])) $data['shell'] = (!empty($valores['shell'])) ? $valores['shell'] : null;
		if (isset($valores['theme'])) $data['theme'] = (!empty($valores['theme'])) ? $valores['theme'] : null;
		
		if (isset($valores['unix_startdate'])) $data['unix_startdate'] = (!empty($valores['unix_startdate'])) ? $valores['unix_startdate'] : null;
		if (isset($valores['suspended'])) $data['suspended'] = (!empty($valores['suspended'])) ? $valores['suspended'] : null;
		if (isset($valores['suspendreason'])) $data['suspendreason'] = (!empty($valores['suspendreason'])) ? $valores['suspendreason'] : null;
		if (isset($valores['suspendtime'])) $data['suspendtime'] = (!empty($valores['suspendtime'])) ? $valores['suspendtime'] : null;
		if (isset($valores['is_locked'])) $data['is_locked'] = (!empty($valores['is_locked'])) ? $valores['is_locked'] : null;
		
		if (isset($valores['maxaddons'])) $data['maxaddons'] = (!empty($valores['maxaddons'])) ? $valores['maxaddons'] : null;
		if (isset($valores['maxsub'])) $data['maxsub'] = (!empty($valores['maxsub'])) ? $valores['maxsub'] : null;
		if (isset($valores['maxpop'])) $data['maxpop'] = (!empty($valores['maxpop'])) ? $valores['maxpop'] : null;
		if (isset($valores['maxparked'])) $data['maxparked'] = (!empty($valores['maxparked'])) ? $valores['maxparked'] : null;
		if (isset($valores['maxsql'])) $data['maxsql'] = (!empty($valores['maxsql'])) ? $valores['maxsql'] : null;
		if (isset($valores['maxlst'])) $data['maxlst'] = (!empty($valores['maxlst'])) ? $valores['maxlst'] : null;
		if (isset($valores['maxftp'])) $data['maxftp'] = (!empty($valores['maxftp'])) ? $valores['maxftp'] : null;
		
		if (isset($valores['inodesused'])) $data['inodesused'] = (!empty($valores['inodesused'])) ? $valores['inodesused'] : null;
		if (isset($valores['inodeslimit'])) $data['inodeslimit'] = (!empty($valores['inodeslimit'])) ? $valores['inodeslimit'] : null;
		
		if (isset($valores['max_email_per_hour'])) $data['max_email_per_hour'] = (!empty($valores['max_email_per_hour'])) ? $valores['max_email_per_hour'] : null;
		if (isset($valores['min_defer_fail_to_trigger_protection'])) $data['min_defer_fail_to_trigger_protection'] = (!empty($valores['min_defer_fail_to_trigger_protection'])) ? $valores['min_defer_fail_to_trigger_protection'] : null;
		if (isset($valores['max_defer_fail_percentage'])) $data['max_defer_fail_percentage'] = (!empty($valores['max_defer_fail_percentage'])) ? $valores['max_defer_fail_percentage'] : null;
		if (isset($valores['outgoing_mail_hold'])) $data['outgoing_mail_hold'] = (!empty($valores['outgoing_mail_hold'])) ? $valores['outgoing_mail_hold'] : null;
		if (isset($valores['outgoing_mail_suspended'])) $data['outgoing_mail_suspended'] = (!empty($valores['outgoing_mail_suspended'])) ? $valores['outgoing_mail_suspended'] : null;
		
		
		$data['fecha'] = now();
		
		$res = $this->db->update('servicios_hosting', $data, array('user'=>$user));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function actualizarDiskusage($user)
	{
		$valores = $this->cpanel->diskusage($user);
		
		if (isset($valores) && !isset($valores['error']))
		{
			$data['diskused'] = $valores['_count'];
			$data['disklimit'] = $valores['_max'];
			
			$data['fecha'] = now();
			
			$this->db->update('servicios_hosting', $data, array('user'=>$user));
		}
		
		return (!empty($valores)) ? $valores : null;
	}
	
	
	function actualizarBandwidthusage($user)
	{
		$valores = $this->cpanel->bandwidthusage($user);
		
		if (isset($valores) && !isset($valores['error']))
		{
			$data['bandwidthused'] = $valores['_count'];
			$data['bandwidthlimit'] = $valores['_max'];
			
			$data['fecha'] = now();
			
			$this->db->update('servicios_hosting', $data, array('user'=>$user));
		}
		
		return (!empty($valores)) ? $valores : null;
	}
	
	
	public function planesAlLimite($limite = 80, $tipo)
	{
		$sql = "
				SELECT servicios_hosting.id, servicios_hosting.id_servicio, servicios_hosting.ip, servicios_hosting.user, servicios_hosting.domain, servicios_hosting.diskused, servicios_hosting.disklimit, servicios_hosting.bandwidthused, servicios_hosting.bandwidthlimit, empresas.empresa, empresas.id AS id_empresa,
				
				@diskused_porcentaje:=IF(diskused>=disklimit, 99, ROUND(diskused*100/disklimit)) AS diskused_porcentaje,
				@bandwidthused_porcentaje:=IF(bandwidthlimit>0, IF(bandwidthused>=bandwidthlimit, 99, ROUND(bandwidthused*100/bandwidthlimit)), 0) AS bandwidthused_porcentaje,
				
				@porcentaje:=ROUND(IF(@diskused_porcentaje>=@bandwidthused_porcentaje, @diskused_porcentaje, @bandwidthused_porcentaje)) AS porcentaje
									
				FROM servicios_hosting
				LEFT JOIN servicios ON servicios_hosting.id_servicio = servicios.id
				LEFT JOIN servicios_estado ON servicios.estado = servicios_estado.id
				LEFT JOIN categorias_generales ON servicios.id_categoria = categorias_generales.id
				LEFT JOIN empresas ON servicios.id_empresa = empresas.id
				LEFT JOIN hosting_servidores ON hosting_servidores.id = servicios_hosting.id_servidor
				
				WHERE servicios.grupo = 502
				AND servicios.estado = 4
				AND empresas.estado > 0
			";
			
		// consulta
		if ($tipo = 'espacio')
		{
			$sql .= "AND IF(diskused>=disklimit, 99, ROUND(diskused*100/disklimit)) > ?";
		}
		else
		{
			$sql .= "AND IF(bandwidthlimit>0, IF(bandwidthused>=bandwidthlimit, 99, ROUND(bandwidthused*100/bandwidthlimit)), 0) > ?";
		}
		
		$query = $this->db->query($sql, array($limite));

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function planesConFaltaDeEspacio($limite = 80)
	{
		return $this->planesAlLimite($limite, 'espacio');
	}
	
	
	public function verificarSiEstaBloqueada($ip)
	{
		$sql = "
				SELECT true
				
				FROM mailbox
				
				WHERE blocked_ip = ?
			";
		
		// consulta
		$query = $this->db->query($sql, array($ip));
		
		return ($query->row_array()) ? true : false;
	}
	
	
	public function track($id)
	{
		$comunicacion['recibido'] = now();
		
		$this->db->where('recibido IS NULL');
		$res = $this->db->update('alertas_stats', $comunicacion, array('id'=>$id));
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function total()
	{
		return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
	}


}