<?php defined('BASEPATH') or exit('No direct script access allowed');


class Voip_model extends CI_Model {

	public function getLlamadas($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS llamadas.id, trim(CONCAT(contactos.nombre, ' ', IFNULL(contactos.apellido, ''))) AS contacto, trim(CONCAT(agente.nombre, ' ', IFNULL(agente.apellido, ''))) AS agente, llamadas.fecha_alta,
				
					CASE
					   WHEN llamadas.estado = 1 THEN 'label-primary'
					   WHEN llamadas.estado = 2 THEN 'label-danger'
					   WHEN llamadas.estado = 3 THEN 'label-warning'
					   WHEN llamadas.estado = 4 THEN 'label-plain'
					   WHEN llamadas.estado = 5 THEN 'label-info'
					END AS estado_ui_class,
					
					CASE
						WHEN llamadas.estado = 1 THEN '1'
						WHEN llamadas.estado = 2 THEN 'Sin respuesta'
						WHEN llamadas.estado = 3 THEN '3'
						WHEN llamadas.estado = 4 THEN '4'
						WHEN llamadas.estado = 5 THEN '5'
					END AS estado
				
				FROM llamadas
				LEFT JOIN contactos ON llamadas.id_contacto = contactos.id
				LEFT JOIN contactos AS agente ON llamadas.username_alta = agente.id
		
				WHERE llamadas.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND contactos.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND contactos.estado > 0";
			}
			
			if (!empty($parametros['id_contacto']))
			{
				$sql .= " AND llamadas.id_contacto = ?";
				$placeholders[] = $parametros['id_contacto'];
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (contactos.username REGEXP '" . $value . "'";
				$sql .= " OR contactos.nombre REGEXP '" . $value . "'";
				$sql .= " OR contactos.apellido REGEXP '" . $value . "'";
				$sql .= " OR contactos.email REGEXP '" . $value . "'";
				$sql .= " OR llamadas.agente REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (contactos.username LIKE '%" . $value . "%'";
				$sql .= " OR contactos.nombre LIKE '%" . $value . "%'";
				$sql .= " OR contactos.apellido LIKE '%" . $value . "%'";
				$sql .= " OR contactos.email LIKE '%" . $value . "%'";
				$sql .= " OR llamadas.agente LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " llamadas.fecha_alta";
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


	public function getLlamada($id)
	{

	}


	public function llamar($valores)
	{
		$llamada['grupo'] = $this->usuario->grupo;

		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['agente']))
			{
				$llamada['agente'] = preg_replace('/\D/', '', $valores['agente']);
			}
			elseif (!empty($this->usuario->telefono))
			{
				$llamada['agente'] = preg_replace('/\D/', '', $this->usuario->telefono);
			}
			else
			{
				$res['error'] = 'Debe especificar un agente';
			}
		}
		elseif (!empty($this->usuario->telefono))
		{
			$llamada['agente'] = preg_replace('/\D/', '', $this->usuario->telefono);
		}
		else
		{
			$res['error'] = 'Debe especificar un agente';
		}
		
		
		if (!isset($res['error']))
		{
			$key = $this->getVoipKey();
	
			if (isset($key['error']))
			{
				$res['error'] = $key['error'];
			}
		}
		
		if (!isset($res['error']))
		{
			$credito = $this->getCredito();
			
			if (isset($credito['error']))
			{
				$res['error'] = $credito['error'];
			}
		}
		
		if (!isset($res['error']))
		{
			$llamada['id_contacto'] = (!empty($valores['id_contacto'])) ? $valores['id_contacto'] : null;
			
			$llamada['codigo_pais'] = (!empty($valores['codigo_pais'])) ? $valores['codigo_pais'] : 54;
			$llamada['codigo_area'] = (!empty($valores['codigo_area'])) ? $valores['codigo_area'] : 11;
			$llamada['numero'] = $valores['numero'];

			$llamada['fecha_alta'] = now();
			$llamada['username_alta'] = $this->usuario->id;
		

			$insert = $this->db->insert('llamadas', $llamada);

			$data['id'] = $this->db->insert_id();
		}

