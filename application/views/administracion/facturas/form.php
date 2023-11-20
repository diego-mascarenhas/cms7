<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/facturas'); ?>">Facturas</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva factura' : 'Modificar factura'; ?></h5>
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
	                            	
	                            <?php if (empty($detalle['id'])) { ?>
	                            	<input type="hidden" name="id_empresa_fiscal" value="<?php echo (isset($detalle['id_empresa_fiscal'])) ? $detalle['id_empresa_fiscal'] : null; ?>">
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Operación</label>
	                                    <div class="col-sm-4">
		                                    <div class="radio radio-inline">
		                                    	<input type="radio" name="operacion" value="C" <?php if (isset($detalle['operacion']) && $detalle['operacion'] == 'C') echo 'checked="checked"'; ?>>
		                                    	<label>Compra</label>
		                                    </div>
		                                    <div class="radio radio-inline">
		                                        <input type="radio" name="operacion" value="V" <?php if (isset($detalle['operacion']) && $detalle['operacion'] == 'V') echo 'checked="checked"'; ?>>
		                                        <label>Venta</label>
		                                    </div>
	                                    </div>
			                            <label class="col-sm-2 control-label">Tipo de Factura</label>
	                                    <div class="col-sm-4">
		                                    <div class="radio radio-inline">
			                                    <?php echo form_dropdown('id_factura_tipo', $facturas_tipo, (isset($detalle['id_factura_tipo'])) ? $detalle['id_factura_tipo'] : null, 'class="form-control m-b"'); ?>
		                                    </div>
	                                    </div>
	                                </div>
	                                <div class="hr-line-dashed"></div>
	                                
	                                <div class="form-group">
			                            <label class="col-sm-2 control-label">Talonario</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="numero_talonario" class="form-control" value="<?php echo (isset($detalle['numero_talonario'])) ? $detalle['numero_talonario'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Número</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="numero_factura" class="form-control" value="<?php echo (isset($detalle['numero_factura'])) ? $detalle['numero_factura'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Fecha</label>
			                            <div class="col-sm-2">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha" value="<?php echo formatear_fecha($detalle['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?>">
		                                	</div>
			                            </div>
			                            <label class="col-sm-2 control-label">Vencimiento</label>
			                            <div class="col-sm-2">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="vencimiento" value="<?php echo (isset($detalle['vencimiento'])) ? $detalle['vencimiento'] : null; ?>">
		                                	</div>
			                            </div>
			                            <label class="col-sm-2 control-label">Presentación</label>
			                            <div class="col-sm-2">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="presentacion" value="<?php echo (isset($detalle['presentacion'])) ? $detalle['presentacion'] : null; ?>">
		                                	</div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Moneda</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_moneda', $monedas, (isset($detalle['id_moneda'])) ? $detalle['id_moneda'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Forma de pago</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_forma_pago', $formas_pago, (isset($detalle['id_forma_pago'])) ? $detalle['id_forma_pago'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <?php if (empty($detalle['id'])) { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Categoría</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_categoria', $categorias_generales, null, 'class="form-control m-b"'); ?>
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
		                            <?php } ?>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Descuento</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="descuento" class="form-control" value="<?php echo (isset($detalle['descuento'])) ? $detalle['descuento'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Valor sin I.V.A.</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="bruto" class="form-control" value="<?php echo (isset($detalle['bruto'])) ? $detalle['bruto'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Subtotal I.V.A. %10,5</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="SUBTOTAL105" class="form-control" value="<?php echo (isset($detalle['SUBTOTAL105'])) ? $detalle['SUBTOTAL105'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">I.V.A. %10,5</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="IMP105" class="form-control" value="<?php echo (isset($detalle['IMP105'])) ? $detalle['IMP105'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Concapetos no gravados</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="NO_GRAVADOS105" class="form-control" value="<?php echo (isset($detalle['NO_GRAVADOS105'])) ? $detalle['NO_GRAVADOS105'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Subtotal I.V.A. %21</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="SUBTOTAL210" class="form-control" value="<?php echo (isset($detalle['SUBTOTAL210'])) ? $detalle['SUBTOTAL210'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">I.V.A. %21</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="IMP210" class="form-control" value="<?php echo (isset($detalle['IMP210'])) ? $detalle['IMP210'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Concapetos no gravados</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="NO_GRAVADOS210" class="form-control" value="<?php echo (isset($detalle['NO_GRAVADOS210'])) ? $detalle['NO_GRAVADOS210'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Subtotal I.V.A. %27</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="SUBTOTAL270" class="form-control" value="<?php echo (isset($detalle['SUBTOTAL270'])) ? $detalle['SUBTOTAL270'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">I.V.A. %27</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="IMP270" class="form-control" value="<?php echo (isset($detalle['IMP270'])) ? $detalle['IMP270'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Concapetos no gravados</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="NO_GRAVADOS270" class="form-control" value="<?php echo (isset($detalle['NO_GRAVADOS270'])) ? $detalle['NO_GRAVADOS270'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Exento I.V.A.</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="EXENTO" class="form-control" value="<?php echo (isset($detalle['EXENTO'])) ? $detalle['EXENTO'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Retenci&oacute;n I.V.A.</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="RETENCION_IVA" class="form-control" value="<?php echo (isset($detalle['RETENCION_IVA'])) ? $detalle['RETENCION_IVA'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Retenci&oacute;n IIBB</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="RETENCION_IIBB" class="form-control" value="<?php echo (isset($detalle['RETENCION_IIBB'])) ? $detalle['RETENCION_IIBB'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Retenciones generales</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="RETENCIONES_GENERALES" class="form-control" value="<?php echo (isset($detalle['RETENCIONES_GENERALES'])) ? $detalle['RETENCIONES_GENERALES'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Percepci&oacute;n IIBB</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="PERCEPCION_IIBB" class="form-control" value="<?php echo (isset($detalle['PERCEPCION_IIBB'])) ? $detalle['PERCEPCION_IIBB'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Total neto</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="total_neto" class="form-control" value="<?php echo (isset($detalle['total_neto'])) ? $detalle['total_neto'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <?php } else { ?>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Talonario</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="numero_talonario" class="form-control" value="<?php echo (isset($detalle['numero_talonario'])) ? $detalle['numero_talonario'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Número</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="numero_factura" class="form-control" value="<?php echo (isset($detalle['numero_factura'])) ? $detalle['numero_factura'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">CAE</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="cae_numero" class="form-control" value="<?php echo (isset($detalle['cae_numero'])) ? $detalle['cae_numero'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Vencimiento</label>
		                                <div class="col-sm-4">
			                                <div class="input-group date dia">
		                                    	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="cae_vencimiento" value="<?php echo (isset($detalle['cae_vencimiento'])) ? date('d-m-Y', strtotime($detalle['cae_vencimiento'])) : null; ?>">
		                                	</div>
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