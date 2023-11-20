<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Cuentas</strong>
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
	                                        <th>Titular</th>
	                                        <th>Empresa</th>
	                                        <th class="text-center">CBU</th>
	                                        <th class="text-center">Fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($cuentas as $cuenta) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/cuentas/detalle/'); ?><?php echo $cuenta['id']; ?>"><?php echo $cuenta['titular']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $cuenta['id_empresa']; ?>"><?php echo $cuenta['empresa']; ?></a></td>
		                                        <td class="text-center"><?php echo $cuenta['cbu']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($cuenta['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><span class="label <?php echo $cuenta['estado_ui_class']; ?>"><?php echo $cuenta['estado']; ?></span></td>
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