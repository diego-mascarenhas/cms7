<style>
.btn_eliminar_popup { border:0; background:none;}
</style>
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/carrito/categorias">Categor&iacute;as</a>
                    </li>
                    <li class="active">
                        <strong>Listado</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a href="<?php echo base_url('cms-v2/carrito/categorias/ingresar/'); ?>" class="btn btn-primary">Ingresar</a>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                	<div class="ibox-title"><h5>Listado de Categor&iacute;as</h5></div>
                    <div class="ibox-content pull-left full-width">
                    	<div class="col-sm-4">
			                <form name="filtrar" method="post" action="<?php echo base_url('cms-v2/carrito/categorias/filtrar'); ?>" >
					   			<?php echo form_dropdown('padre', array('0' => ' Todas las categorías ') + $categorias, ($this->input->post('padre')) ? $this->input->post('padre') : null, array('class'=>'form-control p-sm pull-left', 'style'=>'width:200px')); ?> <button type="submit" class="btn btn-primary btn-sm m-l-sm"><i class="fa fa-filter"></i> Filtrar</button>
			                </form>
                    	</div>
                    </div>

                    <div class="ibox-content">
                        <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover dataTables-example">
		                    <thead>
			                    <tr>
			                        <th>Nombre</th>
			                        <th>Categor&iacute;a principal</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                 <?php 
		                   if($listado) 
		                   { 
			                    foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $lista['categoria'];?></td>
			                        <td><?php echo ($lista['padre_nombre']) ? $lista['padre_nombre'] : '-----';?></td>
			                        <td <?php if($lista['estado'] == 1) { echo ' class="bg-danger"'; }?>><?php echo ($lista['estado'] == 1) ? 'Inactivo' : 'Activo';?></td>
			                        <td>
				                        <a href="<?php echo base_url('cms-v2/carrito/categorias/modificar/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Editar</a> 
				                        <a href="<?php echo base_url('cms-v2/carrito/categorias/duplicar/').$lista['id']; ?>" title="Duplicar" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> Duplicar</a> 
				                        <a href="<?php echo base_url('cms-v2/carrito/categorias/ordenar/').$lista['padre']; ?>" title="Ordenar" class="btn btn-primary btn-sm"><i class="fa fa-sort"></i> Ordenar</a> 
				                        <a title="Cambiar publicaci&oacute;n" id="item" href="#" data-toggle="modal" data-estado='<?php echo $lista['estado'];?>' data-seccion='<?php echo $lista['categoria'];?>?' data-id="<?php echo $lista['id'];?>" data-target="#myModalPublicacion" <?php echo ($lista['estado'] == 2) ? 'class="btn btn-sm btn-primary btn-sm"><i class="fa fa-download"></i> Dejar de publicar' : 'class="btn btn-sm btn-primary btn-sm"><i class="fa fa-upload"></i> Publicar secci&oacute;n &nbsp;';?></a>				                    				                        <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['categoria'];?>' data-id="<?php echo $lista['id'];?>" data-estado="<?php echo $lista['estado'];?>" data-target="#myModal" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></td>
			                    </tr>
		                 <?php }  }?>	

		                    </tbody>
		                    </table>
                        </div>


                    <!-- Modal -->
                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
	                        <div class="modal-content animated">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	                                <h4 class="modal-title">Eliminar categor&iacute;a</h4>
	                            </div>
	                            <div class="modal-body">
	                                <p>&iquest;Est&aacute; seguro de querer eliminar la categor&iacute;a <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
	                                <p class="font-bold font-italic">IMPORTANTE: Tenga en cuenta que al eliminar la categor&iacute;a, se eliminar&aacute;n las subcategor&iacute;as de la misma y los productos en la categor&iacute;a principal y subcategor&iacute;as asociadas.</p>
	                                <div class="modal-footer">
			                            <form name="eliminar" method="post" action="<?php echo base_url('cms-v2/carrito/categorias/eliminar/'); ?>">
			                            	<input type="hidden" name="id" id="id" value=""/>
			                            	<input type="hidden" name="estado" id="estado" value=""/>
			                                <input type="submit" class="btn btn-primary" value="Eliminar">
			                            </form>
			                        </div>
	                            </div>
	                        </div>
                        </div>
                    </div>
                    <!-- Fin Modal -->

                    <!-- Modal 2 -->
                    <div class="modal inmodal" id="myModalPublicacion" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
	                        <div class="modal-content animated">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	                                <h4 class="modal-title">Cambiar publicaci&oacute;n</h4>
	                            </div>
	                            <div class="modal-body">
	                                <p>&iquest;Est&aacute; seguro de cambiar publicaci&oacute;n de<strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong></p>
	                                <p class="font-bold font-italic">IMPORTANTE: Tenga en cuenta que al cambiar el estado de la publicaci&oacute;n de la categor&iacute;a, se cambiar&aacute; el estado de las subcategor&iacute;as de la misma y de los productos en la categor&iacute;a principal y subcategor&iacute;as asociadas.</p>
		                            <div class="modal-footer">
			                            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/carrito/categorias/publicar/'); ?>">
			                            	<input type="hidden" name="id" id="id" value="" />
			                            	<input type="hidden" name="estado" id="estado" value="" />
			                                <input type="submit" class="btn btn-primary" value="Cambiar publicaci&oacute;n">
			                            </form>
		                            </div>
		                       </div>
	                        </div>
                        </div>
                    </div>
                    <!-- Fin Modal 2-->

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
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });

  $('#myModalPublicacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>


