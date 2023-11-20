<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div class="single-channel-page" id="content-wrapper">
			<?php if (isset($channel_banner)) { ?>
			<div class="single-channel-image">
				<img class="img-fluid" src="<?php echo $channel_banner; ?>">
				<div class="channel-profile d-none d-md-block d-lg-block">
					<?php if (isset($proyecto['thumb']))
							{ ?>
							<img class="channel-profile-img" src="<?php echo base_url('multimedia/thumbs/' . $proyecto['thumb']); ?>">
					
					<?php } ?>
				</div>
			</div>
			<?php } ?>

            <div class="single-channel-nav">
                <nav class="navbar navbar-expand-lg navbar-light">
					<?php if (isset($breadcrumb)) { ?>
		                        <a class="channel-brand" href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
		                    <?php
			                	foreach ($breadcrumb as $item)
			                	{
			                	?>
			                	<?php if ($this->input->get('proyecto') == $item['id']) { ?>
			                		<a class="channel-brand" href="#">&nbsp;/&nbsp;<?php echo $item['proyecto']; ?></a>
			                    <?php } else { ?>
			                        <a class="channel-brand" href="<?php echo base_url('multimedia/?proyecto=' . $item['id']); ?>">&nbsp;/&nbsp;<?php echo $item['proyecto']; ?></a>
			                    <?php } ?>

			                	<?php
				                }
				            } else { ?>
		                        <a class="channel-brand" href="#">Multimedia</a>
						<?php } ?>

					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	                <span class="navbar-toggler-icon"></span>
	                </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">

                            <li class="nav-item
								<?php if ($this->input->get('tipo') == 'video') echo 'active'; ?>">
								<a class="nav-link" href="?tipo=video
								<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
								<?php echo $this->lang->line('cms_media-videos'); ?>
								</a>
							</li>

							<li class="nav-item <?php if ($this->input->get('tipo') == 'audio') echo 'active'; ?>">
								<a class="nav-link" href="?tipo=audio
								<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
								<?php echo $this->lang->line('cms_media-audios'); ?>
								</a>
							</li>


							<li class="nav-item <?php if ($this->input->get('tipo') == 'imagen') echo 'active'; ?>">
								<a class="nav-link" href="?tipo=imagen
								<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
								<?php echo $this->lang->line('cms_media-imagenes'); ?>
								</a>
							</li>

							<li class="nav-item <?php if ($this->input->get('tipo') == 'archivo') echo 'active'; ?>">
								<a class="nav-link" href="?tipo=archivo
								<?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
								<?php echo $this->lang->line('cms_media-archivos'); ?>
								</a>
							</li>

                        </ul>
                    </div>
                </nav>
            </div>

            <div class="container-fluid">
                <div class="video-block section-padding">
                    <div class="row">
						<div class="col-md-12">
							<div class="main-title">
							<!-- // Filtros //  -->
							<div class="btn-group float-right right-action">
								<a href="#" class="right-action-link text-gray" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Filtrar <i class="fa fa-caret-down" aria-hidden="true"></i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="?order_by=nombre&order=ASC <?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
										<i class="fas fa-file-signature"></i> &nbsp; Nombre</a>
									<a class="dropdown-item" href="?order_by=fecha_alta&order=ASC <?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
										<i class="fas fa-calendar-alt"></i> &nbsp; Fecha</a>
									<a class="dropdown-item" href="?order_by=peso&order=ASC <?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
										<i class="fas fa-weight-hanging"></i></i> &nbsp; Peso</a>
									<a class="dropdown-item" href="?order_by=estado&order=ASC <?php if ($this->input->get('proyecto')) echo '&proyecto=' . $this->input->get('proyecto'); ?>">
										<i class="fas fa-check-square"></i> &nbsp; Estado</a>
								</div>
							</div>
							<h6>Multimedia</h6>
							</div>
						</div>

						<?php  if (!empty($this->session->userdata('livestream'))) { ?>
						<div class="col-xl-3 col-sm-6 mb-3">
						    <div class="video-card">
					            <div class="video-card-image">
					            <script type="text/javascript" src="//player.wowza.com/player/latest/wowzaplayer.min.js"></script>
					            <script type="text/javascript">
					                WowzaPlayer.create('playerElement',
					                    {
					                    "license":"PLAY1-jrUYJ-nfkDV-kf7xx-dMH4Q-7x6xD",
					                    "sourceURL":"<?php echo $this->session->userdata('livestream'); ?>",
					                    "autoPlay":true,
					                    "volume":"75",
					                    "mute":false,
					                    "loop":false,
					                    "audioOnly":false,
					                    "uiShowQuickRewind":true,
					                    "uiQuickRewindSeconds":"30"
					                    }
					                );
					            </script>
								<div id="playerElement" style="width:100%; height:0; padding:0 0 56.25% 0"></div>
					            </div>
								<div class="video-card-body">
								   <div class="video-title">
									  <a href="#">En Vivo</a>
								   </div>
								   <div class="video-page text-success">
									  Streaming <a title="" data-placement="top" data-toggle="tooltip" href="#" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></a>
								   </div>
								   <!-- <div class="video-view">&nbsp;</div> -->
								</div>
						    </div>
						</div>
						<?php } ?>

						<?php  if (!empty($medias)) {
							foreach ($medias as $media) {
					    ?>
                        <div class="col-xl-3 col-sm-6 mb-3">
                            <div class="video-card">
                                <div class="video-card-image">
	                                <?php $link = (isset($parametros['proyecto'])) ? 'multimedia/detalle/' . $media['id'] . '?proyecto=' . $parametros['proyecto'] : 'multimedia/detalle/' . $media['id']; ?>
                                    <a class="play-icon" href="<?php echo base_url($link); ?>"><i class="fas fa-play-circle"></i></a>
	                                    <?php if (isset($media['thumb']))
		                                    { 
			                                    $thumb = $media['thumb'];
			                                }
			                                else
											{
												switch ($media['tipo'])
			                                        {
			                                        	case 'imagen':
			                                        		$thumb = base_url('assets/vidoe/img/thumb-imagen.png');
			                                        		break;
			                                        	case 'video':
			                                        		$thumb = base_url('assets/vidoe/img/thumb-video.png');
			                                        		break;
			                                        	case 'audio':
			                                        		$thumb = base_url('assets/vidoe/img/thumb-audio.png');
			                                        		break;
			                                        	default:
			                                        		$thumb = base_url('assets/vidoe/img/thumb-default.png');
			                                        		break;
			                                        }
											}
		                                    ?>
	                                    	<img class="img-fluid" src="<?php echo $thumb; ?>">

                                    <?php if ($media['id_estado'] == 1) { ?>
	                                    <div class="time bg-info">Inactivo</div>
	                                <?php } elseif ($media['id_estado'] == 2) { ?>
	                                    <div class="time bg-primary">Activo</div>
	                                <?php } else { ?>
	                                    <div class="time bg-success">Público</div>
	                                <?php } ?>
                                </div>

                                <div class="video-card-body">
                                    <div class="video-title">
                                        <a href="<?php echo base_url($link); ?>"><?php echo ellipsize($media['nombre'], 40); ?></a>
                                    </div>
<!--
                                    <div class="video-page text-success">
                                        < ?php echo $media['archivo']; ?> <a title="" data-placement="top" data-toggle="tooltip" href="#" data-original-title="Verified"></a>
                                    </div>
-->
                                    <div class="video-view">
                                        <i class="fas fa-cloud"></i>&nbsp;<?php echo byte_format($media['peso']*1024); ?>&nbsp;
										<i class="fas fa-calendar-alt">&nbsp;</i><?php echo formatear_fecha($media['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
									}
								}
							?>

                    </div>

                    <nav aria-label="Page navigation example">
<!--
                        <ul class="pagination justify-content-center pagination-sm mb-0">
                            <li class="page-item disabled"><a tabindex="-1" href="#" class="page-link">Previous</a></li>

                            <li class="page-item active"><a href="#" class="page-link">1</a></li>

                            <li class="page-item"><a href="#" class="page-link">2</a></li>

                            <li class="page-item"><a href="#" class="page-link">3</a></li>

                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
-->
                    </nav>
                </div>
            </div>












<!--
Te he dicho que no mires atrás
porque el cielo no es tuyo
y hay que empezar despacio
a deshacer el mundo
-->












