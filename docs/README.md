# Documentación - Sistema de Tracking eLearning

Esta carpeta contiene toda la documentación del sistema de tracking de usuarios en cursos eLearning.

---

## 📚 Índice de Documentación

### 1. [TESTING_TRACKING.md](./TESTING_TRACKING.md)
**Backend - Testing y Verificación**

Documentación completa del backend implementado:
- ✅ Migración de base de datos (nuevas columnas)
- ✅ API Endpoints creados (`registrar_ingreso_video`, `certificar`)
- ✅ Vista admin de seguimiento
- ✅ Tests con cURL
- ✅ Queries SQL para verificación
- ✅ Checklist completo de testing backend

**Usa este archivo para**:
- Probar los endpoints del backend con cURL
- Verificar que la base de datos esté correcta
- Acceder a la vista admin de seguimiento

---

### 2. [INSTRUCCIONES_FRONTEND.md](./INSTRUCCIONES_FRONTEND.md)
**Frontend - Implementación**

Guía completa para implementar el tracking en el frontend (CodeIgniter 4):
- 📝 Código JavaScript listo para copiar/pegar
- 📝 Modificaciones necesarias en `detalle-curso.php`
- 📝 Variables que debe pasar el controlador `Gestor.php`
- 📝 Cómo obtener `id_pedido` desde la base de datos
- 📝 Testing en navegador con DevTools
- 📝 Troubleshooting de errores comunes

**Usa este archivo para**:
- Implementar las llamadas a la API desde el frontend
- Copiar el código JavaScript necesario
- Resolver problemas de implementación

---

## 🎯 Quick Start

### Backend (Ya completado ✅)
1. Migración ejecutada: Columnas `fecha_ingreso_video` y `fecha_completo_encuesta` agregadas
2. API funcionando en: `https://cms.revisionalpha.com/api-v2/CmsElearning/`
3. Vista admin disponible: `/cms-v2/elearning/pedidos/seguimiento/{id}`

### Frontend (Pendiente ⏳)
1. Leer `INSTRUCCIONES_FRONTEND.md`
2. Copiar el script JavaScript a `detalle-curso.php`
3. Modificar controlador `Gestor.php` para pasar variables necesarias
4. Probar con DevTools abierto

---

## 🔑 Datos Importantes

### API Key
```
CMS-API-KEY: e7H3k9nq4j3k88ws8gs8ey6ujtgy7b45nw81nt5t
```

### Endpoints
- **Registrar ingreso**: `POST /api-v2/CmsElearning/registrar_ingreso_video`
- **Certificar**: `POST /api-v2/CmsElearning/certificar`

### Base de Datos
- **Tabla**: `con_rel_pedido_contactos`
- **Columnas nuevas**: 
  - `fecha_ingreso_video` (DATETIME)
  - `fecha_completo_encuesta` (DATETIME)

---

## 📊 Flujo del Sistema

```
Usuario entra al curso
    ↓
JavaScript llama a registrar_ingreso_video
    ↓
Se guarda fecha_ingreso_video en BD
    ↓
Usuario completa encuesta
    ↓
Sistema llama a certificar
    ↓
Se guarda fecha_completo_encuesta en BD
    ↓
Admin puede ver seguimiento en CMS
```

---

## 🛠️ Archivos del Proyecto

### Backend (cms7)
```
application/
├── controllers/
│   ├── api-v2/CmsElearning.php          (API endpoints)
│   └── cms-v2/elearning/Pedidos.php     (Vista seguimiento)
├── models/
│   └── cms-v2/elearning/Pedidos_model.php  (Métodos tracking)
└── views/
    └── cms-v2/lizama/elearning/pedidos/
        ├── detalle.php                   (Botón "Ver Seguimiento")
        └── seguimiento.php               (Tabla de tracking)

migration_tracking_usuarios.sql          (Script migración)
```

### Frontend (academializama) - A modificar
```
app/
├── Controllers/
│   └── Gestor.php                       (Pasar variables a vista)
└── Views/
    └── gestor/
        └── detalle-curso.php            (Agregar JS tracking)
```

---

## 📞 Soporte

Para dudas o problemas:
1. Revisar sección de **Troubleshooting** en `INSTRUCCIONES_FRONTEND.md`
2. Verificar logs de API en servidor
3. Revisar DevTools > Console y Network en navegador

---

## 📅 Última Actualización

**Fecha**: 2026-01-19  
**Estado**: Backend completado ✅ | Frontend pendiente ⏳
