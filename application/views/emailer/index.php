<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<!-- navbar main -->
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
						</li>
						<li class="active">
	                        <strong>Mailer Dashboard</strong>
	                    </li>
					</ol>		
				</div>
				<div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('emailer/newsletters/ingresar/'); ?>" class="btn btn-primary btn-sm">Crear mensaje</a>
                    </div>
                </div>
			</div>

			<!-- cards -->
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
					
					<!-- card mensaje -->
		            <div class="col-lg-3">
			            <div class="widget style1 red-bg">
			                <a href="<?php echo base_url('emailer/newsletters'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
										<i class="fa fa-envelope-o fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span>Mensajes nuevos</span>
										<h2 class="font-bold"><?php echo $detalle['mensajes']; ?></h2>
									</div>
								</div>
							</a>		
			            </div>
					</div>

					<!-- card lista -->
					<div class="col-lg-3">
			            <div class="widget style1 lazur-bg">
			                <a href="<?php echo base_url('emailer/listas'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-tasks fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span>Listas de envío</span>
										<h2 class="font-bold"><?php echo $detalle['listas']; ?></h2>
									</div>
								</div>
							</a>		
			            </div>
					</div>

					<!-- card suscriptores -->
					<div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('emailer/listas'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-user fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span>Suscriptores</span>
										<h2 class="font-bold"><?php echo $detalle['suscriptores']; ?></h2>
									</div>
								</div>
							</a>		
			            </div>
					</div>

					<!-- card mensajes enviados -->
					<div class="col-lg-3">
			            <div class="widget style1 navy-bg">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-paper-plane-o fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span>Mensajes enviados</span>
										<h2 class="font-bold"><?php echo $detalle['enviados']; ?></h2>
									</div>
								</div>
							</a>		
			            </div>
					</div>

				</div>
			</div>
		
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row bg-white">
					<!-- grafico -->	
					<div class="col-sm-3">
						<div class="statistic-box" style="padding-top: 3px; padding-left: 2px; margin-top: 10px;">
							<h4>Créditos</h4>
							<hr style="margin-top: 3px; margin-bottom: 3px; background-color: gray;">
							<div class="row text-center">
								<div class="col-lg-12">
									<canvas id="doughnutChart" style="margin: 18px auto 0"></canvas>
									<h5>Utilizados: <?php echo $detalle['utilizados']; ?></h5>
									<h5>Restantes: <?php echo $detalle['restantes']; ?></h5>
									<h5><strong>Total: 1000000</strong></h5>
								</div>
							</div>
                        </div>
					</div>
				</div>
			</div>

			<!-- ChartJS-->
			<script src="assets/js/plugins/chartJs/Chart.min.js"></script>

			<!-- Toastr -->
			<script src="assets/js/plugins/toastr/toastr.min.js"></script>
			
			<!-- script grafico -->
	        <script>
				$(document).ready(function() {
					/* setTimeout(function() {
					}, 1300); */

					var data1 = [
						[0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,30],[11,10],[12,13],[13,4],[14,3],[15,3],[16,6]
					];
					var data2 = [
						[0,1],[1,0],[2,2],[3,0],[4,1],[5,3],[6,1],[7,5],[8,2],[9,3],[10,2],[11,1],[12,0],[13,2],[14,8],[15,0],[16,0]
					];
					$("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
						data1, data2
					],
							{
								series: {
									lines: {
										show: false,
										fill: true
									},
									splines: {
										show: true,
										tension: 0.4,
										lineWidth: 1,
										fill: 0.4
									},
									points: {
										radius: 0,
										show: true
									},
									shadowSize: 2
								},
								grid: {
									hoverable: true,
									clickable: true,
									tickColor: "#d5d5d5",
									borderWidth: 1,
									color: '#d5d5d5'
								},
								colors: ["#1C84C6", "#1ab394"],
								xaxis:{
								},
								yaxis: {
									ticks: 4
								},
								tooltip: false
							}
					);

					var doughnutData = {
						labels: ["Utilizados","Restantes"],
						datasets: [{
							data: [<?php echo $detalle['utilizados']; ?>, <?php echo $detalle['restantes']; ?>],
							backgroundColor: ["#dedede", "#a3e1d4"]
						}]
					} ;


					var doughnutOptions = {
						responsive: false,
						legend: {
							display: false
						}
					};


					var ctx4 = document.getElementById("doughnutChart").getContext("2d");
					new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});

					var doughnutData = {
						labels: ["Utilizados","Restantes"],
						datasets: [{
							data: [70,27],
							backgroundColor: ["#a3e1d4","#dedede"]
						}]
					} ;


					var doughnutOptions = {
						responsive: false,
						legend: {
							display: false
						}
					};


					var ctx4 = document.getElementById("doughnutChart2").getContext("2d");
					new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});

				});
			</script>