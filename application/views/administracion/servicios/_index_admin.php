<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Servicios</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Servicios</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
			
			<?php if (isset($servicios)) { ?>
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Descripción</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Próxima</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($servicios as $servicio) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/servicios/detalle/'); ?><?php echo $servicio['id']; ?>"><?php echo strip_tags($servicio['descripcion']); ?></a>
		                                        	<br>
													<small><span class="badge <?php echo $servicio['operacion_ui_class']; ?>"><?php echo $servicio['operacion']; ?></span> <?php echo $servicio['categoria']; ?></small></td>
		                                        <td class="text-right"><?php echo $servicio['simbolo']; ?><strong><?php echo $servicio['total']; ?></strong>
													<?php if ($servicio['descuento_porcentaje'] > 0) : ?>
														<br>
														<small>- <?php echo $servicio['descuento_porcentaje']; ?>% de <?php echo $servicio['simbolo']; ?><?php echo $servicio['valor']; ?></small>
													<?php endif; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($servicio['proxima'], 'd-m-Y', null, $this->usuario->timezone); ?>
		                                        	<br>
		                                        	<small>(<?php echo $servicio['frecuencia']; ?>)</small></td>
		                                        <td class="text-center"><span class="label <?php echo $servicio['estado_ui_class']; ?>"><?php echo $servicio['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="4"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>
	        <?php } ?>