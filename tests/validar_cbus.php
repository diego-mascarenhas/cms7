<?php

/**
 * Validador de CBUs en la base de datos
 * Verifica que todos los CBUs tengan el formato correcto según las especificaciones
 */

// Cargar configuración de base de datos
require_once 'db_config.php';

/**
 * Función para validar un CBU según el formato SNP (26 caracteres)
 * 
 * Formato SNP: 0 + 007 + SSSS + X + 000 + T + M + 00 + FFFFFFFF + A + B + Y
 * Donde:
 * 007 = Código de banco
 * SSSS = Número de sucursal
 * X = Dígito verificador del bloque 1
 * T = Tipo de cuenta (2 - CC y 3 - CA)
 * M = Moneda de la cuenta (0 - $ y 1 - U$S)
 * FFFFFFFF = Folio de la cuenta
 * A = Dígito verificador 1 de la cuenta
 * B = Dígito verificador 2 de la cuenta
 * Y = Dígito verificador del bloque 2
 * 
 * @param string $cbu El CBU a validar
 * @return array Resultado de la validación [valid, errors]
 */
function validarCBU($cbu) {
    $errors = [];
    
    // 1. Verificar longitud
    if (strlen($cbu) != 26) {
        $errors[] = "Longitud incorrecta. Debe tener 26 caracteres, tiene " . strlen($cbu);
    }
    
    // 2. Verificar que solo contenga números
    if (!ctype_digit($cbu)) {
        $errors[] = "El CBU debe contener solo números";
    }
    
    // Si hay errores básicos, no continuar con validaciones más específicas
    if (!empty($errors)) {
        return [
            'valid' => false,
            'errors' => $errors
        ];
    }
    
    // 3. Verificar estructura según formato SNP
    $banco = substr($cbu, 0, 3);
    $sucursal = substr($cbu, 3, 4);
    $dvBloque1 = substr($cbu, 7, 1);
    $tipoCuenta = substr($cbu, 8, 1);
    $moneda = substr($cbu, 9, 1);
    $folio = substr($cbu, 10, 8);
    $dv1 = substr($cbu, 18, 1);
    $dv2 = substr($cbu, 19, 1);
    $dvBloque2 = substr($cbu, 20, 1);
    
    // 4. Verificar tipo de cuenta
    if ($tipoCuenta != '2' && $tipoCuenta != '3') {
        $errors[] = "Tipo de cuenta inválido. Debe ser 2 (CC) o 3 (CA), es $tipoCuenta";
    }
    
    // 5. Verificar moneda
    if ($moneda != '0' && $moneda != '1') {
        $errors[] = "Moneda inválida. Debe ser 0 ($) o 1 (U\$S), es $moneda";
    }
    
    // 6. Calcular los dígitos verificadores (implementación simplificada)
    // En una implementación completa, habría que calcular los dígitos verificadores
    // según el algoritmo específico de validación de CBU
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'details' => [
            'banco' => $banco,
            'sucursal' => $sucursal,
            'dv_bloque1' => $dvBloque1,
            'tipo_cuenta' => $tipoCuenta == '2' ? 'CC' : 'CA',
            'moneda' => $moneda == '0' ? 'ARS' : 'USD',
            'folio' => $folio,
            'dv1' => $dv1,
            'dv2' => $dv2,
            'dv_bloque2' => $dvBloque2
        ]
    ];
}

/**
 * Normaliza un CBU en formato variable a formato SNP (26 caracteres)
 * Elimina espacios, guiones y otros caracteres no numéricos
 * 
 * @param string $cbu El CBU en formato variable
 * @return string El CBU normalizado
 */
function normalizarCBU($cbu) {
    // Eliminar cualquier carácter no numérico
    $cbu = preg_replace('/[^0-9]/', '', $cbu);
    
    // Si el CBU tiene menos de 26 caracteres, podría estar en formato Banelco (22 caracteres)
    if (strlen($cbu) == 22) {
        // Convertir de formato Banelco a SNP
        // Para una conversión exacta, se necesitaría conocer el algoritmo específico
        // Esta es una versión simplificada basada en los ejemplos
        $cbu = '0' . $cbu . '000';
    }
    
    return $cbu;
}

