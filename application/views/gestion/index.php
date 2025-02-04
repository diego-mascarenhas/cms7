<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
		<h2>Gestión</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo base_url(); ?>">Home</a>
			</li>
			<li>
				<strong>Gestión administrativa</strong>
			</li>
		</ol>
	</div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-8">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<h2>Deudores</h2>
					<small>Empresas con saldo deudor por más de 45 días</small>

					<?php if (!empty($deudores))
					{ ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Empresa</th>
										<th>Contacto</th>
										<th class="text-center">Facturas</th>
										<th class="text-center">Vencimiento</th>
										<th class="text-right">Saldo</th>
									</tr>
								</thead>
								<tbody>
									<?php $total = 0; ?>
									<?php foreach ($deudores as $deudor)
									{ ?>
										<tr>
											<td><a
													href="<?php echo base_url('administracion/empresas/detalle/' . $deudor['id']); ?>"><?php echo $deudor['empresa']; ?></a>
											</td>
											<td><a
													href="<?php echo base_url('administracion/contactos/detalle/' . $deudor['id_contacto']); ?>"><?php echo $deudor['contacto']; ?></a>
											</td>
											<td class="text-center"><?php echo $deudor['facturas']; ?></td>
											<td class="text-center">
												<?php echo formatear_fecha($deudor['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?>
											</td>
											<td class="text-right">$<?php echo $deudor['saldo']; ?></td>
										</tr>
										<?php $total += $deudor['saldo']; ?>
									<? } ?>
									<tr>
										<td colspan="5" class="text-right"><strong>$<?php echo $total; ?></strong></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php }
					else
					{ ?>
						<ul class="todo-list m-t small-list">
							<li>
								<span class="text-info m-l-xs"><i class="fa fa-check-circle"></i> No hay empresas con saldo
									deudor</span>
							</li>
						</ul>
					<? } ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="widget style1 yellow-bg">
						<div class="row">
							<div class="col-xs-4">
								<i class="fa fa-usd fa-4x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<span>Dolar</span>
								<h2 class="font-bold"><?php echo $dolar['cambio']; ?></h2>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-12">
					<div class="widget style1">
						<div class="row">
							<div class="col-xs-4 text-center">
								<i class="fa fa-trophy fa-5x"></i>
							</div>
							<div class="col-xs-8 text-right">
								<span>Ingresos de hoy</span>
								<h2 class="font-bold">$ <?php echo ($ingresos) ? $ingresos : 0; ?></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<h2>Cuentas</h2>
					<small>Reporte de pagos aprobados</small>

					<?php if (!empty($cuentas))
					{ ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Cuenta</th>
										<th class="text-right">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($cuentas as $cuenta)
									{ ?>
										<tr>
											<td><a
													href="<?php echo base_url('gestion/cuentas/reporte/' . $cuenta['id_cuenta']); ?>"><?php echo $cuenta['cuenta']; ?></a>
											</td>
											<td class="text-right">$<?php echo $cuenta['total']; ?></td>
										</tr>
									<? } ?>
								</tbody>
							</table>
						</div>
					<?php }
					else
					{ ?>
						<ul class="todo-list m-t small-list">
							<li>
								<span class="text-info m-l-xs"><i class="fa fa-check-circle"></i> No hay registros por el
									momento</span>
							</li>
						</ul>
					<? } ?>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<h2>I.V.A.</h2>
					<small>Reporte I.V.A. compra / Venta del mes actual</small>

					<?php if (!empty($iva))
					{ ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>Tipo</th>
										<th class="text-right">Total</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($iva as $item)
									{ ?>
										<tr>
											<td><?php echo $item['operacion']; ?></td>
											<td class="text-right">$<?php echo $item['iva']; ?></td>
										</tr>
									<? } ?>
								</tbody>
							</table>
						</div>
					<?php }
					else
					{ ?>
						<ul class="todo-list m-t small-list">
							<li>
								<span class="text-info m-l-xs"><i class="fa fa-check-circle"></i> No hay registros por el
									momento</span>
							</li>
						</ul>
					<? } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content" id="ibox-content">

					<div id="vertical-timeline" class="vertical-container dark-timeline center-orientation">
						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-file-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Emisión de facturas</h2>
								<p>Controlar que en el reporte anual no haya nada descabezado y confirmar la gestión con
									AFIP.</p>
								<p>Marcar como impresa las facturas S.<br>
									Idóneo, Ciberiada y Carla De Loureiro.</p>
								<!-- 	                                    <a href="#" class="btn btn-sm btn-success">Reporte anual</a> -->
								<span class="vertical-date">
									Primer día<br />
									<small>1º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon blue-bg">
								<i class="fa fa-paper-plane-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Comunicación de facturas</h2>
								<p>Envío de facturas a los clientes.</p>
								<span class="vertical-date">
									Primer día<br />
									<small>1º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Débito automático</h2>
								<p>Generar débito automático desde la máquina virtual en el Datacenter (VPN).
									<?php if ($debito['total'] > 0)
									{ ?>
										<br>Total: $<?php echo $debito['total']; ?>
									<?php } ?>
								</p>
								<a href="<?php echo base_url('gestion/debito'); ?>"
									class="btn btn-sm btn-primary">Reporte</a>
								<span class="vertical-date">
									Segundo día<br />
									<small>2º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon yellow-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Cobros a domicilio</h2>
								<p>Llamar a Ismael Cuasnicú de <a href="/administracion/empresas/detalle/6555"
										target="_blank">Ciberiada</a> y coordinar con Gerardo de la mensajería <a
										href="/administracion/empresas/detalle/382" target="_blank">Time Fly</a> para
									retirar el pago.</p>
								<span class="vertical-date">
									Quinto día<br />
									<small>5º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-tasks"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Ajuste de cajas</h2>
								<p>Ajuste de cuenta corriente, Mercado Pago y PayPal.</p>
								<span class="vertical-date">
									Quinto día<br />
									<small>5º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon yellow-bg">
								<i class="fa fa-envelope"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Enviar comprobantes</h2>
								<p>Descargar resúmenes mensuales (fijarse que no se igual al del mes anterior) y de
									tarjetas de crédito.</p>
								<p>Bajar archivo CSV con las facturas de compra y venta.</p>
								<p>Comprimir la carpeta y enviarla al estudio contable.</p>
								<p>
									<?php $start_date = strtotime('-3 MONTHS');
									$start_quarter = ceil(date('m', $start_date) / 3);
									$start_year = date('Y', $start_date);

									$ano = date('Y', strtotime('-1 MONTH'));
									$mes = date('m', strtotime('-1 MONTH'));
									?>
									<a href="<?php echo base_url('administracion/facturas/exportar/trimestral/' . $start_year . '/' . $start_quarter . '/C'); ?>"
										class="btn btn-sm btn-primary">Comprobantes de compra (trimestral)</a><br><br>
									<a href="<?php echo base_url('administracion/facturas/exportar/mensual/' . $ano . '/' . $mes . '/C'); ?>"
										class="btn btn-sm btn-primary">Comprobantes de compra (mensual)</a><br><br>

									<a href="<?php echo base_url('administracion/facturas/exportar/trimestral/' . $start_year . '/' . $start_quarter . '/V'); ?>"
										class="btn btn-sm btn-primary">Comprobantes de venta (trimestral)</a><br><br>
									<a href="<?php echo base_url('administracion/facturas/exportar/mensual/' . $ano . '/' . $mes . '/V'); ?>"
										class="btn btn-sm btn-primary">Comprobantes de venta (mensual)</a>
								</p>
								<span class="vertical-date">
									Octavo día<br />
									<small>8º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon yellow-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Cobros a domicilio</h2>
								<p>Llamar a Gonzalo Frery de <a
										href="https://cms.revisionalpha.com/administracion/empresas/detalle/305"
										target="_blank">Idóneo</a> y coordinar por el pago de Casamientos Online.</p>
								<span class="vertical-date">
									Octavo día<br />
									<small>8º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon blue-bg">
								<i class="fa fa-paper-plane-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Aviso de facturas próximas a vencer</h2>
								<p>Comunicación de facturas próximas a vencer 48 hs antes.</p>
								<span class="vertical-date">
									Octavo día<br />
									<small>8º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon red-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Honorarios contables</h2>
								<p>Llevarle las facturas impresas y pagar los honorarios.
								</p>
								<span class="vertical-date">
									Décimo día<br />
									<small>10º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon red-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Tarjetas</h2>
								<p>Pasar gastos de tarjetas VISA.</p>
								<span class="vertical-date">
									Vigésimo sexto día<br />
									<small>10º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Cobro débito automático</h2>
								<p>Ingresar a <a href="https://wsec06.bancogalicia.com.ar/Users/LogIn/"
										target="_blank">Galicia Office</a>, no es necesario que sea por VPN desde la
									máquina virtual y descargar el archivo DEBITO.TXT de la sección Cobros, Pago
									directo, Recepción de archivo buscando el mismo por fecha.</p>
								<a href="<?php echo base_url('administracion/movimientos/importar'); ?>"
									class="btn btn-sm btn-primary">Importar</a>
								<span class="vertical-date">
									Décimo segundo<br />
									<small>12º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon red-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Pagar infraestructura</h2>
								<p>Gigared</p>
								<span class="vertical-date">
									Décimo segundo<br />
									<small>12º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon blue-bg">
								<i class="fa fa-paper-plane-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Aviso de facturas vencidas</h2>
								<p>Comunicación de facturas vencidas 48 hs después.</p>
								<span class="vertical-date">
									Décimo segundo día<br />
									<small>12º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon lazur-bg">
								<i class="fa fa-coffee"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Reunión quincenal</h2>
								<p>Pasar reporte de clientes, proyectos y servicios.</p>
								<span class="vertical-date">
									Décimo quinto día<br />
									<small>15º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon red-bg">
								<i class="fa fa-usd"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Pagar impuestos de AFIP</h2>
								<p>Pedir VEP de I.V.A. e Ingresos Brutos al estudio contable<br>
									Ingresarlos y pagarlos desde la opción pago de servicios (Pago mis cuentas) de <a
										href="https://www.bancogalicia.com/" target="_blank">Banco Galicia</a>.</p>

								<span class="vertical-date">
									Décimo quinto día<br />
									<small>15º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon blue-bg">
								<i class="fa fa-paper-plane-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Notificación de Suspensión de Servicios</h2>
								<p>Aviso de suspensión de servicios</p>
								<span class="vertical-date">
									Vigésimo sexto día<br />
									<small>26º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-globe"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Dominios</h2>
								<p>Renovar dominios <a href="https://nic.ar" target="_blank">Nic.Ar</a></p>
								<p>Importar Dominios desde <a href="https://secure.mydomain.com/secure/login.bml"
										target="_blank">MyDomain</a></p>
								<a href="<?php echo base_url('administracion/facturas/importar'); ?>"
									class="btn btn-sm btn-primary">Importar</a>
								<span class="vertical-date">
									Vigésimo sexto día<br />
									<small>26º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon navy-bg">
								<i class="fa fa-file-o"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Ingresar facturas de servicios</h2>
								<p><a href="https://mycloud.rackspace.com/" target="_blank">Rackspace</a></p>
								<p><a href="https://adwords.google.com/aw/billing/summary?ocid=97017278&billingId=189281678&__c=7873459022&authuser=0&__u=6827202182"
										target="_blank">Google Addwords</a></p>
								<p><a href="https://www.cablevisionfibertel.com.ar/clientes/factura"
										target="_blank">Fibertel</a></p>
								<p><a href="https://empresas.claro.com.ar/" target="_blank">Claro</p>
								<p><a href="http://negocios.movistar.com.ar/online" target="_blank">Movistar</a></p>
								<span class="vertical-date">
									Vigésimo sexto día<br />
									<small>26º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon yellow-bg">
								<i class="fa fa-phone"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Llamar a deudores</h2>
								<p>Llamar a los que tiene facturas sin pagar por más de 45 días y confirmar si quieren
									seguir teniendo el servicio.</p>
								<span class="vertical-date">
									Vigésimo sexto día<br />
									<small>26º día</small>
								</span>
							</div>
						</div>

						<div class="vertical-timeline-block">
							<div class="vertical-timeline-icon lazur-bg">
								<i class="fa fa-coffee"></i>
							</div>

							<div class="vertical-timeline-content">
								<h2>Reunión fin de mes</h2>
								<p>Si se facturó más que el mes anterior....... se almuerza sushi!!!</p>
								<span class="vertical-date">
									Vigésimo noveno día<br />
									<small>29º día</small>
								</span>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

</div>