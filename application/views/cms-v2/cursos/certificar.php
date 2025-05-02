<style>
.btn-file>input { position: absolute;top: 0;right: 0;margin: 0;opacity: 0;filter: alpha(opacity=0);font-size: 23px;height: 100%;width: 100%;direction: ltr;cursor: pointer;}
.skin-1 .ibox-content:last-child {border-style: solid solid solid solid;}
.ibox-title,.ibox-content {border-width: 1px;}
.b_bottom { border-bottom: 1px solid #e7eaec }
.note-editor.note-frame { border: none;}
.btn_eliminar_popup { border:0; background:none;}
.m_t_20 { margin-top:20px !important;}
.m_t_b_5 { margin:5px 0px !important;}
.p_b_25 { padding-bottom:25px !important;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Cursos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/cursos/');?>">Cursos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id'])) ? 'Crear nueva pregunta de certificaci&oacute;n' : 'Modificar pregunta de certificaci&oacute;n'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id_contenido'] : null; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir pregunta de certificaci&oacute;n</h5>
					</div>
                </div>
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>
        </div>
        
       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top:0 !important;">
            <div class="row">

			<div class="col-lg-12 m_b_25">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información de la pregunta</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    
                    <div class="ibox-content" style="float:left; width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-6"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-1">
			                    <?php echo (isset($item['id'])) ? form_dropdown('estado', $estados, $pregunta['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                    <label class="text-right col-lg-2 control-label">Orden</label>
		                    <div class="col-sm-1"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($pregunta['orden'])) ? $pregunta['orden']: null; ?>"></div>
			            </div>
			            <br><br><br>
                    </div>
                </div>
            </div>
		<?=form_close()?>
		<!-- Fin Contenido -->

          <!-- Informacion -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Respuestas</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                <div class="ibox-content">
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">1</label>
	                    <div class="col-sm-5"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($pregunta['contenido1'])) ? $item['contenido1']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">2</label>
	                    <div class="col-sm-5"><input type="text" name="contenido2" class="form-control" value="<?php echo (isset($pregunta['contenido2'])) ? $item['contenido2']: null; ?>"></div>
                 	</div>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">3</label>
	                    <div class="col-sm-5"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($pregunta['contenido3'])) ? $pregunta['contenido3']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">4</label>
	                    <div class="col-sm-5"><input type="text" name="contenido2" class="form-control" value="<?php echo (isset($pregunta['contenido4'])) ? $pregunta['contenido4']: null; ?>"></div>
                 	</div>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">5</label>
	                    <div class="col-sm-5"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($pregunta['contenido5'])) ? $pregunta['contenido5']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">6</label>
	                    <div class="col-sm-5"><input type="text" name="contenido2" class="form-control" value="<?php echo (isset($pregunta['contenido6'])) ? $pregunta['contenido6']: null; ?>"></div>
                 	</div>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">7</label>
	                    <div class="col-sm-5"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($pregunta['contenido7'])) ? $pregunta['contenido7']: null; ?>"></div>
                 	</div>
	                    
		   		</div>
		   	</div>
       </div>
       

		<br><br></div></div>
        <!-- Fin Tener en cuenta -->
