<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/proyectos'); ?>">Proyectos</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo proyecto' : 'Modificar proyecto'; ?></h5>
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
			                            <label class="col-sm-2 control-label">Empresa</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_empresa', $empresas, (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Titulo</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="titulo" class="form-control" value="<?php echo (!empty($detalle['titulo'])) ? $detalle['titulo'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Categoría</label>
		                                <div class="col-sm-4">
											<select name="id_categoria" class="form-control m-b">
			                                	<?php echo $categorias_generales; ?>
			                                </select>
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
			                            <label class="col-sm-2 control-label">Descripción</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control summernote col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Valor</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="valor" class="form-control" value="<?php echo ($detalle['valor'] > 0) ? $detalle['valor'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Descuento (%)</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="descuento" class="form-control" value="<?php echo ($detalle['descuento'] > 0) ? $detalle['descuento'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
		                                <div class="col-sm-10">
			                                <div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="13" <?php if (isset($detalle['estado']) && $detalle['estado'] == 13) echo 'checked="checked"'; ?>>
			                                	<label>No Aprobado</label>
			                                </div>
			                                <div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label>Presupuestar</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label>Presupuestado</label>
	                                        </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
	                                        	<label>Autorizado</label>
	                                        </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="4" <?php if (isset($detalle['estado']) && $detalle['estado'] == 4) echo 'checked="checked"'; ?>>
	                                        	<label>Enviado</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="5" <?php if (isset($detalle['estado']) && $detalle['estado'] == 5) echo 'checked="checked"'; ?>>
	                                        	<label>Recibido</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="7" <?php if (isset($detalle['estado']) && $detalle['estado'] == 7) echo 'checked="checked"'; ?>>
	                                        	<label>Aprobado</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="8" <?php if (isset($detalle['estado']) && $detalle['estado'] == 8) echo 'checked="checked"'; ?>>
	                                        	<label>Esperando respuesta</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="9" <?php if (isset($detalle['estado']) && $detalle['estado'] == 9) echo 'checked="checked"'; ?>>
	                                        	<label>En curso</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="10" <?php if (isset($detalle['estado']) && $detalle['estado'] == 10) echo 'checked="checked"'; ?>>
	                                        	<label>Terminado</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="11" <?php if (isset($detalle['estado']) && $detalle['estado'] == 11) echo 'checked="checked"'; ?>>
	                                        	<label>Facturar</label>
			                                </div>
			                                <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="12" <?php if (isset($detalle['estado']) && $detalle['estado'] == 12) echo 'checked="checked"'; ?>>
	                                        	<label>Facturado</label>
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
	        
	        
	        <!-- Data picker -->
			<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
			
			<!-- Summernote -->
			<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
		        
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
		            
		            $('.summernote').summernote({
			               height: 500
						});
	            });
	        </script>