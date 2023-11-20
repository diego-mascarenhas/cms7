<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/listas'); ?>">Listas</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	       
	       <div class="wrapper wrapper-content animated fadeInRight">
		       <div class="alert alert-info">
					<?php echo '<pre>' . print_r($detalle, true) . '</pre>'; ?>
				</div>
				
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva lista' : 'Modificar lista'; ?></h5>
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
			                            <label class="col-sm-2 control-label">Lista</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="lista" class="form-control" value="<?php echo (isset($detalle['lista'])) ? $detalle['lista'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Contactos</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="filtros[contactos_categorias]" class="form-control" value="<?php echo (isset($detalle['filtro']['contactos_categorias'])) ? $detalle['filtro']['contactos_categorias'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Estado</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="filtros[contactos_estados]" class="form-control" value="<?php echo (isset($detalle['filtro']['contactos_estados'])) ? $detalle['filtro']['contactos_estados'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Servicios</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="filtros[servicios_categorias]" class="form-control" value="<?php echo (isset($detalle['filtro']['servicios_categorias'])) ? $detalle['filtro']['servicios_categorias'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Estado</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="filtros[servicios_estados]" class="form-control" value="<?php echo (isset($detalle['filtro']['servicios_estados'])) ? $detalle['filtro']['servicios_estados'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Inactiva </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label> Activa </label>
				                            </div>
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