-- #timestamp <20140324181100>

INSERT INTO `tblLabel` ( `name`, `description`, `created`, `changed`, `createdBy`, `changedBy`, `state`) VALUES
	('Casa', 'La Etiqueta corresponde a la Casa del Pasajero', NOW(), NULL, 1, NULL, 1),
	('Trabajo', 'La Etiqueta corresponde a la Casa del Pasajero', NOW(), NULL, 1, NULL, 1),
	('Gimnasio', 'La Etiqueta corresponde al Gimnasio del Pasajero', NOW(), NULL, 1, NULL, 1),
	('Tienda Comercial', 'La Etiqueta corresponde a la Tienda Comercial del Pasajero', NOW(), NULL, 1, NULL, 1);
	
INSERT INTO `tblPerson`(`identityCard`,`firstName`,`lastName`,`dateOfBirth`,`phone`,`phonework`,`phonemobil`,`sex`,`type`,`created`,`changed`,`createdBy`,`changedBy`,`state`,`profilePictureId`) VALUES
	(59387823,'Adan','Condori',NOW(),'700167834',NULL,700167834,1,'passenger',NOW(),NULL,NULL,NULL,1,NULL);
	
INSERT INTO `tblPassenger` ( `id`, `address`, `description`) VALUES
	(2, 'Direccion del Pasajero Victor', 'Urbanizacion Los Penocos');
	
INSERT INTO `tblAddress`(`name`, `labelId`, `passengerId`, `created`, `changed`, `createdBy`, `changedBy`, `state`) VALUES
	('Urbanizacion los Penocos', 1, 2, NOW(), NULL, 1, NULL, 1),
	('Roca y Coronado', 2, 2, NOW(), NULL, 1, NULL, 1),
	('Master Gym', 3, 2, NOW(), NULL, 1, NULL, 1);