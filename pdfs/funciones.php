<?php

function DigitoVerificador($codbarras)
{
    $totalpares = 0;
    $totalimpares = 0;
    $digito = 0;

    for ($i = 1; $i <= strlen($codbarras); $i = $i + 2)
    {
        $totalimpares = $totalimpares + substr($codbarras, $i, 1);
    }

    for ($i = 0; $i <= strlen($codbarras); $i = $i + 2)
    {
        $totalpares = $totalpares + substr($codbarras, $i, 1);
    }

    $suma = $totalimpares + $totalpares;

    while ((intval($suma / 10) * 10) <> $suma)
    {
        $suma = $suma + 1;
        $digito = $digito + 1;
    }
    
    return $digito;
}


function htmlParaPdf($factura, $cae)
{
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
	    if ($factura['codigo_postal']) $envio['domicilio'] .= ', ' . $factura['codigo_postal'];
	    if ($factura['provincia']) $envio['domicilio'] .= ', ' . $factura['provincia'];
	    if ($factura['pais']) $envio['domicilio'] .= ', ' . $factura['pais'];
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
    $codigoqrjson_base64 = "https://www.afip.gob.ar/fe/qr/?p=". base64_encode($codigoqrjson);
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