<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
            	<div class="container-fluid">
	            	<div class="row">
						<div class="col-lg-12">
							<div class="main-title">
								<h6>Subir archivo</h6>
							</div>
							<hr>
						</div>
					</div>
                	<div class="row">
                    	<div class="col-md-8 mx-auto text-center upload-video pt-5 pb-5">
	                    	<h1><i class="fas fa-file-upload text-primary"></i></h1>
							<h6 class="mt-5 text-muted"><?php echo $this->lang->line('cms_media-selecciona_un_archivo_para_subir'); ?></h6>
							
							<?php if (validation_errors()) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo validation_errors(); ?>
										</div>
									</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php echo form_open_multipart(null); ?>
                        		<input type="hidden" name="upload" value="true">
								<p class="land"><input type="file" name="file" class="form-control"></p>
								<div class="mt-4">
                            		<button class="btn btn-primary" type="submit"><?php echo $this->lang->line('cms_media-subir'); ?></button>
                        		</div>
							<?php echo form_close(); ?>
                    	</div>
					</div>
            	</div>
