		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>eLearning Pedidos</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('micuenta'); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a></li>
                    <li><strong>Modificar</strong></li>
                </ol>
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
		                                <dt>Nombre:</dt> <dd><?php echo (isset($contacto['nombre'])) ? $contacto['nombre'] : ' ------ ';?></dd>
		                                <dt>Apellido:</dt> <dd><?php echo (isset($contacto['apellido'])) ? $contacto['apellido'] : ' ------ ';?></dd>
		                                <dt>Empresa:</dt> <dd><?php echo (isset($contacto['razon_social'])) ? $contacto['razon_social'] : ' ------ ';?></dd>
		                                <dt>Email:</dt> <dd><?php echo (isset($contacto['email'])) ? '<a href="mailto:'.$contacto['email'].'" title=""> '.$contacto['email'].'</a>' : ' ------ ';?></dd>
		                                <dt>Teléfono:</dt> <dd><?php echo (isset($contacto['telefono'])) ? $contacto['telefono'] : ' ------ ';?></dd>
		                                <dt>Fecha:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
			                        </dl>
		                        </div>
	                        	<div class="col-sm-6">
							        <form name="cambiar_estado" method="post" action="<?php echo base_url('cms-v2/elearning/pedidos/modificar/'.$detalle['id']); ?>">
								        <div class="form-group full-width">
									        <label class="col-sm-1 control-label">Estado:</label>
									        <div class="col-sm-3"><?php echo (isset($detalle['estado'])) ? form_dropdown('estado', $estados_pedido, $detalle['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados_pedido, null, array('class'=>'form-control m-b')); ?></div>
								        </div>
								        <input type="hidden" name="id" value="<?php echo $detalle['id'];?>">
							            <input type="submit" class="btn btn-primary btn-sm" value="Cambiar">
						            </form>
	                        	</div>
	                        </div>
                        </div>
                    </div>
            	</div>
            </div>

            <div class="row fila-impresion">
            	<div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Items del pedido</h5>
							<a title="Agregar Item" href="#" data-toggle="modal" data-target="#myModalItem" class="btn btn-primary btn-sm pull-right" style="margin-top:-7px;"><i class="fa fa-plus-circle"></i> Agregar ítem</a>
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
	                                        </td>
	
	                                        <td width="65"><a href="<?php echo base_url('cms-v2/elearning/pedidos/eliminar_item/'.$item['id'].'/'.$detalle['id']); ?>"><i class="fa fa-trash fa-2x"></i></a></td>
	                                    </tr>

                                <?php }	?> 
                                </tbody>
                            </table>
                            <?php } else { echo 'No hay items ingresados'; } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($contacto['tipo_contacto'] == 1) { ?>
            <div class="row">
		        <div class="col-lg-12 m-b-md">
	               <div class="ibox-title pull-left full-width">
	                    <h2 class="bg-muted p-sm">Usuarios 
							<a title="Ingresar" id="item" href="#" data-toggle="modal" data-target="#myModalUsuario" class="sepV_a btn btn-primary btn-sm pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ingresar Usuario</a>
		                    <a title="Subir CSV" href="<?php echo base_url('cms-v2/elearning/pedidos/subir_archivo/'.$detalle['id']);?>" class="sepV_a btn btn-primary btn-sm pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Subir Archivo CSV</a> 		                    
	                    </h2>
	                </div>
	                <div class="ibox-content" style="border-top:0;">
	                    <div class="table-responsive">
		                    <?php if(isset($usuarios)) { ?>
		                    <table class="table table-striped table-bordered table-hover">
			                    <thead>
			                    <tr>
			                        <th>Nombre</th>
			                        <th>Apellido</th>
			                        <th>Email</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
				                   <?php foreach($usuarios as $usuario) { ?>	
				                   	 <tr class="gradeX">
				                        <td><?php echo $usuario['nombre'];?></td>
				                        <td><?php echo $usuario['apellido'];?></td>
				                        <td><?php echo $usuario['email'];?></td>
				                        <td><?php echo $usuario['estado'];?></td>
				                        <td>
											<a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#demo<?php echo $usuario['id'];?>"><i class="fa fa-pencil"></i> Modificar</a> 
											<a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $usuario['nombre'].' '.$usuario['apellido'];?>" data-id="<?php echo $usuario['id'];?>" data-estado="<?php echo $usuario['estado'];?>" data-target="#myModalEliminar" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a>
										</td>
				                     </tr>
				                     <tr id="demo<?php echo $usuario['id'];?>" class="collapse">
					                     	<td colspan="5">
										        <form name="eliminar" class="form_ingresar" method="post" action="<?php echo base_url('cms-v2/elearning/pedidos/modificar_usuario/'.$detalle['id']); ?>">
								                   <input type="hidden" name="id" value="<?php echo $usuario['id'];?>">
								                   <div class="col-sm-4 m-b-sm">
									                    <label class="control-label">Nombre</label>
									                    <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre'];?>" required>
								                   </div>
								                   <div class="col-sm-4 m-b-sm">
									                    <label class="control-label">Apellido</label>
									                    <input type="text" name="apellido" value="<?php echo $usuario['apellido'];?>" class="form-control" required>
									                </div>
								                    <div class="col-sm-4">
								                    	<label class="control-label pull-left">Estado</label>
							                            <select name="estado" id="estado" class="form-control m-b">
							                                <option value="1" <?php echo ($usuario['id_estado'] == 1) ? 'selected': null ;?>>Inactivo</option>
							                                <option value="2" <?php echo ($usuario['id_estado'] == 2) ? 'selected': null ;?>>Activo</option>
							                            </select>
								                    </div>
								                    <div class="col-sm-4 m-b-sm">
									                    <label class="control-label pull-left">Email</label>
									                    <input type="text" name="email" value="<?php echo $usuario['email'];?>" class="form-control" required>
													</div>
								                    <div class="col-sm-4 m-b-sm">
									                    <label class="control-label pull-left">Contraseña</label>
									                    <input type="password" name="password" class="form-control" value="">
									                </div>
								                    <div class="col-sm-4 m-b-sm">
										                <input type="submit" class="btn btn-primary pull-right" value="Modificar">
								                    </div>
										        </form>
					                     	</td>
				                     	</div>
					                  </tr>
				                   <?php } ?>	
			                    </tbody>
		                    </table>
				            <?php } else { ?><p>No hay usuarios ingresados.</p>	
				          <?php } ?>	
				   		</div>
				   	</div>
		       </div>
		    </div>
		  <?php } ?>	
		 </div>

 	<!-- Modal Agregar Items -->
    <div class="modal inmodal" id="myModalItem" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Agregar Cursos</h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" action="<?php echo base_url('cms-v2/elearning/pedidos/agregar_item/'); ?>">
				       <input type="hidden" name="id_contacto" value="<?php echo $contacto['id'];?>">
		               <input type="hidden" name="masivo" value="1">
	                   <div class="col-sm-12 m-b-sm">
						<?php if(!empty($cursos)) { foreach($cursos as $lista) { ?>	
							<div class="col-lg-10 col-lg-offset-1">
			                    <h4><input type="checkbox" name="items[]" value="<?php echo $lista['id_elearning'];?>" <?php if(isset($items)) { foreach($items as $cursos) { if($lista['id_elearning'] == $cursos['id_elearning']) {echo ' checked disabled';} } }?>>
								<?php echo $lista['titulo'];?></h4>
							</div>
						<?php } } else { echo 'No se encontraron resultados'; } ?>	
	                    </div>
	                    <div class="col-sm-12 m-t-sm">
	                    	<input type="hidden" name="id_pedido" value="<?php echo $detalle['id'];?>">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
			                <a class="btn btn-white" type="button" class="close" data-dismiss="modal">Cancelar</a>
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
	
 	<!-- Modal Ingresar Usuarios -->
    <div class="modal inmodal" id="myModalUsuario" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar Usuario</h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" action="<?php echo base_url('cms-v2/elearning/pedidos/ingresar_usuario/'); ?>">
		               <input type="hidden" name="id_contacto_padre" value="<?php echo $contacto['id']; ?>">
		               <input type="hidden" name="tipo_contacto" value="2">
	                   <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Nombre</label>
		                    <input type="text" name="nombre" class="form-control" value="" required>
					   </div>
	                   <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Apellido</label>
		                    <input type="text" name="apellido" value="" class="form-control" required>
		                </div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Email</label>
		                    <input type="email" name="email" value="" class="form-control" required>
						</div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Contraseña</label>
		                    <input type="password" name="password" class="form-control" value="" minlength="8" required>
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="2">Activo</option>
                            </select>
	                    </div>
	                    <div class="col-sm-12 m-t-sm">
	                    	<input type="hidden" name="url" value="<?php echo current_url();?>">
	                    	<input type="hidden" name="id_pedido" value="<?php echo $detalle['id'];?>">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar usuario</h4>
            </div>
            <div class="modal-body">
            <p>&iquest;Est&aacute; seguro de querer borrar para este pedido el usuario <strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong>?</p>
                <div class="modal-footer">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/elearning/pedidos/eliminar_usuario/'.$detalle['id']); ?>">
	                    <input type="hidden" name="id_pedido" value="<?php echo $detalle['id'];?>">
                    	<input type="hidden" name="id" id="id" value="">
                    	<input type="hidden" name="estado" id="estado" value="">
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>

<script>
  $('#myModalEliminar').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });

$('[data-toggle="tooltip"]').tooltip(); 
</script>
	