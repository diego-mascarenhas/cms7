<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Multimedia extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			$this->load->helper('number');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['tipo'] = $this->input->get('tipo');
			$parametros['proyecto'] = $this->input->get('proyecto');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'nombre';
			$parametros['order'] = ($this->input->get('order')) ? $this->input->get('order') : 'ASC';
			
			$data['medias'] = $this->multimedia_model->getMedias($parametros);

			$config['total_rows'] = $this->multimedia_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();

			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('/multimedia/lista', $data);
			$this->load->view('/footer');
		}
		
		elseif ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->load->helper('number');
			$this->load->helper('text');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['tipo'] = $this->input->get('tipo');
			$parametros['proyecto'] = $this->input->get('proyecto');
			
			$parametros['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'nombre';
			$parametros['order'] = ($this->input->get('order')) ? $this->input->get('order') : 'ASC';
			$parametros['per_page'] = 248;
			
			$data['proyectos'] = $this->multimedia_model->menuProyectosActivos();
			if ($this->input->get('proyecto')) $data['proyecto'] = $this->multimedia_model->getProyectoDetalleRaw($this->input->get('proyecto'));
			$data['breadcrumb'] = ($this->input->get('proyecto')) ? $this->multimedia_model->breadcrumbs($this->input->get('proyecto')) : null;
			
			if ($medias = $this->multimedia_model->getMedias($parametros))
			{
				foreach ($medias as $obj)
				{
					$data['medias'][] = array_replace($obj, array('thumb' => (isset($obj['thumb'])) ? base_url('multimedia/thumbs/' . $obj['thumb']) : null));
				}
			}
			
			$data['dropzone']['accepted_files'] = '.' . implode(',.', $this->multimedia_model->getMediaArchivosPermitidos());
			$data['mostrar'] = ($this->usuario->perfil == 'guest' || $this->input->get('proyecto') || $this->input->get('tipo')) ? true : false;
			$data['channel_banner'] = (!empty($this->multimedia_model->getThumbDetalle(6, $this->usuario->id_empresa)['archivo'])) ? base_url('multimedia/thumbs/' . $this->multimedia_model->getThumbDetalle(6, $this->usuario->id_empresa)['archivo']) : null;
			$data['parametros'] = $parametros;

			$config['total_rows'] = $this->multimedia_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
						
			$this->load->view($this->tema() . '/header', array('buscador'=>true));
			$this->load->view($this->tema() . '/multimedia/index', $data);
			$this->load->view($this->tema() . '/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function upload()
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

        if (!$this->upload->do_upload('file'))
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

			if ($data = $this->multimedia_model->ingresarMedia($data))
	        {
		        if ($this->input->post('id_proyecto')) $this->multimedia_model->asociarMedia($data['id'], $this->input->post('id_proyecto'));
		        
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data['id'], $thumb);
					
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '320x180');
					$this->multimedia_model->ingresarThumb(2, $data['id'], $thumb);
					
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1280x720');
					$this->multimedia_model->ingresarThumb(3, $data['id'], $thumb);
		        }
		        
				// Ok!
				echo 'ok!';
	        }
	        else
	        {
		        // Error!
		        echo 'Error!';
	        }

			redirect(base_url('multimedia/index'));
		}
	}
	
	
	public function upload_sajax()
	{
		if ($this->is_logged_in('admin') || $this->is_logged_in('user') || $this->is_logged_in('guest'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('upload', 'Upload', 'required');
			
			if (empty($_FILES['file']['name']))
			{
			    $this->form_validation->set_rules('file', 'Archivo', 'required');
			}
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
				
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/upload', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
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
		
		        if (!$this->upload->do_upload('file'))
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
		
					if ($data = $this->multimedia_model->ingresarMedia($data))
			        {
				        if ($this->input->post('id_proyecto')) $this->multimedia_model->asociarMedia($data['id'], $this->input->post('id_proyecto'));
				        
				        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
				        {
					        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
							$this->multimedia_model->ingresarThumb(1, $data['id'], $thumb);
							
							$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '320x180');
							$this->multimedia_model->ingresarThumb(2, $data['id'], $thumb);
							
							$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1280x720');
							$this->multimedia_model->ingresarThumb(3, $data['id'], $thumb);
				        }
				        
						redirect(base_url('multimedia/modificar/' . $data['id']));
			        }
			        else
			        {
				        redirect(base_url('multimedia/error'));
			        }
				}

			}
		}
	}
	
	
	public function upload_stream()
	{
		set_time_limit(0);
		ini_set('memory_limit', -1);

		// models
		$this->load->model('multimedia_model');
		
		$config['upload_path'] = FCPATH . 'multimedia/stream/';
		
	    $config['encrypt_name'] = false;
	    $config['file_ext_tolower'] = 'true';
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;
		
		$this->load->library('upload', $config);

        if (!$this->upload->do_upload('file'))
        {
                $error = array('error' => $this->upload->display_errors());
				echo '<pre>' . print_r($error, true) . '</pre>';
        }
        else
        {
			$upload_data = $this->upload->data();

			redirect(base_url('multimedia/index'));
		}
	}
	
	
	public function upload_thumb($id)
	{
		// models
		$this->load->model('multimedia_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// set validation rules
		$this->form_validation->set_rules('id', 'ID', 'required');
			
			if (empty($_FILES['file']['name']))
			{
			    $this->form_validation->set_rules('file', 'Archivo', 'required');
			}
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = $this->multimedia_model->getMediaDetalle($id);
			
			$this->load->view($this->tema() . '/header');
			$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
			$this->load->view($this->tema() . '/footer');
		}
		else
		{
			// models
			$this->load->model('sys_model');
		
			if ($this->sys_model->verificarPropiedad($this->input->post('id'), 'media'))
			{
				$config['upload_path'] = FCPATH . 'multimedia/thumbs/';
		
			    $config['encrypt_name'] = true;
			    $config['file_ext_tolower'] = 'true';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['overwrite'] = true;
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('file'))
		        {
			        // form values
			        $data['detalle']['id'] = $this->input->post('id');
		            $data['error'] = $this->upload->display_errors();

					$this->load->view($this->tema() . '/header');
					$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
					$this->load->view($this->tema() . '/footer');
		        }
		        else
		        {
					$upload_data = $this->upload->data();
					
					if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
			        {
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
						$this->multimedia_model->ingresarThumb(1, $id, $thumb);
						
						$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '320x180');
						$this->multimedia_model->ingresarThumb(2, $id, $thumb);
						
						$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1280x720', true);
						$this->multimedia_model->ingresarThumb(3, $id, $thumb);
			        }

					redirect(base_url('multimedia/detalle/' . $this->input->post('id')));
				}
			}
			else
			{
				// form values
				$data['detalle']['id'] = $this->input->post('id');
				$data['error'] = 'Ha habido un problema, por favor intenta más tarde';

				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
				$this->load->view($this->tema() . '/footer');
			}
		}
	}
	
	
	public function upload_ftp()
	{
		set_time_limit(0);
		ini_set('memory_limit', -1);
		
		$this->load->library('ftp');

		$config['hostname'] = 'localhost';
		$config['username'] = 'cmsrocoto';
		$config['password'] = 'giga2002!';
		$config['debug']        = true;
		
		$this->ftp->connect($config);
		
		$this->ftp->upload($_FILES['file']['tmp_name'], '/public_html/multimedia/test/' . $_FILES['file']['name'], 'binary', 0775);
		
		$this->ftp->close();
		
		echo '<pre>' . print_r($_FILES['file'], true) . '</pre>';
	}	
	
	
	public function download($id = null)
	{
		if ($this->is_logged_in('reseller') && $id)
		{
			// models
			$this->load->model('multimedia_model');
			
			$data = $this->multimedia_model->getMediaFromUid($id);
			
			$data['file_path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $data['id_empresa'] . '/' . $data['archivo'];

			header('Content-type: ' . $data['mime']);
			header('Content-Disposition: attachment; filename="' . $data['nombre'] . '"');
			readfile($data['file_path']);
		}
		elseif ($this->is_logged_in() && $id)
		{
			// models
			$this->load->model('multimedia_model');
			
			$data = $this->multimedia_model->getMediaFromUid($id);
			
			if ($data['id_empresa'] == $this->usuario->id_empresa)
			{
				$data['file_path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $data['archivo'];
	
				header('Content-type: ' . $data['mime']);
				header('Content-Disposition: attachment; filename="' . $data['nombre'] . '"');
				readfile($data['file_path']);
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
			}
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
		}
	}
	
	
	public function download_preview($id = null)
	{
		if ($this->is_logged_in('reseller') && $id)
		{
			// models
			$this->load->model('multimedia_model');
			
			$data = $this->multimedia_model->getMediaFromUid($id);
			
			$data['file_path'] = FCPATH . 'multimedia/preview/' . $data['archivo'];

			header('Content-type: ' . $data['mime']);
			header('Content-Disposition: attachment; filename="' . $data['nombre'] . '"');
			readfile($data['file_path']);
		}
		elseif ($this->is_logged_in() && $id)
		{
			// models
			$this->load->model('multimedia_model');
			
			$data = $this->multimedia_model->getMediaFromUid($id);
			
			if ($data['id_empresa'] == $this->usuario->id_empresa)
			{
				$data['file_path'] = FCPATH . 'multimedia/preview/' . $data['archivo'];
	
				header('Content-type: ' . $data['mime']);
				header('Content-Disposition: attachment; filename="' . $data['nombre'] . '"');
				readfile($data['file_path']);
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
			}
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->library('form_validation');
			$this->load->helper('number');
			$this->load->helper('text');
			

			$data['detalle'] = $this->multimedia_model->getMediaDetalle($id);
			$data['breadcrumb'] = ($this->input->get('proyecto')) ? $this->multimedia_model->breadcrumbs($this->input->get('proyecto')) : null;
			
			if ($data['detalle']['tipo'] == 'video')
			{
				$this->load->helper('file');
				$data['path'] = FCPATH . 'multimedia/';
				
				if ($data['detalle']['stream'] == 3 && !(file_exists($data['path'] . '/procesar/' . preg_replace('/.[^.]*$/', '', $data['detalle']['archivo']))))
				{
					// 59f778d8c8b09.streamlock.net
					$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/streamcms/_definst_/' . preg_replace('/.[^.]*$/', '', $data['detalle']['archivo']) . '.smil/playlist.m3u8';
				}
				else
				{
					$data['path'] = FCPATH . 'multimedia/preview/';
					$data['info'] = get_file_info($data['path'] . preg_replace('/.[^.]*$/', '', $data['detalle']['archivo']) . '.mp4');
		
					if ($data['info'])
					{
						$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/vodcms/mp4:multimedia/preview/' . $data['info']['name'] . '/playlist.m3u8';
						
						$data['detalle']['preview']['size'] = $data['info']['size'];
					}
					else
					{
						$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/vodcms/mp4:multimedia/' . $data['detalle']['grupo'] . '/' . $data['detalle']['id_empresa'] . '/' . $data['detalle']['archivo'] . '/playlist.m3u8';
					}
				}
			}
			
			$data['detalle']['thumb'] = (isset($data['detalle']['thumb']) && !(file_exists(FCPATH . 'multimedia/thumb/' . $data['detalle']['thumb']))) ? base_url('multimedia/thumbs/' . $data['detalle']['thumb']) : null;
			
			if ($this->input->get('proyecto'))
			{
				$parametros['proyecto'] = $this->input->get('proyecto');
			
				$parametros['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'nombre';
				$parametros['order'] = ($this->input->get('order')) ? $this->input->get('order') : 'ASC';
			
				$data['medias'] = $this->multimedia_model->getMedias($parametros);
				$data['parametros'] = $parametros;
			}
			
			if (isset($data['detalle']))
			{
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/detalle', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				$this->load->view($this->tema() . '/401');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar()
	{
		if ($this->is_logged_in('admin'))
		{
			// models
			$this->load->model('multimedia_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
				$data['detalle']['estado'] = 2;
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/multimedia/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if (preg_match('/http/i', $this->input->post('nombre')))
				{
					$valores['id_tipo'] = 16;
					$valores['nombre'] = $this->input->post('nombre');
					$valores['archivo'] = $this->input->post('nombre');
					
					if ($data = $this->multimedia_model->ingresarMedia($valores))
					{
						redirect(base_url('multimedia/detalle/' . $data['id']));
					}
					else
					{
						$data['error'] = 'Ha habido un problema, por favor pruebe más tarde';
					}
				}
				else
				{
					$this->load->helper('file');
					
					$data['path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/';
					$data['info'] = get_file_info($data['path'] . $this->input->post('nombre'));
								
					if ($data['info'])
					{
						if (!$this->multimedia_model->getMediaIdFromArchivo($this->input->post('nombre')))
						{
							$valores['id_tipo'] = $this->multimedia_model->getMediaTipoId(strtolower(preg_replace('/^.*\./', '', $data['path'] . $this->input->post('nombre'))));
							$valores['nombre'] = $this->input->post('nombre');
							$valores['archivo'] = $this->input->post('nombre');
							$valores['peso'] = $data['info']['size'];
							
							if ($data = $this->multimedia_model->ingresarMedia($valores))
							{
								if ($tags = $this->input->post('tags'))
								{
									if (is_array($tags))
									{
										$tagueado = $this->sys_model->asociarTags(70, $data['id'], $tags);
									}
									else
									{
										$tagueado = $this->sys_model->asociarTags(70, $data['id'], explode(',', $tags));
									}
								}
								
								redirect(base_url('multimedia/detalle/' . $data['id']));
							}
							else
							{
								$data['error'] = 'Ha habido un problema, por favor pruebe más tarde';
							}
						}
						else
						{
							$data['error'] = 'El archivo ya se encuentra en base de datos';
						}
					}
					else
					{
						$data['error'] = 'El archivo que se quiere ingresar no existe';
					}
				}
				
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
							
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/multimedia/form', $data);
				$this->load->view('/footer');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function modificar($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin') || $this->is_logged_in('user') || $this->is_logged_in('guest'))
		{
			// models
			$this->load->model('multimedia_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
	
			
			// set validation rules
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->multimedia_model->getMediaDetalleRaw($id);
				
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/form', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				$data = $this->multimedia_model->getMediaDetalleRaw($id);
				
				if ($this->multimedia_model->modificarMedia($id, $this->input->post()))
				{
					if ($this->input->post('stream') && $data['stream'] != $this->input->post('stream')) $this->ffmpeg($data['grupo'], $data['id_empresa'], $data['archivo'], $this->input->post('stream'));
					
					if ($tags = $this->input->post('tags'))
					{
						if (is_array($tags))
						{
							$tagueado = $this->sys_model->asociarTags(70, $id, $tags);
						}
						else
						{
							$tagueado = $this->sys_model->asociarTags(70, $id, explode(',', $tags));
						}
					}
					
					redirect(base_url('multimedia/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/multimedia/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->multimedia_model->getMediaDetalleRaw($id);
				
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/eliminar', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'media'))
				{
					$res = $this->sys_model->eliminar($id, 'media');
					
					redirect(base_url('multimedia/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/multimedia/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar_archivo()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('archivo', 'Archivo', 'required');
			
			if ($this->form_validation->run() === false)
			{
				$archivo = substr(strrchr(rtrim($this->input->get('archivo'), '/'), '/'), 1);
				
				if (!$this->multimedia_model->getMediaIdFromArchivo($archivo, false))
				{
					// form values
					$data['detalle']['archivo'] = $this->input->get('archivo');
				}
				else
				{
					$data['detalle']['archivo'] = $this->input->get('archivo');
					$data['error'] = 'Al existir en base de datos no podemos eliminarlo';
				}
				
				$this->load->view('/header');
				$this->load->view('/multimedia/eliminar_archivo', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
				
				$archivo = substr(strrchr(rtrim($this->input->post('archivo'), '/'), '/'), 1);
				
				if (!$this->multimedia_model->getMediaIdFromArchivo($archivo, false))
				{
					if (is_file($this->input->post('archivo'))) unlink($this->input->post('archivo'));
					
					redirect(base_url('multimedia/reporte'));
				}
				else
				{
					$data['detalle']['archivo'] = $this->input->post('archivo');
					$data['error'] = 'Al existir en base de datos no podemos eliminarlo';
	
					$this->load->view($this->tema() . '/header');
					$this->load->view($this->tema() . '/multimedia/eliminar_archivo', $data);
					$this->load->view($this->tema() . '/footer');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view($this->tema() . '/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function share($id)
	{
		// models
		$this->load->model('multimedia_model');
		
		if ($data['detalle'] = $this->multimedia_model->getMediaFromUid($id))
		{
			if ($data['detalle']['estado'] == 1)
			{
				echo 'La media no está activa.';
			}
			elseif ($this->is_logged_in() || $data['detalle']['estado'] == 3)
			{
				if ($data['detalle']['stream'] == 3)
				{
					$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/streamcms/_definst_/' . preg_replace('/.[^.]*$/', '', $data['detalle']['archivo']) . '.smil/playlist.m3u8';
				}
				else
				{
/*
					$this->load->helper('file');
						
					$data['path'] = FCPATH . 'multimedia/preview';
					$data['info'] = get_file_info($data['path'] . '/' . preg_replace('/.[^.]*$/', '', $data['detalle']['archivo']) . '.mp4');
		
					if ($data['info'])
					{
						$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/vodcms/mp4:multimedia/preview/' . $data['info']['name'] . '/playlist.m3u8';
					}
					else
					{
						$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/vodcms/mp4:multimedia/' . $data['detalle']['grupo'] . '/' . $data['detalle']['id_empresa'] . '/' . $data['detalle']['archivo'] . '/playlist.m3u8';
					}
*/
					
					$data['detalle']['video'] = 'https://59f778d8c8b09.streamlock.net/vodcms/mp4:multimedia/' . $data['detalle']['grupo'] . '/' . $data['detalle']['id_empresa'] . '/' . $data['detalle']['archivo'] . '/playlist.m3u8';
				}
				
				$data['detalle']['thumb'] = (isset($data['detalle']['thumb'])) ? base_url('multimedia/thumbs/' . $data['detalle']['thumb']) : null;

				$this->load->view('/multimedia/share', $data);
			}
			else
			{
				$this->load->view('/403');
			}
		}
		else
		{
			$this->load->view('/404');
		}
	}


	public function asociar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('id', 'ID', 'required|integer', array('required' => 'Debe ingresar un ID.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['item'] = $this->multimedia_model->getMediaDetalle($id);
				$data['proyectos'] = $this->multimedia_model->menuProyectos();
				$data['relacionados'] = $this->multimedia_model->getProyectosAsociados($id);
				
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/asociar', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{	
				if ($this->multimedia_model->getProyectosAsociados($this->input->post('id')))
				{
					$this->multimedia_model->eliminarAsociacionDeMedia($this->input->post('id'));
				}
	
				if ($this->input->post('proyectos'))
				{
					foreach ($this->input->post('proyectos') as $proyecto)
					{
						$this->multimedia_model->asociarMedia($this->input->post('id'), $proyecto);
					}
				}
				
				redirect(base_url('multimedia/detalle/' . $this->input->post('id')));
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function proyectos()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			
			
			$data['proyectos'] = $this->multimedia_model->menuProyectos(($this->input->get('padre')) ? $this->input->get('padre') : null, null, ($this->input->get('nivel')) ? $this->input->get('nivel') : 10);

			$this->load->view($this->tema() . '/header');
			$this->load->view($this->tema() . '/multimedia/proyectos', $data);
			$this->load->view($this->tema() . '/footer');
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
	public function gestionar_proyecto($id = NULL)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('multimedia_model');
			$this->load->model('contacto_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');

			$this->form_validation->set_rules('proyecto', 'Proyecto', 'required|min_length[2]', array('required' => 'Debe ingresar un proyecto'));
			$this->form_validation->set_rules('padre', 'Padre', 'integer');

			if ($this->form_validation->run() === false)
			{
				$data['proyectos'] = $this->multimedia_model->menuProyectos();

				if (isset($id))
				{
					$data['relacionar'] = $this->multimedia_model->getUsuariosAsociadosAlProyecto($id);

					$data['detalle'] = $this->multimedia_model->getProyectoDetalleRaw($id);
					$data['contactos'] = $this->contacto_model->getContactos(array('per_page'=>1000));
				}
				else
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/proyectos_form', $data);
				$this->load->view($this->tema() . '/footer');
			}
			
			else
			{
				if ($this->input->post('id'))
				{
					if ($data = $this->multimedia_model->modificarProyecto($id, $this->input->post()))
			        {
				        // Preview proyecto
				        if (!empty($_FILES['file']['name']))
						{
							$config['upload_path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/';
							
						    $config['file_name'] = 'proyecto_' . $id;
						    $config['file_ext_tolower'] = 'true';
							$config['allowed_types'] = 'gif|jpg|png';
							$config['overwrite'] = true;
							
							$this->load->library('upload', $config);
					
					        if (!$this->upload->do_upload('file'))
					        {
					                $error = array('error' => $this->upload->display_errors());
									echo '<pre>' . print_r($error, true) . '</pre>';
					        }
					        else
					        {
								$upload_data = $this->upload->data();
								
								$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1280x720');
								$this->multimedia_model->ingresarThumb(5, $id, $thumb);
								
								$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '100x100', true);
								$this->multimedia_model->ingresarThumb(4, $id, $thumb);
							}
						}
						
						if ($tags = $this->input->post('tags'))
						{
							if (is_array($tags))
							{
								$tagueado = $this->sys_model->asociarTags(71, $id, $tags);
							}
							else
							{
								$tagueado = $this->sys_model->asociarTags(71, $id, explode(',', $tags));
							}
						}
						
						redirect(base_url('multimedia/proyectos'));
			        }
			        else
			        {
				        $this->load->view($this->tema() . '/multimedia/proyectos/detalle', $data);
			        }
				}
				
				else
				{
					if ($data = $this->multimedia_model->ingresarProyecto($this->input->post()))
			        {
						redirect(base_url('multimedia/proyectos'));
			        }
			        else
			        {
				        $this->load->view($this->tema() . '/multimedia/proyectos/detalle', $data);
			        }
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function compartir_proyecto($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('multimedia_model');
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('id', 'ID', 'required|integer', array('required' => 'Debe ingresar el ID del proyecto que se quiere compartir'));
	
			if ($this->form_validation->run() === false)
			{
				$data['proyectos'] = $this->multimedia_model->menuProyectos();

				if (isset($id))
				{
					$data['relacionar'] = $this->multimedia_model->getUsuariosAsociadosAlProyecto($id);

					$data['detalle'] = $this->multimedia_model->getProyectoDetalleRaw($id);
					$data['contactos'] = $this->contacto_model->getContactos(array('per_page'=>1000));
				}
				else
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/compartir_form', $data);
				$this->load->view($this->tema() . '/footer');
			}
			
			else
			{
				if ($this->multimedia_model->getUsuariosAsociadosAlProyecto($this->input->post('id')))
				{
					$this->multimedia_model->eliminarUsuariosAsociadosAlProyecto($this->input->post('id'));
				}
	
				if ($this->input->post('relacionados'))
				{
					foreach ($this->input->post('relacionados') as $relacionado)
					{
						$this->multimedia_model->asociarUsuarioAlProyecto($this->input->post('id'), $relacionado);
					}
				}
				
				redirect(base_url('multimedia/gestionar-proyecto/' . $this->input->post('id')));
			}
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar_proyecto($id)
	{
		if ($this->is_logged_in('admin'))
		{
			// models
			$this->load->model('multimedia_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->multimedia_model->getProyectoDetalle($id);
				
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/eliminar_proyecto', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'media_proyectos'))
				{
					$res = $this->sys_model->eliminar($id, 'media_proyectos');
					
					redirect(base_url('multimedia/proyectos'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/multimedia/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view($this->tema() . '/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	function reporte($empresa = null)
	{
		// helpers and libraries
		$this->load->helper('number');
				
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('multimedia_model');
				
			$data['path'] = FCPATH . 'multimedia/' . $this->usuario->grupo;
			if (isset($empresa)) $data['path'] .= '/' . $empresa;
			
	
			$this->load->helper('directory');
			$data['map'] = directory_map($data['path'], 1, true);
			
			
			$this->load->helper('file');
			
			foreach ($data['map'] as $obj)
			{
				$empresa = (int) $obj;
				
				$data['path_local'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $empresa;
				$data['info'] = get_dir_file_info($data['path_local']);
				
				if (!empty($data['info']))
				{
					foreach ($data['info'] as $item)
					{
						if ($item['id'] = $this->multimedia_model->getMediaIdFromArchivo($item['name']))
						{
							$row = $this->multimedia_model->getMediaDetalleRaw($item['id']);

							if ($row['estado'] > 0)
							{
								$item['nombre'] = $row['nombre'];
								$item['size'] = ROUND($item['size']/1024, 0);
								$item['size_db'] = $row['peso'];
								$item['fecha_alta'] = $row['fecha_alta'];
			
								$item['estado'] = ($item['size_db'] == $item['size']) ? 1 : 2; // ok! - Difiere el peso del archivo con el de la DB
								
								if ($item['estado'] == 2) $this->multimedia_model->modificarMedia($item['id'], array('peso'=>$item['size']));
							}
							else
							{
								$item['nombre'] = $item['name'];
								$item['size'] = ROUND($item['size']/1024, 0);
								$item['fecha_alta'] = null;
								$item['estado'] = 4; // Archivo eliminado
							}
						}
						else
						{
							$item['nombre'] = $item['name'];
							
							$item['size'] = ROUND($item['size']/1024, 0);
							$item['size_db'] = 0;
							$item['fecha_alta'] = null;
		
							$item['estado'] = 3; // No existe en DB
						}
						
						$data['reporte'][] = $item;
					}
				}
			}
			
			$this->load->view('/header');
			$this->load->view('/multimedia/reporte', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
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
	
	
	function thumb_from_id($id_tipo, $id)
	{
		// models
		$this->load->model('multimedia_model');
		
		$row = $this->multimedia_model->getMediaDetalleRaw($id);
		
		$upload_data['full_path'] = FCPATH . 'multimedia/' . $row['grupo'] . '/' . $row['id_empresa'] . '/' . $row['archivo'];
		
		if ($row['tipo'] == 'imagen')
		{
			$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
			$this->multimedia_model->ingresarThumb(1, $row['id'], $thumb);
			
			$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '320x180');
			$this->multimedia_model->ingresarThumb(2, $row['id'], $thumb);
			
			$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1280x720');
			$this->multimedia_model->ingresarThumb(3, $row['id'], $thumb);
		}
	}

	
	function ffmpeg($grupo, $id_empresa, $archivo, $stream, $reprocesar = false)
	{
		$uid = preg_replace('/.[^.]*$/', '', $archivo);
		
		// helpers and libraries
		$this->load->helper('file');
		
		
		// Procesar Preview
		$cmd = 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 170k -vf "scale=284:160:force_original_aspect_ratio=decrease,pad=284:160:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/preview/' . $uid . '.mp4
			';
		
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -f mjpeg -ss 120 -vframes 1 -vf "scale=426:240:force_original_aspect_ratio=increase,crop=426:240" /mnt/multimedia/preview/' . $uid . '.jpg
			';
		
		
		if ($stream == 3)
		{
			$data = '<?xml version="1.0" encoding="UTF-8"?>
					<smil title="revisionalpha.com">
						<body>
							<switch>
								<video src="' . $uid . '_240.mp4" width="426" height="240" systemLanguage="eng" title="240p">
									<param name="videoBitrate" value="270000" valuetype="data"></param>
									<param name="audioBitrate" value="44100" valuetype="data"></param>
								</video>
								<video src="' . $uid . '_360.mp4" width="640" height="360" systemLanguage="eng" title="360p">
									<param name="videoBitrate" value="360000" valuetype="data"></param>
									<param name="audioBitrate" value="44100" valuetype="data"></param>
								</video>
								<video src="' . $uid . '_480.mp4" width="854" height="480" systemLanguage="eng" title="480p">
									<param name="videoBitrate" value="450000" valuetype="data"></param>
									<param name="audioBitrate" value="44100" valuetype="data"></param>
								</video>
								<video src="' . $uid . '_720.mp4" width="1280" height="720" systemLanguage="eng" title="720p">
									<param name="videoBitrate" value="1350000" valuetype="data"></param>
									<param name="audioBitrate" value="44100" valuetype="data"></param>
								</video>
								<video src="' . $uid . '_1080.mp4" width="1920" height="1080" systemLanguage="eng" title="1080p">
									<param name="videoBitrate" value="2700000" valuetype="data"></param>
									<param name="audioBitrate" value="44100" valuetype="data"></param>
								</video>
							</switch>
						</body>
					</smil>';
			
			if (!write_file(FCPATH . 'multimedia/stream/' . $uid . '.smil', $data))
			{
			        $res['error'] = 'No se pudo crear el smil';
			}
			
			// Procesar Adaptative
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 300k -vf "scale=426:240:force_original_aspect_ratio=decrease,pad=426:240:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_240.mp4
			';
			
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 400k -vf "scale=640:360:force_original_aspect_ratio=decrease,pad=640:360:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_360.mp4
			';
			
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . '  -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 500k -vf "scale=854:480:force_original_aspect_ratio=decrease,pad=854:480:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_480.mp4
			';
			
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 1500k -vf "scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_720.mp4
			';
			
			$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 3000k -vf "scale=1920:1080:force_original_aspect_ratio=decrease,pad=1920:1080:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_1080.mp4
			';
		}
		
		elseif ($stream == 2)
		{
			if (!file_exists('/mnt/multimedia/stream/' . $uid . '_720.mp4') || $reprocesar == true)
			{
				$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $archivo . ' -ac 2 -ab 64k -c:v libx264 -threads 0 -r 50 -g 48 -keyint_min 48 -sc_threshold 0 -x264opts no-mbtree:bframes=1 -pass 1 -b:v 1500k -vf "scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2" /mnt/multimedia/stream/' . $uid . '_720.mp4
				';
			}
			
			$cmd .= 'rm /mnt/multimedia/stream/' . $uid . '.smil
			';
			$cmd .= 'rm /mnt/multimedia/stream/' . $uid . '_240.mp4
			';
			$cmd .= 'rm /mnt/multimedia/stream/' . $uid . '_360.mp4
			';
			$cmd .= 'rm /mnt/multimedia/stream/' . $uid . '_480.mp4
			';
			$cmd .= 'rm /mnt/multimedia/stream/' . $uid . '_1080.mp4
			';
		}
		
		else
		{
			$cmd = 'rm /mnt/multimedia/stream/' . $uid . '*
			';
		}
		
		if (!isset($res['error']))
		{
			if (!write_file(FCPATH . 'multimedia/procesar/' . $uid, $cmd))
			{
		        $res['error'] = 'No se pudo crear el archivo de procesos';
			}
			else
			{
				$res['mensaje'] = 'En breve comenzará la conversión de los archivos';
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	public function procesar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('multimedia_model');
			
			$data = $this->multimedia_model->getMediaDetalleRaw($id);
			$this->ffmpeg($data['grupo'], $data['id_empresa'], $data['archivo'], $data['stream'], true);
			
			redirect(base_url('multimedia/detalle/' . $id));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function empresa_banner_upload()
	{
		// models
		$this->load->model('multimedia_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// set validation rules
		$this->form_validation->set_rules('id', 'ID', 'required');
			
			if (empty($_FILES['file']['name']))
			{
			    $this->form_validation->set_rules('file', 'Archivo', 'required');
			}
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle']['id'] = $this->usuario->id_empresa;
			
			$this->load->view($this->tema() . '/header');
			$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
			$this->load->view($this->tema() . '/footer');
		}
		else
		{
			// models
			$this->load->model('sys_model');
		
			if ($this->sys_model->verificarPropiedad($this->input->post('id'), 'empresas'))
			{
				$config['upload_path'] = FCPATH . 'multimedia/thumbs/';
		
			    $config['encrypt_name'] = true;
			    $config['file_ext_tolower'] = 'true';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['overwrite'] = true;
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('file'))
		        {
			        // form values
			        $data['detalle']['id'] = $this->input->post('id');
		            $data['error'] = $this->upload->display_errors();

					$this->load->view($this->tema() . '/header');
					$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
					$this->load->view($this->tema() . '/footer');
		        }
		        else
		        {
					$upload_data = $this->upload->data();
			
					$data['id_tipo'] = $this->multimedia_model->getMediaTipoId($upload_data['file_ext']);
					$data['nombre'] = $upload_data['orig_name'];
					$data['archivo'] = $upload_data['file_name'];
					$data['peso'] = $upload_data['file_size'];
					
					if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
			        {
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1500x386', true);
						$this->multimedia_model->ingresarThumb(6, $this->input->post('id'), $thumb);
			        }

					redirect(base_url());
				}
			}
			else
			{
				// form values
				$data['detalle']['id'] = $this->input->post('id');
				$data['error'] = 'Ha habido un problema, por favor intenta más tarde';

				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/multimedia/upload_thumb', $data);
				$this->load->view($this->tema() . '/footer');
			}
		}
	}
	
	
	public function crop($id)
	{
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('multimedia_model');
			

			$data['detalle'] = $this->multimedia_model->getMediaDetalle($id);
			
			$data['detalle']['thumb'] = (isset($data['detalle']['thumb']) && !(file_exists(FCPATH . 'multimedia/thumb/' . $data['detalle']['thumb']))) ? base_url('multimedia/thumbs/' . $data['detalle']['thumb']) : null;
			//echo '<pre>' . print_r($data, true) . '</pre>';
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/cropper/cropper.min.css')
							);
		
			$this->load->view($this->tema() . '/header', $header);
			$this->load->view($this->tema() . '/multimedia/crop', $data);
			$this->load->view($this->tema() . '/footer');
		}
	}


}