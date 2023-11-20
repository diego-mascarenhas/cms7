<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal library configuration
// ------------------------------------------------------------------------
//
// Se puede usar la función getPayPalCredenciales();
//

// PayPal environment, Sandbox or Live
$config['paypal_sandbox_mode'] = false; // FALSE for live environment

// PayPal business email
$config['paypal_business_email'] = 'administracion@revisionalpha.com.ar';

// What is the default currency?
$config['paypal_currency_code'] = 'USD';

// If (and where) to log ipn response in a file
$config['paypal_ipn_log'] = false;
$config['paypal_ipn_log_file'] = 'logs/paypal.log';