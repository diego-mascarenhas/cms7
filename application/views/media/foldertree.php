<?php defined('BASEPATH') OR exit('No direct script access allowed');

$path = $this->session->userdata('path');

?>

		<li>
			<?php 
			$class = !$path ? 'mediapath active' : 'mediapath';
			$icon = !$path ? 'fa-folder-open' : 'fa-folder';
			?>
			<a class="<?php echo $class; ?>" href="home"><span class="fa <?php echo $icon; ?>"></span> Home<?php // echo $this->config->item('media_path'); ?> <?php echo get_media_count(); ?></a>            
			<?php if(isset($foldertree)): ?>
				<?php generate_folder_tree($foldertree, $path); ?>
			<?php endif; ?>
		</li>

<?php
// Function to generate folder tree structure
function generate_folder_tree($arr, $path)
{  
	?>
	<ul class="child-item">
		<?php foreach($arr as $k => $v): ?>    
			<li>	
				<?php 
				$class = ($path == $arr[$k]['path']) ? 'mediapath active' : 'mediapath';
				$icon = ($path == $arr[$k]['path']) ? 'fa-folder-open' : 'fa-folder';
				?>		
				<a class="<?php echo $class; ?>" href="<?php echo $arr[$k]['path']; ?>"><span class="fa <?php echo $icon; ?>"></span> <?php echo $k; ?> <?php echo get_media_count($arr[$k]['path']); ?></a>				
				<?php if(isset($arr[$k]['children'])): ?>
					<?php generate_folder_tree($arr[$k]['children'], $path); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php 
}

function get_media_count($path = FALSE)
{
	$path = ($path) ? '/' . $path . '/' : '/';
	
	$ci = &get_instance();  
	$ci->db->where('user_id', $ci->session->userdata('auth_user'));
	$ci->db->where('file_path', $path);
	$result = $ci->db->count_all_results('media_v1');
	
	if ($result)
	{
		return '<span class="badge small btn-primary">' . $result . '</span>';
	}
}