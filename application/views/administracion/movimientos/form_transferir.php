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
	                        <strong>Transferir</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Transferir</h5>
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
	                                <div class="form-group">
			                            <label class="col-sm-2 control-label">Fecha débito</label>
			                            <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_debito" value="<?php echo (isset($detalle['fecha_debito'])) ? formatear_fecha($detalle['fecha_debito'], 'd-m-Y', null, $this->usuario->timezone) : null; ?>">
		                                	</div>
			                            </div>
			                            <label class="col-sm-2 control-label">Fecha crédito</label>
			                            <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_credito" value="<?php echo (isset($detalle['fecha_credito'])) ? formatear_fecha($detalle['fecha_credito'], 'd-m-Y', null, $this->usuario->timezone) : null; ?>">
		                                	</div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Importe débito</label>
			                            <div class="col-sm-4">
			                                <input type="text" name="valor_debito" class="form-control" value="<?php echo (isset($detalle['valor_debito'])) ? $detalle['valor_debito'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Importe crédito</label>
			                            <div class="col-sm-4">
			                                <input type="text" name="valor_credito" class="form-control" value="<?php echo (isset($detalle['valor_credito'])) ? $detalle['valor_credito'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Cuenta débito</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_cuenta_debito', $cuentas, (isset($detalle['id_cuenta_debito'])) ? $detalle['id_cuenta_debito'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Cuenta crédito</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_cuenta_credito', $cuentas, (isset($detalle['id_cuenta_credito'])) ? $detalle['id_cuenta_credito'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Forma de pago</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_forma_pago', $formas_pago, (isset($detalle['id_forma_pago'])) ? $detalle['id_forma_pago'] : null, 'class="form-control m-b"'); ?>
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