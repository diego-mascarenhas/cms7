<?php defined('BASEPATH') or exit('No direct script access allowed');


class Stream_model extends CI_Model {
	
	public function stream($macaddress)
	{
		$sql = "
				SELECT servicios.host, stream.stream, stream_items.url
				
				FROM servicios
				LEFT JOIN stream_rel_servicios ON stream_rel_servicios.id_servicio = servicios.id
				LEFT JOIN stream ON stream_rel_servicios.id_stream = stream.id
				LEFT JOIN stream_rel_items ON stream_rel_items.id_stream = stream.id
				LEFT JOIN stream_items ON stream_rel_items.id_item = stream_items.id
				
				WHERE servicios.host = ?
				AND servicios.estado = 4
				AND stream.estado = 2
				AND stream_items.estado = 2
				
				ORDER BY stream.orden, stream_items.orden ASC
			";
		
		
		// consulta
		$placeholders[] = $macaddress;
		
		$query = $this->db->query($sql, $placeholders);

		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function streamX()
	{
		$sql = "
				SELECT url
				
				FROM stream
				
				WHERE stream.estado = 2
			";
		

		// consulta
		$query = $this->db->query($sql);

		
		if ($query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}


}