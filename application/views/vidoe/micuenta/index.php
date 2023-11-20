<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
				<?php if ($this->usuario->perfil == 'admin') { ?>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
			            	<div class="main-title">
								<h6>Panel de Control</h6>
							</div>
						</div>
						<div class="col-xl-3 col-sm-6 mb-3">
							<div class="card text-white bg-primary o-hidden h-100 border-none">
									<a href="<?php echo base_url('administracion/contactos'); ?>" style="color: #fff">
										<div class="card-body">
											<div class="card-body-icon">
												<i class="fas fa-fw fa-users"></i>
											</div>
											<div class="mr-5">
												<span><?php echo $this->lang->line('cms_users-usuarios'); ?></span>
												<h2 class="font-bold" style="color:#fff;"><?php echo $contactos; ?></h2>
											</div>
										</div>
									</a>
									<a class="card-footer text-white clearfix small z-1" href="<?php echo base_url('administracion/contactos'); ?>">
										<span class="float-left">Ver usuarios</span>
										<span class="float-right"><i class="fas fa-angle-right"></i></span>
									</a>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6 mb-3">
								<div class="card text-white bg-primary o-hidden h-100 border-none">
									<a href="<?php echo base_url('multimedia/proyectos'); ?>" style="color: #fff">
										<div class="card-body">
											<div class="card-body-icon">
												<i class="fa fa-rss"></i>
											</div>
											<div class="mr-5">
												<span>Categorías</span>
												<h2 class="font-bold" style="color:#fff;"><?php echo ($media['proyectos']) ? $media['proyectos'] : 0; ?></h2>
											</div>
										</div>
									</a>
									<a class="card-footer text-white clearfix small z-1" href="<?php echo base_url('multimedia/proyectos'); ?>">
										<span class="float-left">Ver categorías</span>
										<span class="float-right"><i class="fas fa-angle-right"></i></span>
									</a>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6 mb-3">
								<div class="card text-white bg-primary o-hidden h-100 border-none">
									<a href="<?php echo base_url('multimedia'); ?>" style="color: #fff">
										<div class="card-body">
											<div class="card-body-icon">
												<i class="far fa-file"></i>
											</div>
											<div class="mr-5">
												<span><?php echo $this->lang->line('cms_media-archivos'); ?></span>
												<h2 class="font-bold" style="color:#fff;"><?php echo ($media['archivos']) ? $media['archivos'] : 0; ?></h2>
											</div>
										</div>
									</a>
									<a class="card-footer text-white clearfix small z-1" href="<?php echo base_url('multimedia'); ?>">
										<span class="float-left">Ir a archivos</span>
										<span class="float-right"><i class="fas fa-angle-right"></i></span>
									</a>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6 mb-3">
								<div class="card text-white bg-primary o-hidden h-100 border-none">
									<div class="card-body">
										<div class="card-body-icon">
											<i class="fas fa-file-video"></i>
										</div>
										<div class="mr-5">
											<span><?php echo $this->lang->line('cms_users-storage'); ?></span>
											<h2 class="font-bold" style="color:#fff;"><?php echo byte_format($media['espacio']*1024); ?></h2>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } else { header('Location: ' . base_url('multimedia')); } ?>
				</div>
			</div>