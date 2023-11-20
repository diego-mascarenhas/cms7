
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
	                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
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
		                    <div class="ibox-title"><h5>Información del evento</h5>
		                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
		                    </div>
		                    
		                    <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                            <input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <h2>Datos del evento</h2>
	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">T&iacute;tulo</label>
				                    <div class="col-sm-3"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: $this->input->post('titulo'); ?>"></div>
		                            <label class="col-sm-2 control-label">Subt&iacute;tulo</label>
				                    <div class="col-sm-3"><input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($detalle['subtitulo'])) ? $detalle['subtitulo']: $this->input->post('subtitulo'); ?>"></div>
	                            </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">Estado</label>
				                    <div class="col-sm-3"><?php echo (isset($detalle['id'])) ? form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
				                    <label class="text-right col-sm-2 control-label">C&oacute;digo</label>
				                    <div class="col-sm-3"><input type="text" name="codigo" class="form-control" value="<?php echo (isset($detalle['codigo'])) ? $detalle['codigo']: $this->input->post('codigo'); ?>"></div>
	                            </div>
	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">Vencimiento</label>
									<div class="col-sm-3">
		                                <div class="input-group date dia">
		                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_vencimiento" value="<?php if(isset($detalle['fecha_vencimiento'])) { $date = date_create($detalle['fecha_vencimiento']); echo date_format($date, 'Y-m-d H:i:s'); } ?>">
		                            	</div>
		                            </div>	 
		                            
									<div class="col-sm-7">
										<?php if((isset($detalle['titulo'])) && $detalle['id_media']) { ?>
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$detalle['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            		</div>
	                            		<label class="text-right col-sm-4 control-label">Imagen</label>
						                <div class="col-sm-5">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del certificado del evento, debe tener 842x597 píxeles o proporcionales mayores. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
	                                    </div>
										<?php } else {?>
	                            		<label class="text-right col-sm-4 control-label">Imagen</label>
						                <div class="col-sm-4">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del certificado del evento, debe tener 842x597 píxeles o proporcionales mayores. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
										<?php } ?>
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
            </div>
	        <!-- Fin Contenido -->
			<?php echo form_close(); ?>

	       
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
						 	
