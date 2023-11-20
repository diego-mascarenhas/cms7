	<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
	<!-- Carga de Imagenes -->
	<link href="<?php echo base_url('assets/css/plugins/dropzone/basic.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/plugins/dropzone/dropzone.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">

		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/productos'); ?>">Productos</a>
                    </li>
                    <li>
                        <strong>Galería</strong>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row wrapper animated fadeInRight m-t-xl">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                	<div class="ibox-title border-left-right border-bottom"><h5>Galería de Imágenes <?php if(isset($item['id'])) { ?> para <a href="<?php echo base_url('tienda/productos/modificar/'.$item['id']);?>" title=""><?php echo $item['titulo']; ?></a> <?php } ?></h5></div>
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
                                <div class="hr-line-dashed"></div>
	                                <button type="button" data-toggle="collapse" data-target="#collapse-upload" class="btn btn-primary btn-block"><span class="fa fa-upload"></span><span class="hidden-sm-down"> Subir archivos</span></button>
								<?php } ?>
                                <div class="hr-line-dashed"></div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

	            <div class="col-lg-9 animated fadeInRight">
	                <div class="row">
	                    <div class="col-lg-12">
							<!-- Comienzo Upload de Archivos -->
							<div id="collapse-upload" class="collapse media-forms">   
								<?php echo form_open('tienda/productos/upload_galeria', array('class'=>'dropzone', 'id'=>'my-dropzone'));?>
								<input type="hidden" name="id_proyecto" value="<?php echo $id_proyecto; ?>">
								<input type="hidden" name="producto" value="<?php echo $item['id']; ?>">
								<?php echo form_close();?>
							</div>
							<!-- Fin Upload de Archivos -->

		                    <?php if (!empty($medias))
				                	{
					                	foreach ($medias as $media)
					                	{
					                	?>
	                        <div class="file-box">
                                <div class="file">
<!--                                     <a href="<?php echo base_url('multimedia/detalle/'); ?><?php echo $media['id']; ?>"> -->
                                        <span class="corner"></span>
	                                        <?php if (isset($media['thumb'])) { ?>
	                                        <div class="image">
												<img alt="image" class="img-responsive" src="<?php echo base_url('multimedia/thumbs/'.$media['thumb']); ?>">
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
<!--                                     </a> -->
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