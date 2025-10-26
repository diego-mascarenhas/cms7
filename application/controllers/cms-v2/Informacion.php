<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informacion extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Informacion_model');
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
				$parametros['order_by'] = 'con_contenidos.fecha_alta';
				$parametros['order'] = 'DESC';
				if($this->input->get('tipo')) { $data['tipo'] = $parametros['tipo']; } else { $data['tipo'] = 'todos'; }
				$data['listado'] = $this->Informacion_model->getContenidos($parametros);
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/index', $data);
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
				$this->load->helper('text');
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('destacado', 'Destacado', 'required');
				$this->form_validation->set_rules('destacado_slide', 'Destacado en Slide', 'required');
/*
				$this->form_validation->set_rules('titulo_es', 'Título', 'required');
				$this->form_validation->set_rules('url_es', 'URL', 'required');
*/
				if ($this->form_validation->run() === false)
				{
					if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
					{
						echo validation_errors();
					}
					else
					{
						$default['estado'] = 1;
						$default['destacado'] = 0;
						$default['destacado_slide'] = 0;
						$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
						$parametros['tipo'] = $tipo;
						$parametros['combo'] = 1;
						$data['idiomas'] = $this->Informacion_model->getIdiomas();
						$data['instituciones'] = $this->Informacion_model->getInstituciones(83, 'es');
						$data['secciones'] = $this->Informacion_model->getCategorias($parametros);
		
						$this->load->view('header');
						$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/form', $data);
						$this->load->view('footer');
					}
				}
				else
				{
					$idiomas = $this->Informacion_model->getIdiomas();
					
					if ($data = $this->Informacion_model->ingresarInformacion($this->input->post()))
					{			        
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $extension, 18, $this->input->post('medidas_slides')); 

								//INGRESO IMAGEN SLIDE
								if ($this->input->post('destacado_slide') == 1)
								{
									$modificar = $this->Informacion_model->ingresarMedia($data['id'], $original['id'], $extension, 18);
								} 
	
							}
	
					        if(!empty($_FILES['imagen2_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen2_'.$extension]['name'], 'imagen2_'.$extension, $extension, $this->input->post('id_tipo_imagen2'), $this->input->post('medidas_imagen2'), $this->input->post('medidas_miniatura_imagen2')); 
							}

					        if((empty($_FILES['imagen2_'.$extension]['name'])) && (!empty($this->input->post('medidas_miniatura_imagen2'))) && ($this->input->post('template') == 2))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $extension, $this->input->post('id_tipo_imagen2'), $this->input->post('medidas_miniatura_imagen2'), $this->input->post('medidas_miniatura_imagen2')); 
							}

					        if(!empty($_FILES['imagen3_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen3_'.$extension]['name'], 'imagen3_'.$extension, $extension, 20, $this->input->post('medidas_imagen3'), $this->input->post('medidas_miniatura_imagen3')); 
							}

					        if(!empty($_FILES['archivo1_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['archivo1_'.$extension]['name'], 'archivo1_'.$extension, $extension, null); 
							}
				        }
	
						if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
						{
					        $this->session->set_flashdata(array('categoria' => 1, 'resultado' => 'ok', 'data' => 'La noticia fue ingresada correctamente.'));
			            	echo 'SI';
						}
						else
						{
				            redirect(base_url('cms-v2/informacion/modificar/'.$data['id']));
				        }
					}
					else
					{
						if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
						{
							echo 'NO';
						}
						else
						{
							$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
							$this->load->view('cms-v2/error/');
						}
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
	public function modificar($id =null, $tipo = null)
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
				
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('destacado', 'Destacado', 'required');
				$this->form_validation->set_rules('destacado_slide', 'Destacado en Slide', 'required');
				
				if ($this->form_validation->run() === false)
				{
					if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
					{
						echo validation_errors();
					}
					else
					{
						$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Informacion_model->getContenidoDetalleRaw($id);
						$data['idiomas'] = $this->Informacion_model->getIdiomas();
						$parametros['tipo'] = $tipo;
						$parametros['combo'] = 1;
						$data['instituciones'] = $this->Informacion_model->getInstituciones(83, 'es');
						$data['secciones'] = $this->Informacion_model->getCategorias($parametros);
		
						$this->load->view('header');
						$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/form', $data);
						$this->load->view('footer');
					}
				}
				else
				{
					$idiomas = $this->Informacion_model->getIdiomas();
	
					if($id)
					{
						$data = $this->Informacion_model->modificarInformacion($id, $this->input->post());
					}
					else
					{
						$data = $this->Informacion_model->modificarInformacion($this->input->post('id'), $this->input->post());
					}

					if ($data)
					{					
				        foreach($idiomas as $idioma)
				        {
							if($id) { $id = $id; } else { $id = $this->input->post('id'); }
							
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $extension, 18, $this->input->post('medidas_slides')); 
								//ASOCIO IMAGEN SLIDE
								if ($this->input->post('destacado_slide') == 1)
								{
									$modificar = $this->Informacion_model->ingresarMedia($id, $original['id'], $extension, 18);
								} 
							}
	
					        if(!empty($_FILES['imagen2_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen2_'.$extension]['name'], 'imagen2_'.$extension, $extension, 19, $this->input->post('medidas_imagen2'), $this->input->post('medidas_miniatura_imagen2')); 
							}

					        if(!empty($_FILES['imagen3_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen3_'.$extension]['name'], 'imagen3_'.$extension, $extension, 20, $this->input->post('medidas_imagen3'), $this->input->post('medidas_miniatura_imagen3')); 
							}

					        if(!empty($_FILES['archivo1_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['archivo1_'.$extension]['name'], 'archivo1_'.$extension, $extension, null); 
							}
				        }
	
						if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
						{
					        $this->session->set_flashdata(array('noticia' => 1, 'resultado' => 'ok', 'data' => 'La noticia fue modificada correctamente.'));
			            	echo 'SI';
						}
						else
						{
				            redirect(base_url('cms-v2/informacion/modificar/'.$id.'/'));
				        }
					}
					else
					{
						if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
						{
							echo 'NO';
						}
						else
						{
							$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
							$this->load->view('cms-v2/error/');
						}
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
				if ($data = $this->Informacion_model->duplicarInformacion($id))
		        {
					redirect(base_url('cms-v2/informacion'));
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
				
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); } 
				$parametros['estado'] = 3;
				$parametros['order_by'] = 'con_contenidos.orden';
				$parametros['order'] = 'ASC';
				
				$data['item'] = $this->Informacion_model->getCategoriaDetalleRaw($id);
				$data['listado'] = $this->Informacion_model->getContenidos($parametros);
	
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion/ordenar', $data);
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
		$data = $this->Informacion_model->ordenarItems(json_decode($_POST['items']), 'con_contenidos');
		echo json_encode($data);
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Informacion_model->eliminarItem($this->input->post(), 'con_contenidos'))
		        {
					if(($this->input->post('template')) && ($this->input->post('template') == 1 || $this->input->post('template') == 2))
					{
					     $this->session->set_flashdata(array('noticia' => 1, 'resultado' => 'ok', 'data' => 'La noticia fue eliminada correctamente.'));
						redirect($this->input->post('url'));
					}
					else
					{
						redirect(base_url('cms-v2/informacion'));
			        }
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/informacion', $data);
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
	public function upload($id, $imagen, $nombre, $extension = null, $id_tipo = null, $medidas = null, $medidas_miniatura = null)
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
			$data['alto'] = $upload_data['image_height'];
			$data['ancho'] = $upload_data['image_width'];
			$data['peso'] = $upload_data['file_size'];
			$data['estado'] = 2;
			
			//Ingreso
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {		        
		        if ($medidas != null) { $medidas = $medidas; } else { $medidas = $data['ancho'].'x'.$data['alto']; }

		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Asocio
			        if ($id_tipo == 18) 
			        {
				        $this->Informacion_model->asociarMedia($data1['id'], $id, $extension, 18);
				        //Sistema
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
						$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);
	
				        //Slide
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
						$this->multimedia_model->ingresarThumb(18, $data1['id'], $thumb);
					} 
					else
					{
				        //Noticias 2 Imagen
				        if($id_tipo == 20)
				        {
					        $imagen = @getimagesize($upload_data['full_path']);
							$medidas_adicional = $imagen[0].'x'.$imagen[1];

					        $this->Informacion_model->asociarMedia($data1['id'], $id, $extension, 20);
							$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas_adicional);
							$this->multimedia_model->ingresarThumb(20, $data1['id'], $thumb);
						}

				        //Noticias 1 Imagen
						else
				        {
							$this->Informacion_model->asociarMedia($data1['id'], $id, $extension, 14);
							$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
							$this->multimedia_model->ingresarThumb(14, $data1['id'], $thumb);

					        //Noticias Miniatura
							$this->Informacion_model->asociarMedia($data1['id'], $id, $extension, 19);
							$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas_miniatura);
							$this->multimedia_model->ingresarThumb(19, $data1['id'], $thumb);
						}
					}

		        }

		        if($data['id_tipo'] == 9)
		        {
			        if ($id) $this->Informacion_model->asociarMedia($data1['id'], $id, $extension, 9);
/* 			        $archivo = $this->Informacion_model->ingresarArchivo($id, $data['nombre'], $nombre); */
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