<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
			
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mi cuenta</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('variable_name'); ?>Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('variable_name'); ?>Mi cuenta</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('micuenta/facturas'); ?>"><?php echo $this->lang->line('variable_name'); ?>Facturas</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('variable_name'); ?>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if (isset($factura['descargar'])) { ?>
                        	<a href="<?php echo $factura['descargar']; ?>" class="btn btn-white"><i class="fa fa-save"></i> <?php echo $this->lang->line('variable_name'); ?>Guardar </a>
                        <?php } ?>
                        <?php if (isset($factura['link'])) { ?>
                        	<a href="<?php echo $factura['link']; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i><?php echo $this->lang->line('variable_name'); ?> Imprimir </a>
                        <?php } ?>
                    </div>
                </div>
	        </div>
	        
			<div class="wrapper wrapper-content animated fadeInRight">
				<?php if (isset($alerta) && $alerta == 'pendiente') { ?>
					<div class="alert alert-warning"><?php echo $this->lang->line('variable_name'); ?>Recuerde completar el proceso de pago.</div>
				<?php } elseif (isset($alerta) && $alerta == 'error') { ?> 
               		<div class="alert alert-danger"><?php echo $this->lang->line('variable_name'); ?>No se ha podido procesar el pago.</div>
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
											<span><?php echo $this->lang->line('variable_name'); ?>Vencimiento: <strong><?php echo formatear_fecha($factura['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?></strong></span>
											<br>
											<?php } ?>
		                                    <span><?php echo $factura['razon_social']; ?></span>
	                                    </p>
	                                </div>
	                            </div>
	
	                            <div class="table-responsive m-t">
	                                <table class="table invoice-table">
	                                    <thead>
	                                    <tr>
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Descripción</th>
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Importe</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                <?php foreach ($factura['items'] as $items) { ?>
	                                    <tr>
	                                        <td><?php echo $items['descripcion']; ?></td>
	                                        <td><?php echo $factura['simbolo']; ?><?php echo $items['valor']; ?></td>
	                                    </tr>
	                                    <?php } ?>
	                                    </tbody>
	                                </table>
	                            </div><!-- /table-responsive -->
	
	                            <table class="table invoice-total">
	                                <tbody>
	                                <tr>
	                                    <td><strong><?php echo $this->lang->line('variable_name'); ?>Subtotal:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['bruto']; ?></td>
	                                </tr>
	                                <?php if ($factura['descuento'] > 0) { ?>
	                                <tr>
	                                    <td><strong><?php echo $this->lang->line('variable_name'); ?>Descuento:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['descuento']; ?></td>
	                                </tr>
									<?php } ?>
									<?php if ($factura['imp210'] > 0) { ?>
									<tr>
	                                    <td><strong><?php echo $this->lang->line('variable_name'); ?>Subtotal con descuento:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['subtotal210']; ?></td>
	                                </tr>
	                                <tr>
	                                    <td><strong><?php echo $this->lang->line('variable_name'); ?>I.V.A 21%:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['imp210']; ?></td>
	                                </tr>
	                                <?php } ?>
	                                <tr>
	                                    <td><strong><?php echo $this->lang->line('variable_name'); ?>Importe Total:</strong></td>
	                                    <td><?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?></td>
	                                </tr>
	                                </tbody>
	                            </table>
	                            <?php if (isset($preference)) { ?>
	                            <div class="text-right">
	                                <a href="<?php echo $preference['response']['init_point']; ?>" class="btn btn-primary" name="MP-Checkout" title="Pagar comprobante con MercadoPago"><?php echo $this->lang->line('variable_name'); ?>Pagar</a>
	                            </div>
	                            <?php } ?>
	                            
	                            <?php if (isset($paypal)) { ?>
	                            <div class="text-right">
	                                <a href="<?php echo base_url('micuenta/facturas/paypal-checkout/' . $factura['id']); ?>" class="btn btn-primary" name="MP-Checkout" title="Pagar comprobante con PayPal"><?php echo $this->lang->line('variable_name'); ?>Pagar</a>
	                            </div>
	                            <?php } ?>
	                            
								<?php if (isset($factura['observaciones'])) { ?>
	                            <div class="well m-t"><strong><?php echo $this->lang->line('variable_name'); ?>Observaciones</strong>
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
		                                    <th><?php echo $this->lang->line('variable_name'); ?>Fecha</th>
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Forma de Pago</th>
	                                        <th class="text-right"><?php echo $this->lang->line('variable_name'); ?>Valor</th>
	                                        <th class="text-center"><?php echo $this->lang->line('variable_name'); ?>Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($movimientos as $movimiento) { ?>
		                                    <tr>
			                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td><?php echo $movimiento['forma_pago']; ?></td>
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