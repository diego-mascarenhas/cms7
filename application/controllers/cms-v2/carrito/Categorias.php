<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/carrito/categorias_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');

			$parametros['padre'] = 0;
			$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
			$data['listado'] = $this->categorias_model->getCategorias();				

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/categorias/index', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	//FILTRAR
	public function filtrar()
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$parametros1['padre'] = 0;
			$data['categorias'] = $this->categorias_model->comboCategorias($parametros1);
			if($this->input->post('padre') > 0 ) { $parametros['padre'] = $this->input->post('padre'); } else { $parametros['hijo'] =1;}
			$data['listado'] = $this->categorias_model->getCategorias($parametros);

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/categorias/index', $data);
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
			$this->load->helper('text');
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('categoria', 'Categoria', 'required|min_length[3]', array('required' => 'Debe ingresar una categor&iacute;a.', 'min_length' => 'Debe ingresar una categor&iacute;a de al menos 3 caracteres.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));

			if ($this->form_validation->run() === false)
			{
				$parametros['padre'] = 0;
				$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->categorias_model->ingresarCategoria($this->input->post()))
		        {
					if($this->input->post('menu') == 1)
					{
					    $valores = $this->input->post();
						$valores['id'] = $data['id'];
						$seccion = $this->categorias_model->ingresarSeccion($valores);
					}
					redirect(base_url('cms-v2/carrito/categorias'));
		        }
		        else
		        {
					$data['mensaje'] = 'Error en la carga de categor&iacute;a';

					$this->load->view('header');
					$this->load->view('cms-v2/categorias/error', $data);
					$this->load->view('footer');
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
			$this->form_validation->set_rules('categoria', 'Categoria', 'required|min_length[3]', array('required' => 'Debe ingresar una categor&iacute;a.', 'min_length' => 'Debe ingresar una categor&iacute;a de al menos 3 caracteres.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe ingresar un estado.'));
			
			if ($this->form_validation->run() === false)
			{
				$parametros['padre'] = 0;
				$data['categorias'] = $this->categorias_model->comboCategorias($parametros);
				$data['item'] = $this->categorias_model->detalleCategoria($id);

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/categorias/form', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($data = $this->categorias_model->modificarCategoria($this->input->post()))
		        {
					if($this->input->post('menu') == 1)
					{
					    $valores = $this->input->post();
						$valores['id'] = $id;
						$seccion = $this->categorias_model->ingresarSeccion($valores);
					}
					redirect(base_url('cms-v2/carrito/categorias'));
		        }
		        else
		        {
					$data['mensaje'] = 'Error en la modificaci&oacute;n de categor&iacute;a';

					$this->load->view('header');
					$this->load->view('cms-v2/carrito/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function duplicar($id)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->categorias_model->duplicarCategoria($id))
	        {
	            redirect(base_url('cms-v2/carrito/categorias'));
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la duplicaci&oacute;n de categor&iacute;a';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function ordenar($padre)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$parametros['padre'] = $padre;
			$data['listado'] = $this->categorias_model->getCategorias($parametros);

			$this->load->view('header');
			$this->load->view('cms-v2/carrito/categorias/ordenar', $data);
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ordenarCategorias()
	{
		$data = $this->categorias_model->ordenarCategorias(json_decode($this->input->post('items')));
		echo json_encode($data);
	}

	public function publicar()
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->categorias_model->publicarCategoria($this->input->post()))
	        {
	            redirect(base_url('cms-v2/carrito/categorias'));
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la modificaci&oacute;n de categor&iacute;a';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function eliminar()
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->categorias_model->eliminarCategoria($this->input->post()))
	        {
	            redirect(base_url('cms-v2/carrito/categorias'));
	        }
	        else
	        {
				$data['mensaje'] = 'Error en la eliminaci&oacute;n de categor&iacute;a';

				$this->load->view('header');
				$this->load->view('cms-v2/carrito/error', $data);
				$this->load->view('footer');
	        }
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}
}