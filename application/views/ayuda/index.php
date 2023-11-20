<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			
			<style>
				.faq-answer ol {
					counter-reset: item
				}
				.faq-answer li, .alert li {
					display: block;
					margin: 3px 0 3px 0;
				}
				.faq-answer li:before {
					content: counters(item, ".") ". ";
					counter-increment: item
				}
			</style>
            
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Mesa de ayuda</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
                        <li class="active"><strong>Ayuda</strong></li>
                    </ol>
                </div>
            </div>


            <div class="wrapper wrapper-content animated fadeInRight">
                
                <?php if ($blocked_ip) { ?>
                <div class="alert alert-danger">
                    La IP <!-- (<?php echo $this->usuario->ip; ?>) -->con la cual te estás conectando está bloqueada y es por eso que no podes acceder a tus servicios.
                    	
					<a class="alert-link" href="<?php echo base_url('ayuda/desbloquear-ip'); ?>">Solicitar el desbloqueo de la misma.</a>
                </div>
                <?php } ?>
                    
                <?php if (isset($tickets)) { ?>
                <div class="alert alert-warning">
                    <?php if (count($tickets) > 1) { ?>
                    	<p>En nuestro sistema figuran los siguientes tickets abiertos. Por favor continue desde estos mismos si no es un nuevo inconveniente.</p>
                    <?php } else { ?>
                    	<p>En nuestro sistema figura un ticket abierto. Por favor continue desde el mismo si no es un nuevo inconveniente.</p>
                    <?php } ?>
                    	
                    	<ul>
                        	<?php foreach ($tickets as $ticket) { ?>
                        	<li><a href="<?php echo base_url('tickets/detalle/' . $ticket['id']); ?>"><?php echo $ticket['asunto']; ?></a></li>
                        	<?php } ?>
                    	</ul>
                </div>
                <?php } ?>
                
                <?php if (isset($balance['parcial'])) { ?>
                <div class="alert alert-warning">
                    <?php if ($balance['facturas'] > 1) { ?>
                    	<p>La empresa presenta un total de <strong><?php echo $balance['facturas']; ?> facturas impagas</strong> por un <strong>total de $<?php echo $balance['saldo']; ?></strong>.</p>
                    <?php } else { ?>
                    	<p>La empresa presenta un saldo de <strong>$<?php echo $balance['saldo']; ?></strong>.</p>
                    <?php } ?>
                    	<p>Para reactivar sus servicios deberá hacer un <strong>pago mínimo de $<?php echo $balance['parcial']; ?></strong> perteneciente a las <strong>facturas vencidas por más de 45 días</strong>.</p>
                    	
                    	<ul>
                        	<?php foreach ($facturas as $factura) { ?>
                        	<li><a href="<?php echo base_url('micuenta/facturas/detalle/' . $factura['id']); ?>">Factura <?php echo $factura['comprobante']; ?> (<?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?>)</a></li>
                        	<?php } ?>
                    	</ul>
                </div>
                <?php } ?>
                
                <div class="ibox-content m-b-sm border-bottom">
                    <div class="text-center p-lg">
                        <h2>¿Con qué podemos ayudarte?</h2>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-7">
                            <a data-toggle="collapse" href="#faq1" class="faq-question"><span class="text-primary">Hosting</span></a>
                            <small>¿Cómo <strong>optimizar</strong> mi sitio para lograr una <strong>mejor performance</strong>?</small>
                        </div>

                        <div class="col-md-5">
                            <span class="small font-bold">Tags</span>

                            <div class="tag-list">
                                <a href="https://www.revisionalpha.com/tag/bandwidth/" target="_blank" class="tag-item text-muted">Ancho de banda</a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div id="faq1" class="panel-collapse collapse">
                                <div class="faq-answer">
	                                <?php if (isset($balance['parcial'])) { ?>
	                                	<p>No podemos brindarte soporte hasta que no regularices tus pagos.</p>
	                                <?php } else { ?>
	                                    <ol>
	                                        <li>No veo mi sitio
	                                        	<ol>
		                                        	<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Nunca pude ver mi sitio'); ?>">Siempre</a></li>
		                                        	<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Ahora no puedo ver mi sitio'); ?>">Ahora</a></li>
		                                        	<li><a href="<?php echo base_url('ayuda'); ?>">URL + leyenda Browser</a></li>
	                                        	</ol>
	                                        </li>
	                                        <li>Tarda mucho en cargar
	                                        	<ol>
		                                        	<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Siempre tarda mucho en cargar'); ?>">Siempre</a></li>
		                                        	<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Tarda mucho en cargar'); ?>">Ahora</a></li>
	                                        	</ol>
	                                        </li>
											<li>Leyenda del navegador
												<ol>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Sitio inseguro'); ?>">Sitio inseguro</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Software malicioso'); ?>">Software malicioso</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Pegue los datos de error'); ?>">Pegue los datos de error</a></li>
												</ol>
											</li>
	                                    </ol>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-7">
                            <a data-toggle="collapse" href="#faq2" class="faq-question"><span class="text-primary">Emails</span></a> 
                            <small>Problemas de <strong>configuración</strong>, <strong>envío</strong> y <strong>recepción</strong> de emails</small>
                        </div>

                        <div class="col-md-5">
                            <span class="small font-bold">Tags</span>

                            <div class="tag-list">
                                <a href="https://www.revisionalpha.com/tag/spoofing/" target="_blank" class="tag-item text-muted">Spoofing</a> <a href="https://www.revisionalpha.com/tag/spam/" target="_blank" class="tag-item text-muted">Spam</a>
                            </div>
                        </div>

