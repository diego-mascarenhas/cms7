<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

				<div class="row wrapper border-bottom white-bg page-heading">
		            <div class="col-lg-12">
		                <h2>Mailer</h2>
		                <ol class="breadcrumb">
		                    <li>
		                        <a href="<?php echo base_url(); ?>">Home</a>
		                    </li>
		                    <li class="active">
		                        <strong>Errores</strong>
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
		                                        <th>IP</th>
		                                        <th>Email</th>
		                                        <th>Error</th>
		                                        <th class="text-center">Estado</th>
		                                    </tr>
		                                    </thead>
		                                    <tbody>
			                                	<?php foreach ($errores as $error) { ?>
			                                    <tr>
				                                    <td><?php echo $error['host']; ?></td>
					                                <td>
						                                <?php echo $error['email']; ?>
														<br>
														<small><strong>ID Error: </strong><?php echo $error['id_error']; ?></small>
						                            </td>
					                                <td><small><?php echo strip_tags($error['error']); ?></small></td>
			                                        <td class="text-center"><small><span class="label <?php echo $error['estado_ui_class']; ?>"><?php echo $error['estado']; ?></span></td>
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