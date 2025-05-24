# CMS7
CMS+ KUN (CodeIgniter 3)

## Docker Setup

Para ejecutar la aplicación en Docker:

```bash
# Navegar al directorio docker
cd docker/

# Construir las imágenes
docker-compose build

# Iniciar la aplicación
docker-compose up

# Para ejecutar en segundo plano
docker-compose up -d
```

## Configuración de la aplicación

Antes de iniciar la aplicación, es necesario modificar los siguientes archivos de configuración:

1. **application/config/config.php**
   - Configurar `base_url` según su entorno
   - Verificar la configuración de sesiones

2. **application/config/database.php**
   - Configurar las credenciales de la base de datos
   - Para Docker, la conexión debe apuntar al servicio de la base de datos

3. **application/config/smtp.php**
   - Configurar los datos del servidor SMTP para el envío de correos

## Acceso a la aplicación

La aplicación estará disponible en: http://localhost:8080

## License

The CMS7 admin is open-sourced software licensed under the GNU Affero General Public License v3.0

### Additional Terms

By deploying this software, you agree to notify the original author at diego.mascarenhas@icloud.com or by visiting http://linkedin.com/in/diego-mascarenhas/. Any modifications or enhancements must be shared with the original author.
