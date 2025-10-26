<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Contactos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/contactos/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar contacto</a>
                    </div>
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
	                                        <th>Contacto</th>
	                                        <?php if ($this->usuario->perfil == 'reseller'): ?><th>Empresa</th><?php endif; ?>
	                                        <th>Teléfono</th>
	                                        <th class="text-center">Ultima visita</th>
	                                        <th class="text-center">Acciones</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($contactos as $contacto) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['contacto']; ?></a></td>
		                                        <?php if ($this->usuario->perfil == 'reseller'): ?><td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $contacto['id_empresa']; ?>"><?php echo $contacto['empresa']; ?></a></td><?php endif; ?>
		                                        <td><?php if (isset($contacto['telefono'])) { ?>
		                    	                    <i class="fa fa-phone"></i> <a href="<?php echo base_url('voip/llamar/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['telefono']; ?></a>
		                    	                    <?php } ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($contacto['ultima_visita'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
		                                        <td class="text-center">
			                                        <?php if (verificarPermiso('tickets', $this->session->menu)) { ?>
			                                        	<a href="<?php echo base_url('tickets/ingresar?id_empresa=' . $contacto['id_empresa'] . '&id_contacto=' . $contacto['id']); ?>"><i class="fa fa-ticket"></i></a>
			                                        <?php } ?>
			                                        <?php if (isset($contacto['username'])) { ?>
			                                        	&nbsp;&nbsp;
														<a href="<?php echo base_url('administracion/contactos/password-reset/' . $contacto['id']); ?>"><span class="fa fa-unlock-alt"></span></a>
			                                        <?php } ?>
				                                </td>
				                                <td class="text-center"><span class="label <?php echo $contacto['estado_ui_class']; ?>"><?php echo $contacto['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="<?php echo ($this->usuario->perfil == 'reseller') ? 6 : 5; ?>"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>