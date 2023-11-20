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
	                        <strong>Empresas</strong>
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
	                            <h5>Obtención de Empresas</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p class="m-b-lg">Se pueden utilizar filtros como search, estado, order_by, order, page y per_page. <em>ej: /?page=2&per_page=15</em></p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('empresas');
	
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
	                            <h5>Ingresar Empresa</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Ingreso de empresa desde un formulario..........</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['empresa'] = 'Empresa creada desde la API';
	$variables['id_categoria'] = null; // Buscar ID de categoría
	$variables['referido'] = ''; // ID del la empresa referida
	$variables['telefono'] = '6632-2134';
	$variables['web'] = 'https://www.revisionalpha.com';
	$variables['domicilio'] = '';
	$variables['id_localidad'] = '';
	$variables['obervaciones'] = '';
	$variables['estado'] = 3; // 1 => Inactivo, 2 => Activo, 3 => Prospecto, 4 => Nuevo (Por defecto), 5 => Asignado, 6 => Contactado, 7 => Esperando Respuesta
	 	
	$request = new CMS('empresas', 'POST', $variables);
	
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
	                            <h5>Modificar Empresa</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Modificación de Empresa.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['empresa'] = 'Empresa modificada desde la API';
	$variables['id_categoria'] = ''; // Buscar ID de categoría
	$variables['telefono'] = ''; // Para borrar el teléfono
	$variables['web'] = 'https://www.revisionalpha.com.ar';
	$variables['estado'] = 2; // 1 => Inactivo, 2 => Activo, 3 => Prospecto, 4 => Nuevo (Por defecto), 5 => Asignado, 6 => Contactado, 7 => Esperando Respuesta
	 		
	$request = new CMS('empresas/7023', 'PUT', $variables);
	
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
	                            <h5>Detalle de Empresa</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Obtención del detalle completo de una Empresa.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('empresas/253');
	
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
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Obtención de los datos de Mi Cuenta</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$request = new CMS('micuenta');
	
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
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox ">
	                        <div class="ibox-title">
	                            <h5>Modificación de los datos de Mi Cuenta</h5>
	                        </div>
	                        <div class="ibox-content">
	                            <p  class="m-b-lg">Modificación de los datos completos de facturación.</p>

<textarea class="code">

	require 'sdk.cms.php';
	
	$variables['id_contacto'] = 1234;
	$variables['nombre'] = 'Nombre'; // Obligatorio
	$variables['apellido'] = 'Apellido'; // Obligatorio
	$variables['empresa'] = 'Empresa'; // Obligatorio
	$variables['id_empresa'] = 1234; // Obligatorio
	$variables['telefono'] = 'Teléfono'; // Obligatorio
	$variables['email'] = 'Email'; // Obligatorio
	$variables['password'] = 'Contraseña';

	$variables['id_empresa_fiscal'] = 1234; // Si no se envía se crea una nueva
	$variables['id_condicion_iva'] = 'Condición fiscal'; // 1 => Responsable Inscripto, 2 => Monotributista, 3 => Consumidor Final, 4 => Exento (Obligatorio)
	$variables['razon_social'] = 'Razón Social';
	$variables['cuit'] = 'DNI o CUIT de la razón social'; // Obligatorio

	$variables['id_forma_pago'] = 1234; // Obligatorio
	$variables['id_cuenta'] = 1234;
	$variables['titular'] = 'Titular';
	$variables['cuenta_documento'] = 'Documento del titular de la cuenta';
	$variables['cbu'] = 'CBU';
	
	$request = new CMS('micuenta', 'PUT', $variables);
	
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