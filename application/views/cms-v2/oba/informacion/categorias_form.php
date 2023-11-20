<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio Web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/informacion');?>">Información</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/categorias');?>">Categor&iacute;as</a>
                    </li>
                    <li>
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4"></div>
        </div>
                               
        <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva categor&iacute;a' : 'Modificar categor&iacute;a'; ?></h5>
	                    </div>
	                    <div class="ibox-content">
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
							

							<div class="form-group">
                            	<label class="col-sm-2 control-label">Tipo<br></label>
                                <div class="col-sm-10">
                                    <div class="radio radio-inline">
                                    	<input type="radio" name="id_secciones_tipo" value="9" <?php if (isset($detalle['id_secciones_tipo']) && $detalle['id_secciones_tipo'] == '9') echo 'checked="checked"'; ?>>
                                    	<label>Noticias</label>
                                    </div>
                                    <div class="radio radio-inline">
										<input type="radio" name="id_secciones_tipo" value="10" <?php if (isset($detalle['id_secciones_tipo']) && $detalle['id_secciones_tipo'] == '10') echo 'checked="checked"'; ?>>
										<label>Documentos</label>
									</div>
									<button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tipo de información." title=""> <i class="fa fa-question"></i></button>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Categor&iacute;a Espa&ntilde;ol</label>
			                    <div class="col-sm-2 col-md-2">
                                    <div class="input-group">
                                    	<input type="text" name="seccion" class="form-control" value="<?php echo (isset($detalle['seccion'])) ? $detalle['seccion']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la categoría según idioma." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Categor&iacute;a Ingl&eacute;s</label>
			                    <div class="col-sm-2 col-md-2"><input type="text" name="descripcion" class="form-control" value="<?php echo (isset($detalle['descripcion'])) ? $detalle['descripcion']: null; ?>"></div>
			                    <label class="text-right col-sm-2 control-label">Categor&iacute;a Portugu&eacute;s</label>
			                    <div class="col-sm-2"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($detalle['contenido1'])) ? $detalle['contenido1']: null; ?>"></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                                
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Orden</label>
			                    <div class="col-sm-2">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará la categoría." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
	                            <label class="col-sm-2 control-label text-right">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
		                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si la categoría se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
		                         </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
					                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
					                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
		                </div>
		            </div>
		        </div>
		    </div>
        </div>
	    <?php echo form_close();?>

	    <!-- Fin Contenido -->
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>