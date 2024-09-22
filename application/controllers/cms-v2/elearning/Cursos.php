<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/elearning/Elearning_model');
		$this->load->model('cms-v2/elearning/Categorias_model');
		$this->load->model('cms-v2/Configuracion_model');
	}

	//Listar 
	public function index($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); }
	
				$parametros['order_by'] = 'con_elearning.fecha_alta';
				$parametros['order'] = 'DESC';
				
				if($this->input->get('tipo')) { $data['tipo'] = $parametros['tipo']; } else { $data['tipo'] = 'todos';}
				$data['listado'] = $this->Elearning_model->getCursos($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cursos/index', $data);
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
	
	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				$this->form_validation->set_rules('titulo', 'Título', 'required');

				if ($this->form_validation->run() === false)
				{
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();

					$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
					$parametros['combo'] = 1;
					$data['idiomas'] = $this->Elearning_model->getIdiomas();
					$data['categorias'] = $this->Categorias_model->comboCategorias($parametros);
									
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cursos/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Elearning_model->getIdiomas();
					
					if ($data = $this->Elearning_model->ingresarCurso($this->input->post()))
					{			        
						$this->load->model('evento_model');
						$evento = $this->evento_model->ingresarEvento($this->input->post());
						$valores['id_evento'] = $evento['id'];
						$relacionar = $this->Elearning_model->asociarEvento($data['id'], $valores['id_evento']);
						
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen1_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen1_'.$extension]['name'], 'imagen1_'.$extension, 37, $extension, $this->input->post('medidas1')); 
							}
					        if(!empty($_FILES['archivo_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['archivo_'.$extension]['name'], 'archivo_'.$extension, 9, $extension, null); 
							}
				        }

				        $this->session->set_flashdata('mensaje', 'El curso fue ingresado correctamente.');
			            redirect(base_url('cms-v2/elearning/cursos/modificar/'.$data['id']));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
						$this->load->view('cms-v2/error/', $data);
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
			redirect(base_url('user/login/'));
		}
	}


	//Modificar 
	public function modificar($id, $tipo = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				$this->form_validation->set_rules('titulo', 'Título', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Elearning_model->getDetalleCurso($id);
					$data['idiomas'] = $this->Elearning_model->getIdiomas();
					$parametros['combo'] = 1;
					$data['categorias'] = $this->Categorias_model->comboCategorias($parametros);

					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cursos/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Elearning_model->getIdiomas();
	
					if ($data = $this->Elearning_model->modificarCurso($id, $this->input->post()))
					{					
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, 37, $extension, $this->input->post('medidas1')); 
							}
					        if(!empty($_FILES['archivo_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['archivo_'.$extension]['name'], 'archivo_'.$extension, 9, $extension, null); 
							}
				        }
				        $this->session->set_flashdata('mensaje', 'El evento fue modificado correctamente.');
			            redirect(base_url('cms-v2/elearning/cursos/modificar/'.$id));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
						$this->load->view('cms-v2/error/', $data);
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
			redirect(base_url('user/login'));
		}
	}

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($data = $this->Elearning_model->duplicarCurso($id))
		        {
					redirect(base_url('cms-v2/elearning/cursos'));
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
			redirect(base_url('user/login/'));
		}
	}
	
	public function ordenar($id, $parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				$parametros['estado'] = 3;
				$parametros['order_by'] = 'con_elearning.orden';
				$parametros['order'] = 'ASC';
				
				$data['listado'] = $this->Elearning_model->getCursos($parametros);
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/cursos/ordenar', $data);
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
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarInformacion()
	{
		$data = $this->Elearning_model->ordenarItems(json_decode($_POST['items']), 'con_elearning');
		echo json_encode($data);
	}

	public function relacionar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->helper('text');
				$this->config->set_item('language', $this->usuario->idioma);

				if (!$this->input->post())
				{
					$parametros['estado'] = 3;
					$data['idiomas'] = $this->Elearning_model->getIdiomas();
					$data['detalle'] = $this->Elearning_model->getDetalleCurso($id);
					$data['listado'] = $this->Elearning_model->getCursos($parametros);
					
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/relacionar', $data);
					$this->load->view('/footer');

				}
				else
				{
					if ($data = $this->Elearning_model->relacionarServicio($this->input->post()))
					{			        
			            redirect(base_url('cms-v2/elearning/relacionar/'.$id));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
		
						$this->load->view('cms-v2/error/');
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
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Elearning_model->eliminarCurso($this->input->post()))
		        {
					redirect(base_url('cms-v2/elearning/cursos'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/cursos', $data);
					$this->load->view('/footer');
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
			redirect(base_url('user/login/'));
		}
	}				
			

	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $tipo, $extension = null, $medidas = null)
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
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Asocio
			        if ($id) $this->Elearning_model->asociarMedia($data1['id'], $id, $extension, $tipo);

			        //Sistema
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);

			        //Imagen
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
					$this->multimedia_model->ingresarThumb($tipo, $data1['id'], $thumb);

		        }
		        else
		        {
			        if($data['id_tipo'] == 9)
			        {
				        if ($id) $this->Elearning_model->asociarMedia($data1['id'], $id, $extension, 9);
			        }
		        }
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
//PROBADAS	
}