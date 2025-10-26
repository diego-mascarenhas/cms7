<?php
// Script para diagnosticar y corregir problemas de permisos en el directorio QR

// Función para mostrar estado
function show_status($message, $success = true) {
    echo '<div style="padding: 10px; margin: 5px; border-radius: 5px; background-color: ' . 
         ($success ? '#dff0d8' : '#f2dede') . '; color: ' . 
         ($success ? '#3c763d' : '#a94442') . ';">' . $message . '</div>';
}

// Directorios a verificar
$qr_paths = [
    '/home/forge/cms.revisionalpha.com/pdfs/qr_images/',
    dirname(__FILE__) . '/qr_images/',
];

// Verificar directorios
echo '<h1>Diagnóstico y reparación de permisos para QR</h1>';

foreach ($qr_paths as $path) {
    echo '<h2>Verificando: ' . htmlspecialchars($path) . '</h2>';
    
    // Verificar si existe
    if (file_exists($path)) {
        show_status("El directorio existe");
        
        // Verificar si es directorio
        if (is_dir($path)) {
            show_status("Es un directorio válido");
            
            // Verificar permisos
            $perms = decoct(fileperms($path) & 0777);
            show_status("Permisos actuales: $perms", $perms >= 755);
            
            // Verificar si es escribible
            if (is_writable($path)) {
                show_status("El directorio tiene permisos de escritura");
            } else {
                show_status("ERROR: El directorio NO tiene permisos de escritura", false);
                
                // Intentar corregir permisos
                echo '<h3>Intentando corregir permisos...</h3>';
                
                if (@chmod($path, 0755)) {
                    show_status("Permisos cambiados a 755 correctamente");
                } else {
                    show_status("ERROR: No se pudieron cambiar los permisos", false);
                    show_status("Comando para ejecutar en SSH: chmod -R 755 " . escapeshellarg($path), false);
                }
            }
            
            // Verificar usuario/grupo
            $owner = posix_getpwuid(fileowner($path));
            $group = posix_getgrgid(filegroup($path));
            show_status("Propietario: {$owner['name']}, Grupo: {$group['name']}");
            
            // Verificar si podemos crear un archivo de prueba
            $test_file = $path . '/test_' . time() . '.txt';
            if (file_put_contents($test_file, 'Test')) {
                show_status("Prueba de escritura: EXITOSA");
                @unlink($test_file); // Eliminar archivo de prueba
            } else {
                show_status("ERROR: No se pudo escribir archivo de prueba", false);
            }
            
            // Listar contenido
            echo '<h3>Contenido del directorio:</h3>';
            echo '<ul>';
            $files = scandir($path);
            if (count($files) <= 2) {
                show_status("El directorio está vacío (solo . y ..)", false);
            } else {
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        $full_path = $path . '/' . $file;
                        $file_size = filesize($full_path);
                        $file_perms = decoct(fileperms($full_path) & 0777);
                        echo '<li>' . htmlspecialchars($file) . ' - ' . $file_size . ' bytes (permisos: ' . $file_perms . ')</li>';
                    }
                }
            }
            echo '</ul>';
        } else {
            show_status("ERROR: No es un directorio", false);
        }
    } else {
        show_status("El directorio no existe", false);
        
        // Intentar crear directorio
        echo '<h3>Intentando crear directorio...</h3>';
        
        if (@mkdir($path, 0755, true)) {
            show_status("Directorio creado exitosamente con permisos 755");
            
            // Verificar si se puede escribir
            if (is_writable($path)) {
                show_status("El directorio creado tiene permisos de escritura");
            } else {
                show_status("ERROR: El directorio creado NO tiene permisos de escritura", false);
            }
        } else {
            show_status("ERROR: No se pudo crear el directorio", false);
            show_status("Comando para ejecutar en SSH: mkdir -p " . escapeshellarg($path), false);
        }
    }
}

// Verificar configuración PHP
echo '<h2>Configuración de PHP</h2>';

