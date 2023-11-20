<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Comunicaciones</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('developers'); ?>">Developers</a>
	                    </li>
	                    <li class="active">
	                        <strong>Comunicaciones</strong>
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
	                            <h5>Obtención de Comunicaciones</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p class="m-b-lg">Se pueden utilizar filtros como search, estado, order_by, order, page y per_page. <em>ej: /?order_by=asunto&order=DESC</em></p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('comunicaciones');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Ingreso de Comunicaciones</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Deberá estar creada la plantilla (comunicaciones_templates) según el idioma del contacto.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['id_contacto'] = ID; // Obligatorio
	$variables['id_tipo'] = null; // Obligatorio / 1 => Nueva factura, 2 => Aviso de vencimiento, 3 => Aviso de segundo débito, 4 => Aviso de factura vencida, 5 => Notificación de suspensión de servicios, 6 => Aviso de datos de perfil incompletos, 7 => Suspensión de servicios por datos de perfil incompletos, 8 => Confirmación de registro de usuario, 9 => Registro de usuario, 10 => Confirmación de recupero de contraseña, 11 => Recupero de contraseña, 12 => Solicitud de alta de servicio, 13 => Alta de servicio, 14 => Notificación de alta de servicio, 15 => Presupuesto
	$variables['enviar'] = true; // Si enviamos esta variable el sistema creará la comunicación y la enviará automáticamente
	
	$request = new CMS('comunicaciones, 'POST', $variables);
	
	// Es necesario usar credenciales de resller
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
	                            <h5>Detalle de la comunicación</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Obtención del detalle completo de la comunicación.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('comucicacion/ID');
	
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