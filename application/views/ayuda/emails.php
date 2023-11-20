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
                        <li class="active"><strong>Emails</strong></li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
	                <div class="col-lg-12 text-center">
		                <div class="ibox-content">
			                <?php if (isset($servicios)) { ?>
		                    <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">Decinos a que dominio pertenece tu CUENTA DE EMAIL</h2>
		                    <small>Si hay problemas con más de una cuenta tuya,<strong> tenés que hacer de a un reclamo a la vez</strong></small>
							<div class="form-group centrado" style="margin-top:20px; margin-bottom:20px; padding-bottom:20px;">
								<div class="col-sm-3"></div>
                                <div class="col-sm-6">
									<?php foreach ($servicios as $servicio) { ?>
				                    	<li><a href="<?php echo base_url('ayuda/detalle-del-servicio/' . $detalle['problema'] . '/' . $servicio['id']); ?>"><?php echo $servicio['domain']; ?></a></li>
				                    <?php } ?>
                                </div>
								<div class="col-sm-3"></div>
	                        </div>
	                        <?php } else { ?>
	                        <h2 style="margin-top:20px; float:none; font-size:26px; color:#EE3F3F; font-weight:lighter;">¿No tienes servicios?</h2>
		                    <small>No encontramos servicios de hosting asociados a su empresa</strong></small>
	                        <?php } ?>
		                </div>
		            </div>
                </div>
            </div>