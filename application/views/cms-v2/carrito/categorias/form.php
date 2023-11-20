<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>                        
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/carrito/categorias">Categor&iacute;as</a>
                    </li>
                    <li class="active">
                         <strong><?php echo (empty($item['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
        </div>
            
	        <div class="wrapper wrapper-content animated fadeInRight p-b-sm">
	            <div class="row">
	                <?php if (validation_errors()) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
					</div>
					<?php endif; ?>
					<?php if (isset($error)) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
					</div>
					<?php endif; ?>
	            </div>

	            <div class="row">
					<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title"><h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nueva'; ?> Categor&iacute;a</h5>
		                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
		                    </div>
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
                        	<input type="hidden" name="idioma" value="<?php if (isset($item['idioma'])) { echo $item['idioma']; } ?>">
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <h2>Datos de la Categor&iacute;a</h2>
	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">Nombre</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
					                    	<input type="text" name="categoria" class="form-control" value="<?php if (isset($item['categoria'])) { echo $item['categoria']; } else { if ($this->input->post('categoria')) { echo $this->input->post('categoria'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la categor&iacute;a. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
					                   	</div>
				                    </div>
		                            <?php 
		                           		if( (isset($item['id'])) && ($item['padre'] > 0)) 
		                           		{  
			                         ?>
		                            <label class="col-sm-2 control-label">Padre</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <?php echo form_dropdown('padre', $categorias, $item['padre'], array('class'=>'form-control p-sm pull-left')); ?>
											<span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Padre de la categor&iacute;a, en caso de corresponder. Sino tiene padre debe seleccionar -- Sin padre --. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
				                    	</div>
				                    </div>
		                            <?php 
		                            	}
		                            	elseif(!isset($item['id']))
			                           	{
			                         ?>
		                            <label class="col-sm-2 control-label">Padre</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <?php echo form_dropdown('padre', $categorias, null, array('class'=>'form-control p-sm pull-left')); ?>
											<span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Padre de la categor&iacute;a, en caso de corresponder. Sino tiene padre debe seleccionar -- Sin padre --. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
				                    	</div>
				                    </div>
		                            <?php 
			                           	}
			                         ?>
				                    
	                            </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">Estado </label>
				                    <div class="col-sm-3">
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="2" <?php if (isset($item['estado']) && $item['estado'] == '2') echo 'checked="checked"'; ?>> <label> Activo </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
			                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca Activo se mostrar&aacute; en la web, sino no. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></div>
									</div>

									<?php if (isset($item['id'])) { ?>
				                    <label class="text-right col-sm-2 control-label">Orden</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <input type="text" name="orden" class="form-control" value="<?php if (isset($item['orden'])) { echo $item['orden']; } else { if ($this->input->post('orden')) { echo $this->input->post('orden'); } }?>" readonly="true"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrar&aacute;, si es subcategor&iacute;a tomar&aacute; el orden de las categor&iacute;as asociadas a la principal. Campo modificable desde la acci&oacute;n Ordenar del listado de categor&iacute;s" title=""> <i class="fa fa-question"></i></button></div>
	                                </div>
				                 <?php } ?>
						        </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-3 control-label">Publicar como sección de sitio web </label>
				                    <div class="col-sm-4">
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="menu" value="1" <?php if ((isset($item['seccion'])) && ($item['seccion'] > 0)) echo 'checked="checked"'; ?>> <label> Sí </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="menu" value="null" <?php if ((isset($item['seccion'])) && ($item['seccion'] <=0)) echo 'checked="checked"'; ?>><label> No </label>
			                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca Sí la categoría se mostrar&aacute; en el menú de secciones del web, sino no. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></div>
									</div>
						        </div>

		                                            			                            
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
					                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
	                                </div>
	                            </div>
	                        </form>
	                        </div>
                </div>
            </div>
        </div>
	</div>
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>		        