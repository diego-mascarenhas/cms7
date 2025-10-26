<style>
.dropdown-menu>li>a { padding:3px 10px; margin: 4px 0; line-height: 20px;}
.ocultar { visibility:visible !important;}

@media(max-width:1200px) {
.dropdown-menu>li>a { padding:3px 10px; margin: 4px 0;}
.ocultar { display:none; visibility: hidden !important;}
.table td { width:33% !important;}
}
@media(max-width:680px) {
.dropdown-menu { right:0 !important; left:auto;}
}
</style>
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
	                <h2>Encuestas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Eventos para Encuestas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-0 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('encuestas/ingresar/'); ?>" class="btn btn-primary btn-sm m-t-sm">Ingresar evento</a>
                    </div>
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de eventos</h5></div>
		                    <div class="ibox-content">
				                    <table class="table table-striped table-bordered table-hover dataTables-listados">
					                    <thead>
					                    <tr>
					                        <th class="titulo-lista">Nombre Evento</th>
					                        <th class="ocultar">Código</th>
					                        <th class="ocultar">Fecha de vencimiento</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td class="titulo-lista"><?php echo $lista['titulo']; ?></td>
													<td class="ocultar"><?php echo $lista['codigo']; ?></td>
													<td class="ocultar"><?php echo $lista['fecha_vencimiento']; ?></td>
													<td><?php echo ($lista['estado'] == 2) ? 'Activa' : 'Inactiva'; ?></td>
													<td>
														<div class="dropdown">
														  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
														  </button>
														  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
														    <li><a href="<?php echo base_url('encuestas/modificar/' . $lista['id']); ?>" title="Modificar" class="btn btn-sm"><i class="fa fa-pencil"></i> Modificar</a></li>
														    <li><a href="<?php echo base_url('encuestas/duplicar/' . $lista['id']); ?>" title="Duplicar" class="btn btn-sm"><i class="fa fa-copy"></i> Duplicar</a></li>
														    <li><a href="<?php echo base_url('encuestas/ordenar/' . $lista['id']); ?>" title="Ordenar" class="btn btn-sm"><i class="fa fa-sort"></i> Ordenar</a></li>
														    <li><a href="<?php echo base_url('encuestas/contactos/' . $lista['id']); ?>" title="Ver contactos" class="btn btn-sm"><i class="fa fa-eye"></i> Ver Contactos</a></li>
														    <li><a href="<?php echo base_url('encuestas/subir_archivo/' . $lista['id']); ?>" title="Suvir CVS con contactos" class="btn btn-sm"><i class="fa fa-upload"></i> Subir Contactos</a></li>
														    <li><a href="<?php echo base_url('encuestas/preguntas/' . $lista['id']); ?>" title="Preguntas/Encuenta" class="btn btn-sm"><i class="fa fa-bookmark"></i> Preguntas</a></li> 
														    <li><a href="<?php echo base_url('encuestas/resultados-generales/' . $lista['id']); ?>" title="Preguntas/Resultados" class="btn btn-sm"><i class="fa fa-download"></i> Resultados Preguntas</a></li> 
														    <li><a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id'];?>" data-target="#myModalEliminarItem" class="sepV_a btn btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></li> 
														  </ul>
														</div>
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
	
<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar evento</h4>
            </div>
            <div class="modal-body text-center">
            <p class="pull-left">&iquest;Est&aacute; seguro de querer eliminar el evento </p><strong> <input type="text" name="seccion" id="seccion" value="" style="border:0; background:transparent; width:auto !important; float:left; margin-left:5px;"/></strong>
            <br>
                <div class="modal-footer m-t-xl">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('encuestas/eliminar/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
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
    $('.dataTables-listados').DataTable({
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
        responsive: false
    });
});

$('#myModalEliminarItem').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>