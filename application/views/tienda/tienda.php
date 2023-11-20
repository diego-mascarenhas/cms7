<style>
.tabs-container .panel-body { border-bottom:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
pre  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
pre code { white-space: pre-line; }
</style>          
              
<link href="<?php echo base_url('assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                        <strong>Tienda </strong>
                    </li>
                </ol>
            </div>
            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
        </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if ($this->input->get('error') == 1) { ?>
				<div class="col-md-12">
					<div class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <p>Debe ingresar al menos un medio de envío y al menos un medio de pago.</p>
					</div>
				</div>
                <?php } ?>
                <?php if ($this->input->get('ok') == 1) { ?>
				<div class="col-md-12">
					<div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <p>El contenido fue modificado con &eacute;xito.</p>
					</div>
				</div>
                <?php } ?>
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container m-b-md">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"> Configuración</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2"> Redes Sociales</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-3"> Envíos</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-4"> Métodos de Pago</a></li>
                        </ul>

                        <div class="tab-content">
	                        <!-- Configuracion -->
							<div id="tab-1" class="tab-pane active">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12 p-xxs">
											<h2 class=" pull-left full-width">Datos general de la tienda</h2>
											<div class="hr-line-dashed pull-left full-width"></div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Nombre</label>
											 	<div class="col-sm-4">
													<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>" readonly="true"></div>
											 	<label class="col-md-2 control-label">Horario</label>
											 	<div class="col-sm-4">
													<input type="text" name="contenido1" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?>"></div>
			                                </div>
											<div class="hr-line-dashed pull-left full-width"></div>
		                            
										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Rubro</label>
											 	<div class="col-sm-4">
													<?php echo (isset($item['id_rubro'])) ? form_dropdown('rubro', $rubros, $item['id_rubro'], array('class'=>'form-control m-b')) : form_dropdown('rubro', $rubros, null, array('class'=>'form-control m-b')); ?></div>
					                            <label class="col-sm-2 control-label">Estado</label>
					                            <div class="col-sm-4">
						                            <div class="radio radio-inline radio-primary">
					                                	<input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> ONline </label>
						                            </div>
						                            <div class="radio radio-inline radio-primary">
			                                        	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> OFFline </label>
						                            </div>
					                            </div>

			                                </div>
											<div class="hr-line-dashed pull-left full-width"></div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Teléfono fijo o para recibir llamadas</label>
											 	<div class="col-sm-4">
													<input type="text" name="telefono" class="form-control" value="<?php echo (isset($item['telefono'])) ? $item['telefono']: null; ?>"></div>
												<label class="col-md-2 control-label">Celular</label>
												<div class="col-md-4">
													<input type="text" name="celular" class="form-control" value="<?php echo (isset($item['celular'])) ? $item['celular']: null; ?>"></div>
			                                </div>
											<div class="hr-line-dashed pull-left full-width"></div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
					                            <label class="col-sm-2 control-label">Email</label>
				                                <div class="col-sm-4">
					                                <input type="text" class="form-control" name="email" value="<?php echo (isset($item['email'])) ? $item['email'] : null; ?>">
					                            </div>
					                            <label class="col-sm-2 control-label">Recibir pedidos por email</label>
					                            <div class="col-sm-4">
						                            <div class="radio radio-inline radio-primary">
					                                	<input type="radio" name="recibir_email" value="1" <?php if (isset($item['recibir_email']) && $item['recibir_email'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
						                            </div>
						                            <div class="radio radio-inline radio-primary">
			                                        	<input type="radio" name="recibir_email" value="0" <?php if (isset($item['recibir_email']) && $item['recibir_email'] == '0') echo 'checked="checked"'; ?>><label> No </label>
						                            </div>
					                            </div>
				                            </div>
											<div class="hr-line-dashed pull-left full-width"></div>
		                            
		                            
										 	<div class="form-group m-b-md pull-left full-width m-t-md">
												<label class="col-md-2 control-label">Calle</label>
												<div class="col-md-4">
													<input type="text" name="domicilio" class="form-control" value="<?php echo (isset($item['domicilio'])) ? $item['domicilio']: null; ?>"></div>
												<label class="col-md-2 control-label">Número</label>
												<div class="col-md-4">
													<input type="text" name="numero" class="form-control" value="<?php echo (isset($item['numero'])) ? $item['numero']: null; ?>"></div>
										 	</div>
										 	<div class="form-group m-b-md pull-left full-width m-t-md">
												<label class="col-md-2 control-label">Localidad</label>
												<div class="col-md-2">
													<input type="text" name="localidad" class="form-control" value="<?php echo (isset($item['localidad'])) ? $item['localidad']: null; ?>"></div>
												<label class="col-md-2 control-label">Provincia</label>
												<div class="col-md-2">
													<input type="text" name="provincia" class="form-control" value="<?php echo (isset($item['provincia'])) ? $item['provincia']: null; ?>"></div>
												<label class="col-md-2 control-label">País</label>
												<div class="col-md-2">
													<?php echo (isset($item['pais'])) ? form_dropdown('pais', $paises, $item['pais'], array('class'=>'form-control m-b')) : form_dropdown('pais', $paises, null, array('class'=>'form-control m-b')); ?></div>

										 	</div>
											<div class="hr-line-dashed pull-left full-width"></div>
										 	
										 	<div class="form-group m-b-md pull-left full-width m-t-md">
						                   <!-- Imagenes Generales -->
						                    <div class="col-sm-6">
						                    	<div class="ibox-title bg-muted"><h5>Imagen Header</h5></div>
												<div class="ibox-content caja-imagen-tienda">
						                            <?php if(!empty($item['imagen'])) { ?>
					                            	<p>Imagen Actual</p>
					                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px; clear:both;"/>
													<br><br>
													<input type="file" name="imagen" class="form-control" style="width:auto;display:inline;">
					                            <?php } else { ?>
													<br><br>
													<input type="file" name="imagen" class="form-control d-block">
					                            <?php } ?>
												</div>
						                    </div>
						                    
							               <!-- Logo -->
						                    <div class="col-sm-6">
						                    	<div class="ibox-title bg-muted caja-imagen-tienda"><h5>Logo</h5></div>
												<div class="ibox-content caja-imagen-tienda">
						                            <?php if(!empty($item['logo'])) { ?>
					                            	<p>Imagen Actual</p>
					                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['logo']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
													<br><br>
													<input type="file" name="logo" class="form-control" style="width:auto;display:inline;">
					                            <?php } else { ?>
													<br><br>
													<input type="file" name="logo" class="form-control d-block">
					                            <?php } ?>
												</div>
						                    </div>
					                 	</div>


					                    <div class="col-sm-12">
											<div class="hr-line-dashed pull-left full-width"></div>
											<h2 class=" pull-left full-width">Personalizar</h2>
											<div class="hr-line-dashed pull-left full-width"></div>

											<div class="form-group pull-left full-width m-t-lg">
											    <label class="col-sm-2 control-label">Dominio propio</label>
												<div class="col-md-4 col-sm-8">
													<div class="input-group"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['url'])) ? $item['url']: null; ?>"><span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Para poder usar esta función, tiene que tener contratado un Plan Pro, caso contrario no va a funcionar su personalización. Consulte por favor a nuestro whatsapp sobre los costos del mismo. Ingrese su dominio sin https://, por ejemplo: tienda.sudominio.com" title=""> <i class="fa fa-question"></i></span></div>
												</div>
											</div>
											
											<div class="form-group m-b-md pull-left full-width m-t-lg">
											    <label class="col-sm-5 col-md-3 control-label">Seleccione Color Barras</label>
												<div class="col-sm-5 col-md-3">
													<div id="demo1" class="input-group colorpicker-component">
													    <input type="text" name="color1" value="<?php echo (isset($item['color1'])) ? $item['color1']: '#000000'; ?>" class="form-control" />
													    <span class="input-group-addon"><i></i></span>
													</div>
												</div>
													
											    <label class="col-sm-5 col-md-3 control-label">Seleccione Color Botones</label>
												<div class="col-sm-5 col-md-3">
													<div id="demo2" class="input-group colorpicker-component">
													    <input type="text" name="color2" value="<?php echo (isset($item['color2'])) ? $item['color2']: '#000000'; ?>" class="form-control" />
													    <span class="input-group-addon"><i></i></span>
													</div>
												</div>
											</div>
											
											<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Código de Google Analytics</label>
											 	<div class="col-sm-10">
											        <textarea name="analytics" rows="7" class="full-width"><?php echo (isset($item['analytics'])) ? $item['analytics']: null; ?></textarea>
											        <pre><code><b>Código completo, por ejemplo:</b> <br>
											        &lt;script&gt; async src=https://www.googletagmanager.com/gtag/js?id=UA-suID-x>&lt;/script&gt;
													&lt;script&gt;window.dataLayer = window.dataLayer || [];
													  function gtag(){dataLayer.push(arguments);}
													  gtag('js', new Date());
													  gtag('config', 'su-ID');
													&lt;/script&gt;</code></pre>
												</div>
											</div>
										    <div class="hr-line-dashed pull-left full-width"></div>



					                    </div>
									</div>
								</div>
							</div>
						</div>

		                    <!-- Redes Sociales -->
		                    <div id="tab-2" class="tab-pane">
		                        <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm p-xs pull-left full-width">Redes Sociales</h2>
										<div class="hr-line-dashed pull-left full-width"></div>

									 	<div class="form-group m-b-md pull-left full-width m-t-sm">
										 	<label class="col-md-2 control-label">Facebook</label>
										 	<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="facebook" class="form-control" value="<?php echo (isset($item['facebook'])) ? $item['facebook']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.facebook.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
		                                        </div>
											</div>
										 	<label class="col-md-2 control-label">Twitter</label>
										 	<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="twitter" class="form-control" value="<?php echo (isset($item['twitter'])) ? $item['twitter']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://twitter.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
		                                        </div>
	                                       </div>
		                                </div>
										<div class="hr-line-dashed pull-left full-width"></div>

									 	<div class="form-group m-b-md pull-left full-width m-t-sm">
										 	<label class="col-md-2 control-label">Instagram</label>
										 	<div class="col-sm-4">
		                                        <div class="input-group">
													<input type="text" name="instagram" class="form-control" value="<?php echo (isset($item['instagram'])) ? $item['instagram']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.instagram.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
		                                        </div>
		                                    </div>
										 	<label class="col-md-2 control-label">Linkedin</label>
										 	<div class="col-sm-4">
		                                        <div class="input-group">
													<input type="text" name="linkedin" class="form-control" value="<?php echo (isset($item['linkedin'])) ? $item['linkedin']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.linkedin.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
		                                        </div>
		                                    </div>
		                                </div>
										<div class="hr-line-dashed pull-left full-width"></div>

									 	<div class="form-group m-b-md pull-left full-width m-t-sm">
										 	<label class="col-md-2 control-label">Youtube</label>
										 	<div class="col-sm-4">
		                                        <div class="input-group">
													<input type="text" name="Youtube" class="form-control" value="<?php echo (isset($item['youtube'])) ? $item['youtube']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.youtube.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
		                                        </div>
		                                    </div>
		                                </div>
		                               </div>
									</div>
								</div>
							</div>
		                    <!-- Fin Redes Sociales -->


	                        <!-- Envios -->
	                        <div id="tab-3" class="tab-pane">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12 p-xxs">
											<h2 class=" pull-left full-width">Selección opciones para envíos</h2>
											<div class="hr-line-dashed pull-left full-width"></div>
			                                    <?php if(!empty($envios)) { foreach($envios as $listaenvios) { ?>	
											 	<div class="form-group m-b-md pull-left full-width m-t-xs">
				                                    <div class="col-sm-4">
														<div class="checkbox checkbox-primary">
										                    <input id="checkbox<?php echo $listaenvios['id'];?>" type="checkbox" name="relacionesenvios[]" value="<?php echo $listaenvios['id'];?>" <?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) {echo ' checked';} } }?>>
					                                        <label for="checkbox<?php echo $listaenvios['id'];?>"><?php echo $listaenvios['medio_envio']; ?></label>
														</div>
													</div>
			                                       <div class="col-sm-5">
				                                      <label class="col-md-6 control-label">Recargo/Descuento</label>
			                                       <div class="col-md-4">
									                   <select name="envio<?php echo $listaenvios['id'];?>" class="form-control">
									                    	<option value="0">-- Tipo --</option>
									                    	<option value="20"<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if( ($listaenvios['id'] == $relaenvios['id']) && ($relaenvios['tipo'] == 20)) { echo ' selected'; }} }?>>Descuento</option>
									                    	<option value="21"<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if( ($listaenvios['id'] == $relaenvios['id']) && ($relaenvios['tipo'] == 21)) { echo ' selected'; }} }?>>Recargo</option>
									                   </select>
			                                       </div>
			                                       </div>
			                                       <div class="col-sm-3">
			                                        <label><?php echo ($listaenvios['id'] == 1) ? 'Porcentaje (%) ' : 'Valor en $ ';?></label>
								                     <input id="valor<?php echo $listaenvios['id'];?>" type="text" name="valor<?php echo $listaenvios['id'];?>" value="<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) { if($relaenvios['descuento'] > 0) { $relaenvios['valor'] = $relaenvios['descuento']; } elseif($relaenvios['recargo'] > 0) { $relaenvios['valor'] = $relaenvios['recargo']; } else {$relaenvios['valor'] = ''; } echo $relaenvios['valor'];} } }?>">
								                    </div>
			                                    </div>
								                <?php } } else { echo 'No se encontraron resultados';} ?>	
