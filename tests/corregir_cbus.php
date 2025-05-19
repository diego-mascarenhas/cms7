<?php

/**
 * Corrector de CBUs en la base de datos
 * Corrige CBUs mal formateados según las especificaciones del formato SNP
 */

// Cargar configuración de base de datos
require_once 'db_config.php';

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
    
    // Si el CBU tiene 22 caracteres (formato Banelco), convertirlo a formato SNP (26 caracteres)
    if (strlen($cbu) == 22) {
        // Según documento: Formato SNP (26 caracteres): 0007SSSX000TM00FFFFFFFFABY
        // Se agrega un '0' al inicio y tres '0' al final
        $cbu = '0' . $cbu . '000';
    } 
    // Si tiene una longitud diferente, intentar corregirlo basado en el patrón de banco
    else if (strlen($cbu) != 26) {
        // Obtener el código de banco (primeros dígitos)
        $codigoBanco = substr($cbu, 0, 3);
        
        // Ajustar según banco (implementación básica)
        // Esta lógica debería refinarse con reglas específicas por banco
        if (strlen($cbu) < 26) {
            // Rellenar con ceros a la derecha
            $cbu = str_pad($cbu, 26, '0', STR_PAD_RIGHT);
        } else if (strlen($cbu) > 26) {
            // Truncar a 26 caracteres
            $cbu = substr($cbu, 0, 26);
        }
    }
    
    return $cbu;
}

/**
 * Verifica si un CBU cumple con el formato SNP (validación básica)
 * 
 * @param string $cbu El CBU a validar
 * @return bool Si el CBU es válido o no
 */
function verificarFormatoCBU($cbu) {
    // Comprobar longitud y que solo contenga dígitos
    if (strlen($cbu) != 26 || !ctype_digit($cbu)) {
        return false;
    }
    
    // Verificar el tipo de cuenta y moneda (básico)
    $tipoCuenta = substr($cbu, 8, 1);
    $moneda = substr($cbu, 9, 1);
    
    if (($tipoCuenta != '2' && $tipoCuenta != '3') || 
        ($moneda != '0' && $moneda != '1')) {
        return false;
    }
    
    return true;
}

