<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluimos archivos necesarios
require_once 'config.php';
require_once 'funciones.php';

// Función para conectar a la base de datos usando mysqli
function conectarDB() {
    $mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
    
    if ($mysqli->connect_error) {
        die("Error de conexión a la base de datos: " . $mysqli->connect_error);
    }
    
    $mysqli->set_charset(CMS5_DB_CHARSET);
    return $mysqli;
}

// Función para obtener datos de la factura por ID o hash
function obtenerFactura($id_o_hash, $debug = false) {
    $mysqli = conectarDB();
    
    // Verificar si es un hash MD5 o un ID directo
    $is_hash = preg_match('/^[0-9a-f]{32}$/', $id_o_hash);
    $id = null;
    
    if ($is_hash) {
        // Es un hash MD5, buscar la factura correspondiente usando CONCAT(grupo, id)
        $query_hash = "SELECT id, grupo FROM facturas WHERE MD5(CONCAT(grupo, id)) = ?";
        $stmt_hash = $mysqli->prepare($query_hash);
        
        if (!$stmt_hash) {
            die("Error en la consulta de hash: " . $mysqli->error);
        }
        
        $stmt_hash->bind_param("s", $id_o_hash);
        $stmt_hash->execute();
        $result_hash = $stmt_hash->get_result();
        
        if ($result_hash->num_rows === 0) {
            die("No se encontró ninguna factura con el hash proporcionado: " . htmlspecialchars($id_o_hash));
        }
        
        $row = $result_hash->fetch_assoc();
        $id = $row['id'];
        $grupo = $row['grupo'];
        
        if ($debug) {
            echo "<p>Buscando factura con ID: " . $id . "</p>";
            echo "<p>Hash calculado: " . md5($grupo . $id) . "</p>";
            echo "<p>Hash recibido: " . $id_o_hash . "</p>";
            echo "<p>¿Coinciden? " . ((md5($grupo . $id) === $id_o_hash) ? "Sí" : "No") . "</p>";
        }
        
        $stmt_hash->close();
    } else {
        // Es un ID directo
        $id = intval($id_o_hash);
        if ($debug) {
            echo "<p>Buscando factura con ID directo: " . $id . "</p>";
        }
    }
    
    // Consulta principal para obtener datos de la factura
    $query = "SELECT facturas.*, 
              facturas_tipo.impuesto, facturas_tipo.cuit, facturas_tipo.id_afip, 
              facturas_tipo.factura_tipo, facturas_tipo.plantilla as template,
              empresas_fiscales.razon_social, empresas_fiscales.domicilio, 
              empresas_fiscales.codigo_postal, empresas_fiscales.provincia, 
              empresas_fiscales.pais, condiciones_iva.condicion_iva, 
              IFNULL(documentos_tipo.id, 80) AS id_documento_tipo, 
              empresas_fiscales.cuit AS documento_numero, 
              sys_monedas.moneda,
              facturas.SUBTOTAL210 as subtotal210,
              facturas.IMP210 as imp210
              FROM facturas 
              LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id 
              LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id 
              LEFT JOIN condiciones_iva ON empresas_fiscales.id_condicion_iva = condiciones_iva.id 
              LEFT JOIN documentos_tipo ON 1=1
              LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id 
              WHERE facturas.id = ?";
    
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("Error en la consulta principal: " . $mysqli->error);
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("No se encontró la factura con ID: " . $id);
    }
    
    $factura = $result->fetch_assoc();
    $stmt->close();
    
    // Asegurar que los campos necesarios existan
    if (!isset($factura['subtotal210'])) $factura['subtotal210'] = 0;
    if (!isset($factura['imp210'])) $factura['imp210'] = 0;
    
    // Usar una plantilla simplificada que no dependa de imágenes externas
    $factura['template'] = "templates/revisionalpha/simple.php";
    
    // Obtener los items de la factura
    $query_items = "SELECT * FROM facturas_items WHERE id_factura = ?";
    $stmt_items = $mysqli->prepare($query_items);
    $stmt_items->bind_param("i", $id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();
    
    $items = array();
    while ($item = $result_items->fetch_assoc()) {
        $items[] = (object) array(
            'id' => $item['id'],
            'descripcion' => $item['descripcion'],
            'valor' => $item['valor'],
            'descuento' => $item['descuento']
        );
    }
    
    $factura['items'] = $items;
    $stmt_items->close();
    
    // Generar información del CAE
    $cae = array(
        'CAE' => $factura['cae_numero'],
        'CAEFchVto' => $factura['cae_vencimiento'],
        'CbteDesde' => $factura['numero_factura']
    );
    
    $mysqli->close();
    
    return array('factura' => $factura, 'cae' => $cae);
}