<!--
											 	<div class="form-group m-b-md pull-left full-width m-t-xs">
												<label class="col-md-2 control-label">Costo envío</label>
												<div class="col-md-2">
													<input type="text" name="costo_envio" class="form-control" value="<?php echo (isset($item['costo_envio'])) ? $item['costo_envio']: null; ?>"></div>

												</div>
-->
										</div>
									</div>
								</div>
							</div>

	                        <!-- Metodos de Pago -->
	                        <div id="tab-4" class="tab-pane">
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm pull-left full-width">Métodos de Pago</h2>
										<div class="hr-line-dashed pull-left full-width"></div>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">

											<?php if(!empty($medios)) { foreach($medios as $lista) { ?>	
												<div class="form-group m-b-md pull-left full-width m-t-xs">
			                                       <div class="col-sm-4">
									                    <input id="checkbox<?php echo $lista['id'];?>" type="checkbox" name="relaciones[]" value="<?php echo $lista['id'];?>" <?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) {echo ' checked';} } }?>>
				                                        <label for="checkbox<?php echo $lista['id'];?>"><?php echo $lista['forma_pago']; ?></label>
								                    </div>
			                                       <div class="col-sm-5">
				                                      <label class="col-md-6 control-label">Recargo/Descuento</label>
			                                       <div class="col-md-4">
									                   <select name="tipo<?php echo $lista['id'];?>" class="form-control">
									                    	<option value="0">-- Tipo --</option>
									                    	<option value="20"<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if( ($lista['id'] == $rela['id']) && ($rela['tipo'] == 20)) { echo ' selected'; }} }?>>Descuento</option>
									                    	<option value="21"<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if( ($lista['id'] == $rela['id']) && ($rela['tipo'] == 21)) { echo ' selected'; }} }?>>Recargo</option>
									                   </select>
			                                       </div>
			                                       </div>
			                                       <div class="col-sm-3">
			                                        <label>Porcentaje (%)</label>
								                    <input id="porcentaje<?php echo $lista['id'];?>" type="text" name="porcentaje<?php echo $lista['id'];?>" value="<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) { if($rela['descuento'] > 0) { $rela['porcentaje'] = $rela['descuento']; } elseif($rela['recargo'] > 0) { $rela['porcentaje'] = $rela['recargo']; } else {$rela['porcentaje'] = ''; } echo $rela['porcentaje'];} } }?>">
								                    </div>

