-- every 28 days the table User will reset cantidad_reportes to 0
CREATE EVENT IF NOT EXISTS reset_cantidad_reportes
ON SCHEDULE EVERY 28 DAY
DO
UPDATE User
SET cantidad_reportes = 0;
