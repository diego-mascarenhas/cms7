<?php defined('BASEPATH') or exit('No direct script access allowed');


class Debug extends MY_Controller
{

	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['debug'] = $this->session->userdata();
			$data['debug']['FCPATH'] = FCPATH;
			$data['debug']['base_url'] = base_url();
			$data['debug']['SERVER_NAME'] = $_SERVER['SERVER_NAME'];
			$data['debug']['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
			$data['debug']['commit'] = $this->get_current_git_commit();

			$this->load->view('header');
			$this->load->view('debug', $data);
			$this->load->view('footer');

		}
		else
		{
			redirect(base_url('user/login'));
		}
	}


	public function phpinfo()
	{
		echo phpinfo();
	}


	public function curl()
	{
		$url = 'https://cms.rocoto.tv/api-v2/multimedia/media';
		$accesstoken = '';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('CMS-API-KEY: ' . $accesstoken));
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);

		echo '<pre>' . print_r(json_decode($data, true), true) . '</pre>';

	}


	public function user_agent()
	{
		// helpers and libraries
		$this->load->library('user_agent');

		echo '<pre>' . print_r($this->agent, true) . '</pre>';
	}


	function default_lang()
	{
		switch ($_SERVER['HTTP_ACCEPT_LANGUAGE'])
		{
			case 'en-us':
				$this->lang->load('cms', 'english');
				break;
			default:
				$this->lang->load('cms', 'spanish');
				break;
		}

		$this->load->view('header');
		$this->load->view('debug', $data);
		$this->load->view('footer');
	}


	function carpetas()
	{
		$data['path'] = FCPATH . 'multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa;


		$this->load->helper('directory');
		$data['map'] = directory_map($data['path'], FALSE);


		$this->load->helper('file');
		$data['info'] = get_dir_file_info($data['path']);


		if (!is_dir($data['path']))
		{
			if (!mkdir($data['path'], 0777, TRUE))
			{
				$data['error'] = 'No se puede crear la carpeta.';
			}
		}

		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	function tema($tema = null)
	{
		// models
		$this->load->model('user_model');

		$user = $this->user_model->getUserInfo($this->usuario->id);

		$user->tema = $tema;

		$this->session->set_userdata('usuario', $user);

		redirect(base_url('debug'));
	}


	function limpiar_dominio($url)
	{
		echo limpiarDominio($url);
	}


	public function cpanel_password_reset($user)
	{
		$valores['password'] = 'Passw0rd!';

		// models
		$this->load->model('hosting_model');

		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);

		$data = $this->cpanel->passwd($user, $valores);

		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function email_password_reset($user)
	{
		$valores['domain'] = 'nuevosite.com';
		$valores['email'] = 'webmaster@nuevosite.com';
		$valores['password'] = 'Passw0rd!';

		// models
		$this->load->model('hosting_model');

		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);

		$data = $this->cpanel->passwdpop($user, $valores);

		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function cambiar_plan($user)
	{
		$valores['plan'] = 'revision_enterprise';

		// models
		$this->load->model('hosting_model');

		// helpers and libraries
		$config = $this->hosting_model->getCredenciales($this->hosting_model->getServerIdFromUser($user));
		$this->load->library('Cpanel', $config);

		$data = $this->cpanel->changepackage($user, $valores);

		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function alertas()
	{
		// models
		$this->load->model('sys_model');
		$this->load->model('empresa_model');

		$this->sys_model->setAlerta('contacto', (!$this->session->userdata('usuario')->password) ? true : false);
		$this->sys_model->setAlerta('empresa', (!$this->session->userdata('usuario')->id_empresa || $this->empresa_model->verificarDatosDeLaEmpresaIncompletos($this->usuario->id_empresa)) ? true : false);
		$this->sys_model->setAlerta('saldo', $this->empresa_model->getEmpresaSaldo($this->usuario->id_empresa)['saldo']);

		echo '<pre>' . print_r($this->session->userdata('alertas'), true) . '</pre>';
	}


	public function ssh($grupo, $id_empresa, $uid, $tipo = null)
	{
		$this->load->helper('file');

		$data = '<?xml version="1.0" encoding="UTF-8"?>
<smil title="Stream">
	<body>
		<switch>
			<video height="240" src="' . $uid . '_LOW.mp4" systemLanguage="eng" width="424" title="Baja">
				<param name="videoBitrate" value="450000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
			<video height="360" src="' . $uid . '_MID.mp4" systemLanguage="eng" width="640" title="Media">
				<param name="videoBitrate" value="750000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
			<video height="720" src="' . $uid . '_HQ.mp4" systemLanguage="eng" width="1272" title="Alta">
				<param name="videoBitrate" value="1100000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
		</switch>
	</body>
</smil>';

		if (!write_file(FCPATH . 'multimedia/stream/' . $uid . '.smil', $data))
		{
			echo 'No se pudo crear el smil';
		}
		else
		{
			echo 'Smil creado!';
		}

		$server = '10.0.0.6';
		$user = 'root';
		$pass = 'el22lope';

		$connection = ssh2_connect($server, 22);
		ssh2_auth_password($connection, $user, $pass);

		switch ($tipo)
		{
			case ('low'):
				$cmd = 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 1024k -bufsize 1M /mnt/multimedia/stream/' . $uid . '_LOW.mp4';
				break;
			case ('mid'):
				$cmd = 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 2048k -bufsize 1M /mnt/multimedia/stream/' . $uid . '_MID.mp4';
				break;
			case ('hq'):
				$cmd = 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 4096k -bufsize 1M /mnt/multimedia/stream/' . $uid . '_HQ.mp4';
				break;
			default:
				$cmd = 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -vf scale=320:-1 -b 128k -bufsize 1M -ss 10 -t 30 /mnt/multimedia/preview/' . $uid . '.mp4';
				break;
		}

		$stream = ssh2_exec($connection, $cmd);

		$res = stream_get_contents($stream);

		echo '<pre>' . print_r($res, true) . '</pre>';
	}


	function ffmpeg($grupo, $id_empresa, $uid)
	{
		$this->load->helper('file');

		$data = '<?xml version="1.0" encoding="UTF-8"?>
<smil title="Stream">
	<body>
		<switch>
			<video height="720" src="' . $uid . '_HQ.mp4" systemLanguage="eng" width="1272" title="Alta">
				<param name="videoBitrate" value="1100000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
			<video height="360" src="' . $uid . '_MID.mp4" systemLanguage="eng" width="640" title="Media">
				<param name="videoBitrate" value="750000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
			<video height="240" src="' . $uid . '_LOW.mp4" systemLanguage="eng" width="424" title="Baja">
				<param name="videoBitrate" value="450000" valuetype="data"></param>
				<param name="audioBitrate" value="44100" valuetype="data"></param>
			</video>
		</switch>
	</body>
</smil>';

		if (!write_file(FCPATH . 'multimedia/stream/' . $uid . '.smil', $data))
		{
			echo 'No se pudo crear el smil';
		}
		else
		{
			echo 'Smil creado!';
		}


		// Procesar
		$cmd = '/usr/bin/ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -vf scale=320:-1 -b 128k -bufsize 1M -ss 10 -t 60 /mnt/multimedia/preview/' . $uid . '.mp4
		';

		$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 1024k -bufsize 1M -ss 10 -t 60 /mnt/multimedia/stream/' . $uid . '_LOW.mp4
		';
		$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 2048k -bufsize 1M -ss 10 -t 60 /mnt/multimedia/stream/' . $uid . '_MID.mp4
		';
		$cmd .= 'ffmpeg -y -i /mnt/multimedia/' . $grupo . '/' . $id_empresa . '/' . $uid . '.mp4 -b 4096k -bufsize 1M -ss 10 -t 60 /mnt/multimedia/stream/' . $uid . '_HQ.mp4
		';

		if (!write_file(FCPATH . 'multimedia/procesar/' . $uid, $cmd))
		{
			echo 'No se pudo crear el archivo de procesos';
		}
		else
		{
			echo 'Archivo de procesos creado!';
		}
	}


	public function firebase()
	{
		// helpers and libraries
		//$config['server_key'] = 'AIzaSyBJ44uRirarZnsYl0GOjMXd6T20aEd4CcE';
		//$this->load->library('Firebase', $config);

		$this->load->config('firebase');
		$this->load->library('Firebase', $this->config->item('firebase'));

		//$token = 'fpnfxDuiS6Q:APA91bE0NPdDb6Z3Ifmrvg_-9Os_5E9mghZ6uCuzCW04xGFakTYOOXCFhGSqKk6mCOTk-QnLp_014QYDdR9Y6RleW5WvIVtZbfL4bR9vhG4w78kRpOjz9qAaOwWseiNxaSsYq4SDmsMe'; // Diego

		$token = 'eBAnLS50tSQ:APA91bFBfBNY76sYUVIlofi2nksx8NGlejpUqUCT5ngvdobKO3IPlmuF6fmYy3kpirjmfYNHiYlzeYVHT-vuyEjhWPI9PLy9QV4HWbMIXNqXrCbYajF8wHy17V3dumrddBERN7Td_QA7'; // Pablo

		//$token = 'cKeHyaHB9X8:APA91bGb6OA3ZQQdBrDvMiArL9LTATytx5huTcH8k38HXzE3DRXY2tPeeZEE2Hp8_Ze86iBH_v79CSWyFMcdgT4_MdNCwFxVEegOUwjMZp8Rhf7g2QzAms6TPVc6BUWsswe5cmITdbza'; // Barbie

		//$token = 'cxbRRf-Q1uI:APA91bEaAzsGlm9rQivGJQcKPRvANSOuyMSRn3LW2TNoNWeWj4xSDfYHheV0EUEVamNvURiJZRQNGENzM0ovX4LqJkMLuwb8JjWOyek9zshuuxuOx2n_fo5lA9poD4PpRx-zTVB15H8R'; // Mauro

		$valores['id_tipo'] = '27';
		$valores['title'] = 'Prueba';
		$valores['body'] = 'Mensaje tipo: ' . $valores['id_tipo'];
		$valores['id_referencia'] = '666';

		$data = $this->firebase->notificar($token, $valores);

		echo '<pre>' . print_r($data, true) . '</pre>';
	}


	public function dispositivos()
	{
		/*
				// models
				$this->load->model('app_model');
					
				$data = $this->app_model->getDispositivos(1, 475);
				
				echo '<pre>' . print_r($data, true) . '</pre>';
		*/
		// models
		$this->load->model('app_model');

		// helpers and libraries
		$this->load->config('firebase');
		$this->load->library('Firebase', $this->config->item('firebase'));


		$dispositivos = $this->app_model->getDispositivos(1, 475);

		if ($dispositivos)
		{
			$valores['id_tipo'] = '26';
			$valores['title'] = 'Nuevo ticket';
			$valores['body'] = 'Prueba';
			$valores['id_referencia'] = '7089';

			foreach ($dispositivos as $obj)
			{
				$push = $this->firebase->notificar($obj['token'], $valores);

				//if ($push['failure'] && $push['results'][0]['error'] == 'NotRegistered') $this->app_model->desactivarDispositivo(1, $obj['token']);
				if ($push['failure'])
					$this->app_model->desactivarDispositivo(1, $obj['token']);

				echo '<pre>' . print_r($push, true) . '</pre>';
			}
		}
	}


	public function json()
	{
		$array = utf8_encode('Atención lésbica, Bailes eróticos, Besos, Despedidas, Disfraces, Oral Americana, Oral Natural, Masaje Tántrico, Masturbación Rusa');

		$valores = explode(',', $array);


		echo json_encode($valores);
	}


	public function escribir_archivo()
	{
		// helpers and libraries
		$this->load->helper('file');


		if (!write_file(FCPATH . 'tests/archivos/template.php', 'Prueba de escritura'))
		{

			echo 'Ha habido un problema y no se pudo crear el template, por favor intenta más tarde';
		}
		else
		{
			echo 'Contenido del archivo: ' . read_file(FCPATH . 'tests/archivos/template.php');
		}
	}


	public function corregir_empresas_fiscales()
	{
		// models
		$this->load->model('empresa_model');


		$parametros['per_page'] = 9999999;

		$data['empresas'] = $this->empresa_model->getEmpresas($parametros);

		foreach ($data['empresas'] as $obj)
		{
			$row = $this->empresa_model->getDatosFiscalesDetalle($this->empresa_model->getDatosFiscalesIdFromIdEmpresa($obj['id']));

			if (isset($row['cuit']))
			{
				$valores = $row;
				$valores['cuit'] = preg_replace('/\D/', '', $row['cuit']);

				$this->empresa_model->modificarEmpresaFiscal($row['id'], $valores);

				echo '<pre>' . print_r($valores, true) . '</pre>';
			}
		}
	}


	/**
	 * Get the hash of the current git HEAD
	 * @param str $branch The git branch to check
	 * @return mixed Either the hash or a boolean false
	 */
	function get_current_git_commit($branch = 'master')
	{
		if ($hash = file_get_contents(sprintf('.git/refs/heads/%s', $branch)))
		{
			return $hash;
		}
		else
		{
			return false;
		}
	}


}