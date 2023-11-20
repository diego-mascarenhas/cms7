<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/carrito/productos_model');
		$this->load->model('cms-v2/carrito/categorias_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$parametros['hijos'] = 1;
			$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
			$data['listado'] = $this->productos_model->getProductos();

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/productos/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//FILTRAR
	public function filtrar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$parametros['id_categoria'] = $this->input->post('id_categoria');
			$parametros1['hijos'] = 1;
			$data['categorias'] = $this->categorias_model->comboCategorias($parametros1);
			$data['listado'] = $this->productos_model->getProductos($parametros);

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/productos/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//FILTRO PARA EDICION MASIVA
	public function filtrar_masivo($categoria = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['categorias'] = $this->categorias_model->comboCategorias('productos');
			if($categoria > 0) 
			{ 
				$parametros['id_categoria'] = $categoria;
				$data['listado'] = $this->productos_model->getProductos($parametros);
			}
			else 
			{ 
				$parametros['id_categoria'] = $this->input->post('id_categoria');
				$data['listado'] = $this->productos_model->getProductos($parametros);
			}

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/productos/form-actualizar', $data);
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
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Nombre', 'required|min_length[3]', array('required' => 'Debe ingresar un nombre.', 'min_length' => 'Debe ingresar un nombre de al menos 3 caracteres.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));

			if ($this->form_validation->run() === false)
			{
				$parametros['hijos'] = 1;
				$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/productos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->productos_model->ingresarProducto($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$id_imagen_tipo = 8; //Productos
						$extension = 'es'; //Idioma
						$original = $this->upload($_FILES['imagen']['name'], $id_imagen_tipo);
						$modificar = $this->productos_model->updateImagen($data['id'], $original['id'], $extension, $id_imagen_tipo); 
					}
					
					redirect(base_url('cms-v2/carrito/productos'));
		        }
		        else
		        {
					$data['mensaje'] = 'Error en la carga de producto';

					$this->load->view('header');
					$this->load->view('cms-v2/carrito/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function modificar($idioma, $id)
	{
		if ($this->is_logged_in())
		{	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Nombre', 'required|min_length[3]', array('required' => 'Debe ingresar un nombre.', 'min_length' => 'Debe ingresar un nombre de al menos 3 caracteres.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			
			if ($this->form_validation->run() === false)
			{
				$parametros['idioma'] = $idioma;
				$parametros['id'] = $id;
				
				$parametros1['hijos'] = 1;
				$data['categorias'] = $this->categorias_model->comboCategorias($parametros1);
				$data['item'] = $this->productos_model->detalleProducto($parametros);

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/productos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->productos_model->modificarProducto($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
				        $id_producto = $this->input->post('id');
						$id_imagen_tipo = 8; //Productos
						$extension = 'es'; //Idioma
						$original = $this->upload($_FILES['imagen']['name'], $id_imagen_tipo);
						$modificar = $this->productos_model->updateImagen($id_producto, $original['id'], $extension, $id_imagen_tipo); 
					}
					redirect(base_url('cms-v2/carrito/productos'));
		        }
		        else
		        {
					$data['mensaje'] = 'Error en la modificación de producto';

					$this->load->view('header');
					$this->load->view('cms-v2/carrito/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function duplicar($id, $categoria = null)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->productos_model->duplicarProducto($id))
	        {
				if($categoria)
				{
					$this->load->helper('form');
					$parametros['id_categoria'] = $categoria;
					$data['categoria'] = $categoria;
					$parametros1['hijos'] = 1;
					$data['categorias'] = $this->categorias_model->comboCategorias($parametros1);
					$data['listado'] = $this->productos_model->getProductos($parametros);
		
					$this->load->view('header');
					$this->load->view('cms-v2/carrito/productos/index', $data);
					$this->load->view('footer');
				}
				else
				{
					redirect(base_url('cms-v2/carrito/productos'));
				}
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la duplicaci&oacute;n de producto';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	//EDICION MASIVA
	public function actualizacion()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$parametros['hijos'] = 1;
			$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
			$data['listado'] = $this->productos_model->getProductos();

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/productos/form-actualizar', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function actualizacion_masiva($categoria = null)
	{
		if ($this->is_logged_in())
		{
			if ($data = $this->productos_model->actualizarProductos($this->input->post()))
			{
				redirect(base_url('cms-v2/carrito/productos/actualizacion?actualizacion=ok'));
			}
			else
			{
				redirect(base_url('cms-v2/carrito/productos/actualizacion?actualizacion=error'));
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenar($categoria)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$parametros['id_categoria'] = $categoria;
			$data['listado'] = $this->productos_model->getProductos($parametros);

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/productos/ordenar', $data);
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarProductos()
	{
		$data = $this->productos_model->ordenarProductos(json_decode($this->input->post('items')));
		echo json_encode($data);
	}

	public function publicar()
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->productos_model->publicarProducto($this->input->post()))
	        {
	            redirect(base_url('cms-v2/carrito/productos'));
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la modificaci&oacute;n de producto';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function eliminar()
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->productos_model->eliminarProducto($this->input->post()))
	        {
	            redirect(base_url('cms-v2/carrito/productos'));
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la eliminaci&oacute;n de producto';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	//Ingresar imagen 
	public function upload($imagen, $id_imagen_tipo, $proyecto = null)
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

		if($proyecto)
		{
			$archivo = 'file';
		}
		else
		{
			$archivo = 'imagen';
		}
                
        if (!$this->upload->do_upload($archivo))
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
			if ($data = $this->multimedia_model->ingresarMedia($data))
	        {
		        
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
					//Ingreso imagen con sus medidas
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '360x460');
					$this->multimedia_model->ingresarThumb($id_imagen_tipo, $data['id'], $thumb);

					//Ingreso miniatura
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data['id'], $thumb);
		        }
		        return $data;
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