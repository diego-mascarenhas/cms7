<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/empresas'); ?>">Empresas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Datos fiscales</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?> datos de facturación</h5>
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
	                            	<input type="hidden" name="id_empresa" value="<?php echo (!empty($detalle['id_empresa'])) ? $detalle['id_empresa'] : null; ?>">
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Razón social</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="razon_social" class="form-control" value="<?php echo (isset($detalle['razon_social'])) ? $detalle['razon_social'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">CUIT</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="cuit" class="form-control" value="<?php echo (isset($detalle['cuit'])) ? $detalle['cuit'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Condición I.V.A.</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_condicion_iva', $detalle['condiciones_fiscales'], (isset($detalle['id_condicion_iva'])) ? $detalle['id_condicion_iva'] : null, 'class="form-control m-b"'); ?>
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