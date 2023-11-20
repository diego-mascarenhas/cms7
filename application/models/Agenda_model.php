<?php defined('BASEPATH') or exit('No direct script access allowed');

class Agenda_model extends CI_Model {

	public function getReuniones($parametros = null)
	{
		$sql = "	
				SELECT SQL_CALC_FOUND_ROWS agenda.id, agenda.nombre, agenda.empresa, agenda.id_agenda_fecha, agenda.email, agenda.telefono, agenda.pais, agenda.estado AS id_estado, agenda_fechas.dia, agenda_fechas.hora, agenda_paises.pais as oficina, empresas.id AS id_empresa,											
					CASE
						WHEN agenda.estado = 1 THEN 'label-plain'
						WHEN agenda.estado = 2 THEN 'label-primary'
						WHEN agenda.estado = 3 THEN 'label-info'
						WHEN agenda.estado = 4 THEN 'label-plain'
						WHEN agenda.estado = 5 THEN 'label-plain'
					END AS estado_ui_class,
					
					CASE
						WHEN agenda.estado = 1 THEN 'Rechazada'
						WHEN agenda.estado = 2 THEN 'Solicitada'
						WHEN agenda.estado = 3 THEN 'Confirmada'
						WHEN agenda.estado = 4 THEN 'Cancelada'
						WHEN agenda.estado = 5 THEN 'Email Incorrecto'
					END AS estado
				
				FROM agenda
				LEFT JOIN agenda_fechas ON agenda.id_agenda_fecha = agenda_fechas.id
				LEFT JOIN empresas ON agenda.id_empresa = empresas.id
				LEFT JOIN agenda_paises ON agenda_fechas.id_agenda_pais = agenda_paises.id 
		
				WHERE agenda.grupo = ?
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND agenda.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND agenda.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND agenda.estado = ?";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND agenda.estado > 0";
			}
			
			// busqueda
			if (!empty($parametros['filtrar']))
			{
				$value = str_replace(' ', '|', trim($parametros['filtrar']));
				
				$sql .= " AND (agenda.nombre REGEXP '" . $value . "'";
				$sql .= " OR agenda.email REGEXP '" . $value . "'";
				$sql .= " OR empresas.empresa REGEXP '" . $value . "') ";
			}
			
