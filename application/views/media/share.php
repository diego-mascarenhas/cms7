<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	        

            <?php if ($detalle['file_type'] == 'video/mp4') { ?>

                <script type="text/javascript" src="<?php echo base_url('assets/jwplayer/jwplayer.js'); ?>"></script>
				<script>jwplayer.key="ncOVi77J9SPH25mDM4C1AAypONO7Y8DzpzSHig==";</script>
				<div id="thePlayer"></div>
				<script type="text/javascript">
				    jwplayer("thePlayer").setup({
				        flashplayer: "<?php echo base_url('assets/jwplayer/player.swf'); ?>",
				        file: "<?php echo base_url($detalle['media_path'] . '/' . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']); ?>",
				        "sharing": {"sites": ["facebook","twitter"]}
				    });
				</script>

            <?php } else { ?>
            	
            	<img src="<?php echo base_url($detalle['media_path'] . '/' . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']); ?>">
            
            <?php } ?>