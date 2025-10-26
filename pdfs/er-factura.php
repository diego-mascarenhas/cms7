<?php
// Conectar a la base de datos
require_once('config.php');
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Recibir el hash directamente
$hash = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($hash)) {
    die("Error: No se proporcionó un hash");
}

// Buscar la factura por hash
$found = false;
$query = "SELECT id, grupo FROM facturas";
$result = $mysqli->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $hash_calculado = md5($row['grupo'] . $row['id']);
        if ($hash_calculado === $hash) {
            $id = $row['id'];
            $found = true;
            break;
        }
    }
    $result->free();
}

if (!$found) {
    die("Error: No se encontró la factura con el hash proporcionado");
}

// Obtener datos completos
$query = "SELECT 
         facturas.*,
         facturas_tipo.impuesto, 
         facturas_tipo.cuit, 
         facturas_tipo.id_afip,
         facturas_tipo.factura_tipo,
         facturas_tipo.plantilla,
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

$result = $mysqli->query($query);
if (!$result || $result->num_rows == 0) {
    die("Error: No se pudieron obtener los datos de la factura");
}

$factura = $result->fetch_assoc();

// Obtener items
$query = "SELECT * FROM facturas_items WHERE id_factura = " . $mysqli->real_escape_string($id);
$result_items = $mysqli->query($query);

$items = array();
if ($result_items) {
    while ($item = $result_items->fetch_assoc()) {
        $items[] = array(
            'descripcion' => $item['descripcion'],
            'valor' => $item['valor'],
            'descuento' => $item['descuento'],
            'impuesto' => $factura['impuesto'],
            'total_neto' => ($item['valor'] - $item['descuento']) * (1 + ($factura['impuesto']/100))
        );
    }
    $result_items->free();
}

$factura['items'] = $items;

// Datos del CAE
$cae_data = array(
    'CAE' => $factura['cae_numero'],
    'CAEFchVto' => $factura['cae_vencimiento'],
    'CbteDesde' => $factura['numero_factura']
);

// Generar PDF
require_once('html2pdf/html2pdf.class.php');

try {
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
    $html2pdf->setDefaultFont('helvetica');
    
    $plantilla = $factura['plantilla'] ?? 'templates/revisionalpha/30716710072_01_0001.php';
    
    if (file_exists($plantilla)) {
        ob_start();
        include($plantilla);
        $content = ob_get_clean();
        
        $html2pdf->writeHTML($content);
        
        $prefijo = ($factura['factura_tipo'] == 'A') ? 'COMPROBANTE A' : 'COMPROBANTE';
        $nombreArchivo = $prefijo . ' Nº ' . 
                        str_pad($factura['numero_talonario'], 4, '0', STR_PAD_LEFT) . '-' . 
                        str_pad($factura['numero_factura'], 8, '0', STR_PAD_LEFT) . '.PDF';
        
        $html2pdf->Output($nombreArchivo, 'I');
    } else {
        echo "Error: Plantilla no encontrada: " . $plantilla;
    }
} catch (HTML2PDF_exception $e) {
    echo $e->getMessage();
}

$mysqli->close();
?>
