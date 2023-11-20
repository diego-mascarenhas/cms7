<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2><?php echo $this->lang->line('cms_users-tickets'); ?></h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('cms_users-tickets'); ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold"><?php echo $this->lang->line('cms_users-sin-tickets'); ?></h3>
                    <div class="error-desc">
                        <?php echo $this->lang->line('cms_users-sin-tickets-info'); ?>
                        <br/><a href="<?php echo base_url('tickets/ingresar'); ?>" class="btn btn-primary m-t"><?php echo $this->lang->line('cms_users-create-ticket'); ?></a>
                    </div>
                </div>
            </div>