<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Eventos_model');
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
				
				$parametros['order_by'] = ' con_contenidos.orden';
				$parametros['order'] = 'ASC';
				$data['listado'] = $this->Eventos_model->getContenidos($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/eventos/index', $data);
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

	//Ingresar 
	public function ingresar($tipo = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('destacado', 'Destacado', 'required');
				$this->form_validation->set_rules('destacado_slide', 'Destacado en Slide', 'required');
				$this->form_validation->set_rules('titulo_es', 'Título', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$default['estado'] = 1;
					$default['destacado'] = 1;
					$default['destacado_modal'] = 0;
					$default['destacado_slide'] = 0;
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
					$data['idiomas'] = $this->Eventos_model->getIdiomas();
	
					$header['css'] = array(
										base_url('assets/css/plugins/summernote/summernote.css'),
										base_url('assets/css/plugins/summernote/summernote-bs3.css')
									);
				
					$this->load->view('header', $header);
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/eventos/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Eventos_model->getIdiomas();
	
					if ($data = $this->Eventos_model->ingresarInformacion($this->input->post()))
					{
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($data['idioma_'.$extension], $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $extension); 
								
								//ASOCIO IMAGEN SLIDE
								if ($this->input->post('destacado_slide') == 1)
								{
									$this->Eventos_model->asociarMedia($original['id'], $data['slide'.$extension], $extension);
								} 
							}
				        }
			            redirect(base_url('cms-v2/eventos/modificar/'.$data['id']));
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
			redirect(base_url('user/login'));
		}
	}

	//Modificar 
	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				$this->load->helper('form');
				$this->load->library('form_validation');
							
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('destacado', 'Destacado', 'required');
				$this->form_validation->set_rules('destacado_slide', 'Destacado en Slide', 'required');
	
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Eventos_model->getContenidoDetalleRaw($id);
					$data['idiomas'] = $this->Eventos_model->getIdiomas();
	
					$header['css'] = array(
										base_url('assets/css/plugins/summernote/summernote.css'),
										base_url('assets/css/plugins/summernote/summernote-bs3.css')
										);
					
					$this->load->view('header', $header);
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/eventos/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Eventos_model->getIdiomas();
	
					if ($data = $this->Eventos_model->modificarInformacion($id, $this->input->post()))
					{					
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $extension); 
							}
				        }
			            redirect(base_url('cms-v2/eventos/modificar/'.$id.'/'));
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
				if ($data = $this->Eventos_model->duplicarEventos($id))
		        {
					redirect(base_url('cms-v2/eventos'));
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

	public function ordenar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				//$this->trackUri();
				$this->config->set_item('language', $this->usuario->idioma);
				
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); } 
				$parametros['estado'] = 3;
				$parametros['order_by'] = 'con_contenidos.orden';
				$parametros['order'] = 'ASC';
				
				$data['listado'] = $this->Eventos_model->getContenidos($parametros);
	
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/eventos/ordenar', $data);
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

	public function ordenarEventos()
	{
		$data = $this->Eventos_model->ordenarItems(json_decode($_POST['items']), 'con_contenidos');
		echo json_encode($data);
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Eventos_model->eliminarItem($this->input->post(), 'con_contenidos'))
		        {
					redirect(base_url('cms-v2/eventos'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/eventos', $data);
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
	public function upload($id, $imagen, $nombre, $extension)
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

			//Ingreso
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {
		        //Asocio
		        if ($id) $this->Eventos_model->asociarMedia($data1['id'], $id, $extension, $nombre);

		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Slide
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1200x400');
					$this->multimedia_model->ingresarThumb(18, $data1['id'], $thumb);

			        //Noticias Miniatura
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '380x240');
					$this->multimedia_model->ingresarThumb(19, $data1['id'], $thumb);
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