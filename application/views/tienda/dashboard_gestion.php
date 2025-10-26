<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Tienda</a>
	                    </li>
	                    <li>
	                        <strong>Tablero Gestión</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="<?php echo base_url('tienda/pedidos/listado/mensual'); ?>" title="Ver pedidos recibidos" class="label label-success pull-right">Ver pedidos</a>
                                <h5>Facturación del Mes</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $facturacion['total']; ?></h1>
                                <?php echo($comparativafactura >= 1) ? '<div class="stat-percent font-bold text-info">'.$comparativafactura.' <i class="fa fa-level-up"></i>': '<div class="stat-percent font-bold text-danger">'.$comparativafactura.' <i class="fa fa-level-down"></i>';?></div>
                                <small>Total facturas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="<?php echo base_url('tienda/pedidos/listado/mensual'); ?>" title="Ver pedidos recibidos" class="label label-info pull-right">Ver pedidos</a>
                                <h5>Pedidos del Mes</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $pedidosmes['total']; ?></h1>
                                <?php echo($comparativames >= 1) ? '<div class="stat-percent font-bold text-info">'.$comparativames.' <i class="fa fa-level-up"></i>': '<div class="stat-percent font-bold text-danger">'.$comparativames.' <i class="fa fa-level-down"></i>';?></div>
                                <small>Cantidad de pedidos del mes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">Mes actual</span>
                                <h5>Promedio $ Pedidos</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $promediofacturas; ?></h1>
                                <?php echo($comparativamesant >= 1) ? '<div class="stat-percent font-bold text-info">'.$comparativamesant.' <i class="fa fa-level-up"></i>': '<div class="stat-percent font-bold text-danger">'.$comparativamesant.' <i class="fa fa-level-down"></i>';?></div>
                                <small>Monto promedio de cada pedido</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <a href="<?php echo base_url('tienda/clientes/registrados'); ?>" title="Ver pedidos recibidos" class="label label-info pull-right">Ver clientes</a>
                                <h5>Clientes registrados por Mes</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $clientesmes['total']; ?></h1>
                                <?php echo($comparativaclientes >= 1) ? '<div class="stat-percent font-bold text-info">'.$comparativaclientes.' <i class="fa fa-level-up"></i>': '<div class="stat-percent font-bold text-danger">'.$comparativaclientes.' <i class="fa fa-level-down"></i>';?></div>
                                <small>Cantidad de Clientes registrados del Mes</small>
                            </div>
                        </div>
                     </div>
	            </div>
           </div>
           

           <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Pedidos por día del mes </h5>

<!--
                            <div class="pull-right">
		                    	<div class="btn-group">
			                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'todos') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard_gestion');?>">Todos </a>
			                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'diario') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard_gestion/diario/');?>">Día </a>
			                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'semanal') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard_gestion/semanal/');?>">Semana </a>
			                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'mensual') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard_gestion/mensual/');?>">Mes </a>
		                        </div>
                            </div>
-->
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                            <div class="col-lg-12">
			                        <div class="ibox-content">
			                            <div>
			                                <canvas id="barChart" height="80"></canvas>
			                            </div>
			                        </div>
			                </div>
                            </div>

                        </div>
                    </div>
                </div>
          </div>
                
    <!-- jQuery UI -->
    <script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/chartJs/Chart.min.js'); ?>"></script>

   <script>
	   $(document).ready(function() {
		$(function () {
		    var barData = {
		        labels: [<?php for($i=1; $i <= $diasactual; $i++) { echo '"'.$i.'",'; } ?> ],
		        datasets: [
		            {
		                label: "Cantidad de pedidos",
		                backgroundColor: 'rgba(84, 2, 178, 0.5)',
		                pointBorderColor: "#fff",
		                data: [
		                <?php 
		                	for($i=1; $i <= $diasactual; $i++) 
							{ 
								foreach ($pedidospordia as $pedido)
								{
									if ($i == $pedido['dia']) 
									{ 
										echo $pedido['pedidos'].','; 
									}
								}
								$buscar = in_array($i, array_column($pedidospordia, 'dia'));
								if(!$buscar) { echo '0,'; }
							} ?>
						]
		            }
		        ]
		    };

	    var barOptions = {
	        responsive: true
	    };
	    var ctx2 = document.getElementById("barChart").getContext("2d");
	    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
    
	    });
     });
    </script>
