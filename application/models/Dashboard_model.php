<?php defined('BASEPATH') or exit('No direct script access allowed');


class Dashboard_model extends CI_Model {

	public function getDashboardStats($parametros = null)
	{
		$sql = "
				SELECT *

				FROM dashboard
				
				WHERE grupo = ?
			";
		

		// permisos	
		$placeholders[] = $this->usuario->grupo;
		
		
		// consulta
		$query = $this->db->query($sql, $placeholders);

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		
		return (!empty($res)) ? $res : null;
	}


}