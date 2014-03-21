-- #timestamp <20140321045500>

CREATE  TABLE IF NOT EXISTS `tblLabel` (
	`id`			INT				NOT NULL AUTO_INCREMENT,
	`name`			VARCHAR(50)		NOT NULL,
	`description`	TEXT			NULL,
	`created` 		DATETIME 		NOT NULL,
	`changed` 		DATETIME 		DEFAULT NULL,
	`createdBy` 	INT(11) 		DEFAULT NULL,
	`changedBy` 	INT(11) 		DEFAULT NULL,
	`state` 		TINYINT(1) 		NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblLabel`
		PRIMARY KEY (`id`),

	KEY `i_tblLabel_id` (`id`),
	INDEX `i_tblLabel_state_id` (`state`, `id`))
ENGINE = INNODB;

CREATE  TABLE IF NOT EXISTS `tblAddress` (
	`id`			INT				NOT NULL AUTO_INCREMENT,
	`name`			TEXT 			NOT NULL,
	`labelId`		INT(11)			DEFAULT NULL,
	`passengerId`	INT(11)			DEFAULT NULL,
	`created`		DATETIME		NOT NULL,
	`changed`		DATETIME		DEFAULT NULL,
	`createdBy`		INT(11)			DEFAULT NULL,
	`changedBy`		INT(11)			DEFAULT NULL,
	`state`			TINYINT(1)		NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblAddress`
		PRIMARY KEY (`id`),

	KEY `i_tblAddress_id` (`id`),
	INDEX `i_tblAddress_state_id` (`state`, `id`),
	INDEX `i_tblAddress_labelId` (`labelId`),
	INDEX `i_tblAddress_passengerId` (`passengerId`),

	CONSTRAINT `fk_tblAddress_labelId`
	FOREIGN KEY (`labelId`)
	REFERENCES `tblLabel` (`id`)
	ON DELETE NO ACTION
	ON UPDATE CASCADE,
	CONSTRAINT `fk_tblAddress_passengerId`
	FOREIGN KEY (`passengerId`)
	REFERENCES `tblPassenger` (`id`)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
) ENGINE = INNODB;