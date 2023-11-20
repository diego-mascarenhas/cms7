<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->getCategorias();

			$this->load->view('header');
			$this->load->view('tienda/categorias/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->getCategorias();

			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('categoria', 'Categoria', 'required', array('required' => 'Debe ingresar una categor&iacute;a.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));

			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('tienda/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->ingresarCategoria($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($data['id'], $_FILES['imagen']['name'], 'imagen', 21); 
						$update_imagen = $this->tienda_model->updateImagen($data['id'], $original['id'], 21, 'tienda_productos_categorias'); 
					}
					if($this->input->post('productos'))
					{
						redirect(base_url('tienda/productos/ingresar'));
					}
					else
					{
						redirect(base_url('tienda/categorias'));
					}
		        }
		        else
		        {
					$this->load->view('tienda/categorias/form');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('categoria', 'Categoria', 'required', array('required' => 'Debe ingresar una categor&iacute;a.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = $this->tienda_model->detalleCategoria($id);

				$this->load->view('header');
				$this->load->view('tienda/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->modificarCategoria($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($id, $_FILES['imagen']['name'], 'imagen', 21); 
						$update_imagen = $this->tienda_model->updateImagen($id, $original['id'], 21, 'tienda_productos_categorias'); 
					}
					redirect(base_url('tienda/categorias/'));
		        }
		        else
		        {
					$this->load->view('tienda/categorias/index');
			        echo 'Error';
		        }
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
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->getCategorias();
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('tienda/categorias/ordenar', $data);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarCategorias()
	{
		$data = $this->tienda_model->ordenarItems(json_decode($_POST['items']), 'tienda_productos_categorias');
		echo json_encode($data);
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_productos_categorias'))
	        {
				redirect(base_url('tienda/categorias'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/categorias/index');
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $tipo)
	{
		set_time_limit(0);
		ini_set('memory_limit', -1);

		// models
		$this->load->model('multimedia_model');
		
		$config['upload_path'] = FCPATH . 'multimedia/'.$this->usuario->grupo.'/tienda/';
		
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
			$data['estado'] = 3;
			
			//Ingreso
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {		        
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Sistema
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);

			        //Categorias
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '100x90');
					$this->multimedia_model->ingresarThumb(21, $data1['id'], $thumb);
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
}