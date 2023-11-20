<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-12">
	                <h2>Hosting</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Alertas</strong>
	                    </li>
	                </ol>
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
	                                        <th>Host</th>
	                                        <th>Contacto</th>
	                                        <th class="text-center">Enviado</th>
	                                        <th class="text-center">Recibido</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($alertas as $item) { ?>
		                                    <tr>
			                                    <td>
													<?php echo formatear_fecha($item['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($item['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
												</td>
			                                    <td>
				                                    <?php echo $item['host']; ?>
				                                    <br>
				                                    <small><?php echo $item['tipo']; ?></small>
				                                </td>
			                                    <td><?php echo $item['contacto']; ?></td>
												<td class="text-center">
													<?php echo formatear_fecha($item['enviado'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($item['enviado'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
												</td>
												<td class="text-center">
													<?php echo formatear_fecha($item['recibido'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($item['recibido'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
												</td>
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