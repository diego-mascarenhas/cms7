<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Newsletters</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('developers'); ?>">Developers</a>
	                    </li>
	                    <li class="active">
	                        <strong>Newsletters</strong>
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
	                            <h5>Obtención de Newsletters</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p class="m-b-lg">Se pueden utilizar filtros como search, estado, order_by, order, page y per_page. <em>ej: /?estado=3</em></p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('newsletters');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	                
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Ingresar Newsletter</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Ingreso de newsletter desde un formulario..........</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['asunto'] = 'Mensaje creado desde la API';
	$variables['remitente'] = 'CMS Tester';
	$variables['email'] = 'mkt@revisionalpha.com';
	$variables['email_respuesta'] = null;
	 
	$variables['mensaje'] = 'Mensaje creado por <strong><?php echo $this->usuario->contacto; ?></strong>';
	
	$request = new CMS('newsletters', 'POST', $variables);
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Modificar Newsletter</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Modificación de Newsletters.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['asunto'] = 'Mensaje modificado desde la API';
	$variables['remitente'] = 'CMS Tester INC.';
	$variables['email'] = 'mkt@revisionalpha.com';
	$variables['email_respuesta'] = null;
	 
	$variables['mensaje'] = 'Mensaje creado por <strong>Diego Mascarenhas</strong>';
	
	$variables['estado'] = 4;
	
	$request = new CMS('newsletters/7', 'PUT', $variables);
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Detalle del Newsletter</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Obtención del detalle completo de un News.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('newsletters/7');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
	
	echo '<pre>' . print_r($data, true) . '</pre>'; // Object
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