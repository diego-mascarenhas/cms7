<?php

// Incluimos archivos necesarios
require_once('config.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php'); // Incluir la biblioteca QRcode

// Función para registrar errores en log
function logError($message)
{
	error_log($message);

	// Si estamos en modo debug, mostrar el error
	if (isset($_GET['debug']) && $_GET['debug'] == '1')
	{
		echo "<p>ERROR: " . htmlspecialchars($message) . "</p>";
	}
}

// Función para mostrar datos de manera legible
function prettyPrint($data)
{
	if (is_array($data) || is_object($data))
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	else
	{
		echo '<pre>' . htmlspecialchars($data) . '</pre>';
	}
}


// En CodeIgniter, podemos obtener el segmento de la URL
// Verificar si estamos en un contexto de CodeIgniter
if (function_exists('get_instance'))
{
	// Estamos dentro de CodeIgniter
	$CI = &get_instance();
	$hash = $CI->uri->segment(2); // El hash debería estar en el segmento 2
}
else
{
	// No estamos dentro de un controlador de CodeIgniter, usamos un enfoque alternativo
	$requestUri = $_SERVER['REQUEST_URI'];
	$segments = explode('/', trim(parse_url($requestUri, PHP_URL_PATH), '/'));
	$hash = isset($segments[1]) ? $segments[1] : '';
}


// Verificar si hay un hash en la URL
if (!empty($hash) && $hash != 'index')
{
	// Quitar extensión .pdf si existe
	if (substr($hash, -4) === '.pdf')
	{
		$hash = substr($hash, 0, -4);
	}

	// Mostrar hash de la factura
	// echo "Hash de la factura: " . $hash;
}


// Conectar a la base de datos
// echo '<h2>Conectando a la base de datos</h2>';
// echo '<p>Host: ' . htmlspecialchars(CMS5_DB_HOST) . '<br>';
// echo 'Base de datos: ' . htmlspecialchars(CMS5_DB_NAME) . '</p>';

$mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
if ($mysqli->connect_error)
{
	echo '<div class="error">Error de conexión: ' . htmlspecialchars($mysqli->connect_error) . '</div>';
	exit;
}
// echo '<p class="success">Conexión a la base de datos exitosa</p>';

$mysqli->set_charset("utf8");


// Si se proporcionó un hash, buscar la factura por hash
if (!empty($hash))
{
	$query = "SELECT facturas.id, facturas.grupo, md5(CONCAT(facturas.grupo, facturas.id)) as calculated_hash 
              FROM facturas 
              WHERE md5(CONCAT(facturas.grupo, facturas.id)) = '" . $mysqli->real_escape_string($hash) . "'";

	// echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

	$result = $mysqli->query($query);
	if (!$result)
	{
		echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
		exit;
	}

	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		// echo '<p class="success">Factura encontrada por hash.</p>';
		// echo '<p>ID de la factura: <span class="highlight">' . $row['id'] . '</span></p>';
		// echo '<p>Grupo: <span class="highlight">' . $row['grupo'] . '</span></p>';
		// echo '<p>Hash calculado: <span class="highlight">' . $row['calculated_hash'] . '</span></p>';

		// Usar el ID encontrado
		$id = $row['id'];
	}
	else
	{
		echo '<div class="error">No se encontró ninguna factura con el hash proporcionado.</div>';
		exit;
	}

	$result->free();
}


// Construir array completo para generar PDF
// echo '<h2>Construyendo array completo para generar PDF</h2>';

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

// echo '<p>Ejecutando consulta: <code>' . htmlspecialchars($query) . '</code></p>';

