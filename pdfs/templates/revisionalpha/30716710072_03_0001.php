<style type="text/css">
	* { margin:0; padding:0; box-sizing:border-box; }

	#wrap > table td { vertical-align:top; }

	p, td, th { font-size:12px; line-height:1.5; font-family:'proxima-r'; }
	h2 { font-size:20px; font-family:'proxima-sb'; }
	h3 { font-size:14px; font-family:'proxima-sb'; }

	.table-info { border:1px solid #DDD; border-radius:5px; }
	.table-info td, .table-info th { padding:8px 12px; }

	.table-detalle { width:100%; }
	.table-detalle td, .table-detalle th { padding:8px 12px; }
	.table-detalle th { border-left:1px solid #DDD; border-top:1px solid #DDD; border-bottom:1px solid #DDD; font-family:'proxima-sb'; }
	
	.text-sm { font-size:14px; }
	.text-xs { font-size:12px; }
	
	.tc-red-5 { color:#FF1A1D !important; }
	.tw-light { font-family:'proxima-l'; }
	.tw-regular { font-family:'proxima-r'; }
	.tw-bold { font-family:'proxima-b'; }
	.tw-semibold, strong { font-family:'proxima-sb'; font-weight:normal; }
	
	.section-title { display:block; font-family:'proxima-l'; font-size:40px; line-height:1.3; margin:0; }
	.section-title.section-title-small { font-size:26px; line-height:1.5; }
	.section-title.section-title-full { font-size:26px; font-family:'proxima-sb'; border-bottom: 3px solid #FF1A1D; text-transform:uppercase; }
	.section-title > span { display:inline-block; border-bottom:3px solid #FF1A1D; }
	
	.general-title { display:block; font-family:'proxima-sb'; font-size:40px; line-height:1.3; margin:0; }
	.general-title > span { display:inline-block; border-bottom:3px solid lightgrey; padding-bottom:15px; }
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
								<td style="width:328px; padding-left: 10px;">
									<h1><img src="https://cms.revisionalpha.com/pdfs/templates/revisionalpha/images/revision-alpha.png" alt="REVISION ALPHA" height="35"></h1><br>
								</td>
								<td style="width:100px;">&nbsp;</td>
								<td style="width:328px;">
									<h2 class="tw-semibold">NOTA DE CRÉDITO A <?php echo $_POST['numero_talonario']; ?>-<?php echo $_POST['numero_factura']; ?></h2>
									<h3 class="tw-regular"><?php if ( !empty( $_POST['vencimiento'] ) ) : ?>
											VTO: <?php echo $_POST['vencimiento']; ?>
										<?php else : ?>
											&nbsp;
										<?php endif; ?>
									</h3><br>
								</td>
							</tr>
							<tr>
								<td style="padding-left: 10px;">
									<p class="tw-semibold tc-red-5">REVISION ALPHA S.A.S.<br>
									<span class="tw-regular" style="color:#808080;">Vuelta de Obligado 2443 Of. 403, CABA<br>
									+54.11 5274.8490<br>
									I.V.A. Responsable Inscripto</span></p>
								</td>
								<td>&nbsp;</td>
								<td>
									<p><strong>Fecha:</strong> <?php echo $_POST['fecha']; ?><br>
									<strong>CUIT:</strong> 30-71671007-2<br>
									<strong>ISIB:</strong> 1585344-06<br>
									<strong>Inicio de Actividad:</strong> 10/12/2019</p>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<div style="height:40px;"></div>
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
				<td style="height:535px;">
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
								<td align="right">$<?php echo number_format( $item->valor, 2, ',', '.' ); ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Detalle -->
				
			<tr>
				<td style="height:50px;">
					<table cellspacing="0" class="table-detalle">
						<tbody>
							<?php if ($_POST['documento_numero'] < 30000000000) { ?>
							<tr>
								<td>
									<div style="width:756px; line-height:1.2;"><small class="text-xs tw-light"><em>El crédito fiscal discriminado en el presente comprobante, sólo podrá ser computado a efectos del Régimen de Sostenimiento e Inclusión Fiscal para Pequeños Contribuyentes de la Ley Nº 27.618.</em></small></div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
			</tr> <!-- FIN Observaciones -->

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
													<strong>Subtotal con descuento:</strong><br>
													<?php endif; ?>
													<strong>IVA 21%:</strong><br>
													<strong>Importe Total:</strong></p>
												</td>
												<td style="width:112px;" align="right">
													<p>$<?php echo number_format( $_POST['bruto'], 2, ',', '.' ); ?><br>
													<?php if ( $_POST['descuento'] > 0 ) : ?>
													$<?php echo number_format( $_POST['descuento'], 2, ',', '.' ); ?><br>
													$<?php echo number_format( $_POST['subtotal210'], 2, ',', '.' ); ?><br>
													<?php endif; ?>
													$<?php echo number_format( $_POST['imp210'], 2, ',', '.' ); ?><br>
													<span class="tw-semibold tc-red-5">$<?php echo number_format( $_POST['total_neto'], 2, ',', '.' ); ?></span></p>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr><td height="20"></td></tr>

							<tr>
								<td>
									<table cellspacing="0">
										<tbody>
											<tr>
												<td style="width:150px;" align="center">
													<?php
													if (isset($_POST['qr']))
													{
														echo $_POST['qr'];
													}
													else
													{
														?>
														<barcode type="I25"
															value="<?php echo $_POST['numeroCodigoBarras']; ?>" label="none"
															style="width:50px; height:50px; font-size: 1mm"></barcode>
														<?php
													}
													?>
												</td>
												<td style="width:451px; padding-left: 20px;">
													<p class="tw-semibold">CAE N&deg;: <?php echo $_POST['CAE']; ?><br>
														Vto. de CAE: <?php echo $_POST['CAEFchVto']; ?><br>
														<?php echo $_POST['numeroCodigoBarras']; ?></p>
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