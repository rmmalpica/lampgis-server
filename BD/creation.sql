create database GIS;
CREATE USER 'lampgis'@'localhost' IDENTIFIED BY 'lampgis';
GRANT ALL PRIVILEGES ON GIS.* TO 'lampgis'@'localhost';

CREATE TABLE IF NOT EXISTS `RASTER_LAYER` (
  `raster_layer_id` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `metadata` MEDIUMTEXT NULL,
  `xmin` FLOAT NOT NULL,
  `ymin` FLOAT NOT NULL,
  `xmax` FLOAT NOT NULL,
  `ymax` FLOAT NOT NULL,
  `width` MEDIUMINT NOT NULL,
  `height` MEDIUMINT NOT NULL,
  `band_count` TINYINT NULL,
  `cell_size_x` FLOAT NULL,
  `cell_size_y` FLOAT NULL,  
  `no_data_value` VARCHAR(20),
  PRIMARY KEY (`raster_layer_id`))
ENGINE = InnoDB;

