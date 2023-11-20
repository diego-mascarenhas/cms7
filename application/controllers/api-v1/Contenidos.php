<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Contenidos extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('contenido_model');

		if ($id)
		{
			$data = $this->contenido_model->getContenidoDetalleRaw($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->contenido_model->getContenidos($parametros);
		}

		$this->response($data);
	}


}