<!--
                        <div class="col-md-2 text-right">
                            <span class="small font-bold">Voting</span><br>
                            42
                        </div>
-->
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div id="faq2" class="panel-collapse collapse">
                                <div class="faq-answer">
	                                <?php if (isset($balance['parcial'])) { ?>
	                                	<p>No podemos brindarte soporte hasta que no regularices tus pagos.</p>
	                                <?php } else { ?>
	                                    <ol>
	                                        <li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/No me salen los emails'); ?>">No me salen los emails</a></li>
	                                        <li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/No me llegan los emails'); ?>">No me llegan los emails</a></li>
	                                        <li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/No me salen ni me llegan los emails'); ?>">No me salen ni me llegan los emails</a></li>
	                                        <li>No puedo loguear
	                                        	<ol>
		                                        	<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Olvido la contraseña'); ?>">Olvido la contraseña</a></li>
	                                        	</ol>
	                                        </li>
											<li>Recepción de emails sospechosos
												<ol>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Pishing'); ?>">Pishing</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Spoofing'); ?>">Spoofing</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Pegue el contenido del email'); ?>">Pegue el contenido del email</a></li>
												</ol>
											</li>
											<li>Problemas con el webmnail
												<ol>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/No carga la página'); ?>">No carga la página</a></li>
												</ol>
											</li>
											<li>Rebote de emails
												<ol>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Rebotes a Gmail'); ?>">Rebotes a Gmail</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Rebotes a Hotmail'); ?>">Rebotes a Hotmail</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Rebotes a Outlook'); ?>">Rebotes a Outlook</a></li>
													<li><a href="<?php echo base_url('ayuda/seleccion-del-servicio/Rebotes a otro'); ?>">Rebotes a otro (Pegue el contenido)</a></li>
												</ol>
											</li>
	                                    </ol>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="row">
                        <div class="col-md-7">
                            <a data-toggle="collapse" href="#faq3" class="faq-question"><span class="text-primary">Administración</span></a>
                            <small>Cambiar <strong>datos de facturación</strong>, <strong>forma de pago</strong> y <strong>contraseña</strong>. Ver y <strong>gestionar servicios</strong>.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div id="faq3" class="panel-collapse collapse">
                                <div class="faq-answer">
                                    <ol>
                                        <li><a href="<?php echo base_url('micuenta/perfil'); ?>">Datos de facturación</a></li>
                                        <li><a href="<?php echo base_url('micuenta/perfil'); ?>">Forma de pago</a></li>
										<li>Cambios en la facturación
											<ol>
												<li><a href="<?php echo base_url('micuenta/perfil'); ?>">Titularidad</a></li>
												<li><a href="<?php echo base_url('micuenta/perfil'); ?>">Forma de pago</a></li>
											</ol>
										</li>
                                        <li><a href="<?php echo base_url('micuenta/perfil/'); ?>">Datos de contacto</a></li>
                                        <li><a href="<?php echo base_url('micuenta/perfil/password'); ?>">Cambiar contraseña</a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>