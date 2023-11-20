<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Facturas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('variable_name'); ?>Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('variable_name'); ?>Mi cuenta</a>
	                    </li>
	                    <li>
	                        <strong>Facturas</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold"><?php echo $this->lang->line('variable_name'); ?>Sin facturas</h3>
                    <div class="error-desc">
                        <?php echo $this->lang->line('variable_name'); ?>No se han creado facturas hasta el momento
                    </div>
                </div>
            </div>