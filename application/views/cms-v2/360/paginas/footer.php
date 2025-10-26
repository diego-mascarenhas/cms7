<?php
	$CI =& get_instance();
	$CI->load->model("cms-v2/servicios/Servicios_model");
	$parametros['orden'] = 0;
	$parametros['idioma'] = 'es';
	$servicios = $CI->Servicios_model->getServicios($parametros);
?>
								

<!-- Modal Ingresar Informacion -->
    <div class="modal inmodal" id="myModalIngresarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-body p-xs pull-left full-width">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				    <?php if($detalle['id_con_secciones'] == 777) { ?>
		            <h4 class="modal-title">Ingresar usuario</h4>
			        <form name="ingresar" class="form_ingresar" method="post" action="/administracion/contactos/ingresar">
		            	<input type="hidden" name="id_empresa" value="10578">
	                    <input type="hidden" name="timezone" value="<?php echo $this->usuario->timezone; ?>">
	                    <input type="hidden" name="idioma" value="<?php echo $this->usuario->idioma; ?>">
	                    <input type="hidden" name="apellido" value="NULL">
	                    <input type="hidden" name="telefono" value="NULL">
	                    <input type="hidden" name="celular" value="NULL">
	                    <input type="hidden" name="sexo" value="M">
	                    <input type="hidden" name="email" value="<?php echo $this->usuario->email; ?>">
	                    <input type="hidden" name="area_privada" value="5">
	                    <input type="hidden" name="estado" value="2">

	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Dirección</label>
		                    <div class="input-group col-sm-9"><input type="text" name="nombre" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Ubicación del proyecto, por ejemplo Mendoza 331." title=""> <i class="fa fa-question"></i></button></span></div>
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Proyecto</label>
		                    <div class="input-group col-sm-9">
			                    <select name="username" class="form-control m-b-md">
			                    	<?php foreach($servicios as $servicio) { ?>
			                    		<option value="<?php echo url_title(str_replace('/','',$servicio['titulo']), '', TRUE);?>"><?php echo str_replace('/',' ',$servicio['titulo']);?></option>
			                    	<?php } ?>
			                    </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Proyecto al que se asignará el usuario." title=""> <i class="fa fa-question"></i></button></span></div>
						</div>
						
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Contraseña</label>
		                    <div class="input-group col-sm-9"><input type="password" class="form-control" name="password"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contraseña." title=""> <i class="fa fa-question"></i></button></span></div>
						</div>


                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>

				    <?php } else { ?>
		            <h4 class="modal-title">Ingresar contenido de Nosotros</h4>
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            	<input type="hidden" name="idioma" id="idioma" value="">
	                    <input type="hidden" name="id_tipo" value="711">
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="1113">
	                    <input type="hidden" name="id_imagen_tipo" value="13">
	                	<input type="hidden" name="medidas" value="1300x620">
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Título</label>
		                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
							<div class="col-sm-9 no-padding"><div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""></textarea></div></div>
						</div>

                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 1300x620 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>

                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
				        <?php } ?>

	                    <div class="col-sm-12 m-t-sm">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>


<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Eliminar contenido</h4>
                <p class="text-center">&iquest;Está seguro de querer eliminar el contenido <em> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></em></p>
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

<!-- Mainly scripts -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>

<script>
  $('#myModalIngresarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var contenido = $(e.relatedTarget).data().contenido;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
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

$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 140,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']],
          ['insert', ['grid']],
          ['misc', ['codeview']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>