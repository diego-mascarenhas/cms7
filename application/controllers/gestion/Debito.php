<?php defined('BASEPATH') or exit('No direct script access allowed');


class Debito extends MY_Controller {

	public function index()
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('movimiento_model');
			
			$data['debitos'] = $this->movimiento_model->generarDebito();
			$data['total'] = $this->movimiento_model->totalDebito();
			
			$this->load->view('/header');
			$this->load->view('/gestion/debito/index', $data);
			$this->load->view('/footer');
		}
		
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('user'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('guest'))
		{
			redirect(base_url('multimedia'));
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	public function exportar($fechaVto = null)
	{
		if ($this->is_logged_in('reseller'))
		{
			$this->trackUri();
			
			// models
			$this->load->model('movimiento_model');
			
			
			// Obtener los débitos y contar registros
			$debitos = $this->movimiento_model->generarDebito();
			$cantidadRegistros = count($debitos);
			
			// Formatear cantidad de registros (7 dígitos)
			$cantidadFormateada = str_pad($cantidadRegistros, 7, '0', STR_PAD_LEFT);
			
			// Obtener el total
			$total = $this->movimiento_model->totalDebito();
			
			// Formatear el importe total (12 enteros y 2 decimales, sin puntos ni comas)
			$importeTotal = number_format($total, 2, '', '');
			$importeTotal = str_pad($importeTotal, 14, '0', STR_PAD_LEFT);

			// Construir el header con el formato correcto
			$contenido = "00" .                    // Tipo de registro (pos 1-2)
						 "018888" .                // Nro de prestación (pos 3-8)
						 "C" .                     // Servicio (pos 9)
						 date('Ymd') .            // Fecha de generación (pos 10-17)
						 "1" .                     // Identificación de archivo (pos 18)
						 "EMPRESA" .               // Origen (pos 19-25)
						 $importeTotal .           // Importe total (pos 26-39)
						 $cantidadFormateada .     // Cantidad de registros (pos 40-46)
						 str_repeat(" ", 304) .    // Espacios en blanco (pos 47-350)
						 "\r\n";

			// Agregar las líneas de detalle
			foreach ($debitos as $debito) {
				if ($fechaVto === null) {
					$fechaVto = date('Ymd', strtotime('+2 days'));
				}
				
				$contenido .= "0370" .                        // Tipo de registro (pos 1-4)
							 str_pad($debito['codigo'], 22, ' ', STR_PAD_RIGHT) .  // ID Cliente (pos 5-26)
							 $debito['cbu'] .                          // CBU sin espacios (pos 27-52)
							 "REVISION ALPHA " .             // Referencia unívoca (pos 53-67)
							 $fechaVto .                     // Fecha de vencimiento (pos 68-75)
							 str_pad(number_format($debito['saldo'], 2, '', ''), 14, '0', STR_PAD_LEFT) . // Importe (pos 76-89)
							 "00000000" . // Fecha de 2º vencimiento (pos 90-97)
							 "00000000000000" .  // Importe (pos 98-111)
							 "00000000" . // Fecha de 3º vencimiento (pos 112-119)
							 "00000000000000" .  // Importe (pos 120-133)
							 "0" . // Moneda factura
							 "   " . // 3 espacios
							 "000000000000000" .            // Más ceros
							 "                      " .      // Espacios
							 "0000000000000000000000000000000000000000" . // Ceros finales
							 "\r\n";
			}

			// Agregar el trailer (registro de cierre)
			$contenido .= "99" .                     // Tipo de registro (pos 1-2)
						 "018888" .                 // Nro de prestación (pos 3-8)
						 "C" .                      // Servicio (pos 9)
						 date('Ymd') .             // Fecha de generación (pos 10-17)
						 "1" .                      // Identificación de archivo (pos 18)
						 "EMPRESA" .                // Origen (pos 19-25)
						 $importeTotal .            // Importe total (pos 26-39)
						 $cantidadFormateada .      // Cantidad de registros (pos 40-46)
						 str_repeat(" ", 304) .     // Espacios en blanco (pos 47-350)
						 "\r\n";

			// Configurar headers para descarga
			header('Content-Type: text/plain');
			header('Content-Disposition: attachment; filename="DEBITOS.txt"');
			header('Content-Length: ' . strlen($contenido));
			header('Cache-Control: no-store, no-cache');
			header('Pragma: no-cache');

			// Enviar contenido
			echo $contenido;
			exit;
		}
		
		elseif ($this->is_logged_in('admin'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('user'))
		{
			redirect(base_url('micuenta'));
		}
		
		elseif ($this->is_logged_in('guest'))
		{
			redirect(base_url('multimedia'));
		}
		
		else
		{
			redirect(base_url('user/login'));
		}
	}

	
}