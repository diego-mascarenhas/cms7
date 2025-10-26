<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paginas extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Paginas_model');
		$this->load->model('cms-v2/Configuracion_model');
		$this->load->model('cms-v2/carrito/Categorias_model');
		$this->load->model('cms-v2/carrito/Productos_model');
		$this->load->model('cms-v2/Informacion_model');
	}

	//Listar 
	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);

			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				//$parametros['estado'] = 3;
				$parametros['order_by'] = 'padre';
				$parametros['order'] = 'DESC';
				$data['menu1'] = $this->Paginas_model->getPaginas();
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/index', $data);
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

	//Configuracion 
	public function configuracion($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->config->set_item('language', $this->usuario->idioma);
				$this->form_validation->set_rules('seo_titulo_es', 'Título', 'trim|required|min_length[4]');
				
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Paginas_model->getPaginaDetalleRaw($id);
					$data['idiomas'] = $this->Paginas_model->getIdiomas();
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/configuracion', $data);
					$this->load->view('footer');
				}
				else
				{
					if ($data = $this->Paginas_model->modificarSeccion($id, $this->input->post()))
					{					
			            redirect(base_url('cms-v2/paginas/configuracion/'.$id.'/'));
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
			redirect(base_url('/user/login/'));
		}
	}

	//Modificar 
	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('string');
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			
			if($data['configuracion']['id'])
			{
				$this->load->helper('text');
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->config->set_item('language', $this->usuario->idioma);
				if($this->input->post('titulo_es'))
				{
					$this->form_validation->set_rules('titulo_es', 'Título', 'trim|required|min_length[4]');
				}
				else
				{
					$this->form_validation->set_rules('titulo_en', 'Título', 'trim|required|min_length[4]');
				}

				if ($this->form_validation->run() === false)
				{
					if($this->input->post('id')) { $data['detalle'] = $this->Paginas_model->getPaginaDetalleRaw($this->input->post('id')); } else { $data['detalle'] = $this->Paginas_model->getPaginaDetalleRaw($id); }
					$data['categorias'] = $this->Paginas_model->getCategoriasAdicionales($data['detalle']['id_con_secciones']);
					$data['idiomas'] = $this->Paginas_model->getIdiomas();
						
					$this->load->model('multimedia_model');
					$this->load->model('sys_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();
					$data['paises'] = $this->Paginas_model->comboPaises();
					$data['provincias'] = $this->sys_model->comboProvincias(10);
					
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/'.$data['detalle']['template'], $data);
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/footer');
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Paginas_model->getIdiomas();
					
					if ($data = $this->Paginas_model->modificarPagina($id, $this->input->post()))
					{					
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
							if(!empty($this->input->post('id_imagen_tipo'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo'); } else { $id_imagen_tipo = 12;}
					        if(!empty($this->input->post('medidas'))) { $medidas = $this->input->post('medidas'); } else { $medidas = null;}
					        if(!empty($_FILES['imagen_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen_'.$extension]['name'], 'imagen_'.$extension, $id_imagen_tipo, $medidas, $extension);
							}
					        if(!empty($_FILES['imagen_seccion_'.$extension]['name']))
					        {
						        if(!empty($this->input->post('medidas2'))) { $medidas2 = $this->input->post('medidas2'); } else { $medidas2 = null;}
						        if(!empty($this->input->post('id_imagen_tipo2'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo2'); } else { $id_imagen_tipo = 12;}
								$original = $this->upload($id, $_FILES['imagen_seccion_'.$extension]['name'], 'imagen_seccion_'.$extension, $this->input->post('id_imagen_tipo2'), $medidas2, $extension);
							}
					        if(!empty($_FILES['imagen_seccion3_'.$extension]['name']))
					        {
						        if(!empty($this->input->post('medidas3'))) { $medidas2 = $this->input->post('medidas3'); } else { $medidas2 = null;}
						        if(!empty($this->input->post('id_imagen_tipo3'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo3'); } else { $id_imagen_tipo = 12;}
								$original = $this->upload($id, $_FILES['imagen_seccion3_'.$extension]['name'], 'imagen_seccion3_'.$extension, $this->input->post('id_imagen_tipo3'), $medidas2, $extension);
							}
							if(!empty($_FILES['archivo']))
					        {
								$id_imagen_tipo = null;
								$original = $this->upload($data['id'], $_FILES['archivo']['name'], 'archivo', $id_imagen_tipo, $id_imagen_tipo, $extension);
							}
				        }
				        $this->session->set_flashdata('mensaje', 'ok');
			            redirect(base_url('cms-v2/paginas/modificar/'.$id.'/'));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
			            redirect(base_url('cms-v2/paginas/modificar/'.$id.'/'));
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

	//Publicar seccion
	public function publicar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Paginas_model->publicarSeccion($this->input->post()))
		        {
		            redirect(base_url('cms-v2/paginas'));
		        }
		        else
		        {
			        echo 'Error';
					$this->load->view('header');
			        $this->load->view('cms-v2/paginas/detalle', $data);
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

	//Ingresar Informacion Adicional
	public function ingresar_informacion()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('string');
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				if (empty($this->input->post('titulo'))) 
				{
					redirect(base_url('cms-v2/paginas/modificar/'.$this->input->post('id').'?error=informacion'));
				}
				else
				{
					$data = $this->Paginas_model->ingresarContenidoAdicional($this->input->post());
		
			        $extension = $this->input->post('idioma');
			        if(!empty($_FILES['imagen']['name']))
			        {
						if(!empty($this->input->post('id_imagen_tipo'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo'); } else { $id_imagen_tipo = 12;}
						if ($this->input->post('medidas')) { $medidas = $this->input->post('medidas'); } else { $medidas = null; }
						$original = $this->upload($data['id'], $_FILES['imagen']['name'], 'imagen', $id_imagen_tipo, $this->input->post('medidas'), $extension);
						$modificar = $this->Paginas_model->ingresarArchivo($data['id'], $original['id'], $medidas, $this->input->post('id_imagen_tipo'));
					}
			        if(!empty($_FILES['imagen2']['name']))
			        {
						if(!empty($this->input->post('id_imagen_tipo2'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo2'); } else { $id_imagen_tipo = 12;}
						$original = $this->upload($data['id'], $_FILES['imagen2']['name'], 'imagen2', $id_imagen_tipo, $this->input->post('medidas2'), $extension);
					}
			        if(!empty($_FILES['archivo']))
			        {
						$id_imagen_tipo = null;
						$original = $this->upload($data['id'], $_FILES['archivo']['name'], 'archivo', $id_imagen_tipo, $id_imagen_tipo, $extension);
					}
		
		            redirect(base_url('cms-v2/paginas/modificar/'.$this->input->post('id_contenido')));
				}
			}
			else
			{
				$this->load->model('multimedia_model');
				$data['media_proyectos'] = $this->multimedia_model->comboProyectos();

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

	//Modificar Informacion Adicional
	public function modificar_informacion($pagina, $id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('string');
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('text');
				$this->load->helper('form');
				$this->load->library('form_validation');

				$this->config->set_item('language', $this->usuario->idioma);
				$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[3]');
				if(!empty($_FILES['imagen']['name'])) { $this->form_validation->set_rules('medidas', 'Tamaño imagen', 'required'); }
				$this->load->model('multimedia_model');
				$this->load->model('sys_model');
				$data['media_proyectos'] = $this->multimedia_model->comboProyectos();
				
				if ($this->form_validation->run() === false)
				{
					// form values
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Paginas_model->getPaginaDetalleRaw($pagina);
					$data['item'] = $this->Paginas_model->getDetalleContenidoAdicional($id);
					$data['archivo'] = $this->Paginas_model->getArchivo($id, $data['item']['idioma']);
					$parametros['idioma'] = $data['item']['idioma'];
					$parametros['id_contenido'] = $data['detalle']['id'];
					$parametros['id'] = $id;
					$parametros['id_imagen'] = 12;
					$data['bgencabezado'] = $this->Paginas_model->getEncabezadoAdicional($parametros);
					$data['categorias'] = $this->Paginas_model->getCategoriasAdicionales($data['detalle']['id_con_secciones']);
					$data['paises'] = $this->Paginas_model->comboPaises();
					$data['provincias'] = $this->sys_model->comboProvincias(10);
					
					//multimedia
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
	
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();

					// validation not ok, send validation errors to the view
					$header['css'] = array(
										base_url('assets/css/plugins/summernote/summernote.css'),
										base_url('assets/css/plugins/summernote/summernote-bs3.css')
									);
				
					$this->load->view('header', $header);
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/modificar', $data);
					$this->load->view('footer');
				}
				else
				{					
					if ($data = $this->Paginas_model->modificarContenidoAdicional($id, $this->input->post()))
					{					

				        $extension = $this->input->post('idioma');
				        if(!empty($_FILES['imagen']['name']))
				        {
							if(!empty($this->input->post('id_imagen_tipo'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo'); } else { $id_imagen_tipo = 12;}
							if ($this->input->post('medidas')) { $medidas = $this->input->post('medidas'); } else { $medidas = null; }
							$original = $this->upload($id, $_FILES['imagen']['name'], 'imagen', $id_imagen_tipo, $this->input->post('medidas'), $extension);
							$modificar = $this->Paginas_model->ingresarArchivo($id, $original['id'], $medidas, $this->input->post('id_imagen_tipo'));
						}

				        if(!empty($_FILES['imagen2']['name']))
				        {
							if(!empty($this->input->post('id_imagen_tipo2'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo2'); } else { $id_imagen_tipo = 12;}
							$original = $this->upload($id, $_FILES['imagen2']['name'], 'imagen2', $id_imagen_tipo, $this->input->post('medidas2'), $extension);
						}
				        if(!empty($_FILES['archivo']['name']))
				        {
							$id_imagen_tipo = null;
							$original = $this->upload($id, $_FILES['archivo']['name'], 'archivo', $id_imagen_tipo, $id_imagen_tipo, $extension);
							$modificar = $this->Paginas_model->ingresarArchivo($id, $original['id'], null, 9);
						}
				        $this->session->set_flashdata('mensaje', 'ok');
			            redirect(base_url('cms-v2/paginas/modificar/'.$pagina.'/'.$id.'/'));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
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
			redirect(base_url('/user/login/'));
		}
	}

	//Ordenar get Informacion Adicional
	public function ordenar($id, $id_tipo, $idioma)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$data['item'] = $this->Paginas_model->getDetalleCategoria($id);
				$parametros['idioma'] = $idioma;
				$parametros['id_tipo'] = $id_tipo;
				$parametros['id'] = $id;
				$data['listado'] = $this->Paginas_model->getContenidoAdicionalIdioma($parametros);
	
				//cargo las vistas
				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/ordenar', $data);
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


	//Ordenar post Informacion Adicional
	public function ordenarInformacion()
	{
		$data = $this->Paginas_model->ordenarItems(json_decode($_POST['items']), 'con_contenido_items_adicionales');
		echo json_encode($data);
	}
	
	//Eliminar Informacion Adicional
	public function eliminar_informacion($id = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($data = $this->Paginas_model->eliminarInformacion($this->input->post()))
		        {
		            redirect(base_url('cms-v2/paginas/modificar/'.$this->input->post('id_contenido')));
		        }
		        else
		        {
					$this->load->view('header');
			        $this->load->view('cms-v2/paginas/detalle', $data);
			        echo 'Error';
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

	public function relacionar_servicios($id)
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
					$this->load->model('cms-v2/servicios/Servicios_model');
					$data['detalle'] = $this->Paginas_model->getDetalleContenidoAdicional($id);
					$data['padre'] = $this->Paginas_model->getPaginaDetalleRaw($data['detalle']['id_con_contenido']);
					$parametros['estado'] = 3;
					$parametros['idioma'] = $data['detalle']['idioma'];
					$data['listado'] = $this->Servicios_model->getServicios($parametros);
					$parametros1['estado'] = 3;
					$parametros1['id'] = $data['detalle']['id'];
					$parametros1['idioma'] = $data['detalle']['idioma'];
					$data['relacionados'] = $this->Paginas_model->getRelacionados($parametros1);

					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/relacionar', $data);
					$this->load->view('/footer');

				}
				else
				{
					if ($data = $this->Paginas_model->relacionarServicio($this->input->post()))
					{			        
			            redirect(base_url('cms-v2/paginas/relacionar_servicios/'.$id));
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

	public function relacionar_informacion($id)
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
					$data['detalle'] = $this->Paginas_model->getDetalleContenidoAdicional($id);
					$parametros['estado'] = 3;
					$parametros['idioma'] = $data['detalle']['idioma'];
					$parametros['id'] = $id;
					$data['padre'] = $this->Paginas_model->getPaginaDetalleRaw($data['detalle']['id_con_contenido']);
					$data['relacionados'] = $this->Paginas_model->getInformacionRelacionados($parametros);

					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/paginas/relacionar_informacion', $data);
					$this->load->view('/footer');

				}
				else
				{
					if ($data = $this->Paginas_model->relacionarContenidosAdicional($this->input->post()))
					{			        
			            redirect(base_url('cms-v2/paginas/relacionar_informacion/'.$id));
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
	
	
	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $id_imagen_tipo, $medidas, $idioma = null)
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
		$config['max_size'] = 40000;
		
		$this->load->library('upload', $config);

        if (!$this->upload->do_upload($nombre))
        {
            $error = array('error' => $this->upload->display_errors());
			echo '<pre>' . print_r($error, true) . '</pre>';
			die();
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
		        //Asocio
		        if ($id_imagen_tipo == 3 || $id_imagen_tipo == 12 || $id_imagen_tipo == 13) { $this->Paginas_model->asociarMedia($data1['id'], $id, $id_imagen_tipo, $idioma); }
		        if ($medidas != null) { $medidas = $medidas; } else { $medidas = $data['ancho'].'x'.$data['alto']; }
		        
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
					$this->multimedia_model->ingresarThumb($id_imagen_tipo, $data1['id'], $thumb);
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);
		        }
		        if($data['id_tipo'] == 9)
		        {
			        if ($id) $this->Paginas_model->asociarMedia($data1['id'], $id, 9, $idioma);
		        }
		        return $data1;
	        }
	        else
	        {
		        // Error!
		        echo 'Error!';
	        }
		}
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