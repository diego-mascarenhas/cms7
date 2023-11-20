<style>
.btn_eliminar_popup { border:0; background:none;}
</style>
<link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio web Categor&iacute;as de Blog</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/noticias">Blog</a>
                    </li>
                    <li class="active">
                        <strong>Listado de Categor&iacute;as</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a href="<?php echo base_url('cms-v2/noticias/categorias_ingresar/'); ?>" class="btn btn-primary">Ingresar</a>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Categor&iacute;as</h5><a href="<?php echo base_url('cms-v2/noticias/ordenar_categorias/'); ?>" class="btn btn-primary pull-right" style="margin-top:-8px;">Ordenar categor&iacute;as</a></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive">
			                    <table class="table table-striped table-bordered table-hover dataTables-example" >
			                    <thead>
			                    <tr>
			                        <th>T&iacute;tulo</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $lista['seccion'];?></td>
			                        <td><?php echo $lista['estado'];?></td>
			                        <td>
 				                        <a href="<?php echo base_url('cms-v2/noticias/categorias_ingresar/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Editar</a> 
				                        <a href="<?php echo base_url('cms-v2/noticias/categorias_duplicar/').$lista['id']; ?>" title="Duplicar" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> Duplicar</a>
				                        <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['seccion'];?>' data-id="<?php echo $lista['id'];?>" data-target="#myModal" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></td>
			                    </tr>
			                   <?php } ?>	
			                    </tbody>
			                    </table>
	                        </div>

                    <!-- Modal -->
                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
	                        <div class="modal-content animated">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	                                <h4 class="modal-title">Eliminar categoria de blog</h4>
	                            </div>
	                            <div class="modal-body">
	                                <p>&iquest;Est&aacute; seguro de querer eliminar la categoria <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		                            <div class="modal-footer">
			                            <form name="eliminar" method="post" action="<?php echo base_url('cms-v2/noticias/categorias_eliminar/'); ?>">
			                            	<input type="hidden" name="id" id="id" value=""/>
			                                <input type="submit" class="btn btn-primary" value="Eliminar">
			                            </form>
		                            </div>
	                            </div>
	                        </div>
                        </div>
                    <!-- Fin Modal -->
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
        pageLength: 25,
        responsive: true
    });
});
  $('#myModal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
</script>