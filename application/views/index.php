<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="wrapper wrapper-content animated fadeInRight">
				
				<div class="row">
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('multimedia/proyectos'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-rss fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_media-canales'); ?></span>
			                            <h2 class="font-bold"><?php echo $media['proyectos']; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('multimedia'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-file-o fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_media-archivos'); ?></span>
			                            <h2 class="font-bold"><?php echo $media['archivos']; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
		                    <div class="row">
		                        <div class="col-xs-4">
		                            <i class="fa fa-cloud fa-5x"></i>
		                        </div>
		                        <div class="col-xs-8 text-right">
		                            <span><?php echo $this->lang->line('cms_users-storage'); ?></span>
		                            <h2 class="font-bold"><?php echo byte_format($media['espacio']*1024); ?></h2>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		        
		        <?php if (verificarPermiso('comunicaciones', $this->session->menu)) { ?>
		        <div class="row">
		            <div class="col-lg-2">
			            <?php if ($comunicaciones['enviar']) { ?>
		                <div class="widget style1 yellow-bg">
		                    <div class="row vertical-align">
		                        <div class="col-xs-3">
		                            <i class="fa fa-bullhorn fa-3x"></i>
		                        </div>
		                        <div class="col-xs-9 text-right">
		                            <h2 class="font-bold"><?php echo ($comunicaciones['enviar']) ? $comunicaciones['enviar'] : 0; ?></h2>
		                        </div>
		                    </div>
		                </div>
		                <?php } else { ?>
		                <div class="widget style1 lazur-bg">
		                    <div class="row vertical-align">
		                        <div class="col-xs-3">
		                            <i class="fa fa-bullhorn fa-3x"></i>
		                        </div>
		                        <div class="col-xs-9 text-right">
		                            <h2 class="font-bold">0</h2>
		                        </div>
		                    </div>
		                </div>
		                <?php } ?>
		            </div>
		        </div>
		        <?php } ?>
		        
	        </div>