<!-- Fin Contenido -->

          <!-- Footer -->
            <div class="footer">
	            <div class="pull-right">
	                <strong>CMS+</strong> ☰
	            </div>
	            <div>
	                <strong>Copyright</strong> revision alpha &copy;2002-2019
	            </div>
	        </div>
        </div>

        <div id="right-sidebar" class="animated">
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
	            <div class="sidebar-container" style="overflow: hidden; width: auto; height: 100%;">

	                <ul class="nav nav-tabs navs-3">
	                    <li class="active">
	                    	<a data-toggle="tab" href="#tab-1">Notas</a>
	                    </li>
	                    <li>
	                    	<a data-toggle="tab" href="#tab-2">Tareas</a>
	                    </li>
	                    <li class="">
	                    	<a data-toggle="tab" href="#tab-3"><i class="fa fa-gear"></i></a>
	                    </li>
	                </ul>
	
	                <div class="tab-content">
						<div id="tab-1" class="tab-pane active">
							<div class="sidebar-title">
	                            <h3> <i class="fa fa-comments-o"></i> Ultimas Notas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($notas = $this->session->userdata('notas')); ?> <?php echo (count($notas) == 1) ? 'nueva nota' : 'nuevas notas'; ?>.</small>
	                        </div>
	                        <div>
		                        <?php if ($notas) { ?>
			                        <?php foreach ($notas as $nota) { ?>
		                            <div class="sidebar-message">
		                                <a href="<?php echo base_url($nota['uri'] . '/detalle/' . $nota['id_referencia']); ?>">
		                                    <div class="pull-left text-center">
		                                        <img alt="image" class="img-circle message-avatar" src="<?php echo base_url('multimedia/avatars/' . $nota['avatar']); ?>">
	<!--
		                                        <div class="m-t-xs">
		                                            <i class="fa fa-star text-warning"></i>
		                                            <i class="fa fa-star text-warning"></i>
		                                        </div>
	-->
		                                    </div>
		                                    <div class="media-body">
												<?php echo $nota['titulo']; ?>
		                                        <br>
		                                        <small class="text-muted"><?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
		                                    </div>
		                                </a>
		                            </div>
	                            	<?php } ?>
	                            <?php } ?>
	                        </div>
	                    </div>
	
	                    <div id="tab-2" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3> <i class="fa fa-cube"></i> Ultimas Tareas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($tareas = $this->session->userdata('tareas')); ?> tareas pendientes.<!--  10 no sin terminar. --></small>
	                        </div>
	                        <?php if ($tareas) { ?>
		                        <?php foreach ($tareas as $tarea) { ?>
		                        <ul class="sidebar-list">
		                            <li>
		                                <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>">
			                                <span class="label label-<?php echo $tarea['estado_ui_class']; ?> pull-right"><?php echo $tarea['estado']; ?></span>
	<!-- 	                                    <div class="small pull-right m-t-xs">9 hours ago</div> -->
		                                    <h4><?php echo $tarea['titulo']; ?></h4>
		                                    <?php echo $tarea['descripcion']; ?>
		
	<!-- 	                                    <div class="small">Completion with: 22%</div> -->
	<!--
		                                    <div class="progress progress-mini">
		                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
		                                    </div>
	-->
		                                    <div class="small text-muted m-t-xs"><?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
		                                </a>
		                            </li>
		                        </ul>
								<?php } ?>
	                        <?php } ?>
	                    </div>
	
	                    <div id="tab-3" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3><i class="fa fa-gears"></i> Configuración</h3>
	                            <small><i class="fa fa-tim"></i> Infórmanos como estas como para atender estas tareas.</small>
	                        </div>
	                    
	                    	<div class="setings-item">
							<span>Soporte Técnico</span>
	                            <div class="switch">
	                                <div class="onoffswitch">
	                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
	                                    <label class="onoffswitch-label" for="example4">
	                                        <span class="onoffswitch-inner"></span>
	                                        <span class="onoffswitch-switch"></span>
	                                    </label>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="setings-item">
		                        
	                        <div class="sidebar-content">
	                            <h4>¿Y ahora?</h4>
	                            <div class="small">
	                                Por favor informanos en que estado estas para poder hacer sustentable la plataforma ;-)
	                            </div>
	                        </div>
	
	                    </div>
	                </div>
            	</div>
				<div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 7px; position: absolute; top: 2px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 675.0260412A333D6px; background-position: initial initial; background-repeat: initial initial;"></div>
				<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.4; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div>
            </div>
		</div>
    </div>

<!-- Modal Eliminar la imagen-->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar la imagen</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar la imagen <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		        	<form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminarmedia/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" value="<?php echo $item['id']; ?>">
		                <input type="submit" class="btn btn-primary" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal Eliminar la imagen-->

<!-- Modal Ingresar tener en cuenta -->
<div class="modal inmodal" id="myModaldos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar ítem tener en cuenta</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/ingresar_cuenta/'); ?>">
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" class="form-control">
	                   </div>
	                   <div class="col-sm-10">
	                    	<label class="control-label pull-left">Item</label>
	                    	<input type="text" name="titulo" class="form-control">
	                   </div><br><br>
		            	<input type="hidden" name="id_tipo" value="2" />
		            	<input type="hidden" name="id" id="id" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal -->

<!-- Modal eliminar tener en cuenta-->
<div class="modal inmodal" id="myModaltres" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar ítem tener en cuenta</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal eliminar tener en cuenta -->
						 	
<!-- Modal Ingresar Informacion -->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcuatro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar ítem Informacion</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/ingresar_informacion/'); ?>">
	                   <div class="col-sm-3">
	                    <label class="control-label pull-left">Icono</label><?php echo (isset($informacion['id'])) ? form_dropdown('subtitulo', $iconos, $informacion['subtitulo'], array('class'=>'form-control m-b')) : form_dropdown('subtitulo', $iconos, null, array('class'=>'form-control m-b')); ?></div>
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" class="form-control">
	                   </div>
	                    <div class="col-sm-7">
	                    	<label class="control-label pull-left">Informacion</label>
	                    	<input type="text" name="titulo" class="form-control">
	                    </div>
	                    <br><br><br><br>
		            	<input type="hidden" name="id_tipo" value="1" />
		            	<input type="hidden" name="id" id="id" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal Informacion -->

<!-- Modal eliminar Informacion-->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcinco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar ítem Informacion</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modaleliminar Informacion -->

<script>

  $('#myModal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });

  $('#myModaldos').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
</script>

</body>
</html>