<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Notas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('notas'); ?>">Notas</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if (isset($detalle['id'])) { ?>
                        	<a href="<?php echo base_url('notas/eliminar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar nota</a>
                        <?php } ?>
                    </div>
                </div>
	        </div>
	       
	       <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva nota' : 'Modificar nota'; ?></h5>
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
	                            	<input type="hidden" name="id_tipo" value="<?php echo (!empty($detalle['id_tipo'])) ? $detalle['id_tipo'] : null; ?>">
	                            	<input type="hidden" name="id_referencia" value="<?php echo (!empty($detalle['id_referencia'])) ? $detalle['id_referencia'] : null; ?>">
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Título</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Descripción</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control summernote col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Responsable</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('responsable', $agentes, (isset($detalle['responsable'])) ? $detalle['responsable'] : null, 'class="form-control m-b"'); ?>
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
	
			<!-- Summernote -->
			<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
		        
	        <script>
	            $(document).ready(function () {
	               $('.summernote').summernote({
		               height: 200
					});
	            });
	        </script>