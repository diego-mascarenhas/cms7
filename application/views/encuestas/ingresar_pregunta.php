
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
	                    <li class="active">
	                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?> Pregunta</strong>
	                    </li>
	                </ol>
	            </div>
	
	        </div>
	                       
	        <div class="row wrapper animated fadeInRight">
            	<!-- Titulo Mensajes -->
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-md">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-md">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
	        </div>

	       	<!-- Comienzo Detalle -->        
	        <div class="wrapper wrapper-content animated fadeInRight p-b-sm">
	            <div class="row">
					<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                    	<h5>Información de la pregunta para el evento <a href="<?php echo base_url('encuestas/modificar/'.$evento['id']); ?>" title="Ir al evento"><?php echo $evento['titulo']; ?></a></h5></div>
		                    </div>
		                    
		                    <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
                            <input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
                            <input type="hidden" name="id_evento" value="<?php echo (!empty($evento['id'])) ? $evento['id'] : null; ?>">
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <h2>Datos de la pregunta</h2>
	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">T&iacute;tulo</label>
				                    <div class="col-sm-3"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"></div>
		                            <label class="col-sm-2 control-label">Subt&iacute;tulo</label>
				                    <div class="col-sm-3"><input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($detalle['subtitulo'])) ? $detalle['subtitulo']: null; ?>"></div>
	                            </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">Estado</label>
				                    <div class="col-sm-3"><?php echo (isset($detalle['id'])) ? form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
				                    <label class="text-right col-sm-2 control-label">¿Es obligatoria?</label>
				                    <div class="col-sm-3">
					                    <select name="obligatoria" class="form-control m-b">
											<option value="0" <?php if((isset($detalle['obligatoria'])) && ($detalle['obligatoria'] == 0)) { echo 'selected';} ?>>No</option>
											<option value="1" <?php if((isset($detalle['obligatoria'])) && ($detalle['obligatoria'] == 1)) { echo 'selected';} ?>>S&iacute;</option>
										</select>
				                    </div>
	                            </div>
	                            
	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">¿Es para certificar?</label>
				                    <div class="col-sm-3">
					                    <select name="para_certificar" class="form-control m-b">
											<option value="0" <?php if((isset($detalle['para_certificar'])) && ($detalle['para_certificar'] == 0)) { echo 'selected';} ?>>No</option>
											<option value="1" <?php if((isset($detalle['para_certificar'])) && ($detalle['para_certificar'] == 1)) { echo 'selected';} ?>>S&iacute;</option>
										</select>
				                    </div>
				                    <label class="text-right col-sm-2 control-label">¿Es an&oacute;nima?</label>
				                    <div class="col-sm-3">
					                    <select name="anonima" class="form-control m-b">
											<option value="0" <?php if((isset($detalle['anonima'])) && ($detalle['anonima'] == 0)) { echo 'selected';} ?>>No</option>
											<option value="1" <?php if((isset($detalle['anonima'])) && ($detalle['anonima'] == 1)) { echo 'selected';} ?>>S&iacute;</option>
										</select>
				                    </div>
	                            </div>

	                            <div class="hr-line-dashed pull-left full-width"></div>
		                            
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
					                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
	                                </div>
	                            </div>

		                    </div>
		                </div>
					</div>
                </div>
	        <!-- Fin Contenido -->
			<?php echo form_close(); ?>

       <?php if(isset($detalle['id'])) { ?>
       		<div class="wrapper wrapper-content animated">
            <div class="row">
                <div class="col-lg-12">
                	<div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Listado de respuestas</h5><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $detalle['titulo']; ?>" data-id_pregunta="<?php echo $detalle['id'];?>" data-id_evento="<?php echo $evento['id'];?>" data-target="#myModalIngresarItem" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a>
	                    </div>
		            
			            <div class="ibox-content pull-left full-width">
		                    <ul class="sortable-list connectList agile-list" id="media">
								<?php 
									if (isset($listado)) 
									{ 
										foreach($listado as $lista) 
										{ 
											switch($lista['correcta'])
											{
												case 0: $correcta = 'No aplica'; break;
												case 1: $correcta = 'Correcta'; break;
												case 2: $correcta = 'Incorrecta'; break;
											}
								?>	
		                       	 <li class="lista" id="<?php echo $lista['id']; ?>"> <?php echo '- '.$lista['titulo'].' ('.$correcta.')'; ?>
		                       	 	<div class="pull-right">
		                       	 		<a title="Modificar Respuesta" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id'];?>" data-titulo="<?php echo $lista['titulo'];?>" data-subtitulo="<?php echo $lista['subtitulo'];?>" data-orden="<?php echo $lista['orden'];?>" data-id_pregunta="<?php echo $lista['id_pregunta'];?>" data-id_evento="<?php echo $evento['id'];?>" data-target="#myModalModificarItem" class="sepV_a btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Modificar</a>
		                       	 		<a title="Eliminar Respuesta" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>?" data-estado="<?php echo $lista['estado'];?>" data-id="<?php echo $lista['id'];?>" data-id_pregunta="<?php echo $detalle['id'];?>" data-id_evento="<?php echo $evento['id'];?>" data-target="#myModalEliminarItem" class="sepV_a btn btn-primary btn-xs"><i class="fa fa-minus-circle"></i> Eliminar</a>
		                       	 	</div>
		                       	 </li>
				                <?php } } else {  ?>
		                       	 <p>No se encontraron resultados</p>
				                <?php } ?>
		                    </ul>
	                	</div>
	                </div>
                </div>	
            </div>
        </div>
       <?php } ?>


    <!-- Modal Ingresar -->
    <div class="modal inmodal" id="myModalIngresarItem" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de <input type="text" name="seccion" id="seccion" value="" readonly="true" style="border:none; background:#fff;text-align:center; width:auto !important;"/></h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" action="<?php echo base_url('encuestas/ingresar_respuesta'); ?>">
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Subtítulo</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
						</div>

	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="2">Activo</option>
                            </select>
	                    </div>
	                    
	                    <div class="col-sm-6">
		                    <label class="control-label pull-left">¿Es correcta?</label>
		                    <select name="correcta" class="form-control m-b">
								<option value="0">No aplica</option>
								<option value="1">S&iacute;</option>
								<option value="2">No</option>
							</select>
		                </div>
	                    
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id_evento" id="id_evento" value="" />
			            	<input type="hidden" name="id_pregunta" id="id_pregunta" value="" />
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
	<!-- Fin Modal Ingresar -->

    <!-- Modal Modificar -->
    <div class="modal inmodal" id="myModalModificarItem" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de <input type="text" name="seccion" id="seccion" value="" readonly="true" style="border:none; background:#fff;text-align:center; width:auto !important;"/></h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" action="<?php echo base_url('encuestas/modificar_respuesta'); ?>">
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Subtítulo</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
						</div>

	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="2">Activo</option>
                            </select>
	                    </div>
	                    
	                    <div class="col-sm-6">
		                    <label class="control-label pull-left">¿Es correcta?</label>
		                    <select name="correcta" class="form-control m-b">
								<option value="0">No aplica</option>
								<option value="1">S&iacute;</option>
								<option value="2">No</option>
							</select>
		                </div>
	                    
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="id_evento" id="id_evento" value="" />
			            	<input type="hidden" name="id_pregunta" id="id_pregunta" value="" />
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
	<!-- Fin Modal Modificar -->

	<!-- Modal Eliminar -->
	<div class="modal inmodal" id="myModalEliminarItem" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-sm">
	        <div class="modal-content animated">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar respuesta</h4>
	            </div>
	            <div class="modal-body text-center">
	            <p class="pull-left">&iquest;Est&aacute; seguro de querer eliminar la respuesta</p> <strong> <input type="text" name="seccion" id="seccion" value="" style="border:0; background:transparent; width:auto !important; float:left; margin-left:5px;"/></strong>
	            <br>
	                <div class="modal-footer m-t-xl">
		                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('encuestas/eliminar_respuesta/'); ?>">
	                    	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="id_pregunta" id="id_pregunta" value="" />
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
<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

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
	
        $("#media").sortable({
        connectWith: ".connectList",
        update: function( event, ui ) {

            var media = $( "#media" ).sortable( "toArray" );
                            
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('encuestas/ordenarRespuestas/media'); ?>',
				data: {items: JSON.stringify(media)},
				success: function(data) {
					console.log(data);
				}
			});
			
        }
    }).disableSelection();

});

