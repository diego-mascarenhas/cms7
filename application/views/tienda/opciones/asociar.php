<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
	                    <a href="<?php echo base_url('tienda/productos/modificar/'.$item['id']); ?>">Producto <?php echo $item['titulo'];?> </a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/opciones/listado/'.$item['id']); ?>"><strong>Grupos de Opciones</strong> </a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
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
	                    <div class="ibox-title">
	                        <h5>Ingresar Grupo de Opci&oacute;n para <a href="<?php echo base_url('tienda/productos/modificar/'.$item['id']); ?>"><?php echo $item['titulo'];?> </a></h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<input type="hidden" name="id" value="<?php  echo $item['id']; ?>">
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Listado de Grupos</label>
                                <div class="col-sm-10">
                                <?php if(!empty($opciones)) { foreach($opciones as $opcion) { ?>	
								<div class="checkbox checkbox-primary">
									<input id="checkbox<?php echo $opcion['id'];?>" type="checkbox" name="relacionesgrupos[]" value="<?php echo $opcion['id'];?>" <?php if($gruposrelacionados){ foreach($gruposrelacionados as $grupos) { if($opcion['id'] == $grupos['id']) {echo ' checked';} } } ?>>
                                    <label for="checkbox<?php echo $opcion['id'];?>"><?php echo $opcion['opcion_grupo']; ?></label>
								</div>
				                <?php } } else { echo 'No se encontraron resultados';} ?>
				                </div>	
                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>
                    
                            		                            
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