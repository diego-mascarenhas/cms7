<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Afip_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function guarda_factura_actualiza($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('facturas', $data);
        return $id;
    }


    public function getafacturar($cantidad, $id = 0)
    {
        $this->db->select('facturas.id, facturas.grupo, facturas_tipo.cuit, facturas.fecha, facturas.vencimiento, facturas.numero_talonario, facturas_tipo.id_afip, facturas_tipo.impuesto, 2 AS id_concepto, condiciones_iva.id_afip AS id_condicion_iva, condiciones_iva.condicion_iva, empresas_fiscales.razon_social, empresas_fiscales.domicilio, empresas_fiscales.codigo_postal, empresas_fiscales.provincia, empresas_fiscales.pais, IF(empresas_fiscales.cuit, IF(CHAR_LENGTH(empresas_fiscales.cuit)>8, 80, 96), 99) AS id_documento_tipo, empresas_fiscales.cuit AS documento_numero, facturas.padre, facturas.bruto, facturas.descuento, facturas.SUBTOTAL210, facturas.IMP210, facturas.total_neto');
        $this->db->from('facturas');
        $this->db->join('empresas_fiscales', 'facturas.id_empresa_fiscal = empresas_fiscales.id', 'left');
        $this->db->join('facturas_tipo', 'facturas.id_factura_tipo = facturas_tipo.id', 'left');
        $this->db->join('condiciones_iva', 'empresas_fiscales.id_condicion_iva = condiciones_iva.id', 'left');
        if ($id > 0)
        {
            $this->db->where('facturas.id', $id);
        }
        $this->db->where(array('facturas.estado' => 1, 'facturas.operacion' => 'V', 'facturas_tipo.id_afip !=' => NULL, 'facturas_tipo.cuit !=' => NULL, ' facturas_tipo.estado' => 3));
        $this->db->where("DATE_ADD(facturas.fecha_alta, INTERVAL 1 HOUR) < NOW()");
        /*
        $this->db->where("NOT EXISTS (  SELECT true
                                     FROM servicios
                                     LEFT JOIN empresas_fiscales AS empresas_fiscales_sub ON servicios.id_empresa = empresas_fiscales_sub.id_empresa
                                     LEFT JOIN empresas ON servicios.id_empresa = empresas.id
                                     LEFT JOIN categorias_generales ON categorias_generales.id = servicios.id_categoria
                                     WHERE empresas_fiscales_sub.id = empresas_fiscales.id
                                     AND servicios.estado = 4
                                     AND empresas.estado > 0
                                     AND empresas_fiscales_sub.estado > 0
                                     AND DATE_FORMAT(servicios.proxima, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                                     AND DATE_FORMAT(servicios.proxima, '%Y-%m-%d') <= DATE_FORMAT(NOW(), '%Y-%m-%d')
                                     AND (IF(servicios.valor>0, servicios.valor, categorias_generales.valor) != 0 AND servicios.descuento < 100)");
                                     */

        if ($cantidad > 0)
        {
            $this->db->limit($cantidad);
        }

        $ret = $this->db->get()->result_array();

        return $ret;
    }


    public function getfacturaxid($id)
    {
        $this->db->select('facturas.cae_numero, facturas.cae_vencimiento, facturas.id, facturas.fecha, facturas.vencimiento,  facturas_tipo.cuit, facturas.numero_talonario, facturas.numero_factura, facturas_tipo.id_afip, facturas_tipo.impuesto, 2 AS id_concepto, condiciones_iva.id_afip AS id_condicion_iva, condiciones_iva.condicion_iva, empresas_fiscales.razon_social, empresas_fiscales.domicilio, empresas_fiscales.codigo_postal, empresas_fiscales.provincia, empresas_fiscales.pais, IF(empresas_fiscales.cuit, IF(CHAR_LENGTH(empresas_fiscales.cuit)>8, 80, 96), 99) AS id_documento_tipo, empresas_fiscales.cuit AS documento_numero, facturas.padre, facturas.bruto, facturas.descuento, facturas.SUBTOTAL210, facturas.IMP210, facturas.total_neto, facturas_tipo.plantilla AS template');
        $this->db->from('facturas');
        $this->db->join('empresas_fiscales', 'facturas.id_empresa_fiscal = empresas_fiscales.id', 'left');
        $this->db->join('facturas_tipo', 'facturas.id_factura_tipo = facturas_tipo.id', 'left');
        $this->db->join('condiciones_iva', 'empresas_fiscales.id_condicion_iva = condiciones_iva.id', 'left');
        $this->db->where('facturas.id', $id);
        $this->db->where(array('facturas.operacion' => 'V', 'facturas_tipo.id_afip !=' => NULL));

        $ret = $this->db->get()->result_array();

        return $ret;
    }


    public function getfacturaitem($id)
    {
        $this->db->select('facturas_items.descripcion, facturas_items.valor, facturas_items.descuento, facturas_tipo.impuesto AS impuesto, ROUND((facturas_items.valor-facturas_items.descuento)*facturas_tipo.impuesto/100+(facturas_items.valor-facturas_items.descuento), 2) AS total_neto');
        $this->db->from('facturas_items');
        $this->db->join('facturas', 'facturas.id = facturas_items.id_factura');
        $this->db->join('facturas_tipo', 'facturas_tipo.id = facturas.id_factura_tipo');
        $this->db->where('facturas_items.id_factura', $id);

        $ret = $this->db->get()->result_array();

        return $ret;
    }


    public function getafacturarprueba27122021($cantidad, $id = 0)
    {
        $this->db->select('facturas.id, facturas.grupo, facturas_tipo.cuit, facturas.fecha, facturas.vencimiento, facturas.numero_talonario, facturas_tipo.id_afip, facturas_tipo.impuesto, 2 AS id_concepto, condiciones_iva.id_afip AS id_condicion_iva, condiciones_iva.condicion_iva, empresas_fiscales.razon_social, empresas_fiscales.domicilio, empresas_fiscales.codigo_postal, empresas_fiscales.provincia, empresas_fiscales.pais, IF(empresas_fiscales.cuit, IF(CHAR_LENGTH(empresas_fiscales.cuit)>8, 80, 96), 99) AS id_documento_tipo, empresas_fiscales.cuit AS documento_numero, facturas.padre, facturas.bruto, facturas.descuento, facturas.SUBTOTAL210, facturas.IMP210, facturas.total_neto');
        $this->db->from('facturas');
        $this->db->join('empresas_fiscales', 'facturas.id_empresa_fiscal = empresas_fiscales.id', 'left');
        $this->db->join('facturas_tipo', 'facturas.id_factura_tipo = facturas_tipo.id', 'left');
        $this->db->join('condiciones_iva', 'empresas_fiscales.id_condicion_iva = condiciones_iva.id', 'left');
        if ($id > 0)
        {
            $this->db->where('facturas.id', $id);
        }
        $this->db->where(array('facturas.estado' => 7, 'facturas.operacion' => 'V', 'facturas_tipo.id_afip !=' => NULL, 'facturas_tipo.cuit !=' => NULL, ' facturas_tipo.estado' => 3));
        $this->db->where("DATE_ADD(facturas.fecha_alta, INTERVAL 1 HOUR) < NOW()");
        $this->db->where("NOT EXISTS (  SELECT true
                                    FROM servicios
                                    LEFT JOIN empresas_fiscales AS empresas_fiscales_sub ON servicios.id_empresa = empresas_fiscales_sub.id_empresa
                                    LEFT JOIN empresas ON servicios.id_empresa = empresas.id
                                    LEFT JOIN categorias_generales ON categorias_generales.id = servicios.id_categoria
                                    WHERE empresas_fiscales_sub.id = empresas_fiscales.id
                                    AND servicios.estado = 4
                                    AND empresas.estado > 0
                                    AND empresas_fiscales_sub.estado > 0
                                    AND DATE_FORMAT(servicios.proxima, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                                    AND DATE_FORMAT(servicios.proxima, '%Y-%m-%d') <= DATE_FORMAT(NOW(), '%Y-%m-%d')
                                    AND (IF(servicios.valor>0, servicios.valor, categorias_generales.valor) != 0 AND servicios.descuento < 100)");

        if ($cantidad > 0)
        {
            $this->db->limit($cantidad);
        }

        $ret = $this->db->get()->result_array();

        return $ret;
    }
}