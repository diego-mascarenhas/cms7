<?php
// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir archivos necesarios
require_once('config.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php'); // Incluir la biblioteca QRcode

// Obtener el hash de la factura
$hash = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($hash))
{
	die('Por favor proporciona un ID de factura usando el parámetro "id"');
}

// Conectar a la base de datos
$mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
if ($mysqli->connect_error)
{
	die('Error de conexión: ' . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// Buscar factura por hash
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
            1 AS id_documento_tipo,
            empresas_fiscales.cuit as documento_numero,
            sys_monedas.moneda
           FROM facturas
           LEFT JOIN facturas_tipo ON facturas.id_factura_tipo = facturas_tipo.id
           LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
           LEFT JOIN condiciones_iva ON empresas_fiscales.id_condicion_iva = condiciones_iva.id
           LEFT JOIN sys_monedas ON facturas.id_moneda = sys_monedas.id
           WHERE md5(CONCAT(facturas.grupo, facturas.id)) = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("s", $hash);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0)
{
	die('No se encontró la factura con el hash proporcionado');
}

$factura = $result->fetch_assoc();

// Obtener items de la factura
$query_items = "SELECT 
                facturas_items.id,
                facturas_items.descripcion, 
                facturas_items.valor, 
                facturas_items.descuento
               FROM facturas_items
               WHERE facturas_items.id_factura = ?";

$stmt = $mysqli->prepare($query_items);
$stmt->bind_param("i", $factura['id']);
$stmt->execute();
$items_result = $stmt->get_result();

$items = [];
while ($item = $items_result->fetch_object())
{
	$items[] = $item;
}
$factura['items'] = $items;

// Calculamos subtotal210 e imp210 si no existen
if (empty($factura['subtotal210']))
{
	$factura['subtotal210'] = $factura['bruto'] - $factura['descuento'];
}
if (empty($factura['imp210']))
{
	$factura['imp210'] = $factura['subtotal210'] * ($factura['impuesto'] / 100);
}

// Construir array CAE
$cae = [
	'CAE' => $factura['cae_numero'],
	'CAEFchVto' => $factura['cae_vencimiento'],
	'CbteDesde' => $factura['numero_factura']
];

// Determinar la plantilla
$template_file = 'templates/revisionalpha/' . $factura['cuit'] . '_' .
	str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT) . '_' .
	str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT) . '.php';

// Verificar si existe la plantilla
if (!file_exists($template_file))
{
	// Usar plantilla genérica si no existe la específica
	$template_file = 'templates/revisionalpha/simple.php';

	// Si no existe la plantilla genérica, mostrar error
	if (!file_exists($template_file))
	{
		die('No se encontró ninguna plantilla válida para esta factura');
	}
}

// Preparar datos para la plantilla
$numeroCodigoBarras = $factura['cuit'];
$numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
$numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
$numeroCodigoBarras .= $cae['CAE'];
$numeroCodigoBarras .= $cae['CAEFchVto'];
$codigoVerificador = DigitoVerificador($numeroCodigoBarras);
$numeroCodigoBarras .= $codigoVerificador;

// Corregir rutas de imágenes para que sean relativas
function corregirRutasHTML($html)
{
	// Reemplazar rutas absolutas por relativas
	$html = str_replace('/home/forge/cms.revisionalpha.com/pdfs/', '', $html);
	return $html;
}

// Generar código QR para AFIP
function generarQR($datos) {
	// Codificar datos en base64
	$base64 = base64_encode(json_encode($datos));
	
	// URL de la API de QR de AFIP
	$urlQR = "https://www.afip.gob.ar/fe/qr/?p=" . $base64;
	
	// Crear objeto QRcode con la URL
	$qrcode = new QRcode($urlQR, 'L');
	$qrcode_array = $qrcode->getBarcodeArray();
	
	// Generar HTML para el código QR
	$html = '<div style="text-align:center;">';
	$html .= '<div style="border:1px solid #000;width:150px;height:150px;margin:0 auto;">';
	$html .= '<table style="border-collapse:collapse;width:130px;height:130px;margin:10px auto;">';
	
	// Generar tabla para mostrar el código QR (cada celda es un módulo)
	$width = $qrcode_array['num_cols'];
	$height = $qrcode_array['num_rows'];
	
	for ($r = 0; $r < $height; $r++) {
		$html .= '<tr style="height:' . (130/$height) . 'px">';
		for ($c = 0; $c < $width; $c++) {
			$color = ($qrcode_array['bcode'][$r][$c] == 1) ? '#000' : '#fff';
			$html .= '<td style="width:' . (130/$width) . 'px;background-color:' . $color . '"></td>';
		}
		$html .= '</tr>';
	}
	
	$html .= '</table>';
	$html .= '</div>';
	$html .= '<p style="font-size:9px;margin-top:5px">Código QR AFIP</p>';
	$html .= '</div>';
	
	return $html;
}

