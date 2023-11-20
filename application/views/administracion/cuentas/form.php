<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/cuentas'); ?>">Cuentas</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva cuenta' : 'Modificar cuenta'; ?></h5>
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
	                            	<input type="hidden" name="id_empresa" value="<?php echo (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null; ?>">
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Titular</label>
		                                <div class="col-sm-10">
			                                <input type="text" class="form-control" name="titular" value="<?php echo (isset($detalle['titular'])) ? $detalle['titular'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Documento</label>
			                            <div class="col-sm-2">
			                                <?php echo form_dropdown('id_documento_tipo', $documentos_tipo, (isset($detalle['id_documento_tipo'])) ? $detalle['id_documento_tipo'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <div class="col-sm-8">
				                            <input type="text" class="form-control" name="numero_documento" value="<?php echo (isset($detalle['numero_documento'])) ? $detalle['numero_documento'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">CBU</label>
		                                <div class="col-sm-10">
			                                <input type="text" class="form-control" name="cbu" data-mask="99999999-99999999999999"  value="<?php echo (isset($detalle['cbu'])) ? $detalle['cbu'] : null; ?>">
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