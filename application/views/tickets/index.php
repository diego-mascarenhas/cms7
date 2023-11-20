<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

				<div class="row wrapper border-bottom white-bg page-heading">
		            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
		                <h2>Tickets</h2>
		                <ol class="breadcrumb">
		                    <li>
		                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
		                    </li>
		                    <li class="active">
		                        <strong><?php echo $this->lang->line('cms_tickets'); ?></strong>
		                    </li>
		                </ol>
		            </div>
		            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
	                    <div class="title-action">
	                        <a href="<?php echo ($this->usuario->perfil == 'reseller') ? base_url('administracion/contactos/?order_by=nombre&order=ASC') : base_url('tickets/ingresar/'); ?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('cms_users-crear-ticket'); ?></a>
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
		                                        <th><?php echo $this->lang->line('cms_users-fecha'); ?></th>
		                                        <th><?php echo $this->lang->line('cms_users-asunto'); ?></th>
		                                        <?php if ($this->usuario->perfil == 'reseller') { ?><th class="text-center"><?php echo $this->lang->line('variable_name'); ?>Empresa</th><?php } ?>
		                                        <th class="text-center"><?php echo $this->lang->line('cms_users-agentes-asociados'); ?></th>
		                                        <th class="text-center"><?php echo $this->lang->line('cms_users-estados-ticket'); ?></th>
		                                    </tr>
		                                    </thead>
		                                    <tbody>
			                                	<?php foreach ($tickets as $ticket) { ?>
			                                    <tr>
				                                    <td>
					                                    <?php echo formatear_fecha($ticket['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>
														<br>
														<small><?php echo formatear_fecha($ticket['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
					                                </td>
				                                    <td>
					                                    <a href="<?php echo base_url('tickets/detalle/' . $ticket['id']); ?>"><?php echo $ticket['asunto']; ?></a>
														<br>
														<small><span class="badge <?php echo $ticket['prioridad_ui_class']; ?>"><?php echo $ticket['prioridad']; ?></span> <strong><?php echo $ticket['grupo']; ?>:</strong> <?php echo $ticket['area']; ?></small>
					                                </td>
			                                        <?php if ($this->usuario->perfil == 'reseller') { ?>
		                                        	<td class="text-center">
				                                        <a href="<?php echo base_url('administracion/empresas/detalle/' . $ticket['id_empresa']); ?>"><?php echo $ticket['empresa']; ?></a>
				                                        <br>
				                                        <small><a href="<?php echo base_url('administracion/contactos/detalle/' . $ticket['username_alta']); ?>"><?php echo $ticket['contacto']; ?></a></small>
			                                        </td>
				                                    <?php } ?>
			                                        <td class="text-center">
				                                        <?php echo (isset($ticket['agentes'])) ? $ticket['agentes'] : '<strong>' . $this->lang->line('cms_users-tickets-sin-asignar') . '</strong>'; ?>
														<br>
														<small><em>(<?php echo $this->lang->line('cms_users-respuestas'); ?>: <?php echo $ticket['respuestas']; ?>)<em></small>
				                                    </td>
			                                        <td class="text-center">
				                                        <span class="label <?php echo $ticket['estado_ui_class']; ?>"><?php echo $ticket['estado']; ?></span>
														<?php if (isset($ticket['efectividad'])) { ?>
															<br>
															<small><em>(<?php echo $this->lang->line('cms_users-efectividad'); ?>: <?php echo $ticket['efectividad']; ?>%)<em></small>
														<?php } ?>
				                                    </td>
			                                    </tr>
												<? } ?>
		                                    </tbody>
		                                    <tfoot>
			                                    <tr>
				                                    <td colspan="<?php echo ($this->usuario->perfil == 'reseller') ? 5 : 4; ?>"><?php if (isset($paginado)) echo $paginado; ?></td>
			                                    </tr>
		                                    </tfoot>
		                                </table>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>