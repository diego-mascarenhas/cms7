<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2><?php echo $this->lang->line('cms_tickets'); ?></h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('tickets'); ?>"><?php echo $this->lang->line('cms_tickets'); ?></a>
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
	                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo ticket' : 'Modificar ticket'; ?></h5>
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
                            	<input type="hidden" name="id_contacto" value="<?php echo (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null; ?>">
                            	
                            	<?php if (isset($servicios)) { ?>
								<div class="form-group">
		                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_servicio'); ?></label>
	                                <div class="col-sm-10">
		                            	<?php echo form_dropdown('id_servicio', $servicios, (isset($detalle['id_servicio'])) ? $detalle['id_servicio'] : null, 'class="form-control m-b"'); ?>
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            <?php } ?>
	                            
                            	<div class="form-group">
	                            	<label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-area'); ?><br/></label>
                                    <div class="col-sm-10">
	                                    <div class="radio radio-inline">
	                                    	<input type="radio" name="id_area" value="1" <?php if (isset($detalle['id_area']) && $detalle['id_area'] == 1) echo 'checked="checked"'; ?>>
	                                    	<label><?php echo $this->lang->line('cms_users-area-gerencia'); ?></label>
	                                    </div>
	                                    <div class="radio radio-inline">
											<input type="radio" name="id_area" value="2" <?php if (isset($detalle['id_area']) && $detalle['id_area'] == 2) echo 'checked="checked"'; ?>>
											<label><?php echo $this->lang->line('cms_users-area-administracion'); ?></label>
										</div>
	                                    <div class="radio radio-inline">
											<input type="radio" name="id_area" value="3" <?php if (isset($detalle['id_area']) && $detalle['id_area'] == 3) echo 'checked="checked"'; ?>>
											<label><?php echo $this->lang->line('cms_users-area-comercial'); ?></label>
										</div>
	                                    <div class="radio radio-inline">
											<input type="radio" name="id_area" value="4" <?php if (isset($detalle['id_area']) && $detalle['id_area'] == 4) echo 'checked="checked"'; ?>>
											<label><?php echo $this->lang->line('cms_users-area-tecnica'); ?></label>
	                                    </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-asunto'); ?></label>
                                    <div class="col-sm-10">
	                                    <input type="text" name="asunto" class="form-control" value="<?php echo (isset($detalle['asunto'])) ? $detalle['asunto'] : null; ?>">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                
                                <div class="form-group">
	                                <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-prioridad'); ?><br/></label>
                                    <div class="col-sm-10">
	                                    <div class="radio radio-inline">
		                                    <input type="radio" name="prioridad" value="1" <?php if (isset($detalle['prioridad']) && $detalle['prioridad'] == 1) echo 'checked="checked"'; ?>>
		                                    <label><?php echo $this->lang->line('cms_users-prioridad-normal'); ?></label>
	                                    </div>
	                                    <div class="radio radio-inline">
		                                    <input type="radio" name="prioridad" value="2" <?php if (isset($detalle['prioridad']) && $detalle['prioridad'] == 2) echo 'checked="checked"'; ?>>
		                                    <label><?php echo $this->lang->line('cms_users-prioridad-alta'); ?></label>
	                                    </div>
		                                <div class="radio radio-inline">
		                                    <input type="radio" name="prioridad" value="3" <?php if (isset($detalle['prioridad']) && $detalle['prioridad'] == 3) echo 'checked="checked"'; ?>>
		                                    <label><?php echo $this->lang->line('cms_users-prioridad-urgente'); ?></label>
		                                </div>
		                                <div class="radio radio-inline">
		                                    <input type="radio" name="prioridad" value="4" <?php if (isset($detalle['prioridad']) && $detalle['prioridad'] == 4) echo 'checked="checked"'; ?>>
		                                    <label><?php echo $this->lang->line('cms_users-prioridad-critica'); ?></label>
		                                </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                
                                <?php if (empty($detalle['id'])) { ?>
                                <div class="form-group">
	                                <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-mensaje'); ?></label>
                                    <div class="col-sm-10">
		                                <?php echo form_textarea('mensaje', (isset($detalle['mensaje'])) ? $detalle['mensaje'] : null, 'class="form-control col-sm-12"'); ?>
		                            </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                
	                                <?php if ($this->usuario->perfil == 'reseller') { ?>
	                                <div class="form-group">
		                                <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-visibilidad'); ?>Visibilidad</label>
	                                    <div class="col-sm-4">
		                                    <div class="checkbox checkbox-inline">
		                                    	<input type="checkbox" name="visibilidad" value="1" <?php if (isset($detalle['visibilidad']) && $detalle['visibilidad'] == 1) echo 'checked="checked"'; ?>>
		                                    	<label><?php echo $this->lang->line('cms_users-visibilidad-info'); ?></label>
		                                    </div>
		                                </div>
		                                <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-origen'); ?></label>
	                                    <div class="col-sm-4">
		                                    <div class="checkbox checkbox-inline">
		                                    	<input type="checkbox" name="id_origen" value="4" <?php if (isset($detalle['id_origen']) && $detalle['id_origen'] == 4) echo 'checked="checked"'; ?>>
		                                    	<label><?php echo $this->lang->line('cms_users-llamado-telefonico'); ?></label>
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="hr-line-dashed"></div>
	                                <?php } ?>
                                <?php } ?>
                                
	                            <?php if (!empty($detalle['id'])) { ?>
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-estados-ticket'); ?>Estados</label>
		                            <div class="col-sm-10">
		                            	<div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-rechazado'); ?></label>
			                            </div>
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3)) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-abierto'); ?></label>
			                            </div>
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="4" <?php if (isset($detalle['estado']) && $detalle['estado'] == 4) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-esperando-respuesta'); ?></label>
			                            </div>
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="5" <?php if (isset($detalle['estado']) && $detalle['estado'] == 5) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-esperando-proveedor'); ?></label>
			                            </div>
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="6" <?php if (isset($detalle['estado']) && $detalle['estado'] == 6) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-plan-de-accion'); ?></label>
			                            </div>
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="7" <?php if (isset($detalle['estado']) && $detalle['estado'] == 7) echo 'checked="checked"'; ?>>
		                                	<label> <?php echo $this->lang->line('cms_users-cerrado'); ?></label>
			                            </div>
			                        </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            <?php } ?>
	                            
	                            
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
	                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);"><?php echo $this->lang->line('cms_users-cancelar'); ?></a>
	                                    <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('variable_name'); ?>Guardar cambios</button>
	                                </div>
	                            </div>
	                        </form>
	                    </div>
	                </div>
	            </div>
	        </div>
        </div>