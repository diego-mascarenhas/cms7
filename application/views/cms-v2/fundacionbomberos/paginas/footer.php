<!-- Modal Ingresar Informacion -->
    <div class="modal inmodal" id="myModalIngresarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de <input type="text" name="seccion" id="seccion" value="" readonly="true" style="border:none; background:#fff;text-align:center; width:auto !important;"/></h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-6">
		                    <label class="control-label pull-left">Categoría</label>
                            <select name="id_tipo" class="form-control m-b">
                                <?php if($categorias) { foreach($categorias as $categoria) { ?>
                                <option value="<?php echo $categoria['id'];?>"><?php echo $categoria['seccion'];?></option>
                                <?php } } ?>
                            </select>
		                </div>

	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                
	                    <div class="col-sm-6">
		                    <label class="control-label pull-left">Tamaño Imagen</label>
                            <select name="id_imagen_tipo" class="form-control m-b">
                            <?php 
		                		switch($detalle['id_con_secciones'])
		                		{
		                			case 271 : echo '<option value="13" selected>300x240 (servicios)</option>'; break;
		                			case 273 : echo '<option value="13" selected>300x300 (clientes)</option>'; break;
		                			case 261 : echo '<option value="13" selected>120x120 (iconos)</option>'; break;
		                			case 263 : echo '<option value="13" selected>250x250 (equipo)</option>'; break;
		                		}
		                	?>
                            </select>
						</div>

	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
								<div class="ibox-content no-padding">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>

	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
		                
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen">
	                    	</div>
						</div>
						
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="idioma" id="idioma" value="" />
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
                            <?php 
		                		switch($detalle['id_con_secciones'])
		                		{
		                			case 271 : echo '<input type="hidden" name="medidas" value="300x250" />'; break;
		                			case 273 : echo '<input type="hidden" name="medidas" value="300x240" />'; break;
		                			case 261 : echo '<input type="hidden" name="medidas" value="120x120" />'; break;
		                			case 263 : echo '<input type="hidden" name="medidas" value="250x250" />'; break;
		                		}
		                	?>
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
<!-- Fin Modal Ingresar -->

<!-- Modal Slide -->
    <div class="modal inmodal" id="myModalSlide" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de <input type="text" name="seccion" id="seccion" value="" readonly="true" style="border:none; background:#fff;text-align:center; width:auto !important;"/></h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
								<div class="ibox-content no-padding">
								    <textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
						</div>

	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Texto</label>
                            <select name="sin_texto" class="form-control m-b">
                                <option value="1">Publicar</option>
                                <option value="0">No publicar</option>
                            </select>
	                    </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen">
	                    	</div>
	                    	<small class="font-italic">Recomendado 1920 x 600 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site.</small>
	                    </div>

	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="8" />
			            	<input type="hidden" name="id_imagen_tipo" id="tipo" value="18" />
			            	<input type="hidden" name="idioma" id="idioma" value="" />
			            	<input type="hidden" name="medidas" value="1920x600" />
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
<!-- Fin Modal Slide -->

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
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/paginas/eliminar_informacion/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="estado" id="estado" value="" />
                    	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>
<!-- Fin Modal Eliminar -->

<!-- Mainly scripts -->
<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
	
        $("#media").sortable({
        connectWith: ".connectList",
        update: function( event, ui ) {

            var media = $( "#media" ).sortable( "toArray" );
                            
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('cms-v2/paginas/ordenarCategorias/media'); ?>',
				data: {items: JSON.stringify(media)},
				success: function(data) {
					console.log(data);
				}
			});
			
        }
    }).disableSelection();

    });
</script>

<script>
  $('#myModalIngresarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var contenido = $(e.relatedTarget).data().contenido;
     var titulo = $(e.relatedTarget).data().titulo;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#titulo').val(titulo);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#contenido').val(contenido);
      $(e.currentTarget).find('#idioma').val(idioma);
      
      $('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 140,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['paragraph']],
          ['insert', ['link']]
        ]
        });
  });

  $('#myModalSlide').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#idioma').val(idioma);
      $('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 140,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
        });
  });

  $('#myModalEliminarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
  });
</script>