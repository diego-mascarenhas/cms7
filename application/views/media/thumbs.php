<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="masonry-layout">
	<!-- folders -->
	<?php if (isset($folders)): ?>
		<?php foreach ($folders as $folder): ?>						
			<div class="media-item media-icon">
				<div class="cover"></div>
				<div class="media-inner"><a class="mediapath" href="<?php echo $folder['path']; ?>"><span class="fa fa-folder fa-4x"></span></a></div>
				<div class="media-title"><a class="mediapath" href="<?php echo $folder['path']; ?>"><?php echo $folder['name']; ?></a><br/>
			</div>
			<input type="checkbox" name="rm[]" class="hidden-xs-up" value="<?php echo $folder['path']; ?>" data-media="folder" data-raw-name="<?php echo $folder['name']; ?>" data-path="<?php echo $folder['path']; ?>">
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	
	<!-- files -->
	<?php if (isset($media['files'])): ?>
		<?php foreach ($media['files'] as $file): ?>		
			<?php if ($file['type'] == 'image'): ?>
				<div class="media-item media-image" style="<?php echo 'width:'.$file['width_x'].'px'; ?>">
					<div class="cover"></div>
					<div class="media-inner">
						<a href="<?php echo base_url('media/detalle/' . $file['id']); ?>" title="<?php echo $file['raw_name']; ?>"><img src="<?php echo base_url($file['url']); ?>" alt="<?php echo $file['name']; ?>" /></a>
					</div>
					<div class="media-title">
						<?php echo $file['name']; ?>
					</div>
					<input type="checkbox" name="rm[]" class="hidden-xs-up" value="<?php echo $file['path']; ?>" data-media="file" data-raw-name="<?php echo $file['raw_name']; ?>" data-path="<?php echo $file['path']; ?>">
				</div>
			<?php else: ?>
				<div class="media-item media-icon">
					<div class="cover"></div>
					<div class="media-inner">
						<a href="<?php echo base_url('media/detalle/' . $file['id']); ?>" title="<?php echo $file['raw_name']; ?>">
							<img src="<?php echo base_url($file['icon_url-32']); ?>" alt="<?php echo $file['name']; ?>" />
						</a>
					</div>
					<div class="media-title">
						<?php echo $file['name']; ?>
					</div>
					<input type="checkbox" name="rm[]" class="hidden-xs-up" value="<?php echo $file['path']; ?>" data-media="file" data-raw-name="<?php echo $file['raw_name']; ?>" data-path="<?php echo $file['path']; ?>">
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div>