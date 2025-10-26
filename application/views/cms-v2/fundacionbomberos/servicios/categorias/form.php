<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Servicios y Beneficios</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/servicios/categorias');?>">Categorías</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="menu" value="0">
			<?php if (!empty($detalle['id'])) { ?>
			<input type="hidden" name="seccion" value="<?php echo $detalle['seccion'];?>">
			<input type="hidden" name="fecha_alta" value="<?php echo $detalle['fecha_alta'];?>">
			<?php } ?>
			
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
			</div>
        </div>
		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            Se produjo un error en el formulario.</div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				La categoría fue actualizada correctamente.</div>
			<?php } ?>
		</div>
		<?php } ?>

        
       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content" style="padding-top:0 !important;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-content pull-left full-width">
                        <h2>Datos de la Categoría</h2>
			            <hr class="hr-line-dashed pull-left full-width">
                        <div class="form-group pull-left full-width m-t-md m-b-sm">
	                        <?php if ( (!isset($detalle['id'])) || ( (isset($detalle['id'])) && (!empty($detalle['padre'])) ) ) { ?>
	                        <label class="col-sm-1 control-label">Tipo<br></label>
                                <div class="col-sm-10">
                                    <div class="radio radio-inline">
                                    	<input type="radio" name="padre" value="84" <?php if (isset($detalle['padre']) && $detalle['padre'] == '84') echo 'checked="checked"'; ?>>
                                    	<label>Servicios</label>
                                    </div>
                                    <div class="radio radio-inline">
										<input type="radio" name="padre" value="45" <?php if (isset($detalle['padre']) && $detalle['padre'] == '45') echo 'checked="checked"'; ?>>
										<label>Beneficios</label>
									</div>
									<button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tipo de categoría." title=""> <i class="fa fa-question"></i></button>
                                </div>
                            </div>
                        <div class="hr-line-dashed pull-left full-width"></div>
	                    <?php } ?>

                        <div class="form-group pull-left full-width m-t-md m-b-sm">
                            <label class="col-sm-1 control-label text-right">Nombre</label>
		                    <div class="col-sm-4">
                                <div class="input-group">
			                    	<input type="text" name="categoria" class="form-control" value="<?php if (isset($detalle['categoria'])) { echo $detalle['categoria']; } else { if ($this->input->post('categoria')) { echo $this->input->post('categoria'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la categoría. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></span>
			                   	</div>
		                    </div>
                            <label class="col-sm-1 control-label text-right">Color</label>
		                    <div class="col-sm-4">
                                <div class="input-group">
	                                <input type="text" name="color" id="color" value="<?php if (isset($detalle['color'])) { echo $detalle['color']; } else { if ($this->input->post('color')) { echo $this->input->post('color'); } }?>" class="form-control demo1 colorpicker-element"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Color de fondo de la categoría." title=""> <i class="fa fa-question"></i></button></span></div>
		                    </div>
                        </div>
		                    
                        <div class="form-group pull-left full-width m-t-md m-b-sm">
		                    <label class="text-right col-sm-2 control-label">Estado</label>
		                    <div class="col-sm-4">
	                            <div class="radio radio-inline">
                                	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == '2') echo 'checked="checked"'; ?>> <label> Activo </label>
	                            </div>
	                            <div class="radio radio-inline">
                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
	                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca Activo se mostrar&aacute; en la web, sino no. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></div>
							</div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-11 col-sm-offset-1 m-t-lg">
			                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                            </div>
                        </div>
                    </form>
                 </div>
             </div>                 
         </div>
     </div>     
<link href="/assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script>
$('[data-toggle="tooltip"]').tooltip(); 
  $('.demo1').colorpicker();
</script>