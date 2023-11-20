<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Share extends MY_Controller {	
	
	public function media($id)
	{
		if ($this->is_logged_in() && $id)
		{
			// models
			$this->load->model('media_model');
			
			// app parameters
			$this->load->config('media');
			
			$data = $this->media_model->getMediaFromUid($id);
			
			if ($data['id_empresa'] == $this->usuario->id_empresa)
			{
				$data['media_path'] = $this->config->item('media_path');
				$data['file_path'] = $data['media_path'] . '/' . $data['user_id'] . $data['file_path'] . $data['file_name'];
				
				$img = fopen('./' . $data['file_path'], 'rb');
				
				if ($img)
				{
				    header('Content-Type: ' . $data['file_type']);
				    fpassthru($img);
				    exit;
				}
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
			}
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
		}
	}
	
	
	public function play($id = null)
	{
		if ($this->is_logged_in() && $id)
		{
			// models
			$this->load->model('media_model');
			
			// app parameters
			$this->load->config('media');
			
			$data['detalle'] = $this->media_model->getMediaFromUid($id);
			
			if ($data['id_empresa'] == $this->usuario->id_empresa)
			{
				$data['detalle']['media_path'] = $this->config->item('media_path');
				
				$this->load->view('media/share', $data);
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
			}
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
		}
	}
	
	
	public function download($id = null)
	{
		if ($this->is_logged_in() && $id)
		{
			// models
			$this->load->model('media_model');
			
			// app parameters
			$this->load->config('media');
			
			$data = $this->media_model->getMediaFromUid($id);
			
			if ($data['id_empresa'] == $this->usuario->id_empresa)
			{
				$data['media_path'] = $this->config->item('media_path');
				$data['file_path'] = $data['media_path'] . '/' . $data['user_id'] . $data['file_path'] . $data['file_name'];
	
				header('Content-type: ' . $data['file_type']);
				header('Content-Disposition: attachment; filename="' . $data['file_name'] . '"');
				readfile($data['file_path']);
			}
			else
			{
				header('HTTP/1.0 404 Not Found');
			}
		}
		else
		{
			header('HTTP/1.1 403 Forbidden');
		}
	}
	
	
	public function stream($id = null)
	{
		$data = '#EXTM3U
		';
		
		if ($id) // http://cms.rocoto.tv/share/stream/MACADDRESS
		{
			// models
			$this->load->model('stream_model');
			
			$res = $this->stream_model->stream($id);
			
			if ($res)
			{
				foreach ($res as $obj)
				{
					$data .= $obj['url'] . '
					';
				}
			}
			else
			{
				$data .= 'https://cms.rocoto.tv/multimedia/506/1/Lucio/Rocotologo.jpg
				';
			}
		}
		else
		{
			$data .= 'https://cms.rocoto.tv/multimedia/506/1/Lucio/Rocotologo.jpg
			'; // https://cms.rocoto.tv/multimedia/506/17/MICRO_WALKMAN_01.mp4

		}
		
		echo $data;
	}
		
		
}