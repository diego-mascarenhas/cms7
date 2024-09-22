<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Empresas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/empresas/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar empresa</a>
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
	                                        <th>Empresa</th>
	                                        <th>Contacto</th>
	                                        <th>Teléfono</th>
	                                        <th>Referido</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($empresas as $empresa) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $empresa['id']; ?>"><?php echo $empresa['empresa']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $empresa['id_contacto']; ?>"><?php echo $empresa['contacto']; ?></a></td>
		                                        <td><?php if (isset($empresa['telefono'])) echo '<i class="fa fa-phone"></i> ' . $empresa['telefono']; ?></td>
		                                        <td>
		                                        	<?php if (isset($empresa['referido'])) : ?>
			                                        	<a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $empresa['id_referido']; ?>"><?php echo $empresa['referido']; ?></a>
				                                    <?php endif; ?>
												</td>
												<td class="text-center"><span class="label <?php echo $empresa['estado_ui_class']; ?>"><?php echo $empresa['estado']; ?></span></td>
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