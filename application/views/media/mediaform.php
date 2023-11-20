<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- file upload form -->        
<div id="collapse-upload" class="collapse media-forms">   
	<form id="upload-form" action="<?php echo site_url(CN_BASE.'do_upload'); ?>" method="post" enctype="multipart/form-data" class="form-inline dropzone" role="form">
		<div class="form-group fallback">              
			<label for="filedata">Subir archivo</label>      
			<input type="file" name="filedata[]" id="filedata" class="form-control form-control-sm" multiple>  
			<button class="btn btn-sm btn-primary"><span class="fa fa-upload"></span> Empezar a Subir</button>
		</div>  
		<div class="meter"><span class="roller"></span></div>             
		<button type="button" class="btn btn-sm btn-primary btn-upload"><span class="fa fa-upload"></span> Empezar a Subir</button>      
	</form>
	<p class="text-muted small"><em>(Tamaño máximo: <?php echo $this->config->item('max_size'); ?> MB)</em></p>
</div>      
<!-- /.file upload form -->
<!-- create folder form -->
<div id="collapse-folder" class="collapse media-forms">
	<form action="<?php echo site_url(CN_BASE.'create_folder'); ?>" method="post" class="form-inline" role="form">
		<div class="form-group">
			<label class="sr-only" for="folderpath">Ruta de la carpeta</label>
			<input type="text" id="folderpath" class="form-control form-control-sm" readonly value="<?php echo $this->session->userdata('path').'/'; ?>">
		</div>
		<div class="form-group">
			<label class="sr-only" for="foldername">Carpeta</label>
			<input type="text" name="foldername" id="foldername" class="form-control form-control-sm">
		</div>          
		<button type="submit" class="btn btn-secondary btn-sm"><span class="fa fa-folder-open"></span> Crear Carpeta</button>
	</form>
</div>
<!-- /.create folder form -->