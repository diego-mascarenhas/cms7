<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/productos'); ?>">Productos </a>
                    </li>
                    <li>
                        <strong>Categorías </strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
                    <a href="<?php echo base_url('tienda/categorias/ingresar'); ?>" class="btn btn-primary btn-sm">Ingresar Categoría</a>
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
		                    <table class="table table-striped table-bordered table-hover dataTables-listado" >
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
											<td><img src="<?php echo ($lista['imagen']) ? base_url('/multimedia/thumbs/'.$lista['imagen']) : 'https://app.pedimosfacil.com/v2/assets/images/no-disponible.jpg';?>" title="" alt="" class="listados_miniatura"></td>
											<td><?php echo $lista['categoria']; ?></td>
											<td><?php echo $lista['observaciones']; ?></td>
											<td><?php echo $lista['orden']; ?></td>
											<td><?php echo ($lista['estado'] == 3) ? 'Activa' : 'Inactiva'; ?></td>
											<td>
					                        	<a href="<?php echo base_url('tienda/categorias/modificar/' . $lista['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
					                        	<a href="<?php echo base_url('tienda/categorias/ordenar/' . $lista['id']); ?>" title="Ordenar" class="btn btn-primary btn-sm"><i class="fa fa-sort"></i> Ordenar</a> 
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
                <form name="eliminar" method="post" action="<?php echo base_url('tienda/categorias/eliminar'); ?>">
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

	    $('.dataTables-listado').DataTable({
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
	        responsive: true,
            dom: '<"html5buttons">lTfgitp',
            buttons: [
            	{extend: 'csv', title: 'Agenda CSV'},
                {extend: 'pdf', title: 'Agenda PDF'},
                {extend: 'excel', title: 'Agenda EXCEL'}
        ]
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
