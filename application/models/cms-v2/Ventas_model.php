<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ventas_model extends CI_Model {

	public function listadoItems()
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion, con_secciones.fecha_alta, con_secciones.imagen, con_secciones.estado as id_estado, con_estados.estado";
		$sql .= " FROM con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_secciones.estado";
		$sql .= " LEFT JOIN con_secciones_tipo ON con_secciones_tipo.id = con_secciones.id_secciones_tipo";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id_secciones_tipo = 1";
		$sql .= " AND con_secciones_tipo.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}

	public function detalleItem($id)
	{
		$sql = "SELECT con_secciones.id, con_secciones.id_secciones_tipo, con_secciones.estado as id_estado, con_secciones.orden, con_secciones.seccion, con_secciones.imagen, con_secciones.seo_titulo, con_secciones.seo_keywords, con_secciones.seo_descripcion, con_estados.estado";
		$sql .= " FROM con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_secciones.estado";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id = $id";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function ingresarPedido($id)
	{
		$this->load->helper('date');

		if (empty($_POST['id']))
		{
			$datos['id_secciones_tipo'] = 1;
			$datos['seccion'] = $this->input->post('seccion');
			$datos['padre'] = $this->input->post('padre');
			$datos['url'] = $this->input->post('url');
			$datos['orden'] = $this->input->post('orden');
			$datos['seo_titulo'] = $this->input->post('seo_titulo');
			$datos['seo_keywords'] = $this->input->post('seo_keywords');
			$datos['seo_descripcion'] = $this->input->post('seo_descripcion');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;
	
			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_secciones', $datos);
			$res['id'] = $this->db->insert_id();
		}
		else
		{
			$datos['seccion'] = $this->input->post('seccion');
			$datos['padre'] = $this->input->post('padre');
			$datos['url'] = $this->input->post('url');
			$datos['orden'] = $this->input->post('orden');
			$datos['seo_titulo'] = $this->input->post('seo_titulo');
			$datos['seo_keywords'] = $this->input->post('seo_keywords');
			$datos['seo_descripcion'] = $this->input->post('seo_descripcion');
			$datos['estado'] = $this->input->post('estado');
			$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_modificacion'] = $this->usuario->id;

			$where = "id = ".$this->input->post('id');
			$res = $this->db->update('con_secciones', $datos, $where);
		}
		return ($res);
	}
}