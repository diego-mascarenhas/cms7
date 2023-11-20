<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-12">
	                <h2>Media</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Media</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

	        <div class="wrapper wrapper-content animated fadeInRight">
	           	<!-- Mensajes -->
	            <div class="row">
					<div class="col-md-12">
						<?php $this->load->view('/media/messages'); ?>
					</div>
				</div>

	        
<!--
	                                    <li><a href=""><i class="fa fa-folder"></i> Files</a></li>
	                                    <li><a href=""><i class="fa fa-folder"></i> Pictures</a></li>
	                                    <li><a href=""><i class="fa fa-folder"></i> Web pages</a></li>
	                                    <li><a href=""><i class="fa fa-folder"></i> Illustrations</a></li>
	                                    <li><a href=""><i class="fa fa-folder"></i> Films</a></li>
	                                    <li><a href=""><i class="fa fa-folder"></i> Books</a></li>
	                                </ul>
	                                <h5 class="tag-title">Tags</h5>
	                                <ul class="tag-list" style="padding: 0">
	                                    <li><a href="">Family</a></li>
	                                    <li><a href="">Work</a></li>
	                                    <li><a href="">Home</a></li>
	                                    <li><a href="">Children</a></li>
	                                    <li><a href="">Holidays</a></li>
	                                    <li><a href="">Music</a></li>
	                                    <li><a href="">Photography</a></li>
	                                    <li><a href="">Film</a></li>
	                                </ul>
	                                <div class="clearfix"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
		
		            <div class="col-lg-9 animated fadeInRight">
		                <div class="row">
		                    <div class="col-lg-12">
			                    
			                    <?php if ($medias) { ?>
				                    <?php foreach ($medias as $media) { ?>
			                        <div class="file-box">
		                                <div class="file">
		                                    <a href="<?php echo base_url('gallery/detalle/'); ?><?php echo $media['id']; ?>">
		                                        <span class="corner"></span>
		
		                                        <div class="icon">
			                                        <?php switch ($media['tipo'])
			                                        {
			                                        	case 'video/mp4':
			                                        		$ico = 'fa-film';
			                                        		break;
			                                        	case 'image/jpeg':
			                                        		$ico = 'fa-file-picture-o';
			                                        		break;
			                                        	case 'music/mp3':
			                                        		$ico = 'fa-music';
			                                        		break;
			                                        	default:
			                                        		$ico = 'fa-file';
			                                        		break;
			                                        }
													?>
		                                            <i class="fa <?php echo $ico; ?>"></i>
		                                        </div>
		                                        <div class="file-name">
		                                            <?php echo $media['nombre']; ?>
		                                            <br/>
		                                            <small>Creado: <?php echo formatear_fecha($media['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></small>
		                                        </div>
		                                    </a>
		                                </div>
		                            </div>
		                            <?php } ?>
	                            <?php } ?>

		
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		    
		    <script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
		    
		    <script>
		        $(document).ready(function(){
		            $('.file-box').each(function() {
		                animationHover(this, 'pulse');
		            });
		        });
		    </script>
-->

	            
				<!-- Contenido -->
		        <div class="row">

					<!-- Lateral con carpetas -->
			            <div class="col-lg-3">
		                    <div class="ibox float-e-margins">
		                        <div class="ibox-content">
		                            <div class="file-manager">
<!--
		                                <h5>MOSTRAR:</h5>
		                                <a href="#" class="file-control active">Todo</a>
		                                <a href="#" class="file-control">Documentos</a>
		                                <a href="#" class="file-control">Audio</a>
		                                <a href="#" class="file-control">Images</a>
		                                <div class="hr-line-dashed"></div>
-->
		                                <button type="button" data-toggle="collapse" data-target="#collapse-upload" class="btn btn-primary btn-block"><span class="fa fa-upload"></span><span class="hidden-sm-down"> Subir archivos</span></button>
		                                <div class="hr-line-dashed"></div>
<!-- 		                                <h5>Carpetas</h5> -->
		                                <div id="folder-tree">
		                                	<ul class="folder-list root" style="padding: 0">
			                                <?php $this->load->view('/media/foldertree'); ?>
			                                </ul>
			                                <div class="hr-line-dashed"></div>
		                                </div>
	<!--
		                                <h5 class="tag-title">Tags</h5>
		                                <ul class="tag-list" style="padding: 0">
		                                    <li><a href="">Family</a></li>
		                                    <li><a href="">Work</a></li>
		                                    <li><a href="">Home</a></li>
		                                    <li><a href="">Children</a></li>
		                                    <li><a href="">Holidays</a></li>
		                                    <li><a href="">Music</a></li>
		                                    <li><a href="">Photography</a></li>
		                                    <li><a href="">Film</a></li>
		                                </ul>
	-->
		                                <div class="clearfix"></div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
			
			            <div class="col-lg-9 animated fadeInRight">
			                <div class="row">
			                    <div class="col-lg-12">
									<div class="main-section">

										<div id="media-manager" class="container-fluid">
											<div id="controls" class="row">
												<div class="col-md-12">
													<div class="btn-toolbar" role="toolbar" area-label="toolbar of control buttons" title="Toggle Folder Lists">
													<div class="btn-toolbar" role="toolbar" area-label="toolbar of media buttons">
														<div class="btn-group" role="group" area-label="show thumb" title="Thumbs View">
															<button type="button" id="thumbs" data-layout="thumbs" class="btn btn-sm btn-secondary btn-layout"><span class="fa fa-th-large"></span></button>			    
														</div>
														<div class="btn-group" role="group" area-label="show details" title="Details View">
															<button type="button" id="details" data-layout="details" class="btn btn-sm btn-secondary btn-layout"><span class="fa fa-list"></span></button>			    
														</div>
														<div id="btn-group-select" class="btn-group hidden-xs-up" role="group" area-label="select items" title="Seleccionar Items">
															<button type="button" class="btn btn-sm btn-secondary"><span class="fa fa-check"></span> <span class="hidden-sm-down">Seleccionar Items</span></button>  		    
														</div>			

													
														<div class="botones-derecha">
															<div class="btn-group" role="group" area-label="Crear nueva carpeta" title="Crear nueva carpeta">
																<button type="button" data-toggle="collapse" data-target="#collapse-folder" class="btn btn-secondary btn-sm"><span class="fa fa-folder-open-o"></span> <span class="hidden-sm-down"> Crear nueva carpeta</span></button>          
															</div>
															<div class="btn-group" role="group" area-label="trash media or folder" title="Renombrar">
															<button type="button" class="btn btn-secondary btn-sm btn-tb-rename"><span class="fa fa-pencil"></span> <span class="hidden-sm-down"> Renombrar</span></button>          
														</div>
														<div class="btn-group" role="group" area-label="trash media or folder" title="Eliminar">
															<button type="button" class="btn btn-secondary btn-sm btn-tb-delete"><span class="fa fa-times"></span> <span class="hidden-sm-down"> Eliminar</span></button>         
														</div>        
	
															<div class="btn-group hidden-md-up" role="group" area-label="show off-canvas folders list">
																<button type="button" class="btn btn-secondary btn btn-sm btn-off-canvas"><span class="fa fa-list"></span></button>
															</div>
<!--
															<div class="btn-group" role="group" area-label="Subir media" title="Subir">
																<button type="button" data-toggle="collapse" data-target="#collapse-upload" class="btn btn-primary btn-sm" style="float:right;"><span class="fa fa-upload"></span><span class="hidden-sm-down"> Subir</span></button>
															</div>
-->
														</div>	
													</div>
												</div>
												
											</div>
										</div>
									</div>
			                    </div>
			                </div>
			                
			                <div class="row">
			                    <div class="col-lg-12">
									<?php $this->load->view('/media/mediaform'); ?>
									<?php $this->load->view('/media/medialayout'); ?>
			                    </div>
			                </div>
			                
			                
			                
			            </div>
			        </div>
		        </div>
	        </div>
			
			<script src="<?php echo base_url('assets/js/media/tether.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/dropzone/dropzone.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/media/js.cookie.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/media/bootbox.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/media/masonry.pkgd.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/magnificpopup/jquery.magnific-popup.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/media/pwstrength-bootstrap.min.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/media.js'); ?>"></script>    
			<script src="<?php echo base_url('assets/js/client.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/mediaelementplayer/mediaelement-and-player.min.js'); ?>"></script>
			
			<script>var site_url = '<?php echo site_url(CN_BASE).'/'; ?>';</script>
			<script>var max_size = '<?php echo $this->config->item('max_size'); ?>';</script>
			<script>var max_files = '<?php echo $this->config->item('max_files'); ?>';</script>