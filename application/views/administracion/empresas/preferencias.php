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
	                        <strong>Preferencias</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Modificar preferenicas</h5>
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
			                            <label class="col-sm-2 control-label">Contacto</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $contactos, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Tipo de factura</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_factura_tipo', $facturas_tipo, (isset($detalle['id_factura_tipo'])) ? $detalle['id_factura_tipo'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Forma de pago</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_forma_pago', $formas_pago, (isset($detalle['id_forma_pago'])) ? $detalle['id_forma_pago'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Código</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="codigo" class="form-control" value="<?php echo (isset($detalle['codigo']) && $detalle['codigo']) ? $detalle['codigo'] : str_pad($detalle['id'], 14, '0', STR_PAD_LEFT); ?>">
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