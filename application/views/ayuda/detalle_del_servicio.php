<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Mesa de ayuda</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
                        <li><a href="<?php echo base_url('ayuda'); ?>">Ayuda</a></li>
                        <li class="active"><strong><?php echo urldecode($detalle['problema']); ?></strong></li>
                    </ol>
                </div>
            </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        
			    <div class="row" style="margin-top:25px;">
		            <div class="col-lg-6 text-center">
		                <div class="ibox-content">
		                    <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">Estamos revisando todo esto:</h2>
		                    <small>Si ves algún problema,<strong>clickeá el botón RESOLVER PROBLEMA(S)</strong></small>
							
							<ul style="text-align:left ;width:70%;margin: 0 auto; margin-top:20px; margin-bottom:20px;" class="todo-list m-t">
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Estado del Sitio Web</span>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> ONLINE</small>
	                            </li>
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Bloqueo de la IP</span>
									<small style="float:right; font-size:12px" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> ATENCION</small>
	
	                            </li>
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Vencimiento del Dominio</span>
									<small style="float:right; font-size:12px" class="label label-danger"><i class="fa fa-times-circle"></i> VENCIDO</small>
	                            </li>
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Balance de las Facturas (Pagos)</span>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> AL DIA</small>
	                            </li>
								<li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Uso del Espacio en Disco</span>
									<span class="pie" style="display: none;">0.52/1.561</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 0 1 14.933563796318165 11.990700825968545 Z" fill="#1ab394"></path><path d="M 8 8 L 14.933563796318165 11.990700825968545 A 8 8 0 1 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> NORMAL</small>
	                            </li>
								<li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Uso de la Transferencia</span>
									<span class="pie" style="display: none;">1.32/1.561</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 1 1 1.4006893143306263 3.4779320577848436 Z" fill="#1ab394"></path><path d="M 8 8 L 1.4006893143306263 3.4779320577848436 A 8 8 0 0 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
									<small style="float:right; font-size:12px" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> ATENCION</small>
	                            </li>
								<li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Uso de las Casillas de Email</span>
									<span class="pie" style="display: none;">1.561/1.561</span><svg class="peity" height="16" width="16"><circle cx="8" cy="8" r="8" fill="#1ab394"></circle></svg>
									<small style="float:right; font-size:12px" class="label label-danger"><i class="fa fa-times-circle"></i> PELIGRO</small>
	                            </li>
								<li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Velocidad de Carga del Sitio Web</span>
									<span class="pie" style="display: none;">0.52/1.561</span><svg class="peity" height="16" width="16"><path d="M 8 8 L 8 0 A 8 8 0 0 1 14.933563796318165 11.990700825968545 Z" fill="#1ab394"></path><path d="M 8 8 L 14.933563796318165 11.990700825968545 A 8 8 0 1 1 7.999999999999998 0 Z" fill="#d7d7d7"></path></svg>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> NORMAL</small>
	                            </li>
	                        </ul>
							
							<button style="font-size:18px;" class="btn btn-info hvr-bubble-top hvr-grow-shadow" type="button"><i class="fa fa-medkit" style="font-size:20px; padding-right:10px;"></i>&nbsp;RESOLVER PROBLEMA(S)</button>
		                </div>
		            </div>
	            
					<?php if ($detalle['problema'] == 'hosting') { ?>
					<div class="col-lg-6 text-center">
		                <div class="ibox-content">
		                    <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">Es otro el motivo de tu contacto?</h2>
		                    <small>Por favor ,<strong>clickeá la opción que más aproxime a cual es tu problema</strong></small>
							
							<div style="text-align:left!important; margin-top:20px;" class="dd" id="nestable2">
                                <ol class="dd-list">
									<li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-eye"></i></span><strong>NO VEO MI SITIO WEB</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>NUNCA</strong> puedo verlo!
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>AHORA</strong> no puedo verlo (antes sí!) 
													</div>
												</li>
                                            </a>
											<a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="4">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>VEO UNA PANTALLA DE REVISION ALPHA</strong> 
													</div>
												</li>
                                            </a>
											<li class="dd-item" data-id="4">
												<div class="dd-handle">
													<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>PEGUE EL VINCULO WEB (URL)</strong> 
													<div class="chat-form" style="margin-top:15px; margin-left:30px;">
														<form role="form">
															<div class="form-group">
																<textarea class="form-control" placeholder="www.su-dominio.com"></textarea>
															</div>
															<div class="text-right">
																<button type="submit" class="btn btn-sm btn-primary m-t-n-xs"><strong>ENVIAR</strong></button>
															</div>
														</form>
													</div>
												</div>
											</li>
                                        </ol>
                                    </li>
									<hr>
                                    <li class="dd-item" data-id="2">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-tachometer"></i></span><strong>TARDA MUCHO EN CARGAR</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>SIEMPRE</strong> tarda en cargar?
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>AHORA</strong> tarda en cargar (antes no lo hacía) 
													</div>
												</li>
                                            </a>
                                        </ol>
                                    </li>
									<hr>
                                    <li class="dd-item" data-id="3">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-bullhorn"></i></span><strong>LEYENDA DEL NAVEGADOR</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Dice que el sitio es <strong>INSEGURO</strong>
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Dice que en el sitio hay un <strong>SOFTWARE MALICIOSO</strong>
													</div>
												</li>
                                            </a>
											<li class="dd-item" data-id="4">
												<div class="dd-handle">
													<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>¿OTRO ERROR?</strong> ... COPIE Y PEGUE EL ERROR AQUI 
													<div class="chat-form" style="margin-top:15px; margin-left:30px;">
														<form role="form">
															<div class="form-group">
																<textarea class="form-control" placeholder="Peque aquí el error y la URL exacta de donde lo ve"></textarea>
															</div>
															<div class="text-right">
																<button type="submit" class="btn btn-sm btn-primary m-t-n-xs"><strong>ENVIAR</strong></button>
															</div>
														</form>
													</div>
												</div>
											</li>
                                        </ol>
                                    </li>
                                </ol>
                            </div>
		                </div>
		            </div>
		            <?php } else { ?>
		            
		            <div class="col-lg-6 text-center">
		                <div class="ibox-content">
		                    <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">Es otro el motivo de tu contacto?</h2>
		                    <small>Por favor ,<strong>clickeá la opción que más aproxime a cual es tu problema</strong></small>
							
							<div style="text-align:left!important; margin-top:20px;" class="dd" id="nestable2">
                                <ol class="dd-list">
									<li class="dd-item" data-id="1">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-envelope"></i></span><strong>NO ME SALEN</strong> LOS EMAILS
                                        </div>
                                    </li>
									<li class="dd-item" data-id="2">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-envelope"></i></span><strong>NO ME LLEGAN</strong> LOS EMAILS
                                        </div>
                                    </li>
									<li class="dd-item" data-id="3">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-envelope"></i></span><strong>NO ME SALEN NI ME LLEGAN</strong> LOS EMAILS
                                        </div>
                                    </li>
									<hr>
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-retweet"></i></span><strong>ME VUELVEN REBOTADOS</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me vienen de vuelta los emails que mando a @ <strong>GMAIL</strong>
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me vienen de vuelta los emails que mando a @ <strong>OUTLOOK</strong>
													</div>
												</li>
											</a>
											<a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me vienen de vuelta los emails que mando a @ <strong>HOTMAIL</strong>
													</div>
												</li>
											</a>
											<li class="dd-item" data-id="5">
												<div class="dd-handle">
													<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me vienen de vuelta los emails que mando a <strong>OTROS DOMINIOS</strong>
													<div class="chat-form" style="margin-top:15px; margin-left:30px;">
														<form role="form">
															<div class="form-group">
																<textarea class="form-control" placeholder="Peque aquí el contenido del email que le volvió con error"></textarea>
															</div>
															<div class="text-right">
																<button type="submit" class="btn btn-sm btn-primary m-t-n-xs"><strong>ENVIAR</strong></button>
															</div>
														</form>
													</div>
												</div>
											</li>
                                        </ol>
                                    </li>
									<hr>
									<li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-lock"></i></span><strong>NO ME PUEDO LOGUEAR</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Dice que el <strong>USUARIO ES INCORRECTO</strong>
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Dice que la <strong>CONTRASEÑA ES INCORRECTA</strong>
													</div>
												</li>
                                            </a>
												
											<a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>OLVIDE MI CONTRASEÑA</strong>
													</div>
												</li>
                                            </a>
											<a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>NO ME PUEDO CONECTAR DESDE EL WEBMAIL</strong>
													</div>
												</li>
                                            </a>
                                        </ol>
                                    </li>
									<hr>
									<li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-warning"></i></span><strong>RECIBO EMAILS SOSPECHOSOS</strong>
                                        </div>
                                        <ol class="dd-list">
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me llegan emails con @ de empresas importantes, pero con contenido dudoso<strong> (SPOOFING)</strong>
													</div>
												</li>
											</a>
                                            <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="3">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Me llegan emails de empresas importantes, pero el @ es dudoso<strong> (PISHING)</strong>
													</div>
												</li>
                                            </a>
												
											<li class="dd-item" data-id="5">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> <strong>¿OTRO TIPO DE EMAILS?</strong> ... COPIE Y PEGUE EL EMAIL AQUI 
														<div class="chat-form" style="margin-top:15px; margin-left:30px;">
															<form role="form">
																<div class="form-group">
																	<textarea class="form-control" placeholder="Peque aquí el contendio completo del email aquí"></textarea>
																</div>
																<div class="text-right">
																	<button type="submit" class="btn btn-sm btn-primary m-t-n-xs"><strong>ENVIAR</strong></button>
																</div>
															</form>
														</div>
													</div>
												</li>
                                        </ol>
                                    </li>
                                </ol>
                            </div>
		                </div>
		            </div>
					<?php } ?>
		        </div>
		        
		        <?php if (isset($nagios)) { ?>
		        	<?php foreach ($nagios as $obj) { ?>
		                <div class="alert alert-<?php echo $obj['estado_ui_class']; ?>">
		                    <?php echo $this->lang->line('cms_ayuda-' . strtolower(str_replace(' ' , '-', $obj['tipo']))); ?>
		                </div>
		            <?php } ?>
		        <?php } ?>            
	            
	            <?php if ($detalle['id_servicio_hosting']) { ?>
	            <div class="row">
		            
		            <?php if ($plan['id_estado'] == 4 || $plan['id_estado'] == 3) { ?>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content ">
                                <h5 class="m-b-md"><?php echo $plan['domain']; ?></h5>
                                <h2 class="text-navy">
                                    <i class="fa fa-play fa-rotate-270"></i> <?php echo $plan['estado']; ?>
                                </h2>
                                <small>Fecha de alta: <?php echo formatear_fecha($plan['fecha'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5 class="m-b-md"><?php echo $plan['domain']; ?></h5>
                                <h2 class="text-danger">
                                    <i class="fa fa-play fa-rotate-90"></i> <?php echo $plan['estado']; ?>
                                </h2>
                                <small>Fecha de alta: <?php echo formatear_fecha($plan['fecha'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Espacio</h5>
                                <h2><?php echo $plan['diskused_porcentaje']; ?>%</h2>
                                <div class="progress progress-mini">
                                    <div style="width: <?php echo $plan['diskused_porcentaje']; ?>%;" class="progress-bar <?php echo $plan['diskused_progress_ui_class']; ?>"></div>
                                </div>

                                <div class="m-t-sm small">Espacio utilizado: <?php echo byte_format($plan['diskused']*1024*1024); ?>/<?php echo byte_format($plan['disklimit']*1024*1024); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Trasnferencia</h5>
                                <h2><?php echo $plan['bandwidthused_porcentaje']; ?>%</h2>
                                <div class="progress progress-mini">
                                    <div style="width: <?php echo $plan['bandwidthused_porcentaje']; ?>%;" class="progress-bar <?php echo $plan['bandwidthused_progress_ui_class']; ?>"></div>
                                </div>

                                <div class="m-t-sm small">Trasnferencia mensual: <?php echo byte_format($plan['bandwidthused']*1024*1024); ?>/<?php echo ($plan['bandwidthlimit'] > 0) ? byte_format($plan['bandwidthlimit']*1024*1024) : 'Ilimitado'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (isset($emails)) { ?>
		        <div class="ibox-content m-b-sm border-bottom">
		            <div class="row">
		                <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-striped footable">
                                    <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th class="text-center">Consumo</th>
                                        <th class="text-center">Capacidad</th>
                                        <?php if ($this->usuario->perfil == 'admin') { ?>
                                        	<th class="text-center">Acciones</th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
	                                	<?php foreach ($emails as $item) { ?>
	                                    <tr>
		                                    <td><?php echo $item['email']; ?></td>
		                                    <td class="text-center">
			                                    <span>
								                    <i class="fa fa-cloud"></i> <?php echo $item['diskusedpercent']; ?>%
				                                </span>
			                                    <div class="progress progress-mini">
													<div style="width: <?php echo $item['diskusedpercent']; ?>%;" class="progress-bar <?php echo ($item['diskusedpercent'] > 80) ? ($item['diskusedpercent'] > 95) ? 'progress-bar-danger' : 'progress-bar-warning' : 'progress-bar-primary'; ?>"></div>
				                                </div>
		                                    </td>
											<td class="text-center"><?php echo $item['humandiskused']; ?>/<?php echo $item['humandiskquota']; ?></td>
											<?php if ($this->usuario->perfil == 'admin') { ?>
												<td class="text-center">
													<?php $email = explode('@', $item['email']); ?>
													<a href="<?php echo base_url('hosting/email-password-reset/' . $detalle['id_servicio_hosting'] . '/' . $email[0] . '/' . $email[1]); ?>"><span class="fa fa-unlock-alt"></span> Cambiar contraseña</a>
												</td>
	                                        <?php } ?>
	                                    </tr>
										<? } ?>
                                    </tbody>
                                </table>
                            </div>
		                </div>
		            </div>
		        </div>
		        <?php } ?>
                
	        </div>