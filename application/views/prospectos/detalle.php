<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Prospectos</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('prospectos'); ?>">Prospectos</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/empresas/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar empresa</a>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        
		        <?php if (!empty($detalle['observaciones'])) { ?>
			        <ul class="notes">
                        <li>
                            <div>
                                <small><?php echo formatear_fecha($detalle['fecha_modificacion'], 'd-m-Y H:i', 'Hs', $this->usuario->timezone); ?></small>
                                <h4><?php echo $detalle['username_modificacion']; ?></h4>
                                <p><?php echo $detalle['observaciones']; ?></p>
                                <a href="#"><i class="fa fa-trash-o "></i></a>
                            </div>
                        </li>
                    </ul>
                <?php } ?>

	            <div class="row m-b-lg m-t-lg">
	                <div class="col-md-6">
	                    <div class="profile-info">
	                        <div>
	                            <div>
	                                <h2 class="no-margins">
	                                    <?php echo $detalle['empresa']; ?>
	                                </h2>
	                                <h4><?php echo $detalle['categoria']; ?></h4>
	                                <small>
	                                    <?php echo $detalle['actividad']; ?>
	                                </small>
	                                <h4>Creador: <?php echo $detalle['username_alta']; ?> (<?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?>)</h4>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>


				<?php if ($this->usuario->perfil == 'reseller') { ?>
			    <div class="row">
		            <div class="col-lg-6">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Asignar ejecutivo</h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php echo form_open(base_url('prospectos/asignar-contacto/'), array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                            	
	                            	<?php if ($this->usuario->perfil == 'reseller') { ?>
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label">Contactos</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $agentes, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <button class="btn btn-primary" type="submit">Asignar</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		            
		            <div class="col-lg-6">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Desasociar ejecutivo</h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php echo form_open(base_url('prospectos/desasociar-contacto/'), array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                            	
	                            	<?php if ($this->usuario->perfil == 'reseller') { ?>
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label">Contactos</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $agentes, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <button class="btn btn-primary" type="submit">Desasociar</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	            <?php } ?>
	            
	            	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Contacto</label>
	                            <div class="bg-muted p-xs b-r-sm"> <a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $detalle['id_contacto']; ?>"><?php echo $detalle['contacto']; ?></a></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
		                        <?php if (isset($detalle['id_referido'])) { ?>
	                            <label class="control-label">Referido</label>
	                            <div class="bg-muted p-xs b-r-sm"> <a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $detalle['id_referido']; ?>"><?php echo $detalle['referido']; ?></a></div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Tipo de factura</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['tipo_factura']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Forma de pago</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['forma_pago']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
		                        <?php if (isset($detalle['codigo'])) { ?>
	                            <label class="control-label">Código</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['codigo']; ?></div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Razón Social</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['razon_social']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
		                        <?php if (isset($detalle['cuit'])) { ?>
	                            <label class="control-label">C.U.I.T.</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['cuit']; ?></div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Condición fiscal</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['condicion_iva']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
		                        <?php if (isset($detalle['ingresos_brutos'])) { ?>
	                            <label class="control-label">IIBB</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['ingresos_brutos']; ?></div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <?php if (isset($detalle['titular'])) { ?>
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Titular</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['titular']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Documento</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['documento']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
		                        <?php if (isset($detalle['cbu'])) { ?>
	                            <label class="control-label">CBU</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['cbu']; ?></div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($contactos)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Contactos</h5>
		                        <div class="ibox-tools">
		                            <a class="collapse-link">
		                                <i class="fa fa-chevron-up"></i>
		                            </a>
		                            <a href="<?php echo base_url('administracion/contactos/ingresar?id_empresa='); ?><?php echo $detalle['id']; ?>">
		                                <i class="fa fa-plus"></i>
		                            </a>
		                            <a class="close-link">
		                                <i class="fa fa-times"></i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
		                        <table class="table">
		                            <thead>
			                            <tr>
			                                <th>Nombre</th>
			                                <th>Email</th>
			                                <th>Teléfono</th>
			                                <th class="text-center">Ultima visita</th>
			                                <th class="text-center">Estado</th>
			                            </tr>
		                            </thead>
		                            <tbody>
			                            <?php if (isset($contactos)) { ?>
				                            <?php foreach ($contactos as $contacto) { ?>
				                            <tr>
				                                <td>
					                                <a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['contacto']; ?></a>
					                                <br>
					                                <small><?php echo $contacto['username']; ?></small>
					                                </td>
				                                <td>
					                                <?php echo $contacto['email']; ?>
					                                <br>
					                                <small><?php echo $contacto['perfil']; ?></small>
					                            </td>
				                                <td>
					                                <?php if (isset($contacto['telefono'])) echo '<i class="fa fa-phone"></i> ' . $contacto['telefono']; ?>
					                                <br>
					                                <small><?php if (isset($contacto['celular'])) echo '<i class="fa fa-phone"></i> ' . $contacto['celular']; ?></small>
					                                </td>
				                                <td class="text-center">
					                                <?php echo formatear_fecha($contacto['ultima_visita'], 'd-m-Y', null, $this->usuario->timezone); ?>
					                                <br>
					                                <small><?php echo formatear_fecha($contacto['ultima_visita'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
					                                </td>
				                                <td class="text-center"><span class="label <?php echo $contacto['estado_ui_class']; ?>"><?php echo $contacto['estado']; ?></span></td>
				                            </tr>
											<?php } ?>
			                            <?php } else { ?>
			                            	<tr>
				                                <td colspan="5">No se encontraron Contactos</td>
			                            	</tr>
			                            <?php } ?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
	            </div>
	            
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($servicios)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Servicios</h5>
		                        <div class="ibox-tools">
		                            <a class="collapse-link">
		                                <i class="fa fa-chevron-up"></i>
		                            </a>
		                            <a href="<?php echo base_url('administracion/servicios/ingresar?id_empresa='); ?><?php echo $detalle['id']; ?>">
		                                <i class="fa fa-plus"></i>
		                            </a>
		                            <a class="close-link">
		                                <i class="fa fa-times"></i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
		                        <table class="table">
		                            <thead>
			                            <tr>
			                                <th>Descripción</th>
			                                <th class="text-right">Valor</th>
											<th class="text-center">Próxima</th>
											<th class="text-center">Estado</th>
			                            </tr>
		                            </thead>
		                            <tbody>
		                            </tbody>
		                            	<?php if (isset($servicios)) { ?>
				                            <?php foreach ($servicios as $servicio) { ?>
				                            <tr>
				                                <td><a href="<?php echo base_url('administracion/servicios/detalle/'); ?><?php echo $servicio['id']; ?>"><?php echo strip_tags($servicio['descripcion']); ?></a>
				                                	<br>
													<small><span class="badge <?php echo $servicio['operacion_ui_class']; ?>"><?php echo $servicio['operacion']; ?></span> <?php echo $servicio['categoria']; ?></small></td>
				                                <td class="text-right"><?php echo $servicio['simbolo']; ?><strong><?php echo $servicio['total']; ?></strong>
													<?php if ($servicio['descuento'] > 0) : ?>
														<br>
														<small>- <?php echo $servicio['descuento']; ?>% de <?php echo $servicio['simbolo']; ?><?php echo $servicio['valor']; ?></small>
													<?php endif; ?>
												</td>
				                                <td class="text-center"><?php echo formatear_fecha($servicio['proxima'], 'd-m-Y', null, $this->usuario->timezone); ?>
		                                        	<br>
		                                        	<small>(<?php echo $servicio['frecuencia']; ?>)</small></td>
		                                        <td class="text-center"><span class="label <?php echo $servicio['estado_ui_class']; ?>"><?php echo $servicio['estado']; ?></span></td>
				                            </tr>
				                            <?php } ?>
			                            <?php } else { ?>
			                            	<tr>
				                                <td colspan="4">No se encontraron Servicios</td>
			                            	</tr>
			                            <?php } ?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
	            </div>
	            
	        </div>