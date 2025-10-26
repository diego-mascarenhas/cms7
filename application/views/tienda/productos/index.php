<style>
.dropdown-submenu { margin-top:-2px !important;}
.dropdown-submenu li a { color:#fff !important;padding:2px 5px !important;}
</style>
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
	                        <strong>Productos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="https://pedimosfacil.com/<?php echo $tienda['titulo']; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i> Ver Tienda</a>
                    </div>
                </div>
	        </div>
            <div class="row">

			<?php if ($this->session->flashdata('mensaje')) { ?>
			<div class="col-md-12 m-t-md">
				<?php if ($this->session->flashdata('resultado') == 'error') { ?>
				<div class="alert alert-danger alert-dismissable" role="alert">
	            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	            <?php echo $this->session->flashdata('mensaje');?></div>
				<?php } else { ?>
				<div class="alert alert-success alert-dismissable" role="alert">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<?php echo $this->session->flashdata('mensaje');?></div>
				<?php } ?>
			</div>
			<?php } ?>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de Productos</h5></div>
		                    <div class="ibox-content pull-left full-width">
		                    	<div class="col-sm-4">
					                <?php if(isset($categorias)) { ?>
					                <form name="filtrar" method="post" action="<?php echo base_url('tienda/productos/filtrar'); ?>">
							   			<?php echo form_dropdown('id_categoria', array('0' => ' Todas las categorías ') + $categorias, ($this->input->post('id_categoria')) ? $this->input->post('id_categoria') : null, array('class'=>'form-control m-b p-md width-auto pull-left')); ?> <button type="submit" class="btn btn-primary btn-sm m-l-sm"><i class="fa fa-filter"></i> Filtrar</button>
					                </form>
					                <?php } ?>
		                    	</div>
		                    	<div class="col-sm-8 text-right">
		                    		
			                    	<?php if($tienda['bruler_id'] <= 0) { ?><a href="<?php echo base_url('tienda/productos/ingresar'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Ingresar Producto</a><?php } else { ?> <a href="<?php echo base_url('tienda/productos/bruler'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Importar Productos</a><?php } ?>
									<?php if($tienda['bruler_id'] <= 0) { ?>
									<div class="dropdown" style="display:inline-block;">
									  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-refresh"></i> Actualización Masiva <span class="caret"></span>
									  </button>
									  <ul class="dropdown-menu dropdown-submenu" aria-labelledby="dropdownMenu1" style="background:#5402B2;">
									    <li><a href="<?php echo base_url('tienda/productos/importar'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Importar CSV</a></li>
									    <li><a href="<?php echo base_url('tienda/productos/exportar'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Exportar CSV</a></li>
									    <li><a href="<?php echo base_url('tienda/productos/editar'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Actualización Masiva</a></li>
									    <li><a href="<?php echo base_url('tienda/productos/actualizacion'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> Actualización Masiva (%)</a></li>
									  </ul>
									</div>
									<?php } ?>
		                    	</div>
		                    </div>
		                    
		                    <div class="ibox-content">
		                        <div class="table-responsive tabla-pedidos">
				                    <table class="table table-striped table-bordered table-hover dataTables-listado">
					                    <thead>
					                    <tr>
					                        <th>Imagen</th>
					                        <th>Categoría</th>
					                        <th>Producto</th>
					                        <th>Precio</th>
					                        <th>Destacado</th>
					                        <th>Estado</th>
					                        <?php if($tienda['bruler_id'] <= 0) { echo '<th>Acciones</th>'; }?>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td><img src="<?php echo ($lista['imagen']) ? base_url('/multimedia/thumbs/'.$lista['imagen']) : 'https://app.pedimosfacil.com/v2/assets/images/no-disponible.jpg';?>" title="" alt="" class="listados_miniatura"></td>
													<td><?php echo $lista['categoria']; ?></td>
													<td><?php echo $lista['titulo']; ?></td>
													<td><?php echo $tienda['simbolo'].' '.$lista['precio']; ?></td>
													<td><?php echo ($lista['destacado'] == 0) ? 'No' : 'S&iacute;'; ?></td>
													<td><?php echo ($lista['id_estado'] == 1) ? 'Inactivo' : 'Activo'; ?></td>
													<?php if($tienda['bruler_id'] <= 0) { ?>
													<td>
														<div class="dropdown">
														  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
														  </button>
														  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
														    <li><a href="<?php echo base_url('tienda/productos/modificar/' . $lista['id']); ?>" title="Modificar"><i class="fa fa-pencil"></i> Modificar</a></li>
														    <li><a href="<?php echo base_url('tienda/productos/galeria/' . $lista['id']); ?>" title="Galería"><i class="fa fa-camera"></i> Galería</a></li>
														    <li><a href="<?php echo base_url('tienda/opciones/listado/' . $lista['id']); ?>" title="Opciones"><i class="fa fa-list"></i> Opciones</a></li>
														    <?php if ($lista['id_estado'] == 3) { ?>
														    <li><a href="<?php echo base_url('tienda/productos/ordenar/' . $lista['id_categoria']); ?>" title="Ordenar"><i class="fa fa-sort"></i> Ordenar</a></li>
														    <?php } ?>

														    <li><a href="<?php echo base_url('tienda/productos/cambiar_estado/'.$lista['id']); ?>" title="Cambiar Estado" <?php echo($lista['id_estado'] == 3) ? '<i class="fa fa-lock"></i> Desactivar' :  '<i class="fa fa-unlock"></i> Activar';?></a></li>

														    <li><a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['titulo'];?>' data-id="<?php echo $lista['id'];?>" data-estado='<?php echo $lista['id_estado'];?>' data-target="#myModal" class="sepV_a"><i class="fa fa-minus-circle"></i> Eliminar</a></li> 
														  </ul>
														</div>
													</td>
													<?php } ?>
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
                <h4 class="modal-title">Eliminar producto</h4>
            </div>
            <div class="modal-body">
            	
                <p>&iquest;Est&aacute; seguro de querer eliminar el producto <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
            <div class="modal-footer">
                <form name="eliminar" method="post" action="<?php echo base_url('tienda/productos/eliminar'); ?>">
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
	$(document).ready(function(){
	    $('.dataTables-listado').DataTable({
		    "language": {
	            "lengthMenu": "Mostrar _MENU_ resultados por p&aacute;gina",
	            "zeroRecords": "No se encontraron resultados",
	            "infoEmpty": "No se encontraron resultados",
	            "infoFiltered": "(filtered from _MAX_ total records)",
	            "search": "Buscar: ",
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
	        pageLength: 20,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
            	{extend: 'csv', title: 'Agenda CSV'},
                {extend: 'pdf', title: 'Agenda PDF'},
                {extend: 'excel', title: 'Agenda EXCEL'}
        ]
    });
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