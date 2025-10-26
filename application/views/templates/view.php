<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detalles del Template: <?= $template['name'] ?></h4>
                    <div class="card-tools">
                        <a href="<?= base_url('templates') ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver a la lista
                        </a>
                        <a href="<?= base_url('templates/fetch/' . urlencode($template['url_source'])) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-sync"></i> Actualizar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Información</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td><?= $template['id'] ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <td><?= $template['name'] ?></td>
                                </tr>
                                <tr>
                                    <th>URL Fuente</th>
                                    <td>
                                        <a href="<?= $template['url_source'] ?>" target="_blank">
                                            <?= $template['url_source'] ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fecha Creación</th>
                                    <td><?= $template['date_created'] ?></td>
                                </tr>
                                <tr>
                                    <th>Última Actualización</th>
                                    <td><?= $template['date_updated'] ?></td>
                                </tr>
                                <tr>
                                    <th>Descripción</th>
                                    <td><?= $template['description'] ?? 'Sin descripción' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Vista previa</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?= nl2br(htmlspecialchars($template['content'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Contenido Raw</h5>
                            <div class="form-group">
                                <textarea class="form-control" rows="10" readonly><?= $template['content'] ?></textarea>
                            </div>
                            <button class="btn btn-info" onclick="copyContent()">
                                <i class="fas fa-copy"></i> Copiar Contenido
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function copyContent() {
    var textarea = document.querySelector('textarea');
    textarea.select();
    document.execCommand('copy');
    
    // Mostrar mensaje de copiado
    var originalText = document.querySelector('.btn-info').innerHTML;
    document.querySelector('.btn-info').innerHTML = '<i class="fas fa-check"></i> ¡Copiado!';
    
    setTimeout(function() {
        document.querySelector('.btn-info').innerHTML = originalText;
    }, 2000);
}
</script> 