<style type="text/css">
	* { margin:0; padding:0; box-sizing:border-box; }

	#wrap > table td { vertical-align:top; }

	p, td, th { font-size:12px; line-height:1.5; }
	h2 { font-size:20px; }
	h3 { font-size:14px; }

	.table-info { border:1px solid #DDD; border-radius:5px; }
	.table-info td, .table-info th { padding:8px 12px; }

	.table-detalle {  }
	.table-detalle td, .table-detalle th { padding:8px 12px; }
	.table-detalle th { border-left:1px solid #DDD; border-top:1px solid #DDD; border-bottom:1px solid #DDD; }
</style>
<div id="wrap" style="height:100%;">
	<!-- Espacio adicional al inicio del documento -->
	<div style="height:15px;"></div>
	
	<table cellspacing="0">
		<tbody>
			<tr>
				<td>
					<table cellspacing="0">
						<tbody>
							<tr>
								<td style="width:328px;">
									<h1><img src="/home/forge/cms.revisionalpha.com/pdfs/templates/revisionalpha/images/revision-alpha.png" alt="REVISION ALPHA" height="35"></h1><br>
								</td>
								<td style="width:100px;">&nbsp;</td>
								<td style="width:328px;">
									<h2>FACTURA N&deg; <?php echo $_POST['numero_talonario']; ?>-<?php echo $_POST['numero_factura']; ?></h2>
									<h3><?php if ( !empty( $_POST['vencimiento'] ) ) : ?>
											VTO: <?php echo $_POST['vencimiento']; ?>
										<?php else : ?>
											&nbsp;
										<?php endif; ?>
									</h3><br>
								</td>
							</tr>
							<tr>
								<td>
									<p>revision alpha S.L.<br>
									González Besada 39 Of. 4º B, Oviedo,<br>
									Asturias España<br>
									+34 722 372 858</p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p><strong>Fecha:</strong> <?php echo $_POST['fecha']; ?><br>
									<strong>NIF:</strong> 16704934<br>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<img src="/home/forge/cms.revisionalpha.com/pdfs/templates/revisionalpha/images/factura-E.jpg" alt="Factura E" width="752">
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Header -->

			<tr><td height="20"></td></tr>

			<tr>
				<td>
					<table cellspacing="0" class="table-info">
						<tbody>
							<tr>
								<td style="width:520px;">
									<?php if ( $_POST['id_documento_tipo'] != 96 ) : ?>
									<p><strong>Raz&oacute;n Social:</strong> <?php echo $_POST['razon_social']; ?><br>
									<?php else : ?>
									<p><strong>Nombre y Apellido:</strong> <?php echo $_POST['razon_social']; ?><br>
									<?php endif; ?>
									<?php if ( !empty( $_POST['domicilio'] ) ) : ?>
									<strong>Domicilio:</strong> <?php echo $_POST['domicilio']; ?><br>
									<?php endif; ?>
									<strong>IVA:</strong> <?php echo $_POST['condicion_iva']; ?></p>
								</td>
								<td style="width:160px; padding-left:0; text-align:right">
									<p><br>
									<?php if ( !empty( $_POST['domicilio'] ) ) : ?>
									<br>
									<?php endif; ?>
									<?php if ( !empty( $_POST['documento_numero'] ) ) : ?>
										<?php if ( $_POST['id_documento_tipo'] != 96 ) : ?>
										<strong>CUIT:</strong> <?php echo $_POST['documento_numero']; ?>
										<?php elseif ( $_POST['id_documento_tipo'] != 80 ) : ?>
										<strong>DNI:</strong> <?php echo $_POST['documento_numero']; ?>
										<?php endif; ?>
									<?php endif; ?>
									</p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Info -->

			<tr><td height="20"></td></tr>

			<tr>
				<td style="height:585px;">
					<table cellspacing="0" class="table-detalle">
						<thead>
							<tr>
								<th style="width:532px; border-radius:5px 0px 0px 5px;">Descripci&oacute;n</th>
								<th style="width:120px; border-right:1px solid #DDD; border-radius:0px 5px 5px 0px;" align="right">Importe</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach (json_decode($_POST['items']) as $item) { ?>
							<tr>
								<td align="left"><div style="width:520px;"><?php echo $item->descripcion; ?></div></td>
								<td align="right">$<?php echo number_format( $item->valor+($item->valor*$item->impuesto)/100 , 2, ',', '.' ); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Detalle -->

			<tr>
				<td>
					<table cellspacing="0">
						<tbody>
							<tr>
								<td>
									<table cellspacing="0" class="table-info">
										<tbody>
											<tr>
												<td style="width:545px;" align="right">
													<p><strong>Subtotal:</strong><br>
													<?php if ( $_POST['descuento'] > 0 ) : ?>
													<strong>Descuento:</strong><br>
													<?php endif; ?>
													<strong>Importe Total:</strong></p>
												</td>
												<td style="width:112px;" align="right">
													<p>$<?php echo number_format( $_POST['bruto']+($_POST['bruto']*$_POST['impuesto'])/100, 2, ',', '.' ); ?><br>
													<?php if ( $_POST['descuento'] > 0 ) : ?>
													$<?php echo number_format( $_POST['descuento']+($_POST['descuento']*$_POST['impuesto'])/100, 2, ',', '.' ); ?><br>
													<?php endif; ?>
													$<?php echo number_format( $_POST['total_neto'], 2, ',', '.' ); ?></p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Footer -->

		</tbody>
	</table>
</div>