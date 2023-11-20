<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Firebase
{
	const version = "0.0.1";

	protected $server_key;
	protected $url;
	protected $notification;
	protected $sound;
	protected $badge;
	protected $priority;
	
	function __construct($config)
	{
		$this->server_key = $config['server_key'];
		$this->url = 'https://fcm.googleapis.com/fcm/send';
		$this->sound = 'default';
		$this->badge = '1';
		$this->priority = 'high';
	}
	
	function notificar($token, $notification)
	{
		$notification['sound'] = $this->sound;
		$notification['badge'] = $this->badge;
		
		$arrayToSend = array('to' => $token, 'data' => $notification, 'priority' => $this->priority); // Suena la alerta
		//$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high'); // Para al APP está abierta
		
		$data = json_encode($arrayToSend);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key=' . $this->server_key));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$res = curl_exec($curl);
		
		if ($res == false)
		{
		    $res['error'] = error_log("Oops! FCM Send Error: \"" . curl_error($curl) . "\" for $arrayToSend");
		}
		else
		{
			$res = json_decode($res, true);
		}
		
		curl_close($curl);
		
		return (!empty($res)) ? $res : null;
	}
	

}