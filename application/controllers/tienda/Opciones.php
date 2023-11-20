<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opciones extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$parametros = array('tienda' => $data['tienda']['id'], 'estado' => 0);
			$data['opciones'] = $this->tienda_model->getGrupos($parametros);

			$this->load->view('header');
			$this->load->view('tienda/opciones/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	public function listado($id)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$data['item'] = $this->tienda_model->detalleProducto($id, 1);
			$parametros = array('tienda' => $data['tienda']['id'], 'producto' => $data['item']['id'], 'estado' => 0);
			$data['opciones'] = $this->tienda_model->getGrupos($parametros);

			$this->load->view('header');
			$this->load->view('tienda/opciones/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function asociar($id = NULL)
	{
		if ($this->is_logged_in())
		{	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('relacionesgrupos[]', 'Nombre', 'required', array('required' => 'Debe ingresar un ítem.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = $this->tienda_model->detalleProducto($id, 1);
				$parametros = array('tienda' => $data['tienda']['id'], 'estado' => 0);
				$data['opciones'] = $this->tienda_model->getGrupos($parametros);
				$parametros2 = array('tienda' => $data['tienda']['id'], 'estado' => 0, 'producto' => $id);
				$data['gruposrelacionados'] = $this->tienda_model->getGrupos($parametros2);

				$this->load->view('header');
				$this->load->view('tienda/opciones/asociar', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->relacionarGrupo($id, $this->input->post()))
		        {
					redirect(base_url('tienda/opciones/listado/'.$id));
		        }
		        else
		        {
					$this->load->view('tienda/opciones/asociar');
			        echo 'Error';
		        }
			}
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
			$this->form_validation->set_rules('opcion_grupo', 'Nombre', 'required', array('required' => 'Debe ingresar un nombre.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('tienda/opciones/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->ingresarGrupo($id, $this->input->post()))
		        {
					redirect(base_url('tienda/opciones/index'));
		        }
		        else
		        {
					$this->load->view('tienda/opciones');
			        echo 'Error';
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
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('opcion_grupo', 'Nombre', 'required', array('required' => 'Debe ingresar un nombre.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = $this->tienda_model->detalleGrupo($id);

				$this->load->view('header');
				$this->load->view('tienda/opciones/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->modificarGrupo($id))
		        {
					redirect(base_url('tienda/opciones/'));
		        }
		        else
		        {
					$this->load->view('tienda/opciones/index');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function items($grupo, $producto = null)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$parametros = array('tienda' => $data['tienda']['id'], 'grupo' => $grupo, 'estado' => 0);
			if($producto) $data['item'] = $this->tienda_model->detalleProducto($producto, 1);
			$data['grupo'] = $this->tienda_model->detalleGrupo($grupo);
			$data['listado'] = $this->tienda_model->getOpciones($parametros);

			$this->load->view('header');
			$this->load->view('tienda/opciones/items', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar_item($id)
	{
		if ($this->is_logged_in())
		{	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('opcion', 'Opci&oacute;n', 'required', array('required' => 'Debe ingresar una opci&oacute;n.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['grupo'] = $this->tienda_model->detalleGrupo($id);

				$this->load->view('header');
				$this->load->view('tienda/opciones/form_items', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->ingresarItemGrupo($this->input->post()))
		        {
					redirect(base_url('tienda/opciones/items/'.$id));
		        }
		        else
		        {
					$this->load->view('tienda/opciones');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function modificar_item($grupo, $id)
	{
		if ($this->is_logged_in())
		{	
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('opcion', 'Opci&oacute;n', 'required', array('required' => 'Debe ingresar una opci&oacute;n.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
				$data['item'] = $this->tienda_model->detalleItemGrupo($id);
				$data['grupo'] = $this->tienda_model->detalleGrupo($grupo);

				$this->load->view('header');
				$this->load->view('tienda/opciones/form_items', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->tienda_model->modificarItemGrupo($this->input->post()))
		        {
					redirect(base_url('tienda/opciones/items/'.$grupo));
		        }
		        else
		        {
					$this->load->view('tienda/opciones');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	/* modificar_item */

	public function estado_items($id, $id_item)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->cambiarEstado($id, 'tienda_opciones'))
	        {
				redirect(base_url('tienda/opciones/items/'.$id_item));
	        }
	        else
	        {
				$this->load->view('tienda/opciones/items');
		        echo 'Error';
	        }
	    }
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function estado_grupo($id)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->cambiarEstado($id, 'tienda_opciones_grupos'))
	        {
				redirect(base_url('tienda/opciones'));
	        }
	        else
	        {
				$this->load->view('tienda/opciones');
		        echo 'Error';
	        }
	    }
		else
		{
			redirect(base_url('user/login/'));
		}
	}
/*
	public function ordenar($categoria)
	{
		if ($this->is_logged_in())
		{
			$data['tienda'] = $this->tienda_model->detalleConfiguracion(null, null, $this->usuario->id_empresa);
			$parametros = array('tienda' => $data['tienda']['id'], 'estado' => 0);
			$data['listado'] = $this->tienda_model->getGrupos($parametros);
	
			//cargo las vistas
			$this->load->view('header');
			$this->load->view('tienda/opciones/ordenar', $data);
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenarOpciones()
	{
		$data = $this->tienda_model->ordenarItems(json_decode($_POST['items']), 'tienda_opciones_grupos');
		echo json_encode($data);
	}
*/

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_opciones_grupos'))
	        {
				redirect(base_url('tienda/opciones'));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/opciones/index');
		        echo 'Error';
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function eliminar_item($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->tienda_model->eliminarItems($this->input->post(), 'tienda_opciones'))
	        {
				redirect(base_url('tienda/opciones/items/'.$this->input->post('grupo')));
	        }
	        else
	        {
				$this->load->view('header');
		        $this->load->view('tienda/opciones/index');
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


	
