			<!-- /.container-fluid -->
			<!-- Sticky Footer -->
			<footer class="sticky-footer">
				<div class="container">
					<div class="row no-gutters">
						<div class="col-lg-12">
							<p class="mt-1 mb-0">&copy; Copyright 2018 <strong class="text-dark">Rocoto</strong>. All Rights Reserved<br>
								<small class="mt-0 mb-0">powered by</i> <a class="text-primary" target="_blank" href="https://www.revisionalpha.com/">revision alpha</a>
								</small>
							</p>
						</div>
<!--
						<div class="col-lg-6 col-sm-6 text-right">
							<div class="app">
								<a href="#"><img alt="" src="<?php echo base_url('assets/img/google.png'); ?>"></a>
								<a href="#"><img alt="" src="<?php echo base_url('assets/img/apple.png'); ?>"></a>
							</div>
						</div>
-->
					</div>
				</div>
			</footer>
		</div>
		<!-- /.content-wrapper -->
	</div>
	<!-- /#wrapper -->
	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>

	<!-- Logout Modal-->
	<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('cms_users-list_para_salir_titulo'); ?></h5>
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body"><?php echo $this->lang->line('cms_users-list_para_salir_texto'); ?></div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo $this->lang->line('cms_users-cancelar'); ?></button>
						<a class="btn btn-primary" href="<?php echo base_url('user/logout'); ?>"><?php echo $this->lang->line('cms_users-logout'); ?></a>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Bootstrap core JavaScript-->
	<script src="<?php echo base_url('assets/vidoe/jquery/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/vidoe/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- Core plugin JavaScript-->
	<script src="<?php echo base_url('assets/vidoe/jquery-easing/jquery.easing.min.js'); ?>"></script>
	<!-- Owl Carousel -->
	<script src="<?php echo base_url('assets/vidoe/owl-carousel/owl.carousel.js'); ?>"></script>
	<!-- Custom scripts for all pages-->
	<script src="<?php echo base_url('assets/vidoe/js/custom.js'); ?>"></script>
	
	<script type="text/javascript">
		function setMiniNavBar() {
			if($('body').hasClass('sidebar-toggled')) {
				localStorage.setItem('miniNavbar', 0);
			} else {
				localStorage.setItem('miniNavbar', 1);
			}
		}
		
		(function() {
			(typeof localStorage.miniNavbar === 'undefined') ? localStorage.setItem('miniNavbar', 0) : null;
			if(localStorage.miniNavbar == 1) {
				$("body").addClass("sidebar-toggled");
 				$(".sidebar").addClass("toggled");
			}
		}) ();
	</script>
</body>
</html>