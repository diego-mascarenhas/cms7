<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Categorias_generales extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('categorias_generales_model');
		
		if ($id)
		{
			$data = $this->categorias_generales_model->getCategoriaGeneralDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			$parametros['id_tipo'] = $this->input->get('id_tipo');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->categorias_generales_model->getCategoriasGenerales($parametros);
		}

		$this->response($data);
	}


}