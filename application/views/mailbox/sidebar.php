<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

						<div class="ibox float-e-margins">
		                    <div class="ibox-content mailbox-content">
		                        <div class="file-manager">
<!-- 		                            <a class="btn btn-block btn-primary compose-mail" href="mail_compose.html">Compose Mail</a> -->
<!-- 		                            <div class="space-25"></div> -->
<!--
		                            <h5>Folders</h5>
		                            <ul class="folder-list m-b-md" style="padding: 0">
		                                <li><a href="<?php echo base_url('mailbox'); ?>"> <i class="fa fa-inbox "></i> Inbox <span class="label label-warning pull-right">16</span></a></li>
		                                <li><a href="<?php echo base_url('mailbox'); ?>"> <i class="fa fa-envelope-o"></i> Send Mail</a></li>
		                                <li><a href="<?php echo base_url('mailbox'); ?>"> <i class="fa fa-certificate"></i> Important</a></li>
		                                <li><a href="<?php echo base_url('mailbox'); ?>"> <i class="fa fa-file-text-o"></i> Drafts</a></li>
		                                <li><a href="<?php echo base_url('mailbox'); ?>"> <i class="fa fa-trash-o"></i> Trash <span class="label label-danger pull-right">2</span></a></li>
		                            </ul>
-->
		                            <h5>Importancia</h5>
		                            <ul class="category-list" style="padding: 0">
		                                <li><a href="<?php echo base_url('mailbox?prioridad=5'); ?>"> <i class="fa fa-circle text-navy"></i> Información</a></li>
		                                <li><a href="<?php echo base_url('mailbox?prioridad=4'); ?>"> <i class="fa fa-circle text-info"></i> Normal</a></li>
		                                <li><a href="<?php echo base_url('mailbox?prioridad=3'); ?>"> <i class="fa fa-circle text-primary"></i> Alta</a></li>
		                                <li><a href="<?php echo base_url('mailbox?prioridad=2'); ?>"> <i class="fa fa-circle text-warning"></i> Urgente</a></li>
		                                <li><a href="<?php echo base_url('mailbox?prioridad=1'); ?>"> <i class="fa fa-circle text-danger"></i> Crítico</a></li>
		                            </ul>
		
		                            <h5 class="tag-title">Filtros</h5>
		                            <ul class="tag-list" style="padding: 0">
		                                <li><a href="<?php echo base_url('mailbox?filter=Excessive resource usage'); ?>"><i class="fa fa-tag"></i> Excessive resource usage</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=Suspicious process running'); ?>"><i class="fa fa-tag"></i> Suspicious process running</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=Log Scanner Report'); ?>"><i class="fa fa-tag"></i> Log Scanner Report</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=blocked'); ?>"><i class="fa fa-tag"></i> blocked</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=cxs Scan'); ?>"><i class="fa fa-tag"></i> cxs Scan</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=RELAY Alert'); ?>"><i class="fa fa-tag"></i> RELAY Alert</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=root access alert'); ?>"><i class="fa fa-tag"></i> root access alert</a></li>
		                                <li><a href="<?php echo base_url('mailbox?filter=Email queue size alert'); ?>"><i class="fa fa-tag"></i> Email queue size alert</a></li>
		                            </ul>
		                            <div class="clearfix"></div>
		                        </div>
		                    </div>
		                </div>