$result = $mysqli->query($query);
if (!$result)
{
	echo '<div class="error">Error en la consulta: ' . htmlspecialchars($mysqli->error) . '</div>';
}
else
{
	if ($result->num_rows > 0)
	{
		$factura_completa = $result->fetch_assoc();

		$factura_completa['numero_talonario'] = str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT);
		$factura_completa['numero_factura'] = str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT);
		$factura_completa['fecha'] = date('d/m/Y', strtotime($factura_completa['fecha']));
		$factura_completa['vencimiento'] = date('d/m/Y', strtotime($factura_completa['vencimiento']));


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
		if ($items_result)
		{
			while ($item = $items_result->fetch_assoc())
			{
				$items[] = $item;
			}
			$items_result->free();
		}

		$factura_completa['items'] = json_encode($items);

		// echo '<h3>Array completo para generar PDF:</h3>';
		// prettyPrint($factura_completa);

		// Construir array CAE
		$factura_completa['CAE'] = $factura_completa['cae_numero'];
		$factura_completa['CAEFchVto'] = $factura_completa['cae_vencimiento'];
		$factura_completa['CbteDesde'] = $factura_completa['numero_factura'];

		$cae = [
			'CAE' => $factura_completa['cae_numero'],
			'CAEFchVto' => $factura_completa['cae_vencimiento'],
			'CbteDesde' => $factura_completa['numero_factura'],
		];


		// echo '<h3>Array CAE para generar PDF:</h3>';
		// prettyPrint($cae);


		// codigo de barras
		// $numeroCodigoBarras = $factura['cuit'];
		// $numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
		// $numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
		// $numeroCodigoBarras .= $cae['CAE'];
		// $numeroCodigoBarras .= $cae['CAEFchVto'];
		// $codigoVerificador = DigitoVerificador($numeroCodigoBarras);
		// $numeroCodigoBarras .= $codigoVerificador;

		// $factura_completa['numeroCodigoBarras'] = $numeroCodigoBarras;

		// Armar código QR para AFIP
		$codigoqr = array(
			'ver' => 1,
			'fecha' => date("Y-m-d", strtotime($factura_completa['fecha'])),
			'cuit' => 30716710072,
			'ptoVta' => intval(str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT)),
			'tipoCmp' => intval($factura_completa['id_afip']),
			'nroCmp' => intval(str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT)),
			'importe' => floatval($factura_completa['total_neto']),
			'moneda' => 'PES',
			'ctz' => 1,
			'tipoDocRec' => intval($factura_completa['id_documento_tipo']),
			'nroDocRec' => floatval(str_replace('-', '', $factura_completa['documento_numero'])),
			'tipoCodAut' => 'E',
			'codAut' => floatval($factura_completa['cae_numero'])
		);

		$factura_completa['qr'] = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode(json_encode($codigoqr));
		echo generarQR($factura_completa['qr']);

		// Determinar la plantilla
		$template_file = $factura_completa['cuit'] . '_' .
			str_pad($factura_completa['id_afip'], 2, 0, STR_PAD_LEFT) . '_' .
			str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '.php';
		$template_path = 'templates/revisionalpha/' . $template_file;

		// echo '<h3>Plantilla que se usaría:</h3>';
		// echo '<p>Archivo: <span class="highlight">' . htmlspecialchars($template_path) . '</span></p>';
		// echo '<p>¿Existe? ' . (file_exists($template_path) ? '<span class="success">Sí</span>' : '<span class="error">No</span>') . '</p>';

		// Construir ruta del PDF
		$pdf_name = 'REVISION ALPHA ' . $factura_completa['factura_tipo'] . ' ' .
			str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '-' .
			str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT) . '.PDF';

		// $pdf_path = 'pdfs/' . $factura_completa['cuit'] . '_' .
		//     str_pad($factura_completa['id_afip'], 2, 0, STR_PAD_LEFT) . '_' .
		//     str_pad($factura_completa['numero_talonario'], 4, 0, STR_PAD_LEFT) . '_' .
		//     str_pad($factura_completa['numero_factura'], 8, 0, STR_PAD_LEFT) . '.pdf';

		// Incluir el generador de PDF y pasarle los datos
		// echo $factura_completa;
		// echo $cae_data;
	}
	else
	{
		echo '<div class="error">No se encontró la factura completa.</div>';
	}
	$result->free();
}

// Include the HTML2PDF library
require_once('html2pdf/html2pdf.class.php');

// Sample data that would normally come from a form or database
// $_POST = [
//     'numero_talonario' => '0001',
//     'numero_factura' => '00000123',
//     'vencimiento' => '30/11/2023',
//     'fecha' => date('d/m/Y'),
//     'razon_social' => 'Cliente Ejemplo S.A.',
//     'domicilio' => 'Av. Corrientes 1234, CABA',
//     'condicion_iva' => 'Responsable Inscripto',
//     'id_documento_tipo' => '80',
//     'documento_numero' => '30123456789',
//     'items' => json_encode([
//         ['descripcion' => 'Servicio de desarrollo web', 'valor' => 50000],
//         ['descripcion' => 'Mantenimiento mensual', 'valor' => 25000],
//     ]),
//     'bruto' => 75000,
//     'descuento' => 0,
//     'subtotal210' => 75000,
//     'imp210' => 15750,
//     'total_neto' => 90750,
//     'CAE' => '71234567890123',
//     'CAEFchVto' => '10/12/2023',
//     'numeroCodigoBarras' => '307167100720001000001237123456789012320231210'
// ];

