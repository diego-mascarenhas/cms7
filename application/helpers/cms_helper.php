<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CMS+ by revision alpha
 * @author	Diego Mascarenhas
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter FECHAS Helper
 *
 * @package		CMS+
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/captcha_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('formatear_fecha'))
{
	/**
	 * Format FECHAS
	 *
	 * @param	array	$fecha
	 * @param	string	$tag
	 * @param	string	$timezone
	 * @param	string	$daylight_saving
	 * @param	array	$extras
	 * @return	string
	 */
	function formatear_fecha($fecha = null, $formato = null, $tag = null, $timezone = 'UTC', $daylight_saving = false, $extras = null)
	{
		if ($fecha > 0 && isValidTimeStamp($fecha))
		{
			$fecha_local = nice_date(unix_to_human(gmt_to_local($fecha, $timezone, $daylight_saving)), $formato);
			
			if (isset($tag))
			{
				if (preg_match('/%s/', $tag))
				{
					$res = sprintf($tag, $fecha_local);
				}
				else
				{
					$res = $fecha_local . $tag;
				}
			}
			else
			{
				$res = $fecha_local;
			}
		}
		
		elseif (isset($extras['default']))
		{
			$res = $extras['default'];
		}
		
		else
		{
			$res = $fecha;
		}
		
		return (!empty($res)) ? $res : null;
	}
}


if (!function_exists('fecha_db'))
{
	/**
	 * Format FECHAS
	 *
	 * @param	array	$fecha
	 * @return	string
	 */
	function fecha_db($fecha = null)
	{
		if (isset($fecha))
		{
			$res = human_to_unix(local_to_gmt($fecha));
		}
			
		return (!empty($res)) ? $res : null;
	}
}


if (!function_exists('isValidTimeStamp'))
{
	function isValidTimeStamp($timestamp)
	{
	    return ((string) (int) $timestamp === $timestamp) 
	        && ($timestamp <= PHP_INT_MAX)
	        && ($timestamp >= ~PHP_INT_MAX);
	}
}


if (!function_exists('verificarPermiso'))
{
	/**
	 * Verifica si un elemento existe en un array, incluso en subarrays
	 *
	 * @param	mixed	$needle		Elemento a buscar
	 * @param	array	$haystack	Array donde buscar
	 * @param	bool	$strict		Usar comparación estricta
	 * @return	bool				True si encuentra el elemento, False si no
	 */
	function verificarPermiso($needle, $haystack, $strict = false)
	{
		// Verificar que haystack sea un array
		if (!is_array($haystack)) {
			return false;
		}
		
	    foreach ($haystack as $item)
	    {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && verificarPermiso($needle, $item, $strict)))
	        {
	            return true;
	        }
	    }
	    
	    // Si no se encontró, retornar false explícitamente
	    return false;
	}
}


if (!function_exists('limpiarDominio'))
{
	function limpiarDominio($dominio)
	{
		$url = strtolower($dominio);
		
		$disallowed = array('http://', 'https://', 'www.');
		
		foreach ($disallowed as $d)
		{
			if (strpos($url, $d) === 0)
			{
				return str_replace($d, '', $url);
			}
		}

		return $url;
	}
}