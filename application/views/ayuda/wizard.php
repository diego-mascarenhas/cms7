<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			
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
                
                <?php if (isset($tickets)) { ?>
                <div class="row text-center">
		            <div class="alert alert-warning">
			            <?php if (count($tickets) > 1) { ?>
	                		<p style="font-size:16px;">En nuestro registros ya figuran tus siguientes reclamos:</p>
						<?php } else { ?>
							<p style="font-size:16px;">En nuestro registro figura tu siguiente reclamo:</p>
						<?php } ?>
						<div class="padding-10">
							<?php foreach ($tickets as $ticket) { ?>
								<a href="<?php echo base_url('tickets/detalle/' . $ticket['id']); ?>">
									<span class="label label-danger"><?php echo $ticket['asunto']; ?></span>
								</a>
							<?php } ?>
						</div>	
						<span class="padding-10"><button type="button" class="btn btn-outline btn-warning">CLICKEA AQUI PARA REFORZAR TUS RECLAMOS</button></span>
						<p class="padding-10"><em>... si es por otro asunto, por favor elegí una de las siguientes opciones</em></p>
	            	</div>
                </div>
                <?php } ?>
                
                
                <?php if ($blocked_ip) { ?>
                <div class="alert alert-danger">
                    La IP <!-- (<?php echo $this->usuario->ip; ?>) -->con la cual te estás conectando está bloqueada y es por eso que no podes acceder a tus servicios.
                    	
					<a class="alert-link" href="<?php echo base_url('ayuda/desbloquear-ip'); ?>">Solicitar el desbloqueo de la misma.</a>
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
                
				<div class="row text-center">
					<div class="padding-10"><h5 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">¿En qué podemos ayudarte?</h5></div>
				</div>
				
				<div class="row">
		            <div class="col-lg-12">
		                <div class="wrapper wrapper-content">
	                        <div class="row">
								<div class="col-lg-4 animated fadeInLeftBig">
									<div class="ibox float-e-margins">
										<div class="ibox-content text-center">
											 <div class="padding-10"><img alt="image" src="<?php echo base_url('assets/img/ayuda-hosting.jpg'); ?>"></div>
											 <div class="pading-10 p-caption sfb tp-resizeme flv_rev_17" data-x="center" data-hoffset="-400" data-y="center" data-voffset="120" data-speed="500" data-start="3000" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="300">
												<a href="<?php echo base_url('ayuda/seleccion-del-servicio/hosting'); ?>" class="gradient-button gradient-button-3">MI SITIO WEB</a>
											</div>
											<hr style="margin: 5px!important;">
										 <div>
											<small><strong>No se ve el sitio web</strong>, <strong>tarda en cargar</strong> y <strong>Frase rara</strong> en el navegador web)</small>
										 </div>
										</div>
									</div>
								</div>
	                            <div class="col-lg-4 animated fadeInDownBig">
	                                <div class="ibox float-e-margins">
	                                    <div class="ibox-content text-center">
											 <div class="padding-10"><img alt="image" src="<?php echo base_url('assets/img/ayuda-email.jpg'); ?>"></div>
											 <div class="pading-10 p-caption sfb tp-resizeme flv_rev_17" data-x="center" data-hoffset="-400" data-y="center" data-voffset="120" data-speed="500" data-start="3000" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="300">
												<a href="<?php echo base_url('ayuda/seleccion-del-servicio/emails'); ?>" class="gradient-button gradient-button-3">MIS EMAILS</a>
											</div>
											<hr style="margin: 5px!important;">
											<div>
												<small>(Problemas de <strong>configuración</strong>, <strong>envío</strong> y <strong>recepción</strong> de emails)</small>
											</div>
	                                    </div>
	                                </div>
	                            </div>
								<div class="col-lg-4 animated fadeInRightBig">
	                                <div class="ibox float-e-margins">
	                                    <div class="ibox-content text-center">
											 <div class="padding-10"><img alt="image" src="<?php echo base_url('assets/img/ayuda-administracion.jpg'); ?>"></div>
											 <div class="pading-10 p-caption sfb tp-resizeme flv_rev_17" data-x="center" data-hoffset="-400" data-y="center" data-voffset="120" data-speed="500" data-start="3000" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="300">
												<a href="<?php echo base_url('ayuda/administracion'); ?>" class="gradient-button gradient-button-3">MIS DATOS</a>
											</div>
											<hr style="margin: 5px!important;">
											<div>
												<small>(Problemas con las <strong>facturas</strong>, las <strong>formas de pago</strong> y los <strong>datos de contacto</strong>)</small>
											</div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
		                </div>
		            </div>
		        </div>

            </div>