// Armar código QR para AFIP
$codigoqr = array(
	'ver' => 1,
	'fecha' => date("Y-m-d", strtotime($factura['fecha'])),
	'cuit' => 30716710072,
	'ptoVta' => intval(str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT)),
	'tipoCmp' => intval($factura['id_afip']),
	'nroCmp' => intval(str_pad($factura['numero_factura'], 8, 0, STR_PAD_LEFT)),
	'importe' => floatval($factura['total_neto']),
	'moneda' => 'PES',
	'ctz' => 1,
	'tipoDocRec' => intval($factura['id_documento_tipo']),
	'nroDocRec' => floatval(str_replace('-', '', $factura['documento_numero'])),
	'tipoCodAut' => 'E',
	'codAut' => floatval($factura['cae_numero'])
);

// Cargar la plantilla directamente (sin cURL)
$_POST = [
	'numero_talonario' => str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT),
	'numero_factura' => str_pad($cae['CbteDesde'], 8, 0, STR_PAD_LEFT),
	'fecha' => date('d/m/Y', strtotime($factura['fecha'])),
	'vencimiento' => (!empty($factura['vencimiento'])) ? date('d/m/Y', strtotime($factura['vencimiento'])) : NULL,
	'impuesto' => $factura['impuesto'],
	'id_documento_tipo' => $factura['id_documento_tipo'],
	'documento_numero' => $factura['documento_numero'],
	'razon_social' => $factura['razon_social'],
	'condicion_iva' => $factura['condicion_iva'],
	'domicilio' => (!empty($factura['domicilio'])) ? $factura['domicilio'] .
		(($factura['codigo_postal']) ? ', ' . $factura['codigo_postal'] : '') .
		(($factura['provincia']) ? ', ' . $factura['provincia'] : '') .
		(($factura['pais']) ? ', ' . $factura['pais'] : '') : '',
	'items' => json_encode($factura['items']),
	'bruto' => $factura['bruto'],
	'descuento' => $factura['descuento'],
	'subtotal210' => $factura['subtotal210'],
	'imp210' => $factura['imp210'],
	'total_neto' => $factura['total_neto'],
	'CAE' => $cae['CAE'],
	'CAEFchVto' => date('d/m/Y', strtotime($cae['CAEFchVto'])),
	'numeroCodigoBarras' => $numeroCodigoBarras,
	'moneda' => $factura['moneda'],
	'qr_code' => generarQR($codigoqr) // Generar código QR usando la clase
];

// URL del código QR para AFIP
$codigoqrjson_base64 = "https://www.afip.gob.ar/fe/qr/?p=". base64_encode(json_encode($codigoqr));
$_POST['codigoqrjson_base64'] = $codigoqrjson_base64;

// Generar HTML directamente
ob_start();
include($template_file);
$html = ob_get_clean();

// Corregir rutas de imágenes
$html = corregirRutasHTML($html);

// Reemplazar el tag <barcode> por la imagen del código QR
if (strpos($html, '<barcode') !== false) {
	$html = preg_replace('/<barcode.*?<\/barcode>/s', $_POST['qr_code'], $html);
}

// Agregar el código QR si no está presente ya
if (strpos($html, 'Código QR AFIP') === false && strpos($html, '</tbody></table></td></tr>') !== false) {
	$qrHtml = '<tr><td style="text-align: center;">' . $_POST['qr_code'] . '</td></tr>';
	$html = str_replace('</tbody></table></td></tr>', $qrHtml . '</tbody></table></td></tr>', $html);
}

// Mostrar HTML
echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Factura HTML</title></head><body>';
echo $html;
echo '</body></html>';

// Cerrar conexiones
$mysqli->close();
?>