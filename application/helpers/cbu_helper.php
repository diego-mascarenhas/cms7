<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Converts 22-digit Banelco CBU format to 26-digit SNP format
 * Adds '0000' after the first 3 digits (bank code)
 * 
 * @param string $cbu_banelco 22-digit CBU number
 * @return string 26-digit CBU number
 */
function convert_cbu_to_snp($cbu_banelco) {
    if (strlen($cbu_banelco) !== 22) {
        return false;
    }

    // Extract first 3 digits (bank code)
    $bank_code = substr($cbu_banelco, 0, 3);
    // Get remaining digits
    $remaining = substr($cbu_banelco, 3);
    
    // Insert '0000' after bank code
    return $bank_code . '0000' . $remaining;
} 