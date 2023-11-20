<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2><?php echo $this->lang->line('cms_users-tickets'); ?></h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tickets'); ?>"><?php echo $this->lang->line('cms_users-tickets'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('cms_users-efectividad'); ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>				
			
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
	                <div class="col-lg-6">
		                <div class="ibox">
                            <div class="ibox-content text-center">
                                <h3 class="m-b-xxs"><?php echo $this->lang->line('cms_users-total-mes-pasado'); ?></h3>
                                <small>$<?php if (isset($total['anterior'])) { ?><?php echo $total['anterior']; ?><?php } else { ?>0<?php } ?></small>
                            </div>
                        </div>
	                </div>
	                <div class="col-lg-6">
		                <div class="ibox">
                            <div class="ibox-content text-center">
                                <h3 class="m-b-xxs"><?php echo $this->lang->line('cms_users-total-este-mes'); ?></h3>
                                <small>$<?php if (isset($total['actual'])) { ?><?php echo $total['actual']; ?><?php } else { ?>0<?php } ?></small>
                            </div>
                        </div>
	                </div>
				</div>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th><?php echo $this->lang->line('cms_users-asunto'); ?></th>
	                                        <th class="text-center"><?php echo $this->lang->line('cms_users-fecha-ticket'); ?></th>
	                                        <th class="text-center"><?php echo $this->lang->line('cms_users-agentes'); ?></th>
	                                        <th class="text-center"><?php echo $this->lang->line('cms_users-efectividad'); ?></th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php if (isset($tickets)) { ?>
			                                	<?php foreach ($tickets as $item) { ?>
			                                    <tr>
				                                    <td><a href="<?php echo base_url('tickets/detalle/' . $item['id']) ?>"><?php echo $item['asunto']; ?></a>
				                                    <td class="text-center"><?php echo $item['inicio']; ?></td>
			                                        <td class="text-center"><?php echo $item['agentes_cantidad']; ?></td>
			                                        <td class="text-center"><?php echo $item['efectividad']; ?>%</td>
			                                    </tr>
			                                    <? } ?>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="4"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>