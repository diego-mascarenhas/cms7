<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<html>
	<head>
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
	
		<meta name="viewport" content="width=device-width, initial-scale=1.0">   
		<script type="text/javascript" src="https://wowzamedia.wpengine.com/wp-content/themes/wowzav1/js/wp/wowzaplayer.min.js?ver=1.0.0"></script>
	</head>
	        
	<body style="margin:0;padding:0">
            
            <?php if ($detalle['tipo'] == 'video') { ?>
				<script type="text/javascript">
					WowzaPlayer.create('playerElement',
					    {
					    "license":"PLAY1-jrUYJ-nfkDV-kf7xx-dMH4Q-7x6xD",
					    "sourceURL":"<?php echo $detalle['video']; ?>",
					    "autoPlay":true,
					    "volume":"75",
					    "mute":false,
					    "loop":false,
					    "audioOnly":false,
					    "uiShowQuickRewind":true,
					    "uiQuickRewindSeconds":"10",
						"uiShowFullscreen":true,
						"uiShowBitrateSelector":false,
						"posterFrameURL":"<?php echo $detalle['thumb']; ?>"
					    }
					);
				</script>
				
				<div id="playerElement" style="width: 100%; height: 0; padding: 0 0 56.25% 0;"></div>
            
            <?php } elseif ($detalle['tipo'] == 'audio') { ?>
				<audio controls>
					<source src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>">
				</audio>
				
			<?php } else { ?>
				<img src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>" style="max-width: 100%;">

			<?php } ?>
				
	</body>
</html>