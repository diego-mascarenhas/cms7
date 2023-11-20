<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encuestas extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('evento_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->config->set_item('language', $this->usuario->idioma);
			$data['listado'] = $this->evento_model->getEventos();
		
			$this->load->view('header', array('buscador'=>true));
			$this->load->view('encuestas/index', $data);
			$this->load->view('footer');
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
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			if($data = $this->evento_model->verificarCodigo($this->usuario->grupo, $this->usuario->id_empresa, $this->input->post('codigo')))
			{
				$this->form_validation->set_rules('codigo', 'C&Oacute;DIGO', 'required|is_unique[eventos.codigo]', array('required' => 'Debe ingresar un C&Oacute;DIGO.','is_unique' => 'El c&oacute;digo ya fue ingresado, debe ingresar un c&oacute;digo diferente.'));

			}
			else
			{
				$this->form_validation->set_rules('codigo', 'C&Oacute;DIGO', 'required', array('required' => 'Debe ingresar un C&Oacute;DIGO.'));
			}

			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('fecha_vencimiento', 'VENCIMIENTO', 'required', array('required' => 'Debe ingresar la FECHA DE VENCIMIENTO.'));
			
			if(empty($_FILES['imagen']['name']))
			{
				$this->form_validation->set_rules('imagen', 'IMAGEN', 'required', array('required' => 'Debe ingresar una imagen.'));
			}
				
			if ($this->form_validation->run() === false)
			{
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->ingresarEvento($this->input->post()))
				{					
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($data['id'], $_FILES['imagen']['name'], 'imagen', '842x597');

						//Ingreso id de media en eventos
						$valores['id_media'] = $original['id'];
						$ingresar = $this->evento_model->modificarEvento($data['id'], $valores);
					}
		            redirect(base_url('encuestas'));
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
			redirect(base_url('user/login'));
		}
	}

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			$codigo = $this->evento_model->verificarCodigo($this->usuario->grupo, $this->usuario->id_empresa, $this->input->post('codigo'));
			
			if($codigo === $this->input->post('codigo'))
			{
				$this->form_validation->set_rules('codigo', 'C&Oacute;DIGO', 'required|is_unique[eventos.codigo]', array('required' => 'Debe ingresar un C&Oacute;DIGO.','is_unique' => 'El c&oacute;digo ya fue ingresado, debe ingresar un c&oacute;digo diferente.'));

			}
			else
			{
				$this->form_validation->set_rules('codigo', 'C&Oacute;DIGO', 'required', array('required' => 'Debe ingresar un C&Oacute;DIGO.'));
			}
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('fecha_vencimiento', 'VENCIMIENTO', 'required', array('required' => 'Debe ingresar la FECHA DE VENCIMIENTO.'));
				
			if ($this->form_validation->run() === false)
			{
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				$data['detalle'] = $this->evento_model->detalleEvento($id);
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->modificarEvento($id, $this->input->post()))
				{					
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->upload($id, $_FILES['imagen']['name'], 'imagen', '842x597');

						//Ingreso id de media en eventos
						$valores['id_media'] = $original['id'];
						$ingresar = $this->evento_model->modificarEvento($id, $valores);
					}
		            redirect(base_url('encuestas'));
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
			redirect(base_url('user/login'));
		}
	}

	public function duplicar($id)
	{
		if ($this->is_logged_in())
		{
			if ($data = $this->evento_model->duplicarEvento($id))
	        {
				redirect(base_url('encuestas'));
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
			$this->config->set_item('language', $this->usuario->idioma);
			
			$parametros['order_by'] = 'eventos.orden';
			$parametros['order'] = 'ASC';
			
			$data['listado'] = $this->evento_model->getEventos($parametros);

			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('encuestas/ordenar', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarEventos()
	{
		$data = $this->evento_model->ordenarItems(json_decode($_POST['items']), 'eventos');
		echo json_encode($data);
	}

	public function subir_archivo($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			$this->form_validation->set_rules('id_evento', 'EVENTO', 'required', array('required' => 'Debe tener un EVENTO.'));
			if ($this->form_validation->run() === false)
			{
				$data['detalle'] = $this->evento_model->detalleEvento($id);
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/subir_archivo', $data);
				$this->load->view('footer');
			}
			else
			{
				 //Seleccionamos CSV
		         $fname = $_FILES['archivo']['name'];
		         $chk_ext = explode(".",$fname);
		         
		         if(strtolower(end($chk_ext)) == "csv")
		         {
					//si es correcto, entonces damos permisos de lectura para subir
					$filename = $_FILES['archivo']['tmp_name'];
					$handle = fopen($filename, "r");
					
					while (($data = fgetcsv($handle, 1000, ',')) !== FALSE)
					{ 
						//INGRESO EN CONTACTOS
						$valores['grupo'] = $this->usuario->grupo;
						$valores['id_empresa'] = $this->usuario->id_empresa;
						$valores['nombre'] = $data[0];
						$valores['apellido'] = $data[1];
						$valores['email'] = $data[2];
						$valores['fecha_alta'] = now();
						$valores['username_alta'] = $this->usuario->id;

						$sql = "SELECT id, estado FROM eventos_contactos";
						$sql .= " WHERE grupo = ".$this->usuario->grupo;
						$sql .= " AND id_empresa = ".$this->usuario->id_empresa;
						$sql .= " AND email = '".$valores['email']."'";
						
						$query = $this->db->query($sql);
						$contacto = $query->row_array();

						if($contacto)
						{
							$sql2 = "SELECT id, certificado FROM eventos_rel_evento_contactos";
							$sql2 .= " WHERE grupo = ".$this->usuario->grupo;
							$sql2 .= " AND id_empresa = ".$this->usuario->id_empresa;
							$sql2 .= " AND id_evento = ".$this->input->post('id_evento');
							$sql2 .= " AND id_contacto = ".$contacto['id'];
							
							$query = $this->db->query($sql2);
							$contacto2 = $query->row_array();
							
							if(!$contacto2)
							{
								//INGRESO LA RELACION
								$relacion['grupo'] = $this->usuario->grupo;
								$relacion['id_empresa'] = $this->usuario->id_empresa;
								$relacion['id_evento'] = $this->input->post('id_evento');
								$relacion['id_contacto'] = $contacto['id'];
								
								$insert = $this->db->insert('eventos_rel_evento_contactos', $relacion);
							}
						}
						else
						{
							$res = $this->db->insert('eventos_contactos', $valores);
							$id = $this->db->insert_id();
	
							if($res)
							{
								//INGRESO LA RELACION
								$relacion['grupo'] = $this->usuario->grupo;
								$relacion['id_empresa'] = $this->usuario->id_empresa;
								$relacion['id_evento'] = $this->input->post('id_evento');
								$relacion['id_contacto'] = $id;
								
								$res2 = $this->db->insert('eventos_rel_evento_contactos', $relacion);
							}
						}
					} 
				}

		        redirect(base_url('encuestas/contactos/'.$this->input->post('id_evento')));
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
			if ($datos = $this->evento_model->eliminarItem($this->input->post(), 'eventos'))
	        {
				redirect(base_url('encuestas'));
	        }
	        else
	        {
		        $data = 'Error';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('encuestas', $data);
				$this->load->view('/footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}				

	public function contactos($id)
	{
		if ($this->is_logged_in())
		{
			$this->config->set_item('language', $this->usuario->idioma);
			$data['detalle'] = $this->evento_model->detalleEvento($id);

			$parametros['id_evento'] = $id;
			$data['listado'] = $this->evento_model->getContactos($parametros);
		
			$this->load->view('header', array('buscador'=>true));
			$this->load->view('encuestas/contactos', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
	

	public function preguntas($id)
	{
		if ($this->is_logged_in())
		{
			$this->config->set_item('language', $this->usuario->idioma);
			$data['detalle'] = $this->evento_model->detalleEvento($id);

			$parametros['id_evento'] = $id;
			$data['listado'] = $this->evento_model->getPreguntas($parametros);
		
			$this->load->view('header', array('buscador'=>true));
			$this->load->view('encuestas/preguntas', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
	
	public function ingresar_pregunta($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
			if ($this->form_validation->run() === false)
			{
				$data['evento'] = $this->evento_model->detalleEvento($id);
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/ingresar_pregunta', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->ingresarPregunta($this->input->post()))
				{					
		            redirect(base_url('encuestas/preguntas/'.$id));
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
			redirect(base_url('user/login'));
		}
	}

	public function modificar_pregunta($id, $pregunta)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
			if ($this->form_validation->run() === false)
			{
				$data['evento'] = $this->evento_model->detalleEvento($id);
				$data['detalle'] = $this->evento_model->detallePregunta($pregunta);
				$parametros['pregunta'] = $pregunta;
				$data['listado'] = $this->evento_model->getRespuestas($parametros);
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/ingresar_pregunta', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->modificarPregunta($id, $this->input->post()))
				{					
		            redirect(base_url('encuestas/preguntas/'.$id));
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
			redirect(base_url('user/login'));
		}
	}

	public function ordenar_preguntas($id)
	{
		if ($this->is_logged_in())
		{
			$this->config->set_item('language', $this->usuario->idioma);
			
			$parametros['order_by'] = 'eventos_preguntas.orden';
			$parametros['order'] = 'ASC';
			$parametros['id_evento'] = $id;
			
			$data['detalle'] = $this->evento_model->detalleEvento($id);
			$data['listado'] = $this->evento_model->getPreguntas($parametros);
			
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('encuestas/ordenar_preguntas', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarPreguntas()
	{
		$data = $this->evento_model->ordenarItems(json_decode($_POST['items']), 'eventos_preguntas');
		echo json_encode($data);
	}

	public function duplicar_pregunta($id, $id_evento)
	{
		if ($this->is_logged_in())
		{
			if ($data = $this->evento_model->duplicarPregunta($id))
	        {
				redirect(base_url('encuestas/preguntas/'.$id_evento));
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar_pregunta($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->evento_model->eliminarItem($this->input->post(), 'eventos_preguntas'))
	        {
				redirect(base_url('encuestas/preguntas/'.$this->input->post('id_evento')));
	        }
	        else
	        {
		        $data = 'Error';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('encuestas', $data);
				$this->load->view('/footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}				

	public function ingresar_respuesta($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
			if ($this->form_validation->run() === false)
			{
				$data['evento'] = $this->evento_model->detalleEvento($id);
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/ingresar_pregunta', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->ingresarRespuestaCMS($this->input->post()))
				{					
		            redirect(base_url('encuestas/modificar_pregunta/'.$this->input->post('id_evento').'/'.$this->input->post('id_pregunta')));
				}
				else
				{
					echo 'Ha habido un problema, por favor intenta más tarde';
					die();
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	public function modificar_respuesta($id = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);

			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
				
			if ($this->form_validation->run() === false)
			{
				$data['evento'] = $this->evento_model->detalleEvento($id);
				$data['estados'] = array(1=>'Inactivo', 2=>'Activo');
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('encuestas/ingresar_pregunta', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->evento_model->modificarRespuestaCMS($this->input->post()))
				{					
		            redirect(base_url('encuestas/modificar_pregunta/'.$this->input->post('id_evento').'/'.$this->input->post('id_pregunta')));
				}
				else
				{
					echo 'Ha habido un problema, por favor intenta más tarde';
					die();
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	public function ordenarRespuestas()
	{
		$data = $this->evento_model->ordenarItems(json_decode($_POST['items']), 'eventos_respuestas');
		echo json_encode($data);
	}
	
	public function eliminar_respuesta($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->evento_model->eliminarItem($this->input->post(), 'eventos_respuestas'))
	        {
	            redirect(base_url('encuestas/modificar_pregunta/'.$this->input->post('id_evento').'/'.$this->input->post('id_pregunta')));
	        }
	        else
	        {
		        $data = 'Error';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('encuestas/ingresar_pregunta', $data);
				$this->load->view('/footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}				


	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $medidas)
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
			if ($data = $this->multimedia_model->ingresarMedia($data))
	        {
		        //Asocio
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {					
					//Ingreso imagen con sus medidas
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, '842x597');
					$this->multimedia_model->ingresarThumb(28, $data['id'], $thumb);
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
	
	public function resultados($id, $id_pregunta)
	{
		if ($this->is_logged_in())
		{
			$data['detalle'] = $this->evento_model->detalleEvento($id);
			$data['total'] = $this->evento_model->totalRespuestas($id_pregunta);
			$data['listado'] = $this->evento_model->resultadosRespuestas($id_pregunta);
	
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('encuestas/resultados', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function resultados_contactos($id_pregunta, $id_respuesta)
	{
		if ($this->is_logged_in())
		{
			$data['listado'] = $this->evento_model->resultadosRespuestas($id_pregunta, $id_respuesta);
	
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('encuestas/resultados_contactos', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function resultados_generales($id)
	{
		if ($this->is_logged_in())
		{
			$parametros['id_evento'] = $id;
			$parametros['encuesta'] = 1;
			$data['listado'] = $this->evento_model->getPreguntas($parametros);
			$data['detalle'] = $this->evento_model->detalleEvento($id);
	
			$this->load->view('/header', array('buscador'=>true));
			$this->load->view('encuestas/resultados_generales', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	//ANTERIOR, USADO EN OBA 2020
/*
	public function resultados($id, $respuesta = null)
	{
		if ($this->is_logged_in())
		{
			$data['pregunta'] = $id;
			$parametros['pregunta'] = $id;
			$parametros['respuesta'] = $respuesta;
			$data['listado'] = $this->evento_model->estadisticasRespuestas($parametros);
	
			$this->load->view('header');
			$this->load->view('encuestas', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
*/
}
