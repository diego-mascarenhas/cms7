<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>
         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>eLearning Categorías</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning');?>">Elearning</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/categorias');?>">Categorías</a>
                    </li>
                    <li>
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4"></div>
        </div>
                               
        <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
        <input type="hidden" name="id_secciones_tipo" value="9">
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva categoría' : 'Modificar categoría'; ?></h5>
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
							
						<?php if ($this->session->flashdata('mensaje')) { ?>
						<div class="col-md-12">
							<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
							<div class="alert alert-danger alert-dismissable" role="alert">
				            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				            <?php echo $error; ?></div>
							<?php } else { ?>
							<div class="alert alert-success alert-dismissable" role="alert">
								<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
								<?php echo $this->session->flashdata('mensaje');?></div>
							<?php } ?>
						</div>
						<?php } ?>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Nombre Categoría</label>
			                    <div class="col-sm-2 col-md-2">
                                    <div class="input-group">
                                    	<input type="text" name="seccion" class="form-control" value="<?php echo (isset($detalle['seccion'])) ? $detalle['seccion']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la categoría." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
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

<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>