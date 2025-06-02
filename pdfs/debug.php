<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Estilo para mejor visualización
echo '<style>
    body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; max-width: 1200px; margin: 0 auto; }
    h1, h2, h3 { background-color: #f5f5f5; padding: 10px; border-radius: 5px; }
    pre { background-color: #f9f9f9; padding: 10px; border-radius: 5px; overflow: auto; }
    table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .highlight { background-color: #ffffcc; padding: 2px 5px; border-radius: 3px; }
    .box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
</style>';

echo '<h1>Depuración de Factura PDF</h1>';

// Función para mostrar datos de manera legible
function prettyPrint($data) {
    if (is_array($data) || is_object($data)) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    } else {
        echo '<pre>' . htmlspecialchars($data) . '</pre>';
    }
}

// Obtener ID de factura
$id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($id)) {
    echo '<div class="error">Por favor proporciona un ID de factura (?id=XXXXX) o un hash (?hash=XXXXX)</div>';
    
    // Mostrar formulario para ingresar ID o hash
    echo '<div class="box">
        <form method="GET">
            <label for="id">ID de Factura:</label>
            <input type="text" name="id" id="id" placeholder="52918">
            <input type="submit" value="Buscar por ID">
        </form>
        <br>
        <form method="GET">
            <label for="hash">Hash MD5:</label>
            <input type="text" name="hash" id="hash" placeholder="265a2a1cfa2b3e109426ae7ba0e0cc1f">
            <input type="submit" value="Buscar por Hash">
        </form>
    </div>';
    
    exit;
}

// Si se proporcionó un hash en lugar de un ID
$hash = isset($_GET['hash']) ? $_GET['hash'] : '';
if (!empty($hash)) {
    echo '<h2>Buscando factura por hash: ' . htmlspecialchars($hash) . '</h2>';
}

// Incluir los archivos necesarios
require_once('config.php');

// Conectar a la base de datos
echo '<h2>Conectando a la base de datos</h2>';
echo '<p>Host: ' . htmlspecialchars(CMS5_DB_HOST) . '<br>';
echo 'Base de datos: ' . htmlspecialchars(CMS5_DB_NAME) . '</p>';

$mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
if ($mysqli->connect_error) {
    echo '<div class="error">Error de conexión: ' . htmlspecialchars($mysqli->connect_error) . '</div>';
    exit;
}
echo '<p class="success">Conexión a la base de datos exitosa</p>';

$mysqli->set_charset("utf8");

// Si se proporcionó un hash, buscar la factura por hash
if (!empty($hash)) {
    $query = "SELECT facturas.id, facturas.grupo, md5(CONCAT(facturas.grupo, facturas.id)) as calculated_hash 
              FROM facturas 
              WHERE md5(CONCAT(facturas.grupo, facturas.id)) = '" . $mysqli->real_escape_string($hash) . "'";
    
    echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';
    
    $result = $mysqli->query($query);
    if (!$result) {
        echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
        exit;
    }
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<p class="success">Factura encontrada por hash.</p>';
        echo '<p>ID de la factura: <span class="highlight">' . $row['id'] . '</span></p>';
        echo '<p>Grupo: <span class="highlight">' . $row['grupo'] . '</span></p>';
        echo '<p>Hash calculado: <span class="highlight">' . $row['calculated_hash'] . '</span></p>';
        
        // Usar el ID encontrado
        $id = $row['id'];
    } else {
        echo '<div class="error">No se encontró ninguna factura con el hash proporcionado.</div>';
        exit;
    }
    
    $result->free();
}

// Buscar factura por ID
echo '<h2>Obteniendo datos de la factura ID: ' . htmlspecialchars($id) . '</h2>';

$query = "SELECT 
           facturas.*,
           facturas_tipo.impuesto, 
           facturas_tipo.cuit, 
           facturas_tipo.id_afip,
           facturas_tipo.factura_tipo,
           empresas_fiscales.razon_social, 
           empresas_fiscales.domicilio, 
           empresas_fiscales.codigo_postal,
           empresas_fiscales.provincia, 
           empresas_fiscales.pais, 
           condiciones_iva.condicion_iva,
           empresas_fiscales.cuit as documento_numero,
           sys_monedas.moneda
          FROM facturas
          LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
          LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
          LEFT JOIN condiciones_iva ON empresas_fiscales.id_condicion_iva = condiciones_iva.id
          LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
          WHERE facturas.id = " . $mysqli->real_escape_string($id);

echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result) {
    echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
    exit;
}

if ($result->num_rows == 0) {
    echo '<div class="error">No se encontró ninguna factura con el ID proporcionado.</div>';
    exit;
}

$factura = $result->fetch_assoc();
$result->free();

