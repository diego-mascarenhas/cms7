<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluimos archivos necesarios
require_once 'config.php';
require_once 'funciones.php';

// Función para registrar errores en log
function logError($message) {
	error_log($message);
	
	// Si estamos en modo debug, mostrar el error
	if (isset($_GET['debug']) && $_GET['debug'] == '1') {
		echo "<p>ERROR: " . htmlspecialchars($message) . "</p>";
	}
}

// Función para conectar a la base de datos usando mysqli
function conectarDB() {
	$mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
	
	if ($mysqli->connect_error) {
		logError("Error de conexión a la base de datos: " . $mysqli->connect_error);
		die("Error al conectar con la base de datos. Por favor, inténtelo más tarde.");
	}
	
	$mysqli->set_charset(CMS5_DB_CHARSET);
	return $mysqli;
}

// Función para obtener datos de la factura por ID o hash
function obtenerFactura($id_o_hash, $debug = false) {
	try {
		$mysqli = conectarDB();
		
		// Verificar si es un hash MD5 o un ID directo
		$is_hash = preg_match('/^[0-9a-f]{32}$/', $id_o_hash);
		$id = null;
		
		if ($is_hash) {
			// Es un hash MD5, buscar la factura correspondiente usando CONCAT(grupo, id)
			$query_hash = "SELECT id, grupo FROM facturas WHERE MD5(CONCAT(grupo, id)) = ?";
			$stmt_hash = $mysqli->prepare($query_hash);
			
			if (!$stmt_hash) {
				logError("Error en la consulta de hash: " . $mysqli->error);
				die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
			}
			
			$stmt_hash->bind_param("s", $id_o_hash);
			$stmt_hash->execute();
			$result_hash = $stmt_hash->get_result();
			
			if ($result_hash->num_rows === 0) {
				logError("No se encontró factura con hash: " . $id_o_hash);
				die("No se encontró la factura solicitada.");
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
			logError("Error en la consulta principal: " . $mysqli->error);
			die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
		}
		
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$result = $stmt->get_result();
		
		if ($result->num_rows === 0) {
			logError("No se encontró factura con ID: " . $id);
			die("No se encontró la factura solicitada.");
		}
		
		$factura = $result->fetch_assoc();
		$stmt->close();
		
		// Verificar que los campos necesarios existan
		if (empty($factura['template'])) {
			logError("No se encontró la plantilla para la factura ID: " . $id);
			if ($debug) {
				echo "<p>ERROR: No se encontró la plantilla para esta factura (template está vacío)</p>";
			}
		}
		
		if (!isset($factura['subtotal210'])) {
			if ($debug) {
				echo "<p>AVISO: No se encontró el campo subtotal210 para esta factura, usando valor predeterminado</p>";
			}
			$factura['subtotal210'] = 0;
		}
		
		if (!isset($factura['imp210'])) {
			if ($debug) {
				echo "<p>AVISO: No se encontró el campo imp210 para esta factura, usando valor predeterminado</p>";
			}
			$factura['imp210'] = 0;
		}
		
		// Obtener los items de la factura
		$query_items = "SELECT * FROM facturas_items WHERE id_factura = ?";
		$stmt_items = $mysqli->prepare($query_items);
		if (!$stmt_items) {
			logError("Error en la consulta de items: " . $mysqli->error);
			if ($debug) {
				echo "<p>ERROR: No se pudieron obtener los items de la factura</p>";
			}
			$items = array();
		} else {
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
			
			$stmt_items->close();
		}
		
		$factura['items'] = $items;
		
		// Generar información del CAE
		$cae = array(
			'CAE' => $factura['cae_numero'],
			'CAEFchVto' => $factura['cae_vencimiento'],
			'CbteDesde' => $factura['numero_factura']
		);
		
		$mysqli->close();
		
		return array('factura' => $factura, 'cae' => $cae);
	} catch (Exception $e) {
		logError("Excepción en obtenerFactura: " . $e->getMessage());
		die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
	}
}

// Depuración completa de htmlParaPdf para identificar errores
function debugHtmlParaPdf($factura, $cae) {
	echo "<h2>Depuración de htmlParaPdf</h2>";
	
	// Verificar campos críticos
	echo "<h3>Verificación de campos críticos:</h3>";
	echo "<ul>";
	echo "<li>cuit: " . (isset($factura['cuit']) ? htmlspecialchars($factura['cuit']) : "NO EXISTE") . "</li>";
	echo "<li>id_afip: " . (isset($factura['id_afip']) ? htmlspecialchars($factura['id_afip']) : "NO EXISTE") . "</li>";
	echo "<li>numero_talonario: " . (isset($factura['numero_talonario']) ? htmlspecialchars($factura['numero_talonario']) : "NO EXISTE") . "</li>";
	echo "<li>CAE: " . (isset($cae['CAE']) ? htmlspecialchars($cae['CAE']) : "NO EXISTE") . "</li>";
	echo "<li>CAEFchVto: " . (isset($cae['CAEFchVto']) ? htmlspecialchars($cae['CAEFchVto']) : "NO EXISTE") . "</li>";
	echo "<li>template: " . (isset($factura['template']) ? htmlspecialchars($factura['template']) : "NO EXISTE") . "</li>";
	echo "</ul>";
	
	// Verificar datos para DigitoVerificador
	if (isset($factura['cuit']) && isset($factura['id_afip']) && isset($factura['numero_talonario']) && isset($cae['CAE']) && isset($cae['CAEFchVto'])) {
		echo "<h3>Datos para DigitoVerificador:</h3>";
		$numeroCodigoBarras = $factura['cuit'];
		$numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
		$numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
		$numeroCodigoBarras .= $cae['CAE'];
		$numeroCodigoBarras .= $cae['CAEFchVto'];
		
		echo "<p>numeroCodigoBarras: " . htmlspecialchars($numeroCodigoBarras) . "</p>";
		
		// Intentar calcular el dígito verificador
		echo "<p>Intentando calcular DigitoVerificador...</p>";
		
		// Asegurarse de que todos los valores son numéricos
		$numeroCodigoBarrasNumerico = preg_replace('/[^0-9]/', '', $numeroCodigoBarras);
		echo "<p>numeroCodigoBarras (solo números): " . htmlspecialchars($numeroCodigoBarrasNumerico) . "</p>";
		
		if ($numeroCodigoBarrasNumerico !== $numeroCodigoBarras) {
			echo "<p style='color: red;'>ADVERTENCIA: El código de barras contiene caracteres no numéricos</p>";
		}
		
		// Intentar la operación de DigitoVerificador paso a paso
		echo "<h4>Cálculo paso a paso:</h4>";
		
		$totalpares = 0;
		$totalimpares = 0;
		
		echo "<p>Recorriendo caracteres impares (índices 1, 3, 5...):</p>";
		for ($i = 1; $i < strlen($numeroCodigoBarrasNumerico); $i = $i + 2) {
			$char = substr($numeroCodigoBarrasNumerico, $i, 1);
			echo "Índice $i: Carácter '$char', Suma parcial: $totalimpares + $char = ";
			$totalimpares = $totalimpares + intval($char);
			echo "$totalimpares<br>";
		}
		
		echo "<p>Recorriendo caracteres pares (índices 0, 2, 4...):</p>";
		for ($i = 0; $i < strlen($numeroCodigoBarrasNumerico); $i = $i + 2) {
			$char = substr($numeroCodigoBarrasNumerico, $i, 1);
			echo "Índice $i: Carácter '$char', Suma parcial: $totalpares + $char = ";
			$totalpares = $totalpares + intval($char);
			echo "$totalpares<br>";
		}
		
		$suma = $totalimpares + $totalpares;
		echo "<p>Suma total (impares + pares): $totalimpares + $totalpares = $suma</p>";
		
		$digito = 0;
		echo "<p>Cálculo del dígito verificador:</p>";
		echo "Suma inicial: $suma, Dígito inicial: $digito<br>";
		
		while ((intval($suma / 10) * 10) != $suma) {
			$suma = $suma + 1;
			$digito = $digito + 1;
			echo "Nueva suma: $suma, Nuevo dígito: $digito<br>";
		}
		
		echo "<p>Dígito verificador final: $digito</p>";
	} else {
		echo "<p style='color: red;'>No se pueden generar los datos para DigitoVerificador porque faltan campos críticos</p>";
	}
	
	// Verificar curl request
	if (isset($factura['template'])) {
		echo "<h3>Verificación de la petición curl:</h3>";
		echo "<p>URL de la plantilla: " . htmlspecialchars($factura['template']) . "</p>";
		
		// Intentar hacer una petición de prueba
		echo "<p>Intentando hacer una petición de prueba a la plantilla...</p>";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $factura['template']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		curl_close($ch);
		
		echo "<p>Código HTTP: $httpCode</p>";
		if (!empty($error)) {
			echo "<p style='color: red;'>Error de cURL: " . htmlspecialchars($error) . "</p>";
		}
		
		if ($httpCode != 200) {
			echo "<p style='color: red;'>La plantilla no está accesible (código HTTP $httpCode)</p>";
		} else {
			echo "<p style='color: green;'>La plantilla parece estar accesible</p>";
		}
	} else {
		echo "<p style='color: red;'>No se puede verificar la petición curl porque falta la URL de la plantilla</p>";
	}
	
	// Mostrar datos completos de la factura y el CAE
	echo "<h3>Datos completos de la factura:</h3>";
	echo "<pre>" . print_r($factura, true) . "</pre>";
	
	echo "<h3>Datos completos del CAE:</h3>";
	echo "<pre>" . print_r($cae, true) . "</pre>";
}

// Verificar si es una petición para ver/descargar un PDF o si es modo depuración
$is_debug = isset($_GET['debug']) && $_GET['debug'] == '1';
$is_debug_html = isset($_GET['debug_html']) && $_GET['debug_html'] == '1';
$is_debug_raw = isset($_GET['debug_raw']) && $_GET['debug_raw'] == '1';

// Procesamiento principal
try {
	if (!isset($_GET['id'])) {
		die("Error: Se requiere un ID de factura o hash");
	}
	
	// Obtener el ID o hash desde GET
	$id_o_hash = $_GET['id'];
	
	// Quitar extensión .pdf si existe
	if (substr($id_o_hash, -4) === '.pdf') {
		$id_o_hash = substr($id_o_hash, 0, -4);
	}
	
	// Si estamos en modo debug_raw, mostrar el HTML sin procesar
	if ($is_debug_raw) {
		echo "<html><head><title>HTML Sin Procesar</title></head><body>";
		
		// Obtener datos de la factura
		$datos = obtenerFactura($id_o_hash, true);
		$factura = $datos['factura'];
		$cae = $datos['cae'];
		
		// Generar el HTML
		$html = htmlParaPdf($factura, $cae);
		
		if (empty($html)) {
			echo "<p style='color:red;'>Error: No se pudo generar el HTML</p>";
		} else {
			echo "<h2>HTML generado para el PDF:</h2>";
			echo "<pre>" . htmlspecialchars($html) . "</pre>";
			
			echo "<h2>HTML corregido:</h2>";
			$htmlCorregido = corregirRutasHTML($html);
			echo "<pre>" . htmlspecialchars($htmlCorregido) . "</pre>";
			
			echo "<h2>Vista previa del HTML:</h2>";
			echo "<div style='border:1px solid #ccc; padding:20px;'>";
			echo $htmlCorregido;
			echo "</div>";
		}
		
		echo "</body></html>";
		exit;
	}
	
	// Si estamos en modo debug_html, ejecutar la depuración de htmlParaPdf
	if ($is_debug_html) {
		echo "<html><head><title>Depuración de HTML para PDF</title></head><body>";
		
		echo "<h1>Depuración detallada de generación de PDF</h1>";
		echo "<p>ID/Hash: " . htmlspecialchars($id_o_hash) . "</p>";
		
		// Obtener datos de la factura
		$datos = obtenerFactura($id_o_hash, true);
		$factura = $datos['factura'];
		$cae = $datos['cae'];
		
		// Ejecutar la depuración de htmlParaPdf
		debugHtmlParaPdf($factura, $cae);
		
		echo "</body></html>";
		exit;
	}
	
	// Modo depuración normal
	if ($is_debug) {
		// Código de depuración existente...
		// (Mantén el código original para debug=1)
	}
	
	// Determinar si es para ver o descargar
	$accion = isset($_GET['accion']) ? $_GET['accion'] : 'ver';
	
	// Obtener datos de la factura
	$datos = obtenerFactura($id_o_hash, $is_debug);
	$factura = $datos['factura'];
	$cae = $datos['cae'];
	
	// Verificar que tengamos una plantilla
	if (empty($factura['template'])) {
		echo "Error: La factura no tiene una plantilla definida. ID/Hash: " . htmlspecialchars($id_o_hash);
		exit;
	}
	
	// Generar el HTML para el PDF
	$html = htmlParaPdf($factura, $cae);
	
	if (empty($html)) {
		echo "Error al generar el HTML para el PDF. Puede que la plantilla no esté accesible o que falten datos críticos.";
		echo "<p>Para diagnóstico detallado, añade <strong>?debug_html=1</strong> a la URL.</p>";
		exit;
	}
	
	// Corregir rutas en el HTML generado
	$html = corregirRutasHTML($html);
	
	// Crear el directorio para los PDFs temporales si no existe
	$tempDir = 'pdfs/temp';
	if (!file_exists($tempDir)) {
		mkdir($tempDir, 0755, true);
	}
	
	// Generar un nombre único para el archivo
	$filename = "factura-" . $factura['numero_talonario'] . "-" . $factura['numero_factura'] . ".pdf";
	$tempFilePath = $tempDir . '/' . $filename;
	
	// Crear el PDF y guardarlo en el servidor primero
	try {
		$html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(0, 0, 0, 0));
		$html2pdf->setDefaultFont('Arial');
		$html2pdf->writeHTML($html);
		
		// Guardar el PDF en el servidor
		$html2pdf->Output($tempFilePath, 'F');
		
		// Verificar si el archivo se guardó correctamente
		if (!file_exists($tempFilePath)) {
			echo "Error: No se pudo guardar el archivo PDF en el servidor.<br>Para diagnóstico detallado, añade <strong>?debug_html=1</strong> o <strong>?debug_raw=1</strong> a la URL.";
			exit;
		}
		
		// Comprobar si el archivo es un PDF válido
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);
		$mimeType = finfo_file($fileInfo, $tempFilePath);
		finfo_close($fileInfo);
		
		if ($mimeType !== 'application/pdf') {
			echo "Error: El archivo generado no es un PDF válido (tipo MIME: $mimeType).<br>Para diagnóstico detallado, añade <strong>?debug_html=1</strong> o <strong>?debug_raw=1</strong> a la URL.";
			exit;
		}
		
		// Si todo está bien, enviar el archivo al navegador
		header('Content-Type: application/pdf');
		
		if ($accion === 'descargar') {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
		} else {
			header('Content-Disposition: inline; filename="' . $filename . '"');
		}
		
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($tempFilePath));
		
		readfile($tempFilePath);
		
		// Eliminar el archivo temporal
		unlink($tempFilePath);
		
		exit;
	} catch (Exception $e) {
		echo "Error al generar el PDF: " . $e->getMessage() . "<br>Para diagnóstico detallado, añade <strong>?debug_html=1</strong> o <strong>?debug_raw=1</strong> a la URL.";
		exit;
	}
} catch (Exception $e) {
	logError("Excepción general: " . $e->getMessage());
	die("Error al procesar la solicitud. Por favor, inténtelo más tarde.");
}
?>