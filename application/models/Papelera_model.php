<?php defined('BASEPATH') or exit('No direct script access allowed');


class Papelera_model extends CI_Model {

	public function getEliminados($parametros = null)
	{
		$sql = "	
				SELECT contenido.id, UNIX_TIMESTAMP(contenido.fecha_modificacion) AS fecha_modificacion, contenido.username_modificacion, contenido.titulo as nombre, 'contenido' AS tipo
				FROM contenido
				WHERE contenido.grupo = ?
				AND contenido.estado < 0
				
				UNION
				
				SELECT contactos.id, UNIX_TIMESTAMP(contactos.fecha_modificacion) AS fecha_modificacion, contactos.fecha_modificacion, CONCAT(contactos.nombre,' ',contactos.apellido) as nombre, 'contactos' AS tipo
				FROM contactos
				WHERE contactos.grupo = ?
				AND contactos.estado < 0
					
				UNION
				
				SELECT empresas.id, UNIX_TIMESTAMP(empresas.fecha_modificacion) AS fecha_modificacion, empresas.fecha_modificacion, empresas.empresa as nombre, 'empresas' AS tipo
				FROM empresas
				WHERE empresas.grupo = ?
				AND empresas.estado < 0
				
				UNION
				
				SELECT empresas_fiscales.id, UNIX_TIMESTAMP(empresas_fiscales.fecha_modificacion) AS fecha_modificacion, empresas_fiscales.username_modificacion, empresas_fiscales.razon_social as nombre, 'empresas_fiscales' AS tipo
				FROM empresas_fiscales
				WHERE empresas_fiscales.grupo = ?
				AND empresas_fiscales.id NOT IN (SELECT id_empresa_fiscal FROM facturas)
				AND empresas_fiscales.estado < 0
				
				UNION
				
				SELECT servicios.id, UNIX_TIMESTAMP(servicios.fecha_modificacion) AS fecha_modificacion, servicios.username_modificacion, servicios.descripcion as nombre, 'servicios' AS tipo
				FROM servicios
				WHERE servicios.grupo = ?
				AND servicios.estado < 0
				
				UNION
				
				SELECT cuentas.id, UNIX_TIMESTAMP(cuentas.fecha_modificacion) AS fecha_modificacion, cuentas.username_modificacion, cuentas.titular as nombre, 'cuentas' AS tipo
				FROM cuentas
				WHERE cuentas.grupo = ?
				AND cuentas.estado < 0
				
				UNION
				
				SELECT media.id, media.fecha_modificacion AS fecha_modificacion, media.username_modificacion, media.nombre AS nombre, 'media' AS tipo
				FROM media
				WHERE media.grupo = ?
				AND media.estado < 0
				
				ORDER BY fecha_modificacion DESC
			";
		
		
		// permisos	
		if ($this->usuario->perfil == 'reseller' || $this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
			$placeholders[] = $this->usuario->grupo;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		
		
		if (!isset($res['error']))
		{
			// limite
			//$sql .= " LIMIT ?, ?";
			//$placeholders[] = (isset($parametros['page'])) ? ($this->config->item('use_page_numbers') == true) ? ($parametros['page']-1)*$this->config->item('per_page') : $parametros['page'] : 0;
			//$placeholders[] = (isset($parametros['per_page'])) ? (int) $parametros['per_page'] : $this->config->item('per_page');
			
			// consulta
			$query = $this->db->query($sql, $placeholders);
		}

		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		
		return (!empty($res)) ? $res : null;
	}


	function total()
	{
		//return $this->db->query('SELECT FOUND_ROWS() count;')->row()->count;
		return 10;
	}


}