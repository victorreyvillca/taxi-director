-- #timestamp <20140415051800>

CREATE  TABLE IF NOT EXISTS `tblBacktrack` (
	`id`			INT  		NOT NULL AUTO_INCREMENT,
	`taxiId`			INT  		NOT NULL,
	`latitud`			VARCHAR(50)	NOT NULL,
	`longitud`			VARCHAR(50)	NOT NULL,
	`status`			VARCHAR(10)	NOT NULL,
	`timenow`			DATETIME 	DEFAULT NULL,
	`created` 		DATETIME 	NOT NULL,
	`changed` 		DATETIME 	DEFAULT NULL,
	`createdBy` 	INT(11) 		DEFAULT NULL,
	`changedBy` 	INT(11) 		DEFAULT NULL,
	`state` 		TINYINT(1) 	NOT NULL DEFAULT '1',
	CONSTRAINT `pk_tblBacktrack`
		PRIMARY KEY (`id`),

	KEY `i_tblBacktrack_id` (`id`),
	INDEX `i_tblBacktrack_state_id` (`state`, `id`),
	INDEX `i_tblBacktrack_taxiId` (`taxiId`),

	CONSTRAINT `fk_tblBacktrack_taxiId`
	FOREIGN KEY (`taxiId`)
	REFERENCES `tblTaxi` (`id`)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
) ENGINE = INNODB;