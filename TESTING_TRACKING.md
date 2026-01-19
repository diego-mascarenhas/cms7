# Testing - Sistema de Tracking de Usuarios en Cursos

## Backend Completado ✅

### 1. Base de Datos
**Archivo**: `migration_tracking_usuarios.sql`

Ejecutar el script SQL para agregar las columnas:
```bash
mysql -u tu_usuario -p nombre_base_datos < migration_tracking_usuarios.sql
```

O manualmente en phpMyAdmin/MySQL:
```sql
ALTER TABLE con_rel_pedido_contactos 
ADD COLUMN fecha_ingreso_video DATETIME NULL COMMENT 'Fecha y hora cuando entró a la página del video',
ADD COLUMN fecha_completo_encuesta DATETIME NULL COMMENT 'Fecha y hora cuando completó la encuesta exitosamente';
```

Verificar que se agregaron correctamente:
```sql
DESCRIBE con_rel_pedido_contactos;
```

---

### 2. API Endpoint - Registrar Ingreso a Video

**Endpoint**: `POST /api-v2/CmsElearning/registrar_ingreso_video`

**Test con cURL**:
```bash
curl -X POST https://cms.revisionalpha.com/api-v2/CmsElearning/registrar_ingreso_video \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t" \
  -d '{
    "id_contacto": 123,
    "id_producto": 78,
    "id_pedido": 1775
  }'
```

**Respuesta esperada (éxito)**:
```json
{
  "status": true,
  "message": "Ingreso a video registrado correctamente",
  "data": {
    "id": 456,
    "actualizado": 1
  }
}
```

**Respuesta si ya existe**:
```json
{
  "status": true,
  "message": "Ingreso a video registrado correctamente",
  "data": {
    "id": 456,
    "actualizado": 0,
    "mensaje": "Ya existe fecha de ingreso"
  }
}
```

---

### 3. API Endpoint - Certificar (Modificado)

**Endpoint**: `POST /api-v2/CmsElearning/certificar`

Este endpoint ahora **también registra automáticamente** la `fecha_completo_encuesta` cuando el usuario certifica exitosamente.

**Test con cURL**:
```bash
curl -X POST https://cms.revisionalpha.com/api-v2/CmsElearning/certificar \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t" \
  -d '{
    "id_tipo": 2,
    "id_contacto": 123,
    "id_producto": 78,
    "certificado": 1
  }'
```

---

### 4. Verificar en Base de Datos

Después de las pruebas con cURL, verificar que se guardaron las fechas:

```sql
SELECT 
    id,
    id_pedido,
    id_contacto,
    id_producto,
    fecha_ingreso_video,
    fecha_completo_encuesta,
    certificado
FROM con_rel_pedido_contactos
WHERE id_contacto = 123 AND id_producto = 78;
```

---

### 5. Vista Admin - Ver Seguimiento

**Rutas creadas**:
- `/cms-v2/elearning/pedidos/seguimiento/{id_pedido}` - Página de seguimiento
- Botón agregado en `/cms-v2/elearning/pedidos/detalle/{id_pedido}`

**Acceso**:
1. Ir a: https://cms.revisionalpha.com/cms-v2/elearning/pedidos/detalle/1775
2. Hacer clic en el botón **"Ver Seguimiento"** (azul con ícono de gráfico)
3. Ver tabla con:
   - Usuario
   - Email  
   - Curso
   - Fecha ingresó a video
   - Fecha completó encuesta
   - Estado del certificado

**Códigos de colores**:
- 🟢 **Verde**: Usuario completó encuesta y tiene certificado
- 🟡 **Amarillo**: Usuario vio video pero no completó encuesta
- ⚪ **Sin color**: Sin actividad

---

## Próximos Pasos - Frontend (Pendiente)

### 6. Frontend - Registro Automático (A implementar)

**Archivo**: `/Users/magoo/Sites/academializama/app/Views/gestor/detalle-curso.php`
- Agregar JavaScript que llame al endpoint `registrar_ingreso_video` cuando carga la página

**Archivo**: `/Users/magoo/Sites/academializama/app/Controllers/Gestor.php`
- El método `certificar()` ya debería actualizar la fecha automáticamente vía el endpoint modificado

---

## Datos de Prueba Sugeridos

### Pedido de Prueba: 1775
Este pedido tiene usuarios cargados para probar.

### IDs para testing:
- `id_pedido`: 1775
- `id_producto`: 78 (o el ID del curso que tengas en ese pedido)
- `id_contacto`: Usar el ID de alguno de los contactos que subiste en el CSV

### Consulta para obtener IDs reales:
```sql
SELECT 
    rpc.id_pedido,
    rpc.id_contacto,
    rpc.id_producto,
    c.nombre,
    c.apellido,
    c.email
FROM con_rel_pedido_contactos rpc
LEFT JOIN contactos c ON c.id = rpc.id_contacto
WHERE rpc.id_pedido = 1775
LIMIT 5;
```

---

## Checklist de Testing Backend

- [ ] Ejecutar migration SQL
- [ ] Verificar columnas creadas en `con_rel_pedido_contactos`
- [ ] Probar endpoint `registrar_ingreso_video` con cURL
- [ ] Verificar en BD que se guardó `fecha_ingreso_video`
- [ ] Probar endpoint `certificar` con cURL
- [ ] Verificar en BD que se guardó `fecha_completo_encuesta`
- [ ] Acceder a vista admin `/cms-v2/elearning/pedidos/detalle/1775`
- [ ] Verificar que aparece botón "Ver Seguimiento"
- [ ] Hacer clic en botón y verificar tabla de seguimiento
- [ ] Verificar que se muestran las fechas correctamente
- [ ] Verificar códigos de colores según el estado

---

## Archivos Modificados/Creados

### Backend (CMS7)
1. ✅ `migration_tracking_usuarios.sql` - Script de migración
2. ✅ `application/models/cms-v2/elearning/Pedidos_model.php` - Métodos agregados:
   - `registrarIngresoVideo()`
   - `obtenerProgresoUsuarios()`
3. ✅ `application/controllers/api-v2/CmsElearning.php` - Endpoints:
   - `registrar_ingreso_video_post()` (nuevo)
   - `certificar_post()` (modificado)
4. ✅ `application/controllers/cms-v2/elearning/Pedidos.php` - Método agregado:
   - `seguimiento()`
5. ✅ `application/views/cms-v2/lizama/elearning/pedidos/seguimiento.php` - Vista nueva
6. ✅ `application/views/cms-v2/lizama/elearning/pedidos/detalle.php` - Botón agregado

### Frontend (academializama) - PENDIENTE
7. ⏳ `app/Views/gestor/detalle-curso.php` - Agregar JS para tracking
8. ⏳ `app/Controllers/Gestor.php` - Verificar método certificar()

---

## Notas Importantes

- El endpoint `registrar_ingreso_video` **solo registra la primera vez**. Si ya existe una fecha, no la sobrescribe.
- El endpoint `certificar` actualiza `fecha_completo_encuesta` automáticamente cuando `certificado = 1`.
- La vista de seguimiento muestra todos los usuarios del pedido con su actividad.
- Los colores en la vista ayudan a identificar rápidamente el estado de cada usuario.
