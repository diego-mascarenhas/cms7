<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Developers</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('developers'); ?>">Developers</a>
	                    </li>
	                    <li class="active">
	                        <strong>Tickets</strong>
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
	                            <h5>Obtención de Tickets</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p class="m-b-lg">Se pueden utilizar filtros como search, estado, order_by, order, page y per_page. <em>ej: /?search=fallidos</em></p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('tickets');
	
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
	                            <h5>Ingresar Ticket</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Si el ticket lo ingresa un usuario, el mismo Ticket queda asociado a él. Tampoco se permite especificar el estado y por defecto queda en Nuevo.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['id_servicio'] = null; // Opcional para asociarlo y ver el estado del servicio
	$variables['id_area'] = 2; // 1 => Gerencia, 2 => Administrativa, 3 => Comercial/Ventas, 4 => Técnica (Por defecto)  
	$variables['asunto'] = 'Ticket creado desde la API';
	$variables['prioridad'] = 2; // 1 => Normal (Por defecto), 2 => Alta, 3 => Urgente, 4 => Crítica
	 
	$variables['mensaje'] = 'Mensaje creado por <strong><?php echo $this->usuario->contacto; ?></strong>';
	$variables['id_empresa'] = <?php echo $this->usuario->id_empresa; ?>;
	
	$request = new CMS('tickets', 'POST', $variables);
	
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
	                            <h5>Modificar Ticket</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Modificación de tickets desde un formulario. Obtendremos la respuesta de lo sucedido, de la misma manera que el login. De la misma manera que el ingreso, si nuestro perfil es de <strong>Reseller</strong> podremos modificar tickets de las empresas de nuestro entorno.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['id_servicio'] = null; // Opcional para asociarlo y ver el estado del servicio
	$variables['id_area'] = 1; // 1 => Gerencia, 2 => Administrativa, 3 => Comercial/Ventas, 4 => Técnica (Por defecto)  
	$variables['asunto'] = 'Ticket creado desde la API (Modificado)';
	$variables['prioridad'] = 1; // 1 => Normal (Por defecto), 2 => Alta, 3 => Urgente, 4 => Crítica
	$variables['estado'] = 5; // 1 => Rechazado, 2 => Nuevo, 3 => Abierto, 4 => Esperando respuesta, 5 => Elevado al proveedor, 6 => Plan de acción, 7 => Cerrado
	
	$request = new CMS('tickets/1421', 'PUT', $variables);
	
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
	                            <h5>Responder Ticket</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['mensaje'] = 'Mensaje respondido por <strong><?php echo $this->usuario->contacto; ?></strong>';
	
	$request = new CMS('tickets/item/1432', 'POST', $variables);
	
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
	                            <h5>Detalle del Ticket</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Obtención del detalle completo de un Ticket.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('tickets/1421');
	
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