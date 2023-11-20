<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


		            <?php if ($detalle['file_type'] == 'video/mp4') { ?>
		            <div class="row">
	                    <div class="col-sm-6">
	                        <div>
	                            <script type="text/javascript" src="<?php echo base_url('assets/jwplayer/jwplayer.js'); ?>"></script>
								<script>jwplayer.key="ncOVi77J9SPH25mDM4C1AAypONO7Y8DzpzSHig==";</script>
								<div id="thePlayer"></div>
								<script type="text/javascript">
								    jwplayer("thePlayer").setup({
								        flashplayer: "<?php echo base_url('assets/jwplayer/player.swf'); ?>",
								        file: "<?php echo base_url('multimedia/') . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']; ?>"
								        //height: "270",
								        //width: "380"
								    });
								</script>
	                        </div>
	                    </div>
	                </div>
	                <?php } else { ?>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <img src="<?php echo base_url('multimedia/') . $detalle['user_id'] . '/' . $detalle['file_path'] . '/' . $detalle['file_name']; ?>">
	                    </div>
	                </div>
	                <?php } ?>