<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Afip extends CI_Controller {


    public function pruebaimpresion()
    {

        $this->load->library('facturapdf');
        $resultado = $this->facturapdf->generaPdf(35792);
        


    }


    public function pruebafact()
    {
        $params = array('CUIT' => 30716710072,'production' => TRUE);
        echo 5;
        $this->load->library('afip', $params);
        echo 6;
        try {
            $afip = new Afip($params);
            echo "La librería Afip se cargó correctamente.";
        } catch (Exception $e) {
            echo "Error al cargar la librería Afip: " . $e->getMessage();
        }
        echo 7;
        $lastvou = $afip->ElectronicBilling->GetLastVoucher(1, 103);
        echo 8;
        print_r($lastvou);
        die();
        
        // conexion via libreria codeigniter
        $this->load->library('facturaelectronica');
        $params = array('CUIT' => 30716710072,'production' => TRUE);

        $resultado = $this->facturaelectronica->prueba($params);
//        $resultado = $this->facturaelectronica->facturaprueba();
//        $resultado = $this->facturaelectronica->facturapruebaNC();

        
        echo '<pre>'.print_r($resultado,true).'</pre>';
    }

    public function generafacturasprueba()
    {
        $this->load->library('facturaelectronica');
        $resultado = $this->facturaelectronica->facturaprueba();
        echo print_r($resultado);

    }

    public function llamafacturarxid($id)
    {
        $this->load->library('facturaelectronica');
        $resultado = $this->facturaelectronica->factura($id);
        echo print_r($resultado);

    }


    public function CronFacturas($cantidad = 1)
    {

        $this->load->library('facturaelectronica');
        $resultado = $this->facturaelectronica->facturasParaFacturar($cantidad);
        echo print_r($resultado);
    }


}

