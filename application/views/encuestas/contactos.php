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
	                        <strong>Contactos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
<!--                         <a href="<?php echo base_url('encuestas/ingresar_contacto/'); ?>" class="btn btn-primary btn-sm">Ingresar contacto</a> -->
                    </div>
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de contactos para el evento <a href="<?php echo base_url('encuestas/modificar/'.$detalle['id']); ?>" title="Ir al evento"><?php echo $detalle['titulo']; ?></a></h5></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-example" >
					                    <thead>
					                    <tr>
					                        <th>Nombre</th>
					                        <th>Apellido</th>
					                        <th>Email</th>
					                        <?php if(isset($detalle['id_elearning'])) { ?>
					                        <th>Certific&oacute;</th>
					                        <?php } else { ?>
					                        <th>Estado</th>
						                    <th>Acciones</th>
					                        <?php } ?>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td><?php echo $lista['nombre']; ?></td>
													<td><?php echo $lista['apellido']; ?></td>
													<td><?php echo $lista['email']; ?></td>
													<?php if(isset($detalle['id_elearning'])) { ?>
													<td><?php echo ($lista['certificado'] == 1) ? 'S&iacute;' : 'No'; ?></td>
													<?php } else { ?>
													<td><?php echo ($lista['estado'] == 1) ? 'Inactivo' : 'Activo'; ?></td>
													<td>
														<?php if($lista['certificado'] < 1) { ?><a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['nombre'].' '.$lista['apellido'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id'];?>" data-evento="<?php echo $detalle['id'];?>" data-target="#myModalEliminarItem" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></td>
														<?php } else { ?>
														El usuario realiz¨® la certificaci¨®n.
														<?php } ?>
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
	
<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar contenido</h4>
            </div>
            <div class="modal-body text-center">
            <p class="pull-left">&iquest;Est&aacute; seguro de querer eliminar el contacto </p><strong> <input type="text" name="seccion" id="seccion" value="" style="border:0; background:transparent; width:50% !important; float:left;"/></strong>
            <br>
                <div class="modal-footer m-t-xl">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('encuestas/eliminar_contacto/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="id_evento" id="evento" value="" />
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
        responsive: true
    });
});

$('#myModalEliminarItem').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var evento = $(e.relatedTarget).data().evento;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#evento').val(evento);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>