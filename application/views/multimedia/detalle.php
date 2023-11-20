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
	                    <li>
	                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=70&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
	                    <?php } ?>
	                    <a href="<?php echo base_url('/multimedia/upload-thumb/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Subir miniatura</a>
						<?php if ($detalle['tipo'] == 'imagen') { ?>
		            		<a href="<?php echo base_url('multimedia/crop/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Recortar media</a>
						<?php } ?>
						<a href="<?php echo base_url('multimedia/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar media</a>
					</div>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
	            <div class="col-lg-6 animated fadeInLeft">
                    <div class="ibox-title">
	                    <span class="label <?php echo $detalle['estado_ui_class']; ?> pull-right"><?php echo $detalle['estado']; ?></span>
                        <h5 style="text-transform: capitalize;"><?php echo $detalle['nombre']; ?></h5>
                    </div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
							<?php if ($detalle['tipo'] == 'video') { ?>
								<script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
								<script type="text/javascript">
								WowzaPlayer.create('playerElement',
								    {
								    "license":"PLAY1-jrUYJ-nfkDV-kf7xx-dMH4Q-7x6xD",
								    "title":"",
								    "description":"",
								    "sourceURL":"<?php echo $detalle['video']; ?>",
								    "autoPlay":false,
								    "volume":"75",
								    "mute":false,
								    "loop":false,
								    "audioOnly":false,
								    "uiShowQuickRewind":true,
								    "uiQuickRewindSeconds":"10",
									"uiShowFullscreen":false,
									"uiShowBitrateSelector":true,
									"posterFrameURL":"<?php echo $detalle['thumb']; ?>",
									"abrStartingBitrate":"lowest"
								    }
								);
								</script>
								<div id="playerElement" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
								
							<?php } elseif ($detalle['tipo'] == 'audio') { ?>
								<audio controls>
									<source src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>">
								</audio>
							<?php } elseif ($detalle['tipo'] == 'imagen' && isset($detalle['thumb'])) { ?>
								<img src="<?php echo $detalle['thumb']; ?>" style="max-width: 90%; max-height: 400px;">
							<?php } else { ?>
								<?php switch ($detalle['tipo'])
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
								<i class="fa <?php echo $ico; ?> big-icon"></i>
							<?php } ?>
                        </div>
                    </div>
	           	</div>

			   	<div class="col-lg-6 animated fadeInRight">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Descripción</h5>
                        </div>
                        <div class="ibox-content">
                            <p><i class="fa fa-user"></i> Usuario: <?php echo $detalle['contacto']; ?></p>
                            <p><i class="fa fa-clock-o"></i> Fecha: <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></p>
                            
                            
                            <?php if ($detalle['tipo'] == 'video')
	                            	{
		                            	$procesando = (file_exists(FCPATH . 'multimedia/procesar/' . preg_replace('/.[^.]*$/', '', $detalle['archivo']))) ? true : false;
		                            	
		                            	switch ($detalle['stream'])
		                            	{
		                            		case 2:
		                            			$stream = 'On demand';
		                            			break;
		                            		case 3:
		                            			$stream = 'Adaptative';
		                            			break;
		                            		default:
		                            			$stream = 'Storage';
		                            			break;
		                            	}
		                    ?>
                            
                            <p><i class="fa fa-file-o"></i> Tipo: <?php echo $detalle['mime']; ?> - <?php echo $stream; ?> <?php if ($procesando == true) echo '(Procesando)'; ?></p>
	                        
	                        
	                        <?php } else { ?>
	                        	<p><i class="fa fa-file-o"></i> Tipo: <?php echo $detalle['mime']; ?></p>
	                        <?php } ?>
	                        
	                        <p><i class="fa fa-hdd-o"></i> Tamaño: <?php echo byte_format($detalle['peso']*1024); ?></p>
	                        
                            <p><i class="fa fa-file"></i> Archivo: <a href="<?php echo base_url('multimedia/' . $detalle['grupo'] . '/' . $detalle['id_empresa'] . '/' . $detalle['archivo']); ?>" target="_blank"><?php echo $detalle['archivo']; ?></a>
	                            <br>
	                            <small><?php echo base_url('multimedia/' . $detalle['grupo'] . '/' . $detalle['id_empresa'] . '/' . $detalle['archivo']); ?></small>
	                        </p>
	                        
							<?php if ($detalle['id_estado'] == 3) { ?>
							<p><i class="fa fa-link"></i> Link público: 
	                            <a href="<?php echo base_url('multimedia/share/' . $detalle['uid']); ?>" target="_blank">
	                            <?php echo $detalle['uid']; ?>
	                            </a><br>
	                            <small><?php echo base_url('multimedia/share/' . $detalle['uid']); ?></em></small>
	                        </p>
	                        <?php } else { ?>
	                        <p><i class="fa fa-link"></i> Link: El video no está compartido.</p>
	                        <?php } ?>
	                        
                            <?php if ($this->usuario->perfil == 'reseller') { ?>
<!--
                            	<p>
	                            	<i class="fa fa-folder-o"></i> Carpeta: <?php echo base_url('multimedia/' . $detalle['grupo'] . '/' . $detalle['id_empresa'] . '/'); ?><br>
	                            	<small><em>mount 10.0.0.100:/mnt/multimedia/ /Volumes/multimedia/</em></small><br><br>
	                            	<i class="fa fa-file"></i> Archivo: <?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>.mp4<br><br>
	                            	<small><em>
		                            	<?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>_360.mp4 (480x360)<br>
		                            	<?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>_480.mp4 (720x480)<br>
		                            	<?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>_720.mp4 (1280x720)<br>
		                            	<?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>_1080.mp4 (1920x1080)<br>
		                            	<?php echo preg_replace('/.[^.]*$/', '', $detalle['archivo']); ?>.smil
									</em></small>
                            	</p>
-->
                            <?php } ?>
                            
                            <div class="row m-t-md">
								<a href="<?php echo base_url('multimedia/download/' . $detalle['uid']); ?>" title="Descargar" class="btn btn-primary btn-sm pull-left" style="margin-left: 25px;"><i class="fa fa-download"></i> Descargar</a>
								<?php if ($this->usuario->perfil == 'reseller' && isset($detalle['preview'])) { ?> 
				                	<a href="<?php echo base_url('multimedia/download-preview/' . $detalle['uid']); ?>" title="Descargar preview" class="btn btn-primary btn-sm pull-left" style="margin-left: 25px;"><i class="fa fa-download"></i> Descargar preview (<?php echo byte_format($detalle['preview']['size']); ?>)</a>
								<?php } ?>
								<?php if ($detalle['stream'] > 1 && $procesando != true) { ?>
									<a href="<?php echo base_url('multimedia/share/' . $detalle['uid']); ?>" target="_blank" title="Visualizar" class="btn btn-primary btn-sm pull-left" style="margin-left: 25px;"><i class="fa fa-eye"></i> Visualizar</a>
								<?php } ?>
							<?php if ($this->usuario->perfil == 'admin') { ?> 
				                <a title="Asociar" href="<?php echo base_url('/multimedia/asociar/' . $detalle['id']); ?>" class="btn btn-sm btn-danger pull-right" style="margin-right: 25px;"><i class="fa fa-link"></i> Asociar</a>
							<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
	        
		        <?php if ($this->usuario->perfil == 'reseller') { ?>
	                <button type="button" data-toggle="collapse" data-target="#collapse-upload" class="btn btn-primary btn-block"><span class="fa fa-upload"></span><span class="hidden-sm-down"> Subir archivos para stream con video adaptativo</span></button>
				<?php } ?>
				
				<!-- Comienzo Upload de Archivos -->
				<?php if ($this->usuario->perfil == 'reseller') { ?>
				<div id="collapse-upload" class="collapse media-forms">   
					<?php echo form_open('multimedia/upload-stream', array('class'=>'dropzone', 'id'=>'my-dropzone'));?>
					<input type="hidden" name="id_proyecto" value="<?php echo (isset($_GET['proyecto'])) ? $_GET['proyecto'] : ''; ?>">
					<?php echo form_close();?>
				</div>
				<?php } ?>
				<!-- Fin Upload de Archivos -->
			</div>
			
	        <script src="<?php echo base_url('assets/js/plugins/dropzone/dropzone.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>
			
			<script>
				Dropzone.options.myDropzone = {
					acceptedFiles: ".mp4,.smil",
					maxFilesize: 100000,
					parallelUploads: 1,
					addRemoveLinks: true,
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