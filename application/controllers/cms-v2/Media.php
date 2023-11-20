<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//cargo el modelo de secciones
		$this->load->model('cms/Media_model');
	}

	public function index()
	{
		if ($this->is_logged_in())
		{
			$datos['listado'] = $this->Media_model->listadoMedia();
	
			$this->load->view('cms/header');
			$this->load->view('cms/media/listado', $datos);
			$this->load->view('cms/footer');
		}
		else
		{
			redirect(base_url('/cms/user/login/'));
		}
	}

	public function listados()
	{
		if ($this->is_logged_in())
		{
			$this->load->library('pagination');
			
	        // init params
	        $params = array();
	        $limit_per_page = 50;
	        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
	        $total_records = $this->Media_model->get_total();
/*
	        echo $total_records;
	        die();
*/
	
	        if ($total_records > 0) 
	        {
	            $datos["listado"] = $this->Media_model->listadoMediaPaginas($start_index,30);
	             
	            $config['base_url'] = base_url().'cms/media/pagina';
	            $config['total_rows'] = $total_records;
	            $config['per_page'] = $limit_per_page;
	            $config["uri_segment"] = 4;
	            $config["num_links"] = 8;
	            $config['cur_tag_open'] = '<span class="curlink">';
	            $config['cur_tag_close'] =  ' &nbsp;| </span>';
	 
	            $config['num_tag_open'] = '<span class="numlink">';
	            $config['num_tag_close'] = ' &nbsp;| </span>';
	            $config['last_link'] = ' [&Uacute;lt.]';
	            $config['last_tag_open'] = '<span class="lastlink">';
	            $config['last_tag_close'] = '</span>';
	             
	            $config['next_link'] = '&raquo; ';
	            $config['next_tag_open'] = '<span class="nextlink">';
	            $config['next_tag_close'] = '</span>';
	 
	            $config['prev_link'] = '&laquo; ';
	            $config['prev_tag_open'] = '<span class="prevlink">';
	            $config['prev_tag_close'] = '</span>';
	
	            $this->pagination->initialize($config);
	             
	            $datos["links"] = $this->pagination->create_links();
	        }
	         	
			//$datos['listado'] = $this->Media_model->listadoMedia();
			
			$this->load->view('cms/header');
			$this->load->view('cms/media/listado-2', $datos);
			$this->load->view('cms/footer');
		}
		else
		{
			redirect(base_url('/cms/user/login/'));
		}
	}

	public function pagina()
	{
		if ($this->is_logged_in())
		{
			$this->load->library('pagination');
			
	        // init params
	        $params = array();
	        $limit_per_page = 50;
	        $start_index = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
	        $total_records = $this->Media_model->get_total();
	
	        if ($total_records > 0) 
	        {
	            $datos["listado"] = $this->Media_model->listadoMediaPaginas($start_index,30);
	             
	            $config['base_url'] = base_url().'cms/media/pagina';
	            $config['total_rows'] = $total_records;
	            $config['per_page'] = $limit_per_page;
	            $config["uri_segment"] = 4;
	            $config["num_links"] = 8;
	            $config['cur_tag_open'] = '<span class="curlink">';
	            $config['cur_tag_close'] =  ' &nbsp;| </span>';
	 
	            $config['num_tag_open'] = '<span class="numlink">';
	            $config['num_tag_close'] = ' &nbsp;| </span>';
	            $config['last_link'] = ' [&Uacute;lt.]';
	            $config['last_tag_open'] = '<span class="lastlink">';
	            $config['last_tag_close'] = '</span>';
	             
	            $config['next_link'] = '&raquo; ';
	            $config['next_tag_open'] = '<span class="nextlink">';
	            $config['next_tag_close'] = '</span>';
	 
	            $config['prev_link'] = '&laquo; ';
	            $config['prev_tag_open'] = '<span class="prevlink">';
	            $config['prev_tag_close'] = '</span>';
	
	            $this->pagination->initialize($config);
	             
	            $datos["links"] = $this->pagination->create_links();
	        }
	         	
			//$datos['listado'] = $this->Media_model->listadoMedia();
			
			$this->load->view('cms/header');
			$this->load->view('cms/media/listado-2', $datos);
			$this->load->view('cms/footer');
		}
		else
		{
			redirect(base_url('/cms/user/login/'));
		}
	}

	public function ingresar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
	
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
	
			if ($this->form_validation->run() === false)
			{
				$datos['estados'] = array(3=>'Activo', 1=>'Inactivo');
				$datos['destacados'] = array(0=>'No destacado', 1=>'Destacado');
				
				if(isset($id))
				{
					$datos['imagen'] = $this->Media_model->detalleMedia($id);
				}
				else
				{
					$datos['imagen'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				}
				$this->load->view('cms/header');
				$this->load->view('cms/media/ingresar', $datos);
				$this->load->view('cms/footer');
			}
			else
			{
				if ($datos = $this->Media_model->ingresarMedia($this->input->post()))
		        {
			        if(!empty($_FILES['imagen']['name']))
			        {
						$original = $this->Media_model->subirOriginal($id,'imagen'); 
						$imagen = $this->Media_model->subirImagen($id,'imagen');
					}
	
		            redirect(base_url('cms/media/'));
		        }
		        else
		        {
					$this->load->view('cms/header');
					$this->load->view('cms/media/detalle', $datos);
					$this->load->view('cms/footer');
			        echo 'Error';
		        }
			}
		}
		else
		{
			redirect(base_url('/cms/user/login/'));
		}
	}

	public function eliminar($id = NULL)
	{
		if ($this->is_logged_in())
		{
			if ($datos = $this->Media_model->eliminarMedia($this->input->post()))
	        {
				redirect(base_url('cms/media/'));
	        }
	        else
	        {
				$this->load->view('cms/header');
		        $this->load->view('cms/media/detalle', $datos);
		        echo 'Error';
				$this->load->view('cms/footer');
	        }
		}
		else
		{
			redirect(base_url('/cms/user/login/'));
		}
	}
}
