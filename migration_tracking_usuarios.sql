-- Migration: Agregar columnas de tracking de actividad de usuarios en cursos
-- Fecha: 2026-01-19
-- Descripción: Registra fecha de ingreso a página de video y fecha de completado de encuesta

ALTER TABLE con_rel_pedido_contactos 
ADD COLUMN fecha_ingreso_video DATETIME NULL COMMENT 'Fecha y hora cuando entró a la página del video',
ADD COLUMN fecha_completo_encuesta DATETIME NULL COMMENT 'Fecha y hora cuando completó la encuesta exitosamente';

-- Verificar las columnas agregadas
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_COMMENT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'con_rel_pedido_contactos' 
-- AND COLUMN_NAME IN ('fecha_ingreso_video', 'fecha_completo_encuesta');
