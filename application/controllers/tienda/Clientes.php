<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['listado'] = $this->tienda_model->listadoPedidos($data['tienda']['id']);

			$this->load->view('header');
			$this->load->view('tienda/pedidos/clientes', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function registrados()
	{
		if ($this->is_logged_in())
		{
			$this->load->model('contacto_model');
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$parametros['id_perfil'] = 5;
			$data['listado'] = $this->contacto_model->getContactos($parametros);

			$this->load->view('header');
			$this->load->view('tienda/clientes/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

			$this->load->model('contacto_model');
			$this->load->helper('form');
			$this->load->library('form_validation');

			if($this->input->post('email'))
			{
				//VERIFICO EMAIL UNICO PARA CADA EMPRESA
				$variables['email'] = $this->input->post('email');
				$variables['empresa'] = $this->usuario->id_empresa;
				
				$contacto = $this->tienda_model->verificarContacto($variables);
				if(isset($contacto['username']))
				{
					$data['erroremail'] = 'El Usuario/Email ya est&aacute; en uso, elija otro por favor.';
				}
				else
				{
					$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
				}
			}
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[M,F]');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
			$this->form_validation->set_rules('celular', 'Celular', 'trim|required');
			$this->form_validation->set_rules('password', 'Contraseña', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|required|integer');
			
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['adicionales'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
								
				$this->load->view('header');
				$this->load->view('tienda/clientes/form', $data);
				$this->load->view('footer');
			}
			else
			{
				
				if ($data = $this->contacto_model->ingresarContacto($this->input->post()))
				{				
					$adicionales = $this->tienda_model->ingresarContactoAdicionales($data, $this->input->post());
					$valores['username'] = $this->usuario->id_empresa.$data;
					$modificar = $this->contacto_model->modificarContacto($data, $valores);
					redirect(base_url('tienda/clientes/registrados'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					$this->load->view('header');
					$this->load->view('tienda/clientes/form', $data);
					$this->load->view('footer');
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
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);

			$this->load->model('contacto_model');
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
			$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
			$this->form_validation->set_rules('celular', 'Celular', 'trim');
			$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
			$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
			
			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $post : $this->contacto_model->getContactoDetalleRaw($id);
				$data['adicionales'] = ($this->input->post()) ? $post : $this->tienda_model->getContactoAdicionales($id);
												
				$this->load->view('header');
				$this->load->view('tienda/clientes/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->contacto_model->modificarContacto($id, $this->input->post()))
				{
					$adicionales = $this->tienda_model->modificarContactoAdicionales($id, $this->input->post());
					redirect(base_url('tienda/clientes/registrados'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					$this->load->view('header');
					$this->load->view('tienda/clientes/form', $data);
					$this->load->view('footer');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
}
