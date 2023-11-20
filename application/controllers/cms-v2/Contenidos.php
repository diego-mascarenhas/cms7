<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contenidos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//cargo el modelo de secciones
		$this->load->model('cms-v2/Contenidos_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Contenidos_model->listadoItems(null);
	
			$this->load->view('header');
			$this->load->view('cms-v2/contenidos/index', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'Nombre', 'required', array('required' => 'Debe ingresar un nombre.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(1=>'Inactivo',3=>'Activo');
				$datos['destacados'] = array(0=>'No', 1=>'S&iacute;');
				
				if(isset($id))
				{
					$datos['item'] = $this->Contenidos_model->detalleItem($id);
					$datos['categoria'] = $this->Contenidos_model->detalleCategoria($datos['item']['id_con_secciones']);
					$datos['recomendaciones'] = $this->Contenidos_model->listadoContenidosAdicionales($id, 3, 'es',0);
					$datos['boxes'] = $this->Contenidos_model->listadoContenidosAdicionales($id, 4, 'es',0);
					$datos['iconos'] = array('aprende'=>'aprende', 'aula'=>'aula', 'certificado'=>'certificado', 'horario'=>'horario');
					$datos['colores'] = array('mostaza'=>'mostaza', 'rojo'=>'rojo', 'turquesa'=>'turquesa', 'turquesa_oscuro'=>'turquesa oscuro', 'amarillo'=>'amarillo', 'azul'=>'azul');
					$datos['slides'] = $this->Contenidos_model->listadoMedia($id, 2);
					$datos['partners'] = $this->Contenidos_model->listadoMedia($id, 3);

					//multimedia
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$datos['media_proyectos'] = $this->multimedia_model->comboProyectos();
					//fin multimedia
				}
				else
				{
					$datos['categoria'] = $this->Contenidos_model->detalleCategoria($this->input->get('categoria'));
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/contenidos/ingresar', $datos);
/* 				$this->load->view('footer'); */
			}
			else
			{
				if ($datos = $this->Contenidos_model->ingresarItem($this->input->post()))
		        {
			        if(!empty($_FILES['image']['name']))
			        {
						$original = $this->Contenidos_model->subirImagen($this->input->post('id'),'imagen'); 
					}
			        if(!empty($_FILES['imagen_adicional']['name']))
			        {
						$original = $this->Contenidos_model->subirOriginal($this->input->post('id'),'imagen_adicional'); 
					}					
					redirect(base_url('cms-v2/contenidos/'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/contenidos/detalle', $datos);
					$this->load->view('footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}


	public function ingresar_informacion($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'?error=informacion'));
		}
		else
		{
			$datos = $this->Contenidos_model->ingresarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id')));
		}
	}

	public function modificar_informacion($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'&error=informacion'));
		}
		else
		{
			$datos = $this->Contenidos_model->modificarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id_contenido')));
		}
	}

	public function modificar_box($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'&error=box'));
		}
		else
		{
			$datos = $this->Contenidos_model->modificarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id_contenido')));
		}
	}

	public function ingresar_cuenta($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'?error=cuenta'));
		}
		else
		{
			$datos = $this->Contenidos_model->ingresarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id')));
		}
	}

	public function modificar_cuenta($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'&error=cuenta'));
		}
		else
		{
			$datos = $this->Contenidos_model->modificarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id_contenido')));
		}
	}

	public function eliminar_contenido_adicional($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Contenidos_model->eliminarContenidoAdicional($this->input->post()))
	        {
				redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/contenidos/detalle', $datos);
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function upload()
	{
		if (!empty($_FILES)) 
		{
			$x = date('YmdHis');
		
			//CARGO ORIGINAL
			$image_path = './multimedia/511/7358/';
		    $config['upload_path'] = $image_path;
		    $config['file_name'] = $x.'-'.$_FILES['file']['name'];
		    $config['allowed_types'] = 'gif|jpg|png|jpeg';
		    $config['max_size']= 1000;
		    $config['max_width']= 2024;
		    $config['max_height']= 1768;
		    $config['remove_spaces'] = TRUE;

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload("file")) {
				echo "Error al cargar archivo(s)";
				
			}
			else
			{
				$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
				
				$data['imagen'] = $upload_data['file_name'];
				$data['imagen'] = $upload_data['file_name'];
		        $data['id_tipo_contenido'] = 3;
		        $data['estado'] = 3;
				$data['fecha_alta'] = unix_to_human(time(), TRUE, 'eu');
				$data['user_alta'] = $this->usuario->id;

				$insert = $this->db->insert('con_media', $data);

				//Traigo el id ingresado
				$insert_id = $this->db->insert_id();

				$relacionar['id_contenido'] = $this->input->post('id_contenido');
				$relacionar['id_media'] = $this->db->insert_id();
					
				$insertdos = $this->db->insert('con_rel_contenidos_media', $relacionar);
		    }
		}	
		else
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$id));
		}
	}

	public function ingresarimagen($id = NULL)
	{
		$this->load->helper('form');

		if (empty($_FILES['imagen_slide']['name'])) 
		{
			redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id').'?error=porfolio'));
		}
		else
		{
			$imagen = $this->Contenidos_model->subirSlideshow($id,$_FILES['imagen_slide']['name']); 
            redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id')));
		}
	}

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			
			if ($datos = $this->Contenidos_model->duplicarItem($id))
	        {
				redirect(base_url('cms-v2/contenidos/'));
	        }
		}
		else
		{
			redirect(base_url('/cms-v2/user/login/'));
		}
	}

	public function ordenar_contenidos()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Contenidos_model->listadoItems($this->uri->segment(4));
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/contenidos/ordenar', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarContenidos()
	{
		$data = $this->Contenidos_model->ordenarContenidos(json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function ordenar($id)
	{
		if ($this->is_logged_in())
		{
			$datos['item'] = $this->Contenidos_model->detalleItem($id);
			$datos['listado'] = $this->Contenidos_model->listadoMedia($id,$this->input->get('id_tipo'));
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/contenidos/ordenar_media', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarMedia($tipo)
	{
		$data = $this->Contenidos_model->ordenarMedia($tipo, json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function relacionar($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'required', array('required' => 'Debe ingresar un ID.'));

		if ($this->form_validation->run() === false)
		{
			$datos['listado'] = $this->Contenidos_model->listadoContenidosRelacionar();
			$datos['relacionados'] = $this->Contenidos_model->listadoContenidosRelacionados($this->uri->segment(4));
			$datos['item'] = $this->Contenidos_model->detalleItem($id);

			$this->load->view('header');
			$this->load->view('cms-v2/contenidos/relacionar', $datos);
			$this->load->view('footer');
		}

		else
		{
			$datos = $this->Contenidos_model->relacionarContenido($this->input->post());
            redirect(base_url('cms-v2/contenidos/relacionar/'.$id));
		}
	}

	public function eliminarmedia($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Contenidos_model->eliminarMedia($this->input->post()))
	        {
				redirect(base_url('cms-v2/contenidos/ingresar/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/contenidos/ingresar/'.$id);
				$this->load->view('footer');
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('/cms-v2/user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Contenidos_model->eliminarContenido($this->input->post()))
	        {
				redirect(base_url('cms-v2/contenidos/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/contenidos/detalle', $datos);
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
