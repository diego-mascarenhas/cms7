<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Configuración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('configuracion'); ?>">Configuración</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('configuracion/categorias'); ?>">Categorías</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
			            <a class="btn btn-white btn-sm" href="<?php echo base_url('administracion/servicios?id_categoria=' . $detalle['id']); ?>"><i class="fa fa-info-circle"></i> Ver servicios asociados</a>
                        <a href="<?php echo base_url('configuracion/categorias/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar categoría</a>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        
		        <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Categoría</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['categoria']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Padre</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['padre']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Moneda</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['moneda']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Valor</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php if (isset($detalle['valor'])) echo $detalle['simbolo'] . $detalle['valor']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Descuento</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['descuento']; ?>%</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
		                <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Frecuencia</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['frecuencia']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Convertir</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo (isset($detalle['convertir'])) ? 'Si' : 'No'; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
		                <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Tipo</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['id_tipo']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Orden</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['orden']; ?></div>
	                        </div>
	                    </div>
	                </div>
	            </div>	            
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Descripción</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['descripcion']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Características</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['caracteristicas']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	        </div>