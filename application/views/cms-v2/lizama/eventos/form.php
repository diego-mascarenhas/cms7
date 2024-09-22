<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
</style>                        
         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url();?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/eventos');?>">Eventos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="destacado" value="0">
			<input type="hidden" name="destacado_slide" value="0">
			<input type="hidden" name="id_tipo" value="11">
			<input type="hidden" name="id_con_secciones" value="685">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
			</div>
        </div>

		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            Se produjo un error al ingresar el evento.</div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<?php echo $this->session->flashdata('mensaje');?></div>
			<?php } ?>
		</div>
		<?php } ?>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content" style="padding-top:0 !important;">
              <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class="active"><a data-toggle="tab" href="#tab-0"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-0" class="tab-pane active">

                        	<?php 
								$CI =& get_instance();
								$CI->load->model("Eventos_model");
								$parametros['idioma'] = $idioma['extension'];
								$cursos = $this->Eventos_model->comboCursos($parametros);
								$empresas = $this->Eventos_model->comboEmpresas($parametros);
								
								if(!empty($detalle['id']))
								{
									$CI->load->model("Eventos_model");
									$item = $this->Eventos_model->getContenidoDetalleIdioma($detalle['id'], $idioma['extension']);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-1 control-label">Título</label>
											<div class="col-md-3">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del evento del curso." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
						                    <label class="text-right col-md-1 control-label">Curso</label>
											<div class="col-md-3">
												<?php echo (isset($detalle['filtro2'])) ? form_dropdown('filtro2', $cursos, $detalle['filtro2'], array('class'=>'form-control m-b')) : form_dropdown('filtro2', $cursos, null, array('class'=>'form-control m-b')); ?>
											</div>
						                    <label class="text-right col-md-1 control-label">Empresa</label>
											<div class="col-md-3">
												<?php echo (isset($detalle['filtro1'])) ? form_dropdown('filtro1', $empresas, $detalle['filtro1'], array('class'=>'form-control m-b')) : form_dropdown('filtro1', $empresas, null, array('class'=>'form-control m-b')); ?>
											</div>
				                 		</div>
					                </div>
	
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-1 control-label">Fecha</label>
						                    <div class="col-md-3">
				                                <div class="input-group date dia">
				                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="subtitulo_<?php echo $idioma['extension'];?>" value="<?php if(isset($item['subtitulo'])) { $date = date_create($item['subtitulo']); echo date_format($date, 'Y-m-d H:i:s'); } ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha del evento." title=""> <i class="fa fa-question"></i></button></span>
				                                </div>
		                                    </div>
		                                    
						                    <label class="text-right col-md-1 control-label">Hora</label>
						                    <div class="col-md-3">
												<div class="input-group clockpicker" data-autoclose="true">
					                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span><input type="text" class="form-control" name="texto_adicional_<?php echo $idioma['extension'];?>" value="14:00"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Hora del evento." title=""> <i class="fa fa-question"></i></button></span>
					                            </div>
					                    	</div>
				                 		</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-1 control-label">Link</label>
						                    <div class="col-md-3">
		                                        <div class="input-group">
			                                        <input type="text" name="contenido1_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo del evento." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                </div>

						                    <label class="text-right col-md-1 control-label">Contraseña</label>
						                    <div class="col-md-3">
		                                        <div class="input-group">
			                                        <input type="text" name="contenido2_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo del evento." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                </div>
		                                </div>
	                                </div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
				                            <label class="col-sm-2 control-label text-right">Estado</label>
				                            <div class="col-sm-2">
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="auto right" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
					                        </div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
						                <div class="hr-line-dashed"></div>
			                            <div class="form-group m-t-lg">
			                                <div class="col-sm-4 col-sm-offset-2">
			                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
			                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
			                                </div>
			                            </div>
					                </div>
					                
					              </div>
	                        </div>
	                       </div>
	                        <!-- Fin Item Generales -->
                       	<?php } ?>
						<!-- Fin Items Idiomas -->
                     <?php echo form_close();?>
                     
                    </div>
                 </div>
             </div>                 
         </div>
     </div>     

<!-- Data picker -->
<link href="<?php echo base_url('assets/css/plugins/clockpicker/clockpicker.css'); ?>" rel="stylesheet" type="text/css">
<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/clockpicker/clockpicker.js'); ?>"></script>
<script>
$('[data-toggle="tooltip"]').tooltip(); 
$('.clockpicker').clockpicker();


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