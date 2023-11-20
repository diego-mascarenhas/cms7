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
	                        <strong>Contactos</strong>
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
	                            <h5>Obtención de Contactos</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p class="m-b-lg">Se pueden utilizar filtros como search, estado, order_by, order, page y per_page. <em>ej: /?search=diego&page=2&per_page=5</em></p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('contactos');
	
	$request->setUsername('<?php echo $this->usuario->username; ?>');
	$request->setPassword('<?php echo $this->usuario->password; ?>');
	
	$request->execute();
	
	$data = json_decode($request->getResponseBody());
	
	echo '<pre>' . print_r($data, true) . '</pre>'; // Array
</textarea>

	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Ingresar Contacto</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Ingreso de contacto desde un formulario..........</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['id_empresa'] = null;
	$variables['nombre'] = 'CMS'; // Obligatorio
	$variables['apellido'] = null; // Puede ser nulo
	$variables['sexo'] = null; // M => Masculino, F => Femenino
	$variables['email'] = 'info@tester.com'; // Obligatorio
	$variables['telefono'] = null;
	$variables['celular'] = null;
	
	$variables['area_privada'] = 3; // 3 => Administrador, 4 => Usuario, 5 => Invitado
	$variables['username'] = null;
	$variables['password'] = null;
	$variables['timezone'] = null; // https://www.codeigniter.com/user_guide/helpers/date_helper.html
	$variables['idioma'] = null; // es_AR, en_US
	
	$variables['estado'] = 6; // 1 => Inactivo, 2 => Activo, 3 => Online, 4 => Bloqeuado, 5 => Vencido, 6 => Prospecto (Cuando se ingresa como prospecto pide la confirmación por email)
	 	
	$request = new CMS('contactos', 'POST', $variables);
	
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
	                            <h5>Modificar Contacto</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Modificación de Contacto.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['apellido'] = 'Tester';
	$variables['sexo'] = 'M'; // M => Masculino, F => Femenino
	$variables['telefono'] = '1234-5678';
	$variables['celular'] = '15 1234-5678';
	
	$request = new CMS('contactos/42839', 'PUT', $variables);
	
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
	                            <h5>Detalle del Contacto</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Obtención del detalle completo de un Contacto.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('contactos/42306');
	
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