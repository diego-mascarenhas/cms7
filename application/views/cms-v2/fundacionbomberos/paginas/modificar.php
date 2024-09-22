<style>
.note-editor.note-frame { border:1px solid #ebebeb; border-radius:0;}
.contact-box { min-height: 210px;max-height: 210px; }
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Páginas</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Páginas</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
            </div>
        </div>
        
       <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
	   <input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
	   <?php 
	   		switch($item['id_tipo']) 
	   		{ 
		   		case 8: echo '<input type="hidden" name="medidas" value="1920x780">';break; //Slide
		   		case 753: echo '<input type="hidden" name="medidas" value="143x187">';break; //Boxes color
		   		case 855: echo '<input type="hidden" name="medidas" value="570x390">';break; //Participa
		   		case 723: echo '<input type="hidden" name="medidas" value="120x120">';break; //Valores
		   		case 759: echo '<input type="hidden" name="medidas" value="200x200">';break;
		   		case 765: echo '<input type="hidden" name="medidas" value="200x200">';break;
		   		case 762: echo '<input type="hidden" name="medidas" value="120x120">';break;
		   		case 729: echo '<input type="hidden" name="medidas" value="230x300">';break;
		   		case 831: echo '<input type="hidden" name="medidas" value="200x230">';break;
		   		case 885: echo '<input type="hidden" name="medidas" value="400x400">';break; //Dona
		   	}
	   	?>
	    
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Modificar contenido de <a href="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>"><?php echo $detalle['seccion']; ?></a></h5>
	                    </div>
	                    <div class="ibox-content">
	                        <?php if (validation_errors()) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo validation_errors(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
									</div>
								</div>
							<?php endif; ?>
							
                            <!-- Boxes Colores -->
                            <?php if($item['id_tipo'] == 753) { ?>
		                    <input type="hidden" name="id_tipo" value="753">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Titulo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']) : null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido del box." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
							    <label class="control-label col-sm-2">Seleccione color</label>
			                    <div class="col-sm-4 col-md-4"> 
			                    	<div class="input-group"><input type="text" name="contenido3" id="contenido3" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?>" class="form-control demo1 colorpicker-element"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Color de fondo del box." title=""> <i class="fa fa-question"></i></button></span></div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link que debe contener la url completa." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Slides -->
                           <?php } elseif($item['id_tipo'] == 8) { ?>
		                    <input type="hidden" name="id_tipo" value="8">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Titulo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del slide." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
								</div>
                            </div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link que debe contener la url completa." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 1920x780 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>


                           <!-- Paraticipá -->
                           <?php } elseif($item['id_tipo'] == 855) { ?>
		                    <input type="hidden" name="id_tipo" value="855">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Titulo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Tipo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del slide." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 570x390 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Valores -->
                           <?php } elseif($item['id_tipo'] == 723) { ?>
		                    <input type="hidden" name="id_tipo" value="723">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Titulo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 120x120 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Comite -->
                           <?php } elseif($item['id_tipo'] == 759) { ?>
		                    <input type="hidden" name="id_tipo" value="759">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Nombre</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del integrante." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Cargo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo del integrante." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre el integrante." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 200x200 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Vocales -->
                           <?php } elseif($item['id_tipo'] == 762) { ?>
		                    <input type="hidden" name="id_tipo" value="762">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Nombre</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Institución</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Institución." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
								</div>
                            </div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 120x120 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Equipo -->
                           <?php } elseif($item['id_tipo'] == 765) { ?>
		                    <input type="hidden" name="id_tipo" value="765">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Nombre</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del integrante." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-sm-2 control-label">Cargo</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo del integrante." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Email</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Email del integrante." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre el integrante." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 200x200 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                           <div class="hr-line-dashed"></div>

                           <!-- Dona -->
                           <?php } elseif($item['id_tipo'] == 831) { ?>
		                    <input type="hidden" name="id_tipo" value="831">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Título 1</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem en primer color." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Título 2</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? quotes_to_entities($item['subtitulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem en segundo color." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="contenido1" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link que debe contener la url completa." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 200x230 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
							</div>
                           <div class="hr-line-dashed pull-left full-width"></div>

                           <!-- Informes -->
                           <?php } elseif($item['id_tipo'] == 729) { ?>
		                    <input type="hidden" name="id_tipo" value="729">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Título</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>

                            <div class="hr-line-dashed pull-left full-width"></div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-4 control-label">Imagen</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 230x300 píxeles." title=""> <i class="fa fa-question"></i></button></span>
			                                    </div>
							                </div>
										</div>
									</div>

				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['archivo']) { ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-3 control-label">Archivo Actual</label>
		                            		<div class="col-sm-8"><?php echo $item['archivo'];?></div>
										</div>
									<?php } ?>
									<div class="form-group pull-left full-width">
		                            	<label class="text-right col-sm-3 control-label">Archivo</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
	                                        	<div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="archivo"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Archivo en PDF" title=""> <i class="fa fa-question"></i></button></span></div>
	                                        </div>
						                </div>
                                    </div>
					            </div>
                            </div>
                           <div class="hr-line-dashed pull-left full-width"></div>

                           <!-- Doná -->
                           <?php } elseif($item['id_tipo'] == 885) { ?>
		                    <input type="hidden" name="id_tipo" value="885">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Título</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>

                            <div class="form-group">
							    <label class="control-label col-sm-2">Categoría</label>
			                    <div class="col-sm-4 col-md-4"> 
			                    	<div class="input-group"><input type="text" name="subtitulo" value="<?php echo (isset($item['subtitulo'])) ? quotes_to_entities($item['subtitulo']): null; ?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Categoría del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
			                    </div>

							    <label class="control-label col-sm-2">Fondo categoría</label>
			                    <div class="col-sm-4 col-md-4"> 
			                    	<div class="input-group"><input type="text" name="texto_adicional" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>" class="form-control demo1 colorpicker-element"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Color de fondo de la categoría." title=""> <i class="fa fa-question"></i></button></span></div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre la acción." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>

                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;height:150px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-3 control-label">Imagen</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño 400x400 píxeles." title=""> <i class="fa fa-question"></i></button></span>
			                                    </div>
							                </div>
										</div>
									</div>
                            </div>
                           <div class="hr-line-dashed pull-left full-width"></div>

                           <!-- Campañas -->
                           <?php } else { ?>
		                    <input type="hidden" name="id_tipo" value="<?php echo $item['id_tipo'];?>">
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Título</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    <input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? quotes_to_entities($item['titulo']): null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>

                            <div class="hr-line-dashed pull-left full-width"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre el integrante." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
									<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4" value=""><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								</div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="contenido2" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link que debe contener la url completa." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Galería</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
										<div class="input-group"><?php echo form_dropdown('subtitulo', $media_proyectos, $item['subtitulo'], 'class="form-control m-b-sm"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Selector de galería de imágenes que se mostrará al lado del texto del ítem. El contenido de la misma se administra desde el módulo Multimedia." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed pull-left full-width"></div>
                           <?php }  ?>
                                
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Orden</label>
			                    <div class="col-sm-2">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
	                            <label class="col-sm-3 control-label text-right">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
		                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el ítem se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
		                         </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                	<input type="hidden" name="idioma" value="<?php echo (!empty($item['idioma'])) ? $item['idioma'] : null; ?>">
                                	<input type="hidden" name="id_imagen_tipo" value="13">
                                	<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
					                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
					                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
		                </div>
		            </div>
		        </div>
		    </div>
        </div>
	    <?php echo form_close();?>

<!-- SUMMERNOTE -->
<link href="/assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script>

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 200,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>

<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script>
  $('.demo1').colorpicker();
</script>    
      
                                       