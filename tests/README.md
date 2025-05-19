# Standalone Tests

This directory contains standalone tests for running specific functions from the CodeIgniter application without the full framework.

## Available Tests

1. `generar_debito_test.php` - Runs the `generarDebito()` function and outputs the results
2. `total_debito_test.php` - Runs the `totalDebito()` function and outputs the results
3. `generar_archivo_debito.php` - Generates the debit file in the same format as the `exportar` method in the Debito controller

## How to Run

From the command line, navigate to the tests directory and run:

```bash
# Run generarDebito test
php generar_debito_test.php

# Run totalDebito test
php total_debito_test.php

# Generate debit file (creates DEBITOS_YYYYMMDD.txt in the current directory)
php generar_archivo_debito.php

# Generate debit file with a specific due date (YYYYMMDD format)
php generar_archivo_debito.php 20240630
```

The output will include:
- The SQL query being executed (for test scripts)
- The results in a readable format (for test scripts)
- A file with debit information (for generar_archivo_debito.php)

## Database Configuration

Database connection details are stored in `db_config.php`. If you need to change the connection settings, edit this file. 