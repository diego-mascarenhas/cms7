<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Prospectos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('prospectos'); ?>">Prospectos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo prospecto' : 'Modificar prospecto'; ?></h5>
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
							
                            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Empresa</label>
	                                <div class="col-sm-10">
		                                <input type="text" class="form-control" name="empresa" value="<?php echo (isset($detalle['empresa'])) ? $detalle['empresa'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Nombre</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Apellido</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="apellido" value="<?php echo (isset($detalle['apellido'])) ? $detalle['apellido'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Teléfono</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="telefono" value="<?php echo (isset($detalle['telefono'])) ? $detalle['telefono'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Email</label>
		                            <div class="col-sm-4">
		                                <input type="text" class="form-control" name="email" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Observaciones</label>
	                                <div class="col-sm-10">
		                                <?php echo form_textarea('observaciones', (isset($detalle['observaciones'])) ? $detalle['observaciones'] : null, 'class="form-control col-sm-12"'); ?>
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
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
        
        
        <!-- Input Mask -->
	    <script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>