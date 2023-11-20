<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>                        
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/carrito/productos">Productos</a>
                    </li>
                    <li class="active">
                         <strong><?php echo (empty($item['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
        </div>
            
	        <div class="wrapper wrapper-content animated fadeInRight p-b-sm">
	            <div class="row">
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
		                    <div class="ibox-title"><h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nuevo'; ?> Producto</h5>
		                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
		                    </div>
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_producto" value="<?php if (isset($item['id'])) { echo $item['id_producto']; } ?>">
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <h2>Datos del producto</h2>
	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">Categor&iacute;a</label>
				                    <div class="col-sm-3">
									    <?php echo form_dropdown('id_categoria', $categorias, (isset($item['id_categoria'])) ? $item['id_categoria'] : null, 'class="required form-control m-b"'); ?>
				                    </div>
	                            </div>

	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">Nombre</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
					                    	<input type="text" name="titulo" class="form-control" value="<?php if (isset($item['titulo'])) { echo $item['titulo']; } else { if ($this->input->post('titulo')) { echo $this->input->post('titulo'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio del producto. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></span></div>
				                    </div>
		                            <label class="col-sm-2 control-label">Observaciones</label>
				                    <div class="col-sm-3"><textarea name="contenido1" class="form-control"><?php if (isset($item['contenido1'])) { echo $item['contenido1']; } else { if ($this->input->post('contenido1')) { echo $this->input->post('contenido1'); } }?></textarea></div>
				                    	<button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca Activo se mostrar&aacute; en la web, sino no. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
	                            </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">Destacado</label>
				                    <div class="col-sm-3">
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="destacado" value="1" <?php if (isset($item['destacado']) && $item['destacado'] == '1') echo 'checked="checked"'; ?>> <label> S&iacute; </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="destacado" value="0" <?php if (isset($item['destacado']) && $item['destacado'] == '0') echo 'checked="checked"'; ?>><label> No </label>
	                                    	<button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca S&iacute; se mostrar&aacute; en el home de la web. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
			                            </div>
									</div>
				                    <label class="text-right col-sm-2 control-label">Estado</label>
				                    <div class="col-sm-3">
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="2" <?php if (isset($item['estado']) && $item['estado'] == '2') echo 'checked="checked"'; ?>> <label> Activo </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
	                                    	<button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si marca Activo se mostrar&aacute; en la web, sino no. Campo obligatorio." title=""> <i class="fa fa-question"></i></button>
			                            </div>
									</div>
	                            </div>

	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">Precio</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <input type="text" name="precio" class="form-control" value="<?php if (isset($item['precio'])) { echo $item['precio']; } else { if ($this->input->post('precio')) { echo $this->input->post('precio'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio del producto. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></span></div>
                                        </div>
		                            <label class="col-sm-2 control-label">Precio con oferta</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
					                    	<input type="text" class="form-control" name="precio_oferta" value="<?php if (isset($item['precio_oferta'])) { echo $item['precio_oferta']; } else { if ($this->input->post('precio_oferta')) { echo $this->input->post('precio_oferta'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio oferta del producto. Campo no obligatorio." title=""> <i class="fa fa-question"></i></button></span>
                                        </div>
				                    </div>
	                            </div>

	                            <div class="form-group pull-left full-width">
		                            <label class="col-sm-2 control-label">Precio adicional</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <input type="text" name="precio_adicional" class="form-control" value="<?php if (isset($item['precio_adicional'])) { echo $item['precio_adicional']; } else { if ($this->input->post('precio_adicional')) { echo $this->input->post('precio_adicional'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio adicional del producto. Campo no obligatorio." title=""> <i class="fa fa-question"></i></button></span></div>
		                            </div>
		                            <label class="col-sm-2 control-label">Precio adicional con oferta</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
					                    	<input type="text" class="form-control" name="precio_adicional_oferta" value="<?php if (isset($item['precio_adicional_oferta'])) { echo $item['precio_adicional_oferta']; } else { if ($this->input->post('precio_adicional_oferta')) { echo $this->input->post('precio_adicional_oferta'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio adicional con oferta del producto. Campo no obligatorio." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
				                    </div>
	                            </div>

	                            <div class="form-group pull-left full-width">
				                    <label class="text-right col-sm-2 control-label">C&oacute;digo</label>
				                    <div class="col-sm-3">
                                        <div class="input-group">
	                                        <input type="text" name="codigo" class="form-control" value="<?php if (isset($item['codigo'])) { echo $item['codigo']; } else { if ($this->input->post('codigo')) { echo $this->input->post('codigo'); } }?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio del producto. Campo obligatorio." title=""> <i class="fa fa-question"></i></button></span></div>
                                    </div>
									<label class="text-right col-sm-2 control-label">Imagen</label>
					                <div class="col-sm-3">
			                            <?php if(!empty($item['imagen'])) { ?>
		                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
		                            <?php } ?>
                                        <div class="input-group">
	                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del producto, debe tener 180x230 píxeles o proporcionales mayores." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
					                </div>
						         </div>
		                                            			                            
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
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>		        		        