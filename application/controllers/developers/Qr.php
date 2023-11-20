<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Qr extends MY_Controller {

	public function index()
	{
		// https://www.desarrollolibre.net/blog/codeigniter/generando-codigos-qr-con-codeigniter
		
		$this->load->library('ciqrcode');
		
		header('Content-Type: image/png');
		
		$params['data'] = 'Pablito se la come!';
		$params['level'] = 'H';
		$params['size'] = 1024;
		
		$this->ciqrcode->generate($params);
	}
	
	
	public function test()
	{
		$this->load->library('ciqrcode');
		
		$config['cacheable']	= true; //boolean, the default is true
		$config['cachedir']		= ''; //string, the default is application/cache/
		$config['errorlog']		= ''; //string, the default is application/logs/
		$config['quality']		= true; //boolean, the default is true
		$config['size']			= '1024'; //interger, the default is 1024
		$config['black']		= array(224,255,255); // array, default is array(255,255,255)
		$config['white']		= array(70,130,180); // array, default is array(0,0,0)
		
		$res = $this->ciqrcode->initialize($config);
		
		echo '<pre>' . print_r($res, true) . '</pre>';
	}

	
	function generar_qr($user_id)
	{
		//hacemos configuraciones
		$params['data'] = $user_id;
		$params['level'] = 'H';
		$params['size'] = 10;

		//decimos el directorio a guardar el codigo qr, en este 
		//caso una carpeta en la raíz llamada qr_code
		$params['savename'] = FCPATH . "uploads/qr_code/qr_$user_id.png";
		//generamos el código qr
		$this->ciqrcode->generate($params);

		$data['img'] = "qr_$user_id.png";

		$this->load->view('admin/header');
		$this->load->view('admin/codigo_qr', $data);
		$this->load->view('footer');
	}
}