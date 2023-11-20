<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/smtps'); ?>">SMTPs</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a href="<?php echo base_url('emailer/smtps/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar SMTP</a>
	                    <?php if ($detalle['estado'] != 'Offline') { ?>
                        	<a href="<?php echo base_url('emailer/smtps/probar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm" target="_blank">Probar servidor</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">		            
	            
	            <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Host</label>
	                            <div class="bg-muted p-xs b-r-sm"><?php echo $detalle['host']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Estado</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['estado']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Usuario</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['user']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Contraseña</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['pass']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Seguridad</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['seguridad']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Puerto</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['puerto']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-4">
	                        <div class="form-group">
	                            <label class="control-label">Errores</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['errores']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-4">
	                        <div class="form-group">
	                            <label class="control-label">Envíos</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['envios']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-4">
	                        <div class="form-group">
	                            <label class="control-label">Cola de Emails</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['mailq']; ?></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	        </div>