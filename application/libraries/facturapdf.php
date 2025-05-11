<?php

class facturapdf {

    function generaPdf($id)
    {


        include("assets/html2pdf/html2pdf.class.php");

        $CI =& get_instance();
        $CI->load->model('facturas_model');
        $row = $CI->facturas_model->getfacturaxid($id);

        if ($row)
        {
            $row = $row[0];
            $comprobantesitems = $CI->facturas_model->getfacturaitem($id);
    
            /* GENERO EL PDF */
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->pdf->SetDisplayMode('real'); 
            $htmlText = $CI->facturapdf->htmlParaPdf($row, $comprobantesitems);

            $html2pdf->writeHTML($htmlText);
            $html2pdf->Output('pdfs/' . $row['cuit'] . '_' . str_pad($row['id_afip'], 2, 0, STR_PAD_LEFT) . '_' . str_pad($row['numero_talonario'], 4, 0, STR_PAD_LEFT) . '_' . str_pad($row['numero_factura'], 8, 0, STR_PAD_LEFT) . '.pdf', 'F'); // 20250242000_01_0003_00000001
            echo "AA";
        }
        
        return (!empty($row)) ? $row : null;
    }


    function htmlParaPdf($factura, $facturaitems )
    {

        $CI =& get_instance();
        $numeroCodigoBarras = $factura['cuit'];
        $numeroCodigoBarras .= str_pad($factura['id_afip'], 2, 0, STR_PAD_LEFT);
        $numeroCodigoBarras .= str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
        $numeroCodigoBarras .= $factura['cae_numero'];
        $numeroCodigoBarras .= $factura['cae_vencimiento'];
        $codigoVerificador = $CI->facturapdf->DigitoVerificador($numeroCodigoBarras);
        $numeroCodigoBarras .= $codigoVerificador;
        
        
        $envio['numero_talonario'] = str_pad($factura['numero_talonario'], 4, 0, STR_PAD_LEFT);
        $envio['numero_factura'] = str_pad($factura['numero_factura'], 8, 0, STR_PAD_LEFT);
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
                
        $envio['items'] = json_encode($facturaitems);
        
        $envio['bruto'] = $factura['bruto'];
        $envio['descuento'] = $factura['descuento'];
        $envio['subtotal210'] = $factura['SUBTOTAL210'];
        $envio['imp210'] = $factura['IMP210'];
        $envio['total_neto'] = $factura['total_neto'];
        
        $envio['CAE'] = $factura['cae_numero'];
        $envio['CAEFchVto'] = date('d/m/Y', strtotime($factura['cae_vencimiento']));
        $envio['numeroCodigoBarras'] = $numeroCodigoBarras;
        $envio['baseurl'] = base_url();
        //$dirtemplate = base_url('assets\templates\revisionalpha\20250242000_01_0003.php');
//        $dirtemplate = base_url('assets\templates'.$factura['template']);
        $dirtemplate = base_url('assets/templates/revisionalpha/30716710072_01_0001.php');

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
        curl_setopt($ch, CURLOPT_URL, $dirtemplate);
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


    function generaPdfbis()
    {

        $CI =& get_instance();

        include("assets/html2pdf/html2pdf.class.php");

        /* GENERO EL PDF */
        $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
        $html2pdf->pdf->SetDisplayMode('real'); 
        $htmlText = $CI->facturapdf->htmlParaPdfbis();
        $html2pdf->writeHTML($htmlText);
        $html2pdf->Output('pdfs/cuitprueba_1_1_9955.pdf', 'F'); // 20250242000_01_0003_00000001
        
        return (!empty($row)) ? $row : null;
    }

    function htmlParaPdfbis()
    {
        $numeroCodigoBarras = '30-999999-33';
        $numeroCodigoBarras .= str_pad(1, 2, 0, STR_PAD_LEFT);
        $numeroCodigoBarras .= str_pad(1, 4, 0, STR_PAD_LEFT);
        $numeroCodigoBarras .= '123123123';
        $numeroCodigoBarras .= '01-01-2030';
        
        
        $envio['numero_talonario'] = str_pad(1, 4, 0, STR_PAD_LEFT);
        $envio['numero_factura'] = str_pad(9955, 8, 0, STR_PAD_LEFT);
        $envio['fecha'] = '15-04-2021';
        $envio['vencimiento'] = '15-04-2021';
        $envio['impuesto'] = 21;
        
        $envio['id_documento_tipo'] = 80;
        $envio['documento_numero'] = 3034304304;
        $envio['razon_social'] = 'pruebafactura cliente';
        $envio['condicion_iva'] = 'respins';
        
        $envio['domicilio'] = 'asdasdasdasdads';
                
        $envio['items'] = 'aaaaaaaaaaaaaaaaaaaaaaaaaa';
        
        $envio['bruto'] = 121;
        $envio['descuento'] = 0;
        $envio['subtotal210'] = 100;
        $envio['imp210'] = 21;
        $envio['total_neto'] = 200;
        
        $envio['CAE'] = 23123123;
        $envio['CAEFchVto'] = '01-01-2030';
        $envio['numeroCodigoBarras'] = $numeroCodigoBarras;
        $envio['baseurl'] = base_url();
        
        $dirtemplate = base_url('assets\templates\revisionalpha\20250242000_01_0003.php');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $dirtemplate);
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




}


?>
