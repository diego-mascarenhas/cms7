<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2><?php echo $this->lang->line('cms_users-tickets'); ?></h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tickets'); ?>"><?php echo $this->lang->line('cms_users-tickets'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('cms_users-detalles'); ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <?php if ($this->usuario->perfil == 'reseller') { ?>
            	<div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=50&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
                        <?php } ?>
                        <a href="<?php echo base_url('tickets/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm"><?php echo $this->lang->line('cms_users-modificar-ticket'); ?></a>
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
                <?php } ?>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
				<?php if ($this->usuario->perfil == 'reseller') { ?>
				<div class="ibox-title">
                    <h5>
	                    <a class="message-author" href="<?php echo base_url('administracion/empresas/detalle/' . $detalle['id_empresa']); ?>"> <?php echo $detalle['empresa']; ?></a> / <?php echo $detalle['area']; ?>
				    	<?php 
	                        if (isset($detalle['servicio']))
	                        {
		                        if (isset($detalle['hosting']))
		                        {
			                        echo '<small><a href="' . base_url('hosting/detalle/' . $detalle['hosting']) . '">' . $detalle['servicio'] . '</a></small>';
		                        }
		                        else
		                        {
		                        	echo '<small>' . $detalle['servicio'] . '</small>';
								}
		                	}
		                	
		                ?>
		                
                    </h5>
                    <div class="ibox-tools">
                        <span class="pull-right label <?php echo $detalle['estado_ui_class']; ?>"><?php echo $detalle['estado']; ?></span>
                    </div>
                </div>
			    <?php  } ?>
			    <div class="row">
			        <div class="col-lg-12">
		                <div class="ibox chat-view">
		                    <div class="ibox-title">
		                        <small class="pull-right text-muted"><em><?php echo $this->lang->line('cms_users-creado'); ?> :<?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></em></small>
								<?php echo $detalle['asunto']; ?>
		                    </div>
		
		                    <div class="ibox-content">
		                        <?php if (isset($detalle['items'])) { ?>

	                            <div class="row">
		                           <!-- Traigo mensajes -->
		                            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
		                                <div class="chat-discussion">
			                                <?php foreach ($detalle['items'] as $item)
				                                {
				                                	$align = ($item['id_contacto'] == $this->usuario->id) ? 'right' : 'left';
			                                ?>
		                                    <div class="chat-message <?php echo $align; ?>">
			                                    <?php if (isset($item['avatar'])) { ?> <img class="message-avatar" src="<?php echo base_url('multimedia/avatars/' . $item['avatar']); ?>"><?php } ?>
		                                        <div class="message">
		                                            <a class="message-author" href="<?php echo base_url('administracion/contactos/detalle/' . $item['id_contacto']); ?>"> <?php echo $item['contacto']; ?> </a>
													<span class="message-date"><i class="<?php echo $item['origen_ui_class']; ?>"></i> <?php echo formatear_fecha($item['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?> </span>
		                                            <span class="message-content"><i class="fa <?php echo ($item['visibilidad'] == 0) ? 'fa-eye' : 'fa-eye-slash'; ?>"></i> <?php echo $item['mensaje']; ?>
		                                            </span>
		                                            
		                                            <?php if ($item['adjuntos']) { ?>
		                                            <div class="hr-line-dashed m_b_10"></div>
		                                            <span class="message-content">
		                                            	<?php foreach ($item['adjuntos'] as $adjuntos) { ?>
		                                            		<!-- <a href="https://revisionalpha.com/cms/tickets/attachment/<?php echo $adjuntos['id']; ?>/download" target="_blank">
			                                            		<i class="fa fa-paperclip"></i> <em><?php echo $adjuntos['nombre']; ?></em>
		                                            		</a> -->
		                                            		<a href="https://revisionalpha.com/storage/ticket-attachments/<?php echo $adjuntos['archivo']; ?>" target="_blank">
			                                            		<i class="fa fa-paperclip"></i> <em><?php echo $adjuntos['nombre']; ?></em>
		                                            		</a>
		                                            	<?php } ?>
		                                            </span>
		                                            <?php } ?>
		                                            
		                                        </div>
		                                    </div>
		                                    <? } ?>
		                                </div>
		                            </div>

		                           <!-- Traigo Agentes -->
		                            <div class="col-lg-3 col-md-3 col-sm-0 col-xs-0">
		                                <div class="chat-users">
			                                <?php if (isset($detalle['agentes'])) { ?>
												<?php foreach ($detalle['agentes'] as $agente) { ?>
			                                    <div class="users-list">
			                                        <div class="chat-user">
				                                        <span class="pull-right label <?php echo $agente['estado_ui_class']; ?>"><?php echo $agente['estado']; ?></span>
				                                        <?php if (isset($agente['avatar'])) { ?><img class="chat-avatar" src="<?php echo base_url('multimedia/avatars/' . $agente['avatar']); ?>"><?php } ?>
			                                            <div class="chat-user-name">
			                                                <a href="<?php echo base_url('administracion/contactos/detalle/' . $agente['id']); ?>"> <?php echo $agente['contacto']; ?></a>
			                                                <?php if ($this->usuario->perfil == 'reseller' && isset($agente['ip'])) { ?>
			                                                	<br><small><em>(<a href="<?php echo base_url('mailbox?search=' . $agente['ip']); ?>"><?php echo $agente['ip']; ?></a>)</em></small>
															<?php } ?>
			                                            </div>
			                                        </div>
			                                    </div>
			                                    <? } ?>
											<? } ?>
		                                </div>
		                            </div>
		                        </div>
	                            
	                            <? } else { ?>
	                           
								<div class="row">
								    <div class="col-lg-12">
								        <div class="alert alert-warning"><?php echo $this->lang->line('cms_users-sin-mensajes-info'); ?>
								           <a class="alert-link" href="<?php echo base_url('tickets'); ?>"><?php echo $this->lang->line('cms_users-presione-para-volver'); ?></a>.
								        </div>
								    </div>
								</div>
								
								<? } ?>
								
								<div class="row">
								    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
								    <?php  echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
								            <div class="chat-message-form">
								                <div class="form-group" style=" margin-bottom:0;">
								                    <textarea class="form-control message-input message-ticket-ra" name="mensaje" placeholder="Escribe tu mensaje" style="resize:none;"><?php echo $detalle['mensaje']; ?></textarea>
								                </div>
								            </div>
								    </div>
								    <div class="col-lg-3 col-md-3 col-sm-0 col-xs-0"></div>
								 </div>

								<div class="row">
								    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" style="padding:15px; margin-botom:0 !important; border-left:16px solid #f3f3f4 !important; ">
									    
										
									    <div class="checkbox checkbox-inline">
										    <input type="file" name="file"/>
										</div>
									    <?php if ($this->usuario->perfil == 'reseller') { ?>
									    <div class="checkbox checkbox-inline">
								        	<input type="checkbox" name="visibilidad" value="1" <?php if (isset($detalle['visibilidad']) && $detalle['visibilidad'] == 1) echo 'checked="checked"'; ?>>
									        <label><?php echo $this->lang->line('cms_users-visibilidad-info'); ?></label>
									    </div>
									    <div class="checkbox checkbox-inline">
								        	<input type="checkbox" name="id_origen" value="4" <?php if (isset($detalle['id_origen']) && $detalle['id_origen'] == 4) echo 'checked="checked"'; ?>>
									        <label><?php echo $this->lang->line('cms_users-llamado-telefonico'); ?></label>
									    </div>
										<?php } ?>
								        <button class="btn btn-primary pull-right m-t-n-xs" type="submit"><?php echo $this->lang->line('cms_users-enviar'); ?></button>
								    </div>
								    <div class="col-lg-3 col-md-3 col-sm-0 col-xs-0"></div>
								</div>
			                    </form>
								
		                        <?php if (validation_errors()) : ?>
								<div class="row">
			                        <div class="ibox-content">
										<div class="col-md-12">
											<div class="alert alert-danger" role="alert">
												<?php echo validation_errors(); ?>
											</div>
										</div>
			                        </div>
								</div>
								<?php endif; ?>
								<?php if (isset($error)) : ?>
								<div class="row">
			                        <div class="ibox-content">
										<div class="col-md-12">
											<div class="alert alert-danger" role="alert">
												<?php echo $error; ?>
											</div>
										</div>
			                        </div>
								</div>
								<?php endif; ?>
		                        
		                    </div>
		                </div>
			        </div>
			    </div>
			    
			    <?php if ($this->usuario->perfil == 'reseller') { ?>
			    <div class="row">
		            <div class="col-lg-6">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo $this->lang->line('cms_users-asignar-agentes'); ?></h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php echo form_open(base_url('tickets/asignar-contacto/'), array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                            	
	                            	<?php if ($this->usuario->perfil == 'reseller') { ?>
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-agentes'); ?></label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $agentes, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <button class="btn btn-primary pull-right" type="submit"><?php echo $this->lang->line('cms_users-asignar'); ?></button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		            <div class="col-lg-6">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo $this->lang->line('cms_users-asignar-contacto'); ?></h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php echo form_open(base_url('tickets/asignar-contacto/'), array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                            	
	                            	<?php if ($this->usuario->perfil == 'reseller') { ?>
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_contactos'); ?></label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $contactos, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <button class="btn btn-primary pull-right" type="submit"><?php echo $this->lang->line('cms_users-asignar'); ?></button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	            <?php } ?>
	            
	            <?php if (isset($detalle['servicios']) && $this->usuario->perfil == 'reseller') { ?>
			    <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo $this->lang->line('cms_servicios'); ?></h5>
		                    </div>
		                    <div class="ibox-content">
		                        <table class="table">
		                            <thead>
			                            <tr>
			                                <th><?php echo $this->lang->line('cms_users-descripcion'); ?></th>
											<th class="text-center"><?php echo $this->lang->line('cms_users-estados-ticket'); ?></th>
			                            </tr>
		                            </thead>
		                            <tbody>
		                            </tbody>
		                            	<?php if (isset($detalle['servicios'])) { ?>
				                            <?php foreach ($detalle['servicios'] as $servicio) { ?>
				                            <tr>
				                                <td><?php echo strip_tags($servicio['descripcion']); ?>
				                                	<br>
													<small><?php echo $servicio['categoria']; ?></small>
												</td>
		                                        <td class="text-center"><span class="label <?php echo $servicio['estado_ui_class']; ?>"><?php echo $servicio['estado']; ?></span></td>
				                            </tr>
				                            <?php } ?>
			                            <?php } else { ?>
			                            	<tr>
				                                <td colspan="4"><?php echo $this->lang->line('cms_users-sin-servicios'); ?></td>
			                            	</tr>
			                            <?php } ?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
	            </div>
	            <?php } ?>
			</div>