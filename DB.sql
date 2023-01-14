-- Cada 28 dias el contador de reportes se reinicia
CREATE EVENT IF NOT EXISTS reset_cantidad_reportes
ON SCHEDULE EVERY 28 DAY
DO
UPDATE Users
SET cantidad_reportes = 0;
