<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Notas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Notas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('notas/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar nota</a>
                    </div>
                </div>
	        </div>
			
			<div class="row">
	            <div class="col-lg-12">
	                <div class="wrapper wrapper-content animated fadeInRight">
		                <?php if (isset($paginado)) echo $paginado; ?>
	                    <ul class="notes">
		                    <?php foreach ($notas as $nota) { ?>
	                        <li>
	                            <div>
	                                <small><?php echo $nota['contacto']; ?>  <?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
	                                <h4><?php echo $nota['titulo']; ?></h4>
	                                <p><?php echo ellipsize($nota['descripcion'], 100); ?></p>
	                                <a href="<?php echo base_url($nota['uri'] . '/detalle/' . $nota['id_referencia']); ?>"><i class="fa fa-link"></i> <?php echo $nota['item']; ?></a>
	                            </div>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                </div>
	            </div>
	        </div>