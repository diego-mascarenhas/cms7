<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Multimedia extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        $this->load->model('multimedia_model');
        
        $this->usuario = $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    

    public function media_get($id = null)
    {
	    if ($id)
		{
			$id = (int) $id;

	        if ($id <= 0)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->multimedia_model->getMediaDetalle($id);
				
				if ($data['tipo'] == 'video')
				{
					$this->load->helper('file');
					$data['path'] = FCPATH . 'multimedia/';
					
					if ($data['stream'] == 3 && !(file_exists($data['path'] . 'procesar/' . preg_replace('/.[^.]*$/', '', $data['archivo']))))
					{
						$data['video'] = 'https://player.revisionalpha.com/streamcms/_definst_/' . preg_replace('/.[^.]*$/', '', $data['archivo']) . '.smil/playlist.m3u8';
					}
					else
					{
						$data['info'] = get_file_info($data['path'] . 'preview/' . preg_replace('/.[^.]*$/', '', $data['archivo']) . '.mp4');
			
						if ($data['info'])
						{
							$data['video'] = 'https://player.revisionalpha.com/vodcms/mp4:multimedia/preview/' . $data['info']['name'] . '/playlist.m3u8';
							
							$data['preview']['size'] = $data['info']['size'];
						}
						else
						{
							$data['video'] = 'https://player.revisionalpha.com/vodcms/mp4:multimedia/' . $data['grupo'] . '/' . $data['id_empresa'] . '/' . $data['archivo'] . '/playlist.m3u8';
						}
					}
				}
				
				elseif ($data['tipo'] == 'imagen')
				{
					$data = array_replace($data, array('archivo' => base_url('multimedia/' .  $data['grupo'] . '/' . $data['id_empresa'] . '/' . $data['archivo'])));
				}
				
				$data['thumb'] = (isset($data['thumb'])) ? base_url('multimedia/thumbs/' . $data['thumb']) : null;
			}
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			$parametros['proyecto'] = $this->input->get('proyecto');
			$parametros['tag'] = $this->input->get('tag');
			
			if ($this->input->get('id_empresa')) $parametros['id_empresa'] = $this->input->get('id_empresa');
			
			$parametros['per_page'] = 9999;
			
			if ($medias = $this->multimedia_model->getMedias($parametros))
			{
				foreach ($medias as $obj)
				{
					$data[] = array_replace($obj, array('thumb' => (isset($obj['thumb'])) ? base_url('multimedia/thumbs/' . $obj['thumb']) : null));
				}
			}
		}
		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }
    

    public function proyectos_get($id = null)
    {
	    if ($id)
		{
			$id = (int) $id;

	        if ($id <= 0)
	        {
	            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
	        }
	        else
	        {
				$data = $this->multimedia_model->getProyectoDetalle($id);
			}
		}
		else
		{
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			$parametros['tag'] = $this->input->get('tag');
			$parametros['padre'] = $this->input->get('padre');
			
			if ($this->input->get('id_empresa')) $parametros['id_empresa'] = $this->input->get('id_empresa');
			
	        $data = $this->multimedia_model->getProyectos($parametros);
		}
		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

}
