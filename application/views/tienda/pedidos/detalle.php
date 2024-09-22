	<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/print-pedido.css'); ?>" rel="stylesheet" type="text/css" media="print">
	<style>
		.dl-pedidos dt { text-align:left; width:auto; margin-right:10px; }
		.dl-pedidos dd { margin-left:0;}
		.no-disponible { width:90px; height:90px; background:#ebebeb; text-align:center; padding-top:25px;}
		input::placeholder, ::placeholder { color:#666 !important; }
		input:read-only { color: green; }
	</style>
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('tienda/pedidos/'); ?>">Pedidos</a>
                    </li>
                    <li>
                        <strong>Detalle</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
			        <a href="<?php echo base_url('tienda/pedidos/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar Pedido</a>
                    <a href="javascript:window.print()" class="btn btn-primary btn-sm">Imprimir Pedido</a>
                </div>
            </div>
	     </div>

	     <div class="wrapper wrapper-content animated fadeInRight">		            
            <div class="row">
            	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" style="border-bottom:2px solid #e7eaec;">
	                        <h2>Pedido Nro. <?php echo $detalle['id']; ?>
	                        <small class="label-primary pull-right p-xs b-r-sm"> <?php echo $detalle['tipo_estado']; ?></small></h2>
	                        <div class="row">
	                        	<div class="col-sm-6">
			                        <dl class="dl-horizontal dl-pedidos">
		                                <dt>Nombre:</dt> <dd><?php echo ($detalle['nombre']) ? $detalle['nombre'] : ' ------ ';?></dd>
		                                <dt>Celular:</dt> <dd>   <?php echo ($detalle['celular']) ? $detalle['celular'] : ' ------ ';?></dd>
		                                <dt>Email:</dt> <dd><?php echo ($detalle['email']) ? '<a href="mailto:'.$detalle['email'].'" title=""> '.$detalle['email'].'</a>' : ' ------ ';?></dd>
		                                <dt>Domicilio:</dt> <dd> <?php echo ($detalle['domicilio']) ? $detalle['domicilio'] : ' ------ ';?> </dd>
		                                <dt>N&deg; de Cliente:</dt> <dd> <?php echo (isset($contacto['numero_cliente'])) ? $contacto['numero_cliente'] : ' ------ ';?> </dd>
		                                <dt>Sucursal:</dt> <dd> <?php echo (isset($sucursal['titulo'])) ? $sucursal['titulo'].' ('.$sucursal['domicilio'].' '.$sucursal['numero'].')' : ' ------ ';?> </dd>
			                        </dl>
		                        </div>
	                        	<div class="col-sm-6">
			                        <dl class="dl-horizontal dl-pedidos">
		                                <dt>Origen:</dt> <dd><?php echo ($detalle['id_tienda_origen'] == 1) ? 'Online' : 'En local';?> <?php echo ($detalle['numero_mesa']) ? ' - Mesa: '.$detalle['numero_mesa'] : '';?></dd>
		                                <dt>Fecha alta:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
		                                <dt>Fecha entrega:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
		                            </dl>
	                        	</div>
	                        </div>
                        </div>
                    </div>
            	</div>
            </div>

            <!-- Listado items -->
            <div class="row fila-impresion">
            	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <span class="pull-right">(<strong><?php echo $cantidaditems['cantidad'];?></strong>) <?php echo ($cantidaditems['cantidad'] > 1) ? 'items' : 'item';?></span>
                            <h5>Items del pedido</h5>
                        </div>

                        <div class="ibox-content">
                            <div class="table-responsive">
                            <?php if($items) { foreach ($items as $item) { ?>
                                <table class="table shoping-cart-table">
                                    <tbody>
	                                    <tr>
	                                        <td width="90">
	                                        	<?php echo ($item['imagen']) ? '<img src="'.base_url('/multimedia/thumbs/'.$item['imagen']).'" alt="'.$item['titulo'].'" width="90">' : '<div class="no-disponible">sin imagen</div>';?>
	                                        </td>
	                                        <td class="desc">
	                                            <h3><?php echo $item['titulo'];?> <?php echo ($item['codigo']) ? "(".$item['codigo'].")" : null; ?></h3>
	                                            <p class="small"><?php echo $item['contenido1'];?><br>
	                                            <b>Opciones:</b> <?php echo ($item['detalle']) ? $item['detalle'] : '----';?></p>
	                                        </td>
	
	                                        <td> <?php echo $tienda['simbolo'].' '.$item['precio'];?></td>
	                                        <td width="65"><input type="text" class="form-control" placeholder="<?php echo $item['cantidad'];?>" readonly="true"></td>
	                                        <td>
	                                            <h4><?php echo $tienda['simbolo'].' '.$item['subtotal'];?></h4>
	                                        </td>
	                                    </tr>

                                <?php }	?> 
                                <tr>
                                    <td colspan="5">
                                    <table style="width:100%;">
	                                    <tbody>
		                                    <tr>
		                                    	<td align="right"><h4 class="pull-right">Subtotal =</h4></td>
		                                    	<td><h4><?php echo $tienda['simbolo'].' '.$detalle['subtotal'];?></h4></td>
		                                    </tr>
		                                    <tr>
		                                    	<td><h4 class="pull-right">Descuentos =</h4></td>
		                                    	<td><h4><?php echo $tienda['simbolo']; echo($detalle['descuento']) ? ' '.$detalle['descuento'] : ' 0.00'; ?></h4></td>
		                                    </tr>
		                                    <tr>
		                                    	<td><h4 class="pull-right">Descuento medio de pago =</h4></td>
		                                    	<td><h4><?php echo $tienda['simbolo']; echo($detalle['descuento_medios_envio']) ? ' '.$detalle['descuento_medios_envio'] : ' 0.00'; ?></td>
		                                    </tr>
		                                    <tr>
		                                    	<td><h4 class="pull-right">Envío =</h4></td>
		                                    	<td><h4><?php echo $tienda['simbolo']; echo($detalle['envio']) ? ' '.$detalle['envio'] : ' 0.00'; ?></h4></td>
		                                    </tr>
		                                    <tr>
		                                    	<td><h4 class="pull-right">Total =</h4></td>
		                                    	<td><h4><?php echo $tienda['simbolo'].' '.$detalle['total'];?></h4></td>
		                                    </tr>
		                                    </tbody>
	                                    </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5">
                                       <div class="pull-right">
										  <code>Cupones aplicados: 
										  <?php 
										  	if ($cupones) 
										  	{  
										  		foreach($cupones as $cupon) 
										  		{ 
										  			echo $cupon['cupon'];
										  		} 
										  	} 
										  	else 
										  	{ 
										  		echo 'No hay cupones asociados'; 
										  	}
										  	?>
										  	</code>
                                       </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <?php } else { echo 'No hay items ingresados'; } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin Listado items -->
            </div>
            <!-- Fin Detalle -->
     </div>        