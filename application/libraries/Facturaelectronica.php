<?php

class facturaelectronica {

  public function factura($id){

    $datehorahoy = date('d-m-Y H:i:s');
    $nombrelog = 'Corrida'.date('d-m-Y').'_'.time();
    $resultcomp = "Comienza Corrida id ".$id." con Fecha y Hora " . $datehorahoy ." <br>" ;

    $CI =& get_instance();
    $CI->load->model('facturas_model');
    $comprobantes = $CI->facturas_model->getafacturar(1, $id);
    $resultcomp .= $CI->facturaelectronica->procesacomprobantes($comprobantes);

    $myfile = file_put_contents('./assets/archivos/logscorridas/'.$nombrelog.'.txt', $resultcomp.PHP_EOL , FILE_APPEND | LOCK_EX);

    return $resultcomp;
  }

  public function facturasParaFacturar($cantidad){

    $datehorahoy = date('d-m-Y H:i:s');
    $nombrelog = 'Corrida'.date('d-m-Y').'_'.time();
    $resultcomp = "Comienza Batch con Fecha y Hora " . $datehorahoy ." <br>" ;

    $CI =& get_instance();
    $CI->load->model('facturas_model');
    $comprobantes = $CI->facturas_model->getafacturar($cantidad);
    $resultcomp .= $CI->facturaelectronica->procesacomprobantes($comprobantes);

    $myfile = file_put_contents('./assets/archivos/logscorridas/'.$nombrelog.'.txt', $resultcomp.PHP_EOL , FILE_APPEND | LOCK_EX);

    return $resultcomp;

  }

  public function procesacomprobantes($comprobantes){

    $CI =& get_instance();
    $CI->load->model('facturas_model');

    $resultcomp = '';
      
    foreach ($comprobantes as $row) {

      $datoscomprobante = array(
            'idpventa' => $row['numero_talonario'],
            'CbteTipo' => $row['id_afip'],
            'fechamysql' => date('Y-m-d'),
            'neto' => $row['SUBTOTAL210'],
            'iva' => $row['IMP210'],
            'total' => $row['total_neto'],
            'doctipo' => $row['id_documento_tipo'],
            'nrodoc' => str_replace('-', '', $row['documento_numero']),
            'fecha' => $row['fecha'],
            'vto' => $row['vencimiento']
      );
        // actualiza estado
      $data = array(
          'estado' => 7,
          'error' => 'No se terminó el proceso de impresión',
      );
//            $actualizafactura = $CI->clientes_model->guarda_factura_actualiza($row['id'], $data);


      $resfolder = dirname(dirname(__DIR__)).'/assets/certificados/';

      $certificado = $row['grupo'].'-'.$row['cuit'].'.crt';
      $key = $row['grupo'].'-'.$row['cuit'].'.key';

      //$certificado = 'certpruebaroman.crt';
      //$key = 'certpruebaroman.key';

      $CI =& get_instance();
      $CI->load->library('facturaelectronica');
//      $params = array('CUIT' => $row['cuit'],'production' => FALSE,'res_folder' => $resfolder,'cert' => $certificado,'key' => $key);      
      $params = array('CUIT' => $row['cuit'],'production' => TRUE,'res_folder' => $resfolder,'cert' => $certificado,'key' => $key);      

      //$params = array('CUIT' => $row['cuit'],'production' => FALSE);      

      $resultado = $CI->facturaelectronica->creavoucher($params, $datoscomprobante);
      if ( $resultado['status'] == 'OK'){
        $resultcomp .= $resultado['textoresult'];
        $cae =  $resultado['CAE'];
        $fechacae =  $resultado['CAEFchVto'];
        $numero =  $resultado['NROCOMP'];

        // actualiza datos factura con cae
        $data = array(
            'estado' => 2,
            'error' => NULL,
            'observaciones' => NULL,
            'cae_numero' => $cae,
            'cae_vencimiento' => str_replace('-','', $fechacae),
            'numero_factura' => $numero,
        );
        $actualizafactura = $CI->facturas_model->guarda_factura_actualiza($row['id'], $data);
        $resultcomp .= '<br>Comprobante Finalizado<br> CAE '.$cae.'/vencCae'.$fechacae.'------------------------------------------<br>';
      }else{
        $resultcomp .= "ERROR al procesar el ultimo comprobante<br>";
        $resultcomp .= $resultado['textoresult'];

        // actualiza base con observaciones del error
        $data = array(
            'observaciones' => $resultado['observaciones'],
            'error' => $resultado['observaciones'],
        );
//              $actualizafactura = $this->clientes_model->guarda_factura_actualiza($row['id'], $data);

        $myfile = file_put_contents('./assets/archivos/logscorridas/'.$nombrelog.'ERROR.txt', $resultcomp.PHP_EOL , FILE_APPEND | LOCK_EX);

        echo print_r($resultcomp);
        die;
      }
    }
    return $resultcomp;
  }

