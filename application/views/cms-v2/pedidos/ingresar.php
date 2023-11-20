             <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-lg-8">
                    <h2>Compras</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/areaprivada">Home</a>
                        </li>
                        <li>
                            <a href="/cms-v2/pedidos" title="Ir a listado de compras">Compras</a>
                        </li>
                        <li class="active">
                            <strong><?php echo (empty($item['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                	<div class="ibox float-e-margins">
	                    <div class="ibox-title">
		                    <h5><?php echo (!isset($item['id'])) ? 'Crear nueva pedido' : 'Modificar pedido Nro.'.$item['id']; ?></h5>
	                    </div>

                        <?php if (validation_errors()) : ?>
						<div class="col-md-12">
							<div class="alert alert-danger" role="alert">
								<?php echo validation_errors(); ?>
							</div>
						</div>
						<?php endif; ?>
						<?php if (isset($error)) : ?>
						<div class="col-md-12">
							<div class="alert alert-danger" role="alert">
								<?php echo $error; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>
                </div>
            </div>
                
            <div class="row">

                <div class="col-md-8">

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Items de la compra</h5>
                        </div>
						
						<?php
						   if(!empty($listado)) { 
						   foreach($listado as $lista) { ?>	
						   
						<!--LISTADO DE ITEMS DEL PEDIDO -->
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table shoping-cart-table">

                                    <tbody>
                                    <tr>
                                        <td width="90">
                                            <img src="<?php echo base_url('/multimedia/511/7358/'.$lista['imagen']);?>" title="<?php echo $lista['titulo'];?>" alt="<?php echo $lista['titulo'];?>" width="90">
                                        </td>
                                        <td class="desc">
                                            <h4 class="naranja"><?=$lista['titulo']?></h4>
                                            <p>Cantidad: <?=$lista['cantidad']?></p>
                                        </td>

                                        <td width="65">
                                            <h4 class="naranja">$<?=$lista['subtotal']?>.-</h4>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
						<?php
						   }} ?>	
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Detalle de la Orden de compra <span class="naranja">Nro. <?=$item['id']?></span></h5>
                        </div>
                        <div class="ibox-content">
			                <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
			                <input type="hidden" name="id" value="<?php echo (isset($item['id'])) ? $item['id'] : null; ?>">
                            <p>Usuario: <span class="font-bold"><?=$comprador['nombre'].' '.$comprador['apellido']?></span></p>
                            <p>Fecha de ingreso: <span class="font-bold"><?=$item['fecha_alta']?></span></p>
                            <p>Subtotal: <span class="font-bold"><?=$item['subtotal']?></span></p>
                            <p>Bonificación: <span class="font-bold"><?=$item['descuento_monto']?></span></p>
                            <p>Total: <span class="font-bold"><?=$item['total']?></span></p>
                            <p>Estado: <?php if($item['estado'] == 2) { echo 'Pagado'; } elseif($item['estado'] == 7) { echo 'Bonificado'; } else { echo $item['estado'];} ?> </p>
                            <hr/>
							<?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
		</div>

<?=form_close()?>
        