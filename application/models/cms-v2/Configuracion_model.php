<?php defined('BASEPATH') or exit('No direct script access allowed');

class Configuracion_model extends CI_Model {

	public function detalleConfiguracion($id = null)
	{
		$sql = "SELECT con_configuracion.*";
		$sql .= " FROM con_configuracion";
		if ($id)
		{
			$sql .= " WHERE con_configuracion.id_empresa = $id";
		}
		else
		{
			$sql .= " WHERE con_configuracion.id_empresa = ".$this->usuario->id_empresa;
		}
		
		$query = $this->db->query($sql);
		$res = $query->row_array();
		return ($res);
	}

	public function detalleConfiguracionDos($id = null)
	{
		$sql = "SELECT con_configuracion.*";
		$sql .= " FROM con_configuracion";
		$sql .= " WHERE con_configuracion.grupo = ?";
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$res['error'] = 'El perfil reseller no puede acceder al módulo de contenidos. Ingresá con un perfil de administrador.';
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$placeholders[] = $this->usuario->grupo;
			
			$sql .= " AND con_configuracion.id_empresa = ?"; $placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
							
		if (!empty($id))
		{
			$sql .= " AND con_configuracion.id = ?"; 
			$placeholders[] = $id;
		}
		
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->row_array();
		}
		return (!empty($res)) ? $res : null;
	}

	public function getIdiomasAdicionales($id)
	{
		$sql = "SELECT con_configuracion.*";
		$sql .= " FROM con_configuracion";
		$sql .= " WHERE con_configuracion.grupo = ?";
		$placeholders[] = $this->usuario->grupo;
		
		// permisos
		if ($this->usuario->perfil == 'reseller')
		{
			$res['error'] = 'El perfil reseller no puede acceder al módulo de contenidos. Ingresá con un perfil de administrador.';
		}
		elseif ($this->usuario->perfil == 'admin')
		{
			$sql .= " AND con_configuracion.id_empresa = ?"; 
			$placeholders[] = $this->usuario->id_empresa;
		}
		else
		{
			$res['error'] = 'Este perfil no cuenta con los privilegios necesarios';
		}
		$sql .= " AND con_configuracion.padre = ?"; 
		$placeholders[] = $id;
							
		// consulta
		$query = $this->db->query($sql, $placeholders);
		
		if (!isset($res['error']) && $query)
		{
			$res = $query->result_array();
		}
		return (!empty($res)) ? $res : null;
	}



	public function ingresarContenido($id)
	{
		$this->load->helper('date');

		//INSERTO 
		$datos['titulo'] = $this->input->post('titulo');
		$datos['subtitulo'] = $this->input->post('subtitulo');
		$datos['direccion'] = $this->input->post('direccion');
		$datos['localidad'] = $this->input->post('localidad');
		$datos['telefonos'] = $this->input->post('telefonos');
		$datos['telefono2'] = $this->input->post('telefono2');
		$datos['email'] = trim($this->input->post('email'));
		$datos['web'] = trim($this->input->post('web'));
		$datos['facebook'] = trim($this->input->post('facebook'));
		$datos['twitter'] = trim($this->input->post('twitter'));
		$datos['instagram'] = trim($this->input->post('instagram'));
		$datos['youtube'] = trim($this->input->post('youtube'));
		$datos['linkedin'] = trim($this->input->post('linkedin'));
		$datos['spotify'] = trim($this->input->post('spotify'));
		$datos['analytics'] = trim($this->input->post('analytics'));
		$datos['keywords'] = $this->input->post('keywords');
		$datos['descripcion'] = $this->input->post('descripcion');
		$datos['fecha_modificacion'] = unix_to_human(time(), TRUE, 'eu');
		$datos['user_modificacion'] = $this->usuario->id;
			
		$where = "id = ".$this->input->post('id');
		$update = $this->db->update('con_configuracion', $datos, $where);
			
		if(!empty($_FILES['logo']['name']))
        {
			//BORRO LA IMAGEN SI TIENE NOMBRE IGUAL
			if (file_exists('./multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$_FILES['logo']['name'])) 
			{
				unlink('./multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$_FILES['logo']['name']);
			}
			$original = $this->Configuracion_model->subirImagen($this->input->post('id'),$_FILES['logo']['name']); 
        }

		if(!empty($_FILES['logo_pie']['name']))
        {
			//BORRO LA IMAGEN SI TIENE NOMBRE IGUAL
			if (file_exists('./multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$_FILES['logo_pie']['name'])) 
			{
				unlink('./multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$_FILES['logo_pie']['name']);
			}
			$original = $this->Configuracion_model->subirImagenPie($this->input->post('id'),$_FILES['logo_pie']['name']); 
        }

		if(!empty($_FILES['favicon']['name']))
        {
			//BORRO LA IMAGEN SI TIENE NOMBRE IGUAL
			unlink('./multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$_FILES['favicon']['name']);
			$original = $this->Configuracion_model->subirFavicon($this->input->post('id'),$_FILES['favicon']['name']); 
        }

		return ($update);
	}

	//SUBO IMAGEN
	function subirImagen($id, $archivo)
	{
		//CARGO ORIGINAL
		$image_path = './multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/';
	    $config['upload_path'] = $image_path;
	    $config['file_name'] = $archivo;
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;
	
	    $this->load->library('upload', $config);
	
	    //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
	    if ( ! $this->upload->do_upload('logo'))
	    {
            $error = array('error' => $this->upload->display_errors());
            echo 'error';
	    }
	    else
	    {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['logo'] = $upload_data['file_name'];
			$where = "id = $id";
			$res = $this->db->update('con_configuracion', $data, $where);
	    }
	}
	
	//SUBO IMAGEN
	function subirImagenPie($id, $archivo)
	{
		//CARGO ORIGINAL
		$image_path = './multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/';
	    $config['upload_path'] = $image_path;
	    $config['file_name'] = $archivo;
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;
	
	    $this->load->library('upload', $config);
	
	    //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
	    if ( ! $this->upload->do_upload('logo_pie'))
	    {
            $error = array('error' => $this->upload->display_errors());
            echo 'error';
	    }
	    else
	    {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['logo_pie'] = $upload_data['file_name'];
			$where = "id = $id";
			$res = $this->db->update('con_configuracion', $data, $where);
	    }
	}

	//SUBO FAVICON
	function subirFavicon($id, $archivo)
	{
		//CARGO ORIGINAL
		$image_path = './multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/';
	    $config['upload_path'] = $image_path;
	    $config['file_name'] = $archivo;
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;
	
	    $this->load->library('upload', $config);
	
	    //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
	    if ( ! $this->upload->do_upload('favicon'))
	    {
            $error = array('error' => $this->upload->display_errors());
            echo 'error';
	    }
	    else
	    {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['favicon'] = $upload_data['file_name'];
			$where = "id = $id";
			$res = $this->db->update('con_configuracion', $data, $where);
	    }
	}

	//SUBO BANNER
	function subirBanner($id, $archivo)
	{
		//CARGO ORIGINAL
		$image_path = './multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/';
	    $config['upload_path'] = $image_path;
	    $config['file_name'] = $archivo;
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
	    $config['max_size']= 1000;
	    $config['max_width']= 2024;
	    $config['max_height']= 1768;
	    $config['remove_spaces'] = TRUE;
	
	    $this->load->library('upload', $config);
	
	    //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA UPLOAD_VIEW
	    if ( ! $this->upload->do_upload('banner'))
	    {
            $error = array('error' => $this->upload->display_errors());
            echo 'error';
	    }
	    else
	    {
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$data['banner'] = $upload_data['file_name'];
			$where = "id = $id";
			$res = $this->db->update('con_configuracion', $data, $where);
	    }
	}
}
