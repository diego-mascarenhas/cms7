<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Gestión</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('gestion'); ?>">Gestión administrativa</a>
	                    </li>
	                    <li class="active">
	                        <strong>Débito automático</strong>
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
	                                <table class="table table-striped">
	                                    <thead>
	                                    <tr>
	                                        <th>Código</th>
	                                        <th>Empresa</th>
	                                        <th class="text-center">Facturas</th>
	                                        <th class="text-center">Desde</th>
	                                        <th class="text-right">Saldo</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($debitos as $debito) { ?>
		                                    <tr>
		                                        <td><?php echo $debito['codigo']; ?></td>
		                                        <td><?php echo $debito['empresa']; ?></td>
<!-- 		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/' . $debito['id_empresa']); ?>"><?php echo $debito['empresa']; ?></a></td> -->
		                                        <td class="text-center"><?php echo $debito['cantidad']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($debito['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-right">$<?php echo $debito['saldo']; ?></td>
		                                    </tr>
											<? } ?>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="text-right"><strong>$<?php echo $total; ?></strong></td>
											</tr>
	                                    </tbody>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>