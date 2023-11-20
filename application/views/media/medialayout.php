<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="media-container">                   
	<form id="media-form" action="<?php echo site_url(CN_BASE.'index'); ?>" method="POST">		
		<!-- control bar -->

		<!-- /.control-bar -->
		<!-- thumbs view -->    
		<div id="thumbs-layout" class="media-layout hidden-xs-up">
			<?php $this->load->view('/media/thumbs'); ?>
		</div>
		<!-- /.thumbs view -->
		<!-- details view -->
		<div id="details-layout" class="media-layout hidden-xs-up">		
			<?php $this->load->view('/media/details'); ?>
		</div>
		<!-- /.details view -->		
		<input id="path" name="path" type="hidden" />                        
	</form>
</div>  