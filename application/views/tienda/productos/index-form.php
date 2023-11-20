<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
<style>
.dataTables-listado{ height:auto !important; }
.dataTables-listado th { text-align:center !important; }
.dataTables-listado td { width:auto !important; }
.dataTables-listado td.precios { min-width:140px !important;width:140px !important; valign:middle !important; }
.dataTables-listado td.precios span { width:10px !important; float:left !important; margin-top:5px;}
.dataTables-listado td.precios input { width:100px !important; float:left !important;text-align:right !important; }
.dataTables-listado td.estado { width:70px !important; }
.dataTables-listado td.categoria { width:170px !important; }
.dataTables-listado td.titulo { min-width:340px !important; }
.dataTables-listado td.codigo { max-width:90px !important; }
</style>
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Tienda</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/productos'); ?>">Productos</a>
	                    </li>
	                    <li>
	                        <strong>Actualizaci&oacute;n masiva</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="https://pedimosfacil.com/<?php echo $tienda['titulo']; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye"></i> Ver Tienda</a>
                    </div>
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
                <?php if ($this->input->get('actualizacion')) { ?>
	            <div class="row">
					<div class="col-md-12">
					<?php if ($this->input->get('actualizacion') == 'ok') { ?>
						<div class="alert alert-success alert-dismissable">
	                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	                        <p>El contenido fue modificado con &eacute;xito.</p>
						</div>
						<?php } else { ?>
						<div class="alert alert-danger alert-dismissable">
	                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	                        <p>Hubo un error en la modificaci&oacute;n del contenido.</p>
						</div>
						<?php } ?>
					</div>
	            </div>
                <?php } ?>

	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de Productos para Actualizaci&oacute;n Masiva</h5></div>
		                    <div class="ibox-content pull-left full-width">
		                    	<div class="col-sm-12">
					                <?php if(isset($categorias)) { ?>
					                <form name="filtrar" method="post" action="<?php echo base_url('tienda/productos/filtrar-masivo'); ?>">
							   			<?php echo form_dropdown('id_categoria', array('0' => ' Todas las categorías ') + $categorias, ($this->input->post('id_categoria')) ? $this->input->post('id_categoria') : $this->uri->segment(4), array('class'=>'form-control m-b p-md width-auto pull-left')); ?> <button type="submit" class="btn btn-primary btn-sm m-l-sm"><i class="fa fa-filter"></i> Filtrar</button>
					                </form>
					                <?php } ?>
		                    	</div>
		                    </div>
		                    
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <div style="height:50vh !important; overflow-y: scroll !important; ">
				                    <table class="table table-striped table-bordered dataTables-listado">
					                    <thead>
					                    <tr>
					                        <th>Imagen</th>
					                        <th>Categoría</th>
					                        <th>Producto</th>
					                        <th>C&oacute;digo</th>
					                        <th>Precio</th>
					                        <th>Precio Oferta</th>
										 	<?php if ($tienda['id_rubro'] == 1 || $tienda['id_rubro'] == 2) {?>
					                        <th>Precio Menú</th>
					                        <th>Precio Menú Oferta</th>
										 	<?php }?>
					                        <th>Estado</th>
					                        <th>Destacado</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
					                   <form name="editar" class="" method="post" action="<?php echo ($this->input->post('id_categoria')) ? base_url('tienda/productos/actualizacion_masiva/'.$this->input->post('id_categoria')) : base_url('tienda/productos/actualizacion_masiva/'.$this->uri->segment(4)); ?>">

						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
						                   			<input type="hidden" name="id[]" value="<?php echo $lista['id']; ?>">
													<td class="estado"><img src="<?php echo ($lista['imagen']) ? base_url('/multimedia/thumbs/'.$lista['imagen']) : 'https://app.pedimosfacil.com/v2/assets/images/no-disponible.jpg';?>" title="" alt="" class="listados_miniatura"></td>
													<td class="categoria"><?php echo form_dropdown('id_categoria[]', $categorias, (isset($lista['id_categoria'])) ? $lista['id_categoria'] : null, array('class'=>'form-control width-auto pull-left')); ?></td>
													<td class="titulo"><input type="text" name="titulo[]" class="form-control required" value="<?php echo $lista['titulo']; ?>"></td>
													<td class="codigo"><input type="text" name="codigo[]" class="form-control required" value="<?php echo $lista['codigo']; ?>"></td>
													<td class="precios"><span><?php echo $tienda['simbolo'];?></span> <input type="text" name="precio[]" class="form-control " value="<?php echo $lista['precio'];?>"></td>
													<td class="precios"><span><?php echo $tienda['simbolo'];?></span> <input type="text" name="precio_oferta[]" class="form-control" value="<?php echo $lista['precio_oferta'];?>"></td>
												 	<?php if ($tienda['id_rubro'] == 1 || $tienda['id_rubro'] == 2) {?>
							                        <td class="precios"><span><?php echo $tienda['simbolo'];?></span> <input type="text" name="precio_local[]" class="form-control" value="<?php echo $lista['precio_local'];?>"></td>
							                        <td class="precios"><span><?php echo $tienda['simbolo'];?></span> <input type="text" name="precio_local_oferta[]" class="form-control" value="<?php echo $lista['precio_local_oferta'];?>"></td>
												 	<?php }?>

													<td class="estado">
														<select name="estado[]" class="form-control width-auto pull-left">
															<option value="1"<?php echo ($lista['id_estado'] == 1) ? ' selected' : null; ?>>Inactivo</option>
															<option value="3"<?php echo ($lista['id_estado'] == 3) ? ' selected' : null; ?>>Activo</option>
														</select>
													</td>
													<td class="estado">
														<select name="destacado[]" class="form-control width-auto pull-left">
															<option value="0"<?php echo ($lista['destacado'] == 0) ? ' selected' : null; ?>>No</option>
															<option value="1"<?php echo ($lista['destacado'] == 1) ? ' selected' : null; ?>>Sí</option>
														</select>
													</td>
						                    	</tr>
											<?php } ?>	
										<?php } ?>

					                    </tbody>
				                    </table>
						          </div>
		                            <div class="form-group m-t-md">
		                                <div class="col-sm-12">
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