<?php defined('BASEPATH') or exit('No direct script access allowed');

class Secciones_model extends CI_Model {

	public function listadoItems()
	{
		$sql = "SELECT con_secciones.id, con_secciones.seccion, con_secciones.fecha_alta, con_secciones.imagen, con_secciones.estado as id_estado, con_estados.estado";
		$sql .= " FROM con_secciones";
		$sql .= " LEFT JOIN con_estados ON con_estados.id = con_secciones.estado";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id_secciones_tipo = 1";
		$sql .= " AND con_secciones.id_empresa = 7358";

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
		$sql .= " AND con_secciones.id_empresa = 7358";

		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}
	
	public function ingresarSeccion($id)
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

	public function duplicarItem($id)
	{
		$this->load->helper('date');
	
		$sql = "SELECT con_secciones.*";
		$sql .= " FROM con_secciones";
		$sql .= " WHERE con_secciones.estado > 0";
		$sql .= " AND con_secciones.id = $id";
		$sql .= " AND con_secciones.id_empresa = 7358";
	
		$query = $this->db->query($sql);
		$res = $query->row_array();
	
		if (!isset($res['error']))
		{
			$datos['id_secciones_tipo'] = 1;
			$datos['seccion'] = $res['seccion'].' copy';
			$datos['descripcion'] = $res['descripcion'];
			$datos['url'] = $res['url'];
			$datos['padre'] = $res['padre'];
			$datos['orden'] = $res['orden'];
			$datos['imagen'] = $res['imagen'];
			$datos['miniatura'] = $res['miniatura'];
			$datos['seo_titulo'] = $res['seo_titulo'];
			$datos['seo_keywords'] = $res['seo_keywords'];
			$datos['seo_descripcion'] = $res['seo_descripcion'];
			$datos['estado'] = 1;
			$datos['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
			$datos['user_alta'] = $this->usuario->id;

			//INSERTO CONTENIDO
			$insert = $this->db->insert('con_secciones', $datos);
			$res2['id'] = $this->db->insert_id();
		}
		return ($res);
	}

    //FUNCIÓN PARA INSERTAR LOS DATOS DE LA IMAGEN SUBIDA
    function subirOriginal($id, $imagen)
    {
		$x = date('YmdHis');

		//CARGO ORIGINAL
		$image_path = './multimedia/511/7358/';
	    $config['upload_path'] = $image_path;
        $config['file_name'] = $x.'-'.url_title($_FILES['imagen']['name']);
	    $config['allowed_types'] = 'gif|jpg|png|jpeg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2524;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;

	    $this->load->library('upload', $config);

        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
        if ( ! $this->upload->do_upload('imagen'))
        {
                $error = array('error' => $this->upload->display_errors());
                echo 'error';
        }
        else
        {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['imagen'] = $upload_data['file_name'];

	        $id = $this->input->post('id');
			$where = "id = $id";
			$res = $this->db->update('con_secciones', $data, $where);
        }
    }

}