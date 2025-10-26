<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia'); ?>">Multimedia</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'admin' && !empty($detalle['id'])) { ?>
                        	<a href="<?php echo base_url('multimedia/eliminar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar media</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo archivo' : 'Modificar archivo'; ?></h5>
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
								
	                            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">

		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Nombre</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
			                            </div>
		                            </div>
		                            
		                            <?php if (isset($detalle['id'])) { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Miniatura</label>
			                            <div class="col-sm-10">
			                            	<input type="file" name="file" class="form-control">
			                            	<small><em>Por el momento solo acepta archivos JPG</em></small>
				                        </div>
			                        </div>
		                            
		                            <?php if (($this->usuario->perfil == 'reseller' || $this->usuario->perfil == 'admin') && isset($detalle['tipo']) && $detalle['tipo'] == 'video' && $detalle['mime'] != 'application/octet-stream') { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Tipo</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="stream" value="1" <?php if (isset($detalle['stream']) && $detalle['stream'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Storage </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="stream" value="2" <?php if (isset($detalle['stream']) && $detalle['stream'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label> VOD </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="stream" value="3" <?php if (isset($detalle['stream']) && $detalle['stream'] == 3) echo 'checked="checked"'; ?>>
	                                        	<label> Adaptative </label>
				                            </div>
				                        </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estado</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Inactivo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label> Activo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
	                                        	<label> Público </label>
				                            </div>
				                        </div>
		                            </div>
		                            <?php } ?>
		                            
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