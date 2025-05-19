<?php

/**
 * Corrector específico para los CBUs rechazados por el banco
 */

// Cargar configuración de base de datos
require_once 'db_config.php';

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

    // Modo de simulación (true = solo muestra cambios, false = aplica cambios)
    $simulacion = isset($argv[1]) && $argv[1] == 'aplicar' ? false : true;

    // CBUs a corregir
    $cbusParaCorregir = [
        '19100000068155006801344150' => '01910000202155006801344150', // Cambiar posiciones 8-9 (tipo de cuenta=2, moneda=0)
        '02700000033510038630730038' => '02700000203510038630730038'  // Cambiar posiciones 8-9 (tipo de cuenta=2, moneda=0)
    ];

    // Buscar y actualizar cada CBU
    foreach ($cbusParaCorregir as $cbuOriginal => $cbuCorregido) {
        // Buscar el CBU en la base de datos
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
                c.cbu26 = ?
        ";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $cbuOriginal);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            echo "Encontrado CBU para corregir:\n";
            echo "ID: {$row['id']}, Empresa: {$row['empresa']}, Cuenta: {$row['nombre_cuenta']}\n";
            echo "CBU26 Original: {$row['cbu26']}\n";
            echo "CBU26 Corregido: {$cbuCorregido}\n\n";

            // Actualizar el CBU si no estamos en modo simulación
            if (!$simulacion) {
                $sqlUpdate = "
                    UPDATE cuentas 
                    SET cbu26 = ?
                    WHERE id = ?
                ";
                
                $stmtUpdate = $mysqli->prepare($sqlUpdate);
                $stmtUpdate->bind_param('si', $cbuCorregido, $row['id']);
                
                if ($stmtUpdate->execute()) {
                    echo "¡Actualización exitosa!\n\n";
                } else {
                    echo "Error al actualizar: " . $stmtUpdate->error . "\n\n";
                }
                
                $stmtUpdate->close();
            }
        } else {
            echo "No se encontró el CBU: {$cbuOriginal}\n\n";
        }

        $stmt->close();
    }

    // Instrucciones para aplicar cambios
    if ($simulacion) {
        echo "\nPara aplicar estos cambios, ejecute:\n";
        echo "php corregir_cbus_especificos.php aplicar\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Cerrar conexión a base de datos
    if (isset($mysqli)) {
        $mysqli->close();
    }
} 