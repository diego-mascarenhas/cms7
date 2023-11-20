<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cms extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('cms_model');
	
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');
			$parametros['categoria'] = $this->input->get('categoria');
			
			$data['contenidos'] = $this->cms_model->getContenidos($parametros);
			
			$config['total_rows'] = $this->cms_model->total();
			
			$data['categorias'] = $this->cms_model->getCategorias();
			
			// helpers and libraries
			$this->load->helper('form');
			
			$data['combo_categorias'] = $this->cms_model->categoriasCombo(null, $this->input->get('categoria'));
			$data['detalle']['categoria'] = $parametros['categoria'];
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/dataTables/datatables.min.css')
								);
								
			$this->load->view('/header', $header);
			($config['total_rows'] > 0) ? $this->load->view('/cms/contenidos/index', $data) : $this->load->view('/cms/contenidos/empty', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('cms_model');
			

			$data['detalle'] = $this->cms_model->getContenidoDetalle($id);
			
			if (isset($data['detalle']))
			{
				$this->load->view('/header');
				$this->load->view('/cms/contenidos/detalle', $data);
				//$this->load->view('/debug', array('debug'=>array($data['detalle'])));
				$this->load->view('/footer');
			}
			else
			{
				$this->load->view('/401');
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ingresar()
	{
		// models
		$this->load->model('cms_model');
		$this->load->model('multimedia_model');
		
		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);

		// set validation rules
		$this->form_validation->set_rules('categoria', 'ID Categoría', 'trim|required|integer');
		$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('subtitulo', 'Subtítulo', 'trim|min_length[4]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[4]');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
			$data['detalle']['campos'] = $this->cms_model->getCategoriaDetalleRaw($data['detalle']['categoria']);
			$data['estados'] = array(1=>'Borrador', 2=>'Publicar');
			if (isset($$data['detalle']['campos']['media_proyecto1_mostrar']) || isset($data['detalle']['campos']['media_proyecto2_mostrar'])) $data['media_proyectos'] = $this->multimedia_model->comboProyectos();

			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css'),
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/cms.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/cms/contenidos/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			if ($data = $this->cms_model->ingresarContenido($this->input->post()))
			{
				//redirect(base_url('cms/detalle/' . $data['id']));
				redirect(base_url('cms?categoria=' . $this->input->post('categoria')));
			}
			else
			{
				$data['error'] = 'Ha habido un problema, por favor intenta más tarde';

				$this->load->view('/cms/contenidos/error/');
			}
		}
	}
	
	
	public function modificar($id)
	{
		// models
		$this->load->model('cms_model');
		$this->load->model('multimedia_model');

		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
			
		// set validation rules
		$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('subtitulo', 'Subtítulo', 'trim|min_length[4]');
		$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[4]');
		
		if ($this->form_validation->run() === false)
		{
			// form values
			$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->cms_model->getContenidoDetalleRaw($id);
			$data['detalle']['campos'] = $this->cms_model->getCategoriaDetalleRaw($data['detalle']['categoria']);
			$data['estados'] = array(1=>'Borrador', 2=>'Publicar');
			if (isset($$data['detalle']['campos']['media_proyecto1_mostrar']) || isset($data['detalle']['campos']['media_proyecto2_mostrar'])) $data['media_proyectos'] = $this->multimedia_model->comboProyectos();
				
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css'),
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/plugins/select2/select2.min.css'),
									base_url('assets/css/cms.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/cms/contenidos/form', $data);
			$this->load->view('/footer');
		}
		else
		{
			if ($data = $this->cms_model->modificarContenido($id, $this->input->post()))
			{
				//redirect(base_url('cms/detalle/' . $id));
				redirect(base_url('cms?categoria=' . $this->input->post('categoria')));
			}
			else
			{
				$data['error'] = 'Ha habido un problema y no se pudo crear el ticket, por favor intenta más tarde';

				$this->load->view('/cms/contenidos/error/', $data);
			}
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
		{
			// models
			$this->load->model('cms_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->cms_model->getContenidoDetalleRaw($id);
				
				$this->load->view('/header');
				$this->load->view('/cms/contenidos/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'contenido'))
				{
					$res = $this->sys_model->eliminar($id, 'contenido');
					
					redirect(base_url('cms'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/cms/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
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
		$this->load->model('cms_model');
		
		$config['upload_path'] = FCPATH . 'multimedia/cms/';
		
		if (!is_dir($config['upload_path']))
		{
	    	if (!mkdir($config['upload_path'], 0777, TRUE))
	    	{
	    		exit('No se puede crear la carpeta.');									
	    	}
		}
		
	    $config['encrypt_name'] = false;
	    $config['file_ext_tolower'] = 'true';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['overwrite'] = true;
		$config['file_name'] = md5(uniqid());
		
		$this->load->library('upload', $config);

        if (!$this->upload->do_upload('file'))
        {
                $error = array('error' => $this->upload->display_errors());
				echo '<pre>' . print_r($error, true) . '</pre>';
        }
        else
        {
			$upload_data = $this->upload->data();
			
			if ($this->input->post('archivo') == 'imagen1')
			{
				$data['imagen1'] = $config['file_name'] . $upload_data['file_ext'];
			}
			elseif ($this->input->post('archivo') == 'imagen2')
			{
				$data['imagen2'] = $config['file_name'] . $upload_data['file_ext'];
			}
			else
			{
				$data['imagen3'] = $config['file_name'] . $upload_data['file_ext'];
			}
			
			if ($data = $this->cms_model->modificarContenido($this->input->post('id'), $data))
	        {
		        // Ok!
				echo 'ok!';
	        }
	        else
	        {
		        // Error!
		        echo 'Error!';
	        }

			//redirect(base_url('cms'));
		}
	}


}