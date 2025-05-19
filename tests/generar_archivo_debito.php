<?php

/**
 * Standalone script to generate the debit file
 * This replicates the functionality of the exportar method in the Debito controller
 */

// Load database configuration
require_once 'db_config.php';

// Optional date parameter (format: YYYYMMDD)
$fechaVto = isset($argv[1]) ? $argv[1] : null;

// Connect to database
try {
    $mysqli = new mysqli(
        $db_config['hostname'], 
        $db_config['username'], 
        $db_config['password'], 
        $db_config['database'], 
        $db_config['port']
    );

    if ($mysqli->connect_error) {
        throw new Exception("Database connection failed: " . $mysqli->connect_error);
    }

    // Set character set
    $mysqli->set_charset($db_config['charset']);

    // Step 1: Get debits (generarDebito function)
    $sql_debitos = "
        SELECT empresas.codigo, 
                empresas.empresa, 
                empresas.id AS id_empresa, 
                cuentas.cbu26 as cbu, 
                UNIX_TIMESTAMP(CONVERT_TZ(facturas.fecha, '-03:00', @@global.time_zone)) AS fecha, 
                COUNT(facturas.id) AS cantidad, 
                SUM(IF(nota.total_neto, facturas.saldo-nota.total_neto, facturas.saldo)) AS saldo
        
        FROM facturas
        LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
        LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
        LEFT JOIN cuentas ON cuentas.id_empresa = empresas.id
        LEFT JOIN facturas AS nota ON nota.padre = facturas.id
        
        WHERE 1
        AND empresas.estado > 0
        AND facturas.operacion = 'V'
        AND (facturas.id_forma_pago = 5 OR facturas.id_forma_pago = 15)
        AND facturas.total_neto <= facturas.saldo
        AND facturas.estado = 2
        AND facturas.id NOT IN (SELECT id FROM facturas WHERE nota.padre = facturas.id)

        GROUP BY empresas.codigo, facturas.id_moneda
        ORDER BY empresas.codigo ASC
    ";

    // Execute query for debits
    $result_debitos = $mysqli->query($sql_debitos);

    if (!$result_debitos) {
        throw new Exception("Debits query failed: " . $mysqli->error);
    }

    // Convert result to array
    $debitos = [];
    while ($row = $result_debitos->fetch_assoc()) {
        $debitos[] = $row;
    }

    // Step 2: Get total (totalDebito function)
    $sql_total = "
        SELECT SUM(IF(nota.total_neto, facturas.saldo-nota.total_neto, facturas.saldo)) AS total
        
        FROM facturas
        LEFT JOIN empresas_fiscales ON facturas.id_empresa_fiscal = empresas_fiscales.id
        LEFT JOIN empresas ON empresas_fiscales.id_empresa = empresas.id
        LEFT JOIN facturas AS nota ON nota.padre = facturas.id
        
        WHERE 1
        AND empresas.estado > 0
        AND facturas.operacion = 'V'
        AND (facturas.id_forma_pago = 5 OR facturas.id_forma_pago = 15)
        AND facturas.total_neto <= facturas.saldo
        AND facturas.estado = 2
        AND facturas.id NOT IN (SELECT id FROM facturas WHERE nota.padre = facturas.id)
    ";

    // Execute query for total
    $result_total = $mysqli->query($sql_total);

    if (!$result_total) {
        throw new Exception("Total query failed: " . $mysqli->error);
    }

    // Get total
    $row_total = $result_total->fetch_assoc();
    $total = $row_total['total'];

    // Step 3: Format the data and create file content
    
    // Count records
    $cantidadRegistros = count($debitos);
    
    // Format record count (7 digits)
    $cantidadFormateada = str_pad($cantidadRegistros, 7, '0', STR_PAD_LEFT);
    
    // Format total amount (12 integers and 2 decimals, without dots or commas)
    $importeTotal = number_format($total, 2, '', '');
    $importeTotal = str_pad($importeTotal, 14, '0', STR_PAD_LEFT);

    // Build header with the correct format
    $contenido = "00" .                    // Type of record (pos 1-2)
                 "018888" .                // Service number (pos 3-8)
                 "C" .                     // Service (pos 9)
                 date('Ymd') .             // Generation date (pos 10-17)
                 "1" .                     // File identification (pos 18)
                 "EMPRESA" .               // Origin (pos 19-25)
                 $importeTotal .           // Total amount (pos 26-39)
                 $cantidadFormateada .     // Record count (pos 40-46)
                 str_repeat(" ", 304) .    // Blank spaces (pos 47-350)
                 "\r\n";

    // Add detail lines
    foreach ($debitos as $debito) {
        if ($fechaVto === null) {
            $fechaVto = date('Ymd', strtotime('+2 days'));
        }
        
        $contenido .= "0370" .                        // Type of record (pos 1-4)
                     str_pad($debito['codigo'], 22, ' ', STR_PAD_RIGHT) .  // Client ID (pos 5-26)
                     $debito['cbu'] .                          // CBU without spaces (pos 27-52)
                     "REVISION ALPHA " .             // Unique reference (pos 53-67)
                     $fechaVto .                     // Due date (pos 68-75)
                     str_pad(number_format($debito['saldo'], 2, '', ''), 14, '0', STR_PAD_LEFT) . // Amount (pos 76-89)
                     "00000000" . // 2nd due date (pos 90-97)
                     "00000000000000" .  // Amount (pos 98-111)
                     "00000000" . // 3rd due date (pos 112-119)
                     "00000000000000" .  // Amount (pos 120-133)
                     "0" . // Invoice currency
                     "   " . // 3 spaces
                     "000000000000000" .            // More zeros
                     "                      " .      // Spaces
                     "0000000000000000000000000000000000000000" . // Final zeros
                     "\r\n";
    }

    // Add trailer (closing record)
    $contenido .= "99" .                     // Type of record (pos 1-2)
                 "018888" .                 // Service number (pos 3-8)
                 "C" .                      // Service (pos 9)
                 date('Ymd') .              // Generation date (pos 10-17)
                 "1" .                      // File identification (pos 18)
                 "EMPRESA" .                // Origin (pos 19-25)
                 $importeTotal .            // Total amount (pos 26-39)
                 $cantidadFormateada .      // Record count (pos 40-46)
                 str_repeat(" ", 304) .     // Blank spaces (pos 47-350)
                 "\r\n";

    // If running from command line, save to file
    if (php_sapi_name() === 'cli') {
        $filename = 'DEBITOS_' . date('Ymd') . '.txt';
        file_put_contents($filename, $contenido);
        echo "File generated: $filename\n";
        echo "Total records: $cantidadRegistros\n";
        echo "Total amount: $total\n";
    } 
    // If running from web, set headers for download
    else {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="DEBITOS.txt"');
        header('Content-Length: ' . strlen($contenido));
        header('Cache-Control: no-store, no-cache');
        header('Pragma: no-cache');
        echo $contenido;
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Close result sets
    if (isset($result_debitos)) {
        $result_debitos->close();
    }
    if (isset($result_total)) {
        $result_total->close();
    }
    
    // Close database connection
    if (isset($mysqli)) {
        $mysqli->close();
    }
} 