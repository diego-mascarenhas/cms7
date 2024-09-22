<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tienda extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function mi_tienda()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un nombre de tienda.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['rubros'] = $this->tienda_model->listadoRubros('ar');
				$data['paises'] = $this->tienda_model->listadoPaises();

				$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['sucursales'] = $this->tienda_model->getSucursales($data['item']['id']);
				
				$this->load->view('header');
				$this->load->view('tienda/configuracion/tienda', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->tienda_model->modificarTienda($this->input->post()))
		        {
			        if(!empty($_FILES['logo']['name']))
			        {
						$original = $this->upload($data['id'], $_FILES['logo']['name'], 'logo', 10); 
						$update_imagen = $this->tienda_model->updateImagen($data['id'], $original['id'], 10, 'tienda_configuracion'); 
					}
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($data['id'], $_FILES['imagen']['name'], 'imagen', 11); 
						$update_imagen = $this->tienda_model->updateImagen($data['id'], $original['id'], 11, 'tienda_configuracion'); 
					}
					redirect(base_url('tienda/tienda/mi-tienda?ok=1'));
		        }
		        else
		        {
					$this->load->view('tienda/tienda/mi-tienda?error=1', $data);
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function sucursales($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('titulo', 'Titulo', 'required', array('required' => 'Debe ingresar un nombre para la sucursal.'));
			$this->form_validation->set_rules('orden', 'Orden', 'required', array('required' => 'Debe ingresar un orden.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			$this->form_validation->set_rules('celular', 'Celular', 'required', array('required' => 'Debe ingresar un celular.'));
			$this->form_validation->set_rules('email', 'Email', 'required', array('required' => 'Debe ingresar un email.'));
			$this->form_validation->set_rules('domicilio', 'Domicilio', 'required', array('required' => 'Debe ingresar un domicilio.'));
			$this->form_validation->set_rules('numero', 'N&uacute;mero', 'required', array('required' => 'Debe ingresar un n&uacute;mero de calle.'));
			$this->form_validation->set_rules('localidad', 'Localidad', 'required', array('required' => 'Debe ingresar una localidad.'));
			$this->form_validation->set_rules('provincia', 'Provincia', 'required', array('required' => 'Debe ingresar una provincia.'));
			$this->form_validation->set_rules('pais', 'Pa&iacute;s', 'required|is_natural_no_zero', array('required' => 'Debe ingresar un pa&iacute;s.', 'is_natural_no_zero' => 'Debe ingresar un pa&iacute;s.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['paises'] = $this->tienda_model->listadoPaises();
				
				if(isset($id))
				{
					$data['item'] = $this->tienda_model->detalleSucursal($id);
				}
				else
				{
					$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view('header');
				$this->load->view('tienda/sucursales', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->ingresarSucursal($this->input->post()))
		        {
					redirect(base_url('/tienda/tienda/mi-tienda?tab=sucursales'));
		        }
		        else
		        {
					$this->load->view('tienda/tienda/sucursales', $data);
			        echo 'Error';
		        }
			}

		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function envios()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('relacionesenvios[]', 'relacionesenvios', 'required', array('required' => 'Debe ingresar opciones de envios.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['envios'] = $this->tienda_model->listadoMediosEnvios();
				$data['enviosrelacionados'] = $this->tienda_model->listadoEnviosTienda($data['item']['id']);
				
				$this->load->view('header');
				$this->load->view('tienda/configuracion/envios', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($envios = $this->tienda_model->relacionarEnvios($this->input->post()))
		        {
					redirect(base_url('tienda/tienda/envios?ok=1'));
		        }
		        else
		        {
					redirect(base_url('tienda/tienda/envios?error=1'));
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function pagos()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('relaciones[]', 'relaciones', 'required', array('required' => 'Debe ingresar opciones de pago.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['medios'] = $this->tienda_model->listadoMediosPago();
				$data['mediosrelacionados'] = $this->tienda_model->listadoFormasPago($data['item']['id']);
				
				$this->load->view('header');
				$this->load->view('tienda/configuracion/pagos', $data);
				$this->load->view('footer');
			}
			else
			{
				$data = $this->tienda_model->modificarTienda($this->input->post());
				if($formas = $this->tienda_model->relacionarFormasPago($this->input->post()))
				{
					redirect(base_url('tienda/tienda/pagos?ok=1'));
		        }
		        else
		        {
					redirect(base_url('tienda/tienda/pagos?error=1'));
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function redes()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'ID', 'required', array('required' => 'Debe ingresar una tienda.'));
			
			if ($this->form_validation->run() === false)
			{				
				$data['item'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				
				$this->load->view('header');
				$this->load->view('tienda/configuracion/redes', $data);
				$this->load->view('footer');
			}
			else
			{
				if($data = $this->tienda_model->modificarTienda($this->input->post()))
				{
					redirect(base_url('tienda/tienda/redes?ok=1'));
		        }
		        else
		        {
					redirect(base_url('tienda/tienda/redes?error=1'));
		        }
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

					switch($tipo)
					{
				        //Logo
				        case 10: 
				        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '250x250');
						$this->multimedia_model->ingresarThumb(10, $data1['id'], $thumb);
						break;

				        //Header
						case 11: 
						$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '1100x450');
						$this->multimedia_model->ingresarThumb(11, $data1['id'], $thumb);
						break;
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
	
	
	public function tiendas()
	{
		if ($this->is_logged_in())
		{
			$data['listado'] = $this->tienda_model->getTiendas();
	
			$this->load->view('header');
			$this->load->view('tienda/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar_sucursal($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_sucursales'))
	        {
				redirect(base_url('tienda/tienda/mi-tienda'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/tienda/mi-tienda#tab-5');
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}