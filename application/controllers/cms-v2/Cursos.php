<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//cargo el modelo de secciones
		$this->load->model('cms-v2/Cursos_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Cursos_model->listadoContenidos(0);
	
			$this->load->view('header');
			$this->load->view('cms-v2/cursos/index', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('destacado', 'DESTACADO', 'required', array('required' => 'Debe ingresar si es DESTACADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				$datos['destacados'] = array(0=>'No', 1=>'Si');
				$datos['estrellas'] = array(3=>'3', 4=>'4', 5=>'5');
							
				if(isset($id))
				{
					$datos['contenido'] = $this->Cursos_model->detalleContenidoCms($id);
					$datos['item'] = $this->Cursos_model->detalleContenidoIdioma($id,'es');
					$datos['informaciones'] = $this->Cursos_model->listadoContenidosAdicionales($id, 1, 'es',0);
					$datos['cuentas'] = $this->Cursos_model->listadoContenidosAdicionales($id, 2, 'es',0);
					$datos['fotos'] = $this->Cursos_model->listadoMedia($id);	
					$datos['iconos'] = array('estrella'=>'estrella', 'flecha'=>'flecha', 'barras'=>'barras', 'clip'=>'clip', 'reloj'=>'reloj', 'gorro'=>'gorro');
				}
				else
				{
					$datos['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				//multimedia
				$this->load->model('cms_model');
				$this->load->model('multimedia_model');

				$datos['media_proyectos'] = $this->multimedia_model->comboProyectos();
				//fin multimedia

				$this->load->view('header');
				$this->load->view('cms-v2/cursos/ingresar', $datos);
				//$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Cursos_model->ingresarContenido($this->input->post()))
		        {
			        if(!empty($_FILES['image']['name']))
			        {
						$original = $this->Cursos_model->subirOriginal($id,'image'); 
					}
			        if(!empty($_FILES['miniatura']['name']))
			        {
						$mapa = $this->Cursos_model->subirMiniatura($id,'miniatura');
					}
			        if(!empty($_FILES['imagen_adicional']['name']))
			        {
						$slide = $this->Cursos_model->subirImagenAdicional($id,'imagen_adicional');
					}
				    
				    //ARCHIVOS PDF
				    if(!empty($_FILES['archivo1']['name']))
			        {
						$pdf = $this->Cursos_model->subirPDF($id,'archivo1'); 
					}
			        if(!empty($_FILES['archivo2']['name']))
			        {
						$yapa = $this->Cursos_model->subirYapa($id,'archivo2'); 
					}
			        if(!empty($_FILES['archivo3']['name']))
			        {
						$yapa = $this->Cursos_model->subirIngredientes($id,'archivo3'); 
					}

		            redirect(base_url('cms-v2/cursos/index'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/cursos/detalle', $datos);
					//$this->load->view('footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar_informacion($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id').'?error=informacion'));
		}
		else
		{
			$datos = $this->Cursos_model->ingresarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id')));
		}
	}

	public function modificar_informacion($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id').'&error=informacion'));
		}
		else
		{
			$datos = $this->Cursos_model->modificarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id_contenido')));
		}
	}

	public function ingresar_cuenta($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id').'?error=cuenta'));
		}
		else
		{
			$datos = $this->Cursos_model->ingresarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id')));
		}
	}

	public function modificar_cuenta($id = NULL)
	{
		$this->load->helper('form');

		if (empty($this->input->post('titulo'))) 
		{
			redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id').'&error=cuenta'));
		}
		else
		{
			$datos = $this->Cursos_model->modificarContenidoAdicional($this->input->post());
            redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id_contenido')));
		}
	}

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->duplicarItem($id))
	        {
				redirect(base_url('cms-v2/cursos/'));
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
		        $data['titulo'] = $upload_data['file_name'];
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
			redirect(base_url('cms-v2/cursos/ingresar/'.$id));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->eliminarContenido($this->input->post()))
	        {
				redirect(base_url('cms-v2/cursos/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cursos/detalle', $datos);
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar_contenido_adicional($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->eliminarContenidoAdicional($this->input->post()))
	        {
				redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cursos/detalle', $datos);
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}


	public function listado_preguntas($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Cursos_model->listadoContenidosAdicionales($id, 5, 'es', 0);
	
			$this->load->view('header');
			$this->load->view('cms-v2/cursos/listado_preguntas', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar_pregunta($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('orden', 'ORDEN', 'required', array('required' => 'Debe ingresar un ORDEN.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
								
				if(isset($id))
				{
					$datos['pregunta'] = $this->Cursos_model->detallePregunta($id, 5, 'es');
					$datos['item'] = $this->Cursos_model->detalleContenido($datos['pregunta']['id_con_contenido']);					
				}
				else
				{
					if($this->input->post('id_contenido'))
					{
						$datos['item'] = $this->Cursos_model->detalleContenido($this->input->post('id_contenido'));					
					}
					else
					{
						$datos['item'] = $this->Cursos_model->detalleContenido($this->input->get('curso'));					
					}
					$datos['pregunta'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/cursos/ingresar_pregunta', $datos);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Cursos_model->ingresarPregunta($this->input->post()))
		        {
		            redirect(base_url('cms-v2/cursos/listado_preguntas/'.$this->input->post('id_contenido')));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/cursos/detalle', $datos);
					$this->load->view('footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function duplicar_pregunta()
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->duplicarPregunta($this->input->post('id')))
	        {
				redirect(base_url('cms-v2/cursos/listado_preguntas/'.$this->input->post('id_contenido')));
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
			if ($datos = $this->Cursos_model->eliminarContenidoAdicional($this->input->post()))
	        {
				redirect(base_url('cms-v2/cursos/listado_preguntas/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cursos/detalle', $datos);
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
/*
	public function certificar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('titulo', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			$this->form_validation->set_rules('orden', 'ORDEN', 'required', array('required' => 'Debe ingresar un ORDEN.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				$datos['destacados'] = array(0=>'No', 1=>'Si');
				
				if(isset($id))
				{
					$datos['item'] = $this->Cursos_model->detalleContenido($this->input->get('curso'));
					$datos['pregunta'] = $this->Cursos_model->detallePregunta($id, 5, 'es');
				}
				else
				{
					//$datos['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/cursos/ingresar_pregunta', $datos);
				//$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Cursos_model->ingresarPregunta($this->input->post()))
		        {
		            redirect(base_url('cms-v2/cursos/index'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/cursos/detalle', $datos);
					//$this->load->view('footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
*/


/*
	public function relacionargaleria($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'required', array('required' => 'Debe ingresar un ID.'));

		if ($this->form_validation->run() === false)
		{
			$datos['listado'] = $this->Cursos_model->listadoGaleriaRelacionar();
			$datos['relacionados'] = $this->Cursos_model->listadoRelacionados($this->uri->segment(4));
			$datos['item'] = $this->Cursos_model->detalleContenido($id);

			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/cursos/relacionargaleria', $datos);
			$this->load->view('footer');
		}

		else
		{
			$datos = $this->Cursos_model->relacionarGaleria($this->input->post());
            redirect(base_url('cms-v2/cursos/ingresar/'.$id));
		}
	}
*/

	public function relacionar($id)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'ID', 'required', array('required' => 'Debe ingresar un ID.'));

		if ($this->form_validation->run() === false)
		{
			$datos['listado'] = $this->Cursos_model->listadoContenidosRelacionar();
			$datos['relacionados'] = $this->Cursos_model->listadoContenidosRelacionados($this->uri->segment(4));
			$datos['item'] = $this->Cursos_model->detalleContenido($id);

			$this->load->view('header');
			$this->load->view('cms-v2/cursos/relacionar', $datos);
		}

		else
		{
			$datos = $this->Cursos_model->relacionarContenido($this->input->post());
            redirect(base_url('cms-v2/cursos/relacionar/'.$id));
		}
	}
	
	public function ordenar_cursos()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Cursos_model->listadoContenidos(0);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/cursos/ordenar_cursos', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarCurso()
	{
		$data = $this->Cursos_model->ordenarCurso(json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function ordenar($id)
	{
		if ($this->is_logged_in())
		{
			$datos['item'] = $this->Cursos_model->detalleContenidoIdioma($id,'es');
			$datos['listado'] = $this->Cursos_model->listadoMedia($id);	
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/cursos/ordenar', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarMedia($tipo)
	{
		$data = $this->Cursos_model->ordenarMedia($tipo, json_decode($_POST['items']));
		echo json_encode($data);
	}


	public function eliminarmedia($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->eliminarMedia($this->input->post()))
	        {
				redirect(base_url('cms-v2/cursos/ingresar/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cursos/ingresar/'.$id);
				$this->load->view('footer');
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

/*

	public function desasociarmedia($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Cursos_model->desasociarMedia($this->input->post()))
	        {
				redirect(base_url('cms-v2/cursos/ingresar/'.$_POST['id_contenido']));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/cursos/ingresar/'.$id);
				$this->load->view('footer');
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
*/

}
