<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Qr extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('tienda_model');
	}


	public function index($id, $menu = null)
	{
		if ($this->is_logged_in())
		{
			$row = $this->tienda_model->qr($id);
			
			$this->load->library('ciqrcode');
			
			header('Content-Type: image/png');
			
			if ($menu == true)
			{
				$params['data'] = $row['url'] . $row['titulo'] . '?tipo=menu';
			}
			else
			{
				$params['data'] = $row['url'] . $row['titulo'];
			}
			
			$params['level'] = 'H';
			$params['size'] = 1024;
			
			$this->ciqrcode->generate($params);
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	
	public function menu($id)
	{
		if ($this->is_logged_in())
		{
			$row = $this->tienda_model->qr($id);
			
			$row['base_url'] = base_url();
			$url = base_url('templates/' . $row['grupo'] . '/qr/menu.php');
	
			$this->load->library('curl');
			echo $this->curl->simple_post($url, $row);
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	
	public function pedidos($id)
	{
		if ($this->is_logged_in())
		{
			$row = $this->tienda_model->qr($id);
			
			$row['base_url'] = base_url();
			$url = base_url('templates/' . $row['grupo'] . '/qr/pedidos.php');
	
			$this->load->library('curl');
			echo $this->curl->simple_post($url, $row);
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	
}