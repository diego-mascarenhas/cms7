<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mi cuenta</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong>Contraseña</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Modificar contraseña</h5>
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
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Contraseña</label>
		                                <div class="col-sm-2">
			                                <input type="password" class="form-control" name="password" value="<?php if (!empty($detalle['password'])) echo $detalle['password']; ?>" placeholder="Contraseña">
			                            </div>
			                            <label class="col-sm-2 control-label">Repetir contraseña</label>
		                                <div class="col-sm-2">
			                                <input type="password" class="form-control" name="passconf" value="<?php if (!empty($detalle['passconf'])) echo $detalle['passconf']; ?>" placeholder="Confirmación contraseña">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <?php if ($this->session->has_userdata('referrer')) { ?> <a class="btn btn-white" type="submit" href="<?php echo $this->session->userdata('referrer'); ?>">Cancelar</a><?php } ?>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>