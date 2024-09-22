<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/elearning/Contacto_model');
		$this->load->model('cms-v2/elearning/Elearning_model');
		$this->load->model('cms-v2/elearning/Pedidos_model');
		$this->load->model('cms-v2/Configuracion_model');
	}
	
	public function individuos()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$parametros['id_perfil'] = 5;
				$data['listado'] = $this->Contacto_model->getContactos($parametros);
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/index', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function empresas()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$parametros['tipo'] = 1;
				$parametros['id_perfil'] = 5;
				$data['listado'] = $this->Contacto_model->getContactos($parametros);
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/empresas', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ingresar($tipo)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->config->set_item('language', $this->usuario->idioma);
				$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
				if($tipo == 0)
				{
					$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
				}
				else
				{
					$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
				}
				$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
				$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
				$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
				$this->form_validation->set_rules('celular', 'Celular', 'trim');
				$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
				$this->form_validation->set_rules('password', 'Contraseña', 'required|trim|min_length[3]');
				$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
				$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
				$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');

				$existente = $this->Contacto_model->verificarSiExiste($this->input->post('email'));
				if($existente['id'])
				{
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[contactos.email]', array('required' => 'El campo email/usuario es obligatorio', 'valid_email' => 'Ingrese un email válido', 'is_unique' => 'Ya hay un usuario registrado con ese email.'));
				}
				else
				{
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array('required' => 'El campo email/usuario es obligatorio', 'valid_email' => 'Ingrese un email válido'));
				}
				
				if ($this->form_validation->run() === false)
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
					$data['estados'] = array(1 => 'Inactivo', 2 => 'Activo');
					$data['cursos'] = $this->Elearning_model->getCursos();

					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/ingresar', $data);
					$this->load->view('footer');
				}
				else
				{
				
					if ($datos = $this->Contacto_model->ingresarContacto($this->input->post()))
			        {
				        $valores['id'] = $datos;
				        $valores['username'] = $this->usuario->id_empresa.$datos;
				        $modificar = $this->Contacto_model->modificarContacto($valores);

				        if(!empty($_FILES['avatar']['name']))
				        {
							$original = $this->upload($valores['id'], $_FILES['avatar']['name'], 'avatar', 7, $this->input->post('medidas')); 
						}

				        if($this->input->post('pedido'))
				        {
					        $this->session->set_flashdata('empresa', $datos);
							redirect(base_url('cms-v2/elearning/pedidos/ingresar/'));
				        }
						else
						{
					        $this->session->set_flashdata('resultado', 'ok');
					        $this->session->set_flashdata('mensaje', 'El usuario se ingresó correctamente.');
							redirect(base_url('cms-v2/elearning/usuarios/modificar/'.$tipo.'/'.$datos));
						}
			        }
			        else
			        {
						$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/'.$tipo);
				        echo 'Error';
			        }
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function modificar($tipo, $id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'Pedido', 'required', array('required' => 'Debe ingresar un pedido.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));

			if ($this->form_validation->run() === false)
			{
				$data['item'] = $this->Contacto_model->detalleContacto($id);
				$data['estados'] = array(1 => 'Inactivo', 2 => 'Activo');
				$data['cursos'] = $this->Elearning_model->getCursos();
				$parametros['id_contacto'] = $id;
				$data['pedidos'] = $this->Pedidos_model->getPedidos($parametros);

				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/ingresar', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Contacto_model->modificarContacto($this->input->post()))
		        {
			        $this->session->set_flashdata('resultado', 'ok');
			        $this->session->set_flashdata('mensaje', 'El usuario se modificó correctamente.');
					redirect(base_url('cms-v2/elearning/usuarios/modificar/'.$tipo.'/'.$id));
		        }
		        else
		        {
					$data['mensaje'] = array("mensaje" =>"No se pudo modificar el usuario", "link" =>"pedidos", "texto_link" => "Volver a Usuarios");
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function eliminar()
	{
		if ($this->is_logged_in())
		{
			$parametros['id_contacto'] = $this->input->post('id');
			if ($datos = $this->Pedidos_model->getPedidos($parametros))
	        {
		        $this->session->set_flashdata('resultado', '0');
		        $this->session->set_flashdata('mensaje', 'El usuario no se puede eliminar porque tiene pedidos ingresados.');
				redirect(base_url('cms-v2/elearning/usuarios/'.$this->input->post('tipo')));
	        }
	        else
	        {
				if ($datos = $this->Contacto_model->eliminarItem($this->input->post()))
		        {
			        $this->session->set_flashdata('resultado', '1');
			        $this->session->set_flashdata('mensaje', 'El usuario se eliminó correctamente.');
			        redirect(base_url('cms-v2/elearning/usuarios/'.$this->input->post('tipo')));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/usuarios/'.$this->input->post('tipo'));
			        echo 'Error';
					$this->load->view('footer');
		        }
		    }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $tipo, $medidas = null)
	{
		set_time_limit(0);
		ini_set('memory_limit', -1);

		// models
		$this->load->model('multimedia_model');
		
		$config['upload_path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/';
		
		if (!is_dir($config['upload_path']))
		{
	    	if (!mkdir($config['upload_path'], 0777, TRUE))
	    	{
	    		exit('No se puede crear la carpeta.');									
	    	}
		}
		
	    $config['encrypt_name'] = true;
	    $config['file_ext_tolower'] = 'true';
		$config['allowed_types'] = implode('|', $this->multimedia_model->getMediaArchivosPermitidos());
		
		$this->load->library('upload', $config);

        if (!$this->upload->do_upload($nombre))
        {
            $error = array('error' => $this->upload->display_errors());
			echo '<pre>' . print_r($error, true) . '</pre>';
        }
        else
        {
			$upload_data = $this->upload->data();
			
			$data['id_tipo'] = $this->multimedia_model->getMediaTipoId($upload_data['file_ext']);
			$data['nombre'] = $upload_data['orig_name'];
			$data['archivo'] = $upload_data['file_name'];
			$data['peso'] = $upload_data['file_size'];
			$data['estado'] = 2;
			
			//Ingreso
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {		        
		        //Sistema
		        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
				$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);

		        //Imagen
				$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
				$this->multimedia_model->ingresarThumb($tipo, $data1['id'], $thumb);

		        //Asocio
				$valores['id'] = $id;
				$valores['avatar'] = $thumb;
		        $contacto = $this->Contacto_model->modificarContacto($valores);
	        }
	        else
	        {
		        // Error!
		        echo 'Error!';
	        }
		}
		return($data1);
	}
		
	function thumbFromImagen($origen, $destino=null, $tamanio, $eliminar=false)
	{
		// helpers and libraries
	    $this->load->library('image_lib');
		
		if (!is_array($tamanio))
		{
			if (preg_match('/x/i', $tamanio))
			{
				$tamanio = explode('x', $tamanio);
			}
			else
			{
				$tamanio = array($tamanio);
			}
		}
		
		if (empty($tamanio[1])) $tamanio[1] = $tamanio[0];
	
		$imagen = @getimagesize($origen);
		$upload_data['image_width'] = $imagen[0];
		$upload_data['image_height'] = $imagen[1];
		
		$upload_data['source'] = $origen;
		
		$url_segment = explode('/', $upload_data['source']);
		$upload_data['archivo'] = end($url_segment);
		
		$upload_data['target'] = (isset($destino)) ? $destino . '/' : FCPATH . 'multimedia/thumbs/';
		$upload_data['thumb'] = 'thumb_' . $tamanio[0] . 'x' . $tamanio[1] . '-' . $upload_data['archivo'];
		
		$image_config['image_library'] = 'gd2';
		$image_config['source_image'] = $upload_data['source'];
		$image_config['create_thumb'] = FALSE;
		$image_config['maintain_ratio'] = TRUE;
		$image_config['new_image'] = $upload_data['target'] . $upload_data['thumb'];
		$image_config['quality'] = 100;
		$image_config['width'] = $tamanio[0];
		$image_config['height'] = $tamanio[1];
		$dim = (intval($upload_data['image_width']) / intval($upload_data['image_height'])) - ($image_config['width'] / $image_config['height']);
		$image_config['master_dim'] = ($dim > 0) ? 'height' : 'width';
		
		$this->image_lib->initialize($image_config);
		 
		if (!$this->image_lib->resize())
		{
			$res['error'] = $this->image_lib->display_errors();
		}
		else
		{
			$image_config['image_library'] = 'gd2';
			$image_config['source_image'] = $upload_data['target'] . $upload_data['thumb'];
			$image_config['new_image'] = $upload_data['target'] . $upload_data['thumb'];
			$image_config['quality'] = 80;
			$image_config['maintain_ratio'] = FALSE;
			$image_config['width'] = $tamanio[0];
			$image_config['height'] = $tamanio[1];

			$vals = @getimagesize($upload_data['target'] . $upload_data['thumb']);
			$width = $vals['0'];
			$height = $vals['1'];
			$image_config['x_axis'] = ($width-$tamanio[0])/2;
			$image_config['y_axis'] = ($height-$tamanio[1])/2;
			
			 
			$this->image_lib->clear();
			$this->image_lib->initialize($image_config); 
			 
			if (!$this->image_lib->crop())
			{
				$res['error'] = $this->image_lib->display_errors();
			}
			else
			{
				if ($eliminar == true) unlink($upload_data['source']);
				
				$res['archivo'] = $upload_data['thumb'];
				$res['ancho'] = $tamanio[0];
				$res['alto'] = $tamanio[1];
			}
		}

	    // clear //
	    $this->image_lib->clear();
	    
	    return (!empty($res)) ? $res : null;
	}
}