-- #timestamp <20140401140800>

ALTER TABLE `tblRide`
	ADD COLUMN `labelId`		INT(11)		DEFAULT NULL,

	ADD INDEX `i_tblRide_labelId` (`labelId`),
	ADD CONSTRAINT `fk_tblRide_labelId`
		FOREIGN KEY (`labelId`)
		REFERENCES `tblLabel` (`id`)
		ON DELETE RESTRICT
		ON UPDATE CASCADE;