// Verificar si GD está habilitado
if (extension_loaded('gd') && function_exists('imagepng')) {
    show_status("Extensión GD: Habilitada");
} else {
    show_status("ERROR: Extensión GD no habilitada", false);
}

// Verificar directorio temporal
$temp_dir = sys_get_temp_dir();
show_status("Directorio temporal: " . $temp_dir);
if (is_writable($temp_dir)) {
    show_status("El directorio temporal es escribible");
} else {
    show_status("ERROR: El directorio temporal NO es escribible", false);
}

// Mostrar límites PHP
show_status("Límite de memoria: " . ini_get('memory_limit'));
show_status("Tiempo máximo de ejecución: " . ini_get('max_execution_time') . " segundos");
show_status("Tamaño máximo de subida: " . ini_get('upload_max_filesize'));
show_status("Tamaño máximo de POST: " . ini_get('post_max_size'));

// Verificar log de errores
$error_log = ini_get('error_log');
if ($error_log) {
    show_status("Log de errores: " . $error_log);
    if (is_writable(dirname($error_log))) {
        show_status("El directorio del log de errores es escribible");
    } else {
        show_status("ERROR: El directorio del log de errores NO es escribible", false);
    }
} else {
    show_status("ERROR: No se ha configurado archivo de log de errores", false);
}

// Prueba de generación de QR
echo '<h2>Prueba de generación de QR</h2>';

// Incluir archivos necesarios
require_once('config.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php');

// Datos de ejemplo
$qr_data = [
    'ver' => 1,
    'fecha' => date('Y-m-d'),
    'cuit' => 30716710072,
    'ptoVta' => 1,
    'tipoCmp' => 1,
    'nroCmp' => time(),
    'importe' => 1000.00,
    'moneda' => 'PES',
    'ctz' => 1,
    'tipoDocRec' => 80,
    'nroDocRec' => 30123456789,
    'tipoCodAut' => 'E',
    'codAut' => 12345678901234
];

// Intentar generar QR
$directory_to_use = is_dir($qr_paths[0]) && is_writable($qr_paths[0]) ? 
                    $qr_paths[0] : $qr_paths[1];

echo '<h3>Generando QR en: ' . htmlspecialchars($directory_to_use) . '</h3>';

try {
    $qr_html = generarQR($qr_data, 'png', $directory_to_use);
    show_status("QR generado exitosamente");
    echo $qr_html;
} catch (Exception $e) {
    show_status("ERROR al generar QR: " . $e->getMessage(), false);
}

// Verificar contenido después de generar
echo '<h3>Contenido después de generar QR:</h3>';
echo '<ul>';
$files = scandir($directory_to_use);
foreach ($files as $file) {
    if ($file != '.' && $file != '..' && strpos($file, 'qr_') === 0) {
        $full_path = $directory_to_use . '/' . $file;
        $file_size = filesize($full_path);
        $file_time = date('Y-m-d H:i:s', filemtime($full_path));
        echo '<li>' . htmlspecialchars($file) . ' - ' . $file_size . ' bytes (modificado: ' . $file_time . ')</li>';
    }
}
echo '</ul>';

// Comandos de ayuda
echo '<h2>Comandos útiles para ejecutar en SSH</h2>';
echo '<pre>';
echo "# Crear directorio QR\n";
echo "mkdir -p " . escapeshellarg($qr_paths[0]) . "\n\n";
echo "# Establecer permisos\n";
echo "chmod -R 755 " . escapeshellarg($qr_paths[0]) . "\n\n";
echo "# Cambiar propietario a forge:forge\n";
echo "chown -R forge:forge " . escapeshellarg($qr_paths[0]) . "\n\n";
echo "# Verificar la configuración del usuario web\n";
echo "ps aux | grep php\n\n";
echo "# Verificar los logs de errores\n";
echo "tail -n 50 " . escapeshellarg($error_log) . "\n";
echo '</pre>'; 