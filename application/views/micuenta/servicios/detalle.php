<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Servicios</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></a>
	                    </li>
	                    <li>
	                    	<a href="<?php echo base_url('micuenta/servicios'); ?>"><?php echo $this->lang->line('cms_servicios'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ($this->usuario->perfil == 'admin') { ?>
				            <?php if ($detalle['id_estado'] == 4) { ?>
				            	<?php if ($detalle['id_servicio_hosting']) { ?>
				            		<a href="<?php echo base_url('hosting/cpanel-password-reset/' . $detalle['id_servicio_hosting']); ?>" class="btn btn-primary btn-sm"> Cambiar contraseña</a>
	                        	<?php } ?>
	                        	<a href="<?php echo base_url('micuenta/servicios/para-suspender/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Suspender servicio</a>
	                        <?php } elseif ($detalle['id_estado'] == 1) { ?>
	                        	<a href="<?php echo base_url('micuenta/servicios/para-activar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Activar servicio</a>
	                        <?php } ?>
	                    <?php } ?>
                    </div>
                </div>
	        </div>

	        <div class="wrapper wrapper-content animated fadeInRight">		            
	            
	            <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Categoría</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['categoria']; ?></div>
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
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Frecuencia</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['frecuencia']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Próxima</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['proxima'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Caduca</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['caduca'], 'd-m-Y', null, $this->usuario->timezone, null, array('default'=>'Sin caducidad')); ?></div>
	                        </div>
	                    </div>
	                </div>
	                
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Descripción</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['descripcion']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	                
	                <?php if ($this->usuario->perfil == 'admin') { ?>
	                <div class="row">
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Precio</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['simbolo']; ?><?php echo $detalle['valor']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Descuento</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['descuento_porcentaje']; ?>%</div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Subtotal</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['simbolo']; ?><?php echo $detalle['total']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Total</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['simbolo']; ?><?php echo $detalle['total_neto']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <?php  } ?>
	            </div>
	            
	            <?php if ($detalle['id_servicio_hosting']) { ?>
	            <div class="ibox-content m-b-sm border-bottom">
	                
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Usuario</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $plan['user']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Dominio</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $plan['domain']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Espacio</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo byte_format($plan['diskused']*1024*1024); ?>/<?php echo byte_format($plan['disklimit']*1024*1024); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Transferencia</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo byte_format($plan['bandwidthused']*1024*1024); ?>/<?php echo ($plan['bandwidthlimit'] > 0) ? byte_format($plan['bandwidthlimit']*1024*1024) : 'Ilimitado'; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-12">
		                    <span>
				                <i class="fa fa-cloud"></i> <?php echo $plan['diskused_porcentaje']; ?>% <i class="fa fa-exchange"></i>  <?php echo $plan['bandwidthused_porcentaje']; ?>%
				            </span>
				            <div class="progress progress-mini">
								<div style="width: <?php echo $plan['porcentaje']; ?>%;" class="progress-bar <?php echo $plan['progress_ui_class']; ?>"></div>
				            </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
		                <?php
			                if (isset($nagios) && is_array($nagios)) {
			                    foreach ($nagios as $obj)
			                    {
				                    ?>
				                    <div class="col-sm-4">
					                    <div class="form-group">
						                    <label class="col-sm-6 control-label"><?php echo $obj['tipo']; ?></label>
										    <span class="label label-<?php echo $obj['estado_ui_class']; ?>"><?php echo $obj['estado']; ?></span>
					                    </div>
			                	    </div>
			                    <?php
			                    }
			                }
			            ?>
		            	
	                </div>
	            </div>

	            <?php if (isset($emails)) { ?>
		        <div class="ibox-content m-b-sm border-bottom">
		            <div class="row">
		                <div class="col-lg-12">
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
		        
				<?php } ?>
	        </div>