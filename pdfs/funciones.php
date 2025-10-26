<?php

function DigitoVerificador($codbarras)
{
    $totalpares = 0;
    $totalimpares = 0;
    $digito = 0;

    // Asegurarnos de que codbarras sea un string y solo contenga números
    $codbarras = preg_replace('/[^0-9]/', '', (string) $codbarras);

    if (empty($codbarras))
    {
        return 0; // Devolver un valor predeterminado si no hay dígitos
    }

    for ($i = 1; $i < strlen($codbarras); $i = $i + 2)
    {
        $totalimpares = $totalimpares + (int) substr($codbarras, $i, 1);
    }

    for ($i = 0; $i < strlen($codbarras); $i = $i + 2)
    {
        $totalpares = $totalpares + (int) substr($codbarras, $i, 1);
    }

    $suma = $totalimpares + $totalpares;

    while ((intval($suma / 10) * 10) != $suma)
    {
        $suma = $suma + 1;
        $digito = $digito + 1;
    }

    return $digito;
}


// Generar código QR para AFIP
function generarQR($datos, $output_type = 'html', $save_path = null)
{
    // Codificar datos en base64 si es un array
    if (is_array($datos)) {
        $base64 = base64_encode(json_encode($datos));
        $urlQR = "https://www.afip.gob.ar/fe/qr/?p=" . $base64;
    } else {
        // Si ya es un string (URL), usarlo directamente
        $urlQR = $datos;
    }

    // Crear objeto QRcode con la URL
    $qrcode = new QRcode($urlQR, 'L');
    
    // Si queremos un archivo PNG
    if ($output_type == 'png') {
        // Generar imagen del código QR
        $qr_image = $qrcode->getBarcodeImage(10, 10, array(0,0,0));
        
        if ($save_path) {
            // Guardar en archivo
            imagepng($qr_image, $save_path);
            imagedestroy($qr_image);
            return $save_path;
        } else {
            // Retornar imagen directamente
            ob_start();
            imagepng($qr_image);
            $image_data = ob_get_clean();
            imagedestroy($qr_image);
            return $image_data;
        }
    }
    
    // Si queremos HTML (comportamiento por defecto) - VERSIÓN MEJORADA
    $qrcode_array = $qrcode->getBarcodeArray();
    
    // Obtener dimensiones
    $width = $qrcode_array['num_cols'];
    $height = $qrcode_array['num_rows'];
    
    // Tamaño
    $total_size = 50; // tamaño total en píxeles
    $cell_size = $total_size / $width; // calcular tamaño de celda para que todo sume 20px
    
    // Generar tabla con tamaño fijo y sin espacios entre celdas
    $html = '<div style="text-align:center;margin:0 auto;">';
    $html .= '<table style="border-collapse:collapse; margin:0 auto; width:'.$total_size.'px; height:'.$total_size.'px; table-layout:fixed;">';
    
    // Generar filas y columnas
    for ($r = 0; $r < $height; $r++) {
        $html .= '<tr style="padding:0; margin:0; height:'.($total_size/$height).'px;">';
        for ($c = 0; $c < $width; $c++) {
            $color = ($qrcode_array['bcode'][$r][$c] == 1) ? '#000000' : '#FFFFFF';
            $html .= '<td style="padding:0; margin:0; width:'.($total_size/$width).'px; height:'.($total_size/$height).'px; background-color:'.$color.';"></td>';
        }
        $html .= '</tr>';
    }
    
    $html .= '</table>';
    $html .= '</div>';

    return $html;
}


function htmlParaPdf($factura, $cae)
{
    // Verificar que la factura tenga todos los campos necesarios
    if (empty($factura['cuit']) || empty($factura['id_afip']) ||
        empty($factura['numero_talonario']) || empty($cae['CAE']) ||
        empty($cae['CAEFchVto']))
    {
        error_log("Datos de factura incompletos para generar el PDF");
        return null;
    }

    // Asegurarnos de que todos los campos necesarios estén presentes
    if (!isset($factura['subtotal210']))
        $factura['subtotal210'] = 0;
    if (!isset($factura['imp210']))
        $factura['imp210'] = 0;
    if (!isset($factura['template']))
    {
        error_log("Plantilla no definida para la factura");
        return null;
    }

    $numeroCodigoBarras = $factura['cuit'];
    $numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
    $numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
    $numeroCodigoBarras .= $cae['CAE'];
    $numeroCodigoBarras .= $cae['CAEFchVto'];
    $codigoVerificador = DigitoVerificador($numeroCodigoBarras);
    $numeroCodigoBarras .= $codigoVerificador;

    $envio['numero_talonario'] = str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
    $envio['numero_factura'] = str_pad($cae['CbteDesde'], 8, 0, STR_PAD_LEFT);
    $envio['fecha'] = date('d/m/Y', strtotime($factura['fecha']));
    $envio['vencimiento'] = (!empty($factura['vencimiento'])) ? date('d/m/Y', strtotime($factura['vencimiento'])) : NULL;
    $envio['impuesto'] = $factura['impuesto'];

    $envio['id_documento_tipo'] = $factura['id_documento_tipo'];
    $envio['documento_numero'] = $factura['documento_numero'];
    $envio['razon_social'] = $factura['razon_social'];
    $envio['condicion_iva'] = $factura['condicion_iva'];

    if (!empty($factura['domicilio']))
    {
        $envio['domicilio'] = $factura['domicilio'];
        if ($factura['codigo_postal'])
            $envio['domicilio'] .= ', ' . $factura['codigo_postal'];
        if ($factura['provincia'])
            $envio['domicilio'] .= ', ' . $factura['provincia'];
        if ($factura['pais'])
            $envio['domicilio'] .= ', ' . $factura['pais'];
    }

    $envio['items'] = json_encode($factura['items']);

    $envio['bruto'] = $factura['bruto'];
    $envio['descuento'] = $factura['descuento'];
    $envio['subtotal210'] = $factura['subtotal210'];
    $envio['imp210'] = $factura['imp210'];
    $envio['total_neto'] = $factura['total_neto'];

    $envio['CAE'] = $cae['CAE'];
    $envio['CAEFchVto'] = date('d/m/Y', strtotime($cae['CAEFchVto']));
    $envio['numeroCodigoBarras'] = $numeroCodigoBarras;
    $envio['moneda'] = $factura['moneda'];

    // arma qr 
    $codigoqr = array(
        'ver' => 1,
        'fecha' => date("Y-m-d", strtotime($factura['fecha'])),
        'cuit' => 30716710072,
        'ptoVta' => intval(str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT)),
        'tipoCmp' => intval($factura['id_afip']),
        'nroCmp' => intval(str_pad($factura['numero_factura'], 8, 0, STR_PAD_LEFT)),
        'importe' => floatval($factura['total_neto']),
        'moneda' => 'PES',
        'ctz' => 1,
        'tipoDocRec' => intval($factura['id_documento_tipo']),
        'nroDocRec' => floatval($factura['documento_numero']),
        'tipoCodAut' => 'E',
        'codAut' => floatval($factura['cae_numero'])
    );
    $codigoqrjson = json_encode($codigoqr);
    $codigoqrjson_base64 = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode($codigoqrjson);
    $envio['codigoqrjson_base64'] = $codigoqrjson_base64;


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $factura['template']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $envio);
    $mensaje_envio = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code === 200)
    {
        $res = $mensaje_envio;
    }

    return (!empty($res)) ? $res : null;
}

?>