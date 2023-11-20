<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

	public function detalleConfiguracion($id)
	{
		$sql = "SELECT con_configuracion.*";
		$sql .= " FROM con_configuracion";
		$sql .= " WHERE con_configuracion.id_tipo = $id";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function listadoPedidos()
	{
		$sql = "SELECT con_car_pedidos.id, con_car_pedidos.id_forma_pago, con_car_pedidos.fecha_alta, con_car_pedidos.regalar, con_car_pedidos.total, con_car_pedidos.estado as id_estado, con_car_pedidos_estados.estado";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_car_pedidos_estados ON con_car_pedidos_estados.id = con_car_pedidos.estado";
		$sql .= " WHERE con_car_pedidos.estado > 0";
		$sql .= " AND con_car_pedidos.id_empresa = 7358";
		$sql .= " ORDER BY con_car_pedidos.id ASC LIMIT 10";
		
		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
	
	public function totalPedidos($moneda)
	{
		$sql = "SELECT SUM(con_car_pedidos.total) as total";
		$sql .= " FROM con_car_pedidos";
		$sql .= " LEFT JOIN con_contactos ON con_contactos.id = con_car_pedidos.id_contacto";
		$sql .= " WHERE con_car_pedidos.estado = 2";
		$sql .= " AND con_car_pedidos.id_empresa = 7358";
		$sql .= " AND con_contactos.pais = '$moneda'";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalPedidosEstado($estado)
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_car_pedidos";
		$sql .= " WHERE estado = $estado";
		$sql .= " AND con_car_pedidos.id_empresa = 7358";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalFavoritos()
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_favoritos";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalUsuarios()
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_contactos";
		$sql .= " WHERE con_contactos.estado > 0";
		$sql .= " AND con_contactos.id_empresa = 7358";
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function totalCursos()
	{
		$sql = "SELECT COUNT(*) as total";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_secciones.id = 3";
		$sql .= " AND con_contenidos.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function listadoNoticias()
	{
		$sql = "SELECT con_contenidos.id, con_contenidos.id_con_secciones, con_contenidos.fecha_alta, con_contenidos.miniatura, con_contenidos.puntaje, con_contenidos.destacado, con_contenidos.estado as id_estado, con_contenido_items.titulo, con_contenido_items.url, con_contenidos.imagen, con_secciones.seccion, con_estados.estado";
		$sql .= " FROM con_contenidos";
		$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
		$sql .= " LEFT JOIN con_secciones ON con_secciones.id = con_contenidos.id_con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_contenidos.estado";
		$sql .= " WHERE con_contenidos.estado > 0";
		$sql .= " AND con_secciones.id_secciones_tipo = 5";
		$sql .= " AND con_contenido_items.idioma = 'es'";
		$sql .= " AND con_contenidos.id_empresa = 7358";
		$sql .= " ORDER BY con_contenidos.orden ASC LIMIT 10";

		$query = $this->db->query($sql);
		$res = $query->result_array();
		return ($res);
	}
}