  public function creavoucher($params, $datoscomprobante){

      date_default_timezone_set('America/Argentina/Buenos_Aires');

      $flag = '';
      $idpventa = $datoscomprobante['idpventa'];
      $CbteTipo = $datoscomprobante['CbteTipo'];
      $fechamysql = date('Y-m-d');
      $neto = $datoscomprobante['neto'];
      $iva = $datoscomprobante['iva'];
      $total = $datoscomprobante['total'];
      $doctipo = $datoscomprobante['doctipo'];;
      $nrodoc = $datoscomprobante['nrodoc'];;
      $nrodoc = str_replace("-","",$nrodoc);
      $vto  = (isset($datoscomprobante['vencimiento'])) ? date('Ymd', strtotime($datoscomprobante['vencimiento'])) : date('Ymd');
      $fecha  = (isset($datoscomprobante['fecha'])) ? date('Ymd', strtotime($datoscomprobante['fecha'])) : date('Ymd');

      $CI =& get_instance();
      $CI->load->library('afip', $params);

      $afip = new Afip($params);

      $lastvou = $afip->ElectronicBilling->GetLastVoucher($idpventa, $CbteTipo);

      $textoresult = 'Ultimo comprobante procesado: ' . $lastvou;
      $Cbteautilizar = $lastvou  + 1;

      if ( $neto > 0 && $Cbteautilizar > 0 && $doctipo > 0){
          $data = array(

              'Concepto'      => 2, // Concepto del Comprobante: (1)Productos, (2)Servicios, (3)Productos y Servicios
              'CantReg'       => 1, // Cantidad de comprobantes a registrar
              'DocTipo'       => $doctipo, // Tipo de documento del comprador (ver tipos disponibles)
              'DocNro'        => trim($nrodoc), // Numero de documento del comprador
              'PtoVta'        => $idpventa, // Punto de venta
              'CbteTipo'      => $CbteTipo, // Tipo de comprobante (ver tipos disponibles) 
              'CbteDesde'     => $Cbteautilizar, // Numero de comprobante o numero del primer comprobante en caso de ser mas de uno
              'CbteHasta'     => $Cbteautilizar, // Numero de comprobante o numero del ultimo comprobante en caso de ser mas de uno
              'CbteFch'       => intval($fecha), // (Opcional) Fecha del comprobante (yyyymmdd) o fecha actual si es nulo
              'ImpTotal'      => $total, // Importe total del comprobante
              'ImpTotConc'    => 0, // Importe neto no gravado
              'ImpNeto'       => $neto, // Importe neto gravado
              'ImpOpEx'       => 0, // Importe exento de IVA
              'ImpIVA'        => $iva, //Importe total de IVA
              'ImpTrib'       => 0, //Importe total de tributos
              'MonId'         => 'PES', //Tipo de moneda usada en el comprobante (ver tipos disponibles)('PES' para pesos argentinos) 
              'MonCotiz'      => 1, // Cotización de la moneda usada (1 para pesos argentinos)  
              'FchServDesde'  => intval(date('Ymd')), // (Opcional) Fecha de inicio del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
              'FchServHasta'  => intval(date('Ymd')), // (Opcional) Fecha de fin del servicio (yyyymmdd), obligatorio para Concepto 2 y 3
              'FchVtoPago'    => intval($vto), // (Opcional) Fecha de vencimiento del servicio (yyyymmdd), obligatorio para Concepto 2             
              'Iva'           => array( // (Opcional) Alícuotas asociadas al comprobante
                  array(
                      'Id'        => 5, // 21% Id del tipo de IVA (ver tipos disponibles) 
                      'BaseImp'   => $neto, // Base imponible
                      'Importe'   => $iva // Importe 
                  )
              ),
          );

          if( $CbteTipo == 2 || $CbteTipo == 3 || $CbteTipo == 7 || $CbteTipo == 8 ){
              $arrayperiodo = array(
                  'FchDesde'      => intval(date($fecha)), 
                  'FchHasta'      => intval(date($fecha))
              );
              $data['PeriodoAsoc'] = $arrayperiodo;
          }


          $textoresult = $textoresult . ' / Comienza Proceso '.$CbteTipo.'/'.$Cbteautilizar;
          $textoresult = $textoresult . '<br> Tipo documento: ' .$doctipo. ' / Nro: ' .$nrodoc;
          $textoresult = $textoresult . '<br> Total: ' .$total. ' / Neto: ' .$neto. ' / Iva:' .$iva;

   //       $textoresult = $textoresult . '<br>  <pre>'.print_r($data,true). '</pre>';

          try {
                  $res = $afip->ElectronicBilling->CreateVoucher($data);
                  $textoresult = $textoresult . '<br> Procesada Correctamente. CAE: '.$res['CAE'];
                  $CAEFchVto = $res['CAEFchVto'];
                  $CAEFchVto = substr($CAEFchVto,0,4) . '-' . substr($CAEFchVto,5,2) . '-'. substr($CAEFchVto,8,2);

                  $msg['CAEFchVto'] = $CAEFchVto;
                  $msg['CAE'] = $res['CAE'];
                  $msg['NROCOMP'] = $Cbteautilizar;

                  $flag = 'OK';
          } catch (Exception $e) {
              //se fija si el error viene por ser una factura mipyme que deberia cambiar su codigocbte
              if ( strpos( $e->getMessage(),'No es un comprobante valido bajo el Regimen de la Ley n° 27.440' )){
                  // es una factura mipyme. Cambio codigos de comprobantes y vuelve a procesar
                  $textoresult = $textoresult . '<br> Detectó Factura Mipyme';
              }

              //se fija si el error viene por ser una factura mipyme que deberia cambiar su codigocbte
              $textoresult = $textoresult . '<br> Error al procesar: ' . $e->getMessage();
              $flag = 'ERROR';
              $msg['observaciones'] = $e->getMessage();

          }

      }
      $msg['textoresult'] = $textoresult;
      $msg['status'] = $flag;


      return $msg;
  }






