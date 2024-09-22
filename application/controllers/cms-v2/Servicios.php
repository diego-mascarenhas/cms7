<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Servicios extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/servicios/Servicios_model');
		$this->load->model('cms-v2/servicios/Categorias_model');
		$this->load->model('cms-v2/Configuracion_model');
	}

	//Listar 
	public function index($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				if($this->input->get('tipo')) { $parametros['tipo'] = $this->input->get('tipo'); }
				$parametros['orden'] = 0;
				if($this->input->get('tipo')) { $data['tipo'] = $parametros['tipo']; } else { $data['tipo'] = 'todos';}
				$parametros['id_tipo'] = 32; 
				$data['listado'] = $this->Servicios_model->getServicios($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/index', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
	
	//Ingresar 
	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				
				$this->form_validation->set_rules('estado', 'Etado', 'required');
				$this->form_validation->set_rules('titulo', 'Título', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();

					$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
					$parametros['combo'] = 1;
					$data['idiomas'] = $this->Servicios_model->getIdiomas();
					$data['categorias'] = $this->Categorias_model->comboCategorias($parametros);
				
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Servicios_model->getIdiomas();
					
					if ($data = $this->Servicios_model->ingresarServicio($this->input->post()))
					{			        
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['imagen1_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen1_'.$extension]['name'], 'imagen1_'.$extension, 32, $extension, $this->input->post('medidas1')); 

								if ($this->input->post('destacado_slide') == 1)
								{
									$modificar = $this->Servicios_model->ingresarMedia($data['id'], $original['id'], $extension, 18);
								} 
							}
	
					        if(!empty($_FILES['imagen2_'.$extension]['name']))
					        {
								$original = $this->upload($data['id'], $_FILES['imagen2_'.$extension]['name'], 'imagen2_'.$extension, 35, $extension, $this->input->post('medidas2')); 
							}

					        if(!empty($_FILES['archivo_'.$extension]['name']))
					        {
								$id_imagen_tipo = 36;
								$original = $this->upload($data['id'], $_FILES['archivo_'.$extension]['name'], 'archivo_'.$extension, $id_imagen_tipo, $extension, null);
							}
				        }
	
				        $this->session->set_flashdata('mensaje', 'El ítem fue ingresado correctamente.');
			            redirect(base_url('cms-v2/servicios/modificar/'.$data['id']));
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
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	//Modificar 
	public function modificar($id, $tipo = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				
				$this->form_validation->set_rules('estado', 'Etado', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();
/* 					$data['media_proyectos'] = $this->Servicios_model->comboProyectos(); */
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->Servicios_model->getDetalleServicio($id);
					$data['idiomas'] = $this->Servicios_model->getIdiomas();
					$parametros['combo'] = 1;
					$data['categorias'] = $this->Categorias_model->comboCategorias($parametros);

					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Servicios_model->getIdiomas();
	
					if ($data = $this->Servicios_model->modificarServicio($id, $this->input->post()))
					{					
				        foreach($idiomas as $idioma)
				        {
							$extension = $idioma['extension'];
					        if(!empty($_FILES['archivo_'.$extension]['name']))
					        {
								$id_imagen_tipo = 36;
								$original = $this->upload($id, $_FILES['archivo_'.$extension]['name'], 'archivo_'.$extension, $id_imagen_tipo, $extension, null);
							}

					        if(!empty($_FILES['imagen1_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen1_'.$extension]['name'], 'imagen1_'.$extension, 32, $extension, $this->input->post('medidas1')); 
								//ASOCIO IMAGEN SLIDE
								if ($this->input->post('destacado_slide') == 1)
								{
									$modificar = $this->Servicios_model->ingresarMedia($id, $original['id'], $extension, 18);
								} 
							}
	
					        if(!empty($_FILES['imagen2_'.$extension]['name']))
					        {
								$original = $this->upload($id, $_FILES['imagen2_'.$extension]['name'], 'imagen2_'.$extension, 35, $extension, $this->input->post('medidas2')); 
							}
				        }
	
				        $this->session->set_flashdata('mensaje', 'El ítem fue modificado correctamente.');
			            redirect(base_url('cms-v2/servicios/modificar/'.$id.'/'));
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
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	public function duplicar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($data = $this->Servicios_model->duplicarServicio($id))
		        {
					redirect(base_url('cms-v2/servicios'));
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function ordenar($id, $parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				$parametros['id_categoria'] = $id; 
				$parametros['estado'] = 3;
				$parametros['order_by'] = 'con_servicios.orden';
				$parametros['order'] = 'ASC';
				
				$data['item'] = $this->Categorias_model->detalleCategoria($id);
				$data['listado'] = $this->Servicios_model->getServicios($parametros);
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/ordenar', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//Ingresar Información
	public function ingresar_informacion()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				if (empty($this->input->post('titulo'))) 
				{
					redirect(base_url('cms-v2/servicios/modificar/'.$this->input->post('id').'?error=informacion'));
				}
				else
				{
					$data = $this->Servicios_model->ingresarContenidoAdicional($this->input->post());
			        $extension = $this->input->post('idioma');
			        if(!empty($_FILES['imagen']['name']))
			        {
						if(!empty($this->input->post('id_imagen_tipo'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo'); } else { $id_imagen_tipo = 12;}
						$original = $this->upload($data['id'], $_FILES['imagen']['name'], 'imagen', $id_imagen_tipo, $extension, $this->input->post('medidas'));
						$modificar = $this->Servicios_model->ingresarArchivo($data['id'], $original['id'], $this->input->post('medidas'), $this->input->post('id_imagen_tipo'));
					}
			        if(!empty($_FILES['imagen2']['name']))
			        {
						if(!empty($this->input->post('id_imagen_tipo2'))) { $id_imagen_tipo = $this->input->post('id_imagen_tipo2'); } else { $id_imagen_tipo = 12;}
						$original = $this->upload($data['id'], $_FILES['imagen2']['name'], 'imagen2', $id_imagen_tipo, $extension, $this->input->post('medidas2'));
					}
			        if(!empty($_FILES['archivo']))
			        {
						$id_imagen_tipo = null;
						$original = $this->upload($data['id'], $_FILES['archivo']['name'], 'archivo', $id_imagen_tipo, $id_imagen_tipo, $extension);
					}
		            redirect(base_url('cms-v2/servicios/modificar/'.$this->input->post('id_contenido')));
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	//Modificar Información
	public function modificar_informacion($servicio, $id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->helper('text');
				$this->config->set_item('language', $this->usuario->idioma);

				if (!$this->input->post())
				{
					$this->load->model('cms_model');
					$this->load->model('multimedia_model');
					$data['media_proyectos'] = $this->multimedia_model->comboProyectos();
					$data['detalle'] = $this->Servicios_model->getDetalleServicio($servicio);
					$data['item'] = $this->Servicios_model->getDetalleServicioAdicional($id);
					
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/modificar', $data);
					$this->load->view('/footer');

				}
				else
				{
					if ($data = $this->Servicios_model->modificarContenidoAdicional($id, $this->input->post()))
					{			        
				        $this->session->set_flashdata('mensaje', 'El contenido fue modificado correctamente.');
			            redirect(base_url('cms-v2/servicios/modificar/'.$servicio));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'Se produjo un error al modificar el contenido.');
			            redirect(base_url('cms-v2/servicios/modificar/'.$servicio));
					}
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
	
	public function ordenarInformacion()
	{
		$data = $this->Servicios_model->ordenarItems(json_decode($_POST['items']), 'con_servicios');
		echo json_encode($data);
	}

	public function ordenar_items($id, $id_tipo, $idioma)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				$parametros['idioma'] = $idioma;
				$parametros['id_tipo'] = $id_tipo;
				$parametros['id'] = $id;
				$data['categoria'] = $this->Categorias_model->detalleCategoria($id_tipo);
				$data['item'] = $this->Servicios_model->getDetalleServicio($id);
				$data['listado']= $this->Servicios_model->getServicioAdicionalIdioma($parametros);
								
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/ordenar_items', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenaritemsInformacion()
	{
		$data = $this->Servicios_model->ordenarItemsInformacion(json_decode($_POST['items']));
		echo json_encode($data);
	}

	public function eliminar_informacion($id = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($data = $this->Servicios_model->eliminarInformacion($this->input->post()))
		        {
		            redirect(base_url('cms-v2/servicios/modificar/'.$this->input->post('id_contenido')));
		        }
		        else
		        {
					$this->load->view('header');
			        $this->load->view('cms-v2/paginas/detalle', $data);
			        echo 'Error';
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function relacionar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->load->helper('form');
				$this->load->helper('text');
				$this->config->set_item('language', $this->usuario->idioma);

				if (!$this->input->post())
				{
					$parametros['estado'] = 3;
					$data['idiomas'] = $this->Servicios_model->getIdiomas();
					$data['detalle'] = $this->Servicios_model->getDetalleServicio($id);
					$data['listado'] = $this->Servicios_model->getServicios($parametros);
					
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/relacionar', $data);
					$this->load->view('/footer');

				}
				else
				{
					if ($data = $this->Servicios_model->relacionarServicio($this->input->post()))
					{			        
			            redirect(base_url('cms-v2/servicios/relacionar/'.$id));
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
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
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
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Servicios_model->eliminarServicio($this->input->post(), 'con_servicios'))
		        {
					redirect(base_url('cms-v2/servicios'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios', $data);
					$this->load->view('/footer');
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}				

	//Listar Categorias 
	public function categorias($parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$parametros['order_by'] = 'con_categorias.fecha_alta';
				$parametros['order'] = 'DESC';
				$data['listado'] = $this->Categorias_model->getCategorias($parametros);
			
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/categorias/index', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	//Ingresar Categoria
	public function ingresar_categoria()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('categoria', 'Categoría', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = ($this->input->post()) ? $this->input->post() : null;
					$data['idiomas'] = $this->Servicios_model->getIdiomas();
				
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/categorias/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Servicios_model->getIdiomas();
					
					if ($data = $this->Categorias_model->ingresarCategoria($this->input->post()))
					{			        
				        $this->session->set_flashdata('mensaje', 'ok');
			            redirect(base_url('cms-v2/servicios/modificar-categoria/'.$data['id']));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
			            redirect(base_url('cms-v2/servicios/ingresar-categoria/'));
					}
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	//Ingresar Categoria
	public function modificar_categoria($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
	
				$this->load->helper('form');
				$this->load->library('form_validation');
				$this->load->helper('text');
				
				$this->form_validation->set_rules('estado', 'Estado', 'required');
				$this->form_validation->set_rules('categoria', 'Categoría', 'required');
				
				if ($this->form_validation->run() === false)
				{
					$data['detalle'] = $this->Categorias_model->detalleCategoria($id);
					$data['idiomas'] = $this->Servicios_model->getIdiomas();
				
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/categorias/form', $data);
					$this->load->view('footer');
				}
				else
				{
					$idiomas = $this->Servicios_model->getIdiomas();
					
					if ($data = $this->Categorias_model->modificarCategoria($this->input->post()))
					{			        
				        $this->session->set_flashdata('mensaje', 'ok');
			            redirect(base_url('cms-v2/servicios/modificar-categoria/'.$id));
					}
					else
					{
				        $this->session->set_flashdata('mensaje', 'error');
			            redirect(base_url('cms-v2/servicios/modificar-categoria/'.$id));
					}
				}
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	//Ordenar Categoria
	public function ordenar_categoria($id = null, $parametros = null)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$this->config->set_item('language', $this->usuario->idioma);
				
				$parametros['estado'] = 2;
				$parametros['order_by'] = 'con_servicios_categorias.orden';
				$parametros['order'] = 'ASC';
				$parametros['padre'] = $id;
				$data['listado'] = $this->Categorias_model->getCategorias($parametros);
				
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios/categorias/ordenar', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function ordenarInformacionCategoria()
	{
		$data = $this->Servicios_model->ordenarItems(json_decode($_POST['items']), 'con_servicios_categorias');
		echo json_encode($data);
	}

	public function eliminar_categoria($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				if ($datos = $this->Categorias_model->eliminarCategoria($this->input->post(), 'con_servicios_categorias'))
		        {
					redirect(base_url('cms-v2/servicios/categorias/'));
		        }
		        else
		        {
			        $data = 'Error';
					$this->load->view('/header', array('buscador'=>true));
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/servicios', $data);
					$this->load->view('/footer');
		        }
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}				
	//Ingresar imagen 
	public function upload($id, $imagen, $nombre, $tipo, $extension = null, $medidas = null)
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
			if ($data1 = $this->multimedia_model->ingresarMedia($data))
	        {		        
		        if ($this->multimedia_model->getMediaTipo($upload_data['file_ext']) == 'imagen')
		        {
			        //Asocio
			        if ($id) $this->Servicios_model->asociarMedia($data1['id'], $id, $extension, $tipo);

			        //Sistema
			        $thumb = $this->thumbFromImagen($upload_data['full_path'], null, '256x144');
					$this->multimedia_model->ingresarThumb(1, $data1['id'], $thumb);

			        //Imagen
					$thumb = $this->thumbFromImagen($upload_data['full_path'], null, $medidas);
					$this->multimedia_model->ingresarThumb($tipo, $data1['id'], $thumb);
		        }
		        if($data['id_tipo'] == 9)
		        {
			        if ($id) $this->Servicios_model->asociarMedia($data1['id'], $id, $extension, 9);
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