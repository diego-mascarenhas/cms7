<style>
#DataTables_Table_0_length { display:none;}
.botones-filtros { position:absolute;}
</style>
<link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Servicios y Beneficios</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('cms-v2/servicios');?>">Home</a></li>
                    <li><a>Servicios y Beneficios</a></li>
                    <li class="active"><strong>Listado</strong></li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a href="<?php echo base_url('cms-v2/servicios/ingresar/'); ?>" class="btn btn-primary">Ingresar</a>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Servicios y Beneficios</h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive" style="min-height:340px;">
			                    <table class="table table-striped table-bordered table-hover dataTables-example" >
			                    <thead>
			                    <tr>
			                        <th>Imagen</th>
			                        <th>Título</th>
			                        <th>Estado</th>
			                        <th>Destacado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php 
									$CI =& get_instance();
									$CI->load->model("Servicios_model");
									$parametros['id_tipo'] = 32;
									$listado = $this->Servicios_model->getServicios($parametros);
			                   
									if($listado) { foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><img src="<?php echo base_url('/multimedia/thumbs/'.$lista['imagen']);?>" alt="<?php echo $lista['titulo'];?>" width="70"></td>
			                        <td><?php echo $lista['titulo'];?></td>
			                        <td><?php echo ($lista['estado'] == 3) ? 'Activo': 'Inactivo';?></td>
			                        <td><?php echo ($lista['destacado'] == 1) ? 'Destacada' : 'No destacada';?></td>
									<td>
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
										    <li><a href="<?php echo base_url('cms-v2/servicios/modificar/' . $lista['id_servicio']); ?>" title="Modificar" class="btn btn-sm"><i class="fa fa-pencil"></i> Modificar</a></li>
										    <li><a href="<?php echo base_url('cms-v2/servicios/duplicar/' . $lista['id_servicio']); ?>" title="Duplicar" class="btn btn-sm"><i class="fa fa-copy"></i> Duplicar</a></li>
										    <li><a href="<?php echo base_url('cms-v2/servicios/ordenar/' . $lista['id_categoria']); ?>" title="Ver contactos" class="btn btn-sm"><i class="fa fa-sort"></i> Ordenar</a></li>
										    <li><a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id_servicio'];?>" data-target="#myModalEliminarInformacion" class="sepV_a btn btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></li> 
										  </ul>
										</div>
									</td>
			                    </tr>
			                   <?php } } ?>	
	
			                    </tbody>
			                    </table>
	                        </div>

                </div>
            </div>
            </div>
        </div>

<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar contenido</h4>
            </div>
            <div class="modal-body">
            <p>&iquest;Est&aacute; seguro de querer eliminar el contenido <strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong>?</p>
                <div class="modal-footer">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/servicios/eliminar/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="estado" id="estado" value="" />
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>

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
        pageLength: 10,
        responsive: true
    });
});

  $('#myModalEliminarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>