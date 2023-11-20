<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-12">
	                <h2>Hosting</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <a href="<?php echo base_url('hosting'); ?>">Hosting</a>
	                    </li>
	                    <li class="active">
	                        <strong>IPs</strong>
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
	                                        <th>Servidor</th>
	                                        <th>Observaciones</th>
	                                        <th class="text-center">Blacklist</th>
	                                        <th class="text-center">Reputación</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($hosting as $item) { ?>
		                                    <tr>
			                                    <td>
				                                    <?php echo $item['descripcion']; ?>
			                                    	<br>
				                                    <small><?php echo $item['servidor']; ?>: <?php echo $item['ip']; ?></small>
				                                </td>
			                                    <td><?php echo $item['observaciones']; ?></td>
			                                    <td class="text-center">
				                                    <?php echo ($item['blacklist']) ? '<i class="fa fa-ban"></i>' : '<i class="fa fa-check"></i>'; ?>
													
													<?php 	if ($item['blacklist_data'])
															{
																$item['blacklist_data'] = json_decode($item['blacklist_data'], true);
																
																foreach ($item['blacklist_data'] as $obj)
																{
																	echo '<a href="' . $obj['DelistUrl'] . '" target="_blank">' . $obj['Name'] . '</a> ';
																}
															}
													?>
				                                </td>
												<td class="text-center"><?php echo $item['reputacion']; ?></td>
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