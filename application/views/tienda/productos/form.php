<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Productos</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/productos'); ?>">Productos</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($item['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5 class="full-width"><?php echo (!isset($item['id'])) ? 'Ingresar' : 'Modificar'; ?> Producto
                            <?php if(isset($item['id'])) { ?><a href="<?php echo base_url('tienda/opciones/listado/'.$item['id']); ?>" class="btn btn-primary btn-sm pull-right" style="margin-top: -5px;margin-bottom: 3px;"><i class="fa fa-check-square-o"></i> Opciones de productos</a><?php } ?></h5>
                        </div>
                        <div class="ibox-content">

				            <?php echo form_open_multipart(null, array('id'=>'form', 'class'=>'form-horizontal wizard-big')); ?>
							<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
							<input type="hidden" name="id_tienda" value="<?php echo $tienda['id']; ?>">
			
								<h1>Categorías</h1>
								<fieldset>
								    <h2>Seleccione categoría de producto</h2>
								    <div class="row">
								        <div class="col-lg-8">
											<div class="form-group">
											<?php if ($categorias) { ?>
											<div class="col-sm-4">
											    <?php echo form_dropdown('id_categoria', $categorias, (isset($item['id_categoria'])) ? $item['id_categoria'] : null, 'class="required form-control m-b"'); ?>
											</div>
											<?php } ?>
											<label class="col-sm-2 control-label"><a href="<?php echo base_url('tienda/categorias/ingresar?productos=1');?>" class="btn btn-primary btn-sm" type="submit">Ingresar Categoría</a></label>
											</div>
								        </div>
                                        <div class="col-lg-4">
                                            <div class="text-center">
                                            </div>
                                        </div>

								    </div>
                                </fieldset>


                                <h1>Detalle de Producto</h1>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-lg-12">
										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Descripción de Producto*</label>
											 	<div class="col-sm-4">
													<input type="text" name="titulo" class="form-control required" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"></div>
											 	<label class="col-md-2 control-label">Descripción corta de Producto (para que aparezca bien en el listado del carrito, hasta 28 caracteres)*</label>
											 	<div class="col-sm-4">
													<input type="text" name="uri" class="form-control" value="<?php echo (isset($item['uri'])) ? $item['uri']: null; ?>"></div>
			                                </div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-md-2 control-label">Observaciones </label>
											 	<div class="col-sm-4">
													<input type="text" name="contenido1" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?>"></div>
											 	<label class="col-md-2 control-label">C&oacute;digo (SKU) </label>
											 	<div class="col-sm-4">
													<input type="text" name="codigo" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['codigo']: null; ?>"></div>
                                            </div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
					                            <label class="col-sm-2 control-label">Destacado*</label>
					                            <div class="col-sm-4">
						                            <div class="radio radio-inline radio-primary">
					                                	<input type="radio" name="destacado" value="1" <?php if (isset($item['destacado']) && $item['destacado'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
						                            </div>
						                            <div class="radio radio-inline radio-primary">
			                                        	<input type="radio" name="destacado" value="0" <?php if ((!isset($item['destacado'])) || (isset($item['destacado']) && $item['destacado'] == '0')) echo 'checked="checked"'; ?>><label> No </label>
						                            </div>
					                            </div>
					                            <label class="col-sm-2 control-label">Estado *</label>
					                            <div class="col-sm-4">
						                            <div class="radio radio-inline radio-primary">
					                                	<input type="radio" name="estado" value="3" <?php if ((!isset($item['estado'])) || (isset($item['estado']) && $item['estado'] == '3')) echo 'checked="checked"'; ?>> <label> Activo </label>
						                            </div>
						                            <div class="radio radio-inline radio-primary">
			                                        	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
						                            </div>
					                            </div>
                                            </div>

										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
					                            <label class="col-sm-2 control-label">Imagen Producto</label>
							                    <div class="col-sm-4">
						                            <?php if(!empty($item['imagen'])) { ?>
					                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:70px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            <?php } ?>
												       <input type="file" name="imagen" class="form-control" />
							                    </div>
					                            <label class="col-sm-2 control-label">Mostrar Galería *</label>
					                            <div class="col-sm-2">
						                            <div class="radio radio-inline radio-primary">
					                                	<input type="radio" name="galeria" value="1" <?php if (isset($item['galeria']) && $item['galeria'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label>
						                            </div>
						                            <div class="radio radio-inline radio-primary">
			                                        	<input type="radio" name="galeria" value="0" <?php if ((!isset($item['galeria'])) || (isset($item['galeria']) && $item['galeria'] == '0')) echo 'checked="checked"'; ?>><label> No </label>
						                            </div>
					                            </div>
										 	</div>
                                        </div>
                                    </div>
                                </fieldset>

                                <h1>Precios</h1>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-lg-12">
										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
											 	<label class="col-sm-3 control-label">Precio <?php echo $tienda['simbolo'];?> *</label>
											 	<div class="col-sm-3">
													<input type="text" name="precio" class="form-control required" value="<?php echo (isset($item['precio'])) ? $item['precio']: null; ?>"></div>
											 	<label class="col-sm-3 control-label">Precio con Oferta <?php echo $tienda['simbolo'];?> </label>
											 	<div class="col-sm-3">
													<input type="text" name="precio_oferta" class="form-control" value="<?php echo (isset($item['precio_oferta'])) ? $item['precio_oferta']: null; ?>"></div>
			                                </div>

										 	<?php if ($tienda['id_rubro'] == 1 || $tienda['id_rubro'] == 2) {?>
										 	<div class="form-group m-b-md pull-left full-width m-t-sm">
					                            <label class="col-sm-3 control-label">Precio Menú Digital <?php echo $tienda['simbolo'];?> *</label>
					                            <div class="col-sm-3">
													<input type="text" name="precio_local" class="form-control" value="<?php echo (isset($item['precio_local'])) ? $item['precio_local']: null; ?>"></div>
											 	<label class="col-sm-3 control-label">Precio Menú Digital con Oferta<?php echo $tienda['simbolo'];?> *</label>
											 	<div class="col-sm-3">
													<input type="text" name="precio_local_oferta" class="form-control" value="<?php echo (isset($item['precio_local_oferta'])) ? $item['precio_local_oferta']: null; ?>"></div>
                                            </div>
										 	<?php }?>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    </div>

                </div>
            </div>



    <script>
        $(document).ready(function(){
            $("#form").steps({
                bodyTag: "fieldset",
                enableCancelButton: false,
                enableAllSteps: true,
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }

        
                    var form = $(this);

                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }

                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";

                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
			    /* Labels */
			    labels: {
			        cancel: "Cancelar",
			        finish: "Guardar",
			        next: "Siguiente",
			        previous: "Anterior",
			        loading: "Cargando ..."
			    },

                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2)
                    {
                        $(this).steps("next");
				          var $input = $('<li aria-hidden="false"><a href="<?php echo base_url('tienda/productos'); ?>" role="menuitem">Cancelar</a></li>');
				          $input.appendTo($('ul[aria-label=Pagination]'));
                    }

                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);

                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";

                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);

                    // Submit form input
                    form.submit();
                }

            }).validate({
                        errorPlacement: function (error, element)
                        {
                            element.before(error);
                        },
						rules:
				        {
				          destacado:{ required:true }
				        },
				        messages: {
							required:"Este campo es obligatorio.",
							titulo: "Ingrese un nombre",
							precio: "Ingrese un precio"
						}
				});
				
       });
    </script>               