# Implementación Frontend - Sistema de Tracking eLearning

## 🎯 Objetivo
Implementar las llamadas a la API para registrar:
1. **Fecha de ingreso al video**: Cuando el usuario entra a la página del curso
2. **Fecha de completar encuesta**: Cuando el usuario certifica exitosamente

---

## 📋 Información de los Endpoints API

### Base URL
```
https://cms.revisionalpha.com/api-v2/CmsElearning/
```

### Header requerido en todas las llamadas
```
CMS-API-KEY: e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t
```

---

## 1️⃣ Endpoint: Registrar Ingreso a Video

**Método**: `POST`  
**URL**: `https://cms.revisionalpha.com/api-v2/CmsElearning/registrar_ingreso_video`

**Datos requeridos** (JSON):
```json
{
  "id_contacto": 123,
  "id_producto": 78,
  "id_pedido": 1775
}
```

**Respuesta exitosa**:
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

**Cuándo llamarlo**: 
- Al cargar la página del video (`detalle-curso.php`)
- En el evento `DOMContentLoaded` o cuando se confirme que el video está visible

---

## 2️⃣ Endpoint: Certificar (actualizado)

**Método**: `POST`  
**URL**: `https://cms.revisionalpha.com/api-v2/CmsElearning/certificar`

**Datos requeridos** (JSON):
```json
{
  "id_tipo": 2,
  "id_contacto": 123,
  "id_producto": 78,
  "certificado": 1
}
```

**IMPORTANTE**: Este endpoint ya está implementado y **automáticamente registra la fecha_completo_encuesta** cuando el usuario certifica exitosamente. Solo necesitas verificar que se esté llamando correctamente.

**Cuándo llamarlo**: 
- Ya se está llamando desde el método `certificar()` del controlador `Gestor.php`
- No requiere modificaciones adicionales

---

## 📝 Archivos a Modificar

### Archivo 1: `app/Views/gestor/detalle-curso.php`

**Ubicación del código**: Después de cargar el reproductor Vimeo o al final del archivo, antes del cierre de `</body>`

**Código a agregar**:

```html
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener datos del curso desde PHP
    const idContacto = <?php echo $id_contacto ?? 'null'; ?>;
    const idProducto = <?php echo $curso['id'] ?? 'null'; ?>;
    const idPedido = <?php echo $id_pedido ?? 'null'; ?>;

    // Validar que tenemos todos los datos necesarios
    if (!idContacto || !idProducto || !idPedido) {
        console.error('Faltan datos para registrar ingreso:', {idContacto, idProducto, idPedido});
        return;
    }

    // Registrar ingreso a video
    fetch('https://cms.revisionalpha.com/api-v2/CmsElearning/registrar_ingreso_video', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'CMS-API-KEY': 'e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t'
        },
        body: JSON.stringify({
            id_contacto: idContacto,
            id_producto: idProducto,
            id_pedido: idPedido
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            console.log('✅ Ingreso a video registrado:', data);
        } else {
            console.error('❌ Error al registrar ingreso:', data);
        }
    })
    .catch(error => {
        console.error('❌ Error de red:', error);
    });
});
</script>
```

**Variables PHP necesarias**:
Asegúrate de que el controlador `Gestor.php` pase estas variables a la vista:
- `$id_contacto` - ID del contacto logueado
- `$curso['id']` - ID del producto/curso
- `$id_pedido` - ID del pedido

---

### Archivo 2: `app/Controllers/Gestor.php`

**Método**: `cursosdetalle()`

**Verificar que se pasen estos datos a la vista**:

```php
public function cursosdetalle($cursoid)
{
    // ... código existente ...
    
    $data = [
        'curso' => $curso_info,
        'id_contacto' => session()->get('id_contacto'), // o como obtengas el ID del usuario
        'id_pedido' => $pedido_id, // Debes obtener el ID del pedido del usuario
        // ... otros datos ...
    ];
    
    return view('gestor/detalle-curso', $data);
}
```

---

### Archivo 3: `app/Controllers/Gestor.php`

**Método**: `certificar()`

**Acción**: Solo VERIFICAR que ya está llamando al endpoint `certificar` correctamente.

El método actual debería verse similar a esto:

```php
public function certificar()
{
    // ... validaciones ...
    
    $api_data = [
        'id_tipo' => 2,
        'id_contacto' => $id_contacto,
        'id_producto' => $id_producto,
        'certificado' => 1
    ];
    
    // Llamada a la API
    $response = $this->callAPI('POST', 'certificar', $api_data);
    
    // ... manejo de respuesta ...
}
```

