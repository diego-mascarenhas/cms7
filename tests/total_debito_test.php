<?php

/**
 * Standalone test for totalDebito function
 */

// Load database configuration
require_once 'db_config.php';

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

    // Execute totalDebito function
    $sql = "
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

    // Execute query
    $result = $mysqli->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $mysqli->error);
    }

    // Fetch and display results
    echo "==== totalDebito Results ====\n\n";
    echo "SQL Query:\n" . $sql . "\n\n";
    echo "Results:\n";
    
    // Get total
    $row = $result->fetch_assoc();
    $total = $row['total'];
    
    echo "Total: " . $total . "\n";
    
    // Close result set
    $result->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // Close database connection
    if (isset($mysqli)) {
        $mysqli->close();
    }
} 