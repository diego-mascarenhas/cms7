<?php
// Este script reemplaza el contenido de index.php con el código de generar.php

// Verificar si estamos autorizados a ejecutar este script
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($token !== 'secreto123') { // Cambia esto por un token seguro
    die("Acceso no autorizado");
}

// Obtener el código de generar.php
$codigo_nuevo = file_get_contents(__DIR__ . '/generar.php');

if (empty($codigo_nuevo)) {
    die("Error: No se pudo leer el archivo generar.php");
}

// Hacer una copia de seguridad del index.php actual
$fecha = date('Ymd_His');
$backup_path = __DIR__ . '/index_backup_' . $fecha . '.php';
$index_path = __DIR__ . '/index.php';

if (file_exists($index_path)) {
    if (!copy($index_path, $backup_path)) {
        die("Error: No se pudo hacer una copia de seguridad de index.php");
    }
}

// Reemplazar el contenido de index.php con el nuevo código
if (file_put_contents($index_path, $codigo_nuevo) === false) {
    die("Error: No se pudo escribir en el archivo index.php");
}

echo "¡Éxito! El archivo index.php ha sido reemplazado correctamente.<br>";
echo "Se ha creado una copia de seguridad en: " . basename($backup_path);
?> 