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
			            <input type="hidden" name="id_tipo" id="id_tipo" value="">
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado </label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                

                        <?php  if($detalle['id_con_secciones'] > 295) { if($detalle['id_con_secciones'] != 299)  { if($detalle['id_con_secciones'] != 303) {?>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen encabezado</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen2">
	                    	</div>
	                    	<small>Medidas: 1540x230 píxeles</small>
						</div>
	                    <div class="col-sm-6">
                            <label class="pull-left control-label">Galería</label>
                               <?php echo form_dropdown('media_proyecto', $media_proyectos, null, 'class="form-control m-b"'); ?>
	                    </div>
                        <?php  } } } ?>

	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
								<div class="ibox-content no-padding">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>

	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto Adicional</label>
								<div class="ibox-content no-padding">
								    <textarea class="form-control summernote2" name="contenido2" rows="4"  value=""></textarea></div>
						</div>

                        <?php if($detalle['id_con_secciones'] != 295) { if($detalle['id_con_secciones'] != 303 ) {?>
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
                        <?php } } ?>
		                
		                    	
		                <?php echo($detalle['id_con_secciones'] == 291) ? '<div class="col-sm-6 m-b-sm">': '<div class="col-sm-12 m-b-sm">' ?> 
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <?php 
			                //destinos
                            if($detalle['id_con_secciones'] > 306 && $detalle['id_con_secciones'] < 318) 
                            { 
                            	echo '<input type="hidden" name="medidas" value="500x340"><input type="hidden" name="id_imagen_tipo" value="13"><input type="hidden" name="medidas2" value="1540x230"><input type="hidden" name="id_imagen_tipo2" value="12">';
                            }
	                    	else
	                    	{
		                    	switch($detalle['id_con_secciones']) 
		                    	{ 
			                    	//home
			                    	case 291: 
			                    	echo '<div class="col-sm-6"><label class="control-label pull-left">Tamaño Imagen</label>
			                    	<select name="medidas" class="form-control m-b">
				                    	<option value="1680x700">1680x700 (banners 100%)</option>
				                    	<option value="140x140">140x140 (testimonios)</option>
				                    	<option value="120x55">120x55 (commitments)</option>
			                    	</select>
			                    	</div>
			                    	<input type="hidden" name="id_imagen_tipo" value="13"><input type="hidden" name="medidas2" value="1540x230"><input type="hidden" name="id_imagen_tipo2" value="12">';break;
			                    	
			                    	//experiencias
			                    	case 295: 
			                    	echo '<input type="hidden" name="medidas" value="465x380"><input type="hidden" name="id_imagen_tipo" value="13">';break;

			                    	//nosotros
			                    	case 299: 
			                    	echo '<input type="hidden" name="medidas" value="465x380"><input type="hidden" name="id_imagen_tipo" value="13">';break;
			                    	
			                    	//posts
			                    	case 303: 
		                            echo '<input type="hidden" name="medidas" value="840x570"><input type="hidden" name="id_imagen_tipo" value="13"><input type="hidden" name="medidas2" value="1540x230"><input type="hidden" name="id_imagen_tipo2" value="12">';break;
		                    	}
		                    }
	                    ?>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen">
	                    	</div>
						</div>
						
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>
<!-- Fin Modal Ingresar -->

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
                    	<input type="hidden" name="id" id="id" value="">
                    	<input type="hidden" name="estado" id="estado" value="">
                    	<input type="hidden" name="id_contenido" id="id_contenido" value="">
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