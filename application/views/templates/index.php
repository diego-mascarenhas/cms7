<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Templates</h4>
                    <div class="card-tools">
                        <a href="<?= base_url('templates/fetch') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Obtener Nuevo Template
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($templates)): ?>
                        <div class="alert alert-info">
                            No hay templates disponibles. Puede obtener uno nuevo haciendo clic en el botón "Obtener Nuevo Template".
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>URL Fuente</th>
                                        <th>Fecha Creación</th>
                                        <th>Última Actualización</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($templates as $template): ?>
                                        <tr>
                                            <td><?= $template['id'] ?></td>
                                            <td><?= $template['name'] ?></td>
                                            <td>
                                                <a href="<?= $template['url_source'] ?>" target="_blank">
                                                    <?= basename($template['url_source']) ?>
                                                </a>
                                            </td>
                                            <td><?= $template['date_created'] ?></td>
                                            <td><?= $template['date_updated'] ?></td>
                                            <td>
                                                <a href="<?= base_url('templates/view/' . $template['id']) ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="<?= base_url('templates/fetch/' . urlencode($template['url_source'])) ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-sync"></i> Actualizar
                                                </a>
                                                <a href="<?= base_url('templates/delete/' . $template['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este template?')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 