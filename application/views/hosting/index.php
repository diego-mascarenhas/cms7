<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Hosting</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Planes</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('hosting/?estado=4'); ?>" class="btn btn-default btn-sm">Activos</a>
                        <a href="<?php echo base_url('hosting/?estado=2'); ?>" class="btn btn-warning btn-sm">Suspender</a>
                        <a href="<?php echo base_url('hosting/?estado=3'); ?>" class="btn btn-success btn-sm">Activar</a>
                        <a href="<?php echo base_url('hosting/?estado=1'); ?>" class="btn btn-default btn-sm">Eliminados</a>
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
	                                        <th>Dominio</th>
	                                        <th>Empresa</th>
	                                        <th class="text-center">Consumos</th>
	                                        <th class="text-center">Actualizado</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($hosting as $item) { ?>
		                                    <tr>
			                                    <td>
				                                    <a href="<?php echo base_url('hosting/detalle/' . $item['id']); ?>"><?php echo $item['domain']; ?></a>
			                                    	<br>
			                                    	<?php if (isset($item['ip'])) { ?>
				                                    	<small><?php echo $item['ip']; ?> (<?php echo $item['servidor']; ?>)</small>
				                                    <?php } else { ?>
				                                    	<small>Dominio vencido o sin delegar</small>
				                                    <?php } ?>
				                                </td>
			                                    <td>
				                                    <a href="<?php echo base_url('administracion/empresas/detalle/' . $item['id_empresa']); ?>"><?php echo $item['empresa']; ?></a>
				                                    <br>
				                                    <small><a href="<?php echo base_url('administracion/servicios/detalle/' . $item['id_servicio']); ?>"><?php echo $item['plan']; ?></a></small>
				                                    </td>
			                                    <td class="text-center">
				                                    <?php if (isset($item['ip'])) { ?>
				                                    <span>
									                    <i class="fa fa-cloud"></i> <?php echo $item['diskused_porcentaje']; ?>% <i class="fa fa-exchange"></i>  <?php echo $item['bandwidthused_porcentaje']; ?>%
					                                </span>
				                                    <div class="progress progress-mini">
														<div style="width: <?php echo $item['porcentaje']; ?>%;" class="progress-bar <?php echo $item['progress_ui_class']; ?>"></div>
					                                </div>
					                                <?php } else { ?>
					                                -
					                                <?php } ?>
			                                    </td>
												<td class="text-center">
													<?php echo formatear_fecha($item['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($item['fecha'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
												</td>
												<td class="text-center"><span class="label <?php echo $item['estado_ui_class']; ?>"><?php echo $item['estado']; ?></span></td>
		                                    </tr>
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