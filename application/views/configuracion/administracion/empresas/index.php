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
	                        Administración
	                    </li>
	                    <li class="active">
	                        <strong>Empresas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle" aria-expanded="false">Ingresar configuración <span class="caret"></span></button>
                            <ul class="dropdown-menu">
	                            <?php if (isset($tipos)) { ?>
									<?php foreach ($tipos as $key => $value) { ?>	                            
	                                <li><a href="/configuracion/ingresar/<?php echo $key; ?>?id_empresa=<?php echo $detalle['id_empresa'];?>&id_tipo=<?php echo $key;?>"><?php echo $value; ?></a></li>
									<?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Empresa</th>
	                                        <th>Tipo</th>
	                                        <th>Valor</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($config)) { ?>
		                                	<?php foreach ($config as $item) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $item['id_empresa']; ?>"><?php echo $item['empresa']; ?></a></td>
		                                        <td><?php echo $item['tipo']; ?></td>
		                                        <td><?php echo $item['value']; ?></td>
		                                    </tr>
											<? } ?>
											<?php } else { ?>
											<tr>
		                                        <td colspan="3">Todavía no se definieron parámetros de configuración.</td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="3"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>