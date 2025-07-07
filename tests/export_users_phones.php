<?php
// Script para exportar contactos del grupo 513 con teléfonos normalizados

// Mostrar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función base_url() mock para cargar la configuración correcta
function base_url() {
    return 'https://cms.revisionalpha.com/';
}

// Incluir configuración de CodeIgniter para acceso a base de datos
define('BASEPATH', true);
define('ENVIRONMENT', 'production');

try {
    require_once('../application/config/database.php');
    
    // Configuración de conexión a la base de datos
    $db_config = $db['default'];
    $host = $db_config['hostname'];
    $port = isset($db_config['port']) ? $db_config['port'] : '3306';
    $username = $db_config['username'];
    $password = $db_config['password'];
    $database = $db_config['database'];
    
    echo "<!-- Debug: Conectando a $host:$port con usuario $username a BD $database -->\n";
    
} catch (Exception $e) {
    echo "Error cargando configuración: " . $e->getMessage();
    exit;
}

try {
    // Conectar a la base de datos
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
    echo "<!-- Debug: DSN: $dsn -->\n";
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<!-- Debug: Conexión exitosa -->\n";
    
    // Consulta para obtener contactos del grupo 513
    $sql = "SELECT nombre, apellido, email, celular 
            FROM contactos 
            WHERE grupo = 513 
            AND estado > 0 
            AND area_privada != 6
            ORDER BY nombre, apellido";
    
    echo "<!-- Debug: SQL: $sql -->\n";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<!-- Debug: Contactos encontrados: " . count($contactos) . " -->\n";
    
    // Si no hay contactos, hacer una consulta de debug
    if (empty($contactos)) {
        $debug_sql = "SELECT COUNT(*) as total FROM contactos WHERE grupo = 513";
        $debug_stmt = $pdo->prepare($debug_sql);
        $debug_stmt->execute();
        $debug_result = $debug_stmt->fetch(PDO::FETCH_ASSOC);
        echo "<!-- Debug: Total contactos grupo 513: " . $debug_result['total'] . " -->\n";
        
        $debug_sql2 = "SELECT COUNT(*) as total FROM contactos WHERE grupo = 513 AND estado > 0";
        $debug_stmt2 = $pdo->prepare($debug_sql2);
        $debug_stmt2->execute();
        $debug_result2 = $debug_stmt2->fetch(PDO::FETCH_ASSOC);
        echo "<!-- Debug: Contactos activos grupo 513: " . $debug_result2['total'] . " -->\n";
        
        $debug_sql3 = "SELECT COUNT(*) as total FROM contactos WHERE grupo = 513 AND estado > 0 AND area_privada != 6";
        $debug_stmt3 = $pdo->prepare($debug_sql3);
        $debug_stmt3->execute();
        $debug_result3 = $debug_stmt3->fetch(PDO::FETCH_ASSOC);
        echo "<!-- Debug: Contactos activos no-emailer grupo 513: " . $debug_result3['total'] . " -->\n";
        
        // Mostrar algunos grupos que existen
        $grupos_sql = "SELECT DISTINCT grupo, COUNT(*) as total FROM contactos GROUP BY grupo ORDER BY grupo LIMIT 10";
        $grupos_stmt = $pdo->prepare($grupos_sql);
        $grupos_stmt->execute();
        $grupos_result = $grupos_stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<!-- Debug: Primeros 10 grupos existentes: " . print_r($grupos_result, true) . " -->\n";
    }
    
            // Normalizar los números de celular (solo limpiar, sin filtros complejos)
        foreach ($contactos as &$contacto) {
            if (!empty($contacto['celular'])) {
                $celular = $contacto['celular'];
                
                // Eliminar todos los caracteres que no sean dígitos
                $celular_limpio = preg_replace('/[^\d]/', '', $celular);
                
                // Convertir a integer si es numérico
                if (ctype_digit($celular_limpio) && !empty($celular_limpio)) {
                    $contacto['celular'] = (int) $celular_limpio;
                } else {
                    $contacto['celular'] = $celular_limpio;
                }
            }
        }
    
    // Verificar si se está solicitando formato JSON
    if (isset($_GET['format']) && $_GET['format'] === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($contactos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        // Mostrar en formato HTML
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Exportar Contactos - Grupo 513</title>';
        echo '<style>';
        echo 'body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }';
        echo '.container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }';
        echo 'h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }';
        echo '.stats { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0; }';
        echo '.export-options { margin: 20px 0; }';
        echo '.export-options a { display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px; }';
        echo '.export-options a:hover { background: #0056b3; }';
        echo 'pre { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px; overflow-x: auto; }';
        echo 'table { width: 100%; border-collapse: collapse; margin: 20px 0; }';
        echo 'th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }';
        echo 'th { background-color: #007bff; color: white; font-weight: bold; }';
        echo 'tr:nth-child(even) { background-color: #f8f9fa; }';
        echo 'tr:hover { background-color: #e9ecef; }';
        echo '.normalized { color: #28a745; font-weight: bold; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        echo '<div class="container">';
        
        echo '<h1>📱 Exportar Contactos - Grupo 513</h1>';
        
        echo '<div class="stats">';
        echo '<strong>📊 Estadísticas:</strong><br>';
        echo '• Total de contactos encontrados: <strong>' . count($contactos) . '</strong><br>';
        echo '• Grupo filtrado: <strong>513</strong><br>';
        echo '• Solo contactos activos (estado > 0)<br>';
        echo '• Excluye perfiles de emailer (area_privada ≠ 6)<br>';
        echo '• Base de datos: <strong>' . htmlspecialchars($database) . '</strong><br>';
        echo '• Servidor: <strong>' . htmlspecialchars($host) . ':' . htmlspecialchars($port) . '</strong>';
        echo '</div>';
        
        // Si no hay contactos, mostrar mensaje especial
        if (empty($contactos)) {
            echo '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;">';
            echo '<h3>⚠️ No se encontraron contactos</h3>';
            echo '<p>No hay contactos en el grupo 513 que cumplan con los criterios especificados.</p>';
            echo '<p>Revisa los comentarios HTML en el código fuente de esta página para más información de debug.</p>';
            echo '</div>';
        }
        
        echo '<div class="export-options">';
        echo '<a href="?format=json" target="_blank">📄 Ver como JSON</a>';
        echo '<a href="#" onclick="copyToClipboard(\'json-data\')">📋 Copiar JSON</a>';
        echo '<a href="#" onclick="copyToClipboard(\'array-data\')">📋 Copiar Array PHP</a>';
        echo '</div>';
        
        // Mostrar tabla de contactos solo si hay datos
        if (!empty($contactos)) {
            echo '<h2>📋 Lista de Contactos</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>Nombre</th><th>Apellido</th><th>Email</th><th>Celular (Normalizado)</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($contactos as $contacto) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($contacto['nombre'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($contacto['apellido'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($contacto['email'] ?? '') . '</td>';
                echo '<td class="normalized">' . htmlspecialchars($contacto['celular'] ?? '') . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        }
        
        // Mostrar array PHP
        echo '<h2>🔧 Array PHP</h2>';
        echo '<pre id="array-data">';
        if (!empty($contactos)) {
            print_r($contactos);
        } else {
            echo "Array\n(\n    // No hay contactos que mostrar\n)\n";
        }
        echo '</pre>';
        
        // Mostrar JSON
        echo '<h2>📄 Formato JSON</h2>';
        echo '<pre id="json-data">';
        if (!empty($contactos)) {
            echo json_encode($contactos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo "[\n    // No hay contactos que mostrar\n]";
        }
        echo '</pre>';
        
        echo '<script>';
        echo 'function copyToClipboard(elementId) {';
        echo '    const element = document.getElementById(elementId);';
        echo '    const text = element.textContent;';
        echo '    navigator.clipboard.writeText(text).then(function() {';
        echo '        alert("¡Copiado al portapapeles!");';
        echo '    }, function() {';
        echo '        alert("Error al copiar al portapapeles");';
        echo '    });';
        echo '}';
        echo '</script>';
        
        echo '</div>';
        echo '</body>';
        echo '</html>';
    }
    
} catch (PDOException $e) {
    echo "<!DOCTYPE html><html><head><title>Error de Conexión</title></head><body>";
    echo "<h1>❌ Error de conexión a la base de datos</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Código:</strong> " . $e->getCode() . "</p>";
    echo "<p><strong>Host:</strong> $host:$port</p>";
    echo "<p><strong>Base de datos:</strong> $database</p>";
    echo "<p><strong>Usuario:</strong> $username</p>";
    echo "</body></html>";
} catch (Exception $e) {
    echo "<!DOCTYPE html><html><head><title>Error General</title></head><body>";
    echo "<h1>❌ Error general</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</body></html>";
}
?>
