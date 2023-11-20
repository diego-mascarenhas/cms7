<?php defined('BASEPATH') or exit('No direct script access allowed');


class App_model extends CI_Model {

	public function getDispositivos($app, $id_user, $parametros = null)
	{
		$sql = "	
				SELECT app_dispositivos.id, app_dispositivos.token
				
				FROM app_dispositivos
				
				WHERE app_dispositivos.id_aplicacion = ?
				AND app_dispositivos.id_user = ?
			";
		
		$placeholders[] = $app;
		$placeholders[] = $id_user;
		
		// filtros
		if (!empty($parametros['estado']))
		{
			$sql .= " AND app_dispositivos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND app_dispositivos.estado = 2";
		}
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function desactivarDispositivo($app, $token)
	{
		$data['estado'] = 1;
		
		$res = $this->db->update('app_dispositivos', $data, array('id_aplicacion'=>$app, 'token'=>$token));

		return (!empty($res)) ? $res : null;
	}


}