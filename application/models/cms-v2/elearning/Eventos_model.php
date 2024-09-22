<?php defined('BASEPATH') or exit('No direct script access allowed');

class Eventos_model extends CI_Model {

	public function getEventosPublic($parametros = null)
	{
		if(isset($parametros['imagen']))
		{
			$sql = "SELECT con_contenidos.fecha_alta, con_contenidos.filtro1, con_contenido_items.titulo, con_contenido_items.id_contenido as id_item, con_contenido_items.precio, con_contenido_items.texto_adicional, con_contenido_items.contenido1, con_contenido_items.contenido2, con_contenido_items.contenido3, con_contenido_items.contenido4, (SELECT media_thumbs.archivo FROM media_thumbs LEFT JOIN media ON media.id = media_thumbs.referencia LEFT JOIN con_rel_contenidos_media ON con_rel_contenidos_media.id_media = media.id WHERE con_rel_contenidos_media.id_contenido = id_item AND media_thumbs.id_tipo = 19 AND con_rel_contenidos_media.idioma = '". $parametros['idioma']."' ORDER BY con_rel_contenidos_media.id ASC LIMIT 1) AS imagen";
			$sql .= " FROM con_contenidos";
			$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
			$sql .= " WHERE con_contenidos.grupo = ?";
		}
		else
		{
			$sql = "SELECT con_contenidos.id, con_contenidos.fecha_alta,  con_contenido_items.titulo, con_contenido_items.subtitulo, con_contenido_items.texto_adicional, con_contenido_items.contenido1,con_contenidos.filtro1, con_contenidos.filtro2, con_contenido_items.contenido4";
			$sql .= " FROM con_contenidos";
			$sql .= " LEFT JOIN con_contenido_items ON con_contenido_items.id_contenido = con_contenidos.id";
			$sql .= " WHERE con_contenidos.grupo = ?";
		}
		
		if ($this->usuario->perfil == 'reseller')
		{
			$placeholders[] = $this->usuario->grupo;
			
			if (isset($parametros['id_empresa']))
			{
				$sql .= " AND con_contenidos.id_empresa = ?";
				$placeholders[] = $parametros['id_empresa'];
			}
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_contenidos.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		$sql .= " AND con_contenidos.id_tipo = 11";
		$sql .= " AND con_contenido_items.idioma = '". $parametros['idioma']."'";

		if (!empty($parametros['estado']))
		{
			$sql .= " AND con_contenidos.estado = ?";
			$placeholders[] = $parametros['estado'];
		}
		else
		{
			$sql .= " AND con_contenidos.estado > 0";
		}
		
		if (!empty($parametros['filtro1']))
		{
			$sql .= " AND con_contenidos.filtro1 = ?";
			$placeholders[] = $parametros['filtro1'];
		}
		
		if (!empty($parametros['filtro2']))
		{
			$sql .= " AND con_contenidos.filtro2 = ?";
			$placeholders[] = $parametros['filtro2'];
		}
		
		if (!empty($parametros['destacado']))
		{
			$sql .= " AND con_contenidos.destacado = 1";
		}

		if (!empty($parametros['modal']))
		{
			$sql .= " AND con_contenidos.destacado_modal = 1";
			$sql .= " ORDER BY con_contenidos.id DESC LIMIT 1";
		}
		else
		{
			$sql .= " ORDER BY";
			$sql .= (!empty($parametros['order_by'])) ? " " . $parametros['order_by'] : " con_contenidos.orden";
			$sql .= (!empty($parametros['order'])) ? " " . $parametros['order'] : " ASC";
		}
					
		$query = $this->db->query($sql, $placeholders);

		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}
}