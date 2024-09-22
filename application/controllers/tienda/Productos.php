<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id']);

			$this->load->view('header');
			$this->load->view('tienda/productos/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//EXPORTAR
	public function exportar()
	{
		$this->load->dbutil();
		$this->load->helper('download');
		$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
		$archivo = 'LISTADO_DE_PRODUCTOS.csv';
		$productos = $this->tienda_model->getProductosExportar($data['tienda']['id']);
		$data = ltrim($this->dbutil->csv_from_result($productos, ';', "\r\n"));
		force_download($archivo, $data);
	}

	//IMPORTAR
	public function importar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('file');
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			if(empty($_FILES['archivo']['name']))
			{
				$this->form_validation->set_rules('archivo', 'Archivo', 'required', array('required' => 'Debe subir un archivo.'));
				
				if ($this->form_validation->run() === false)
				{
					$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
					$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
					$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id']);
		
					$this->load->view('header');
					$this->load->view('tienda/productos/importar', $data);
					$this->load->view('footer');
				}
			}
			else
			{
			 if($_FILES["archivo"]['type'] == 'text/csv')
			 {
			     $fname = $_FILES['archivo']['name'];
			     $chk_ext = explode(".",$fname);
			     
			     if(strtolower(end($chk_ext)) == "csv")
			     {
					//Damos permisos de lectura para subir
					$filename = $_FILES['archivo']['tmp_name'];
					$handle = fopen($filename, "r");
					if(strpos(fgets($handle), ';'))
					{
						$separador = ';';
					}
					else
					{
						$separador = ',';
					}

					while (($data = fgetcsv($handle, 1000, $separador)) !== FALSE)
					{ 
						$tienda = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
						$importar = $this->tienda_model->importarProducto($data, $tienda['id']);
					}
					$this->session->set_flashdata('resultado', 'ok');
			        $this->session->set_flashdata('mensaje', 'El archivo fue subido correctamente.');
				}
				else
				{
			        $this->session->set_flashdata('resultado', 'error');
			        $this->session->set_flashdata('mensaje', 'El archivo es incorrecto. Recuerde subir un archivo CSV.');
				}
			 }
			 else
			 {
			    $this->session->set_flashdata('resultado', 'error');
			    $this->session->set_flashdata('mensaje', 'El archivo es incorrecto. Recuerde subir un archivo CSV.');
			 }
			 
			 redirect(base_url('tienda/productos/'));
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	//BRULER SUBIR ARCHIVO
	public function bruler()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id']);

			$this->load->view('header');
			$this->load->view('tienda/productos/bruler', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//BRULER IMPORTAR
	public function bruler_importar()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$productos = $this->tienda_model->listadoProductosBruler($data['tienda']['id']);

			if($productos)
			{
				$productos_data = json_decode($productos['data'],true);
	
				foreach($productos_data as $producto)
				{
					$this->tienda_model->importarProductoBruler($producto, $data['tienda']['id']);
				}
	
				$this->tienda_model->modificarProductosBruler($productos['id']);

			    $this->session->set_flashdata('resultado', 'ok');
			    $this->session->set_flashdata('mensaje', 'Los productos se importaron.');
			    redirect(base_url('tienda/productos/'));
			    die();
			}
			else
			{
			    $this->session->set_flashdata('resultado', 'error');
			    $this->session->set_flashdata('mensaje', 'No hay productos para importar.');
			    redirect(base_url('tienda/productos/'));
			    die();
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//EDICION MASIVA
	public function editar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id']);

			$this->load->view('header');
			$this->load->view('tienda/productos/index-form', $data);
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

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			if($categoria > 0) 
			{ 
				$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id'], $categoria); 
			}
			else 
			{ 
				$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id'], $this->input->post('id_categoria'));
			}

			$this->load->view('header');
			$this->load->view('tienda/productos/index-form', $data);
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
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

			if($categoria > 0) 
			{ 
				if ($data = $this->tienda_model->actualizacionMasivaProducto($this->input->post(), $data['tienda']['id'],$categoria))
				{
					redirect(base_url('tienda/productos/filtrar-masivo/'.$categoria.'/?actualizacion=ok'));
				}
				else
				{
					redirect(base_url('tienda/productos/filtrar-masivo/'.$categoria.'/?actualizacion=error'));
				}
			}
			else
			{
				if ($data = $this->tienda_model->actualizacionMasivaProducto($this->input->post(), $data['tienda']['id']))
				{
					redirect(base_url('tienda/productos/editar?actualizacion=ok'));
				}
				else
				{
					redirect(base_url('tienda/productos/editar?actualizacion=error'));
				}
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}


	public function filtrar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			$data['listado'] = $this->tienda_model->getProductos($data['tienda']['id'], $this->input->post('id_categoria'));

			$this->load->view('header');
			$this->load->view('tienda/productos/index', $data);
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
/* 			$data['listado'] = $this->tienda_model->getTiendas(); */
	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un titulo.'));
/*             if(!empty($this->input->post('codigo'))) { $this->form_validation->set_rules('codigo', 'Código', 'callback_codigo_producto'); }  */
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('tienda/productos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->ingresarProducto($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($_FILES['imagen']['name'], 22); 
						$update_imagen = $this->tienda_model->updateImagen($data['id'], $original['id'], 22, 'tienda_productos'); 
					}
					redirect(base_url('tienda/productos/'));
		        }
		        else
		        {
					$this->load->view('tienda/productos/index');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function actualizacion()
	{
		if ($this->is_logged_in())
		{
			if($this->input->get('ok'))
			{
				$data['ok'] = "Actualización exitosa.";
			}

			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('porcentaje', 'Porcentaje', 'required', array('required' => 'Debe ingresar un porcentaje.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);

				$this->load->view('header');
				$this->load->view('tienda/productos/actualizacion', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->actualizacionProducto($this->input->post()))
		        {
					redirect(base_url('tienda/productos/actualizacion?ok=actualizacion'));
		        }
		        else
		        {
					$this->load->view('tienda/productos/index');
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
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un titulo.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

				$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
				$data['item'] = $this->tienda_model->detalleProducto($id, 1, 0);

				$this->load->view('header');
				$this->load->view('tienda/productos/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->modificarProducto($id, $this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($_FILES['imagen']['name'], 22); 
						$update_imagen = $this->tienda_model->updateImagen($id, $original['id'], 22, 'tienda_productos'); 
					}
					redirect(base_url('tienda/productos/'));
		        }
		        else
		        {
					$this->load->view('tienda/productos/index');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function galeria($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->helper('text');
			$this->load->helper('number');
			$this->load->library('form_validation');
			$this->load->model('multimedia_model');

			//VERIFICO SI HAY PROYECTO PARA ESTE PRODUCTO
			$data['proyecto'] = $this->tienda_model->getProyectoFromProducto($id);

			//SI HAY PROYECTO TRAIGO LISTADO DE IMAGENES ASOCIADAS
			if($data['proyecto'])
			{
				$parametros['proyecto'] = $data['proyecto']['id'];
				$data['medias'] = $this->multimedia_model->getMedias($parametros);
				$data['id_proyecto'] = $data['proyecto']['id'];
			}

			//SI NO HAY PROYECTO LO INGRESO Y ASOCIO CON EL PRODUCTO
			else
			{
				$data['producto'] = $this->tienda_model->detalleProducto($id, 1, 0);
				$valores['proyecto'] = $data['producto']['titulo'];
				$valores['estado'] = 3;

				if ($data = $this->multimedia_model->ingresarProyecto($valores))
		        {
					$relacionar = $this->tienda_model->relacionarProyecto($data['id'], $id);
					$data['id_proyecto'] = $data['id'];
		        }
		    }
		
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['categorias'] = $this->tienda_model->listadoCategoriasUser($data['tienda']['id']);
			$data['item'] = $this->tienda_model->detalleProducto($id, 1, 0);
			$data['dropzone']['accepted_files'] = '.' . implode(',.', $this->multimedia_model->getMediaArchivosPermitidos());

			$this->load->view('header');
			$this->load->view('tienda/productos/galeria', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function upload_galeria()
	{
		if ($this->is_logged_in())
		{
	        if(!empty($_FILES))
	        {
				$original = $this->upload($_FILES['file']['name'], 23, $this->input->post('id_proyecto')); 
			}
			else
			{
				redirect(base_url('tienda/productos/galeria/'.$this->input->post('producto')));
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
			
	//Ingresar imagen 
	public function upload($imagen, $tipo, $proyecto = null)
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
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {		        
		        if ($proyecto) $this->multimedia_model->asociarMedia($data1['id'], $proyecto);

		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Sistema
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);

			        if($tipo == '23')
			        {
				        //Productos Galeria
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '640x640');
						$this->multimedia_model->ingresarThumb(23, $data1['id'], $thumb);
					}
					else
			        {
				        //Productos Miniaturas
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '300x270');
						$this->multimedia_model->ingresarThumb(22, $data1['id'], $thumb);
					}
		        }
	        }
	        else
	        {
		        // Error!
		        echo 'Error!';
	        }
	        return($data1);
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
	
	public function ordenar($categoria)
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoProductos($data['tienda']['id'], $categoria);
			$data['categoria'] = $this->tienda_model->detalleCategoria($categoria);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('tienda/productos/ordenar', $data);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarProductos()
	{
		$data = $this->tienda_model->ordenarItems(json_decode($_POST['items']), 'tienda_productos');
		echo json_encode($data);
	}

	public function cambiar_estado($id)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->cambiarEstado($id, 'tienda_productos'))
	        {
				redirect(base_url('tienda/productos'));
	        }
	        else
	        {
				$this->load->view('tienda/productos');
		        echo 'Error';
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
			if ($data = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_productos'))
	        {
				redirect(base_url('tienda/productos'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/productos/index');
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//VALIDAR CODIGO
    public function codigo_producto()
    {
        $response = array();
        $str = $this->input->post('codigo');
        $producto = $this->input->post('id_producto');

		$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
/* 		$data['codigo'] = $this->tienda_model->verificarCodigo($str, $producto, $data['tienda']['id']); */

		$sql = "SELECT id, codigo FROM tienda_productos";
		$sql .= " WHERE id_tienda = ".$data['tienda']['id'];
		$sql .= " AND codigo = '".$str."'";
		$sql .= " AND estado >= 0";
		if(!empty($producto))
		{
			$sql .= " AND id != $producto";
		}		
		$query = $this->db->query($sql);
		$codigo = $query->row_array();

        if ($str === $codigo['codigo'])
        {
            echo(json_encode("El código ya existe, ingrese otro")); 
/*
            $this->form_validation->set_message('codigo_producto', 'El código ya existe, ingrese otro.');
            return FALSE;
*/
        }
        else
        {
            echo(json_encode(true)); 
/*             return TRUE; */
        }
    }
}