  public static function prueba($params)
  {

    $CI =& get_instance();
    $CI->load->library('afip', $params);
    
    $afip = new Afip($params);

    $lastvou = $afip->ElectronicBilling->GetLastVoucher(4, 201);

//    $resultadotax = $afip->ElectronicBilling->GetTaxTypes();
    return $lastvou;


  }
  
  public function facturaprueba(){

    $datehorahoy = date('d-m-Y H:i:s');
    $nombrelog = 'Corrida'.date('d-m-Y').'_'.time();
    $resultcomp = "Comienza Corrida id  con Fecha y Hora " . $datehorahoy ." <br>" ;

    $comprobantes = array(array(
          'numero_talonario' => 1,
          'grupo' => 502,
          'id_afip' => 3,
          'SUBTOTAL210' => 6170,
          'IMP210' => 1295.70,
          'total_neto' => 7465.70,
          'id_documento_tipo' => 80,
          'documento_numero' => '30714586676',
          'fecha' => '22-12-2021',
          'vencimiento' => '22-12-2021',
          'cuit' => 30716710072
    ));

    $CI =& get_instance();
    $resultcomp .= $CI->facturaelectronica->procesacomprobantes($comprobantes);

    $myfile = file_put_contents('./assets/archivos/logscorridas/'.$nombrelog.'.txt', $resultcomp.PHP_EOL , FILE_APPEND | LOCK_EX);

    return $resultcomp;
  }

  public function facturapruebaNC(){

    $CI =& get_instance();
    $CI->load->model('facturas_model');
    $comprobantes = $CI->facturas_model->getafacturarprueba27122021(10);
    return $comprobantes;
//    $resultcomp .= $CI->facturaelectronica->procesacomprobantes($comprobantes);


//    return $resultcomp;


  }

}


?>
