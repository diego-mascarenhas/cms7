<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/movimientos'); ?>">Movimientos</a>
	                    </li>
	                    <li class="active">
	                        <a href="<?php echo base_url('administracion/empresas/detalle/' . $empresa['id']); ?>"><strong><?php echo $empresa['empresa']; ?></strong></a>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/movimientos/ingresar?id_empresa=' . $empresa['id']); ?>" class="btn btn-primary btn-sm">Ingresar movimiento</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th>Fecha</th>
	                                        <th>Tipo de movimiento</th>
	                                        <th>Comprobante</th>
	                                        <th class="text-right">Importe</th>
	                                        <th class="text-right">Subtotal</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($balance)) { ?>
			                                	<?php foreach (array_reverse($balance) as $movimiento) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
				                                    <td><span class="<?php echo $movimiento['operacion_ui_class']; ?>"><?php echo $movimiento['descripcion']; ?></span></td>
			                                        <td>
				                                        <a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $movimiento['id_factura']; ?>"> <?php echo $movimiento['comprobante']; ?></a>
				                                        
				                                        <?php if ($movimiento['estado'] == 1) { ?>
				                                        	<span class="label label-danger">Pendiente</span>
				                                        <?php } ?>
				                                        <?php if (!isset($movimiento['comprobante']) && $movimiento['tipo'] == 'MOV') { ?>
				                                        	<a href="<?php echo base_url('administracion/movimientos/modificar/' . $movimiento['id']); ?>"> <span class="label label-warning">Asignar pago</span></a>
				                                        <?php } ?>
				                                    </td>
													<td class="text-right <?php echo $movimiento['operacion_ui_class']; ?>"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
			                                        <td class="text-right"><span class="<?php echo $movimiento['subtotal_ui_class']; ?>"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['subtotal']; ?></span></td>
			                                    </tr>
												<? } ?>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="5"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>