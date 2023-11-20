<!-- Tablas -->
<link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12">
                <h2>Dashboard</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/carrito/dashboard'); ?>">Home</a>
                    </li>
                    <li class="active">
                        <strong>Dashboard</strong>
                    </li>
                </ol>
            </div>
        </div>
            


        <div class="wrapper wrapper-content">
        	<div class="row">
                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Ingresados</span>
                            <h5>Pedidos</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins font-bold text-success"><?php echo $totalpedidos['total']; ?></h1>
                            <small>Total de pedidos ingresados</small>
                            <a href="<?php echo base_url('cms-v2/carrito/pedidos');?>" class="btn-success btn-xs pull-right btn-outline"><i class="fa fa-shopping-cart"></i> Ver pedidos</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-success pull-right">Pagados</span>
                            <h5>Pedidos</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins font-bold text-success"><?php echo $totalpagados['total']; ?></h1>
                            <small>Total de pedidos pagados</small>
                            <a href="<?php echo base_url('cms-v2/carrito/pedidos');?>" class="btn-success btn-xs pull-right btn-outline"><i class="fa fa-dollar"></i> Ver pedidos</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-info pull-right">Ingresados</span>
                            <h5>Productos</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins font-bold text-info"><?php echo $totalproductos['total']; ?></h1>
                            <small>Total de productos ingresados</small>
                            <a href="<?php echo base_url('cms-v2/carrito/productos');?>" class="btn-info btn-xs pull-right btn-outline"><i class="fa fa-truck"></i> Ver productos</a>
                        </div>
                    </div>
                </div>
        	</div>

        	<div class="row">
                <div class="col-lg-6">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Listados de Pedidos</h5></div>
	                    <div class="ibox-content">
	                        <table class="table table-hover no-margins">
	                            <thead>
		                            <tr>
		                                <th>Nro.</th>
		                                <th>Fecha</th>
		                                <th>Monto</th>
		                                <th>Estado</th>
		                            </tr>
	                            </thead>
	                            <tbody>
		                           <?php if($pedidos) { foreach($pedidos as $pedido) { ?> 
		                           <tr>
		                                <td><?php echo $pedido['id']; ?></td>
		                                <td><?php echo $pedido['fecha_alta']; ?></td>
		                                <td><?php echo $pedido['total']; ?></td>
		                                <td><?php echo $pedido['estado']; ?></td>
		                            </tr>
		                           <?php } } else { ?> 
		                           <tr>
		                                <td colspan="4">No se encontraron pedidos</td>
		                            </tr>
		                           <?php }  ?> 
	                            </tbody>
	                        </table>
	                        <a href="<?php echo base_url('cms-v2/carrito/pedidos'); ?>" class="btn btn-sm btn-primary m-t-md"><i class="fa fa-list"></i> Ver pedidos</a>
	                    </div>
	                </div>
	            </div>
	            
                <div class="col-lg-6">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Listado de Productos</h5></div>
	                    <div class="ibox-content">
	                        <table class="table table-hover no-margins">
	                            <thead>
	                            <tr>
	                                <th>Nombre</th>
			                        <th>Categor&iacute;a</th>
			                        <th>C&oacute;digo</th>
			                        <th>Estado</th>
	                            </tr>
	                            </thead>
	                            <tbody>
		                           <?php if($productos) { foreach($productos as $producto) { ?> 
		                           <tr>
		                                <td><?php echo $producto['titulo']; ?></td>
		                                <td><?php echo $producto['categoria']; ?></td>
		                                <td><?php echo $producto['codigo']; ?></td>
		                                <td><?php echo $producto['estado']; ?></td>
		                            </tr>
		                           <?php } } else { ?> 
		                           <tr>
		                                <td colspan="4">No se encontraron productos</td>
		                            </tr>
		                           <?php }  ?> 
	                            </tbody>
	                        </table>
	                        <a href="<?php echo base_url('cms-v2/carrito/productos'); ?>" class="btn btn-sm btn-primary m-t-md"><i class="fa fa-list"></i> Ver productos</a>
	                    </div>
	                    </div>
	                </div>
	            </div>
        	</div>