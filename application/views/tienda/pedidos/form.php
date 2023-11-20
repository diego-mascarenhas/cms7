	<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
	<style>
		.dl-pedidos dt { text-align:left; width:auto; margin-right:10px; }
		.dl-pedidos dd { margin-left:0;}
		.no-disponible { width:90px; height:90px; background:#ebebeb; text-align:center; padding-top:25px;}
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
			        <a href="<?php echo base_url('tienda/pedidos/detalle/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Detalle Pedido</a>
                </div>
            </div>
	     </div>

	     <div class="wrapper wrapper-content animated fadeInRight">		            
            <div class="row">
            	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content pull-left full-width" style="border-bottom:2px solid #e7eaec;">
                        	<div class="col-lg-12">
		                        <h2>Pedido Nro. <?php echo $detalle['id']; ?></h2>
		                        <div class="row">
		                        	<div class="col-sm-4">
				                        <dl class="dl-horizontal dl-pedidos">
			                                <dt>Nombre:</dt> <dd><?php echo ($detalle['nombre']) ? $detalle['nombre'] : ' ------ ';?></dd>
			                                <dt>Celular:</dt> <dd>   <?php echo ($detalle['celular']) ? $detalle['celular'] : ' ------ ';?></dd>
			                                <dt>Email:</dt> <dd><?php echo ($detalle['email']) ? '<a href="mailto:'.$detalle['email'].'" title=""> '.$detalle['email'].'</a>' : ' ------ ';?></dd>
			                                <dt>Domicilio:</dt> <dd> <?php echo ($detalle['domicilio']) ? $detalle['domicilio'] : ' ------ ';?> </dd>
				                        </dl>
			                        </div>
		                        	<div class="col-sm-4">
				                        <dl class="dl-horizontal dl-pedidos">
			                                <dt>Origen:</dt> <dd><?php echo ($detalle['id_tienda_origen'] == 1) ? 'Online' : 'En local';?> <?php echo ($detalle['numero_mesa']) ? ' - Mesa: '.$detalle['numero_mesa'] : '';?></dd>
			                                <dt>Fecha alta:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
			                                <dt>Fecha entrega:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
			                            </dl>
		                        	</div>

				                    <div class="col-lg-4">
				                		<?php echo form_open(null, array('class'=>'form-horizontal')); ?>
					                    	<input type="hidden" name="tienda" value="<?php echo $this->usuario->username;?>">
					                    	<input type="hidden" name="id" value="<?php echo $detalle['id'];?>">
					                    	<div class="form-group">
					                    		<label class="col-sm-2 control-label">Estado</label>
					                    		<div class="col-sm-10">
					                    			<div class="input-group m-b">
					                    				<?php echo form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')); ?>
					                    				<input type="submit" name="submit" class="btn btn-primary pull-right btn-sm" value="Guardar">
				                    				</div>
					                    		</div>
					                    	</div>
				                    	</form>

				                    </div>
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
	                                        	<?php echo ($item['imagen']) ? '<img src="'.$item['imagen'].'" alt="'.$item['titulo'].'" width="90">' : '<div class="no-disponible">sin imagen</div>';?>
	                                        </td>
	                                        <td class="desc">
	                                            <h3>
	                                                <a href="<?php echo base_url('tienda/productos/ingresar/'.$item['id']);?>"><?php echo $item['titulo'];?> <?php echo ($item['codigo']) ? "(".$item['codigo'].")" : null; ?></a>
	                                            </h3>
	                                            <p class="small">
	                                                <?php echo $item['contenido1'];?>
	                                            </p>
	                                        </td>
	
	                                        <td> <?php echo $item['precio'];?></td>
	                                        <td width="65"><input type="text" class="form-control" placeholder="<?php echo $item['cantidad'];?>" readonly="true"></td>
	                                        <td>
	                                            <h4><?php echo $item['subtotal'];?></h4>
	                                        </td>
	                                    </tr>

                                <?php }	?> 
                                <tr>
                                    <td colspan="5">
                                       <div class="pull-right">
										  <h4>Subtotal= <?php echo $detalle['subtotal'];?></h4>
										  <h4>Descuentos = <?php echo($detalle['descuento'] > 0) ? $detalle['subtotal'] : '0.00'; ?></h4>
										  <h4>Env&iacute;o = <?php echo($detalle['envio'] > 0) ? $detalle['envio'] : '0.00'; ?></h4>
										  <h4>Total= <?php echo $detalle['subtotal'];?></h4>
                                       </div>
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