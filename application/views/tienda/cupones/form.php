<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/cupones'); ?>">Cupones</a>
	                    </li>
	                    <li>
	                        <strong>Ingresar</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
            

        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>

            <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nuevo'; ?> Cup&oacute;n</h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Cup&oacute;n</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="cupon" value="<?php if (isset($item['cupon'])) { echo $item['cupon']; } else { if ($this->input->post('cupon')) { echo $this->input->post('cupon'); } }?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
	                                	<input type="radio" name="estado" value="3" <?php if ((!isset($item['estado'])) || (isset($item['estado']) && $item['estado'] == '3')) echo 'checked="checked"'; ?>> <label> Activo </label>
		                            </div>
		                            <div class="radio radio-inline">
                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
		                            </div>
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
						 	<div class="form-group">
	                            <label class="col-sm-2 control-label">Descuento</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="descuento" value="<?php echo (isset($item['id'])) ? $item['descuento']: null; ?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Stock</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="cantidad" value="<?php echo (isset($item['id'])) ? $item['cantidad']: null; ?>">
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                    
						 	<div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Vencimiento</label>
								<div class="col-sm-4 col-md-3">
	                                <div class="input-group date dia">
	                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_vencimiento" value="<?php if(isset($item['fecha_vencimiento'])) { $date = date_create($item['fecha_vencimiento']); echo date_format($date, 'd-m-Y'); } ?>">
	                            	</div>
	                            </div>	                 	
                            </div>
                            		                            
                            <div class="form-group m-t-lg">
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