<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Correo</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('mailbox'); ?>">Correo</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
		    </div>
		
		    <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-2">
		                <?php include('sidebar.php'); ?>
		            </div>
		
		            <div class="col-lg-10 animated fadeInRight">
		                <div class="mail-box-header">
		                    <div class="pull-right tooltip-demo">
<!--
		                        <a href="mail_compose.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Reply">Reply</a>
		                        <a href="#" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Print email"><i class="fa fa-print"></i> </a>
-->
		                        <a href="<?php echo base_url('mailbox/eliminar/' . $detalle['id']); ?>" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Eliminar"><i class="fa fa-trash-o"></i> </a>
		                    </div>
		
		                    <h2>Ver Mensaje</h2>
		
		                    <div class="mail-tools tooltip-demo m-t-md">
		                        <h3><span class="font-noraml">Asunto:</span> <?php echo $detalle['subject']; ?></h3>
		
		                        <h5><span class="pull-right font-noraml"><?php echo formatear_fecha($detalle['enviado'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></span> <span class="font-noraml">De:</span> <?php echo $detalle['fromaddress']; ?></h5>
		                    </div>
		                </div>
		
		                <div class="mail-box">
		                    <div class="mail-body">
		                        <?php echo nl2br($detalle['body']); ?>
		                    </div>
		                    <!--
		                            <div class="mail-attachment">
		                                <p>
		                                    <span><i class="fa fa-paperclip"></i> 2 attachments - </span>
		                                    <a href="#">Download all</a>
		                                    |
		                                    <a href="#">View all images</a>
		                                </p>
		        
		                                <div class="attachment">
		                                    <div class="file-box">
		                                        <div class="file">
		                                            <a href="#">
		                                                <span class="corner"></span>
		        
		                                                <div class="icon">
		                                                    <i class="fa fa-file"></i>
		                                                </div>
		                                                <div class="file-name">
		                                                    Document_2014.doc
		                                                    <br/>
		                                                    <small>Added: Jan 11, 2014</small>
		                                                </div>
		                                            </a>
		                                        </div>
		        
		                                    </div>
		                                    <div class="file-box">
		                                        <div class="file">
		                                            <a href="#">
		                                                <span class="corner"></span>
		        
		                                                <div class="image">
		                                                    <img alt="image" class="img-responsive" src="img/p1.jpg">
		                                                </div>
		                                                <div class="file-name">
		                                                    Italy street.jpg
		                                                    <br/>
		                                                    <small>Added: Jan 6, 2014</small>
		                                                </div>
		                                            </a>
		        
		                                        </div>
		                                    </div>
		                                    <div class="file-box">
		                                        <div class="file">
		                                            <a href="#">
		                                                <span class="corner"></span>
		        
		                                                <div class="image">
		                                                    <img alt="image" class="img-responsive" src="img/p2.jpg">
		                                                </div>
		                                                <div class="file-name">
		                                                    My feel.png
		                                                    <br/>
		                                                    <small>Added: Jan 7, 2014</small>
		                                                </div>
		                                            </a>
		                                        </div>
		                                    </div>
		                                    <div class="clearfix"></div>
		                                </div>
		                            </div>
		-->
		
		                    <div class="mail-body text-right tooltip-demo">
<!--
		                        <a class="btn btn-sm btn-white" href="mail_compose.html">Reply</a>
		                        <a class="btn btn-sm btn-white" href="mail_compose.html"> Forward</a>
		                        <button title="" data-placement="top" data-toggle="tooltip" type="button" data-original-title="Print" class="btn btn-sm btn-white"> Print</button>
-->
		                        <a href="<?php echo base_url('mailbox/eliminar/' . $detalle['id']); ?>" class="btn btn-sm btn-white">Eliminar</a>
		                    </div>
		
		                    <div class="clearfix"></div>
		                </div>
		            </div>
		        </div>
		    </div>