		if (!isset($res['error']) && !empty($data['id']))
		{
			$post['key'] = $key;
			$post['agente'] = $llamada['agente'];
			$post['telefono'] = $llamada['codigo_area'] . $valores['numero'];
			$post['id'] = $data['id'];

			$url = 'http://crm.profcallcenter.com.ar/click-to-call.php';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$status = curl_exec($ch);
			curl_close($ch);

			$estado = (json_decode($status)->ok == true) ? 2 : 1;

			$this->db->update('llamadas', array('estado'=>$estado), array('id'=>$data['id']));

			if ($estado == 2)
			{
				$this->db->set('llamadas_disponibles', 'llamadas_disponibles-1', false);
				$this->db->where('username', $this->usuario->username);
				$this->db->update('servicios_voip');
			}

			$res['id'] = $data['id'];
		}

		return (!empty($res)) ? $res : null;
	}


	public function getVoipKey()
	{
		$this->db->select('sctc_key');
		$this->db->from('grupos');
		$this->db->where('grupos.id', $this->usuario->grupo);

		$query = $this->db->get();

		if ($query)
		{
			$res = $query->row()->sctc_key;
		}

		if (!isset($res))
		{
			$res['error'] = 'No se obtuvo el key de llamadas';
		}

		return (!empty($res)) ? $res : null;
	}


	public function getCredito()
	{
		$this->db->select('llamadas_disponibles');
		$this->db->from('servicios_voip');
		$this->db->where('username', $this->usuario->username);

		$query = $this->db->get();

		if ($query)
		{
			$row = $query->row_array();
		}

		if ($row['llamadas_disponibles'] > 0)
		{
			$res = true;
		}
		else
		{
			$res['error'] = 'Este usuario no dispone de crédito suficiente para realizar la llamada';
		}

		return (!empty($res)) ? $res : null;
	}


	public function getValorDeLlamadaDesdePBX($id)
	{
		$res = new StdClass();

		$pbx= $this->load->database('pbx', true);
		$pbx->select_sum('valor');
		$pbx->select_sum('duration');
		$pbx->from('cdr');
		$pbx->where('id_sctc', $id);

		$query = $pbx->get();

		if ($query)
		{
			$data = $query->row();

			if (isset($data->valor))
			{
				$res->id = $id;
				$res->minutos = round($data->duration/60);
				$res->valor = $data->valor*1.3;
			}
			else
			{
				$res->error = 'Aún no se computó el valor de la llamada';
			}
		}
		else
		{
			$res->error = 'No se pudo obtener el valor de la llamada';
		}

		return (!empty($res)) ? $res : null;
	}


	public function getLlamadasNoContabilizadas()
	{
		$res = new StdClass();

		$this->db->select('id');
		$this->db->from('llamadas');
		$this->db->where('estado', 2);

		$query = $this->db->get();

		if ($query)
		{
			$res = $query->result();
		}

		return (!empty($res)) ? $res : null;
	}
	
	
	public function actualizarLlamada($id)
	{
		$data = $this->getValorDeLlamadaDesdePBX($id);

		if (!isset($data->error))
		{
			$res = $this->db->update('llamadas', array('valor'=>$data->valor, 'minutos'=>$data->minutos, 'estado'=>3), array('id'=>$id, 'estado'=>2));
		}
		else
		{
			$res = new StdClass();

			$res->error = 'No se pudo actualizar la llamada';
		}

		return (!empty($res)) ? $res : null;
	}


	public function actualizarCreditoPorLlamada($id)
	{
		$res = new StdClass();

		$this->db->select('id, minutos, valor, username_alta');
		$this->db->from('llamadas');
		$this->db->where('id', $id);

		$query = $this->db->get();

		if ($query)
		{
			$data = $query->row();
			
			$this->db->set('minutos_disponibles', 'minutos_disponibles-' . $data->minutos, false);
			$this->db->set('credito_disponible', 'credito_disponible-' . $data->valor, false);
			$this->db->where('username', $data->username_alta);
			$this->db->update('servicios_voip');
			
			$res->id = $data->id;
		}
		else
		{
			$res->error = 'Ha habido un error';
		}

		return (!empty($res)) ? $res : null;
	}


	public function actualizarCreditos()
	{
		$res = $this->getLlamadasNoContabilizadas();

		if (isset($res))
		{
			foreach ($res as $obj)
			{
				$llamada = $this->actualizarLlamada($obj->id);
	
				if (!isset($llamada->error))
				{
					$this->actualizarCreditoPorLlamada($obj->id);
					$this->db->update('llamadas', array('estado'=>4), array('id'=>$obj->id, 'estado'=>3));
				}
			}
		}

		return (!empty($res)) ? $res : null;
	}


}