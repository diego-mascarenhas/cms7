<?php defined('BASEPATH') or exit('No direct script access allowed');

class Buscador_model extends CI_Model {

	public function listadoContenidos($variables)
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.url, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado = 3";
		$sql .= " AND con_contenidos.id_tipo = 3";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " AND con_contenido_items.titulo LIKE '%".$variables['busqueda']."%'";
		//$sql .= " ORDER BY con_contenido_items.titulo ASC LIMIT 30";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
}