<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Plantillas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('emailer/templates/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar plantilla</a>
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
	                                        <th>Plantilla</th>
	                                        <?php if ($this->usuario->perfil == 'reseller'): ?><th>Empresa</th><?php endif; ?>
	                                        <th>Fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($templates as $item) { ?>
		                                    <tr>
			                                    <td>
				                                    <a href="<?php echo base_url('emailer/templates/detalle/'); ?><?php echo $item['id']; ?>"><?php echo $item['template']; ?></a>
				                                </td>
				                                <?php if ($this->usuario->perfil == 'reseller'): ?><td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $item['id_empresa']; ?>"><?php echo $item['empresa']; ?></a></td><?php endif; ?>
		                                        <td>
				                                    <?php echo formatear_fecha($item['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>
													<br>
													<small><?php echo formatear_fecha($item['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
				                                </td>
		                                        <td class="text-center"><span class="label <?php echo $item['estado_ui_class']; ?>"><?php echo $item['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="<?php echo ($this->usuario->perfil == 'reseller') ? 4 : 3; ?>"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>