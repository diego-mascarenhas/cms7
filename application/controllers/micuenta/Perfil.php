<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Perfil extends MY_Controller {

	public function index()
	{
		/* navegación */
		$this->load->library('user_agent', 'url');
		if ($this->agent->referrer() != current_url()) $this->session->set_userdata(array('referrer'=>$this->agent->referrer()));
		
		if ($this->is_logged_in('admin'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('empresa_model');
	
			// helpers and libraries
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|min_length[8]');
			
			$this->form_validation->set_rules('empresa', 'Empresa', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('id_empresa', 'ID Empresa', 'integer');
			
			$this->form_validation->set_rules('id_empresa_fiscal', 'ID Empresa Fiscal', 'integer');
			
			$this->form_validation->set_rules('id_condicion_iva', 'Condición fiscal', 'trim|required|integer');
			
			if ($this->input->post('id_condicion_iva') == 3) // Consumidor Final
			{
				$this->form_validation->set_rules('cuit', 'DNI', 'trim|required|valid_dni');
			}
			elseif ($this->input->post('id_condicion_iva') == 2) // Monotributista
			{
				$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
			}
			else // Responsable Inscripto, Exento
			{
				$this->form_validation->set_rules('cuit', 'CUIT', 'trim|required|valid_cuit');
				$this->form_validation->set_rules('razon_social', 'Razón Social', 'trim|required|min_length[3]');
			}
			
			$this->form_validation->set_rules('id_forma_pago', 'Forma de pago', 'trim|required|integer');
			
			if ($this->input->post('id_forma_pago') == 5)
			{
				$this->form_validation->set_rules('titular', 'Titular', 'trim|required|min_length[3]');
				$this->form_validation->set_rules('cuenta_documento', 'Documento del titular de la cuenta', 'trim|required|min_length[8]');
				$this->form_validation->set_rules('cbu', 'CBU', 'trim|required|min_length[22]');
			}

			if ($this->form_validation->run() === false)
			{
				// form values
				$data['valores'] = ($this->input->post()) ? $this->input->post() : $this->empresa_model->getMiCuenta();
				
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/micuenta/perfil/form', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				$valores = $this->empresa_model->getMiCuenta();
				
				if ($data = $this->empresa_model->actualizarMiCuenta($this->input->post()))
				{
					// models
					$this->load->model('comunicacion_model');
			
					$post = $this->input->post();
					
					$post['nombre_update'] = ($valores['nombre'] != $this->input->post('nombre')) ? true : false;
					$post['apellido_update'] = ($valores['apellido'] != $this->input->post('apellido')) ? true : false;
					$post['telefono_update'] = ($valores['telefono'] != $this->input->post('telefono')) ? true : false;
					
					$post['empresa_update'] = ($valores['empresa'] != $this->input->post('empresa')) ? true : false;
				
					$post['forma_pago_update'] = ($valores['id_forma_pago'] != $this->input->post('id_forma_pago')) ? true : false;
					$post['factura_tipo_update'] = ($valores['id_factura_tipo'] != $this->input->post('id_factura_tipo')) ? true : false;
					
					$post['razon_social_update'] = ($valores['razon_social'] != $this->input->post('razon_social')) ? true : false;
					$post['cuit_update'] = ($valores['cuit'] != $this->input->post('cuit')) ? true : false;
					
					if (!empty($this->input->post('cbu')))
					{
						$post['titular_update'] = ($valores['titular'] != $this->input->post('titular')) ? true : false;
						$post['cbu_update'] = ($valores['cbu'] != $this->input->post('cbu')) ? true : false;
						$post['cuenta_documento_update'] = ($valores['cuenta_documento'] != $this->input->post('cuenta_documento')) ? true : false;
					}
					
					$res['id'] = $this->comunicacion_model->ingresarComunicacion($valores['id_contacto'], 16, null, $post);
					$this->comunicacion_model->enviarComunicacionCopia($res['id'], 'administracion@revisionalpha.com');
					
					
					// Alertas
					$alertas['contacto'] = false;
					if (!$this->session->userdata('usuario')->id_empresa || $this->empresa_model->verificarDatosDeLaEmpresaIncompletos($this->usuario->id_empresa)) $alertas['empresa'] = true;
					if (isset($alertas)) $this->session->set_userdata('alertas', $alertas);
			
					redirect(base_url('micuenta'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/micuenta/perfil/error/');
				}
			}
		}
		
		elseif ($this->is_logged_in('user'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('empresa_model');
	
			// helpers and libraries
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('apellido', 'Apellido', 'trim|required|min_length[3]');
			$this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required|min_length[8]');
			
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['valores'] = ($this->input->post()) ? $this->input->post() : $this->empresa_model->getMiCuenta();
				
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/micuenta/perfil/form', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				if ($data = $this->empresa_model->actualizarMiCuenta($this->input->post()))
				{
					redirect(base_url('micuenta'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/micuenta/perfil/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function idioma()
	{
		/* navegación */
		$this->load->library('user_agent', 'url');
		if ($this->agent->referrer() != current_url()) $this->session->set_userdata(array('referrer'=>$this->agent->referrer()));
		
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			// models
			$this->load->model('contacto_model');
			$this->load->model('sys_model');
			
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->load->library('combos');
	
			
			// set validation rules
			$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
			$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->contacto_model->getContactoDetalleRaw($this->usuario->id);
				$data['idiomas'] = $this->combos->idiomas();
				
				
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/micuenta/perfil/form_idioma', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				if ($data = $this->contacto_model->modificarContacto($this->usuario->id, $this->input->post()))
				{
					//redirect(($this->session->userdata('referrer')) ? $this->session->userdata('referrer') : base_url('micuenta/perfil'));
					redirect(base_url('micuenta'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view($this->tema() . '/micuenta/perfil/error/');
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function password()
	{
		/* navegación */
		$this->load->library('user_agent', 'url');
		if ($this->agent->referrer() != current_url()) $this->session->set_userdata(array('referrer'=>$this->agent->referrer()));
		
		if ($this->is_logged_in())
		{
			// models
			$this->load->model('user_model');
			
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
				$data['detalle'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
	
				// validation not ok, send validation errors to the view
				$this->load->view($this->tema() . '/header');
				$this->load->view($this->tema() . '/micuenta/perfil/form_password', $data);
				$this->load->view($this->tema() . '/footer');
			}
			else
			{
				$this->user_model->changePassword($this->usuario->id, $this->input->post('password'));
				
				redirect(base_url('micuenta'));
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	

}