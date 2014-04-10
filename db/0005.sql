-- #timestamp <20140325233900>

CREATE  TABLE IF NOT EXISTS `tblRide` (
	`id`			INT				NOT NULL AUTO_INCREMENT,
	`note`			TEXT 			NOT NULL,
	`notAssignedTime`	INT(11)		DEFAULT NULL,
	`ongoingTime`	INT(11)			DEFAULT NULL,
	`status`		TINYINT			DEFAULT 0,
	`passengerId`	INT(11)			DEFAULT NULL,
	`taxiId`		INT(11)			DEFAULT NULL,
	`created`		DATETIME		NOT NULL,
	`changed`		DATETIME		DEFAULT NULL,
	`createdBy`		INT(11)			DEFAULT NULL,
	`changedBy`		INT(11)			DEFAULT NULL,
	`state`			TINYINT(1)		NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblRide`
		PRIMARY KEY (`id`),

	KEY `i_tblRide_id` (`id`),
	INDEX `i_tblRide_state_id` (`state`, `id`),
	INDEX `i_tblRide_taxiId` (`taxiId`),
	INDEX `i_tblRide_passengerId` (`passengerId`),

	CONSTRAINT `fk_tblRide_taxiId`
	FOREIGN KEY (`taxiId`)
	REFERENCES `tblTaxi` (`id`)
	ON DELETE NO ACTION
	ON UPDATE CASCADE,
	CONSTRAINT `fk_tblRide_passengerId`
	FOREIGN KEY (`passengerId`)
	REFERENCES `tblPassenger` (`id`)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
) ENGINE = INNODB;