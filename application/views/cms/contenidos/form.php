<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<!-- Carga de Imagenes -->
			<link href="<?php echo base_url('assets/css/plugins/dropzone/basic.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/plugins/dropzone/dropzone.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Sitio web</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('cms'); ?>">Sitio web</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
			        <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
			        <div class="col-sm-12 col-md-9">
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div>
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
											
											<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			                            	<input type="hidden" name="id_empresa" value="<?php echo (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null; ?>">
			                            	<input type="hidden" name="categoria" value="<?php echo $detalle['categoria']; ?>">
			                            	
			                            	<input type="text" name="titulo" class="form-control input-lg" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo'] : null; ?>" placeholder="<?php echo (isset($detalle['campos']['titulo_nombre'])) ? $detalle['campos']['titulo_nombre'] : 'Título'; ?>">
				                    </div>
				                </div>
				            </div>
				        </div>
				        
				        <?php if ($detalle['campos']['descripcion_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>General</h5>
				                    </div>
				                    <div class="ibox-content no-padding">
					                    <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control summernote"'); ?>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if ($detalle['campos']['campo1_mostrar'] || $detalle['campos']['campo2_mostrar'] || $detalle['campos']['campo3_mostrar'] || $detalle['campos']['campo4_mostrar'] || $detalle['campos']['campo5_mostrar'] || $detalle['campos']['campo6_mostrar'] || $detalle['campos']['campo7_mostrar'] || $detalle['campos']['campo8_mostrar'] || $detalle['campos']['campo9_mostrar'] || $detalle['campos']['campo10_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Otros datos</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <?php if ($detalle['campos']['campo1_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo1_nombre'])) ? $detalle['campos']['campo1_nombre'] : 'Campo 1'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo1" class="form-control" value="<?php echo (isset($detalle['campo1'])) ? $detalle['campo1'] : null; ?>">
				                            </div>
					                    </div>
					                    <?php } ?>
					                    
					                    <?php if ($detalle['campos']['campo2_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo2_nombre'])) ? $detalle['campos']['campo2_nombre'] : 'Campo 2'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo2" class="form-control" value="<?php echo (isset($detalle['campo2'])) ? $detalle['campo2'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo3_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo3_nombre'])) ? $detalle['campos']['campo3_nombre'] : 'Campo 3'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo3" class="form-control" value="<?php echo (isset($detalle['campo3'])) ? $detalle['campo3'] : null; ?>">
				                            </div>
					                    </div>
				                        <?php } ?>
				                            
				                        <?php if ($detalle['campos']['campo4_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo4_nombre'])) ? $detalle['campos']['campo4_nombre'] : 'Campo 4'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo4" class="form-control" value="<?php echo (isset($detalle['campo4'])) ? $detalle['campo4'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo5_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo5_nombre'])) ? $detalle['campos']['campo5_nombre'] : 'Campo 5'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo5" class="form-control" value="<?php echo (isset($detalle['campo5'])) ? $detalle['campo5'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo6_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo6_nombre'])) ? $detalle['campos']['campo6_nombre'] : 'Campo 6'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo6" class="form-control" value="<?php echo (isset($detalle['campo6'])) ? $detalle['campo6'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo7_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo7_nombre'])) ? $detalle['campos']['campo7_nombre'] : 'Campo 7'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo7" class="form-control" value="<?php echo (isset($detalle['campo7'])) ? $detalle['campo7'] : null; ?>">
				                            </div>
					                    </div>
				                        <?php } ?>
				                            
				                        <?php if ($detalle['campos']['campo8_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo8_nombre'])) ? $detalle['campos']['campo8_nombre'] : 'Campo 8'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo8" class="form-control" value="<?php echo (isset($detalle['campo8'])) ? $detalle['campo8'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo9_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo9_nombre'])) ? $detalle['campos']['campo9_nombre'] : 'Campo 9'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo9" class="form-control" value="<?php echo (isset($detalle['campo9'])) ? $detalle['campo9'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['campo10_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['campo10_nombre'])) ? $detalle['campos']['campo10_nombre'] : 'Campo 10'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="campo10" class="form-control" value="<?php echo (isset($detalle['campo10'])) ? $detalle['campo10'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if ($detalle['campos']['vinculo1_mostrar'] || $detalle['campos']['vinculo2_mostrar'] || $detalle['campos']['vinculo3_mostrar'] || $detalle['campos']['vinculo3_mostrar'] || $detalle['campos']['vinculo5_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Vínculos</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <?php if ($detalle['campos']['vinculo1_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['vinculo1_nombre'])) ? $detalle['campos']['vinculo1_nombre'] : 'Vínculo 1'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="vinculo1" class="form-control" value="<?php echo (isset($detalle['vinculo1'])) ? $detalle['vinculo1'] : null; ?>">
				                            </div>
					                    </div>
					                    <?php } ?>
					                    
					                    <?php if ($detalle['campos']['vinculo2_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['vinculo2_nombre'])) ? $detalle['campos']['vinculo2_nombre'] : 'Vínculo 2'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="vinculo2" class="form-control" value="<?php echo (isset($detalle['vinculo2'])) ? $detalle['vinculo2'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['vinculo3_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['vinculo3_nombre'])) ? $detalle['campos']['vinculo3_nombre'] : 'Vínculo 3'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="vinculo3" class="form-control" value="<?php echo (isset($detalle['vinculo3'])) ? $detalle['vinculo3'] : null; ?>">
				                            </div>
					                    </div>
				                        <?php } ?>
				                            
				                        <?php if ($detalle['campos']['vinculo4_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['vinculo4_nombre'])) ? $detalle['campos']['vinculo4_nombre'] : 'Vínculo 4'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="vinculo4" class="form-control" value="<?php echo (isset($detalle['vinculo4'])) ? $detalle['vinculo4'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['vinculo5_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['vinculo5_nombre'])) ? $detalle['campos']['vinculo5_nombre'] : 'Vínculo 5'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="vinculo5" class="form-control" value="<?php echo (isset($detalle['vinculo5'])) ? $detalle['vinculo5'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if ($detalle['campos']['data1_mostrar'] || $detalle['campos']['data2_mostrar'] || $detalle['campos']['data3_mostrar'] || $detalle['campos']['data3_mostrar'] || $detalle['campos']['data5_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Otros datos</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <?php if ($detalle['campos']['data1_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['data1_nombre'])) ? $detalle['campos']['data1_nombre'] : 'Data 1'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="data1" class="form-control" value="<?php echo (isset($detalle['data1'])) ? $detalle['data1'] : null; ?>">
				                            </div>
					                    </div>
					                    <?php } ?>
					                    
					                    <?php if ($detalle['campos']['data2_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['data2_nombre'])) ? $detalle['campos']['data2_nombre'] : 'Data 2'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="data2" class="form-control" value="<?php echo (isset($detalle['data2'])) ? $detalle['data2'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['data3_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['data3_nombre'])) ? $detalle['campos']['data3_nombre'] : 'Data 3'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="data3" class="form-control" value="<?php echo (isset($detalle['data3'])) ? $detalle['data3'] : null; ?>">
				                            </div>
					                    </div>
				                        <?php } ?>
				                            
				                        <?php if ($detalle['campos']['data4_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['data4_nombre'])) ? $detalle['campos']['data4_nombre'] : 'Data 4'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="data4" class="form-control" value="<?php echo (isset($detalle['data4'])) ? $detalle['data4'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['data5_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['data5_nombre'])) ? $detalle['campos']['data5_nombre'] : 'Data 5'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="data5" class="form-control" value="<?php echo (isset($detalle['data5'])) ? $detalle['data5'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if ($detalle['campos']['media_proyecto1_mostrar'] || $detalle['campos']['media_proyecto2_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Multimedia</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <?php if ($detalle['campos']['media_proyecto1_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['media_proyecto1_nombre'])) ? $detalle['campos']['media_proyecto1_nombre'] : 'Media Proyecto 1'; ?></label>
			                                <div class="col-sm-10">
				                                <?php echo form_dropdown('media_proyecto1', $media_proyectos, (isset($detalle['media_proyecto1'])) ? $detalle['media_proyecto1'] : null, 'class="form-control m-b"'); ?>
				                            </div>
					                    </div>
					                    <?php } ?>
					                    
					                    <?php if ($detalle['campos']['media_proyecto2_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['media_proyecto2_nombre'])) ? $detalle['campos']['media_proyecto2_nombre'] : 'Media Proyecto 2'; ?></label>
			                                <div class="col-sm-10">
				                                <?php echo form_dropdown('media_proyecto2', $media_proyectos, (isset($detalle['media_proyecto2'])) ? $detalle['media_proyecto2'] : null, 'class="form-control m-b"'); ?>
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
<!--
			                            <?php if ($detalle['campos']['media_proyecto3_mostrar']) { ?>
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['media_proyecto3_nombre'])) ? $detalle['campos']['media_proyecto3_nombre'] : 'Media Proyecto 3'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="media_proyecto3" class="form-control" value="<?php echo (isset($detalle['media_proyecto3'])) ? $detalle['media_proyecto3'] : null; ?>">
				                            </div>
					                    </div>
				                        <?php } ?>
				                            
				                        <?php if ($detalle['campos']['media_proyecto4_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['media_proyecto4_nombre'])) ? $detalle['campos']['media_proyecto4_nombre'] : 'Media Proyecto 4'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="media_proyecto4" class="form-control" value="<?php echo (isset($detalle['media_proyecto4'])) ? $detalle['media_proyecto4'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
			                            
			                            <?php if ($detalle['campos']['media_proyecto5_mostrar']) { ?>
				                        <div class="form-group">
				                            <label class="col-sm-2 control-label"><?php echo (isset($detalle['campos']['media_proyecto5_nombre'])) ? $detalle['campos']['media_proyecto5_nombre'] : 'Media Proyecto 5'; ?></label>
			                                <div class="col-sm-10">
				                                <input type="text" name="media_proyecto5" class="form-control" value="<?php echo (isset($detalle['data5'])) ? $detalle['data5'] : null; ?>">
				                            </div>
			                            </div>
			                            <?php } ?>
-->
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Debug</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="form-group">
				                            <label class="col-sm-2 control-label">Multi select</label>
			                                <div class="col-sm-10">
									            <select data-placeholder="Choose a Country..." class="chosen-select" multiple style="width:350px;" tabindex="4">
									                <option value="">Select</option>
									                <option value="United States">United States</option>
									                <option value="United Kingdom">United Kingdom</option>
									                <option value="Afghanistan">Afghanistan</option>
									                <option value="Aland Islands">Aland Islands</option>
									                <option value="Albania">Albania</option>
									                <option value="Algeria">Algeria</option>
									                <option value="American Samoa">American Samoa</option>
									                <option value="Andorra">Andorra</option>
									            </select>
				                            </div>
					                    </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        
				    </div>
	
				    <div class="col-sm-12 col-md-3">
				    	<div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Publicar</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="form-group">
											<ul class="lista_acciones">
												<li><span> <i class="fa fa-key"></i> Estado:<!-- </span> <?php echo (!isset($detalle['estado']) || $detalle['estado'] == 1) ? 'Borrador' : 'Publicada'; ?> --></li>
													<?php echo form_dropdown('estado', $estados, (isset($detalle['estado'])) ? $detalle['estado'] : null, 'class="form-control m-b"'); ?>
<!-- 												<li><span> <i class="fa fa-eye"></i> Visibilidad:</span> Público <a>Editar</a></li> -->
<!-- 												<li><span> <i class="fa fa-history"></i> Revisiones:</span> 4 <a>Explora</a></li> -->
												<?php if (!empty($detalle['id'])) { ?>
													<li><span> <i class="fa fa-calendar"></i> Publicada el:</span> <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></li>
												<?php } ?>
											</ul>
					                    </div>
				                    </div>
				                    <div class="ibox-footer ibox-footer-publicaciones">
		                                <?php if (!empty($detalle['id'])) { ?><a href="<?php echo base_url('cms/eliminar/' . $detalle['id']); ?>" class="pull-left btn-mover-papelera">Mover a la papelera</a><?php } ?>
		                                <span class="pull-right"><button class="btn btn-sm btn-primary" type="submit"><?php echo (!empty($detalle['id'])) ? 'Actualizar' : 'Publicar'; ?></button></span>
	                        		</div>
				                </div>
				            </div>
				        </div>
<!--
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Categorías</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="checkbox m-r-xs">
	                                    	<input type="checkbox" id="checkbox1" name="categoria1">
	                                    	<label for="checkbox1">Categoría 1</label>
	                                    </div>
					                    <div class="checkbox m-r-xs">
	                                    	<input type="checkbox" id="checkbox2" name="categoria2">
	                                    	<label for="checkbox2">Categoría 2</label>
	                                    </div>
					                    <div class="checkbox m-r-xs">
	                                    	<input type="checkbox" id="checkbox3" name="categoria3">
	                                    	<label for="checkbox3">Categoría 3</label>
	                                    </div>
					                    <div class="checkbox m-r-xs">
	                                    	<input type="checkbox" id="checkbox4" name="categoria4">
	                                    	<label for="checkbox4">Categoría 4</label>
	                                    </div>
										<a href="#" class="btn-link btn-link-aside">+ Agregar nueva categoría</a>
				                    </div>
				                </div>
				            </div>
				        </div>
-->
<!--
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5>Etiquetas</h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
				                         <div class="form-group" style="height:70px;">
											<form role="form" class="form-inline">
				                                <div class="m_l_10 pull-left">
				                                    <input type="input" placeholder="Ingresar etiquetas" id="etiquetas" class="form-control">
				                                </div>
				                                <button class="btn btn-white" type="submit">Agregar</button>
				                            </form>
											<small class="pull-left m_l_10 m_t_5">Separar etiquetas por comas</small>
		                        										
											<a href="#" class="btn-link btn-link-aside pull-left m_l_10 m_t_5">+ Agregar nueva etiqueta</a>
			                        	</div>
				                    </div>
				                </div>
				            </div>
				        </div>
-->
					</form>
					
						<?php if (!empty($detalle['id']) && $detalle['campos']['imagen1_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5><?php echo $detalle['campos']['imagen1_nombre']; ?></h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="form-group">
						                    <?php echo form_open('cms/upload', array('class' =>'dropzone'));?>
						                    	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
						                    	<input type="hidden" name="archivo" value="imagen1">
											<?php echo form_close();?>
												<small><em>Tamaño: <?php echo $detalle['campos']['imagen1_ancho']; ?>x<?php echo $detalle['campos']['imagen1_alto']; ?></em></small>
												<?php if (!empty($detalle['imagen1'])) { ?>
													<a href=""><img src="<?php echo base_url('multimedia/cms/' . $detalle['imagen1']); ?>" style="width:80%; margin:0 10px;"></a>
<!-- 													<a href="#" class="btn-link btn-link-aside m_l_10 m_t_5">Modificar imagen1 destacada</a> -->
												<?php } ?>
					                    </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if (!empty($detalle['id']) && $detalle['campos']['imagen2_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5><?php echo $detalle['campos']['imagen2_nombre']; ?></h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="form-group">
						                    <?php echo form_open('cms/upload', array('class' =>'dropzone'));?>
						                    	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
						                    	<input type="hidden" name="archivo" value="imagen2">
											<?php echo form_close();?>
												<small><em>Tamaño: <?php echo $detalle['campos']['imagen2_ancho']; ?>x<?php echo $detalle['campos']['imagen2_alto']; ?></em></small>
												<?php if (!empty($detalle['imagen2'])) { ?>
													<a href=""><img src="<?php echo base_url('multimedia/cms/' . $detalle['imagen2']); ?>" style="width:80%; margin:0 10px;"></a>
<!-- 													<a href="#" class="btn-link btn-link-aside m_l_10 m_t_5">Modificar imagen2 destacada</a> -->
												<?php } ?>
					                    </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
				        
				        <?php if (!empty($detalle['id']) && $detalle['campos']['imagen3_mostrar']) { ?>
				        <div class="row">
				            <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
				                        <h5><?php echo $detalle['campos']['imagen3_nombre']; ?></h5>
				                        <div class="ibox-tools">
				                            <a class="collapse-link">
				                                <i class="fa fa-chevron-up"></i>
				                            </a>
				                        </div>			                   
				                    </div>
				                    <div class="ibox-content">
					                    <div class="form-group">
						                    <?php echo form_open('cms/upload', array('class' =>'dropzone'));?>
						                    	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
						                    	<input type="hidden" name="archivo" value="imagen3">
											<?php echo form_close();?>
												<small><em>Tamaño: <?php echo $detalle['campos']['imagen3_ancho']; ?>x<?php echo $detalle['campos']['imagen3_alto']; ?></em></small>
												<?php if (!empty($detalle['imagen3'])) { ?>
													<a href=""><img src="<?php echo base_url('multimedia/cms/' . $detalle['imagen3']); ?>" style="width:80%; margin:0 10px;"></a>
<!-- 													<a href="#" class="btn-link btn-link-aside m_l_10 m_t_5">Modificar imagen3 destacada</a> -->
												<?php } ?>
					                    </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				        <?php } ?>
					</div>
				</div>
	        </div>
	        
	        
	        <!-- Data picker -->
			<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>
			
			<!-- Clock picker -->
			<script src="<?php echo base_url('assets/js/plugins/clockpicker/clockpicker.js'); ?>"></script>
			
			<!-- Summernote -->
			<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>" type="text/javascript"></script>
			
			<!-- Dropzone -->
			<script src="<?php echo base_url('assets/js/plugins/dropzone/dropzone.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
			
			<!-- Select2 -->
		    <script src="<?php echo base_url('assets/js/plugins/select2/select2.full.min.js'); ?>"></script>
		        
	        <script>
		        Dropzone.options.dropzoneForm = {
			        acceptedFiles: ".gif,.jpg,.png>",
					maxFilesize: 2,
					addRemoveLinks: false,
		            paramName: "file",
		            dictDefaultMessage: "<strong>Arrastrar archivos. </strong>"
		        };
		        
		        
				
	            $(document).ready(function () {
		            $('.input-daterange').datepicker({
		                keyboardNavigation: false,
		                forceParse: false,
		                autoclose: true
		            });
	                
	                $('.clockpicker').clockpicker();
	                
	                $('.summernote').summernote({
				    	height: 150,   
						placeholder: 'Escribe el texto aquí...'
				    });
				    
				    $(".select2_demo_1").select2();
	            $(".select2_demo_2").select2();
	            $(".select2_demo_3").select2({
	                placeholder: "Select a state",
	                allowClear: true
	            });
	            
				    $('.chosen-select').chosen({width: "100%"});
	            });
	        </script>