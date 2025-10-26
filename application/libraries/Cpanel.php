<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Cpanel
{
	const version = "0.0.1";

	private $ip;
	private $user;
	private $pass;
	
	function __construct($config)
	{
		$this->ip = $config['ip'];
		$this->user = $config['user'];
		$this->pass = $config['pass'];
	}
	
	function conectar($query)
	{
		$curl = curl_init();                                // Create Curl Object
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);      // Allow self-signed certs
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);      // Allow certs that do not match the hostname
		curl_setopt($curl, CURLOPT_HEADER, 0);              // Do not include header in output
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);      // Return contents of transfer on curl_exec
		$header[0] = "Authorization: Basic " . base64_encode($this->user . ":" . $this->pass) . "\n\r";
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);    // set the username and password
		curl_setopt($curl, CURLOPT_URL, 'https://' . $this->ip . ':2087' . $query);            // execute the query
		$res = curl_exec($curl);
		
		if (isset($data['cpanelresult']['error'])) // 2020-07-25
		{
			// $res['error'] = error_log("curl_exec threw error \"" . curl_error($curl) . "\" for $query"); // log error if curl exec fails
			$res['error'] = $data['cpanelresult']['error'];
		}
		else
		{
			$res = json_decode($res, true);
		}
		
		curl_close($curl);
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function createacct($dominio, $plan, $email)
	{
		$valores['dominio'] = strtolower(str_replace(array('http://', 'https://'), '', $dominio));
		$dominio_explode = explode('.', $valores['dominio']);
		$valores['user'] = substr(preg_replace( '/\W||\d/', '', $dominio_explode[0]), 0, 6);
		$valores['user'] .= (count($dominio_explode) == 2) ? $dominio_explode[1] : $dominio_explode[2];
		$valores['pass'] = substr(md5($valores['user']), 0, 6) . '*2002!';
		$valores['contactemail'] = $email;
		$valores['plan'] = $plan;
		
		$query = '/json-api/createacct?username=' . $valores['user'] . '&domain=' .  $valores['dominio'] . '&plan=' . $valores['plan'] . '&contactemail=' . $valores['contactemail'] . '&password=' . $valores['pass'];
		
		$data = $this->conectar($query);
		
		if (isset($data['cpanelresult']['error']))
		{
			$res['error'] = $data['cpanelresult']['error'];
		}
		elseif ($data['result'][0]['status'] != 1)
		{
			$res['error'] = $data['result'][0]['statusmsg'];
		}
		else
		{
			$res = $valores;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function accounts()
	{
		$query = '/json-api/listaccts';
		
		$data = $this->conectar($query);
		
		if ($data['status'] != 1)
		{
			$res = $data['status'];
		}
		else
		{
			$res = $data['acct'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function accountsummary($user) // 2020-07-25
	{
		$query = '/json-api/accountsummary?user=' . $user;
		
		$data = $this->conectar($query);

		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		elseif (isset($data['acct'][0]))
		{
			$res = $data['acct'][0];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function stats($user)
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_module=StatsBar&cpanel_jsonapi_func=stat&display=diskusage|bandwidthusage';
		
		$res = $this->conectar($query);
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function diskusage($user) // 2020-07-25
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_module=StatsBar&cpanel_jsonapi_func=stat&display=diskusage';
		
		$data = $this->conectar($query);

		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		elseif (isset($data['cpanelresult']['error']))
		{
			$res['error'] = $data['cpanelresult']['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'][0];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function bandwidthusage($user) // 2020-07-25
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_module=StatsBar&cpanel_jsonapi_func=stat&display=bandwidthusage';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		elseif (isset($data['cpanelresult']['error']))
		{
			$res['error'] = $data['cpanelresult']['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'][0];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
/*
	function passwd($user, $valores)
	{
		// https://hostname.example.com:2087/cpsess##########/json-api/passwd?api.version=1&user=username&password=12345luggage&enabledigest=1
		
		$query = '/json-api/passwd?api.version=1&user=' . $user . '&password=' . $valores['password'];
		
		$data = $this->conectar($query);
		
		if ($data['metadata']['result'] != 1)
		{
			$res['error'] = $data['metadata']['reason'];
		}
		else
		{
			$res = $valores;
		}
		
		return (!empty($res)) ? $res : null;
	}
*/
	
	
	function passwdpop($user, $valores)
	{
		// https://hostname.example.com:2087/cpsess##########/json-api/cpanel?cpanel_jsonapi_user=user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Email&cpanel_jsonapi_func=passwdpop&domain="example.com"&email="user"&password="12345luggage"
		
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Email&cpanel_jsonapi_func=passwdpop&domain=' . $valores['domain'] . '&email=' . $valores['email'] . '&password=' . $valores['password'];
		
		$data = $this->conectar($query);
		
		if ($data['cpanelresult']['data'][0]['result'] != 1)
		{
			$res['error'] = $data['cpanelresult']['data'][0]['reason'];
		}
		else
		{
			$res = $valores;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function changepackage($user, $valores)
	{
		// https://hostname.example.com:2087/cpsess##########/json-api/changepackage?api.version=1&user=username&pkg=package1
		
		$query = '/json-api/changepackage?api.version=1&user=' . $user . '&pkg=' . $valores['plan'];
		
		$data = $this->conectar($query);
		
		if ($data['metadata']['result'] != 1)
		{
			$res['error'] = $data['metadata']['reason'];
		}
		else
		{
			$res = $valores;
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function bandwidtPorMes($user)
	{
		// https://hostname.example.com:2087/cpsess###########/json-api/cpanel?cpanel_jsonapi_user=user&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Stats&cpanel_jsonapi_func=getthismonthsbwusage
		
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Stats&cpanel_jsonapi_func=getmonthlybandwidth';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		else
		{
			//$res = $data['cpanelresult']['data'][0];
			$res = $data;
		}
		
		return (!empty($res)) ? $res : null;
	}


	function lveinfo($user)
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=LVEInfo&cpanel_jsonapi_func=getUsage';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function listpops($user)
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Email&cpanel_jsonapi_func=listpops&api2_sort=1&api2_sort_column=email&api2_sort_method=lexicographic';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res = $data['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	

	function listpopswithdisk($user)
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Email&cpanel_jsonapi_func=listpopswithdisk&api2_sort=1&api2_sort_column=email&api2_sort_method=lexicographic';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res = $data['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function spf($user)
	{
		$query = '/json-api/cpanel?cpanel_jsonapi_user=' . $user . '&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=SPFUI&cpanel_jsonapi_func=get_raw_record';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res = $data['error'];
		}
		else
		{
			$res = $data['cpanelresult']['data'][0];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function activar($user)
	{	
		$query = '/json-api/unsuspendacct?user=' . $user;
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		else
		{
			$res = $data['result'][0];
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
	function suspender($user)
	{	
		$query = '/json-api/suspendacct?user=' . $user . '&reason=cron';
		
		$data = $this->conectar($query);
		
		if (isset($data['error']))
		{
			$res['error'] = $data['error'];
		}
		else if (isset($data['result']) && is_array($data['result']) && !empty($data['result']))
		{
			$res = $data['result'][0];
		}
		else if (isset($data['status']) && $data['status'] === 0 && isset($data['statusmsg']))
		{
			// Pass through the actual error message from the API
			$res['status'] = 0;
			$res['statusmsg'] = $data['statusmsg'];
		}
		else
		{
			// Provide a default response if 'result' is not in the expected format
			$res['status'] = 0;
			$res['statusmsg'] = 'Unknown response format from cPanel API';
			if (isset($data)) {
				$res['raw_response'] = $data;
			}
		}
		
		return (!empty($res)) ? $res : null;
	}
	
	
}