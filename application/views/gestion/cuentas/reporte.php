<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Gestión</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('gestion'); ?>">Gestión administrativa</a>
	                    </li>
	                    <li class="active">
	                        <strong>Reporte de cuentas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a href="<?php echo base_url('gestion/cuentas/conciliar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Conciliar cuenta</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
<!--
				<div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-4">
	                        <div class="form-group">
	                            <label class="control-label" for="product_name">Desde</label>
	                            <div class="input-group date dia">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
            					</div>
	                        </div>
	                    </div>
	                    <div class="col-sm-4">
	                        <div class="form-group">
	                            <label class="control-label" for="product_name">Hasta</label>
	                            <div class="input-group date dia">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="hasta" value="<?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>">
            					</div>
	                        </div>
	                    </div>
	                </div>
	            </div>
-->
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
		                    <div class="ibox-title">
	                            <h5><?php echo $detalle['cuenta']; ?></h5>
	                        </div>
	                        <div class="ibox-content">
<!--
		                        <div class="row">
			                        <label class="col-sm-2 control-label">Desde</label>
	                                <div class="col-sm-4 m-b-xs">
		                                <div class="input-group date dia">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
                    					</div>
                    				</div>
                    				<label class="col-sm-2 control-label">Hasta</label>
	                                <div class="col-sm-4 m-b-xs">
		                                <div class="input-group date dia">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
                    					</div>
                    				</div>
	                            </div>
-->
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th>Fecha</th>
	                                        <th>Empresa</th>
	                                        <th class="text-right">Usuario</th>
	                                        <th class="text-right">Ingreso</th>
	                                        <th class="text-right">Gasto</th>
	                                        <th class="text-right">Total</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($reporte)) { ?>
			                                	<?php foreach ($reporte as $item) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($item['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
			                                        <td>
				                                        <?php if (!isset($item['id_empresa'])) { ?>
				                                        	Transferencia entre cuentas
				                                        <?php } else { ?>
					                                        <a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $item['id_empresa']; ?>"><?php echo $item['empresa']; ?></a>
														<?php } ?>
					                                </td>
			                                        <td class="text-right"><?php echo $item['username_alta']; ?></td>
			                                        <td class="text-right"><?php echo ($item['transaccion'] == 'I') ? '$' . $item['valor'] : null; ?></td>
			                                        <td class="text-right"><?php echo ($item['transaccion'] == 'G') ? '$' . $item['valor'] : null; ?></td>
			                                        <td class="text-right"><?php echo ($item['id_estado'] == 2) ? '$' . $item['total'] : null; ?></td>
			                                        <td class="text-center"><span class="label <?php echo $item['estado_ui_class']; ?>"><?php echo $item['estado']; ?></span></td>
			                                    </tr>
												<? } ?>
											<?php } else { ?>
												<tr>
				                                    <td colspan="7">No se encontraron registros</td>
												</tr>
											<?php } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	
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