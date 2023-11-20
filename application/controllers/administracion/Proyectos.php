<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Proyectos extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('proyecto_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');

			$data['proyectos'] = $this->proyecto_model->getProyectos($parametros);
			
			$config['total_rows'] = $this->proyecto_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/proyectos/index', $data) : $this->load->view('/administracion/proyectos/empty', $data);
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
			$this->load->model('proyecto_model');
			
			// helpers and libraries
			$this->load->helper('text');

			$data['detalle'] = $this->proyecto_model->getProyectoDetalle($id);
			
			if ($this->is_logged_in('reseller'))
			{
				$this->load->model('nota_model');
				$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>113, 'id_referencia'=>$id));
			}
			
			if (isset($data['detalle']))
			{
				// models
				$this->load->model('tarea_model');
				

				$parametros['id_proyecto'] = $id;
	
				$data['tareas'] = $this->tarea_model->getTareas($parametros);
				
				
				$this->load->view('/header');
				$this->load->view('/administracion/proyectos/detalle', $data);
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
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('proyecto_model');
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|integer');
			$this->form_validation->set_rules('desde', 'Desde', 'trim|alpha_dash');
			$this->form_validation->set_rules('hasta', 'Hasta', 'trim|alpha_dash');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_empresa'] = $this->input->get('id_empresa');
				$default['desde'] = null;
				$default['hasta'] = null;
				$default['valor'] = null;
				$default['descuento'] = null;
				$default['estado'] = 1;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(40, (isset($data['detalle']['id_categoria'])) ? $data['detalle']['id_categoria'] : null);
				$data['empresas'] = $this->empresa_model->comboEmpresas();
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css'),
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/plugins/summernote/summernote-bs3.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/proyectos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->proyecto_model->ingresarProyecto($this->input->post()))
				{
					redirect(base_url('administracion/proyectos/detalle/' . $data['id']));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/proyectos/error/');
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
			// models
			$this->load->model('proyecto_model');
			$this->load->model('empresa_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
			$this->form_validation->set_rules('titulo', 'Título', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('id_categoria', 'Categoría', 'trim|required|integer');
			$this->form_validation->set_rules('desde', 'Desde', 'trim|alpha_dash');
			$this->form_validation->set_rules('hasta', 'Hasta', 'trim|alpha_dash');
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->proyecto_model->getProyectoDetalleRaw($id);
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(40, (isset($data['detalle']['id_categoria'])) ? $data['detalle']['id_categoria'] : null);
				$data['empresas'] = $this->empresa_model->comboEmpresas();
				
				// validation not ok, send validation errors to the view
				$header['css'] = array(	base_url('assets/css/plugins/datapicker/datepicker3.css'),
									base_url('assets/css/plugins/clockpicker/clockpicker.css'),
									base_url('assets/css/plugins/summernote/summernote.css'),
									base_url('assets/css/plugins/summernote/summernote-bs3.css')
								);
			
				$this->load->view('/header', $header);
				$this->load->view('/administracion/proyectos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->proyecto_model->modificarProyecto($id, $this->input->post()))
				{
					redirect(base_url('administracion/proyectos/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/proyectos/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function enviar_presupuestos()
	{
		// models
		$this->load->model('proyecto_model');
		$this->load->model('comunicacion_model');
		
		$proyectos = $this->proyecto_model->comunicarProyectosAutorizados();
		
		if (isset($proyectos))
		{
			foreach ($proyectos as $obj)
			{
				$this->db->trans_begin();
		
				$proyecto = $this->proyecto_model->comunicarProyecto($obj['id']);
				$data[] = $this->comunicacion_model->ingresarComunicacion($proyecto['id_contacto'], 15, $obj['id'], $proyecto);
		
				if ($this->db->trans_status() === false)
				{
					$this->db->trans_rollback();
					
					$data['error'] = 'Ha habido un problema y no se pudo ingresar la comunicación, por favor intenta más tarde';
				}
				else
				{
					$this->db->trans_commit();
				}
			}
		}
		else
		{
			$data['error'] = 'No hay proyectos autorizados para comunicar';
		}
		
		if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
}
