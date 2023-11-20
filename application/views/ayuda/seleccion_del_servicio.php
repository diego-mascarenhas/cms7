<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Mesa de ayuda</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
                        <li><a href="<?php echo base_url('ayuda'); ?>">Ayuda</a></li>
                        <li class="active"><strong><?php echo urldecode($detalle['problema']); ?></strong></li>
                    </ol>
                </div>
            </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">		            
	            <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <?php if (isset($servicios)) { ?>
				                <p>Seleccione el servicio con el cual tiene inconvenientes o quiere configurar.</p>
				                <ul>
				                    <?php foreach ($servicios as $servicio) { ?>
				                    <li><a href="<?php echo base_url('ayuda/detalle-del-servicio/' . $detalle['problema'] . '/' . $servicio['id']); ?>"><?php echo $servicio['domain']; ?></a></li>
				                    <?php } ?>
				                </ul>
				                <?php } else { ?>
				                	<p>No encontramos servicios de hosting asociados a su empresa.</p>
				                <?php } ?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>