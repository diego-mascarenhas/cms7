<?php defined('BASEPATH') or exit('No direct script access allowed');


class User extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('simple_session');
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
				
				if ($user->estado > 0)
				{
					// Write to a debug log file to track session data
					$log_message = date('Y-m-d H:i:s') . " - Login: User ID: " . $user->id . "\n";
					file_put_contents(FCPATH . 'application/logs/session_debug.log', $log_message, FILE_APPEND);
					
					// SIMPLIFIED SESSION IMPLEMENTATION
					// 1. Store in simple_session
					$this->simple_session->set('usuario', $user);
					$this->simple_session->set('logged_in', true);
					$this->simple_session->set('reseller', $user->id);
					
					// 2. Store in PHP native session as backup
					$_SESSION['usuario'] = $user;
					$_SESSION['logged_in'] = true;
					$_SESSION['reseller'] = $user->id;
					
					// 3. Store in CodeIgniter session
					$this->session->set_userdata('usuario', $user);
					$this->session->set_userdata('logged_in', true);
					$this->session->set_userdata('reseller', $user->id);
					
					// 4. Load menu and other critical data
					$servicios = $this->user_model->getUserServicios($id);
					$menu = $this->sys_model->menu($user->grupo, $user->id_perfil, $user->id);
					$config = $this->user_model->getUserConfig($user->id_empresa);
					
					// 5. Store additional data in all session types
					// In Simple Session
					$this->simple_session->set('servicios', $servicios);
					$this->simple_session->set('menu', $menu);
					$this->simple_session->set('config', $config);
					
					// In PHP native session
					$_SESSION['servicios'] = $servicios;
					$_SESSION['menu'] = $menu;
					$_SESSION['config'] = $config;
					
					// In CI session
					$this->session->set_userdata('servicios', $servicios);
					$this->session->set_userdata('menu', $menu);
					$this->session->set_userdata('config', $config);
					
					// Use our special method too as backup
					$this->store_user_in_session($user);
					
					// Verify the session data was stored
					$verification = "Session before redirect:\n";
					$verification .= "Session ID: " . session_id() . "\n";
					$verification .= "Has usuario: " . (isset($_SESSION['usuario']) ? 'YES' : 'NO') . "\n";
					$verification .= "Has logged_in: " . (isset($_SESSION['logged_in']) ? 'YES' : 'NO') . "\n";
					$verification .= "Simple session has usuario: " . ($this->simple_session->has('usuario') ? 'YES' : 'NO') . "\n";
					file_put_contents(FCPATH . 'application/logs/session_verification.log', $verification, FILE_APPEND);
					
					// Debug output before redirect
					if (isset($_GET['debug']) && $_GET['debug'] == 1) {
						echo "<pre style='background: #f5f5f5; padding: 15px; border: 1px solid #ddd; margin: 20px;'>";
						echo "<h2>Login Debug - Before Redirect</h2>";
						echo "<h3>SESSION DATA:</h3>";
						var_dump($_SESSION);
						echo "<h3>SESSION ID:</h3>";
						echo session_id();
						echo "<h3>User Object:</h3>";
						var_dump($user);
						echo "<h3>SIMPLE SESSION:</h3>";
						var_dump($this->simple_session->get());
						echo "</pre>";
						echo "<a href='" . base_url('home') . "'>Continue to Dashboard</a>";
						exit;
					}

					// Make sure we have a clean output buffer before redirect
					if (ob_get_level()) ob_end_clean();
					
					// Force session write
					session_write_close();
					
					// Redirect to home debug
					redirect(base_url('home/debug'));
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
			$this->simple_session->destroy();
			
			// user logout ok
			redirect(base_url('user/login?username=' . $data->username . '&password=' . $data->password));
		}
		elseif ($this->is_logged_in())
		{
			$this->user_model->pasarOffline($this->usuario->id);
			
			$redirect = ($this->session->has_userdata('logout')) ? $this->session->userdata('logout') : base_url();
			
			// remove session datas
			$this->session->sess_destroy();
			$this->simple_session->destroy();

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
