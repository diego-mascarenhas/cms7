
	       <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo mensaje' : 'Modificar mensaje'; ?></h5>
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
			                            <label class="col-sm-2 control-label">Asunto</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="asunto" class="form-control" value="<?php echo (isset($detalle['asunto'])) ? $detalle['asunto'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Lista</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_lista', $listas, (isset($detalle['id_lista'])) ? $detalle['id_lista'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Plantilla</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_template', $templates, (isset($detalle['id_template'])) ? $detalle['id_template'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Mensaje</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('mensaje', (isset($detalle['mensaje'])) ? $detalle['mensaje'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
	                                	<label class="col-sm-2 control-label">Programar envío</label>
		                                <div class="col-sm-6 input-daterange input-group">
		                                    <input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
		                                    <span class="input-group-addon">hasta</span>
		                                    <input type="text" class="form-control" name="hasta" value="<?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>">
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
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                        	<label> Activo </label>
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
	        
	        
	
			<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
			
			
			<script src="<?php echo base_url('assets/js/plugins/clockpicker/clockpicker.js'); ?>"></script>
		        
	        <script>
	            $(document).ready(function () {
	                $('.input-daterange').datepicker({
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
          