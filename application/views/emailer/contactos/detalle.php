<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/contactos'); ?>">Contactos</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a href="<?php echo base_url('administracion/contactos/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar contacto</a>
                    </div>
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

	        </div>