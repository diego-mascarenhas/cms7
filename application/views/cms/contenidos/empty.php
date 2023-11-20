<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Sitemap</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Sitemap</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">Sin contenido</h3>
                    <div class="error-desc">
                        No se ha creado ningún contenido hasta el momento.
                        <?php if (isset($detalle['categoria'])) { ?>
                        <br/><a href="<?php echo base_url('cms/ingresar?categoria=' . $detalle['categoria']); ?>" class="btn btn-primary m-t">Crear contenido</a>
                        <?php } else { ?>
                        <br/>Por favor elija una categoría desde el menú para poder hacerlo.
                        <?php } ?>
                    </div>
                </div>
            </div>