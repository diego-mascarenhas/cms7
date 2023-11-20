<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/smtps'); ?>">SMTPs</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller' && !empty($detalle['id'])) { ?>
                        	<a href="<?php echo base_url('emailer/smtps/eliminar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar SMTP</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo SMTP' : 'Modificar SMTP'; ?></h5>
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
								
	                            <?php echo form_open(null, array('class'=>'form-horizontal', 'autocomplete'=>'off')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Host</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="host" value="<?php echo (isset($detalle['host'])) ? $detalle['host'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Usuario</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="user" value="<?php echo (isset($detalle['user'])) ? $detalle['user'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Contraseña</label>
			                            <div class="col-sm-4">
			                                <input type="text" class="form-control" name="pass" value="<?php echo (isset($detalle['pass'])) ? $detalle['pass'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Seguridad</label>
			                            <div class="col-sm-4">
				                            <?php echo form_dropdown('seguridad', $seguridades, (isset($detalle['seguridad'])) ? $detalle['seguridad'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Puerto</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="puerto" value="<?php echo (isset($detalle['puerto'])) ? $detalle['puerto'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Inactivo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3 || $detalle['estado'] == 4 || $detalle['estado'] == 5)) echo 'checked="checked"'; ?>>
	                                        	<label> Activo </label>
				                            </div>
				                        </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <?php if (isset($detalle['accion']) && $detalle['accion'] == 'probar') { ?>
		                            	<div class="form-group">
			                            <label class="col-sm-2 control-label">email</label>
			                            <div class="col-sm-4">
				                            <input type="text" class="form-control" name="email" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Plantilla</label>
		                                <div class="col-sm-4">
			                            	<?php echo form_dropdown('id_template', $templates, (isset($detalle['id_template'])) ? $detalle['id_template'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <?php if (isset($detalle['accion']) && $detalle['accion'] == 'probar') { ?>
		                                    	<button class="btn btn-primary" type="submit">Probar cambios</button>
											<?php } else { ?>
												<button class="btn btn-primary" type="submit">Guardar cambios</button>
											<?php } ?>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>