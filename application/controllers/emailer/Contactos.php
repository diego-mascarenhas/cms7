<?php defined('BASEPATH') or exit('No direct script access allowed');


class Contactos extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			//models
			$this->load->model('emailer_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('combos');
			
			$data['categorias_generales'] = $this->combos->categoriasGeneralesCombo(10, (isset($data['detalle']['id_categoria'])) ? $data['detalle']['id_categoria'] : null);
			
	
			$parametros = null;
			

			//$data['detalle'] = $this->emailer_model->getNewslettersListaDetalle($id);
			//$data['filtros'] = $this->emailer_model->getNewslettersListaFiltros($id);
			
			
			//$parametros['id_empresa'] = 1; // revision alpha

			$parametros['contactos_estados'][] = 2; // Activo
			$parametros['contactos_estados'][] = 3; // Online
			
			//$parametros['contactos_categorias'][] = 3; // Staff
			//$parametros['contactos_categorias'][] = 4; // Tester
			
			//$parametros['servicios_categorias'][] = 200; // Hosting Standard
			//$parametros['servicios_categorias'][] = 201; // Hosting Enterprise
			//$parametros['servicios_categorias'][] = 202; // Hosting Premium
			$parametros['servicios_categorias'][] = 204; // Cloud
			
			$parametros['servicios_estados'][] = 4; // Activo
			
			$data['filtros'] = json_encode($parametros);
			$data['parametros'] = json_decode($data['filtros'], true);
			
			$data['contactos'] = $this->emailer_model->listaDinamica($parametros);
			
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/plugins/dataTables/datatables.min.css')
								);
								
			$this->load->view('/header', $header);
			$this->load->view('/emailer/contactos/index', $data);
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
			$this->load->model('emailer_model');
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->helper('text');
			

			$data['detalle'] = $this->contacto_model->getContactoDetalle($id);
			$data['categorias'] = $this->emailer_model->getContactoCategorias($id);
			
			echo '<pre>' . print_r($data, true) . '</pre>';
			
			$this->load->view('/header');
			$this->load->view('/emailer/contactos/detalle', $data);
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
			$this->load->model('contacto_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('celular', 'Celular', 'trim');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['area_privada'] = 4;
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['accion'] = 'ingresar';
				
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/emailer/contactos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				$post = $this->input->post();
				$post['id_empresa'] = $this->usuario->id_empresa;
				
				if ($data = $this->contacto_model->ingresarContacto($post))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'emailer/contactos/detalle/' . $data));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/emailer/contactos/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function confirmados($cantidad)
	{
		$grupo = 502;
		$categoria = 9085;
		
		// db Mailer
		$this->db = $this->load->database('mailer_app', true);
		
		//models
		$this->load->model('emailer_model');
		
		$parametros['estado'] = 3;
		$parametros['per_page'] = $cantidad;
		$parametros['categoria'] = $categoria;
		$parametros['id'] = $this->emailer_model->ultimoSuscriptorDeLaCategoria($categoria);

		$suscriptores = $this->emailer_model->getSuscriptoresActivos($parametros);
		
		if (isset($suscriptores))
		{
			foreach ($suscriptores as $obj)
			{
				// Ingresar suscriptor
				if (!$data['id'] = $this->emailer_model->verificarSiExiste($obj['email'], $grupo))
				{
					$data['nombre'] = $obj['nombre'];
					$data['apellido'] = $obj['apellido'];
					$data['email'] = $obj['email'];
					$data['estado'] = $obj['estado'];
					
					$data['id'] = $this->emailer_model->ingresarSuscriptor($data);
				}
				
				// Asociarlo a la categoría
				if (!$this->emailer_model->verificarSiExisteEnLaCategoria($data['id'], $parametros['categoria'])) $this->emailer_model->agregarSuscriptorALaCategoria($data['id'], $parametros['categoria']);
				
				if (isset($_GET['debug'])) echo '<pre>' . print_r($data, true) . '</pre>';	
			}
		}
		
		// Ingresar el ID del último suscriptor procesado
		$this->emailer_model->setUltimoSuscriptorDeLaCategoria($parametros['id']+$parametros['per_page'], $parametros['categoria']);
	}
	

}