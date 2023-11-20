<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/empresas'); ?>">Empresas</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller' && !empty($detalle['id'])) { ?>
                        	<a href="<?php echo base_url('administracion/empresas/eliminar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar empresa</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva empresa' : 'Modificar empresa'; ?></h5>
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
								
	                            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Empresa</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="empresa" value="<?php echo (isset($detalle['empresa'])) ? $detalle['empresa'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Categoría</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_categoria', $categorias, (isset($detalle['id_categoria'])) ? $detalle['id_categoria'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Referido</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('referido', $referidos, (isset($detalle['referido'])) ? $detalle['referido'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <?php if (!empty($detalle['id'])) { ?>
			                            <label class="col-sm-2 control-label">Contacto</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $contactos, (isset($detalle['id_contacto'])) ? $detalle['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <?php } ?>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Teléfono</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="telefono" class="form-control" value="<?php echo (isset($detalle['telefono'])) ? $detalle['telefono'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">WhatsApp</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="whatsapp" class="form-control" value="<?php echo (isset($detalle['whatsapp'])) ? $detalle['whatsapp'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                             <div class="form-group">
			                            <label class="col-sm-2 control-label">email</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="email" class="form-control" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Web</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="web" class="form-control" value="<?php echo (isset($detalle['web'])) ? $detalle['web'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Domicilio</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="domicilio" class="form-control" value="<?php echo (isset($detalle['domicilio'])) ? $detalle['domicilio'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">País</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_pais', $paises, (isset($ubicacion['id_pais'])) ? $ubicacion['id_pais'] : null, 'id="id_pais" class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Provincia</label>
		                                <div class="col-sm-4">
			                                <select id="id_provincia" name="id_provincia" class="form-control m-b"></select>
			                            </div>
			                            <input type="hidden" id="id_provincia_tmp" value="<?php echo (isset($ubicacion['id_provincia'])) ? $ubicacion['id_provincia'] : null; ?>" disabled>
			                            
			                            <label class="col-sm-2 control-label">Localidad</label>
										<div class="col-sm-4">
			                            	<select id="id_localidad" name="id_localidad" class="form-control m-b"></select>
			                            </div>
			                            <input type="hidden" id="id_localidad_tmp" value="<?php echo (isset($ubicacion['id_localidad'])) ? $ubicacion['id_localidad'] : null; ?>" disabled>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <script type="text/javascript">
										$(document).ready(function() {
											
											if ($("#id_provincia_tmp").val()) {
												$.ajax({
													type:"POST",
													dataType: 'json',
													url:"<?php echo base_url('sys/get_provincias/') ?>",
													data: {id_pais:$('#id_pais').val()},
													success: function(data) {
																$('select#id_provincia').html('');
																
																if (data) {
																	$.each(data, function(key, value) {
																		
																		$('#id_provincia')
																			.append($("<option></option>")
																				.attr("value", key)
																				.text(value));
																	});
																}
																
																if ($("#id_provincia_tmp").val()) {
																	$("select#id_provincia option[value=" + $("#id_provincia_tmp").val() + "]").attr("selected", true);
																	
																	$.ajax({
																		type:"POST",
																		dataType: 'json',
																		url:"<?php echo base_url('sys/get_localidades/') ?>",
																		data: {id_provincia:$('#id_provincia').val()},
																		success: function(data) {
																					$('select#id_localidad').html('');
																					
																					if (data) {
																						$.each(data, function(key, value) {
																							
																							$('#id_localidad')
																								.append($("<option></option>")
																									.attr("value", key)
																									.text(value));
																						});
																					}
																					
																					if ($("#id_localidad_tmp").val()) {
																						$("select#id_localidad option[value=" + $("#id_localidad_tmp").val() + "]").attr("selected", true);
																					}
																				}
																		});
																}
															}
													});
												}
											
											
											$('#id_pais').on('change', function() {
												$.ajax({
													type:"POST",
													dataType: 'json',
													url:"<?php echo base_url('sys/get_provincias/') ?>",
													data: {id_pais:$(this).val()},
													success: function(data) {
																$('select#id_provincia').html('');
																$('select#id_localidad').html('');
																
																if (data) {
																	$.each(data, function(key, value) {   
																		$('#id_provincia')
																			.append($("<option></option>")
																				.attr("value", key)
																				.text(value)); 
																	});
																}
															}
													});
												});
												
												
											$('#id_provincia').on('change', function() {
												$.ajax({
													type:"POST",
													dataType: 'json',
													url:"<?php echo base_url('sys/get_localidades/') ?>",
													data: {id_provincia:$(this).val()},
													success: function(data) {
																console.log(data);
																
																$('select#id_localidad').html('');
																
																if (data) {
																	$.each(data, function(key, value) {   
																		$('#id_localidad')
																			.append($("<option></option>")
																				.attr("value", key)
																				.text(value)); 
																	});
																}
															}
													});
												});
											
											});
									</script>
									
		                            
<!--
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Observaciones</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('observaciones', (isset($detalle['observaciones'])) ? $detalle['observaciones'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
-->
		                            
		                            <?php if (!empty($detalle['id'])) { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
		                                <div class="col-sm-10">
			                                <div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label>Inactivo</label>
			                                </div>
			                                <div class="radio radio-inline">
			                            		<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
			                            		<label>Activo</label>
			                                </div>
	                                        <div class="radio radio-inline">
		                                        <input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
		                                        <label>Prospecto</label>
	                                        </div>
	                                        <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="4" <?php if (isset($detalle['estado']) && $detalle['estado'] == 4) echo 'checked="checked"'; ?>>
	                                        	<label>Nuevo</label>
	                                        </div>
	                                        <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="5" <?php if (isset($detalle['estado']) && $detalle['estado'] == 5) echo 'checked="checked"'; ?>>
	                                        	<label>Asignado</label>
	                                        </div>
	                                        <div class="radio radio-inline">
		                                        <input type="radio" name="estado" value="6" <?php if (isset($detalle['estado']) && $detalle['estado'] == 6) echo 'checked="checked"'; ?>>
		                                        <label>Contactado</label>
	                                        </div>
	                                        <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="7" <?php if (isset($detalle['estado']) && $detalle['estado'] == 7) echo 'checked="checked"'; ?>>
	                                        	<label>Esperando Respuesta</label>
	                                        </div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <? } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>