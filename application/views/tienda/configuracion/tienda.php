<style>
.tabs-container .panel-body { border-bottom:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
pre, .alert-primary  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
.alert-primary  { font-size:13px;}
pre code { white-space: pre-line; }
.form_tienda { float:left !important;}
@media(max-width:576px)
{
	.ibox-content { padding:14px;}
	.form_tienda { float:none !important;}
}
</style>          
              
<link href="<?php echo base_url('assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-lg-12">
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
        </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
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
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Datos generales de la tienda</h5>
	                    </div>
	                    <div class="ibox-content form_tienda">
				            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
							<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
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

							 	<div class="form-group m-b-md pull-left full-width m-t-sm">
		                            <label class="col-md-3 col-lg-2 control-label">Tienda Privada</label>
		                            <div class="col-md-4 col-lg-3">
			                            <div class="radio radio-inline radio-primary">
		                                	<input type="radio" name="privada" value="1" <?php if (isset($item['privada']) && $item['privada'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
			                            </div>
			                            <div class="radio radio-inline radio-primary">
                                        	<input type="radio" name="privada" value="null" <?php if ($item['privada'] != '1') echo 'checked="checked"'; ?>><label> No </label>
			                            </div>
		                            </div>
		                            <div class="col-md-5 col-lg-7">
			                            <p class="alert alert-primary pull-left">En caso que la tienda sea Privada, para poder ver el contenido, el usuario tendrá que loguearse, y ahí aparecerán sus datos de usuario y podrá hacer pedidos. En caso de que no sea Privada cualquier usuario podrá ver el contenido y comprar.</p>
		                            </div>
	                            </div>
								<div class="hr-line-dashed pull-left full-width"></div>

							 	<div class="form-group m-b-md pull-left full-width m-t-sm">
		                            <label class="col-md-3 col-lg-2 control-label">Productos en tienda</label>
		                            <div class="col-md-4 col-lg-3">
			                            <div class="radio radio-inline radio-primary">
		                                	<input type="radio" name="vista_productos" value="1" <?php if (isset($item['vista_productos']) && $item['vista_productos'] == '1') echo 'checked="checked"'; ?>> <label> <i class="fa fa-th-list"></i> Lista </label>
			                            </div>
			                            <div class="radio radio-inline radio-primary">
                                        	<input type="radio" name="vista_productos" value="0" <?php if (isset($item['vista_productos']) && $item['vista_productos'] == '0') echo 'checked="checked"'; ?>><label> <i class="fa fa-th-large"></i> Grilla </label>
			                            </div>
		                            </div>
		                            <div class="col-md-5 col-lg-7">
			                            <p class="alert alert-primary pull-left">El contenido se puede mostrar en forma de grilla, mostrando todos los productos separados por categorías, o bien en modo lista con todos los productos.</p>
		                            </div>
	                            </div>
								<div class="hr-line-dashed pull-left full-width"></div>
                        
							 	<div class="form-group m-b-md pull-left full-width m-t-sm">
		                            <label class="col-sm-2 control-label">Solicitar teléfono en pedido</label>
		                            <div class="col-sm-4">
			                            <div class="radio radio-inline radio-primary">
		                                	<input type="radio" name="solicitar_telefono" value="1" <?php if (isset($item['solicitar_telefono']) && $item['solicitar_telefono'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
			                            </div>
			                            <div class="radio radio-inline radio-primary">
                                        	<input type="radio" name="solicitar_telefono" value="0" <?php if (isset($item['solicitar_telefono']) && $item['solicitar_telefono'] == '0') echo 'checked="checked"'; ?>><label> No </label>
			                            </div>
		                            </div>
		                            <label class="col-sm-2 control-label">Solicitar email en pedido</label>
		                            <div class="col-sm-4">
			                            <div class="radio radio-inline radio-primary">
		                                	<input type="radio" name="solicitar_email" value="1" <?php if (isset($item['solicitar_email']) && $item['solicitar_email'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
			                            </div>
			                            <div class="radio radio-inline radio-primary">
                                        	<input type="radio" name="solicitar_email" value="0" <?php if (isset($item['solicitar_email']) && $item['solicitar_email'] == '0') echo 'checked="checked"'; ?>><label> No </label>
			                            </div>
		                            </div>
	                            </div>
	                            <div class="col-sm-12">
		                            <p class="alert alert-primary pull-left">Tengan en cuenta que pedir estos datos a vuestro cliente al momento de hacer la compra, es muy importante para acciones de marketing y comunicación futuras a través de herramientas disponibles en nuestra plataforma.  En caso de no solicitarlo, lamentablemente se perderá esa posibilidad. Es importante tener en cuenta, que los celulares actuales suelen guardar los datos completados, en consecuencia no siempre vuestro cliente tendra que volver a tipear todo, sino que el mismo telefono le hará una sugerencia de autocompletado.</p>
	                            </div>
								<div class="hr-line-dashed pull-left full-width"></div>
                        
								<div class="form-group m-b-md pull-left full-width m-t-sm">
								 	<label class="col-md-2 control-label">Leyenda en carro</label>
								 	<div class="col-sm-10">
								        <textarea name="leyenda" rows="7" class="full-width"><?php echo (isset($item['leyenda'])) ? $item['leyenda']: null; ?></textarea>
								        <p class="alert alert-primary pull-left">La leyenda se utiliza para aclarar cuestiones asociadas al pedido, por ejemplo: "Los precios pueden variar". Se mostrará en el carro de compras, luego del listado de productos. No es un campo obligatorio y debe tener menos de 250 caracteres contando espacios y signos de puntuación.</p>
									</div>
								</div>
								<div class="hr-line-dashed pull-left full-width"></div>

							 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 		<div class="col-sm-6">
		                            <label class="col-md-4 control-label">ID Bruler</label>
									<div class="col-md-8">
										<input type="text" name="bruler_id" class="form-control" value="<?php echo (isset($item['bruler_id'])) ? $item['bruler_id']: null; ?>">
									</div>
		                            <label class="col-md-4 control-label m-t-md">ClientID Bruler</label>
									<div class="col-md-8 m-t-md">
										<input type="text" name="bruler_client_id" class="form-control" value="<?php echo (isset($item['bruler_client_id'])) ? $item['bruler_client_id']: null; ?>">
									</div>
							 		</div>
		                            <div class="col-md-6">
			                            <p class="alert alert-primary pull-left">Esta opción es para configurar la importación de productos desde Bruler. Para poder realizar dicha importación es necesario que complete el campo ID Bruler con el RemoteID de Bruler y el campo ClientID Bruler con el ClientID de Bruler.</p>
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
			                    <div class="col-sm-6">
			                    	<div class="ibox-title bg-muted"><h5>Imagen Header</h5></div>
									<div class="ibox-content caja-imagen-tienda">
			                            <?php if(!empty($item['imagen'])) { ?>
		                            	<p>Imagen Actual</p>
		                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px; clear:both;"/>
										<br><br>
										<input type="file" name="imagen" class="form-control" style="width:auto;display:inline;max-width: 100%;">
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
										<input type="file" name="logo" class="form-control" style="width:auto;display:inline;max-width: 100%;	">
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
										<div class="input-group"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['url'])) ? str_replace(array('https://','/'), array('',''), $item['url']) : null; ?>"><span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Para poder usar esta función, tiene que tener contratado un Plan Pro, caso contrario no va a funcionar su personalización. Consulte por favor a nuestro whatsapp sobre los costos del mismo. Ingrese su dominio sin https://, por ejemplo: tienda.sudominio.com" title=""> <i class="fa fa-question"></i></span></div>
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