			if (!empty($parametros['search']))
			{
				$value = trim($parametros['search']);
				
				$sql .= " AND (agenda.nombre LIKE '%" . $value . "%'";
				$sql .= " OR agenda.email LIKE '%" . $value . "%'";
				$sql .= " OR empresas.empresa LIKE '%" . $value . "%') ";
			}
			
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " nombre";
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
	
	
	public function getReunionDetalle($id, $parametros = null)
	{
		if ($parametros['modo'] == 'raw')
		{
			$sql = " 	
				SELECT *
				FROM agenda
			";
		}
		else
		{
			$sql = "	
					SELECT agenda.id, agenda.nombre, agenda.empresa, agenda.id_agenda_fecha, agenda.email, agenda.telefono, agenda.pais, agenda.estado AS id_estado, agenda_fechas.dia, agenda_fechas.hora, empresas.id AS id_empresa, agenda_paises.pais as oficina,															
						CASE
							WHEN agenda.estado = 1 THEN 'label-plain'
							WHEN agenda.estado = 2 THEN 'label-primary'
							WHEN agenda.estado = 3 THEN 'label-info'
							WHEN agenda.estado = 4 THEN 'label-plain'
							WHEN agenda.estado = 5 THEN 'label-plain'
						END AS estado_ui_class,
						
						CASE
							WHEN agenda.estado = 1 THEN 'Rechazada'
							WHEN agenda.estado = 2 THEN 'Solicitada'
							WHEN agenda.estado = 3 THEN 'Confirmada'
							WHEN agenda.estado = 4 THEN 'Cancelada'
							WHEN agenda.estado = 5 THEN 'Email Incorrecto'
						END AS estado
					
					FROM agenda
					LEFT JOIN agenda_fechas ON agenda.id_agenda_fecha = agenda_fechas.id
					LEFT JOIN agenda_paises ON agenda_fechas.id_agenda_pais = agenda_paises.id
					LEFT JOIN empresas ON agenda.id_empresa = empresas.id
				";
		}
		
		$sql .= " 
				WHERE agenda.grupo = ?
				AND agenda.estado > 0
				AND agenda.id = ?		
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
			$sql .= " AND agenda.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		elseif ($this->usuario->perfil == 'user' || $this->usuario->perfil == 'guest')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->id;
			$sql .= " AND agenda.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
	
	
	public function getReunionDetalleRaw($id)
	{
		return $this->getReunionDetalle($id, array('modo'=>'raw'));
	}
	
	
	public function ingresarReunion($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa;
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (!empty($valores['nombre']))
		{
			$data['nombre'] = $valores['nombre'];
		}
		else
		{
			$res['error'] = 'Debe especificar un nombre';
		}
		
		if (isset($valores['empresa'])) $data['empresa'] = (!empty($valores['empresa'])) ? $valores['empresa'] : null;
		if (isset($valores['id_agenda_fecha'])) $data['id_agenda_fecha'] = (!empty($valores['id_agenda_fecha'])) ? $valores['id_agenda_fecha'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;

		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 2;
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('agenda', $data);
			
			$res['id'] = $this->db->insert_id();
			
			if($res['id'])
			{
			   //modifico estado fecha
			   $fechas = $this->modificarFechaEstado($data['id_agenda_fecha'], 1);

			   //comunico accion
 	           $comunicar = $this->comunicarReunion($res['id'],$valores['estado']);
			}
		}

		return (!empty($res)) ? $res : null;
	}
	
	public function modificarReunion($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (!empty($valores['nombre'])) $data['nombre'] = $valores['nombre'];
		$data['empresa'] = $valores['empresa'];
		if (isset($valores['id_agenda_fecha'])) $data['id_agenda_fecha'] = (!empty($valores['id_agenda_fecha'])) ? $valores['id_agenda_fecha'] : $valores['fecha_actual'];
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : null;
				
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('agenda', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('agenda', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
	   //modifico estado fecha nueva
	   if($valores['estado'] == 4 || $valores['estado'] == 1)
	   {
		   $fechas = $this->modificarFechaEstado($data['id_agenda_fecha'], 3);
	   }
	   else
	   {
		   $fechas = $this->modificarFechaEstado($data['id_agenda_fecha'], 1);
	   }

	   if(($valores['id_agenda_fecha'] != $valores['fecha_actual']) && ($valores['id_agenda_fecha'] != 0))
	   {
		   //modifico estado fecha anterior
		   $fechas = $this->modificarFechaEstado($valores['fecha_actual'], 3);
	   }
	   
	   if($valores['estado'] != 5)
	   { //comunico accion
		   $comunicar = $this->comunicarReunion($id,$valores['estado']);
	   }

		return (!empty($res)) ? $res : null;
	}
	
	
	public function getFechas($parametros = null)
	{
		$sql = "SELECT agenda_fechas.id, agenda_fechas.dia, agenda_fechas.hora, agenda_fechas.estado as id_estado, agenda_fechas.id_agenda_pais, agenda_paises.pais,
		
					CASE
						WHEN agenda_fechas.estado = 1 THEN 'label-plain'
						WHEN agenda_fechas.estado = 3 THEN 'label-info'
					END AS estado_ui_class,
					
					CASE
						WHEN agenda_fechas.estado = 1 THEN 'Bloqueada'
						WHEN agenda_fechas.estado = 3 THEN 'Disponible'
					END AS estado

				FROM agenda_fechas
				LEFT JOIN empresas ON agenda_fechas.id_empresa = empresas.id
				LEFT JOIN agenda_paises ON agenda_fechas.id_agenda_pais = agenda_paises.id

				WHERE agenda_fechas.grupo = ?";
				
		// permisos	
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND agenda_fechas.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND agenda_fechas.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
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
				$sql .= " AND agenda_fechas.estado > 0";
				$placeholders[] = $parametros['estado'];
			}
			else
			{
				$sql .= " AND agenda_fechas.estado > 0";
			}
						
			// orden
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " dia";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
						
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
			
	public function getPaisesPublico($id)
	{	
		$sql = "SELECT id, pais FROM agenda_paises WHERE estado = 3 AND id_empresa = $id";
		
		$query = $this->db->query($sql);
		
		$res = $query->result_array();
		
		$padre[0] = '-- Seleccione país de oficina -- ';
		
		foreach ($res as $obj => $valor)
		{
			$padre[$valor['id']] = $valor['pais'];
		}
		return ($padre);
	}

	public function getFechasPublico($id, $pais = NULL)
	{	
		if($pais != null)
		{
			$this->db->where(array('id_empresa' => $id, 'id_agenda_pais' => $pais, 'estado' => 3));
			$this->db->order_by('dia','asc');
			$fechas = $this->db->get('agenda_fechas');
			if($fechas->num_rows()>0)
			{
				return $fechas->result();
			}

/*
			$sql .= " AND id_agenda_pais = $pais";
			$query = $this->db->query($sql);
			$res = $query->result_array();
			return ($res);
*/
		}
		else
		{
			$sql = "SELECT id, dia, hora FROM agenda_fechas WHERE estado = 3 AND id_empresa = $id";
			$query = $this->db->query($sql);
			
			$res = $query->result_array();
			
			$padre[0] = '-- Seleccione día y hora -- ';
			
			foreach ($res as $obj => $valor)
			{
				$padre[$valor['id']] = $valor['dia'].' '.$valor['hora'].'hs';
			}
			return ($padre);
		}
	}

	public function getFechaDetalle($id)
	{	
		$sql = "SELECT agenda_fechas.id, agenda_fechas.dia, agenda_fechas.hora, agenda_fechas.estado, agenda_paises.id as pais FROM agenda_fechas 
				LEFT JOIN agenda_paises ON agenda_fechas.id_agenda_pais = agenda_paises.id 
				WHERE agenda_fechas.id = $id";
				
		$query = $this->db->query($sql);
		
		$res = $query->row_array();
		
		return ($res);
	}

	public function getReunionPublico($id)
	{	
		$sql = "SELECT agenda.nombre, agenda.empresa, agenda.id_agenda_fecha, agenda.email, agenda.telefono, agenda.pais, agenda_fechas.dia, agenda_fechas.hora, agenda_paises.pais as oficina FROM agenda
				LEFT JOIN agenda_fechas ON agenda.id_agenda_fecha = agenda_fechas.id
				LEFT JOIN agenda_paises ON agenda_fechas.id_agenda_pais = agenda_paises.id
				WHERE agenda.id = $id";
		
		$query = $this->db->query($sql);
		
		$res = $query->row_array();

		return ($res);
	}

	public function getReunionEstado($id)
	{	
		$sql = "SELECT agenda.id, agenda.estado 
				FROM agenda
				LEFT JOIN agenda_fechas ON agenda.id_agenda_fecha = agenda_fechas.id		
				WHERE agenda.id_agenda_fecha = $id
			";

		$query = $this->db->query($sql);
		
		$res = $query->row_array();
		
		return ($res);
	}

	public function ingresarReunionPublico($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : null; // VALIDAR QUE LA EMPRESA SEA DEL GRUPO
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
				
		if (isset($valores['nombre'])) $data['nombre'] = (!empty($valores['nombre'])) ? $valores['nombre'] : null;
		if (isset($valores['empresa'])) $data['empresa'] = (!empty($valores['empresa'])) ? $valores['empresa'] : null;
		if (isset($valores['id_agenda_fecha'])) $data['id_agenda_fecha'] = (!empty($valores['id_agenda_fecha'])) ? $valores['id_agenda_fecha'] : null;
		if (isset($valores['email'])) $data['email'] = (!empty($valores['email'])) ? $valores['email'] : null;
		if (isset($valores['telefono'])) $data['telefono'] = (!empty($valores['telefono'])) ? $valores['telefono'] : null;
		if (isset($valores['pais'])) $data['pais'] = (!empty($valores['pais'])) ? $valores['pais'] : null;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 2;

		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
				
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('agenda', $data);
			
			$res['id'] = $this->db->insert_id();
			
			if($res['id'])
			{
			   //modifico estado fecha
			   $fechas = $this->modificarFechaEstado($data['id_agenda_fecha'], 1);
			}
		}

		return (!empty($res)) ? $res : null;
	}

	public function ingresarFecha($valores)
	{
		$data['grupo'] = $this->usuario->grupo;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$data['id_empresa'] = (!empty($valores['id_empresa'])) ? $valores['id_empresa'] : $this->usuario->id_empresa;
		}
		else
		{
			$data['id_empresa'] = $this->usuario->id_empresa;
		}
		
		if (!empty($valores['dia']))
		{
			$data['dia'] = $valores['dia'];
		}
		else
		{
			$res['error'] = 'Debe especificar un día';
		}
		
		if (isset($valores['hora'])) $data['hora'] = (!empty($valores['hora'])) ? $valores['hora'] : null;
		if (isset($valores['pais'])) $data['id_agenda_pais'] = (!empty($valores['pais'])) ? $valores['pais'] : 1;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 3;
		
		$data['fecha_alta'] = now();
		$data['username_alta'] = $this->usuario->id;
		
		if (!isset($res['error']))
		{
			$insert = $this->db->insert('agenda_fechas', $data);
			
			$res['id'] = $this->db->insert_id();
		}

		return (!empty($res)) ? $res : null;
	}

	public function modificarFecha($id, $valores)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (!empty($valores['dia']))
		{
			$data['dia'] = $valores['dia'];
		}
		else
		{
			$res['error'] = 'Debe especificar un día';
		}
		
		if (isset($valores['hora'])) $data['hora'] = (!empty($valores['hora'])) ? $valores['hora'] : null;
		if (isset($valores['pais'])) $data['id_agenda_pais'] = (!empty($valores['pais'])) ? $valores['pais'] : 1;
		if (isset($valores['estado'])) $data['estado'] = (!empty($valores['estado'])) ? $valores['estado'] : 3;
				
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('agenda_fechas', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('agenda_fechas', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
	
		return (!empty($res)) ? $res : null;
	}

	public function modificarFechaEstado($id, $estado)
	{
		if ($this->usuario->perfil == 'reseller')
		{
			if (!empty($valores['id_empresa'])) $data['id_empresa'] = $valores['id_empresa'];
		}
		
		if (isset($estado)) $data['estado'] = (!empty($estado)) ? $estado : null;
				
		$data['fecha_modificacion'] = now();
		$data['username_modificacion'] = $this->usuario->id;
		
		if ($this->usuario->perfil == 'reseller')
		{
			$res = $this->db->update('agenda_fechas', $data, array('id'=>$id, 'grupo'=>$this->usuario->grupo));
		}
		else
		{
			$res = $this->db->update('agenda_fechas', $data, array('id'=>$id, 'id_empresa'=>$this->usuario->id_empresa));
		}
		
		return (!empty($res)) ? $res : null;
	}

	public function duplicarFecha($id)
	{
		$sql = "SELECT agenda_fechas.*";
		$sql .= " FROM agenda_fechas";
		$sql .= " WHERE agenda_fechas.estado > 0";
		$sql .= " AND agenda_fechas.id = $id";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$data['grupo'] = $res['grupo'];
			$data['id_empresa'] = $res['id_empresa'];
			$data['dia'] = $res['dia'];
			$data['hora'] = $res['hora'];
			$data['id_agenda_pais'] = $res['id_agenda_pais'];
			$data['estado'] = 1;
			$data['fecha_alta'] = now();
			$data['username_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('agenda_fechas', $data);
		}
		return ($res);
	}

	public function comunicarReunion($id, $estado)
	{
	    //Si la empresa es englobally
	    if($this->usuario->id_empresa == 7412)
	    {
			$data['reunion'] = $this->getReunionDetalle($id);
	
	        //ENVIAR MAIL DE REGISTRO
	 		$config = Array(
	        'protocol' => 'smtp',
	        'smtp_host' => 'mail.engloballylatam.org',
	        'smtp_port' => 26,
	        'smtp_user' => 'eventos@engloballylatam.org',
	        'smtp_pass' => 'Passw0rd!123',
	        'charset' => 'utf-8',
	        'smtp_timeout' => '7',
	        'mailtype'  => 'html', 
	        'crlf' => '\r\n',
	        'newline' => "\r\n"
	        );    
	
	        $this->load->library('email', $config);
		    $this->email->to($data['reunion']['email']);  //Quien recibe el mail de la empresa
		    
		    $this->email->from('eventos@engloballylatam.org', 'Encuentro Internacional Englobally Latinoamerica');
		    $this->email->cc('eventos@engloballylatam.com');  //Quien recibe el mail de la empresa
		    $this->email->bcc('pablo@revisionalpha.com');  //Quien recibe el mail de la empresa
			$this->email->reply_to('eventos@engloballylatam.com', 'Encuentro Internacional Englobally Latinoamerica');
	        
	        //reunion rechazada
	        if($this->input->post('estado') == 1)
	        {
				$this->email->subject('La reunión no fue confirmada'); //Asunto del mail 
		        $body = $this->load->view('agenda/emails/mail_rechazada.php',$data,TRUE);
		    }
	        //reunion solicitada
	        elseif($this->input->post('estado') == 2)
	        {
				$this->email->subject('Nueva solicitad de reunión'); //Asunto del mail 
		        $body = $this->load->view('agenda/emails/mail_solicitud.php',$data,TRUE);
	        }
	        //reunion confirmada
	        elseif($this->input->post('estado') == 3)
	        {
				$this->email->subject('La reunión fue confirmada'); //Asunto del mail 
		        $body = $this->load->view('agenda/emails/mail_confirmada.php',$data,TRUE);
	        }
	        //reunion cancelada
	        elseif($this->input->post('estado') == 4)
	        {
				$this->email->subject('La reunión fue cancelada'); //Asunto del mail 
		        $body = $this->load->view('agenda/emails/mail_cancelada.php',$data,TRUE);
	        }
		     
			$this->email->message($body);   
	        
	        $this->email->send();   
	   }     
   }
}