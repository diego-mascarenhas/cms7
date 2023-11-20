<style>
.btn_eliminar_popup { border:0; background:none;}
.codigo { width:80px;}
.precios { width:120px;}
.precios span {float:left;}
.precios input { width:90%; float:right;}
</style>
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/carrito/productos">Productos</a>
                    </li>
                    <li class="active">
                        <strong>Actualizaci&oacute;n masiva</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a href="<?php echo base_url('cms-v2/carrito/productos/ingresar/'); ?>" class="btn btn-primary">Ingresar</a>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <?php if ($this->input->get('actualizacion')) { ?>
            <div class="row">
				<div class="col-md-12">
				<?php if ($this->input->get('actualizacion') == 'ok') { ?>
					<div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                        <p>El contenido fue modificado con &eacute;xito.</p>
					</div>
					<?php } else { ?>
					<div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                        <p>Hubo un error en la modificaci&oacute;n del contenido.</p>
					</div>
					<?php } ?>
				</div>
            </div>
            <?php } ?>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                	<div class="ibox-title"><h5>Listado de Productos</h5></div>
                    <div class="ibox-content pull-left full-width">
                    	<div class="col-sm-4">
			                <?php if(isset($categorias)) { ?>
			                <form name="filtrar" method="post" action="<?php echo base_url('cms-v2/carrito/productos/filtrar_masivo'); ?>" >
					   			<?php echo form_dropdown('id_categoria', $categorias, ($this->input->post('id_categoria')) ? $this->input->post('id_categoria') : null, array('class'=>'form-control p-md pull-left', 'style'=>'width:200px')); ?> <button type="submit" class="btn btn-primary btn-sm m-l-sm"><i class="fa fa-filter"></i> Filtrar</button>
			                </form>
			                <?php } ?>
                    	</div>
                    </div>
                    
                   <div class="ibox-content">
                    <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover dataTables-example">
	                    <thead>
		                    <tr>
		                        <th>Categor&iacute;a</th>
		                        <th>C&oacute;digo</th>
		                        <th>Producto</th>
		                        <th>Observaciones</th>
		                        <th>Precio</th>
		                        <th>Precio Oferta</th>
		                        <th>Estado</th>
		                        <th>Destacado</th>
		                    </tr>
	                    </thead>
	                    <tbody>
		                   <form name="editar" class="" method="post" action="<?php echo ($this->input->post('id_categoria')) ? base_url('cms-v2/carrito/productos/actualizacion_masiva/'.$this->input->post('id_categoria')) : base_url('cms-v2/carrito/productos/actualizacion_masiva/'.$this->uri->segment(5)); ?>">
		                 <?php 
		                   if($listado) 
		                   { 
			                    foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
		                   			<input type="hidden" name="id[]" value="<?php echo $lista['id']; ?>">
		                   			<input type="hidden" name="id_producto[]" value="<?php echo $lista['id_producto']; ?>">
									<td class="categoria"><?php echo form_dropdown('id_categoria[]', $categorias, (isset($lista['id_categoria'])) ? $lista['id_categoria'] : null, array('class'=>'form-control width-auto pull-left')); ?></td>
									<td class="codigo"><input type="text" name="codigo[]" class="form-control required" value="<?php echo $lista['codigo']; ?>"></td>
									<td class="titulo"><input type="text" name="titulo[]" class="form-control required" value="<?php echo $lista['titulo']; ?>"></td>
									<td class="contenido1"><input type="text" name="contenido1[]" class="form-control required" value="<?php echo $lista['contenido1']; ?>"></td>
									<td class="precios"><span>$ <input type="text" name="precio[]" class="form-control " value="<?php echo $lista['precio'];?>"></td>
									<td class="precios"><span>$ <input type="text" name="precio_oferta[]" class="form-control" value="<?php echo $lista['precio_oferta'];?>"></td>
									<td class="estado">
										<select name="estado[]" class="form-control width-auto pull-left">
											<option value="1"<?php echo ($lista['estado'] == 1) ? ' selected' : null; ?>>Inactivo</option>
											<option value="2"<?php echo ($lista['estado'] == 2) ? ' selected' : null; ?>>Activo</option>
										</select>
									</td>
									<td class="estado">
										<select name="destacado[]" class="form-control width-auto pull-left">
											<option value="0"<?php echo ($lista['destacado'] == 0) ? ' selected' : null; ?>>No</option>
											<option value="1"<?php echo ($lista['destacado'] == 1) ? ' selected' : null; ?>>S&iacute;</option>
										</select>
									</td>
		                    	</tr>
							<?php } ?>	
						<?php } ?>
	                    </tbody>
	                   </table>
                    </div>
                    <div class="form-group m-b-lg">
                        <div class="col-sm-12">
                            <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
