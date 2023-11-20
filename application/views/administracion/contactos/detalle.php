<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/contactos'); ?>">Contactos</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=111&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
                        <?php } ?>
                        <a href="<?php echo base_url('administracion/contactos/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar contacto</a>
                    </div>
                </div>
                <div class="col-xs-12">
	                <?php if (isset($notas)) { ?>
				        <ul class="notes">
	                        <?php foreach ($notas as $nota) { ?>
	                        <li>
	                            <div>
	                                <small><?php echo $nota['contacto']; ?>  <?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
	                                <h4><?php echo $nota['titulo']; ?></h4>
	                                <p><?php echo ellipsize($nota['descripcion'], 100); ?></p>
	                                <a href="<?php echo base_url('notas/modificar/' . $nota['id']); ?>"><i class="fa fa-edit"></i></a>
	                            </div>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                <?php } ?>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">

	            <div class="row m-b-lg m-t-lg">
	                <div class="col-md-6">
	                    <div class="profile-info">
	                        <div>
	                            <div>
	                                <h2 class="no-margins">
	                                    <?php echo $detalle['contacto']; ?>
	                                </h2>
	                                <h4>
		                                <?php if (isset($detalle['perfil'])) echo $detalle['perfil'] . '<br>'; ?>
										<small>
	                                    	<a href="<?php echo base_url('administracion/empresas/detalle/' . $detalle['id_empresa']); ?>"><?php echo $detalle['empresa']; ?></a>
		                                </small>
	                                </h4>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-6">
	                    <table class="table m-b-xs">
	                        <tbody>
		                    <?php if (isset($detalle['telefono'])) { ?>
	                        <tr>
	                            <td>
	                                <span class="fa fa-phone"></span> <a href="<?php echo base_url('voip/llamar/' . $detalle['id']); ?>"><?php echo $detalle['telefono']; ?></a>
	                            </td>
	                        </tr>
	                        <?php } ?>
	                        <?php if (isset($detalle['email'])) { ?>
	                        <tr>
	                            <td>
	                                <span class="fa fa-envelope"></span> <a href="mailto:<?php echo $detalle['email']; ?>"><?php echo $detalle['email']; ?></a>
	                            </td>
	                        </tr>
	                        <? } ?>
	                        <?php if (isset($detalle['username'])) { ?>
		                        <tr>
		                            <td>
		                                <?php echo '<span class="fa fa-user"></span> ' . $detalle['username']; ?>
		                                <?php if (isset($reseller)) { ?>
		                                	&nbsp;
		                                	<a href="<?php echo base_url('user/login?username=' . $detalle['username'] . '&password=' . $detalle['hash'] . '&reseller=' . $reseller); ?>"><span class="fa fa-sign-in"></span></a>
		                                	&nbsp;&nbsp;
		                                	<a href="<?php echo base_url('administracion/contactos/password-reset/' . $detalle['id']); ?>"><span class="fa fa-unlock-alt"></span></a>
		                                <?php } ?>
		                            </td>
		                        </tr>
		                        <?php if (isset($detalle['ultima_visita'])) { ?>
		                        <tr>
		                            <td>        
		                                <span class="fa fa-clock-o"></span> <?php echo formatear_fecha($detalle['ultima_visita'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone, null, array('default'=>'Aún no se ha conectado')); ?> (<a href="<?php echo base_url('mailbox?search=' . $detalle['ip']); ?>"><?php echo $detalle['ip']; ?></a>)
		                                
		                            </td>
		                        </tr>
		                        <?php } ?>
	                        <?php } ?>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	            
	            <?php if (verificarPermiso('tickets', $this->session->menu)) { ?>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox <?php echo (isset($tickets)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Tickets</h5>
		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('tickets/ingresar?id_empresa=' . $detalle['id_empresa'] . '&id_contacto=' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> Ingresar ticket</i>
		                            </a>
		                            <a href="<?php echo base_url('tickets/get-efectividad-agente/' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-bar-chart-o"> Balance</i>
		                            </a>
		                        </div>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Fecha</th>
	                                        <th>Asunto</th>
	                                        <th class="text-center">Agentes Asociados</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($tickets)) { ?>
			                                	<?php foreach ($tickets as $ticket) { ?>
			                                    <tr>
				                                    <td>
					                                    <?php echo formatear_fecha($ticket['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>
														<br>
														<small><?php echo formatear_fecha($ticket['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
					                                </td>
				                                    <td>
					                                    <a href="<?php echo base_url('tickets/detalle/') ?><?php echo $ticket['id']; ?>"><?php echo $ticket['asunto']; ?></a>
														<br>
														<small><span class="badge <?php echo $ticket['prioridad_ui_class']; ?>"><?php echo $ticket['prioridad']; ?></span> <strong><?php echo $ticket['grupo']; ?>:</strong> <?php echo $ticket['area']; ?></small>
					                                </td>
			                                        <td class="text-center">
				                                        <?php echo (isset($ticket['agentes'])) ? $ticket['agentes'] : '<strong>Ticket sin asignar</strong>'; ?>
														<br>
														<small><em>(Respuestas: <?php echo $ticket['respuestas']; ?>)</em></small>
				                                    </td>
			                                        <td class="text-center">
				                                        <span class="label <?php echo $ticket['estado_ui_class']; ?>"><?php echo $ticket['estado']; ?></span>
				                                        <?php if (isset($ticket['efectividad'])) { ?>
															<br>
															<small><em>(Efectividad: <?php echo $ticket['efectividad']; ?>%)<em></small>
														<?php } ?>
				                                    </td>
			                                    </tr>
			                                    <? } ?>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	            <?php if (verificarPermiso('comunicaciones', $this->session->menu)) { ?>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox <?php echo (isset($comunicaciones)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Comunicaciones</h5>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Asunto</th>
	                                        <th>Enviado</th>
	                                        <th>Recibido</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($comunicaciones)) { ?>
			                                	<?php foreach ($comunicaciones as $comunicacion) { ?>
			                                    <tr>
				                                    <td><a href="<?php echo base_url('administracion/comunicaciones/detalle/') ?><?php echo $comunicacion['id']; ?>"><?php echo $comunicacion['asunto']; ?></a></td>
			                                        <td><?php echo formatear_fecha($comunicacion['enviado'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
			                                        <td><?php echo formatear_fecha($comunicacion['recibido'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
			                                        <td class="text-center"><span class="label <?php echo $comunicacion['estado_ui_class']; ?>"><?php echo $comunicacion['estado']; ?></span></td>
			                                    </tr>
			                                    <? } ?>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	            <?php if (verificarPermiso('voip', $this->session->menu)) { ?>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox <?php echo (isset($llamadas)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Llamadas</h5>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Fecha</th>
	                                        <th>Contacto</th>
	                                        <th class="text-center">Agente</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($llamadas)) { ?>
			                                	<?php foreach ($llamadas as $llamada) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($llamada['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
				                                    <td><?php echo $llamada['contacto']; ?></td>
			                                        <td class="text-center"><?php echo $llamada['agente']; ?></td>
			                                        <td class="text-center"><span class="label <?php echo $llamada['estado_ui_class']; ?>"><?php echo $llamada['estado']; ?></span></td>
			                                    </tr>
			                                    <? } ?>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>

	        </div>