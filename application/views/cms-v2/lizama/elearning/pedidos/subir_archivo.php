
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>eLearning Pedidos Empresa</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a></li>
                    <li><strong>Subir archivo con Usuarios</strong></li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id_pedido" value="<?php echo $detalle['id']; ?>">
            <input type="hidden" name="id_contacto_padre" value="<?php echo $detalle['id_contacto']; ?>">
            <input type="hidden" name="razon_social" value="<?php echo $contacto['razon_social']; ?>">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
		                    <h5>Subir archivo al Pedido <a href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/'.$detalle['id']); ?>" title="Ir al pedido">Nro. <?php echo $detalle['id'].' - '.$detalle['observaciones']; ?></a> de la Empresa: <a href="<?php echo base_url('cms-v2/elearning/usuarios/empresas/'.$contacto['id']); ?>" title="Ir al pedido"><?php echo $contacto['nombre']; ?></a></h5>
	                    </div>
	                    <div class="ibox-content" style="min-height:140px; height:auto; float:left; padding-bottom:25px;">
	                        <?php if (validation_errors()) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo validation_errors(); ?>
								</div>
							</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo $error; ?>
								</div>
							</div>
							<?php endif; ?>

		                    <?php if ($this->session->flashdata('mensaje') == 'ok') : ?>
							<div class="col-md-12 pull-left full-width">
								<div class="alert alert-success alert-dismissable" role="alert">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									Su archivo se subió correctamente.
							    </div>
								<?php endif; ?>
								<?php if ($this->session->flashdata('mensaje') == 'error') : ?>
							<div class="col-md-12 pull-left full-width">
								<div class="alert alert-danger alert-dismissable" role="alert">
		                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		                            Se produjo un error al subir el archivo.
		                        </div>
							</div>
							<?php endif; ?>

		                    <div class="col-sm-12">
	                            <div class="row">
	                            	<div class="col-sm-8">
	                            		<h2>Cargar archivo csv</h2>
	                            	</div>
	                            	<div class="col-sm-4 text-right" style="margin-top: 24px;">
	                            		<a href="#" id="descargar-ejemplo" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Descargar Ejemplo CSV</a>
	                            	</div>
	                            </div>
	                            
	                            <!-- Ayuda sobre el formato CSV -->
	                            <div class="alert alert-info m-b-lg">
	                            	<h4><i class="fa fa-info-circle"></i> Formato del archivo CSV</h4>
	                            	<p><strong>El archivo NO debe contener encabezados.</strong> Cada línea representa un usuario con los siguientes datos en orden:</p>
	                            	<p class="m-t-sm"><code>Nombre,Apellido,Email,Contraseña</code></p>
	                            	
	                            	<hr class="m-t-md m-b-md">
	                            	
	                            	<p><strong>Ejemplo de contenido del archivo:</strong></p>
	                            	<pre style="background: #f5f5f5; padding: 10px; border-radius: 4px;">Juan,Pérez,juan.perez@example.com,Pass1234
María,González,maria.gonzalez@example.com,Pass5678
Pedro,Rodríguez,pedro.rodriguez@example.com,Pass9012</pre>

									<hr class="m-t-md m-b-md">
									
									<p><strong><i class="fa fa-check-circle text-success"></i> ¿Qué pasa si el contacto ya existe?</strong></p>
									<ul class="m-t-sm">
										<li><strong>Si el email existe y es usuario de empresa:</strong> Se asociará automáticamente al pedido (no se crea uno nuevo).</li>
										<li><strong>Si el email existe pero NO es usuario de empresa:</strong> Se omitirá y recibirás una advertencia al finalizar.</li>
										<li><strong>Si el email NO existe:</strong> Se creará el nuevo usuario y se asociará al pedido.</li>
									</ul>
									
									<hr class="m-t-md m-b-md">
									
									<p><strong>Características:</strong></p>
									<ul class="m-l-md">
										<li>Separadores aceptados: <code>,</code> (coma) o <code>;</code> (punto y coma)</li>
										<li>Tamaño máximo: 1 MB</li>
										<li>Codificación recomendada: UTF-8</li>
										<li>Todos los usuarios se crean con estado "Activo"</li>
									</ul>
	                            </div>
	                            
	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-12 control-label text-left m-b-sm"><strong>Seleccionar archivo CSV:</strong></label>
		                            <div class="col-sm-6">
		                            	<input type="file" name="archivo" class="form-control" accept=".csv" required>
		                            	<span class="help-block m-b-none">Archivo en formato CSV (máximo 1 MB)</span>
		                            </div>
	                            </div>
	
	                            <div class="form-group">
	                                <div class="col-sm-12 m-t-md">
				                	<a class="btn btn-white" type="submit" href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/'.$detalle['id']); ?>">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit" name="subir" value="1"><i class="fa fa-upload"></i> Subir Archivo</button>
	                                </div>
	                            </div>
		                </div>
						</div>
	                </div>
	            </div>
            </div>
        </div>
    <!-- Fin Contenido -->
	<?php echo form_close(); ?>

<script>
// Función para descargar CSV de ejemplo
document.getElementById('descargar-ejemplo').addEventListener('click', function(e) {
    e.preventDefault();
    
    // Contenido del CSV de ejemplo
    var csvContent = "Juan,Pérez,juan.perez@example.com,Pass1234\n";
    csvContent += "María,González,maria.gonzalez@example.com,Pass5678\n";
    csvContent += "Pedro,Rodríguez,pedro.rodriguez@example.com,Pass9012\n";
    csvContent += "Ana,Martínez,ana.martinez@example.com,Pass3456\n";
    csvContent += "Luis,Sánchez,luis.sanchez@example.com,Pass7890";
    
    // Crear blob y descargar
    var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    var link = document.createElement("a");
    var url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "ejemplo_usuarios.csv");
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});
</script>
			