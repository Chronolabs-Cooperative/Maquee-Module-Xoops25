
CREATE TABLE `maquee_gallery` (
  `gid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned DEFAULT '0',
  `weight` int(6) DEFAULT '1',
  `language` varchar(64) DEFAULT 'english',
  `description` varchar(500) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `width` int(10) unsigned DEFAULT '0',
  `height` int(10) unsigned DEFAULT '0',
  `extension` varchar(5) DEFAULT 'jpg',
  `url` varchar(500) DEFAULT 'http://',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `maquee_maquees` (
  `mid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` ENUM('_MI_MAQUEE_TYPE_GALLERY','_MI_MAQUEE_TYPE_TICKER') DEFAULT '_MI_MAQUEE_TYPE_GALLERY',
  `name` VARCHAR(255) DEFAULT NULL,
  `description` VARCHAR(500) DEFAULT NULL,
  `reference` VARCHAR(64) DEFAULT 'maquee_%id%',
  `animation` ENUM('_MI_MAQUEE_ANIMATION_SLIDEUP','_MI_MAQUEE_ANIMATION_FADEIN','_MI_MAQUEE_ANIMATION_SLIDEDOWN','_MI_MAQUEE_ANIMATION_FADEOUT','_MI_MAQUEE_ANIMATION_RANDOM','_MI_MAQUEE_ANIMATION_DEFAULT') DEFAULT '_MI_MAQUEE_ANIMATION_DEFAULT',
  `sequence` ENUM('_MI_MAQUEE_SEQUENCE_SEQUENCE','_MI_MAQUEE_SEQUENCE_RANDOM','_MI_MAQUEE_SEQUENCE_RANDOM_START','_MI_MAQUEE_SEQUENCE_DEFAULT') DEFAULT '_MI_MAQUEE_SEQUENCE_DEFAULT',
  `speed` INT(10) DEFAULT '2200',
  `timeout` INT(10) DEFAULT '7500',
  `width` INT(10) DEFAULT '960',
  `height` INT(10) DEFAULT '450',
  `bgcolour` VARCHAR(7) DEFAULT '#ffffff',
  `default` INT(1) UNSIGNED DEFAULT '0',
  `created` INT(13) UNSIGNED DEFAULT '0',
  `updated` INT(13) UNSIGNED DEFAULT '0',
  `actioned` INT(13) UNSIGNED DEFAULT '0',
  PRIMARY KEY (`mid`),
  KEY `COMMON` (`type`,`name`(25),`reference`(25),`animation`,`sequence`)
) ENGINE=INNODB DEFAULT CHARSET=utf8;


CREATE TABLE `maquee_text` (
  `tid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned DEFAULT '0',
  `weight` int(6) DEFAULT '1',
  `language` varchar(64) DEFAULT 'english',
  `html` mediumtext,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(500) DEFAULT 'http://',
  `created` int(13) unsigned DEFAULT '0',
  `updated` int(13) unsigned DEFAULT '0',  
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

