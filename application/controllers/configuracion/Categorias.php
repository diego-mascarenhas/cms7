<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Categorias extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('categorias_generales_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');

			
			$data['categorias'] = $this->categorias_generales_model->getCategoriasGenerales($parametros);
						
			$config['total_rows'] = $this->categorias_generales_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/configuracion/categorias/index', $data) : $this->load->view('/configuracion/categorias/empty');
			$this->load->view('/footer');
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function detalle($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('categorias_generales_model');
			
			$data['detalle'] = $this->categorias_generales_model->getCategoriaGeneralDetalle($id);
			
			$this->load->view('/header');
			$this->load->view('/configuracion/categorias/detalle', $data);
			$this->load->view('/footer');
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
			$this->load->model('categorias_generales_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('categoria', 'Categoría', 'required');
			$this->form_validation->set_rules('id_tipo', 'Tipo', 'trim|integer');
			
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('caracteristicas', 'Características', 'trim|min_length[10]');
			
			$this->form_validation->set_rules('id_moneda', 'Moneda', 'trim|integer');
			$this->form_validation->set_rules('convertir', 'Convertir a pesos', 'trim|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			$this->form_validation->set_rules('frecuencia', 'Frecuencia', 'trim');
			
			$this->form_validation->set_rules('padre', 'Categoría padre', 'trim|required|integer');
			$this->form_validation->set_rules('orden', 'Orden', 'trim|integer');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
						
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(null, (isset($data['detalle']['id_padre'])) ? $data['detalle']['id_padre'] : null, 0);
				$data['frecuencias'] = $this->combos->frecuenciasCombo();
				$data['monedas'] = $this->sys_model->comboMonedas();
				$data['tipos'] = $this->combos->categoriasGeneralesTipo();
				
				
				// validation not ok, send validation errors to the view
				$this->load->view('header');
				$this->load->view('configuracion/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->categorias_generales_model->ingresarCategoriaGeneral($this->input->post()))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'configuracion/categorias/detalle/' . $data['id']));
				}
				else
				{
					// error
					$data['codigo'] = 500;
					$data['error'] = 'Error de aplicación';
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					
					$this->load->view('error', $data);
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
			$this->load->model('categorias_generales_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('categoria', 'Categoría', 'required');
			$this->form_validation->set_rules('id_tipo', 'Tipo', 'trim|integer');
			
			$this->form_validation->set_rules('descripcion', 'Descripción', 'trim|min_length[10]');
			$this->form_validation->set_rules('caracteristicas', 'Características', 'trim');
			
			$this->form_validation->set_rules('id_moneda', 'Moneda', 'trim|integer');
			$this->form_validation->set_rules('convertir', 'Convertir a pesos', 'trim|integer');
			$this->form_validation->set_rules('valor', 'Valor', 'trim|numeric');
			$this->form_validation->set_rules('descuento', 'Descuento', 'trim|numeric');
			$this->form_validation->set_rules('frecuencia', 'Frecuencia', 'trim');
			
			$this->form_validation->set_rules('padre', 'Categoría padre', 'trim|required|integer');
			$this->form_validation->set_rules('orden', 'Orden', 'trim|integer');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$post = $this->input->post();
				
				$data['detalle'] = ($this->input->post()) ? $post : $this->categorias_generales_model->getCategoriaGeneralDetalleRaw($id);
				
				$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(null, (isset($data['detalle']['padre'])) ? $data['detalle']['padre'] : null, 0);
				$data['frecuencias'] = $this->combos->frecuenciasCombo();
				$data['monedas'] = $this->sys_model->comboMonedas();
				$data['tipos'] = $this->combos->categoriasGeneralesTipo();
				
				// validation not ok, send validation errors to the view
				$this->load->view('header');
				$this->load->view('configuracion/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($this->categorias_generales_model->modificarCategoriaGeneral($id, $this->input->post()))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'configuracion/categorias/detalle/' . $id));
				}
				else
				{
					// error
					$data['codigo'] = 500;
					$data['error'] = 'Error de aplicación';
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					
					$this->load->view('error', $data);
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}