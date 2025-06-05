<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Hosting</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('hosting'); ?>">Planes</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('hosting/cpanel-password-reset/' . $detalle['id']); ?>" class="btn btn-primary btn-sm"> Cambiar contraseña</a>
                        <?php } ?>
                        <a href="<?php echo base_url('administracion/servicios/detalle/' . $detalle['id_servicio']); ?>" class="btn btn-success btn-sm">Ver servicio</a>
                        <a href="<?php echo base_url('hosting/recalcular/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Recalcular estadísticas</a>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Empresa</label>
	                            <div class="bg-muted p-xs b-r-sm"> <a href="<?php echo base_url('administracion/empresas/detalle/' . $detalle['id_empresa']); ?>"><?php echo $detalle['empresa']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Usuario</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['user']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Dominio</label>
	                            <div class="bg-muted p-xs b-r-sm"> <a href="http://<?php echo $detalle['domain']; ?>" target="_blank"> <?php echo $detalle['domain']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Servidor</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['servidor']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Plan</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['plan']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Espacio</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo byte_format($detalle['diskused']*1024*1024); ?>/<?php echo byte_format($detalle['disklimit']*1024*1024); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Transferencia</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo byte_format($detalle['bandwidthused']*1024*1024); ?>/<?php echo ($detalle['bandwidthlimit'] > 0) ? byte_format($detalle['bandwidthlimit']*1024*1024) : 'Ilimitado'; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Actualizado</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['fecha'], 'd-m-Y H:i:s', ' Hs', $this->usuario->timezone); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Estado</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['estado']; ?></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="ibox-content m-b-sm border-bottom">
			        <span>
		                <i class="fa fa-cloud"></i> <?php echo $detalle['diskused_porcentaje']; ?>% <i class="fa fa-exchange"></i>  <?php echo $detalle['bandwidthused_porcentaje']; ?>%
		            </span>
		            <div class="progress progress-mini">
						<div style="width: <?php echo $detalle['porcentaje']; ?>%;" class="progress-bar <?php echo $detalle['progress_ui_class']; ?>"></div>
		            </div>
		        </div>	
	        </div>
	        
	        <?php if (isset($emails) && !isset($emails['result'])) { ?>
	        <div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Email</th>
	                                        <th class="text-center">Consumo</th>
	                                        <th class="text-center">Capacidad</th>
	                                        <th class="text-center">Acciones</th>
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
												<td class="text-center">
													<?php $email = explode('@', $item['email']); ?>
													<a href="<?php echo base_url('hosting/email-password-reset/' . $detalle['id'] . '/' . $email[0] . '/' . $email[1]); ?>"><span class="fa fa-unlock-alt"></span> Cambiar contraseña</a>
												</td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <?php } ?>