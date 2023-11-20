<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// models
		$this->load->model('agenda_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['fechas'] = $this->agenda_model->getFechas();
			$data['listado'] = $this->agenda_model->getReuniones();
	
			$this->load->view('header');
			$this->load->view('agenda/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$this->trackUri();
			
			$data['detalle'] = $this->agenda_model->getReunionDetalle($id);

			if (isset($data['detalle']))
			{
				$this->load->view('/header');
				$this->load->view('/agenda/detalle', $data);
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
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[3]', array('required' => 'Debe ingresar un nombre.','min_length' => 'Debe ingresar un nombre de al menos 3 caracteres.'));
			$this->form_validation->set_rules('empresa', 'Empresa', 'required|min_length[3]', array('required' => 'Debe ingresar una empresa.','min_length' => 'Debe ingresar una empresa de al menos 3 caracteres.'));
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('required' => 'Debe ingresar un email.', 'valid_email' => 'Debe ingresar un email v&aacute;lido.'));
			$this->form_validation->set_rules('telefono', 'Tel&eacute;fono', 'required|integer|min_length[8]', array('required' => 'Debe ingresar un tel&eacute;fono.', 'integer' => 'Debe ingresar s&oacute;lo n&uacute;meros en el tel&eacute;fono.', 'min_length' => 'Debe ingresar un tel&eacute;fono de al menos 8 caracteres.'));
			$this->form_validation->set_rules('pais', 'País', 'required|min_length[3]', array('required' => 'Debe ingresar una casa matriz.','min_length' => 'Debe ingresar una casa matriz de al menos 3 caracteres.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['paises'] = array('0' =>'-- Seleccione país -- ','1' =>'Argentina','2' =>'Uruguay','3' =>'Chile','4' =>'Brasil','5' =>'Perú','6' =>'Colombia','7' =>'Panamá','8' =>'Costa Rica','9' =>'México','10' =>'España');
				$data['estados'] = array('2' =>'Solicitada', '3' =>'Confirmada','1' =>'Rechazada','4' =>'Cancelada','5' =>'Mail Incorrecto');
				$data['fechas'] = $this->agenda_model->getFechasPublico($this->usuario->id_empresa);
				$data['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				
				$this->load->view('header');
				$this->load->view('agenda/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->agenda_model->ingresarReunion($this->input->post()))
		        {
			        redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'agenda'));
		        }
		        else
		        {
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/agenda/error/');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[3]', array('required' => 'Debe ingresar un nombre.','min_length' => 'Debe ingresar un nombre de al menos 3 caracteres.'));
			$this->form_validation->set_rules('empresa', 'Empresa', 'required|min_length[3]', array('required' => 'Debe ingresar una empresa.','min_length' => 'Debe ingresar una empresa de al menos 3 caracteres.'));
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('required' => 'Debe ingresar un email.', 'valid_email' => 'Debe ingresar un email v&aacute;lido.'));
			$this->form_validation->set_rules('telefono', 'Tel&eacute;fono', 'required|integer|min_length[8]', array('required' => 'Debe ingresar un tel&eacute;fono.', 'integer' => 'Debe ingresar s&oacute;lo n&uacute;meros en el tel&eacute;fono.', 'min_length' => 'Debe ingresar un tel&eacute;fono de al menos 8 caracteres.'));
			$this->form_validation->set_rules('pais', 'País', 'required|min_length[3]', array('required' => 'Debe ingresar una casa matriz.','min_length' => 'Debe ingresar una casa matriz de al menos 3 caracteres.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['paises'] = array('0' =>'-- Seleccione país -- ','1' =>'Argentina','2' =>'Uruguay','3' =>'Chile','4' =>'Brasil','5' =>'Perú','6' =>'Colombia','7' =>'Panamá','8' =>'Costa Rica','9' =>'México','10' =>'España');
				$data['estados'] = array('2' =>'Solicitada', '3' =>'Confirmada','1' =>'Rechazada','4' =>'Cancelada','5' =>'Mail Incorrecto');
				$data['fechas'] = $this->agenda_model->getFechasPublico($this->usuario->id_empresa);
				$data['detalle'] = $this->agenda_model->getReunionDetalle($id);
				
				$this->load->view('header');
				$this->load->view('agenda/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->agenda_model->modificarReunion($id, $this->input->post()))
		        {
		        	redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'agenda/detalle/' . $id));
		        }
		        else
		        {
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/agenda/error/');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function llena_fechas()
	{
		$id = $this->usuario->id_empresa;
		$pais = $this->input->post('oficina');
		$fechas = $this->agenda_model->getFechasPublico($id, $pais);
		foreach($fechas as $fecha)
		{
		?>
			<option value="<?php echo $fecha -> id; ?>"><?php echo $fecha -> dia.' - '.$fecha -> hora.'hs.'; ?></option>
		<?php
		}
	}


	public function fechas()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('dia', 'Día', 'required|min_length[10]', array('required' => 'Debe ingresar un día.','min_length' => 'Debe ingresar el día con formato dd/mm/aaaa.'));
			$this->form_validation->set_rules('hora', 'Hora', 'required|min_length[5]', array('required' => 'Debe ingresar un horario.', 'min_length' => 'Debe ingresar la hora con formato hh:mm.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['paises'] = array('1' =>'Argentina','2' =>'Uruguay','3' =>'Chile','4' =>'Brasil','5' =>'Perú','6' =>'Colombia','7' =>'Panamá','8' =>'Costa Rica','9' =>'México','10' =>'España');
				$data['estados'] = array('3' =>'Disponible','1' =>'Bloqueada');
				
				$this->load->view('header');
				$this->load->view('agenda/fechas', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->agenda_model->ingresarFecha($this->input->post()))
		        {
			        redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'agenda'));
		        }
		        else
		        {
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/agenda/error/');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function modificar_fecha($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('dia', 'Día', 'required|min_length[10]', array('required' => 'Debe ingresar un día.','min_length' => 'Debe ingresar el día con formato dd/mm/aaaa.'));
			$this->form_validation->set_rules('hora', 'Hora', 'required|min_length[5]', array('required' => 'Debe ingresar un horario.', 'min_length' => 'Debe ingresar la hora con formato hh:mm.'));
	
			if ($this->form_validation->run() === false)
			{
				$data['contenido'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['paises'] = array('1' =>'Argentina','2' =>'Uruguay','3' =>'Chile','4' =>'Brasil','5' =>'Perú','6' =>'Colombia','7' =>'Panamá','8' =>'Costa Rica','9' =>'México','10' =>'España');
				$data['estados'] = array('3' =>'Disponible','1' =>'Bloqueada');
				$data['detalle'] = $this->agenda_model->getFechaDetalle($id);
				$data['reunion'] = $this->agenda_model->getReunionEstado($id);
				
				$this->load->view('header');
				$this->load->view('agenda/fechas', $data);
				$this->load->view('footer');
			}
			else
			{
				$data['reunion'] = $this->agenda_model->getReunionEstado($id);

				if(($this->input->post('estado') == 3) && ($data['reunion']['estado'] == (2 || 3)) )
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
					$this->load->view('/agenda/error/');
				}
				else
				{
					if ($data = $this->agenda_model->modificarFecha($id,$this->input->post()))
			        {
			        	redirect(base_url((!empty($this->input->post('redirect'))) ? $this->input->post('redirect') : 'agenda'));
			        }
			        else
			        {
						$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
						$this->load->view('/agenda/error/');
			        }
			    }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function duplicar_fecha($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->agenda_model->duplicarFecha($id))
	        {
				redirect(base_url('/agenda/'));
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	public function eliminar($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->agenda_model->getReunionDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/agenda/eliminar', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'agenda'))
				{
					$res = $this->sys_model->eliminar($id, 'agenda');
					
					redirect(base_url('agenda'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/agenda/error/');
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

	public function eliminar_fecha($id)
	{
		if ($this->is_logged_in('reseller') || $this->is_logged_in('admin'))
		{
			// helpers and libraries
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			
			// set validation rules
			$this->form_validation->set_rules('id', 'ID', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// form values
				$data['detalle'] = $this->agenda_model->getFechaDetalle($id);
				
				$this->load->view('/header');
				$this->load->view('/agenda/eliminar_fecha', $data);
				$this->load->view('/footer');
			}
			else
			{
				// models
				$this->load->model('sys_model');
			
				if ($data = $this->sys_model->verificarPropiedad($id, 'agenda_fechas'))
				{
					$res = $this->sys_model->eliminar($id, 'agenda_fechas');
					
					redirect(base_url('agenda'));
				}
				else
				{
					$data['error'] = 'Ha habido un problema, por favor intenta más tarde';
	
					$this->load->view('/agenda/error/');
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