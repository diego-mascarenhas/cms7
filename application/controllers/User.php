<?php defined('BASEPATH') or exit('No direct script access allowed');


class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}


	public function index()
	{
		redirect(base_url('user/login'));
	}


	public function login()
	{
		// create the data object
		$data = new stdClass();

		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// data
		if ($this->input->get()) $this->form_validation->set_data($this->input->get());

		// set validation rules
		$this->form_validation->set_rules('username', 'Usuario', 'required|min_length[2]');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');

		if ($this->form_validation->run() == false)
		{
			// form values
			$data->detalle = ($this->input->post()) ? $this->input->post() : $this->input->get();

			// validation not ok, send validation errors to the view
			$this->load->view('user/login/login', $data);
		}
		else
		{
			// form values
			$data->detalle = ($this->input->post()) ? $this->input->post() : $this->input->get();
			
			// set variables from the form
			$username = $data->detalle['username'];
			$password = $data->detalle['password'];

			if ($this->user_model->userLogin($username, $password))
			{
				//models
				$this->load->model('sys_model');
				
				$id = $this->user_model->getUserIdFromUsername($username);
				$user = $this->user_model->getUserInfo($id);
				
				if ($user->estado > 1)
				{
					// set session user datas
					$this->session->set_userdata('logged_in', true);
					$this->session->set_userdata('usuario', $user);
					$this->session->set_userdata('servicios', $this->user_model->getUserServicios($id));
					$this->session->set_userdata('menu', $this->sys_model->menu($user->grupo, $user->id_perfil, $user->id));
					$this->session->set_userdata('config', $this->user_model->getUserConfig($user->id_empresa));
					
					if (!$this->session->has_userdata('reseller') && $user->id_perfil == 2) $this->session->set_userdata('reseller', $user->id);
					
					if ($this->session->has_userdata('reseller'))
					{
						if ($this->session->userdata('reseller') == $user->id) $this->user_model->updateUltimaVisita($user->id);
					}
					else
					{
						$this->user_model->updateUltimaVisita($user->id);
						
						$this->load->library('user_agent');
						if ($this->agent->is_referral()) $this->session->set_userdata('logout', $this->agent->referrer());
					}
					
					redirect((isset($data->detalle['redirect']) && !empty($data->detalle['redirect'])) ? $data->detalle['redirect'] : $user->dashboard);
				}
				else
				{
					// login failed
					$data->error = 'El Usuario está inactivo.';
	
					// send error to the view
					$this->load->view('user/login/login', $data);
				}
				
			}
			else
			{
				// login failed
				$data->error = 'Usuario o Contraseña inválidas.';

				// send error to the view
				$this->load->view('user/login/login', $data);
			}
		}
	}


	public function logout()
	{
		// create the data object
		$data = new stdClass();
		
		if ($this->session->has_userdata('reseller') && $this->session->userdata('reseller') != $this->usuario->id)
		{
			$data = $this->user_model->getUserInfo($this->session->userdata('reseller'));
			
			// remove session datas
			$this->session->sess_destroy();
			
			// user logout ok
			redirect(base_url('user/login?username=' . $data->username . '&password=' . $data->password));
		}
		elseif ($this->is_logged_in())
		{
			$this->user_model->pasarOffline($this->usuario->id);
			
			$redirect = ($this->session->has_userdata('logout')) ? $this->session->userdata('logout') : base_url();
			
			// remove session datas
			$this->session->sess_destroy();

			// user logout ok
			redirect($redirect);
		}
		else
		{
			// there user was not logged in, we cannot logged him out,
			redirect(base_url());
		}
	}
	
	
	public function password_reset()
	{
		// create the data object
		$data = new stdClass();

		// helpers and libraries
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// data
		if ($this->input->get()) $this->form_validation->set_data($this->input->get());

		// set validation rules
		$this->form_validation->set_rules('username', 'Usuario', 'required|min_length[2]');

		if ($this->form_validation->run() == false)
		{
			// form values
			$data->detalle = ($this->input->post()) ? $this->input->post() : $this->input->get();

			// validation not ok, send validation errors to the view
			$this->load->view('user/password-reset/index', $data);
		}
		else
		{
			// form values
			$data->detalle = ($this->input->post()) ? $this->input->post() : $this->input->get();
			
			$id = $this->user_model->getUserIdFromUsername($data->detalle['username']);
			$data = (array) $this->user_model->getUserInfo($id);

			$this->load->model('comunicacion_model');
			$res = $this->comunicacion_model->ingresarComunicacion($id, 11, null, $data);

			// set variables from the form
			redirect(base_url('user/password-reset-email'));
		}
		
	}
	
	
	public function password_reset_email()
	{
		$this->load->view('user/password-reset/email');
	}
	
	
	public function password_reset_confirm($token)
	{
		if ($id = $this->user_model->getUserIdFromToken($token))
		{
			// create the data object
			$data = new stdClass();
	
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			// data
			if ($this->input->get()) $this->form_validation->set_data($this->input->get());
			
			// set validation rules
			$this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[4]');
			$this->form_validation->set_rules('passconf', 'Confirmación de contraseña', 'required|matches[password]');
	
			if ($this->form_validation->run() == false)
			{
				// form values
				$data->detalle = ($this->input->post()) ? $this->input->post() : $this->input->get();
	
				// validation not ok, send validation errors to the view
				$this->load->view('user/password-reset/confirm', $data);
			}
			else
			{
				$this->user_model->changePassword($id, $this->input->post('password'));
				
				$data = $this->user_model->getUserInfo($id);
				
				redirect(base_url('user/login?username=' . $data->username . '&password=' . $data->password));
			}
		}
		else
		{
			$this->load->view('user/password-reset/empty');
		}
	}
	
	
	public function actualizar_usuarios_online()
	{
		// models
		$this->load->model('user_model');
		
		$data = $this->user_model->actualizarUsuariosOnline();
	}


}