// Modo de simulación (true = solo muestra cambios, false = aplica cambios)
$simulacion = isset($argv[1]) && $argv[1] == 'aplicar' ? false : true;

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

    // Consultar CBUs en la base de datos
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
            (c.cbu IS NOT NULL OR c.cbu26 IS NOT NULL)
    ";

    // Ejecutar consulta
    $result = $mysqli->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $mysqli->error);
    }

    // Contadores
    $total = 0;
    $corregidos = 0;
    $sinCambios = 0;
    $errores = 0;
    
    // Resultados para mostrar
    $cambios = [];
    $erroresDetalle = [];

    echo "=== Corrección de CBUs ===\n\n";
    echo "Modo: " . ($simulacion ? "SIMULACIÓN (sin aplicar cambios)" : "APLICACIÓN (aplicando cambios)") . "\n\n";
    
    // Procesar resultados
    while ($row = $result->fetch_assoc()) {
        $total++;
        
        $id = $row['id'];
        $cbuOriginal = $row['cbu'];
        $cbu26Original = $row['cbu26'];
        
        // Normalizar CBU si existe
        $cbuNormalizado = $cbuOriginal ? normalizarCBU($cbuOriginal) : null;
        
        // Normalizar CBU26 si existe
        $cbu26Normalizado = $cbu26Original ? normalizarCBU($cbu26Original) : null;
        
        // Determinar el CBU correcto a usar
        $cbuCorrecto = null;
        
        if ($cbuNormalizado && verificarFormatoCBU($cbuNormalizado)) {
            $cbuCorrecto = $cbuNormalizado;
        } else if ($cbu26Normalizado && verificarFormatoCBU($cbu26Normalizado)) {
            $cbuCorrecto = $cbu26Normalizado;
        } else if ($cbuNormalizado) {
            // Usar el CBU normalizado aunque no sea perfecto
            $cbuCorrecto = $cbuNormalizado;
        } else if ($cbu26Normalizado) {
            // Usar el CBU26 normalizado aunque no sea perfecto
            $cbuCorrecto = $cbu26Normalizado;
        }
        
        // Si no hay un CBU correcto, registrar error
        if (!$cbuCorrecto) {
            $errores++;
            $erroresDetalle[] = [
                'id' => $id,
                'empresa' => $row['empresa'],
                'cuenta' => $row['nombre_cuenta'],
                'cbu' => $cbuOriginal,
                'cbu26' => $cbu26Original,
                'razon' => 'No se pudo determinar un CBU válido'
            ];
            continue;
        }
        
        // Verificar si se necesita actualizar
        $necesitaActualizar = (
            $cbuCorrecto != $cbu26Original || 
            ($cbuOriginal && normalizarCBU($cbuOriginal) != $cbuCorrecto)
        );
        
        if ($necesitaActualizar) {
            $corregidos++;
            $cambios[] = [
                'id' => $id,
                'empresa' => $row['empresa'],
                'cuenta' => $row['nombre_cuenta'],
                'cbu_original' => $cbuOriginal,
                'cbu26_original' => $cbu26Original,
                'cbu_corregido' => $cbuCorrecto
            ];
            
            // Aplicar cambios si no estamos en modo simulación
            if (!$simulacion) {
                $sqlUpdate = "
                    UPDATE cuentas 
                    SET cbu26 = ?
                    WHERE id = ?
                ";
                
                $stmt = $mysqli->prepare($sqlUpdate);
                $stmt->bind_param('si', $cbuCorrecto, $id);
                
                if (!$stmt->execute()) {
                    $errores++;
                    $erroresDetalle[] = [
                        'id' => $id,
                        'empresa' => $row['empresa'],
                        'cuenta' => $row['nombre_cuenta'],
                        'razon' => 'Error al actualizar: ' . $stmt->error
                    ];
                }
                
                $stmt->close();
            }
        } else {
            $sinCambios++;
        }
    }

    // Mostrar resumen
    echo "Total de CBUs revisados: $total\n";
    echo "CBUs que requieren corrección: $corregidos\n";
    echo "CBUs sin cambios necesarios: $sinCambios\n";
    echo "Errores encontrados: $errores\n\n";

    // Mostrar detalles de cambios
    if ($corregidos > 0) {
        echo "=== CBUs a corregir ===\n\n";
        foreach ($cambios as $cambio) {
            echo "ID: {$cambio['id']}, Empresa: {$cambio['empresa']}, Cuenta: {$cambio['cuenta']}\n";
            echo "CBU Original: {$cambio['cbu_original']}\n";
            echo "CBU26 Original: {$cambio['cbu26_original']}\n";
            echo "CBU Corregido: {$cambio['cbu_corregido']}\n\n";
        }
    }

    // Mostrar errores
    if ($errores > 0) {
        echo "=== Errores ===\n\n";
        foreach ($erroresDetalle as $error) {
            echo "ID: {$error['id']}, Empresa: {$error['empresa']}, Cuenta: {$error['cuenta']}\n";
            echo "Razón: {$error['razon']}\n";
            if (isset($error['cbu'])) echo "CBU: {$error['cbu']}\n";
            if (isset($error['cbu26'])) echo "CBU26: {$error['cbu26']}\n";
            echo "\n";
        }
    }
    
    // Instrucciones para aplicar cambios
    if ($simulacion && $corregidos > 0) {
        echo "\nPara aplicar estos cambios, ejecute:\n";
        echo "php corregir_cbus.php aplicar\n";
    }
    
    // Cerrar resultados
    $result->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Cerrar conexión a base de datos
    if (isset($mysqli)) {
        $mysqli->close();
    }
} 