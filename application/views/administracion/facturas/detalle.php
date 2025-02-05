<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
			
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/facturas'); ?>">Facturas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=115&id_referencia=' . $factura['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
	                    <?php } ?>
						
						<?php if ($this->usuario->perfil == 'reseller' && $archivos[0]) { ?>
							<a href="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/archivos/' . $archivos[0]['archivo']); ?>" target="_blank" class="btn btn-white btn-sm"><i class="fa fa-paperclip"></i></a>
						<?php } elseif ($this->usuario->perfil == 'reseller' && $factura['operacion'] == 'C') { ?>
							<a href="<?php echo base_url('archivos/ingresar/115/' . $factura['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-upload"></i></a>
						<?php } ?>
	                    
	                    <?php if ($this->usuario->perfil == 'reseller' && isset($factura['error']) && $afip['accion'] == 'recalcular') { ?>
	                    	<a href="<?php echo base_url('administracion/facturas/recalcular/' . $factura['id']); ?>" class="btn btn-primary btn-sm">Recalcular factura</a>
	                    <?php } ?>
	                    
	                    <?php if ($factura['id_estado'] == 1 || $factura['id_estado'] == 7) { ?>
							<?php 
								if ($this->usuario->id == 475) { ?>
                        		<a href="https://billing.revisionalpha.com/facturas/llamafacturarxid/<?php echo $factura['id']; ?>" target="_blank" class="btn btn-white"><i class="fa fa-smile-o"></i> Facturar </a>
							<?php } else { ?>
								<a href="http://staging.cms.revisionalpha.com/sistemafacturacion/facturas/llamafacturarxid/<?php echo $factura['id']; ?>" target="_blank" class="btn btn-white"><i class="fa fa-external-link"></i> Facturar </a>
							<?php } ?>
                        <?php } ?>
	                    
						<?php if (isset($factura['link']) && $factura['id_estado'] == 2) { ?>
							<a href="<?php echo $factura['link']; ?>" target="_blank" class="btn btn-white"><i class="fa fa-share-alt"></i> Compartir </a>
						<?php } ?>
						
						<?php if ($this->usuario->perfil == 'reseller' && $factura['total_neto'] == $factura['saldo'] && !isset($factura['padre']) && $factura['operacion'] == 'V' && isset($factura['id_afip']) && $factura['id_estado'] == 2) { ?>
	                    	<a href="<?php echo base_url('administracion/facturas/ingresar-nota-de-credito/' . $factura['id']); ?>" class="btn btn-primary btn-sm">Nota de crédito </a>
	                    <?php } elseif ($this->usuario->perfil == 'reseller' && $factura['total_neto'] == $factura['saldo'] && !isset($factura['padre']) && (!isset($factura['id_afip']) OR $factura['id_estado'] != 2)) { ?>
                        	<a href="<?php echo base_url('administracion/facturas/eliminar/') . $factura['id']; ?>" class="btn btn-primary btn-sm">Eliminar factura</a>
						<?php } ?>
	                    
	                    <?php if ($this->usuario->perfil == 'reseller' && $factura['saldo'] > 0 && !isset($factura['padre']) && $factura['id_estado'] == 2) { ?>
	                    		<a href="<?php echo base_url('administracion/movimientos/ingresar?id_empresa=' . $factura['id_empresa'] . '&id_factura=' . $factura['id'] . '&operacion=' . $factura['operacion'] . '&valor=' . $factura['saldo']); ?>" class="btn btn-primary btn-sm"><?php echo ($factura['operacion'] == 'C') ? 'Ingresar pago' : 'Ingresar cobro'; ?></a>
	                    <?php } ?>
	                    
	                    <?php if ($this->usuario->perfil == 'reseller' && $factura['id_estado'] == 1 && $factura['operacion'] == 'V' && $factura['id_afip'] == 19) { ?>
							<a href="<?php echo base_url('administracion/facturas/modificar/') . $factura['id']; ?>" class="btn btn-primary btn-sm">Modificar</a>
							<?php if (isset($factura['numero_factura']) && isset($factura['cae_numero']) && $factura['id_estado'] == 1) { ?>
								<a href="<?php echo base_url('administracion/facturas/marcar-como-impresa/' . $factura['id']); ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Marcar como impresa </a>
							<?php } ?>
						<?php } ?>
						
	                    <?php if ($this->usuario->perfil == 'reseller' && $factura['id_estado'] == 1 && ($factura['operacion'] == 'C' OR !isset($factura['id_afip']))) { ?>
	                    		<a href="<?php echo base_url('administracion/facturas/marcar-como-impresa/' . $factura['id']); ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Marcar como impresa </a>
	                    <?php } ?>
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
				<?php if ($this->usuario->perfil == 'reseller' && $factura['operacion'] == 'V' && $factura['id_afip'] == 19 && !empty($factura['numero_factura']) && !empty($factura['cae_numero'])) { ?>
				<div class="alert alert-info">
						Subir el comprobante de AFIP con el siguiente nombre a <a href="ftp://wsaa.revisionalpha.com/">http://wsaa.revisionalpha.com/pdfs/30716710072_<?php echo $factura['id_afip']; ?>_<?php echo sprintf("%04s", $factura['numero_talonario']); ?>_<?php echo sprintf("%08s", $factura['numero_factura']); ?>.pdf</a>
						<br>
						<em>(Usuario: comprobantes@wsaa.revisionalpha.com | Contraseña: laquequieras)</em>
				</div>
				<?php } ?>
				
				<?php if (isset($alerta) && $alerta == 'pendiente') { ?>
					<div class="alert alert-warning">Recuerde completar el proceso de pago.</div>
				<?php } elseif (isset($alerta) && $alerta == 'error') { ?> 
               		<div class="alert alert-danger">No se ha podido procesar el pago.</div>
               	<?php } elseif (isset($factura['error'])) { ?> 
					<div class="alert alert-danger">
						<?php echo $factura['error']; ?>
						<?php  echo '<pre>' . print_r($afip, true) . '</pre>'; ?>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-content p-xl">
	                            <div class="row">
	                                <div class="col-sm-12 text-right">
	                                    <h4 class="text-danger"><?php echo $factura['comprobante']; ?></h4>
	                                    <p>
			                                <small><?php echo formatear_fecha($factura['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></small>
			                                <br>
			                                <?php if (isset($factura['vencimiento'])) { ?>
											<span>Vencimiento: <strong><?php echo formatear_fecha($factura['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?></strong></span>
											<?php } ?>
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
			                                <br>
			                                <span><a href="<?php echo base_url('administracion/empresas/detalle/' . $factura['id_empresa']); ?>"><?php echo $factura['empresa']; ?> (<?php echo $factura['razon_social']; ?>)</a></span>
	                                    </p>
	                                </div>
	                            </div>
	
	                            <div class="table-responsive m-t">
	                                <table class="table invoice-table">
	                                    <thead>
	                                    <tr>
	                                        <th>Descripción</th>
	                                        <th>Importe</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                <?php foreach ($factura['items'] as $items) { ?>
	                                    <tr>
	                                        <td>
		                                        <?php if ($this->usuario->perfil == 'reseller' && $factura['id_estado'] != 2) { ?>
		                                        	<a href="<?php echo base_url('administracion/facturas/modificar-item/' . $items['id']); ?>"><?php echo $items['descripcion']; ?></a>
												<?php } else { ?>
													<?php echo $items['descripcion']; ?>
												<?php } ?>
		                                    </td>
	                                        <td><?php if ($items['descuento'] != 0.00) echo '<em>(Descuento: ' . $factura['simbolo'] . $items['descuento'] . ')</em>'; ?> <?php echo $factura['simbolo']; ?><?php echo $items['valor']; ?></td>
	                                    </tr>
	                                    <?php } ?>
	                                    </tbody>
	                                </table>
	                            </div><!-- /table-responsive -->
	
	                            <table class="table invoice-total">
	                                <tbody>
	                                <tr>
	                                    <td><strong>Subtotal:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['bruto']; ?></td>
	                                </tr>
	                                <?php if ($factura['descuento'] > 0) { ?>
	                                <tr>
	                                    <td><strong>Descuento:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['descuento']; ?></td>
	                                </tr>
									<?php } ?>
									<?php if ($factura['imp105'] > 0) { ?>
									<tr>
	                                    <td><strong>Subtotal I.V.A 10.5%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['subtotal105']; ?></td>
	                                </tr>
	                                <tr>
	                                    <td><strong>I.V.A 10.5%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['imp105']; ?></td>
	                                </tr>
	                                <?php } ?>
	                                <?php if ($factura['imp210'] > 0) { ?>
									<tr>
	                                    <td><strong>Subtotal I.V.A 21%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['subtotal210']; ?></td>
	                                </tr>
	                                <tr>
	                                    <td><strong>I.V.A 21%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['imp210']; ?></td>
	                                </tr>
	                                <?php } ?>
	                                <?php if ($factura['imp270'] > 0) { ?>
									<tr>
	                                    <td><strong>Subtotal I.V.A 27%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['subtotal270']; ?></td>
	                                </tr>
	                                <tr>
	                                    <td><strong>I.V.A 27%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['imp270']; ?></td>
	                                </tr>
	                                <?php } ?>
	                                <tr>
	                                    <td><strong>Importe Total:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?></td>
	                                </tr>
	                                <tr>
	                                    <td><strong>Saldo:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?></td>
	                                </tr>
	                                </tbody>
	                            </table>
	                            
								<?php if (isset($factura['observaciones'])) { ?>
	                            <div class="well m-t"><strong>Observaciones</strong>
	                                <?php echo $factura['observaciones']; ?>
	                            </div>
	                            <?php } ?>
	                        </div>
		                </div>
		            </div>
				</div>
				
				<?php if (isset($movimientos)) { ?>
				<div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th>Fecha</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($movimientos as $movimiento) { ?>
		                                    <tr>
			                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td><?php echo $movimiento['forma_pago']; ?> (<?php echo $movimiento['cuenta']; ?>)</td>
		                                        <td class="text-right"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
		                                        <td class="text-center"><span class="label <?php echo $movimiento['estado_ui_class']; ?>"><?php echo $movimiento['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	        </div>