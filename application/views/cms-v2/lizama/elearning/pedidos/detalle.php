		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>eLearning Pedidos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('micuenta'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a>
                    </li>
                    <li>
                        <strong>Detalle</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
			        <a href="<?php echo base_url('cms-v2/elearning/pedidos/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar Pedido</a>
                </div>
            </div>
	     </div>
	     <div class="wrapper wrapper-content animated fadeInRight">		            
            <div class="row">
            	<div class="col-lg-12">
                    <?php if ($this->session->flashdata('resultado') == '1') : ?>
					<div class="alert alert-success alert-dismissable" role="alert">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
				    </div>
					<?php endif; ?>
					<?php if ($this->session->flashdata('resultado') == '0') : ?>
					<div class="alert alert-danger alert-dismissable" role="alert">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
                    </div>
                    <?php endif; ?>
					<?php if ($this->session->flashdata('resultado') == '2') : ?>
					<div class="alert alert-warning alert-dismissable" role="alert">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
                    </div>
                    <?php endif; ?>

                    <div class="ibox" style="margin-bottom:0;">
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
			                        </dl>
		                        </div>
	                        	<div class="col-sm-6">
			                        <dl class="dl-horizontal dl-pedidos">
		                                <dt>Fecha:</dt> <dd><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></dd>
		                            </dl>
	                        	</div>
	                        </div>
                        </div>
                    </div>
            	</div>

            <!-- Listado items -->
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
	                                        </td>
	                                        <td></td>
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
            <div class="row"></div></div>
		        <div class="col-lg-12 m-b-md">
	               <div class="ibox-title pull-left full-width">
	                    <h2 class="bg-muted p-sm">Usuarios 
							<a title="Ingresar" id="item" href="#" data-toggle="modal" data-target="#myModalUsuario" class="sepV_a btn btn-primary btn-sm pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ingresar Usuario</a>
		                    <a title="Subir CSV" href="<?php echo base_url('cms-v2/elearning/pedidos/subir_archivo/'.$detalle['id']);?>" class="sepV_a btn btn-primary btn-sm pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Subir Archivo CSV</a>
		                    <a title="Descargar Listado CSV" href="<?php echo base_url('cms-v2/elearning/pedidos/descargar_csv/'.$detalle['id']);?>" class="sepV_a btn btn-success btn-sm pull-right m-l-sm"><i class="fa fa-download"></i> Descargar CSV</a>
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
			                        <th>Última Visita</th>
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
				                        <td><?php echo (isset($usuario['ultima_visita']) && $usuario['ultima_visita'] && $usuario['ultima_visita'] != '0000-00-00 00:00:00') ? date('d-m-Y H:i', strtotime($usuario['ultima_visita'])) . ' hs' : 'Nunca';?></td>
				                        <td><?php echo $usuario['estado'];?></td>
				                        <td>
											<a href="<?php echo base_url('cms-v2/elearning/pedidos/generar_certificado/'.$detalle['id'].'/'.$usuario['id']); ?>" title="Generar Certificado" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-certificate"></i> Certificado</a>
											<a class="btn btn-primary btn-xs" data-toggle="collapse" data-target="#demo<?php echo $usuario['id'];?>"><i class="fa fa-pencil"></i> Modificar</a> 
											<a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $usuario['nombre'].' '.$usuario['apellido'];?>" data-id="<?php echo $usuario['id'];?>" data-estado="<?php echo $usuario['estado'];?>" data-target="#myModalEliminar" class="sepV_a btn btn-primary btn-xs"><i class="fa fa-minus-circle"></i> Eliminar</a>
										</td>
				                     </tr>
				                     <tr id="demo<?php echo $usuario['id'];?>" class="collapse">
					                     	<td colspan="6">
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
		               <input type="hidden" name="empresa" value="<?php echo $contacto['razon_social']; ?>">
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
