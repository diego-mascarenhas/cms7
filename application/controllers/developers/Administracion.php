<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Administracion extends MY_Controller {

	public function contactos()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/contactos');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function empresas()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/empresas');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function servicios()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/servicios');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function facturas()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/facturas');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function balance()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/balance');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function comunicaciones()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/comunicaciones');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function categorias_generales()
	{
		if ($this->is_logged_in('reseller'))
		{
			$header['css'] = array(	base_url('assets/css/plugins/codemirror/codemirror.css'),
									base_url('assets/css/plugins/codemirror/ambiance.css')
								);
			
			$this->load->view('/header', $header);
			$this->load->view('/developers/categorias_generales');
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


}