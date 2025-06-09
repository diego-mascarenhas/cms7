<?php
/**
 * Script de prueba para la API de Plesk
 * 
 * Este script realiza una conexión básica a la API de Plesk y muestra la versión
 * del servidor y la lista de dominios alojados.
 */

// Configuración de conexión
$config = [
    'host'     => '192.99.154.223',  // Cambia esto por la dirección de tu servidor
    'port'     => 8443,
    'username' => 'admin',                  // Cambia esto por tu nombre de usuario
    'password' => '@PabloHDP'           // Cambia esto por tu contraseña
];

// Función para realizar peticiones a la API de Plesk
function pleskApiRequest($config, $request) {
    $url = "https://{$config['host']}:{$config['port']}/enterprise/control/agent.php";
    
    $headers = [
        "HTTP_AUTH_LOGIN: {$config['username']}",
        "HTTP_AUTH_PASSWD: {$config['password']}",
        "Content-Type: text/xml",
        "Content-Length: " . strlen($request)
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    
    if (!empty($error)) {
        return ['error' => $error, 'info' => $info];
    }
    
    // Convertir XML a Array para facilitar el manejo
    $xml = simplexml_load_string($response);
    $json = json_encode($xml);
    $array = json_decode($json, true);
    
    return $array;
}

// Función para imprimir resultados de forma bonita
function prettyPrint($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

// Función para mostrar el estado del resultado
function showStatus($result, $message) {
    if (isset($result['error'])) {
        echo "<div style='color: red; font-weight: bold;'>❌ Error: {$message}</div>";
        prettyPrint($result);
        return false;
    }
    echo "<div style='color: green; font-weight: bold;'>✅ {$message}</div>";
    return true;
}

// Establecer encabezados para HTML
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de API Plesk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            color: #3a4a6d;
            border-bottom: 2px solid #3a4a6d;
            padding-bottom: 10px;
        }
        h2 {
            color: #3a4a6d;
            margin-top: 30px;
        }
        pre {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            overflow: auto;
            border: 1px solid #ddd;
        }
        .section {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <h1>Prueba de Conexión a la API de Plesk</h1>
    
    <div class="section">
        <h2>1. Obtener Versión del Servidor</h2>
        <?php
        // XML para obtener la versión del servidor
        $versionRequest = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <server>
                <get>
                    <version/>
                </get>
            </server>
        </packet>';
        
        // Realizar la petición
        $versionResult = pleskApiRequest($config, $versionRequest);
        
        // Mostrar resultados
        if (showStatus($versionResult, "Conexión exitosa")) {
            if (isset($versionResult['server']['get']['result']['version'])) {
                $version = $versionResult['server']['get']['result']['version'];
                echo "<div>Versión de Plesk: <strong>{$version}</strong></div>";
            } else {
                echo "<div style='color: orange; font-weight: bold;'>⚠️ No se pudo obtener la versión</div>";
                prettyPrint($versionResult);
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>2. Listar Dominios</h2>
        <?php
        // XML para listar dominios
        $domainsRequest = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <webspace>
                <get>
                    <filter/>
                    <dataset>
                        <gen_info/>
                    </dataset>
                </get>
            </webspace>
        </packet>';
        
        // Realizar la petición
        $domainsResult = pleskApiRequest($config, $domainsRequest);
        
        // Mostrar resultados
        if (showStatus($domainsResult, "Consulta de dominios exitosa")) {
            echo "<h3>Dominios encontrados:</h3>";
            
            if (isset($domainsResult['webspace']['get']['result'])) {
                $results = $domainsResult['webspace']['get']['result'];
                
                // Manejar caso de resultado único
                if (isset($results['status'])) {
                    $results = [$results];
                }
                
                echo "<ul>";
                foreach ($results as $result) {
                    if (isset($result['data']['gen_info']['name'])) {
                        $domain = $result['data']['gen_info']['name'];
                        echo "<li>{$domain}</li>";
                    }
                }
                echo "</ul>";
                
                echo "<h3>Datos completos:</h3>";
                prettyPrint($results);
            } else {
                echo "<div>No se encontraron dominios o la estructura de respuesta es diferente</div>";
                prettyPrint($domainsResult);
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>3. Listar Cuentas de Email para un Dominio</h2>
        <?php
        // Verificar si tenemos dominios para realizar la prueba
        $domainToTest = '';
        if (isset($domainsResult['webspace']['get']['result'])) {
            $results = $domainsResult['webspace']['get']['result'];
            
            // Manejar caso de resultado único
            if (isset($results['status'])) {
                $results = [$results];
            }
            
            if (!empty($results) && isset($results[0]['data']['gen_info']['name'])) {
                $domainToTest = $results[0]['data']['gen_info']['name'];
            }
        }
        
        if (!empty($domainToTest)) {
            // XML para listar cuentas de email
            $emailsRequest = '<?xml version="1.0" encoding="UTF-8"?>
            <packet>
                <mail>
                    <get_mail_account>
                        <filter>
                            <site-name>' . htmlspecialchars($domainToTest) . '</site-name>
                        </filter>
                        <dataset>
                            <gen_info/>
                        </dataset>
                    </get_mail_account>
                </mail>
            </packet>';
            
            // Realizar la petición
            $emailsResult = pleskApiRequest($config, $emailsRequest);
            
            // Mostrar resultados
            if (showStatus($emailsResult, "Consulta de cuentas de email exitosa")) {
                echo "<h3>Cuentas de email para el dominio {$domainToTest}:</h3>";
                
                if (isset($emailsResult['mail']['get_mail_account']['result'])) {
                    $results = $emailsResult['mail']['get_mail_account']['result'];
                    
                    // Manejar caso de resultado único
                    if (isset($results['status'])) {
                        $results = [$results];
                    }
                    
                    if (empty($results)) {
                        echo "<div>No se encontraron cuentas de email para este dominio</div>";
                    } else {
                        echo "<ul>";
                        foreach ($results as $result) {
                            if (isset($result['data']['gen_info']['name']) && isset($result['data']['gen_info']['domain'])) {
                                $email = $result['data']['gen_info']['name'] . '@' . $result['data']['gen_info']['domain'];
                                echo "<li>{$email}</li>";
                            }
                        }
                        echo "</ul>";
                    }
                    
                    echo "<h3>Datos completos:</h3>";
                    prettyPrint($results);
                } else {
                    echo "<div>No se encontraron cuentas de email o la estructura de respuesta es diferente</div>";
                    prettyPrint($emailsResult);
                }
            }
        } else {
            echo "<div style='color: orange; font-weight: bold;'>⚠️ No se pudo probar la consulta de cuentas de email porque no se encontraron dominios</div>";
        }
        ?>
    </div>
</body>
</html> 