$_POST = $factura_completa;

// Add custom fonts and styling based on revisionalpha brand styles
$customStyles = <<<EOD
<style type="text/css">
    @font-face {
        font-family: 'proxima-l';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-light.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-r';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-regular.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-sb';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-semibold.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-b';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-bold.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    .tw-light { font-family: 'proxima-l'; }
    .tw-regular { font-family: 'proxima-r'; }
    .tw-bold { font-family: 'proxima-b'; }
    .tw-semibold, strong { font-family: 'proxima-sb'; font-weight: normal; }
    
    .tc-red-5 { color: #FF1A1D !important; }
    .bc-red-5 { background-color: #FF1A1D !important; }
    
    h1, h2, h3, h4 { font-family: 'proxima-sb'; }
    body { font-family: 'proxima-r'; color: #808080; }
    
    .section-title { display: block; font-family: 'proxima-l'; font-size: 40px; line-height: 1.3; margin: 0; }
    .section-title.section-title-small { font-size: 26px; line-height: 1.5; }
    .section-title.section-title-full { font-size: 26px; font-family: 'proxima-sb'; border-bottom: 3px solid #FF1A1D; text-transform: uppercase; }
    .section-title > span { display: inline-block; border-bottom: 3px solid #FF1A1D; }
    
    .general-title { display: block; font-family: 'proxima-sb'; font-size: 40px; line-height: 1.3; margin: 0; }
    .general-title > span { display: inline-block; border-bottom: 3px solid lightgrey; padding-bottom: 15px; }
    
    .form-subtitle { font-size: 20px; font-family: 'proxima-l'; text-transform: uppercase; margin-bottom: 15px; color: #5CA7D7; }
</style>
EOD;

try
{
	// Initialize HTML2PDF with Portrait orientation, A4 format, Spanish language
	$html2pdf = new HTML2PDF('P', 'A4', 'es');

	// Set default font to helvetica instead of Arial (helvetica is included by default in TCPDF)
	$html2pdf->setDefaultFont('helvetica');

	// Buffer the template output with custom styles
	ob_start();
	echo $customStyles;
	
	// include('templates/revisionalpha/30716710072_01_0001.php');
	include($template_path);
	$content = ob_get_clean();

	// Add the template content to the PDF
	$html2pdf->writeHTML($content);

	// Output the PDF (D: force download, I: display in browser)
	$html2pdf->Output($pdf_name, 'I');
}
catch (HTML2PDF_exception $e)
{
	echo $e->getMessage();
}


// $numeroCodigoBarras = $factura['cuit'];
// $numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
// $numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
// $numeroCodigoBarras .= $cae['CAE'];
// $numeroCodigoBarras .= $cae['CAEFchVto'];
// $codigoVerificador = DigitoVerificador($numeroCodigoBarras);
// $numeroCodigoBarras .= $codigoVerificador;


// arma qr 
// $codigoqr = array(
// 	'ver' => 1,
// 	'fecha' => date("Y-m-d", strtotime($factura['fecha'])),
// 	'cuit' => 30716710072, 
// 	'ptoVta' => intval(str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT)),
// 	'tipoCmp' => intval($factura['id_afip']),
// 	'nroCmp' => intval(str_pad($factura['numero_factura'], 8, 0, STR_PAD_LEFT)),
// 	'importe' => floatval($factura['total_neto']),
// 	'moneda' => 'PES',
// 	'ctz' => 1,
// 	'tipoDocRec' => intval($factura['id_documento_tipo']),
// 	'nroDocRec' => floatval($factura['documento_numero']),
// 	'tipoCodAut' => 'E',
// 	'codAut' => floatval($factura['cae_numero'])
//  );
//  $codigoqrjson = json_encode($codigoqr);
//  $codigoqrjson_base64 = "https://www.afip.gob.ar/fe/qr/?p=". base64_encode($codigoqrjson);
//  $envio['codigoqrjson_base64'] = $codigoqrjson_base64;