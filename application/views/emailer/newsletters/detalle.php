<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/newsletters'); ?>">Mensajes</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a href="<?php echo base_url('emailer/templates/ver/' . $detalle['id_template']); ?>" class="btn btn-white btn-sm" target="_blank"><i class="fa fa-eye"></i> Ver plantilla</a>
                        <a href="<?php echo base_url('emailer/newsletters/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar mensaje</a>
                    </div>
                </div>
	        </div>

			<div class="wrapper wrapper-content animated fadeInRight" style="padding-bottom: 0%;">
				<div class="row">
					<div class="ibox">
						<div class="ibox-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="m-b-md">
										<h2>Detalle del mensaje <strong><?php echo $detalle['asunto']; ?></strong></h2><hr>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-10">
									<div class="row">
										<div class="col-lg-6">
											<dl class="dl-horizontal">
												<dt>Asunto:</dt> <dd class="text-navy"><?php echo $detalle['asunto']; ?></dd><br>
												<dt>Remitente:</dt> <dd><?php echo $detalle['remitente']; ?></dd><br>
												<dt>Email del remitente:</dt> <dd><?php echo $detalle['email']; ?></dd><br>
												<dt>Listas:</dt> <dd><?php echo $detalle['lista']; ?></dd><br>
											</dl>
										</div>
										<div class="col-lg-6" id="cluster_info">
											<dl class="dl-horizontal" >
												<dt>Estado:</dt> <dd><span class="label <?php echo $detalle['estado_ui_class']; ?>"><?php echo $detalle['estado']; ?></span></dd><br>
												<dt>Suscriptores:</dt> <dd><?php echo $detalle['suscriptores']; ?></dd><br>
												<dt>Fecha de inicio:</dt> <dd><?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?></dd><br>
												<dt>Fecha de finalización:</dt> <dd><?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></dd><br>
											</dl>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<dl class="dl-horizontal">
												<dt>Completado:</dt>
												<dd>
													<div class="progress progress-striped active m-b-sm">
														<div style="width: 80%;" class="progress-bar"></div>
													</div>
													<small>Envío completado en un <strong>80%</strong>.</small>
												</dd>
											</dl>
										</div>
									</div>
								</div>
								<div class="col-lg-2" style="text-align:center">
									<button class="btn btn-primary btn-xs">Detener</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="m-b-md">
									<h2>Progreso del mensaje</h2>
									<hr>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<span class="label label-success pull-right">Mensual</span>
										<h5>Restantes</h5>
									</div>
								<div class="ibox-content">
									<h1 class="no-margins">408</h1>
									<div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div>
										<small>Total:</small>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<span class="label label-info pull-right">Anual</span>
										<h5>Enviados</h5>
									</div>
									<div class="ibox-content">
										<h1 class="no-margins"><?php echo $stats['progreso']['enviados']; ?></h1>
										<div class="stat-percent font-bold text-info">20% <i class="fa fa-level-up"></i></div>
										<small>Total:</small>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<span class="label label-primary pull-right">Diario</span>
										<h5>Fallidos</h5>
									</div>
									<div class="ibox-content">
										<h1 class="no-margins">106</h1>
										<div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div>
										<small>Total:</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wrapper wrapper-content animated fadeInRight" style="padding: 0%">
				<div class="row">
					<div class="ibox-content">
						<div class="row">
							<div class="col-lg-12">
								<div class="m-b-md">
									<h2>Estadísticas Generales</h2>
									<hr>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-md-4">
						<div class="ibox-title">
							<h5>Enviados</h5>
						</div>
						<div class="ibox-content">		
							<div>
								<span>Leídos</span>
								<small class="pull-right">60%</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 60%;" class="progress-bar"></div>
							</div>

							<div>
								<span>No leídos</span>
								<small class="pull-right">50</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 50%;" class="progress-bar"></div>
							</div>

							<div>
								<span>Rechazados</span>
								<small class="pull-right">40</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 40%;" class="progress-bar"></div>
							</div>

							<div>
								<span>FTP</span>
								<small class="pull-right">20</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 20%;" class="progress-bar progress-bar-danger"></div>
							</div>		
						</div>
					</div>

					<div class="col-md-4">
						<div class="ibox-title">
							<h5>Leídos</h5>
						</div>
						<div class="ibox-content">	
							<div>
								<span>Lecturas Totales</span>
								<small class="pull-right">60%</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 60%;" class="progress-bar"></div>
							</div>

							<div>
								<span>Lecturas Unicas</span>
								<small class="pull-right">50</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 50%;" class="progress-bar"></div>
							</div>

							<div>
								<span>Clicks</span>
								<small class="pull-right">40</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 40%;" class="progress-bar"></div>
							</div>

							<div>
								<span>Desuscriptos</span>
								<small class="pull-right">20</small>
							</div>
							<div class="progress progress-small">
								<div style="width: 20%;" class="progress-bar progress-bar-danger"></div>
							</div>		
						</div>
					</div>

					<div class="col-md-4">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Estadísticas de mensajes enviados</h5>
							</div>
							<div class="ibox-content">
								<div class="flot-chart">
									<div class="flot-chart-pie-content" id="flot-pie-chart"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Lecturas por días de la semana</h5>
							</div>
							<div class="ibox-content">
									<div class="flot-chart">
										<div class="flot-chart-content" id="flot-bar-chart"></div>
									</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="ibox float-e-margins">
							<div class="ibox-title">
								<h5>Lecturas por horas del día</h5>
							</div>
							<div class="ibox-content">
								<div class="flot-chart">
									<div class="flot-chart-content" id="flot-line-chart"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		    <div class="wrapper wrapper-content animated fadeInRight">
				<div class="alert alert-info">
						<?php echo '<pre>' . print_r($detalle, true) . '</pre>'; ?>
						<?php echo '<pre>' . print_r($lista, true) . '</pre>'; ?>
						<?php echo '<pre>' . print_r($stats, true) . '</pre>'; ?>
				</div>
			</div>

			<!-- Flot -->
			<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.tooltip.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.resize.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.pie.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/flot/jquery.flot.time.js'); ?>"></script>

			<!-- flot-pie-chart -->
			<script>
				$(function() {
						var data = [{
							label: "Leídos",
							data: 48,
							color: "#79d2c0",
						}, {
							label: "No leídos",
							data: 52,
							color: "#1ab394",
						}];

						var plotObj = $.plot($("#flot-pie-chart"), data, {
							series: {
								pie: {
									show: true
								}
							},
							grid: {
								hoverable: true
							},
							tooltip: true,
							tooltipOpts: {
								content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
								shifts: {
									x: 20,
									y: 0
								},
								defaultTheme: false
							}
						});

				});
			</script>

			<!-- flot-bar-chart --> 
			<script>
				$(function() {
					let ticks = [[1, "Domingo"], [2, "Lunes"], [3, "Martes"], [4, "Miercoles"], [5, "Jueves"],[6, "Viernes"], [7, "Sábado"]]
					let barOptions = {
						series: {
							bars: {
								show: true,
								barWidth: 0.5,
								fill: true,
								fillColor: {
									colors: [{
										opacity: 0.8
									}, {
										opacity: 0.8
									}]
								}
							}
						},
						xaxis: {
							tickDecimals: 0,
							ticks: ticks
						},
						colors: ["#1ab394"],
						grid: {
							color: "#999999",
							hoverable: true,
							clickable: true,
							tickColor: "#D4D4D4",
							borderWidth:0
						},
						legend: {
							show: false
						},
						tooltip: false,
						tooltipOpts: {
							content: "x: %x, y: %y"
						},
					};
					let barData = {
						
						label: "bar",
						data: [
							[1, 34],
							[2, 25],
							[3, 19],
							[4, 34],
							[5, 32],
							[6, 44],
							[7, 10]
						]
					};
					$.plot($("#flot-bar-chart"), [barData], barOptions);
				});
			</script>

			<!-- flot-line-chart -->
			<script>
				$(function() {
					let barOptions = {
						series: {
							lines: {
								show: true,
								lineWidth: 2,
								fill: true,
								fillColor: {
									colors: [{
										opacity: 0.0
									}, {
										opacity: 0.0
									}]
								}
							}
						},
						xaxis: {
							tickDecimals: 0
						},
						colors: ["#1ab394"],
						grid: {
							color: "#999999",
							hoverable: true,
							clickable: true,
							tickColor: "#D4D4D4",
							borderWidth:0
						},
						legend: {
							show: false
						},
						tooltip: true,
						tooltipOpts: {
							content: "%y"
						}
					};
					let barData = {
						label: "bar",
						data: [
							[1, 34],
							[2, 25],
							[3, 19],
							[4, 34],
							[5, 32],
							[6, 44],
							[7, 20],
							[8, 13],
							[9, 34],
							[10, 40],
							[11, 21],
							[12, 2],
						]
					};
					$.plot($("#flot-line-chart"), [barData], barOptions);
				});				
			</script>