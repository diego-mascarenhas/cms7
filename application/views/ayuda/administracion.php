<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			
			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Mesa de ayuda</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
                        <li>
                        	<a href="<?php echo base_url('ayuda'); ?>">Ayuda</a>
                        </li>
                        <li class="active"><strong>Administración</strong></li>
                    </ol>
                </div>
            </div>


            <div class="wrapper wrapper-content animated fadeInRight">
                
            	<div class="row">
	            	<div class="col-lg-6">
		                <div class="ibox-content">
		                    <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">Estamos revisando todo esto:</h2>
		                    <small>Si ves algún problema, <strong>clickeá el botón RESOLVER PROBLEMA(S)</strong></small>
							
							<ul style="text-align:left ;width:70%;margin: 0 auto; margin-top:20px; margin-bottom:20px;" class="todo-list m-t">
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Datos de Contacto</span>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> COMPLETO</small>
	                            </li>
	                            <li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Datos de Facturación</span>
									<small style="float:right; font-size:12px" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> ATENCION</small>
	                            </li>
								<li>
	                                <a href="#" class="check-link"><i class="fa fa-chevron-circle-right"></i> </a>
	                                <span class="m-l-xs">Balance de las Facturas (Pagos)</span>
									<small style="float:right; font-size:12px" class="label label-primary"><i class="fa fa-check-circle"></i> AL DIA</small>
	                            </li>
	                        </ul>
								
							<a data-toggle="modal" style="font-size:18px;" class="btn btn-info hvr-bubble-top hvr-grow-shadow" href="#modal-ticket"><i class="fa fa-medkit" style="font-size:20px; padding-right:10px;"></i> &nbsp;RESOLVER PROBLEMA(S)</a>
								
	                        <div id="modal-ticket" class="modal fade" aria-hidden="true">
	                            <div class="modal-dialog">
	                                <div class="modal-content">
	                                    <div class="modal-body">
	                                        <div class="row">
												<div class="float-e-margins">
													<div class="ibox-content text-center">
														<div class="padding-10"><img alt="image" src="<?php echo base_url('assets/img/ayuda-hosting.jpg'); ?>"></div>
														<h2 style="margin-top:20px; float:none; font-size:26px; line-height:32px; color:#EE3F3F; font-weight:lighter;">Ya recolectamos la info necesaria<br>sobre tu problema</h2>
														<span style="margin-bottom:20px;"><small>Esto nos va a ayudar para poder <strong>resolver tu problema más rápidamente</strong></small></span>
														<hr style="margin: 5px!important;">
														<div class="pading-10 p-caption sfb tp-resizeme flv_rev_17" data-x="center" data-hoffset="-400" data-y="center" data-voffset="120" data-speed="500" data-start="3000" data-easing="Power3.easeInOut" data-splitin="none" data-splitout="none" data-elementdelay="0.1" data-endelementdelay="0.1" data-endspeed="300">
															<a href="" class="gradient-button gradient-button-3">ENVIAR TICKET AHORA</a>
														</div>
															
														 
													</div>
												</div>
											</div>
										</div>
	                                </div>
	                            </div>
							</div>
		                </div>
		            </div>
            	
					<div class="col-lg-6 text-center">
	                	<div class="ibox-content">
	                    	<h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">¿Es otro el motivo de tu contacto?</h2>
							<small>Por favor, <strong>clickeá la opción que más aproxime a cual es tu problema</strong></small>
							
							<div style="text-align:left!important; margin-top:20px;" class="dd" id="nestable2">
	                            <ol class="dd-list">
									<li class="dd-item" data-id="1">
	                                    <div class="dd-handle">
	                                        <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-user"></i></span><strong>CAMBIAR MIS DATOS DE CONTACTO</strong>
	                                    </div>
	                                </li>
									<li class="dd-item" data-id="2">
	                                    <div class="dd-handle">
	                                        <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-file-text-o"></i></span><strong>CAMBIAR MIS DATOS DE FACTURACION</strong>
	                                    </div>
										<ol class="dd-list">
	                                        <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Cambiar la <strong>TITULARIDAD</strong>
													</div>
												</li>
											</a>
	                                        <a href="http://www.revisionalpha.com" target="_blank">
												<li class="dd-item" data-id="2">
													<div class="dd-handle">
														<span class="label label-info"><i class="fa fa-angle-right"></i></span> Cambiar la <strong>FORMA DE PAGO</strong>
													</div>
												</li>
											</a>
	                                    </ol>
	                                </li>
									<li class="dd-item" data-id="3">
	                                    <div class="dd-handle">
	                                        <span class="label label-info"><i style="font-size:18px; vertical-align: middle;" class="fa fa-money"></i></span><strong>CAMBIAR MI FORMA DE PAGO</strong>
	                                    </div>
	                                </li>
								</ol>
	                        </div>
	                	</div>
	            	</div>
            	</div>
            
            </div>