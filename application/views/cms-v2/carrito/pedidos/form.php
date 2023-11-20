       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/carrito/pedidos">Pedidos</a>
                    </li>
                    <li class="active">
                         <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight p-b-sm">
            <div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>

	     <div class="wrapper wrapper-content animated fadeInRight">		            
            <div class="row">
            	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" style="border-bottom:2px solid #e7eaec;">
	                        <h2>Pedido Nro. <?php echo $detalle['id']; ?>
	                        <small class="label-primary pull-right p-xs b-r-sm"> <?php echo $detalle['tipo_estado']; ?></small></h2>
                        </div>
                    </div>
            	</div>
            </div>

            <!-- Listado items -->
            <div class="row">
            	<div class="col-lg-8">
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
	                                        	<?php if ($item['imagen']) { ?> 
	                                        	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" alt="<?php echo $item['titulo'];?>" width="90">
	                                        	<?php } else { ?> 
	                                        	Sin imagen
	                                        	<?php } ?> 
	                                        </td>
	                                        <td class="desc">
	                                            <h3>
	                                                <a href="<?php echo base_url('cms-v2/carrito/productos/modificar/es/'.$item['id_producto']);?>"><?php echo $item['titulo'];?> <?php echo ($item['codigo']) ? "(".$item['codigo'].")" : null; ?></a>
	                                            </h3>
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
										  <h4>Subtotal = <?php echo $detalle['subtotal'];?></h4>
										  <h4>Descuentos = <?php echo($detalle['descuento'] > 0) ? $detalle['subtotal'] : '0.00'; ?></h4>
										  <h4>Env&iacute;o = <?php echo($detalle['envio'] > 0) ? $detalle['envio'] : '0.00'; ?></h4>
										  <h4>Total = <?php echo $detalle['subtotal'];?></h4>
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

                <!-- Detalle -->
            	<div class="col-lg-4">
                    <div class="ibox">
                        <div class="ibox-content">
                    		<?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                        	<input type="hidden" name="id" value="<?php echo $detalle['id'];?>">
	                        	<div class="input-group m-b">
	                        		<label>Estado:</label>
	                        		<?php echo form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')); ?>
	                        		<span class="input-group-btn"><button type="submit" class="btn btn-primary" style="margin-top:21px;">Cambiar</button> </span>
	                        	</div>
                        	</form>
                            <div>
                                <p><strong>Nombre:</strong> <?php echo ($detalle['contacto']) ? $detalle['contacto'] : ' ------ ';?><br>
                                <strong>Celular:</strong> <?php echo ($detalle['celular']) ? $detalle['celular'] : ' ------ ';?><br>
                                <strong>Email:</strong> <?php echo ($detalle['email']) ? '<a href="mailto:'.$detalle['email'].'" title=""> '.$detalle['email'].'</a>' : ' ------ ';?><br>
                                <strong>Fecha ingreso:</strong> <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></p>
                            </div>
                        </div>
                    </div>
            	</div>
            </div>
            <!-- Fin Detalle -->
     </div>        
     </div>