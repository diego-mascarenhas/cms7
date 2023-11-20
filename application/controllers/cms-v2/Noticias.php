<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noticias extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//cargo el modelo de secciones
		$this->load->model('cms-v2/Noticias_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Noticias_model->listadoContenidos(0);
	
			$this->load->view('header');
			$this->load->view('cms-v2/blog/index', $datos);
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
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				$datos['secciones'] = $this->Noticias_model->listadoPadres();
				
				if(isset($id))
				{
					$datos['contenido'] = $this->Noticias_model->detalleContenido($id);
					$datos['item'] = $this->Noticias_model->detalleContenidoIdioma($id,'es');
					$datos['iconos'] = array('estrella'=>'estrella', 'flecha'=>'flecha', 'barras'=>'barras', 'clip'=>'clip', 'reloj'=>'reloj', 'gorro'=>'gorro');
					$datos['fotos'] = $this->Noticias_model->listadoMedia($id);	
				}
				else
				{
					$datos['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/blog/ingresar', $datos);
				//$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Noticias_model->ingresarContenido($this->input->post()))
		        {
			        if(!empty($_FILES['image']['name']))
			        {
						$original = $this->Noticias_model->subirOriginal($id,'image'); 
					}
		            redirect(base_url('cms-v2/noticias/index'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/blog/detalle', $datos);
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

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Noticias_model->duplicarItem($id))
	        {
				redirect(base_url('cms-v2/noticias/'));
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
			redirect(base_url('cms-v2/noticias/ingresar/'.$id));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Noticias_model->eliminarContenido($this->input->post()))
	        {
				redirect(base_url('cms-v2/noticias/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/blog/detalle', $datos);
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	/* Categorias */
	public function categorias()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Noticias_model->listadoCategorias(0);
	
			$this->load->view('header');
			$this->load->view('cms-v2/blog/categorias', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function categorias_ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('seccion', 'TITULO', 'required', array('required' => 'Debe ingresar un TITULO.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				
				if(isset($id))
				{
					$datos['item'] = $this->Noticias_model->detalleCategoria($id);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('header');
				$this->load->view('cms-v2/blog/categorias_ingresar', $datos);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Noticias_model->ingresarCategoria($this->input->post()))
		        {
		            redirect(base_url('cms-v2/noticias/categorias'));
		        }
		        else
		        {
					$this->load->view('header');
					$this->load->view('cms-v2/blog/detalle', $datos);
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

	public function categorias_duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Noticias_model->duplicarCategoria($id))
	        {
				redirect(base_url('cms-v2/noticias/categorias'));
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function categorias_eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Noticias_model->eliminarCategoria($this->input->post()))
	        {
				redirect(base_url('cms-v2/noticias/categorias'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/blog/detalle', $datos);
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	/* Fin Categorias */

	public function ordenar($id)
	{
		if ($this->is_logged_in())
		{
			$datos['item'] = $this->Noticias_model->detalleContenidoIdioma($id,'es');
			$datos['listado'] = $this->Noticias_model->listadoMedia($id);	
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/blog/ordenar', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarMedia($tipo)
	{
		$data = $this->Noticias_model->ordenarMedia($tipo, json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function ordenar_noticias()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Noticias_model->listadoContenidos(0);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/blog/ordenar_noticias', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarNoticias()
	{
		$data = $this->Noticias_model->ordenarNoticias(json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function ordenar_categorias()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Noticias_model->listadoCategorias(0);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('cms-v2/blog/ordenar_categorias', $datos);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarCategorias()
	{
		$data = $this->Noticias_model->ordenarCategorias(json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function eliminarmedia($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Noticias_model->eliminarMedia($this->input->post()))
	        {
				redirect(base_url('cms-v2/noticias/ingresar/'.$this->input->post('id_contenido')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/blog/ingresar/'.$id);
				$this->load->view('footer');
		        echo 'Error';
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

}
