<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['smtp'] = array('protocol' => 'smtp',
						'smtp_host' => 'mail.revisionalpha.com',
						'smtp_port' => 587,
						'smtp_crypto' => 'tls',
						'smtp_user' => 'administracion@revisionalpha.com',
						'smtp_pass' => 'vRptd2rqBn1',
						'mailtype' => 'html',
						'charset' => 'UTF-8',
						'wordwrap' => FALSE,
						'crlf' => "\n",
						'newline' => "\r\n",
						'encoding' => 'base64',
						'nodebug' => false,
						'isHtml' => TRUE,
						'priority' => 3,
						'content_type' => 'text/html',
						'altText' => false
						);
