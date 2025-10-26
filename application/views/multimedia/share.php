<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
	<head>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
	
		<link rel="stylesheet" href="https://cdn.flowplayer.com/releases/native/3/stable/style/flowplayer.css">
		<script src="https://cdn.flowplayer.com/releases/native/3/stable/flowplayer.min.js"></script>
		<script src="https://cdn.flowplayer.com/releases/native/3/stable/plugins/hls.min.js"></script>
	</head>
	        
	<body style="margin:0;padding:0">
            
            <?php if ($detalle['tipo'] == 'video') { ?>
				<style>
					#playerElement {
						width: 100%;
						height: 0;
						padding: 0 0 56.25% 0;
						position: relative;
					}
					#playerElement .flowplayer {
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 100%;
					}
				</style>

				<div id="playerElement"></div>

				<script type="text/javascript">
					flowplayer('#playerElement', {
						src: "<?php echo $detalle['video']; ?>",
						token: "eyJraWQiOiJZMzQ5cVlIUDFRd1IiLCJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiJ9.eyJjIjoie1wiYWNsXCI6MjIsXCJpZFwiOlwiWTM0OXFZSFAxUXdSXCJ9IiwiaXNzIjoiRmxvd3BsYXllciJ9.URiG5fT4w3-TaPyT76AjZw9Cw8Bt4_Ug9uz2S3X5Tg9I2O0WV5hNUW-hjgY61ZxMFF8THpirCkW8NhWAE0zwXQ",
						poster: "<?php echo $detalle['thumb']; ?>",
						autoplay: true,
						volume: 0.75,
						muted: false,
						loop: false
					});
				</script>
            
            <?php } elseif ($detalle['tipo'] == 'audio') { ?>
				<audio controls>
					<source src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>">
				</audio>
				
			<?php } else { ?>
				<img src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>" style="max-width: 100%;">

			<?php } ?>
				
	</body>
</html>