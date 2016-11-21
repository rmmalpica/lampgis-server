create database GIS;
CREATE USER 'lampgis'@'localhost' IDENTIFIED BY 'lampgis';
GRANT ALL PRIVILEGES ON GIS.* TO 'lampgis'@'localhost';

drop table raster_layer;

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
  `band_count` TINYINT NOT NULL,
  `cell_size_x` FLOAT NOT NULL,
  `cell_size_y` FLOAT NOT NULL,  
  `stat_max` FLOAT NULL,  
  `stat_min` FLOAT NULL,  
  `stat_mean` FLOAT NULL,  
  `stat_std_dev` FLOAT NULL,  
  `extent` POLYGON NOT NULL,  
  `no_data_value` VARCHAR(20),
  PRIMARY KEY (`raster_layer_id`))
ENGINE = MyISAM;

ALTER TABLE RASTER_LAYER ADD SPATIAL INDEX(extent);

drop table raster_layer_block;

CREATE TABLE IF NOT EXISTS `RASTER_LAYER_BLOCK` (
  `raster_layer_block_id` MEDIUMINT NOT NULL AUTO_INCREMENT,  
  `xblock` MEDIUMINT NOT NULL,
  `yblock` MEDIUMINT NOT NULL,
  `xmin` FLOAT NOT NULL,
  `ymin` FLOAT NOT NULL,
  `xmax` FLOAT NOT NULL,
  `ymax` FLOAT NOT NULL,
  `width` MEDIUMINT NOT NULL,
  `height` MEDIUMINT NOT NULL,
  `extent` POLYGON NOT NULL,
  `raster_layer_id` MEDIUMINT NOT NULL,  
  PRIMARY KEY (`raster_layer_block_id`),
  INDEX RASTER_LAYER_BLOCK_xBlock_index (xBlock ASC),
  INDEX RASTER_LAYER_BLOCK_yBlock_index (yBlock ASC),
  INDEX RASTER_LAYER_BLOCK_raster_layer_id (raster_layer_id),
  FOREIGN KEY (raster_layer_id) REFERENCES RASTER_LAYER(raster_layer_id)
  ON DELETE CASCADE)
ENGINE = MyISAM;

ALTER TABLE RASTER_LAYER_BLOCK ADD SPATIAL INDEX(extent);