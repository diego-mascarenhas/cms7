<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Papelera</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Papelera</strong>
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
	                                        <th>Nombre</th>
	                                        <th class="text-center">Tipo</th>
	                                        <th class="text-center">Username</th>
	                                        <th class="text-center">Fecha</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($eliminados)) { ?>
			                                	<?php foreach ($eliminados as $eliminado) { ?>
			                                    <tr>
			                                        <td><?php echo $eliminado['nombre']; ?></td>
			                                        <td class="text-center"><?php echo $eliminado['tipo']; ?></td>
			                                        <td class="text-center"><?php echo $eliminado['username_modificacion']; ?></td>
			                                        <td class="text-center"><?php echo formatear_fecha($eliminado['fecha_modificacion'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
			                                    </tr>
												<? } ?>
											<? } else { ?>
												<tr>
				                                    <td colspan="4">No hay elementos en la papelera</td>
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