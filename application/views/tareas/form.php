<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Tareas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tareas'); ?>">Tareas</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva tarea' : 'Modificar tarea'; ?></h5>
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
			                            <label class="col-sm-2 control-label">Proyecto</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_proyecto', $proyectos, (isset($detalle['id_proyecto'])) ? $detalle['id_proyecto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Desde</label>
	                                    <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
		                                	</div>
			                            </div>
			                            <label class="col-sm-2 control-label">Hasta</label>
	                                    <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="hasta" value="<?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>">
		                                	</div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
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
			                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Horas asignadas</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="horas_designadas" class="form-control" value="<?php echo (isset($detalle['horas_designadas'])) ? $detalle['horas_designadas'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Horas utilizadas</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="horas_utilizadas" class="form-control" value="<?php echo (isset($detalle['horas_utilizadas'])) ? $detalle['horas_utilizadas'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Porcentaje realizado</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="porcentaje_realizado" class="form-control" value="<?php echo (isset($detalle['porcentaje_realizado'])) ? $detalle['porcentaje_realizado'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <?php if (!empty($detalle['id'])) { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
		                                <div class="col-sm-10">
			                                <div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label>Pendiente</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label>En curso</label>
	                                        </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
	                                        	<label>Terminada</label>
	                                        </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="4" <?php if (isset($detalle['estado']) && $detalle['estado'] == 4) echo 'checked="checked"'; ?>>
	                                        	<label>A la espera</label>
			                                </div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <?php } ?>
		                            
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
	        
	        
	        <!-- Data picker -->
			<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
		        
	        <script>
	            $(document).ready(function () {
	                $('.dia.input-group.date').datepicker({
		                todayBtn: "linked",
		                keyboardNavigation: false,
		                forceParse: false,
		                calendarWeeks: true,
		                autoclose: true,
		                format: "dd-mm-yyyy",
		                todayHighlight: true
		            });
	            });
	        </script>