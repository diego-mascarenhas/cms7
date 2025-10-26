<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Función para buscar recursivamente una clase en un directorio
function encontrarClase($className, $directory) {
    $scanned_ 