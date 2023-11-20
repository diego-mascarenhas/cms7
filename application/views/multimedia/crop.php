<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			
			<div class="row wrapper border-bottom white-bg page-heading">
				<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
					<h2>Multimedia</h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo base_url(); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo base_url('multimedia'); ?>">Multimedia</a>
						</li>
						<li class="active">
							<strong>Recortar imágen</strong>
						</li>
					</ol>
				</div>
			</div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-title  back-change">
								<h5>Recortar imágen</h5>
							</div>
							<div class="ibox-content">
								<p>
									<?php echo $detalle['thumb']; ?>
								</p>
								<div class="row">
									<div class="col-md-6">
										<div class="image-crop">
											<!-- <img src="/assets/img/p3.jpg"> -->
											<img src="<?php echo $detalle['thumb']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<h4>Preview image</h4>
										<div class="img-preview img-preview-sm"></div>
										<h4>Comon method</h4>
										<p>
											You can upload new image to crop container and easy download new cropped image.
										</p>
										<div class="btn-group">
											<label title="Upload image file" for="inputImage" class="btn btn-primary">
												<input type="file" accept="image/*" name="file" id="inputImage" class="hide">
												Upload new image
											</label>
											<label title="Donload image" id="download" class="btn btn-primary">Download</label>
										</div>
										<h4>Other method</h4>
										<p>
											You may set cropper options with <code>$(image}).cropper(options)</code>
										</p>
										<div class="btn-group">
											<button class="btn btn-white" id="zoomIn" type="button">Zoom In</button>
											<button class="btn btn-white" id="zoomOut" type="button">Zoom Out</button>
											<button class="btn btn-white" id="rotateLeft" type="button">Rotate Left</button>
											<button class="btn btn-white" id="rotateRight" type="button">Rotate Right</button>
											<button class="btn btn-warning" id="setDrag" type="button">New crop</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- Image cropper -->
	        <script src="<?php echo base_url('assets/js/plugins/cropper/cropper.min.js'); ?>"></script>
			
			<script>
				$(document).ready(function(){
		
					var $image = $(".image-crop > img")
					$($image).cropper({
						aspectRatio: 1.618,
						preview: ".img-preview",
						done: function(data) {
							// Output the result data for cropping image.
						}
					});
		
					var $inputImage = $("#inputImage");
					if (window.FileReader) {
						$inputImage.change(function() {
							var fileReader = new FileReader(),
									files = this.files,
									file;
		
							if (!files.length) {
								return;
							}
		
							file = files[0];
		
							if (/^image\/\w+$/.test(file.type)) {
								fileReader.readAsDataURL(file);
								fileReader.onload = function () {
									$inputImage.val("");
									$image.cropper("reset", true).cropper("replace", this.result);
								};
							} else {
								showMessage("Please choose an image file.");
							}
						});
					} else {
						$inputImage.addClass("hide");
					}
		
					$("#download").click(function() {
						window.open($image.cropper("getDataURL"));
					});
		
					$("#zoomIn").click(function() {
						$image.cropper("zoom", 0.1);
					});
		
					$("#zoomOut").click(function() {
						$image.cropper("zoom", -0.1);
					});
		
					$("#rotateLeft").click(function() {
						$image.cropper("rotate", 45);
					});
		
					$("#rotateRight").click(function() {
						$image.cropper("rotate", -45);
					});
		
					$("#setDrag").click(function() {
						$image.cropper("setDragMode", "crop");
					});
		
				});
		
		
			</script>
			