<!--
			                                       <div class="col-sm-4">
			                                        <label>Recargo</label>
								                    <input id="recargo<?php echo $lista['id'];?>" type="text" name="recargo<?php echo $lista['id'];?>" value="<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) {echo $rela['recargo'];} } }?>">
								                    </div>
			                                       <div class="col-sm-4">
			                                        <label>Descuento</label>
								                    <input id="descuento<?php echo $lista['id'];?>" type="text" name="descuento<?php echo $lista['id'];?>" value="<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) {echo $rela['descuento'];} } }?>">
								                    </div>
-->
												</div>
								           <?php } } else { echo 'No se encontraron resultados';} ?>	

								           </div>
								           <div class="form-group m-b-md pull-left full-width m-t-lg">
										        <label class="col-sm-2 control-label">Email para PayPal</label>
												<div class="col-sm-4">
													<input type="text" name="email_paypal" class="form-control" value="<?php echo (isset($item['email_paypal'])) ? $item['email_paypal']: null; ?>"></div>
								           </div>
								           <div class="form-group m-b-md pull-left full-width m-t-lg">
										        <label class="col-sm-2 control-label">Email para Mercado Pago</label>
												<div class="col-sm-4">
													<input type="text" name="email_MP" class="form-control" value="<?php echo (isset($item['email_MP'])) ? $item['email_MP']: null; ?>"></div>
								           </div>
								           <div class="form-group m-b-md pull-left full-width">
										        <label class="col-sm-2 control-label">ID Cliente Mercado Pago</label>
												<div class="col-sm-4">
													<input type="text" name="clienteMP" class="form-control" value="<?php echo (isset($item['clienteMP'])) ? $item['clienteMP']: null; ?>"></div>
												<label class="col-sm-2 control-label">Client Secret Mercado Pago</label>
												<div class="col-sm-4">
													<input type="text" name="claveMP" class="form-control" value="<?php echo (isset($item['claveMP'])) ? $item['claveMP']: null; ?>"></div>
										   </div>									        
								           <div class="form-group m-b-sm pull-left full-width m-t-md">
										       <div class="alert alert-info fade in">
												  <h4 class="pull-left">CREDENCIALES MERCADO PAGO</h4><br>
													<br>
													<strong>Para ver las credenciales tenés que seguir los siguientes pasos:</strong>
													<ol>
														
														<li>Ingresar en tu cuenta de Mercado Pago</li>
														<li>Ir a <a href="https://www.mercadopago.com/mla/account/credentials" target="_blank">https://www.mercadopago.com/mla/account/credentials</a></li>
														<li>Seleccionar Checkout básico y listo! Ya podés ver tu Client ID y tu Client Secret</li>
													</ol>
												</div>
								           </div>

										</div>
									</div>
								</div>
							</div>



				<div class="col-lg-12 p-xxs" style="background:#fff; padding: 0 25px 25px; margin:0 2px 25px 0; border:1px solid #e7eaec; border-top:0;">
				<div class="form-group">
					<div class="col-sm-4">
						<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
						<button class="btn btn-primary" type="submit">Guardar cambios</button>
					</div>
				</div>
				</div>              							
 		            <?php echo form_close();?>

		                    
							</div>



                      </div>
                    </div>
                </div>
            </div>
<!-- Fin Contenido -->
<!-- Color picker -->
<script src="<?php echo base_url('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>

<script>
$('[data-toggle="tooltip"]').tooltip(); 
$(function() {
    $('#demo1').colorpicker();
    $('#demo2').colorpicker();
});
</script>