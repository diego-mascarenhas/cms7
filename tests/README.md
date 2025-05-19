# Standalone Tests

Este directorio contiene pruebas independientes para ejecutar funciones específicas de la aplicación CodeIgniter sin el marco completo.

## Scripts Disponibles

1. `generar_debito_test.php` - Ejecuta la función `generarDebito()` y muestra los resultados
2. `total_debito_test.php` - Ejecuta la función `totalDebito()` y muestra el resultado
3. `generar_archivo_debito.php` - Genera el archivo de débito en el mismo formato que el método `exportar` en el controlador Debito
4. `validar_cbus.php` - Valida los CBUs en la base de datos según el formato SNP (26 caracteres)
5. `corregir_cbus.php` - Corrige los CBUs en formato incorrecto en la base de datos
6. `generar_archivo_debito_corregido.php` - Genera el archivo de débito con CBUs validados y corregidos
7. `corregir_cbus_especificos.php` - Corrige específicamente los dos CBUs rechazados por el banco
8. `exportar_solo_rechazados.php` - Genera un archivo de débito que solo incluye los registros rechazados con CBUs corregidos
9. `generar_archivo_debito_solo_rechazados.php` - Versión modificada del script original que solo procesa los 2 CBUs rechazados con sus versiones corregidas

## Cómo Ejecutar

Desde la línea de comandos, navegue al directorio de pruebas y ejecute:

```bash
# Ejecutar prueba de generarDebito
php generar_debito_test.php

# Ejecutar prueba de totalDebito
php total_debito_test.php

# Generar archivo de débito (crea DEBITOS_YYYYMMDD.txt en el directorio actual)
php generar_archivo_debito.php

# Generar archivo de débito con una fecha de vencimiento específica (formato YYYYMMDD)
php generar_archivo_debito.php 20240630

# Validar los CBUs en la base de datos
php validar_cbus.php

# Verificar qué CBUs necesitan corrección (modo simulación)
php corregir_cbus.php

# Corregir los CBUs en la base de datos (aplicar cambios)
php corregir_cbus.php aplicar

# Generar archivo de débito con CBUs corregidos
php generar_archivo_debito_corregido.php

# Verificar las correcciones específicas para los CBUs rechazados (modo simulación)
php corregir_cbus_especificos.php

# Aplicar correcciones específicas para los CBUs rechazados
php corregir_cbus_especificos.php aplicar

# Generar archivo solo con los registros rechazados (CBUs corregidos)
php exportar_solo_rechazados.php

# Generar archivo de los registros rechazados con fecha de vencimiento específica
php exportar_solo_rechazados.php 20240630

# Generar archivo de débito que solo incluye los 2 CBUs rechazados (formato original con CBUs corregidos)
php generar_archivo_debito_solo_rechazados.php

# Generar archivo de débito de CBUs rechazados con fecha de vencimiento específica
php generar_archivo_debito_solo_rechazados.php 20240630
```

## Resultados

Los scripts proporcionan diferente información:
- Los scripts de prueba muestran la consulta SQL que se ejecuta y los resultados en un formato legible
- Los scripts de generación de archivos crean un archivo con la información de débito
- Los scripts de validación y corrección muestran detalles sobre los CBUs que no cumplen con el formato correcto

## Formato de CBU

El sistema utiliza el formato SNP de 26 caracteres para los CBUs:

- **Formato Banelco (22 caracteres)**: Formato original
- **Formato SNP (26 caracteres)**: Formato utilizado en el sistema, añadiendo 0 al inicio y 000 al final

### Estructura del CBU (formato SNP)
```
0 + 007 + SSSS + X + 000 + T + M + 00 + FFFFFFFF + A + B + Y
```
Donde:
- 007 = Código de banco
- SSSS = Número de sucursal
- X = Dígito verificador del bloque 1
- T = Tipo de cuenta (2 - CC y 3 - CA)
- M = Moneda de la cuenta (0 - $ y 1 - U$S)
- FFFFFFFF = Folio de la cuenta
- A, B = Dígitos verificadores de la cuenta
- Y = Dígito verificador del bloque 2

## Configuración de Base de Datos

Los detalles de conexión a la base de datos se almacenan en `db_config.php`. Si necesita cambiar la configuración de conexión, edite este archivo. 