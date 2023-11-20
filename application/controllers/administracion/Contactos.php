<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Contactos extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->library('pagination');
			
			
			$parametros['page'] = $this->input->get('page');
			$parametros['search'] = $this->input->get('search');

			$parametros['order_by'] = ($this->input->get('order_by')) ? $this->input->get('order_by') : 'ultima_visita';
			$parametros['order'] = ($this->input->get('order')) ? $this->input->get('order') : 'DESC';
			
			$data['contactos'] = $this->contacto_model->getContactos($parametros);
			
						
			$config['total_rows'] = $this->contacto_model->total();
			$data['paginado'] = $this->pagination->initialize($config)->create_links();
			
			$this->load->view('/header', array('buscador'=>true));
			($config['total_rows'] > 0) ? $this->load->view('/administracion/contactos/index', $data) : $this->load->view('/administracion/contactos/empty');
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
			$this->load->model('contacto_model');
			$this->load->model('ticket_model');
			$this->load->model('comunicacion_model');
			$this->load->model('voip_model');
			
			// helpers and libraries
			$this->load->helper('text');
			

			$data['detalle'] = $this->contacto_model->getContactoDetalle($id);
			$data['tickets'] = $this->ticket_model->getTickets(array('id_contacto'=>$id));
			$data['comunicaciones'] = $this->comunicacion_model->getComunicaciones(array('id_contacto'=>$id));
			$data['llamadas'] = $this->voip_model->getLlamadas(array('id_contacto'=>$id));
			$data['reseller'] = $this->session->userdata('reseller');
			
			if ($this->is_logged_in('reseller'))
			{
				$this->load->model('nota_model');
				$data['notas'] = $this->nota_model->getNotas(array('id_tipo'=>111, 'id_referencia'=>$id));
			}
			
			if (isset($data['detalle']))
			{
				$this->load->view('/header');
				$this->load->view('/administracion/contactos/detalle', $data);
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
			$this->load->model('contacto_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');

			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
			$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
			$this->form_validation->set_rules('celular', 'Celular', 'trim');
			
			$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
			$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]|is_unique[contactos.username]');
			$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
			$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$default['id_empresa'] = $this->input->get('id_empresa');
				$default['timezone'] = $this->usuario->timezone;
				$default['idioma'] = $this->usuario->idioma;
				$default['area_privada'] = 4;
				$default['estado'] = 2;
				
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $default;
				$data['empresas'] = $this->empresa_model->comboEmpresas();
				$data['perfiles'] = $this->sys_model->comboPerfiles();
				$data['idiomas'] = $this->combos->idiomas();
				$data['accion'] = 'ingresar';
				
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/administracion/contactos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->contacto_model->ingresarContacto($this->input->post()))
				{
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'administracion/contactos/detalle/' . $data));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/contactos/error/');
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
			$this->load->model('contacto_model');
			$this->load->model('empresa_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
			$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
			$this->form_validation->set_rules('celular', 'Celular', 'trim');
			
			$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
			$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]|is_unique[contactos.username]');
			$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
			$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
			
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			$this->form_validation->set_message('is_unique', 'El usuario ya existe, por favor elige otro.');
            
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$post = $this->input->post();
				if ($this->input->post('username')) unset($post['username']);
				
				$data['detalle'] = ($this->input->post()) ? $post : $this->contacto_model->getContactoDetalleRaw($id);
				$data['empresas'] = $this->empresa_model->comboEmpresas();
				$data['perfiles'] = $this->sys_model->comboPerfiles();
				$data['idiomas'] = $this->combos->idiomas();
				
				// validation not ok, send validation errors to the view
				$this->load->view('/header');
				$this->load->view('/administracion/contactos/form', $data);
				$this->load->view('/footer');
			}
			else
			{
				if ($data = $this->contacto_model->modificarContacto($id, $this->input->post()))
				{
					$row = $this->contacto_model->getContactoDetalleRaw($id);
					if (isset($row['username']) && !isset($row['hash'])) $this->contacto_model->modificarHash($id, $this->input->post('username'));
					
					redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'administracion/contactos/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/contactos/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->contacto_model->getContactoDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/contactos/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'contactos'))
				{
					$res = $this->sys_model->eliminar($id, 'contactos');
					
					redirect(base_url('administracion/contactos/'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/contactos/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function comunicar($id)
	{
		// models
		$this->load->model('contacto_model');	
		
		$data = $this->contacto_model->comunicarContacto($id);
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function crear_hash()
	{
		// models
		$this->load->model('contacto_model');	
		
		$data = $this->contacto_model->crearHash();
		
		echo '<pre>' . print_r($data, true) . '</pre>';
	}
	
	
	public function password_reset($id)
	{
		if ($this->is_logged_in('reseller'))
		{
			// models
			$this->load->model('contacto_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->contacto_model->getContactoDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/administracion/contactos/password_reset', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'contactos'))
				{
					// models
					$this->load->model('user_model');
					$this->load->model('comunicacion_model');
					
					$data = (array) $this->user_model->getUserInfo($id);
					$this->comunicacion_model->ingresarComunicacion($id, 11, null, $data);
			
					// set variables from the form
					redirect(base_url('administracion/contactos/detalle/' . $id));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/administracion/contactos/error/');
				}
			}
		}
		elseif ($this->is_logged_in())
		{
			$this->load->view('/401');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
			

}