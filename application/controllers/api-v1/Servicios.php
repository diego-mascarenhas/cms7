<?php defined('BASEPATH') or exit('No direct script access allowed');


require_once APPPATH . 'libraries/REST_Controller.php';

class Servicios extends REST_Controller {

	public function index_get($id = null)
	{
		$this->load->model('servicio_model');

		if ($id)
		{
			$data = $this->servicio_model->getServicioDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->servicio_model->getServicios($parametros);
		}
		
		if (!$data) $data['error'] = true;
		
		$this->response($data);
	}


	public function index_post()
	{
/*
		$data = new StdClass();
		$valores = $this->post();
		
		$this->load->model('servicio_model');
		$this->load->model('empresa_model');
		$this->load->model('categoria_model');

		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		$this->form_validation->set_data($valores);
		$this->form_validation->set_rules('id_categoria', 'ID de Categoria', array('required', array($this->categoria_model, 'validarPropiedadDeCategoria')));
		$this->form_validation->set_rules('id_empresa', 'ID de Empresa', array('required', array($this->empresa_model, 'validarPropiedadDeEmpresa')));

		if ($this->form_validation->run() == false)
		{
			$data->error = str_replace(array("\r", "\n"), '', validation_errors());
		}
		else
		{
			$data = $this->servicio_model->getCategoriaById($valores['id_categoria']);
		}
		
		if (!isset($data->error) && isset($data->id_tipo))
		{
			switch ($data->id_tipo)
			{
			case 1: // Hosting
				break;

			case 4: // Mailer
				$this->form_validation->set_rules('username', 'Usuario', 'required|min_length[5]|max_length[12]|is_unique[servicios_emailer.username]', array('required'=>'Debes proporcionar un usuario %s.', 'is_unique'=>'El %s ya existe'));
				$this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[5]');
				break;

			case 7: // SCTC
				$this->form_validation->set_rules('username', 'Usuario', 'required|min_length[5]|max_length[12]|is_unique[servicios_voip.username]', array('required'=>'Debes proporcionar un usuario %s.', 'is_unique'=>'El %s ya existe'));
				$this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[5]');
				$this->form_validation->set_rules('agente', 'Agente', 'required|numeric|min_length[7]|max_length[12]');
				break;

			default:
				$break;
			}

			if ($this->form_validation->run() == false)
			{
				$data->error = str_replace(array("\r", "\n"), '', validation_errors());
			}
		}

		if (!isset($data->error))
		{
			$this->db->trans_begin();

			$servicio = $this->servicio_model->ingresarServicio($valores);
			
			if (!isset($servicio->error))
			{
				$valores['id_servicio'] = $servicio->id;
	
				if (isset($data->id_tipo))
				{
					$valores['id_tipo'] = $data->id_tipo;
					
					if ($data->id_tipo == 4) $data = $this->servicio_model->ingresarServicioEmailer($valores);
					if ($data->id_tipo == 7) $data = $this->servicio_model->ingresarServicioVoip($valores);
				}
			}
			else
			{
				$data->error = $servicio->error;
			}

		}

		if (isset($data->error) || $this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();

			$data->id = $valores['id_servicio'];
		}

		$this->response($data);
		*/
	}


	public function index_put($id = null)
	{
/*
		$data = new StdClass();
		$valores = $this->put();
		
		if (empty($id))
		{
			$data->error = 'No se ha indicado el ID del servicio';
		}

		if (!isset($data->error))
		{
			$this->load->model('servicio_model');

			$data = $this->servicio_model->modificarServicio($id, $valores);
		}

		$this->response($data);
*/
	}


}