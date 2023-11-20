<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Gestión</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('gestion'); ?>">Gestión administrativa</a>
	                    </li>
	                    <li class="active">
	                        <strong>Conciliación de cuenta</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	       
	       <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo $detalle['cuenta']; ?></h5>
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
	                            	<input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
	                            	<input type="hidden" name="cuenta" value="<?php echo $detalle['cuenta']; ?>">
	                            	<input type="hidden" name="valor_original" value="<?php echo $detalle['valor']; ?>">
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label">Valor</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="valor" class="form-control" placeholder="Monto del banco" value="<?php echo ($detalle['valor'] > 0) ? $detalle['valor'] : null; ?>">
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