// Conectar a la base de datos
try {
    $mysqli = new mysqli(
        $db_config['hostname'], 
        $db_config['username'], 
        $db_config['password'], 
        $db_config['database'], 
        $db_config['port']
    );

    if ($mysqli->connect_error) {
        throw new Exception("Error de conexión a la base de datos: " . $mysqli->connect_error);
    }

    // Establecer charset
    $mysqli->set_charset($db_config['charset']);

    // Consultar todos los CBUs en la base de datos
    $sql = "
        SELECT 
            c.id,
            c.nombre_cuenta,
            e.empresa,
            c.cbu,
            c.cbu26
        FROM 
            cuentas c
        LEFT JOIN 
            empresas e ON c.id_empresa = e.id
        WHERE 
            c.cbu26 IS NOT NULL
    ";

    // Ejecutar consulta
    $result = $mysqli->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $mysqli->error);
    }

    // Inicializar contadores
    $total = 0;
    $valid = 0;
    $invalid = 0;
    $inconsistent = 0;
    
    // Resultados detallados
    $invalidCBUs = [];
    $inconsistentCBUs = [];

    // Procesar resultados
    echo "=== Validación de CBUs ===\n\n";
    
    while ($row = $result->fetch_assoc()) {
        $total++;
        
        // Validar CBU26
        $validationResult = validarCBU($row['cbu26']);
        
        // Verificar consistencia entre cbu y cbu26
        $normalizedCBU = normalizarCBU($row['cbu']);
        $isConsistent = ($normalizedCBU == $row['cbu26']);
        
        if (!$isConsistent) {
            $inconsistent++;
            $inconsistentCBUs[] = [
                'id' => $row['id'],
                'empresa' => $row['empresa'],
                'cuenta' => $row['nombre_cuenta'],
                'cbu' => $row['cbu'],
                'cbu26' => $row['cbu26'],
                'normalized' => $normalizedCBU
            ];
        }
        
        if ($validationResult['valid']) {
            $valid++;
        } else {
            $invalid++;
            $invalidCBUs[] = [
                'id' => $row['id'],
                'empresa' => $row['empresa'],
                'cuenta' => $row['nombre_cuenta'],
                'cbu26' => $row['cbu26'],
                'errors' => $validationResult['errors']
            ];
        }
    }

    // Mostrar resumen
    echo "Total de CBUs revisados: $total\n";
    echo "CBUs válidos: $valid\n";
    echo "CBUs inválidos: $invalid\n";
    echo "CBUs inconsistentes (cbu vs cbu26): $inconsistent\n\n";

    // Mostrar detalles de CBUs inválidos
    if ($invalid > 0) {
        echo "=== CBUs con formato inválido ===\n\n";
        foreach ($invalidCBUs as $cbu) {
            echo "ID: {$cbu['id']}, Empresa: {$cbu['empresa']}, Cuenta: {$cbu['cuenta']}\n";
            echo "CBU: {$cbu['cbu26']}\n";
            echo "Errores:\n";
            foreach ($cbu['errors'] as $error) {
                echo "- $error\n";
            }
            echo "\n";
        }
    }

    // Mostrar detalles de CBUs inconsistentes
    if ($inconsistent > 0) {
        echo "=== CBUs inconsistentes ===\n\n";
        foreach ($inconsistentCBUs as $cbu) {
            echo "ID: {$cbu['id']}, Empresa: {$cbu['empresa']}, Cuenta: {$cbu['cuenta']}\n";
            echo "CBU: {$cbu['cbu']}\n";
            echo "CBU26: {$cbu['cbu26']}\n";
            echo "CBU Normalizado: {$cbu['normalized']}\n";
            echo "\n";
        }
    }
    
    // Cerrar conjunto de resultados
    $result->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Cerrar conexión a base de datos
    if (isset($mysqli)) {
        $mysqli->close();
    }
} 