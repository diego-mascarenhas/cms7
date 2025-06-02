<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Facturas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/facturas/?estado=2&pendiente=true&operacion=c'); ?>" class="btn btn-danger btn-sm">Pendientes de Compra</a>
                        <a href="<?php echo base_url('administracion/facturas/?estado=2&pendiente=true&operacion=v'); ?>" class="btn btn-success btn-sm">Pendientes de Venta</a>
                        <a href="<?php echo base_url('administracion/facturas/?estado=1'); ?>" class="btn btn-warning btn-sm">Sin imprimir</a>
                        <a href="<?php echo base_url('administracion/facturas/?estado=8'); ?>" class="btn btn-info btn-sm">Nuevas facturas</a>
                        <a href="<?php echo base_url('administracion/facturas/?estado=3'); ?>" class="btn btn-default btn-sm">Facturas anuladas</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
				<!-- Totales de facturación -->
				<div class="row">
					<div class="col-lg-3">
						<div class="widget style1 navy-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-money fa-4x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span>Total facturado (Ventas)</span>
									<h2 class="font-bold">ARS $<?php echo number_format(isset($totales['total_ventas']) ? $totales['total_ventas'] : 0, 0, ',', '.'); ?></h2>
									<small>Mes anterior: ARS $<?php echo number_format(isset($totales['total_ventas_anterior']) ? $totales['total_ventas_anterior'] : 0, 0, ',', '.'); ?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="widget style1 red-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-shopping-cart fa-4x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span>Total facturado (Compras)</span>
									<h2 class="font-bold">ARS $<?php echo number_format(isset($totales['total_compras']) ? $totales['total_compras'] : 0, 0, ',', '.'); ?></h2>
									<small>Mes anterior: ARS $<?php echo number_format(isset($totales['total_compras_anterior']) ? $totales['total_compras_anterior'] : 0, 0, ',', '.'); ?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="widget style1 lazur-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-arrow-circle-o-down fa-4x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span>Pendiente de cobro</span>
									<h2 class="font-bold">ARS $<?php echo number_format(isset($totales['pendiente_cobro']) ? $totales['pendiente_cobro'] : 0, 0, ',', '.'); ?></h2>
									<small>Mes anterior: ARS $<?php echo number_format(isset($totales['pendiente_cobro_anterior']) ? $totales['pendiente_cobro_anterior'] : 0, 0, ',', '.'); ?></small>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="widget style1 yellow-bg">
							<div class="row">
								<div class="col-xs-4">
									<i class="fa fa-arrow-circle-o-up fa-4x"></i>
								</div>
								<div class="col-xs-8 text-right">
									<span>Pendiente de pago</span>
									<h2 class="font-bold">ARS $<?php echo number_format(isset($totales['pendiente_pago']) ? $totales['pendiente_pago'] : 0, 0, ',', '.'); ?></h2>
									<small>Mes anterior: ARS $<?php echo number_format(isset($totales['pendiente_pago_anterior']) ? $totales['pendiente_pago_anterior'] : 0, 0, ',', '.'); ?></small>
								</div>
							</div>
						</div>
					</div>
				</div>
				
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Comprobante</th>
	                                        <th>Empresa</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Vencimiento</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($facturas as $factura) { ?>
		                                    <tr>
			                                    <td>
				                                    <a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $factura['id']; ?>"><?php echo $factura['comprobante']; ?></a>
			                                    	<br><small><?php echo date('d-m-Y', strtotime($factura['fecha_real'])); ?></small>
			                                    	</td>
		                                        <td>
			                                        <a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $factura['id_empresa']; ?>"><?php echo $factura['empresa']; ?></a>
		                                        	<br>
													<small><?php echo $factura['razon_social']; ?></small></td>
		                                        <td><?php echo $factura['forma_pago']; ?><br><span class="badge <?php echo $factura['operacion_ui_class']; ?>"><?php echo $factura['operacion']; ?></span></td>
		                                        <td class="text-right">
			                                        <?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?>
			                                        <br>
			                                        <small><?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?></small>
		                                        </td>
		                                        <td class="text-center">
			                                        <?php 
			                                        if (!empty($factura['vencimiento_real'])) {
			                                            echo date('d-m-Y', strtotime($factura['vencimiento_real']));
			                                        }
			                                        ?>
			                                        <?php
			                                        	if (isset($factura['recibido']))
			                                        	{
				                                        	echo date('d-m-Y', $factura['recibido']);
				                                        	echo '<br><small><em>(Recibida)</em></small>';
				                                        }
				                                        elseif (isset($factura['enviado']))
				                                        {
					                                        echo date('d-m-Y', $factura['enviado']);
					                                        echo '<br><small><em>(Enviada)</em></small>';
				                                        }
					                                ?>
			                                    </td>
		                                        <td class="text-center">
		                                            <span class="label <?php echo $factura['estado_ui_class']; ?>"><?php echo $factura['estado']; ?></span>
		                                            <?php if ($factura['estado'] == 'Imprimir'): ?>
		                                            <a href="<?php echo base_url('administracion/facturas/marcar_como_anulada/'); ?><?php echo $factura['id']; ?>" class="btn btn-xs btn-danger m-l-xs"><i class="fa fa-ban"></i> Anular</a>
		                                            <?php endif; ?>
		                                        </td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="6"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>