<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/listas'); ?>">Listas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a href="<?php echo base_url('emailer/listas/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar lista</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="alert alert-info">
						<?php echo '<pre>' . print_r($detalle, true) . '</pre>'; ?>
				</div>
				
				<?php if (isset($contactos)) { ?>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped">
	                                    <thead>
	                                    <tr>
	                                        <th>Contacto</th>
	                                        <?php if ($this->usuario->perfil == 'reseller'): ?><th>Empresa</th><?php endif; ?>
	                                        <th>Teléfono</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($contactos as $contacto) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['contacto']; ?></a></td>
		                                        <?php if ($this->usuario->perfil == 'reseller'): ?><td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $contacto['id_empresa']; ?>"><?php echo $contacto['empresa']; ?></a></td><?php endif; ?>
		                                        <td>
			                                        <?php if (isset($contacto['telefono'])) { ?>
		                    	                    	<i class="fa fa-phone"></i> <?php echo $contacto['telefono']; ?>
		                    	                    <?php } ?>
		                    	                </td>
				                                <td class="text-center"><span class="label <?php echo $contacto['estado_ui_class']; ?>"><?php echo $contacto['estado']; ?></span></td>
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
	            <?php } ?>
	            
	        </div>