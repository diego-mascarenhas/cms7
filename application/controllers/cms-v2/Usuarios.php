<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/Usuarios_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Usuarios_model->listadoUsuarios();
	
			//cargo las visats
			$this->load->view('header');
			$this->load->view('cms-v2/usuarios/index', $datos);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->load->helper('string');
	
			if(empty($_POST['id']))
			{
				$this->form_validation->set_rules('username', 'Usuario', 'required|min_length[4]|is_unique[contactos.username]', array('required' => 'Debe ingresar un usuario.', 'is_unique' => 'Nombre de usuario ya ingresado.'));
				$this->form_validation->set_rules('password', 'Password', 'required|min_length[4]', array('required' => 'Debe ingresar un password.'));
			}
			$this->form_validation->set_rules('nombre', 'Nombre', 'required|min_length[3]', array('required' => 'Debe ingresar un nombre.'));

	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(1 => 'Inactivo', 2 => 'Activo', 3 => 'Online', 4 => 'Bloqueado', 5 => 'Vencido', 6 => 'Prospecto');
				if($this->usuario->id_perfil < 4)
				{
					$datos['perfiles'] = array(5 =>'Comprador');
				}
				else
				{
					$datos['perfiles'] = array(5 =>'Comprador');
				}				

				if(isset($id))
				{
					$datos['item'] = $this->Usuarios_model->detalleUsuario($id);
					$datos['listado'] = $this->Usuarios_model->listadoPedidos($id);
					$datos['favoritos'] = $this->Usuarios_model->listadoFavoritos($id);
				}
				else
				{
					$datos['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}

				$this->load->view('header');
				$this->load->view('cms-v2/usuarios/ingresar', $datos);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Usuarios_model->ingresarUsuario($this->input->post()))
		        {
					if (!empty($_POST['id']))
					{
/* 						redirect(base_url('usuarios/ingresar/'.$_POST['id'].'?form=ok&empresa='.$_POST['marca'])); */
						redirect(base_url('cms-v2/usuarios'));
					}
					else
					{
/*
						$sql = "SELECT contactos.id";
						$sql .= " FROM contactos";
						$sql .= " ORDER BY contactos.id DESC LIMIT 1";
						$query = $this->db->query($sql);
						$res = $query->row_array();
						$_POST['id'] = $res['id'];
	
*/
						redirect(base_url('cms-v2/usuarios'));
					}
		        }
		        else
		        {
			        $this->load->view('usuarios/detalle', $datos);
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Usuarios_model->eliminarItem($this->input->post()))
	        {
				redirect(base_url('cms-v2/usuarios/'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('cms-v2/usuarios/detalle', $datos);
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

}
