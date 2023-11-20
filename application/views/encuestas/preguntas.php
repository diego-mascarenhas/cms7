			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Encuestas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('/encuestas'); ?>">Eventos para Encuestas</a>
	                    </li>
	                    <li>
	                        <strong>Preguntas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('encuestas/ingresar_pregunta/'.$detalle['id']); ?>" class="btn btn-primary btn-sm">Ingresar pregunta</a>
                    </div>
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de preguntas para el evento <a href="<?php echo base_url('encuestas/modificar/'.$detalle['id']); ?>" title="Ir al evento"><?php echo $detalle['titulo']; ?></a></h5></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-example" >
					                    <thead>
					                    <tr>
					                        <th>T&iacute;tulo</th>
					                        <th>Obligatoria</th>
					                        <th>An&oacute;nima</th>
					                        <th>Para certificar</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td><?php echo $lista['titulo']; ?><br>
													<small><?php echo $lista['subtitulo']; ?></small></td>
													<td><?php echo ($lista['obligatoria'] == 1) ? 'S&iacute;' : 'No'; ?></td>
													<td><?php echo ($lista['anonima'] == 1) ? 'S&iacute;' : 'No'; ?></td>
													<td><?php echo ($lista['para_certificar'] == 1) ? 'S&iacute;' : 'No'; ?></td>
													<td><?php echo ($lista['estado'] == 2) ? 'Activo' : 'Inactivo'; ?></td>
													<td>
							                        	<a href="<?php echo base_url('encuestas/modificar_pregunta/' .$detalle['id'].'/'.$lista['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
														<a href="<?php echo base_url('encuestas/duplicar_pregunta/' .$lista['id'].'/'.$detalle['id']); ?>" title="Duplicar" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> Duplicar</a> 
							                        	<a href="<?php echo base_url('encuestas/ordenar_preguntas/' .$detalle['id']); ?>" title="Ordenar Preguntas" class="btn btn-primary btn-sm"><i class="fa fa-sort"></i> Ordenar</a> 
							                        	<a href="<?php echo base_url('encuestas/resultados/' .$detalle['id'].'/'.$lista['id']); ?>" title="Ver Resultados" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Ver Resultados</a> 
														<a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id'];?>" data-id_evento="<?php echo $detalle['id'];?>" data-target="#myModalEliminarItem" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></td>
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
	
<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar contenido</h4>
            </div>
            <div class="modal-body text-center">
            <p class="pull-left">&iquest;Est&aacute; seguro de querer eliminar el &iacute;tem </p><strong> <input type="text" name="seccion" id="seccion" value="" style="border:0; background:transparent; width:auto !important; float:left;"/></strong>
            <br>
                <div class="modal-footer m-t-xl">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('encuestas/eliminar_pregunta/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="id_evento" id="id_evento" value="" />
                    	<input type="hidden" name="estado" id="estado" value="" />
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>
<!-- Fin Modal Eliminar -->
	
<!-- Tablas -->
<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
<script>
$(document).ready(function(){
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
        pageLength: 25,
        responsive: true
    });
});

$('#myModalEliminarItem').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var id_evento = $(e.relatedTarget).data().id_evento;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#id_evento').val(id_evento);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>