$('#myModalIngresarItem').on('show.bs.modal', function(e) {    
     var id_evento = $(e.relatedTarget).data().id_evento;
     var id_pregunta = $(e.relatedTarget).data().id_pregunta;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id_evento').val(id_evento);
      $(e.currentTarget).find('#id_pregunta').val(id_pregunta);
      $(e.currentTarget).find('#seccion').val(seccion);
  });

$('#myModalModificarItem').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var id_evento = $(e.relatedTarget).data().id_evento;
     var id_pregunta = $(e.relatedTarget).data().id_pregunta;
     var titulo = $(e.relatedTarget).data().titulo;
     var subtitulo = $(e.relatedTarget).data().subtitulo;
     var orden = $(e.relatedTarget).data().orden;
     var correcta = $(e.relatedTarget).data().correcta;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#id_evento').val(id_evento);
      $(e.currentTarget).find('#id_pregunta').val(id_pregunta);
      $(e.currentTarget).find('#titulo').val(titulo);
      $(e.currentTarget).find('#subtitulo').val(subtitulo);
      $(e.currentTarget).find('#orden').val(orden);
      $(e.currentTarget).find('#correcta').val(correcta);
      $(e.currentTarget).find('#estado').val(estado);
  });

$('#myModalEliminarItem').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var id_evento = $(e.relatedTarget).data().id_evento;
     var id_pregunta = $(e.relatedTarget).data().id_pregunta;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#id_evento').val(id_evento);
      $(e.currentTarget).find('#id_pregunta').val(id_pregunta);
      $(e.currentTarget).find('#estado').val(estado);
  });
</script>
						 	
