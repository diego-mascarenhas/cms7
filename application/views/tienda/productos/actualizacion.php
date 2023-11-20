<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/productos'); ?>">Productos </a>
                    </li>
                    <li>
                        <strong>Actualizaci&oacute;n masiva</strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
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
	        <?php if ($this->input->get('ok')) { ?>
	            <!-- Titulo Mensajes -->
	            <div class="row">
					<div class="col-md-12">
						<div class="alert alert-success alert-dismissable">
	                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	                        <p><?php echo $ok; ?></p>
	                    </div>
	                </div>
	            </div>
	        <?php } ?>

	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Actualizar de precios de productos por categoría</h5>
	                    </div>
	                    <div class="ibox-content">
	                		<?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                    	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
	                        <div class="form-group">
	                            <label class="col-sm-2 control-label">Seleccione categoría</label>
	                            <div class="col-sm-4">
							   		<?php echo form_dropdown('id_categoria', $categorias, null, array('class'=>'form-control m-b')); ?>
	                            </div>
	                        </div>
                            <div class="hr-line-dashed"></div>
                            
						 	<div class="form-group">
	                            <label class="col-sm-2 control-label">Porcentaje precio</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
		                                <input type="text" class="form-control" name="porcentaje" value="">
		                            </div>
	                            </div>
	                            <label class="col-sm-2 control-label">Porcentaje precio Menú</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
		                                <input type="text" class="form-control" name="porcentaje_local" value="">
		                            </div>
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>

						 	<div class="form-group">
	                            <label class="col-sm-2 control-label">Porcentaje precio Oferta</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
		                                <input type="text" class="form-control" name="porcentaje_oferta" value="">
		                            </div>
	                            </div>
	                            <label class="col-sm-2 control-label">Porcentaje precio Menú Oferta</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
		                                <input type="text" class="form-control" name="porcentaje_local_oferta" value="">
		                            </div>
	                            </div>
                            </div>

                            <div class="form-group m-t-lg">
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