**Si este método ya existe y funciona correctamente, NO necesitas modificarlo**. El endpoint backend ya se encarga de registrar la fecha automáticamente.

---

## 🔍 Variables a Obtener en el Frontend

### `$id_contacto`
- **Qué es**: ID del usuario logueado
- **Cómo obtenerlo**: Desde la sesión (`session()->get('id_contacto')`)

### `$curso['id']` (o `$id_producto`)
- **Qué es**: ID del curso/producto que está viendo
- **Cómo obtenerlo**: Ya debería estar en `$curso['id']` cuando cargas la info del curso

### `$id_pedido`
- **Qué es**: ID del pedido asociado al usuario y curso
- **Cómo obtenerlo**: Necesitas hacer un query a la tabla `con_rel_pedido_contactos`:
  ```php
  $query = $this->db->query("
      SELECT id_pedido 
      FROM con_rel_pedido_contactos 
      WHERE id_contacto = ? AND id_producto = ?
      LIMIT 1
  ", [$id_contacto, $id_producto]);
  
  $id_pedido = $query->row()->id_pedido ?? null;
  ```

---

## ✅ Checklist de Implementación

1. [ ] Modificar `app/Views/gestor/detalle-curso.php` - Agregar script de tracking
2. [ ] Verificar `app/Controllers/Gestor.php` método `cursosdetalle()` - Pasar variables necesarias
3. [ ] Obtener `$id_pedido` desde base de datos si no está disponible
4. [ ] Verificar `app/Controllers/Gestor.php` método `certificar()` - Confirmar que llama a API
5. [ ] Probar en navegador con DevTools abierto (Network tab)
6. [ ] Verificar en base de datos que se guarden las fechas

---

## 🧪 Testing

### Test 1: Ingreso a Video
1. Abrir DevTools > Network
2. Entrar a la página de un curso
3. Buscar llamada POST a `registrar_ingreso_video`
4. Verificar que responde `status: true`
5. Ir a la base de datos y verificar que `fecha_ingreso_video` se llenó

### Test 2: Completar Encuesta
1. Completar una encuesta y certificar
2. Verificar llamada POST a `certificar` en DevTools
3. Verificar que responde con certificado generado
4. Ir a la base de datos y verificar que `fecha_completo_encuesta` se llenó

### Test 3: Vista Admin
1. Ir a: https://cms.revisionalpha.com/cms-v2/elearning/pedidos
2. Entrar al detalle de un pedido
3. Hacer clic en "Ver Seguimiento"
4. Verificar que se muestren las fechas correctamente

---

## 🐛 Troubleshooting

### Error: "API key No valida"
- Verificar que el header sea `CMS-API-KEY` (no `X-API-KEY`)
- Verificar que el key sea: `e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t`

### Error: Variables undefined en JavaScript
- Verificar que el controlador esté pasando las variables a la vista
- Usar `<?php echo $variable ?? 'null'; ?>` para evitar errores PHP

### No se registran las fechas
- Abrir DevTools > Console para ver errores JavaScript
- Verificar Network tab para ver si la llamada se hizo
- Revisar que los IDs sean correctos (no null, no undefined)

### La fecha se registra múltiples veces
- El endpoint `registrar_ingreso_video` solo registra la PRIMERA vez
- Las siguientes llamadas no sobrescriben la fecha

---

## 📊 Base de Datos - Tabla Modificada

**Tabla**: `con_rel_pedido_contactos`

**Columnas agregadas**:
- `fecha_ingreso_video` (DATETIME NULL) - Se llena al entrar a la página del video
- `fecha_completo_encuesta` (DATETIME NULL) - Se llena al certificar exitosamente

**Query para verificar**:
```sql
SELECT 
    id,
    id_pedido,
    id_contacto,
    id_producto,
    nombre,
    apellido,
    fecha_ingreso_video,
    fecha_completo_encuesta,
    certificado
FROM con_rel_pedido_contactos
WHERE id_contacto = [TU_ID_CONTACTO]
ORDER BY id DESC
LIMIT 10;
```

---

## 📞 Soporte

Si tienes dudas sobre:
- Cómo obtener las variables en CodeIgniter 4
- Problemas con CORS
- Errores de API

Consulta el archivo `TESTING_TRACKING.md` en el proyecto backend para más detalles técnicos.
