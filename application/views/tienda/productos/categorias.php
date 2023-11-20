<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url(); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda'); ?>">Tienda </a>
                    </li>
                    <li>
                        <strong>Categorías </strong>
                    </li>
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
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

            <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nueva'; ?> Categoría</h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Categoría</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="categoria" value="<?php if (isset($item['categoria'])) { echo $item['categoria']; } else { if ($this->input->post('categoria')) { echo $this->input->post('categoria'); } }?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Observaciones</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="observaciones" value="<?php if (isset($item['observaciones'])) { echo $item['observaciones']; } else { if ($this->input->post('observaciones')) { echo $this->input->post('observaciones'); } }?>">
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
                            
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
			                    <div class="col-sm-2"></div>
			                    <div class="col-sm-4 col-offset-2">
			                    	<div class="ibox-title bg-muted"><h5>Imagen Categoría</h5></div>
									<div class="ibox-content caja-imagen-tienda">
			                            <?php if(!empty($item['imagen'])) { ?>
		                            	<p>Imagen Actual</p>
		                            	<img src="<?php echo base_url('/multimedia/513/7358/'.$item['imagen']);?>" title="<?php echo $item['categoria'];?>" alt="<?php echo $item['categoria'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
		                            <?php } ?>
										<br><br>
			                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
			                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
			                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="image"></span>
				                    	</div>
									</div>
			                    </div>

	                            <label class="col-sm-2 control-label">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
	                                	<input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label>
		                            </div>
		                            <div class="radio radio-inline">
                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
		                            </div>
	                            </div>

                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>
                    
                            		                            
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>		        

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                	<div class="ibox-title"><h5>Listado de Categorías</h5></div>
                    <div class="ibox-content">
                        <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover dataTables-example" >
			                    <thead>
			                    <tr>
			                        <th>Imagen</th>
			                        <th>Categoría</th>
			                        <th>Observaciones</th>
			                        <th>Orden</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
				                    
				                <?php if (isset($listado)) { ?>
									<?php foreach($listado as $lista) { ?>	
				                   		<tr class="gradeX">
											<td><?php echo '----'; ?></td>
											<td><?php echo $lista['categoria']; ?></td>
											<td><?php echo $lista['observaciones']; ?></td>
											<td><?php echo $lista['orden']; ?></td>
											<td><?php echo ($lista['estado'] == 3) ? 'Activa' : 'Inactiva'; ?></td>
											<td>
					                        	<a href="<?php echo base_url('tienda/categorias/' . $lista['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
					                        	<a href="<?php echo base_url('tienda/ordenar_categorias/' . $lista['id']); ?>" title="Ordenar" class="btn btn-primary btn-sm"><i class="fa fa-sort"></i> Ordenar</a> 
						                        <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['categoria'];?>' data-id="<?php echo $lista['id'];?>" data-estado='<?php echo $lista['estado'];?>' data-target="#myModal" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a>
						                    </td>
				                    	</tr>
									<?php } ?>	
								<?php } ?>
			                    </tbody>
		                    </table>
                        </div>
					</div>

            	</div>
        	</div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Eliminar categoria</h4>
            </div>
            <div class="modal-body">
            	
                <p>&iquest;Est&aacute; seguro de querer eliminar la categoria <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
            <div class="modal-footer">
                <form name="eliminar" method="post" action="<?php echo base_url('tienda/eliminar_categoria'); ?>">
                	<input type="hidden" name="id" id="id" value=""/>
                	<input type="hidden" name="estado" id="estado" value=""/>
                    <input type="submit" class="btn btn-primary" value="Eliminar">
                </form>
            </div>
        </div>
      </div>
    </div>
    <!-- Fin Modal -->

	<!-- Tablas -->
	<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
	<script>

	    $('.dataTables-example').DataTable({
		    "language": {
	            "lengthMenu": "Mostrar _MENU_ resultados por p&aacute;gina",
	            "zeroRecords": "No se encontraron resultados",
	            "infoEmpty": "No se encontraron resultados",
	            "infoFiltered": "(filtered from _MAX_ total records)",
	            "search": "Buscar:",
	            "emptyTable": "No se encontraron resultados",
	            "info": "Mostrando _START_ to _END_ de _TOTAL_ resultados",
	            "infoEmpty": "Mostrando 0 to 0 of 0 resultados",
	            "infoFiltered":   "(filtrados de _MAX_ total de resultados)",
			    "loadingRecords": "Cargando...",
			    "processing": "Procesando...",
			    "paginate": {
			        "first":      "Primera",
			        "last":       "&Uacute;ltima",
			        "next":       "Siguiente",
			        "previous":   "Anterior"
			    },
			    "aria": {
			        "sortAscending":  ": ordenar ascendente",
			        "sortDescending": ": ordenar descendente"
			    }
	        },
	        pageLength: 10,
	        responsive: true
	    });

	  $('#myModal').on('show.bs.modal', function(e) {    
	     var id = $(e.relatedTarget).data().id;
	     var seccion = $(e.relatedTarget).data().seccion;
	     var estado = $(e.relatedTarget).data().estado;
	      $(e.currentTarget).find('#id').val(id);
	      $(e.currentTarget).find('#seccion').val(seccion);
	      $(e.currentTarget).find('#estado').val(estado);
	  });
	</script>		        
