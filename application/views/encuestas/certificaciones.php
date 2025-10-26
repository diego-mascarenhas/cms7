<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Certificaciones - <?php echo $detalle['titulo']; ?></title>
    <link href="<?php echo base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/plugins/dataTables/dataTables.bootstrap.css'); ?>" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; }
        .table-container { margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; font-weight: bold; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { margin: 5px; }
        .certificado-si { color: green; font-weight: bold; }
        .certificado-no { color: red; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <h2>ACADEMIA LIZAMA – <?php echo strtoupper($detalle['titulo']); ?></h2>
            <h3>REPORTE DE USUARIOS CERTIFICADOS</h3>
            <h4>MÓDULO <?php echo strtoupper($detalle['titulo']); ?></h4>
        </div>

        <div class="table-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Certificaciones - Evento: <?php echo $detalle['titulo']; ?></h4>
                            <a href="<?php echo base_url('encuestas/generar_pdf_certificaciones/' . $detalle['id']); ?>" 
                               class="btn btn-primary" target="_blank">
                                <i class="fa fa-download"></i> Descargar PDF
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="certificaciones-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ID Pedido</th>
                                            <th>ID Contacto</th>
                                            <th>ID Producto</th>
                                            <th>Certificó</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($certificaciones)): ?>
                                            <?php foreach ($certificaciones as $cert): ?>
                                                <tr>
                                                    <td><?php echo $cert['id']; ?></td>
                                                    <td><?php echo $cert['id_pedido']; ?></td>
                                                    <td><?php echo $cert['id_contacto']; ?></td>
                                                    <td><?php echo $cert['id_producto']; ?></td>
                                                    <td class="<?php echo ($cert['Certifico'] == 'SI') ? 'certificado-si' : 'certificado-no'; ?>">
                                                        <?php echo $cert['Certifico']; ?>
                                                    </td>
                                                    <td><?php echo $cert['nombre']; ?></td>
                                                    <td><?php echo $cert['apellido']; ?></td>
                                                    <td><?php echo $cert['email']; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No hay certificaciones registradas para este evento.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/dataTables/jquery.dataTables.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/dataTables/dataTables.bootstrap.js'); ?>"></script>
    
    <script>
        $(document).ready(function() {
            $('#certificaciones-table').DataTable({
                "language": {
                    "url": "<?php echo base_url('assets/js/plugins/dataTables/Spanish.json'); ?>"
                },
                "pageLength": 25,
                "order": [[6, "asc"], [5, "asc"]], // Ordenar por apellido, luego nombre
                "responsive": true
            });
        });
    </script>
</body>
</html> 