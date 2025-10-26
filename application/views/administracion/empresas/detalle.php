<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/empresas'); ?>">Empresas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=112&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
	                    	<a href="<?php echo base_url('configuracion/?id_empresa=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-cog"></i></a>
	                    <?php } ?>
                        <a href="<?php echo base_url('administracion/empresas/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar empresa</a>
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
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        
		        <?php if (!empty($detalle['observaciones'])) { ?>
			        <ul class="notes">
                        <li>
                            <div>
                                <small><?php echo formatear_fecha($detalle['fecha_modificacion'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
                                <h4><?php echo $detalle['username_modificacion']; ?></h4>
                                <p><?php echo $detalle['observaciones']; ?></p>
                            </div>
                        </li>
                    </ul>
                <?php } ?>

	            <div class="row m-b-lg m-t-lg">
	                <div class="col-md-6">
	                    <div class="profile-info">
	                        <div>
	                            <div>
	                                <h2 class="no-margins"><?php echo $detalle['empresa']; ?></h2>
	                                <h4><?php echo $detalle['categoria']; ?></h4>
	                                <small><?php echo $detalle['actividad']; ?></small><br>
									<small><?php echo !empty($detalle['email']) ? $detalle['email'] : 'No se registra Email' ?></small><br>
									<small><?php echo !empty($detalle['whatsapp']) ? $detalle['whatsapp'] : 'No se registra WhatsApp' ?></small><br>
									<small><em>Creado por: <?php echo $detalle['username_alta']; ?> el <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></em></small>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-md-2">
	                    <table class="table small m-b-xs">
	                        <tbody>
	                        <tr>
	                            <td>
	                                <strong><?php echo (isset($servicios) && is_array($servicios)) ? count($servicios) : 0; ?></strong> Servicios
	                            </td>
	                        </tr>
	                        <tr>
	                            <td>
	                                <strong><?php echo (isset($facturas) && is_array($facturas)) ? count($facturas) : 0; ?></strong> Facturas
	                            </td>
	                        </tr>
	                        <tr>
	                            <td>
	                                <strong><?php echo (isset($contactos) && is_array($contactos)) ? count($contactos) : 0; ?></strong> Contactos
	                            </td>
	                        </tr>
	                        </tbody>
	                    </table>
	                </div>
	                <div class="col-md-4">
	                    <small>Balance <?php echo $grafico['intervalo']; ?> meses</small>
	                    <h2 class="no-margins"><small>$</small><?php echo $grafico['total']; ?></h2>
		                <span class="sparkline" sparkType="line" sparkBarColor="green"><?php echo $grafico['valores']; ?></span>
	                </div>
	            </div>
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($detalle['id'])) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Preferencias</h5>
		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('administracion/empresas/preferencias/' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> Cambiar preferencias</i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
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
		                </div>
		            </div>
	            </div>
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($detalle['razon_social'])) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Datos de facturación</h5>
		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('administracion/empresas/actualizar-datos-fiscales/' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> <?php echo (isset($detalle['razon_social'])) ? 'Actualizar' : 'Ingresar'; ?> datos fiscales</i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
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
		                </div>
		            </div>
	            </div>
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($detalle['titular'])) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Datos para el débito</h5>

		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('administracion/cuentas/' . (isset($detalle['id_cuenta']) ? 'modificar/' . $detalle['id_cuenta'] : 'ingresar?id_empresa=' . $detalle['id'])); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> <?php echo (isset($detalle['id_cuenta'])) ? 'Actualizar' : 'Ingresar'; ?> datos de la cuenta</i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
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
		                </div>
		            </div>
	            </div>
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($contactos)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Contactos</h5>
		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('administracion/contactos/ingresar?id_empresa=' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> Ingresar contacto</i>
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
					                                <?php if (isset($contacto['telefono'])) { ?>
														<i class="fa fa-phone"></i> <a href="<?php echo base_url('voip/llamar/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['telefono']; ?></a>
													<?php } ?>
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
		                            <a href="<?php echo base_url('administracion/servicios/ingresar?id_empresa=' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> Ingresar servicio</i>
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
				                                <td><a href="<?php echo base_url('administracion/servicios/detalle/'); ?><?php echo $servicio['id']; ?>"><?php echo (isset($servicio['descripcion'])) ? strip_tags($servicio['descripcion']) : 'Sin descipción'; ?></a>
				                                	<br>
													<small><span class="badge <?php echo $servicio['operacion_ui_class']; ?>"><?php echo $servicio['operacion']; ?></span> <?php echo $servicio['categoria']; ?></small></td>
				                                <td class="text-right"><?php echo $servicio['simbolo']; ?><strong><?php echo $servicio['total']; ?></strong>
													<?php if ($servicio['descuento_porcentaje'] > 0) : ?>
														<br>
														<small>- <?php echo $servicio['descuento_porcentaje']; ?>% de <?php echo $servicio['simbolo']; ?><?php echo $servicio['valor']; ?></small>
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
	            
	            
	            <div class="row">
	            	<div class="col-lg-12">
		                <div class="ibox <?php echo (isset($facturas)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Facturas</h5>
		                        <div class="ibox-tools">
			                        <?php if (isset($detalle['razon_social'])) { ?>
			                            <a href="<?php echo base_url('administracion/facturas/ingresar?id_empresa='); ?><?php echo $detalle['id']; ?>" class="btn btn-outline btn-xs">
			                                <i class="fa fa-plus-circle"> Ingresar factura</i>
			                            </a>
			                            <a href="<?php echo base_url('administracion/empresas/facturas-y-pagos/' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
			                                <i class="fa fa-file"> Facturas y pagos</i>
			                            </a>
		                            <?php } ?>
		                            <a href="<?php echo base_url('administracion/empresas/balance/' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-bar-chart-o"> Balance</i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
		                        <table class="table">
		                            <thead>
			                            <tr>
	                                        <th>Comprobante</th>
	                                        <th>Razón Social</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Vencimiento</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
		                            </thead>
		                            <tbody>
			                            <?php if (isset($facturas)) { ?>
				                            <?php foreach ($facturas as $factura) { ?>
				                            <tr>
				                                <td>
					                                <a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $factura['id']; ?>"><?php echo $factura['comprobante']; ?></a>
			                                    	<?php echo formatear_fecha($factura['fecha'], 'd-m-Y', '<br><small>%s</small>', $this->usuario->timezone); ?>
			                                    </td>
		                                        <td><?php echo $factura['razon_social']; ?></td>
		                                        <td><?php echo $factura['forma_pago']; ?><br><span class="badge <?php echo $factura['operacion_ui_class']; ?>"><?php echo $factura['operacion']; ?></span></td>
		                                        <td class="text-right">
			                                        <?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?>
			                                        <br>
			                                        <small><?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?></small>
		                                        </td>
		                                        <td class="text-center">
			                                        <?php echo formatear_fecha($factura['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?>
			                                        <?php
			                                        	if (isset($factura['recibido']))
			                                        	{
				                                        	echo formatear_fecha($factura['recibido'], 'd-m-Y', '<br><small><em>(Recibida: %s)</em></small>', $this->usuario->timezone, null);
				                                        }
				                                        elseif (isset($factura['enviado']))
				                                        {
					                                        echo formatear_fecha($factura['enviado'], 'd-m-Y', '<br><small><em>(Enviada: %s)</em></small>', $this->usuario->timezone, null);
				                                        }
					                                ?>
			                                    </td>
		                                        <td class="text-center"><span class="label <?php echo $factura['estado_ui_class']; ?>"><?php echo $factura['estado']; ?></span></td>
				                            </tr>
				                            <?php } ?>
			                            <?php } else { ?>
			                            	<tr>
				                                <td colspan="6">No se encontraron Facturas</td>
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
		                <div class="ibox <?php echo (isset($proyectos)) ? 'float-e-margins' : 'collapsed'; ?>">
		                    <div class="ibox-title">
		                        <h5>Proyectos</h5>
		                        <div class="ibox-tools">
		                            <a href="<?php echo base_url('administracion/proyectos/ingresar?id_empresa=' . $detalle['id']); ?>" class="btn btn-outline btn-xs">
		                                <i class="fa fa-plus-circle"> Ingresar proyecto</i>
		                            </a>
		                        </div>
		                    </div>
		                    <div class="ibox-content">
		                        <table class="table">
		                            <thead>
			                            <tr>
			                                <th>#</th>
	                                        <th>Título</th>
	                                        <th class="text-center">Responsable</th>
	                                        <th class="text-center">Fecha</th>
	                                        <th class="text-center">Estado</th>
			                            </tr>
		                            </thead>
		                            <tbody>
			                            <?php if (isset($proyectos)) { ?>
				                            <?php foreach ($proyectos as $proyecto) { ?>
				                            <tr>
				                                <td><?php echo $proyecto['id']; ?></td>
		                                        <td><a href="<?php echo base_url('administracion/proyectos/detalle/'); ?><?php echo $proyecto['id']; ?>"><?php echo $proyecto['titulo']; ?></a></td>
		                                        <td class="text-center"><?php echo $proyecto['responsable']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($proyecto['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><span class="label <?php echo $proyecto['estado_ui_class']; ?>"><?php echo $proyecto['estado']; ?></span></td>
				                            </tr>
				                            <?php } ?>
			                            <?php } else { ?>
			                            	<tr>
				                                <td colspan="5">No se encontraron Proyectos</td>
			                            	</tr>
			                            <?php } ?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
	            </div>

	        </div>
			
	        <!-- Sparkline -->
		    <script src="<?php echo base_url('assets/js/plugins/sparkline/jquery.sparkline.min.js'); ?>"></script>
		
		    <script>
		        $(document).ready(function() {
			        //$('.sparkline').sparkline();
			        $('.sparkline').sparkline('html', { enableTagOptions: true,  width:100, height: '50', lineColor: '#1ab394', fillColor: 'transparent' });
			    });
		    </script>
		    