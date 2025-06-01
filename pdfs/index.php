<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener la URL completa
$requestUri = $_SERVER['REQUEST_URI'];
echo "URL solicitada: " . htmlspecialchars($requestUri) . "<br>";

// Obtener el path (eliminar parámetros de consulta si existen)
$path = parse_url($requestUri, PHP_URL_PATH);
echo "Path: " . htmlspecialchars($path) . "<br>";

// Extraer el hash del path
if (preg_match('/\/pdfs\/(descargar\/)?([a-zA-Z0-9]+)(\.pdf)?$/', $path, $matches)) {
    $hash = $matches[2];
    echo "Hash extraído: " . htmlspecialchars($hash) . "<br>";
    
    // Incluir los archivos necesarios
    require_once('config.php');
    
    // Conectar a la base de datos
    $mysqli = new mysqli(CMS5_DB_HOST, CMS5_DB_USER, CMS5_DB_PASSWORD, CMS5_DB_NAME, CMS5_DB_PORT);
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    $mysqli->set_charset("utf8");
    
    // Buscar factura por ID directamente (usamos 52918 como mencionaste)
    $id = 52918;
    echo "<h2>Buscando factura con ID: " . $id . "</h2>";
    
    // Simplificar la consulta para evitar errores
    $sql = "SELECT * FROM facturas WHERE id = ?";
    
    // Verificar si la preparación de la consulta fue exitosa
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $mysqli->error . "<br>";
        
        // Intentar una consulta más simple para verificar la conexión
        $test_result = $mysqli->query("SELECT 1");
        if ($test_result) {
            echo "La conexión a la base de datos funciona correctamente.<br>";
            $test_result->free();
        } else {
            echo "Hay un problema con la conexión a la base de datos.<br>";
        }
        
        // Verificar si la tabla existe
        $tables_result = $mysqli->query("SHOW TABLES LIKE 'facturas'");
        if ($tables_result) {
            if ($tables_result->num_rows > 0) {
                echo "La tabla 'facturas' existe.<br>";
            } else {
                echo "La tabla 'facturas' no existe.<br>";
            }
            $tables_result->free();
        }
        
        // Intentar una consulta directa sin preparación
        $direct_result = $mysqli->query("SELECT * FROM facturas WHERE id = 52918 LIMIT 1");
        if ($direct_result) {
            if ($direct_result->num_rows > 0) {
                $direct_row = $direct_result->fetch_assoc();
                echo "<h3>Datos básicos de la factura (consulta directa):</h3>";
                echo "<pre>";
                print_r($direct_row);
                echo "</pre>";
            } else {
                echo "No se encontró ninguna factura con ID 52918 (consulta directa).<br>";
            }
            $direct_result->free();
        } else {
            echo "Error en consulta directa: " . $mysqli->error . "<br>";
        }
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo "<h3>Datos básicos de la factura:</h3>";
            echo "<pre>";
            print_r($row);
            echo "</pre>";
            
            // Calcular el hash para verificar
            $calculated_hash = md5($row['grupo'] . $row['id']);
            echo "<p>Hash calculado: " . $calculated_hash . "</p>";
            echo "<p>Hash recibido: " . $hash . "</p>";
            echo "<p>¿Coinciden? " . ($calculated_hash === $hash ? "Sí" : "No") . "</p>";
        } else {
            echo "No se encontró ninguna factura con ID " . $id . ".<br>";
        }
        
        $stmt->close();
    }
    
    $mysqli->close();
} else {
    echo "No se pudo extraer un hash válido de la URL";
}
?>