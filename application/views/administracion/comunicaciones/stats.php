<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/comunicaciones'); ?>">Comunicaciones</a>
	                    </li>
	                    <li class="active">
	                        <strong>Estadísticas</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper wrapper-content animated fadeInRight">
                        <div class="ibox-content forum-container">
                            <?php foreach ($lista as $item) { ?>
                            <div class="forum-item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo $item['tipo']; ?>

                                        <div class="forum-sub-title">
                                            <?php echo $item['asunto']; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-1 forum-info">
                                        <span class="views-number"><?php echo $item['enviar']; ?></span>

                                        <div>
                                            <small>Enviar</small>
                                        </div>
                                    </div>

                                    <div class="col-md-1 forum-info">
                                        <span class="views-number"><?php echo $item['abiertos']; ?></span>

                                        <div>
                                            <small>Abiertos</small>
                                        </div>
                                    </div>

                                    <div class="col-md-1 forum-info">
                                        <span class="views-number"><?php echo $item['total']; ?></span>

                                        <div>
                                            <small>Total</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>