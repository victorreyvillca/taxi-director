-- #timestamp <20140316235500>

CREATE  TABLE IF NOT EXISTS `tblRole` (
	`id`			INT				NOT NULL AUTO_INCREMENT,
	`name`			VARCHAR(50)		NOT NULL,
	`description`	TEXT			NULL,
	`created` 		DATETIME 		NOT NULL,
	`changed` 		DATETIME 		DEFAULT NULL,
	`createdBy` 	INT(11) 		DEFAULT NULL,
	`changedBy` 	INT(11) 		DEFAULT NULL,
	`state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblRole`
		PRIMARY KEY (`id`),

	KEY `i_tblRole_id` (`id`),
	INDEX `i_tblRole_state_id` (`state`, `id`))
ENGINE = INNODB;


CREATE  TABLE IF NOT EXISTS `tblResource` (
	`id`			INT				NOT NULL AUTO_INCREMENT,
	`title`			VARCHAR(50)		NOT NULL,
	`description`	TEXT			NULL,
	`created`		DATETIME		NOT NULL,
	`changed`		DATETIME		DEFAULT NULL,
	`createdBy`		INT(11)			DEFAULT NULL,
	`changedBy`		INT(11)			DEFAULT NULL,
	`state`			TINYINT(1)		NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblResource`
		PRIMARY KEY (`id`),

	KEY `i_tblResource_id` (`id`),
	INDEX `i_tblResource_state_id` (`state`, `id`))
ENGINE = INNODB;


CREATE  TABLE IF NOT EXISTS `tblRole_Resource` (
	`roleId` 		INT NOT NULL,
	`resourceId`	INT NOT NULL,
	PRIMARY KEY (`roleId`, `resourceId`),
	INDEX `fk_tblRole_Resource_roleId` (`roleId`),
	INDEX `fk_tblRole_Resource_resourceId` (`resourceId`),
	CONSTRAINT `fk_tblRole_Resource_roleId`
	FOREIGN KEY (`roleId` )
	REFERENCES `tblRole` (`id` )
	ON DELETE NO ACTION
	ON UPDATE CASCADE,
	CONSTRAINT `fk_tblRole_Resource_resourceId`
	FOREIGN KEY (`resourceId` )
	REFERENCES `tblResource` (`id` )
	ON DELETE NO ACTION
	ON UPDATE CASCADE
	)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `tblDataVault` (
	`id`		INT(11) NOT NULL AUTO_INCREMENT,
	`expires`	DATETIME DEFAULT NULL,
	`filename`	VARCHAR(150) NOT NULL,
	`mimeType`	VARCHAR(45) DEFAULT NULL,
	`binary`	LONGBLOB,
	`created`	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`state`		TINYINT(1) NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblDataVault` PRIMARY KEY (`id`),
	KEY `i_tblDataVault_id` (`id`),
	INDEX `i_tblDataVault_state_id` (`state`,`id`)
) ENGINE=INNODB;


CREATE TABLE `tblPerson` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identityCard` int(11) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `dateOfBirth` datetime DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `phonework` varchar(45) DEFAULT NULL,
  `phonemobil` int(11) DEFAULT NULL,
  `sex` tinyint(4) NOT NULL,
  `type` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  `changed` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `changedBy` int(11) DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `profilePictureId` int(11) DEFAULT NULL,
  CONSTRAINT `pk_tblPerson`
  PRIMARY KEY (`id`),
  KEY `i_tblPerson_id` (`id`),
  KEY `i_tblPerson_state_id` (`state`,`id`),
  KEY `i_tblPerson_profilePictureId` (`profilePictureId`),
  CONSTRAINT `fk_tblPerson_profilePictureId` FOREIGN KEY (`profilePictureId`) REFERENCES `tblDataVault` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS `tblAccount` (
	`id`			INT(11) 		NOT NULL AUTO_INCREMENT,
	`username`		VARCHAR(45) 	NOT NULL,
	`password`		VARCHAR(128)	NOT NULL,
	`email`			VARCHAR(65)		DEFAULT NULL,
	`role`			VARCHAR(45)		DEFAULT NULL,
	`accountType`	INT(11) 		DEFAULT NULL,
	`created` 		DATETIME 		NOT NULL,
	`changed` 		DATETIME		DEFAULT NULL,
	`createdBy`		INT(11) 		DEFAULT NULL,
	`changedBy`		INT(11)			DEFAULT NULL,
	`state`			TINYINT(1) 		NOT NULL DEFAULT '1',
	
	CONSTRAINT `pk_tblAccount` PRIMARY KEY (`id`),
	KEY `i_tblAccount_id` (`id`),
	INDEX `i_tblAccount_state_id` (`state`,`id`)
) ENGINE=INNODB;


CREATE  TABLE IF NOT EXISTS `tblOperator` (
	`id` 			INT(11) 	NOT NULL AUTO_INCREMENT,
	`visible`		TINYINT(1)	NOT NULL DEFAULT '0',
	`accountId` 	INT(11)		DEFAULT NULL,
	`roleId`		INT(11)		DEFAULT NULL,

	CONSTRAINT `pk_tblOperator`
		PRIMARY KEY (`id`),

	KEY `i_tblOperator_id` (`id`),
	INDEX `i_tblOperator_accountId` (`accountId`),
	INDEX `i_tblOperator_roleId` (`roleId`),

	CONSTRAINT `fk_tblOperator_accountId`
	FOREIGN KEY (`accountId`)
	REFERENCES `tblAccount` (`id`)
	ON UPDATE CASCADE,
	CONSTRAINT `fk_tblOperator_roleId`
	FOREIGN KEY (`roleId`)
	REFERENCES `tblRole` (`id`)
	ON UPDATE CASCADE
) ENGINE = INNODB;


CREATE  TABLE IF NOT EXISTS `tblPassenger` (
	`id` 			INT(11) 	NOT NULL AUTO_INCREMENT,
	`address`		TEXT 		NULL,
	`description`	TEXT		NULL,

	CONSTRAINT `pk_tblPassenger`
		PRIMARY KEY (`id`),

	KEY `i_tblPassenger_id` (`id`)
) ENGINE = INNODB;


CREATE TABLE `tblTaxi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `mark` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `model` int(11) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `plaque` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `pictureId` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `changed` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `changedBy` int(11) DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',

  CONSTRAINT `pk_tblTaxi`
  PRIMARY KEY (`id`),
  KEY `i_tblTaxi_id` (`id`),
  KEY `i_tblTaxi_state_id` (`state`,`id`),
  KEY `i_tblTaxi_pictureId` (`pictureId`),
  CONSTRAINT `fk_tblTaxi_pictureId` FOREIGN KEY (`pictureId`) REFERENCES `tblDataVault` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE  TABLE IF NOT EXISTS `tblDriver` (
	`id` 		INT(11) 	NOT NULL AUTO_INCREMENT,
	`address`	TEXT 		NULL,
	`note`		TEXT		NULL,
	`taxiId`	INT(11)		DEFAULT NULL,

	CONSTRAINT `pk_tblDriver`
		PRIMARY KEY (`id`),

	KEY `i_tblDriver_id` (`id`),
	INDEX `i_tblDriver_taxiId` (`taxiId`),
	
	CONSTRAINT `fk_tblDriver_taxiId`
	FOREIGN KEY (`taxiId`)
	REFERENCES `tblTaxi` (`id`)
	ON DELETE RESTRICT
	ON UPDATE CASCADE
) ENGINE = INNODB;