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
	                        <strong>Categorías</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('configuracion/categorias/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar categoría</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped">
	                                    <thead>
	                                    <tr>
	                                        <th>Categoría</th>
	                                        <th>Tipo</th>
	                                        <th class="text-center">Cantidad</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($categorias as $categoria) { ?>
		                                    <tr>
		                                        <td>
			                                        <a href="<?php echo base_url('configuracion/categorias/detalle/'); ?><?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']; ?></a>
			                                        <br>
			                                        <small><?php echo $categoria['descripcion']; ?></small>
			                                    </td>
			                                    <td>
				                                    <?php echo $categoria['padre']; ?>
				                                    <br>
			                                    	<small><?php if (isset($categoria['valor'])) echo 'ID Tipo: ' . $categoria['id_tipo']; ?></small>
			                                    </td>
			                                    <td class="text-center"><?php echo $categoria['cantidad']; ?></td>
		                                        <td class="text-right"><?php if (isset($categoria['valor'])) echo $categoria['simbolo'] . $categoria['valor']; ?></td>
		                                        <td class="text-center"><span class="label <?php echo $categoria['estado_ui_class']; ?>"><?php echo $categoria['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="5"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>