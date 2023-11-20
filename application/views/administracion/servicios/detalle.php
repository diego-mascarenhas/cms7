<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/servicios'); ?>">Servicios</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ($this->usuario->perfil == 'reseller') { ?>
			            	<a href="<?php echo base_url('notas/ingresar?id_tipo=114&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
			            <?php } ?>
			            <?php if ($detalle['id_servicio_hosting']) { ?>
			            	<a class="btn btn-white btn-sm" href="<?php echo base_url('hosting/detalle/' . $detalle['id_servicio_hosting']); ?>"><i class="fa fa-info-circle"></i> Ver estadísticas del servicio</a>
		                <?php } ?>
		                <?php if ($detalle['id_estado'] == 4) { ?>
                        	<a href="<?php echo base_url('administracion/servicios/para-suspender/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Suspender servicio</a>
                        <?php } elseif ($detalle['id_estado'] == 1) { ?>
                        	<a href="<?php echo base_url('administracion/servicios/para-activar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Activar servicio</a>
                        <?php } ?>
                        <a href="<?php echo base_url('administracion/servicios/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar servicio</a>
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
	                            <label class="control-label">Categoría</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['categoria']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Operación</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['operacion']; ?></div>
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
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Moneda</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['moneda']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Estado</label>
	                            <div class="bg-muted p-xs b-r-sm">
		                            <?php echo $detalle['estado']; ?>
									<?php if ($detalle['id_estado'] == 2 && $detalle['id_tipo'] == 20) { ?>
                        	<a href="<?php echo base_url('administracion/servicios/suspender-tienda/' . $detalle['id'] . '/redirect/'); ?>" class="btn btn-primary btn-xs">Suspender tienda ahora</a>
                        			<?php } ?>
		                        </div>
	                        </div>
	                    </div>
	                </div>
	                <?php if ($this->usuario->perfil == 'reseller') { ?>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Forma de pago</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['forma_pago']; ?><?php if (!isset($detalle['id_forma_pago'])) echo ' <em>(Por defecto)</em>'; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Convertir</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo (isset($detalle['convertir'])) ? 'Si' : 'No'; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Auto suspender</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo (isset($detalle['autosuspender'])) ? 'Si' : 'No'; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <?php } ?>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Descripción</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo (isset($detalle['descripcion'])) ? $detalle['descripcion'] : 'Sin descipción'; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
		                <table class="table invoice-total">
                            <tbody>
                                <tr>
                                    <td><strong>Precio sin I.V.A:</strong></td>
                                    <td><?php echo $detalle['simbolo']; ?><?php echo $detalle['valor']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Descuento Frecuencia de Pago:</strong></td>
                                    <td>(<?php echo $detalle['descuento_frecuencia']; ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Descuento Forma de Pago:</strong></td>
                                    <td>(<?php echo $detalle['descuento_forma_pago']; ?>%)</td>
                                </tr>
                                
                                <tr>
                                    <td><strong>Descuento sobre el Servicio:</strong></td>
                                    <td>(<?php echo $detalle['descuento_servicio']; ?>%)</td>
                                </tr>
                                <tr>
                                    <td><strong>Descuento Total:</strong></td>
                                    <td><?php echo $detalle['simbolo']; ?><?php echo $detalle['descuento']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td><?php echo $detalle['simbolo']; ?><?php echo $detalle['total']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>I.V.A.:</strong></td>
                                    <td><?php echo $detalle['simbolo']; ?><?php echo $detalle['iva']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td><strong><?php echo $detalle['simbolo']; ?><?php echo $detalle['total_neto']; ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
	                </div>
	            </div>

	        </div>