echo '<h3>Datos básicos de la factura:</h3>';
prettyPrint($factura);

// Calcular hash MD5
$hash_calculado = md5($factura['grupo'] . $factura['id']);
echo '<h3>Hash MD5 calculado:</h3>';
echo '<p>grupo + id = ' . $factura['grupo'] . ' + ' . $factura['id'] . ' = <span class="highlight">' . $hash_calculado . '</span></p>';
echo '<p>URL para acceder a esta factura: <a href="index.php?accion=ver&id=' . $hash_calculado . '" target="_blank">index.php?accion=ver&id=' . $hash_calculado . '</a></p>';

// Obtener datos adicionales relacionados con la factura
echo '<h2>Obteniendo datos relacionados</h2>';

// Datos del tipo de factura
$query = "SELECT * FROM facturas_tipo WHERE id = " . $mysqli->real_escape_string($factura['id_factura_tipo']);
echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result) {
    echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
} else {
    if ($result->num_rows > 0) {
        $factura_tipo = $result->fetch_assoc();
        echo '<h3>Datos del tipo de factura:</h3>';
        prettyPrint($factura_tipo);
    } else {
        echo '<p class="error">No se encontró el tipo de factura.</p>';
    }
    $result->free();
}

// Datos del cliente
$query = "SELECT * FROM empresas_fiscales WHERE id = " . $mysqli->real_escape_string($factura['id_empresa_fiscal']);
echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result) {
    echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
} else {
    if ($result->num_rows > 0) {
        $empresa_fiscal = $result->fetch_assoc();
        echo '<h3>Datos del cliente:</h3>';
        prettyPrint($empresa_fiscal);
    } else {
        echo '<p class="error">No se encontró la empresa fiscal.</p>';
    }
    $result->free();
}

// Items de la factura
$query = "SELECT * FROM facturas_items WHERE id_factura = " . $mysqli->real_escape_string($id);
echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result) {
    echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
} else {
    echo '<h3>Items de la factura:</h3>';
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Descripción</th><th>Valor</th><th>Descuento</th></tr>';
        
        while ($item = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $item['id'] . '</td>';
            echo '<td>' . $item['descripcion'] . '</td>';
            echo '<td>' . $item['valor'] . '</td>';
            echo '<td>' . $item['descuento'] . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
    } else {
        echo '<p class="error">No se encontraron items para esta factura.</p>';
    }
    $result->free();
}

// Construir array completo para generar PDF
echo '<h2>Construyendo array completo para generar PDF</h2>';

// Obtener datos completos con joins
$query = "SELECT 
           facturas.*,
           facturas_tipo.impuesto, 
           facturas_tipo.cuit, 
           facturas_tipo.id_afip,
           facturas_tipo.factura_tipo,
           empresas_fiscales.razon_social, 
           empresas_fiscales.domicilio, 
           empresas_fiscales.codigo_postal,
           empresas_fiscales.provincia, 
           empresas_fiscales.pais, 
           condiciones_iva.condicion_iva,
           empresas_fiscales.cuit as documento_numero,
           sys_monedas.moneda
          FROM facturas
          LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
          LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
          LEFT JOIN condiciones_iva ON empresas_fiscales.id_condicion_iva = condiciones_iva.id
          LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
          WHERE facturas.id = " . $mysqli->real_escape_string($id);

echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result) {
    echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
} else {
    if ($result->num_rows > 0) {
        $factura_completa = $result->fetch_assoc();
        
        // Obtener items
        $query_items = "SELECT 
                    facturas_items.descripcion, 
                    facturas_items.valor, 
                    facturas_items.descuento, 
                    facturas_tipo.impuesto AS impuesto, 
                    ROUND((facturas_items.valor-facturas_items.descuento)*facturas_tipo.impuesto/100+(facturas_items.valor-facturas_items.descuento), 2) AS total_neto
                  FROM facturas_items
                  JOIN facturas ON facturas_items.id_factura = facturas.id
                  JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
                  WHERE facturas_items.id_factura = " . $mysqli->real_escape_string($id);
        
        $items_result = $mysqli->query($query_items);
        
        $items = [];
        if ($items_result) {
            while ($item = $items_result->fetch_assoc()) {
                $items[] = $item;
            }
            $items_result->free();
        }
        
        $factura_completa['items'] = $items;
        
        echo '<h3>Array completo para generar PDF:</h3>';
        prettyPrint($factura_completa);
        
        // Construir array CAE
        $cae = [
            'CAE' => $factura_completa['cae_numero'],
            'CAEFchVto' => $factura_completa['cae_vencimiento'],
            'CbteDesde' => $factura_completa['numero_factura']
        ];
        
        echo '<h3>Array CAE para generar PDF:</h3>';
        prettyPrint($cae);
        
        // Determinar la plantilla
        $template_file = $factura_completa['cuit'] . '_' . 
                       str_pad($factura_completa['id_afip'], 2, 0, STR_PAD_LEFT) . '_' . 
                       str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '.php';
        $template_path = 'templates/revisionalpha/' . $template_file;
        
        echo '<h3>Plantilla que se usaría:</h3>';
        echo '<p>Archivo: <span class="highlight">' . htmlspecialchars($template_path) . '</span></p>';
        echo '<p>¿Existe? ' . (file_exists($template_path) ? '<span class="success">Sí</span>' : '<span class="error">No</span>') . '</p>';
        
        // Construir ruta del PDF
        $pdf_name = 'COMPROBANTE ' . $factura_completa['factura_tipo'] . ' Nº ' . 
                  str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '-' . 
                  str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT) . '.PDF';
        
        $pdf_path = 'pdfs/' . $factura_completa['cuit'] . '_' . 
                  str_pad($factura_completa['id_afip'], 2, 0, STR_PAD_LEFT) . '_' . 
                  str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '_' . 
                  str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT) . '.pdf';
        
        echo '<h3>Archivo PDF:</h3>';
        echo '<p>Nombre: <span class="highlight">' . htmlspecialchars($pdf_name) . '</span></p>';
        echo '<p>Ruta: <span class="highlight">' . htmlspecialchars($pdf_path) . '</span></p>';
        echo '<p>¿Existe? ' . (file_exists($pdf_path) ? '<span class="success">Sí</span>' : '<span class="error">No</span>') . '</p>';
        
        // Enlaces para generar o ver el PDF
        echo '<h2>Acciones</h2>';
        echo '<p><a href="index.php?accion=ver&id=' . $hash_calculado . '" target="_blank">Ver PDF</a></p>';
        echo '<p><a href="index.php?accion=descargar&id=' . $hash_calculado . '" target="_blank">Descargar PDF</a></p>';
    } else {
        echo '<div class="error">No se encontró la factura completa.</div>';
    }
    $result->free();
}

// Mostrar estructura de directorios
echo '<h2>Estructura de directorios</h2>';

echo '<h3>Directorio actual:</h3>';
echo '<p>' . htmlspecialchars(__DIR__) . '</p>';

echo '<h3>Contenido del directorio:</h3>';
$files = scandir(__DIR__);
echo '<ul>';
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $type = is_dir(__DIR__ . '/' . $file) ? 'Directorio' : 'Archivo';
        echo '<li>' . htmlspecialchars($file) . ' (' . $type . ')</li>';
    }
}
echo '</ul>';

// Verificar si existe el directorio pdfs/ y su contenido
echo '<h3>Directorio pdfs/:</h3>';
$pdfs_dir = __DIR__ . '/pdfs';
if (is_dir($pdfs_dir)) {
    echo '<p class="success">El directorio pdfs/ existe.</p>';
    
    $pdf_files = scandir($pdfs_dir);
    echo '<ul>';
    foreach ($pdf_files as $file) {
        if ($file != '.' && $file != '..') {
            echo '<li>' . htmlspecialchars($file) . '</li>';
        }
    }
    echo '</ul>';
} else {
    echo '<p class="error">El directorio pdfs/ no existe.</p>';
    
    // Intentar crear el directorio
    echo '<p>Intentando crear el directorio pdfs/...</p>';
    if (mkdir($pdfs_dir, 0755)) {
        echo '<p class="success">Directorio pdfs/ creado correctamente.</p>';
    } else {
        echo '<p class="error">No se pudo crear el directorio pdfs/.</p>';
    }
}

// Verificar el directorio templates/
echo '<h3>Directorio templates/:</h3>';
$templates_dir = __DIR__ . '/templates';
if (is_dir($templates_dir)) {
    echo '<p class="success">El directorio templates/ existe.</p>';
    
    // Verificar directorio revisionalpha/
    $revisionalpha_dir = $templates_dir . '/revisionalpha';
    if (is_dir($revisionalpha_dir)) {
        echo '<p class="success">El directorio templates/revisionalpha/ existe.</p>';
        
        $template_files = scandir($revisionalpha_dir);
        echo '<ul>';
        foreach ($template_files as $file) {
            if ($file != '.' && $file != '..' && strpos($file, '.php') !== false) {
                echo '<li>' . htmlspecialchars($file) . '</li>';
            }
        }
        echo '</ul>';
    } else {
        echo '<p class="error">El directorio templates/revisionalpha/ no existe.</p>';
    }
} else {
    echo '<p class="error">El directorio templates/ no existe.</p>';
}

// Cerrar la conexión
$mysqli->close();
?>