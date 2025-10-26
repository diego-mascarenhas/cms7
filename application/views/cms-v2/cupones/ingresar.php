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
                <h2>Sitio web Cupones</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/cupones/');?>">Cupones</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Ingresar cup&oacute;n</h5></div>
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
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <div class="row">
				<div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Información del cup&oacute;n</h5>
	                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
	                    </div>
	                    
	                    <div class="ibox-content" style="float:left; width:100%;">
		                 	<div class="form-group">
			                    <label class="text-right col-sm-1 col-md-2 control-label">Cup&oacute;n</label>
			                    <div class="col-sm-3 col-md-3"><input type="text" name="cupon" class="form-control" value="<?php echo (isset($item['id'])) ? $item['cupon']: null; ?>"></div>
			                    <label class="text-right col-sm-2 control-label">Descuento</label>
			                    <div class="col-sm-3 col-md-2"><input type="text" name="descuento" class="form-control" value="<?php echo (isset($item['descuento'])) ? $item['descuento']: null; ?>"></div>
			                    <label class="text-right col-sm-1 control-label">Stock</label>
			                    <div class="col-sm-2 col-md-2"><input type="text" name="stock" class="form-control" value="<?php echo (isset($item['stock'])) ? $item['stock']: null; ?>"></div>
		                 	</div>
		                 	<br><br><br>
		                 	<div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Vencimiento</label>
								<div class="col-sm-4 col-md-3">
	                                <div class="input-group date dia">
	                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_vencimiento" value="<?php if(isset($item['fecha_vencimiento'])) { $date = date_create($item['fecha_vencimiento']); echo date_format($date, 'Y-m-d H:i:s'); } ?>">
	                            	</div>
	                            </div>	                 	
			                    <label class="text-right col-sm-2 control-label">Estado</label>
			                    <div class="col-sm-3 col-md-2">
				                    <?php echo (isset($item['id'])) ? form_dropdown('estado', $estados, $item['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
				            </div>
				            <br><br><br>
		                    <!-- Imagenes Generales -->
	                    </div>

                    </div>
                </div>
        
		<?=form_close()?>

		<br><br></div></div>
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

						 	
<!-- Data picker -->
<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>

<script>
$('.dia.input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: "dd-mm-yyyy",
    todayHighlight: true
});
</script>

</body>
</html>