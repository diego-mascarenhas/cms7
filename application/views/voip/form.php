<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Voip</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Voip</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Llamar al siguiente contacto</h5>
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
	                            <input type="hidden" name="id_contacto" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Nombre</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>" disabled="true" readonly="true">
			                            </div>
			                            <label class="col-sm-2 control-label">Apellido</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="apellido" value="<?php echo (isset($detalle['apellido'])) ? $detalle['apellido'] : null; ?>" disabled="true" readonly="true">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Código de país</label>
		                                <div class="col-sm-1">
			                                <input type="text" class="form-control" name="codigo_pais" value="<?php echo (isset($detalle['codigo_pais'])) ? $detalle['codigo_pais'] : null; ?>" disabled="true" readonly="true">
			                            </div>
			                            <label class="col-sm-2 control-label">Código de área</label>
		                                <div class="col-sm-1">
			                                <input type="text" class="form-control" name="codigo_area" value="<?php echo (isset($detalle['codigo_area'])) ? $detalle['codigo_area'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Número</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="numero" value="<?php echo (isset($detalle['telefono'])) ? $detalle['telefono'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Agente</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="agente" value="<?php echo (isset($detalle['agente'])) ? $detalle['agente'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <button class="btn btn-primary" type="submit">Llamar</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>