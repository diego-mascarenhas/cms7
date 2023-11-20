<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Movimientos</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/movimientos'); ?>">Movimientos</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Ingresar pago' : 'Modificar pago'; ?></h5>
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
	                            	<input type="hidden" name="estado" value="2">
	
<!--
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Empresa</label>
		                                <div class="col-sm-10">
			                                <?php echo form_dropdown('id_empresa', $empresas, (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
-->
	                                
	                                <div class="form-group">
			                            <label class="col-sm-2 control-label">Fecha</label>
			                            <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha" value="<?php echo formatear_fecha($detalle['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?>">
		                                	</div>
			                            </div>
			                            <label class="col-sm-2 control-label">Transacción</label>
	                                    <div class="col-sm-4">
		                                    <div class="radio radio-inline">
		                                    	<input type="radio" name="transaccion" value="I" <?php if (isset($detalle['transaccion']) && $detalle['transaccion'] == 'I') echo 'checked="checked"'; ?>>
		                                    	<label>Cobro (Ingreso)</label>
		                                    </div>
		                                    <div class="radio radio-inline">
		                                        <input type="radio" name="transaccion" value="G" <?php if (isset($detalle['transaccion']) && $detalle['transaccion'] == 'G') echo 'checked="checked"'; ?>>
		                                        <label>Pago (Egreso)</label>
		                                    </div>
	                                    </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Importe</label>
			                            <div class="col-sm-4">
			                                <input type="text" name="valor" class="form-control" value="<?php echo (isset($detalle['valor'])) ? $detalle['valor'] : null; ?>" <?php if (isset($detalle['readonly']) && $detalle['readonly'] == true) echo 'readonly'; ?>>
			                            </div>
			                            <?php if (isset($facturas)) { ?>
				                            <label class="col-sm-2 control-label">Factura</label>
				                            <div class="col-sm-4">
				                                <?php echo form_dropdown('id_factura', $facturas, (isset($detalle['id_factura'])) ? $detalle['id_factura'] : null, 'class="form-control m-b"'); ?>
				                            </div>
			                            <?php } ?>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Forma de pago</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_forma_pago', $formas_pago, (isset($detalle['id_forma_pago'])) ? $detalle['id_forma_pago'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Cuenta</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_cuenta', $cuentas, (isset($detalle['id_cuenta'])) ? $detalle['id_cuenta'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Observaciones</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('observaciones', (isset($detalle['observaciones'])) ? $detalle['observaciones'] : null, 'class="form-control col-sm-12"'); ?>
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