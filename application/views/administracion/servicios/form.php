<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Administración</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('administracion/servicios'); ?>">Servicios</a>
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
	                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo servicio' : 'Modificar servicio'; ?></h5>
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
		                            <label class="col-sm-2 control-label">Categoría</label>
	                                <div class="col-sm-4">
		                                <select name="id_categoria" class="form-control m-b">
		                                	<?php echo $categorias_generales; ?>
		                                </select>
		                            </div>
		                            <label class="col-sm-2 control-label">Operación</label>
                                    <div class="col-sm-4">
	                                    <div class="radio radio-inline">
	                                    	<input type="radio" name="operacion" value="C" value="C" <?php if (isset($detalle['operacion']) && $detalle['operacion'] == 'C') echo 'checked="checked"'; ?>>
	                                    	<label>Compra</label>
	                                    </div>
	                                    <div class="radio radio-inline">
                                        	<input type="radio" name="operacion" value="V" value="V" <?php if (isset($detalle['operacion']) && $detalle['operacion'] == 'V') echo 'checked="checked"'; ?>>
                                        	<label>Venta</label>
	                                    </div>
                                    </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Frecuencia</label>
	                                <div class="col-sm-2">
		                                <?php echo form_dropdown('frecuencia', $frecuencias, (isset($detalle['frecuencia'])) ? $detalle['frecuencia'] : null, 'class="form-control m-b"'); ?>
		                            </div>
		                            <label class="col-sm-2 control-label">Próxima</label>
                                    <div class="col-sm-4">
		                                <div class="input-group date dia">
	                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="proxima" value="<?php echo formatear_fecha($detalle['proxima'], 'd-m-Y', null, $this->usuario->timezone); ?>">
	                                	</div>
		                            </div>
		                            <label class="col-sm-2 control-label">Caduca</label>
                                    <div class="col-sm-4">
		                                <div class="input-group date dia">
	                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="caduca" value="<?php echo formatear_fecha($detalle['caduca'], 'd-m-Y', null, $this->usuario->timezone); ?>">
	                                	</div>
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Descripción</label>
	                                <div class="col-sm-10">
		                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control col-sm-12" placeholder="Descripción desde base de datos"'); ?>
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Moneda</label>
	                                <div class="col-sm-2">
		                                <?php echo form_dropdown('id_moneda', $monedas, (isset($detalle['id_moneda'])) ? $detalle['id_moneda'] : null, 'class="form-control m-b"'); ?>
		                            </div>
		                            <label class="col-sm-2 control-label">Valor</label>
	                                <div class="col-sm-2">
		                                <input type="text" name="valor" class="form-control" placeholder="Desde base de datos" value="<?php echo ($detalle['valor'] > 0) ? $detalle['valor'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Descuento (%)</label>
	                                <div class="col-sm-2">
		                                <input type="text" name="descuento" class="form-control" value="<?php echo ($detalle['descuento'] > 0) ? $detalle['descuento'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Forma de pago</label>
	                                <div class="col-sm-4">
		                                <?php echo form_dropdown('id_forma_pago', $formas_pago, (isset($detalle['id_forma_pago'])) ? $detalle['id_forma_pago'] : null, 'class="form-control m-b"'); ?>
                                    </div>
                                    <label class="col-sm-2 control-label">Convertir a pesos</label>
	                                <div class="col-sm-1">
		                                <label class="checkbox-inline"><input type="checkbox" name="convertir" value="1" <?php if (isset($detalle['convertir']) && $detalle['convertir'] == 1) echo 'checked="checked"'; ?>> Si </label>
                                    </div>
                                    <label class="col-sm-2 control-label">Auto suspender</label>
	                                <div class="col-sm-1">
		                                <label class="checkbox-inline"><input type="checkbox" name="autosuspender" value="1" <?php if (isset($detalle['autosuspender']) && $detalle['autosuspender'] == 1) echo 'checked="checked"'; ?>> Si </label>
                                    </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <?php if ($this->usuario->perfil == 'reseller' && !isset($detalle['api'])) { ?>
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Estados</label>
	                                <div class="col-sm-10">
		                                <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
		                                	<label>Suspendido</label>
		                                </div>
		                                <div class="radio radio-inline">
                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
                                        	<label>Suspender</label>
                                        </div>
		                                <div class="radio radio-inline">
                                        	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
                                        	<label>Activar</label>
                                        </div>
		                                <div class="radio radio-inline">
                                        	<input type="radio" name="estado" value="4" <?php if (isset($detalle['estado']) && $detalle['estado'] == 4) echo 'checked="checked"'; ?>>
                                        	<label>Activo</label>
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