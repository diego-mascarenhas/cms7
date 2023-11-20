<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-4">
	                <h2>Media</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('media/'); ?>">Media</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
<!--
	            <div class="col-sm-8">
                    <div class="title-action">
                        <a href="<?php echo base_url('gallery/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar media</a>
                    </div>
                </div>
-->
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        
<!--
		        <div class="row">
	                <div class="col-lg-6">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-title">
	                            <h5><?php echo $detalle['file_name']; ?></h5>
	                        </div>
	                        <div class="ibox-content">
	                            <figure>
	                                <?php if ($detalle['file_type'] == 'video/mp4') { ?>
			                            <script type="text/javascript" src="<?php echo base_url('assets/jwplayer/jwplayer.js'); ?>"></script>
										<script>jwplayer.key="ncOVi77J9SPH25mDM4C1AAypONO7Y8DzpzSHig==";</script>
										<div id="thePlayer"></div>
										<script type="text/javascript">
										    jwplayer("thePlayer").setup({
										        flashplayer: "<?php echo base_url('assets/jwplayer/player.swf'); ?>",
										        file: "<?php echo base_url($detalle['media_path'] . '/' . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']); ?>",
										        "skin": {
													    "name": "cms"
													    },
												controls: true,
												autostart: false
										    });
										</script>
					                <?php } elseif ($detalle['file_type'] == 'audio/x-wav' || $detalle['file_type'] == 'application/zip') { ?>

					                <?php } else { ?>
					                	<img src="<?php echo base_url($detalle['media_path'] . '/' . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']); ?>" style="width: 480px;">
					                <?php } ?>
	                            </figure>
	                        </div>
	                    </div>
	                </div>
-->
	
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content profile-content">
	                            <h4><strong>Información</strong></h4>
	                            <h5><?php echo $detalle['file_path'] . $detalle['file_name']; ?></h5>
	                            <p><i class="fa fa-user"></i> Usuario: <?php echo $detalle['contacto']; ?></p>
	                            <p><i class="fa fa-clock-o"></i> Cargado: <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></p>
	                            <p><i class="fa fa-file-o"></i> Tipo: <?php echo $detalle['file_type']; ?></p>
	                            <p><i class="fa fa-hdd-o"></i> Tamaño: <?php echo byte_format($detalle['file_size']*1024); ?></p>
	                            <p><i class="fa fa-link"></i> Link (Privado): <?php echo base_url('share/media/' . $detalle['uid']); ?></p>
	                            
	                            <div class="row m-t-md">
<!--
	                                <div class="col-md-3">
	                                    <h5><strong>0</strong> Likes</h5>
	                                </div>
	                                <div class="col-md-3">
	                                    <h5><strong>0</strong> Comentarios</h5>
	                                </div>
-->
<!--
	                                <div class="col-md-3">
	                                    <a href="<?php echo base_url('share/media/' . $detalle['uid']); ?>"><i class="fa fa-share-alt"></i> Compartir</a>
	                                </div>
-->
									<div class="col-md-3">
	                                    <a href="<?php echo base_url('media/ver/' . $detalle['id']); ?>" target="_blank"><i class="fa fa-eye"></i> Ver</a>
	                                </div>
	                                <div class="col-md-3">
	                                    <a href="<?php echo base_url('share/download/' . $detalle['uid']); ?>"><i class="fa fa-cloud-download"></i> Descargar</a>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                
	            </div>
	            
	        </div>