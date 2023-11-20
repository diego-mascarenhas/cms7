<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2><?php echo $this->lang->line('cms_users-tickets'); ?>Tickets</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tickets'); ?>"><?php echo $this->lang->line('cms_users-tickets'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('cms_users-agentes'); ?></strong>
	                    </li>
	                </ol>
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
	                                        <th><?php echo $this->lang->line('cms_users-grupo'); ?></th>
	                                        <th><?php echo $this->lang->line('cms_users-contacto'); ?></th>
	                                        <th><?php echo $this->lang->line('cms_users-area'); ?></th>
	                                        <th class="text-center">Nivel</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($agentes as $item) { ?>
		                                    <tr>
			                                    <td><?php echo $item['grupo']; ?></td>
			                                    <td>
				                                    <a href="<?php echo base_url('administracion/contactos/detalle/' . $item['id']) ?>"><?php echo $item['contacto']; ?></a>
				                                </td>
		                                        <td><?php echo $item['area']; ?></td>
		                                        <td class="text-center"><?php echo $item['nivel']; ?></td>
		                                    </tr>
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