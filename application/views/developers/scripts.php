<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Developers</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Developers</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('developers/descargar/'); ?>" class="btn btn-primary btn-sm">Descargar SDK</a>
                    </div>
                </div>
	        </div>


		    <div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Login - PHP</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg"> Podemos consultar si el usuario se encuentra activo en CMS+ para interactuar con nuestro sistema local. Luego guardamos las variables en una sesión para interactuar con el resto de los servicios.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('login');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	
	$data = json_decode($request->getResponseBody());

	if (isset($data->id))
	{
		$_SESSION['username'] = $data->username;
		$_SESSION['password'] = $data->password;
                
		header('location: /micuenta/');
	}
	else
	{
		header('location: /login/');
	}
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Login - Ajax</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Podemos hacer lo mismo pero en este caso, hacer una devolución por Ajax. Si por algún motivo el usuario no tiene acceso, esto será devuelto en la variable "error" desde la API.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('login');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	
	$data = (array) json_decode($request->getResponseBody());

	if (isset($data['id']))
	{
		$_SESSION['username'] = $data['username'];
		$_SESSION['password'] = $data['password'];

		$data['ok'] = 'Ya estás conectado!';
	}
	elseif (!isset($data['error']))
	{
		$data['error'] = 'Ha habido un inconveniente, por favor prueba más tarde.';
	}
	
	echo json_encode($data);
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Login - HTTP_AUTH_MODE</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg"> Podemos traer más información del usuario como los servicios, créditos del Mailer u otros.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('login');
	
	$request->setAuthMode('servicios');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	
	$data = json_decode($request->getResponseBody());

	if (isset($data->id))
	{
		$_SESSION['username'] = $data->username;
		$_SESSION['password'] = $data->password;
                
		header('location: /micuenta/');
	}
	else
	{
		header('location: /login/');
	}
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	        </div>


			<!-- CodeMirror -->
			<script src="<?php echo base_url('assets/js/plugins/codemirror/codemirror.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/codemirror/mode/javascript/javascript.js'); ?>"></script>

		    <script>
		         $(document).ready(function(){
		            
		            $('.code').each(function(index) {
		                $(this).attr('id', 'code-' + index);
		                CodeMirror.fromTextArea(document.getElementById('code-' + index), {
		                        lineNumbers: true,
		                        matchBrackets: true,
		                        styleActiveLine: true,
				                readOnly: true
		                    }
		                );
		            });

		        });
		    </script>