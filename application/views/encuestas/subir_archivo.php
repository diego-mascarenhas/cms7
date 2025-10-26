<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Encuestas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('/encuestas'); ?>">Eventos para Encuestas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Subir archivo</strong>
	                    </li>
	                </ol>
	            </div>
	
	        </div>
	                       
	        <div class="row wrapper animated fadeInRight">
            	<!-- Titulo Mensajes -->
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-md">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-md">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
	        </div>

	       	<!-- Comienzo Detalle -->        
	        <div class="wrapper wrapper-content animated fadeInRight p-b-sm">
	            <div class="row">
					<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title"><h5>Subir archivo para el evento <a href="<?php echo base_url('encuestas/modificar/'.$detalle['titulo']); ?>" title="Ir al evento"><?php echo $detalle['titulo']; ?></a></h5>
		                    </div>
		                    
		                    <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                            <input type="hidden" name="id_evento" value="<?php echo $detalle['id']; ?>">
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <h2>Cargar archivo csv</h2>
	                            <div class="form-group pull-left full-width">
		                            <div class="input-group col-sm-4 m-l-md m-t-md">
		                            	<input type="file" name="archivo" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo csv con nombre, apellido, email, en ese orden." title=""> <i class="fa fa-question"></i></button></span></div>
	                            </div>

	                            <div class="hr-line-dashed pull-left full-width"></div>
		                            
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
					                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit">Subir</button>
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
$('[data-toggle="tooltip"]').tooltip(); 
</script>
			