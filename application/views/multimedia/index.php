<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<!-- Carga de Imagenes -->
			<link href="<?php echo base_url('assets/css/plugins/dropzone/basic.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/plugins/dropzone/dropzone.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
			<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    
	                    <?php if (isset($breadcrumb))
			                    { ?>
			                    <li>
			                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
			                    </li>
			                    
			                    <?php
				                	foreach ($breadcrumb as $item)
				                	{
				                	?>
				                	
				                	<?php if ($this->input->get('proyecto') == $item['id']) { ?>
				                	<li class="active">
				                        <a href="<?php echo base_url('multimedia/?proyecto=' . $item['id']); ?>"><strong><?php echo $item['proyecto']; ?></strong></a>
				                    </li>
				                    <?php } else { ?>
				                    <li>
				                        <a href="<?php echo base_url('multimedia/?proyecto=' . $item['id']); ?>"><?php echo $item['proyecto']; ?></a>
				                    </li>
				                    <?php } ?>
				                    
				                	<?php
					                }
					            } else { ?>
					            <li class="active">
			                        <strong>Multimedia</strong>
			                    </li>
								<?php } ?>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ($this->usuario->perfil == 'admin') { ?>
		            		<a href="<?php echo base_url('multimedia/upload-sajax/'); ?>" class="btn btn-primary btn-sm">Subir archivo</a>
						<?php } ?>
						
						<?php if ($this->usuario->perfil == 'admin') { ?>
		            		<a href="<?php echo base_url('multimedia/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar media</a>
						<?php } ?>
					</div>
	            </div>
	        </div>

	        <div class="row wrapper wrapper-content animated fadeInRight">
	           	<?php if ($this->usuario->perfil != 'guest') { ?>
	           	<div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="file-manager">
                                <h5>Mostrar</h5>
                                <a href="?tipo=todos" class="file-control <?php if ($this->input->get('tipo') == 'todos') echo 'active'; ?>">Todos</a>
                                <a href="?tipo=audio<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>" class="file-control <?php if ($this->input->get('tipo') == 'audio') echo 'active'; ?>">Audio</a>
                                <a href="?tipo=video<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>" class="file-control <?php if ($this->input->get('tipo') == 'video') echo 'active'; ?>">Video</a>
                                <a href="?tipo=imagen<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>" class="file-control <?php if ($this->input->get('tipo') == 'imagen') echo 'active'; ?>">Images</a>
								<?php if ($this->usuario->perfil != 'guest') { ?>
<!--
                                <div class="hr-line-dashed"></div>
	                                <button type="button" data-toggle="collapse" data-target="#collapse-upload" class="btn btn-primary btn-block"><span class="fa fa-upload"></span><span class="hidden-sm-down"> Subir archivos</span></button>
-->
								<?php } ?>
                                <div class="hr-line-dashed"></div>

                                <h5> &nbsp;<?php echo $this->lang->line('cms_media-canales'); ?> <?php if ($this->usuario->perfil != 'guest') { ?><a title="Gestionar Proyectos" href="/multimedia/proyectos" class="btn-proyectos pull-right">  <i class="fa fa-cog"></i> </a><?php } ?></h5> 
                                <?php if (isset($proyectos)) { ?>
									<div class="lista_carpertas">
										<?php
											function menuProyectosVista($menu, $nivel=null)
											{
												?>
												<?php foreach($menu as $obj): ?>
													<li <?php if( (isset($_GET['proyecto'])) && ($obj['id'] == $_GET['proyecto'])) { echo 'class="active"';} ?>>
								                    	<a href="?proyecto=<?php echo $obj['id']; ?>"<?php if( (isset($_GET['proyecto'])) && ($obj['id'] == $_GET['proyecto'])) { echo 'class="activo"';} ?>>
									                    	<?php if (!isset($nivel)) { ?>
									                    		 <i class="fa fa-folder"></i> <span class="nav-label"><?php echo $obj['proyecto']; ?></span>
									                    	<?php } else { ?>
									                    		<span class="minus">- </span><?php echo $obj['proyecto']; ?>
									                    	<?php } ?>
									                	</a>
									                    <?php if (isset($obj['hijos'])): ?>
															<button type="button" data-toggle="collapse" data-target="#collapse-<?=$obj['id']?>" style="float:right; border:none; background: transparent;"><span class="fa arrow"></span></button>
														<?php endif; ?>
									                	
									                	<?php if (isset($obj['hijos'])): ?>
									                		<?php
										                		switch ($obj['nivel'])
										                		{
										                			case 2:
										                				$level_ui_class = 'nav-third-level';
										                				break;
										                			case 3:
										                				$level_ui_class = 'nav-fourth-level';
										                				break;
										                			default:
										                				$level_ui_class = 'nav-second-level';
										                				break;
										                		}
									                		?>
									                		<ul class="nav <?php echo $level_ui_class; ?> collapse" id="collapse-<?=$obj['id']?>">
									                			<?php menuProyectosVista($obj['hijos'], $obj['nivel']); ?>
									                		</ul>
									                	<?php endif; ?>
													</li>
												<?php endforeach; ?>
												<?php 
											}
						                
						                	menuProyectosVista($proyectos);
						                ?>
		                            </div>
	                            <?php } ?>
				                

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
                <?php } ?>

	            <div class="col-lg-9 animated fadeInRight">
	                <div class="row">
	                    <div class="col-lg-12">
							<!-- Comienzo Upload de Archivos -->
							<?php if ($this->usuario->perfil != 'guest') { ?> 
							<div id="collapse-upload" class="collapse media-forms">   
								<?php echo form_open('multimedia/upload', array('class'=>'dropzone', 'id'=>'my-dropzone'));?>
								<input type="hidden" name="id_proyecto" value="<?php echo (isset($_GET['proyecto'])) ? $_GET['proyecto'] : ''; ?>">
								<?php echo form_close();?>
							</div>
							<?php } ?>
							<!-- Fin Upload de Archivos -->

		                    <?php if (!empty($medias))
				                	{
					                	foreach ($medias as $media)
					                	{
					                	?>
	                        <div class="file-box">
                                <div class="file">
                                    <a href="<?php echo base_url('multimedia/detalle/'); ?><?php echo $media['id']; ?>">
                                        <span class="corner"></span>
	                                        <?php if (isset($media['thumb'])) { ?>
	                                        <div class="image">
												<img alt="image" class="img-responsive" src="<?php echo $media['thumb']; ?>">
											</div>
											<?php } else { ?>
												<?php switch ($media['tipo'])
			                                        {
			                                        	case 'imagen':
			                                        		$ico = 'fa-file-picture-o';
			                                        		break;
			                                        	case 'video':
			                                        		$ico = 'fa-film';
			                                        		break;
			                                        	case 'audio':
			                                        		$ico = 'fa-music';
			                                        		break;
			                                        	default:
			                                        		$ico = 'fa-file';
			                                        		break;
			                                        }
													?>
											<div class="icon">
                                            	<i class="fa <?php echo $ico; ?>"></i>
                                            </div>
                                            <?php } ?>
                                        
                                        <div class="file-name">
                                            <?php echo ellipsize($media['nombre'], 22); ?>
                                            <br/>
                                            <small>Estado: <?php echo $media['estado']; ?></small>
                                            <br/>
                                            <small><?php echo byte_format($media['peso']*1024); ?> <?php echo formatear_fecha($media['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></small>
                                    </div>
                                    </a>
                                </div>
                            </div>
	                            <?php
		                            	}
									}
	                            ?>
	                    </div>
	                </div>
	        	</div>
	        </div>


			<script src="<?php echo base_url('assets/js/plugins/dropzone/dropzone.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
			
			<script>
				Dropzone.options.myDropzone = {
					acceptedFiles: "<?php echo $dropzone['accepted_files']; ?>",
					maxFilesize: 10000000, // 10 MB
					parallelUploads: 1,
					addRemoveLinks: true,
					chunking: true,      // enable chunking
					forceChunking: true, // forces chunking when file.size < chunkSize
					parallelChunkUploads: false, 
					chunkSize: 10000000,  // chunk size 1,000,000 bytes (~1MB)
					retryChunks: true,   // retry chunks on failure
					retryChunksLimit: 10, // retry maximum of 3 times (default is 3)
					init: function() {
						this.on("uploadprogress", function(file, progress) {
							console.log("File progress", progress);
						});
					}
				}
				
				$('.btnAdd').on('click', function (e) {
				    e.preventDefault();
				    var elem = $(this).next('.td1')
				    elem.toggle('slow');
				});
			</script>