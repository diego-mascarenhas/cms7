<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Prospectos</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Prospectos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/empresas/ingresar/'); ?>" class="btn btn-primary btn-sm">Nuevo prospecto</a>
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
	                                        <th>Prospecto</th>
	                                        <th class="text-center">Teléfono</th>
	                                        <th class="text-center">Vendedores Asociados</th>
	                                        <th>Fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($empresas as $empresa) { ?>
		                                    <tr>
		                                        <td>
			                                        <a href="<?php echo base_url('prospectos/detalle/'); ?><?php echo $empresa['id']; ?>"><?php echo $empresa['empresa']; ?></a>
			                                        <br>
			                                        <small><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $empresa['id_contacto']; ?>"><?php echo $empresa['contacto']; ?></a></small>
		                                        </td>
		                                        <td class="text-center"><?php if (isset($empresa['telefono'])) echo '<i class="fa fa-phone"></i> ' . $empresa['telefono']; ?></td>
		                                        <td class="text-center">
			                                        <?php echo (isset($empresa['agentes'])) ? $empresa['agentes'] : '<strong>Prospecto sin asignar</strong>'; ?>
			                                    </td>
			                                    <td>
				                                    <?php echo formatear_fecha($empresa['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($empresa['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
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