// Función para generar un HTML simple para el PDF
function generarHtmlSimple($factura, $cae) {
    $html = '
    <style>
    body {font-family: Arial, sans-serif; margin: 0; padding: 20px;}
    h1 {font-size: 24px; margin-bottom: 10px;}
    h2 {font-size: 18px; margin-bottom: 10px;}
    .header {display: flex; justify-content: space-between; margin-bottom: 20px;}
    .info-box {border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;}
    table {width: 100%; border-collapse: collapse; margin-bottom: 20px;}
    th, td {border: 1px solid #ccc; padding: 8px; text-align: left;}
    th {background-color: #f2f2f2;}
    .totals {text-align: right;}
    </style>
    
    <div class="header">
        <div>
            <h1>REVISION ALPHA</h1>
            <p>revision alpha S.A.S.<br>
            Vuelta de Obligado 2443 Of. 403, CABA<br>
            +54.11 5274.8490<br>
            I.V.A. Responsable Inscripto</p>
        </div>
        <div>
            <h2>FACTURA '.$factura['factura_tipo'].' N° '.$factura['numero_talonario'].'-'.$factura['numero_factura'].'</h2>
            <p>Fecha: '.$factura['fecha'].'<br>
            CUIT: 30-71671007-2<br>
            ISIB: 1585344-06<br>
            Inicio de Actividad: 10/12/2019</p>
        </div>
    </div>
    
    <div class="info-box">
        <p><strong>Razón Social:</strong> '.$factura['razon_social'].'</p>
        '.(!empty($factura['domicilio']) ? '<p><strong>Domicilio:</strong> '.$factura['domicilio'].'</p>' : '').'
        <p><strong>IVA:</strong> '.$factura['condicion_iva'].'</p>
        '.(!empty($factura['documento_numero']) ? '<p><strong>CUIT:</strong> '.$factura['documento_numero'].'</p>' : '').'
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>';
    
    foreach ($factura['items'] as $item) {
        $html .= '
            <tr>
                <td>'.$item->descripcion.'</td>
                <td>$'.number_format($item->valor, 2, ',', '.').'</td>
            </tr>';
    }
    
    $html .= '
        </tbody>
    </table>
    
    <div class="totals">
        <p><strong>Subtotal:</strong> $'.number_format($factura['bruto'], 2, ',', '.').'</p>';
    
    if ($factura['descuento'] > 0) {
        $html .= '
        <p><strong>Descuento:</strong> $'.number_format($factura['descuento'], 2, ',', '.').'</p>
        <p><strong>Subtotal con descuento:</strong> $'.number_format($factura['subtotal210'], 2, ',', '.').'</p>';
    }
    
    $html .= '
        <p><strong>IVA 21%:</strong> $'.number_format($factura['imp210'], 2, ',', '.').'</p>
        <p><strong>Importe Total:</strong> $'.number_format($factura['total_neto'], 2, ',', '.').'</p>
    </div>
    
    <div class="info-box">
        <p><strong>CAE N°:</strong> '.$cae['CAE'].'</p>
        <p><strong>Vto. de CAE:</strong> '.$cae['CAEFchVto'].'</p>
    </div>';
    
    return $html;
}

// Verificar si estamos en modo debug HTML
$is_debug_html = isset($_GET['debug_html']) && $_GET['debug_html'] == '1';
$is_debug = isset($_GET['debug']) && $_GET['debug'] == '1';

// Procesamiento principal
if (!isset($_GET['id'])) {
    die("Error: Se requiere un ID de factura o hash");
}

// Obtener el ID o hash desde GET
$id_o_hash = $_GET['id'];

// Quitar extensión .pdf si existe
if (substr($id_o_hash, -4) === '.pdf') {
    $id_o_hash = substr($id_o_hash, 0, -4);
}

// Si estamos en modo debug_html, mostrar el HTML generado
if ($is_debug_html) {
    echo "<html><head><title>Debug HTML</title></head><body>";
    
    // Obtener datos de la factura
    $datos = obtenerFactura($id_o_hash, true);
    $factura = $datos['factura'];
    $cae = $datos['cae'];
    
    echo "<h1>Depuración de datos de factura</h1>";
    echo "<p>ID/Hash: " . htmlspecialchars($id_o_hash) . "</p>";
    
    echo "<h2>Datos de la factura:</h2>";
    echo "<pre>" . print_r($factura, true) . "</pre>";
    
    echo "<h2>Datos del CAE:</h2>";
    echo "<pre>" . print_r($cae, true) . "</pre>";
    
    echo "<h2>Intentando generar HTML con htmlParaPdf():</h2>";
    $html = htmlParaPdf($factura, $cae);
    
    if (empty($html)) {
        echo "<p style='color: red'>Error: La función htmlParaPdf() devolvió un resultado vacío.</p>";
        
        echo "<h2>Probando la función generarHtmlSimple:</h2>";
        $htmlSimple = generarHtmlSimple($factura, $cae);
        echo "<div style='border: 1px solid #ccc; padding: 20px; margin-top: 20px;'>";
        echo $htmlSimple;
        echo "</div>";
    } else {
        echo "<div style='border: 1px solid #ccc; padding: 20px; margin-top: 20px;'>";
        echo $html;
        echo "</div>";
    }
    
    echo "</body></html>";
    exit;
}

// Determinar si es para ver o descargar
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'ver';

try {
    // Obtener datos de la factura
    $datos = obtenerFactura($id_o_hash, $is_debug);
    $factura = $datos['factura'];
    $cae = $datos['cae'];
    
    if ($is_debug) {
        echo "<h1>Datos de la factura:</h1>";
        echo "<pre>" . print_r($factura, true) . "</pre>";
        
        echo "<h2>Datos del CAE:</h2>";
        echo "<pre>" . print_r($cae, true) . "</pre>";
        
        echo "<p>Para ver el HTML generado, añade <strong>?debug_html=1</strong> a la URL.</p>";
        exit;
    }
    
    // Generar el HTML para el PDF (primero intentar con htmlParaPdf)
    $html = htmlParaPdf($factura, $cae);
    
    // Si la función original falla, usar nuestra implementación simplificada
    if (empty($html)) {
        $html = generarHtmlSimple($factura, $cae);
    }
    
    // Crear el PDF
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(0, 0, 0, 0));
    $html2pdf->setDefaultFont('Arial');
    $html2pdf->writeHTML($html);
    
    // Definir el nombre del archivo
    $filename = "factura-" . $factura['numero_talonario'] . "-" . $factura['numero_factura'] . ".pdf";
    
    if ($accion === 'descargar') {
        // Enviar cabeceras para descargar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    } else {
        // Enviar cabeceras para mostrar el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
    }
    
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    $html2pdf->Output($filename, 'I');
    exit;
} catch (Exception $e) {
    echo "Error al generar el PDF: " . $e->getMessage();
    echo "<p>Para diagnóstico detallado, añade <strong>?debug_html=1</strong> a la URL.</p>";
}
?> 