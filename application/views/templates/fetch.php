<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Obtener Template desde URL</h4>
                    <div class="card-tools">
                        <a href="<?= base_url('templates') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver a la lista
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('templates/save') ?>" method="post">
                        <div class="form-group">
                            <label for="url">URL del Template</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="url" name="url" value="<?= isset($url) ? $url : 'https://cms.revisionalpha.com/templates/502/comunicaciones/tickets.php' ?>" required>
                                <div class="input-group-append">
                                    <button type="button" id="fetch-btn" class="btn btn-primary">Obtener Contenido</button>
                                </div>
                            </div>
                            <?php echo form_error('url', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="name">Nombre del Template</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= isset($template_name) ? $template_name : '' ?>" required>
                            <?php echo form_error('name', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Contenido</label>
                            <textarea class="form-control" id="content" name="content" rows="15" required><?= isset($content) ? $content : '' ?></textarea>
                            <?php echo form_error('content', '<div class="text-danger">', '</div>'); ?>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Guardar Template</button>
                            <a href="<?= base_url('templates') ?>" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Función para obtener contenido desde URL
    document.getElementById('fetch-btn').addEventListener('click', function() {
        var url = document.getElementById('url').value;
        
        if (!url) {
            alert('Por favor, ingrese una URL válida');
            return;
        }
        
        // Mostrar cargando
        document.getElementById('content').value = 'Cargando contenido...';
        
        // Hacer solicitud AJAX para obtener el contenido
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?= base_url('templates/get_content/') ?>' + encodeURIComponent(url), true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById('content').value = xhr.responseText;
                document.getElementById('name').value = url.split('/').pop();
            } else {
                document.getElementById('content').value = 'Error al obtener contenido: ' + xhr.statusText;
            }
        };
        
        xhr.onerror = function() {
            document.getElementById('content').value = 'Error de red al intentar obtener el contenido';
        };
        
        xhr.send();
    });
});
</script> 