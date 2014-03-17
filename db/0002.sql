-- #timestamp <20140317235500>

INSERT INTO `tblRole` ( `name`, `description`, `created`, `changed`, `createdBy`, `changedBy`, `state`) VALUES
	('Administrador', 'Encargado de Administrador todo el Sistema', NOW(), NULL, 1, NULL, 1),
	('Operador', 'Encargado de utilizar algunas funcionalidades del sistema', NOW(), NULL, 1, NULL, 1),
	('Invitado', 'No realiza ninguna funcionalidad en el Sistema', NOW(), NULL, 1, NULL, 1);
	
INSERT INTO `tblPerson`(`identityCard`,`firstName`,`lastName`,`dateOfBirth`,`phone`,`phonework`,`phonemobil`,`sex`,`type`,`created`,`changed`,`createdBy`,`changedBy`,`state`,`profilePictureId`) VALUES
	(5938782,'Victor','Villca',NOW(),'',NULL,70016783,1,'operator',NOW(),NULL,NULL,NULL,1,NULL);
INSERT INTO `tblAccount`(`username`,`password`,`email`,`role`,`accountType`,`created`,`changed`,`createdBy`,`changedBy`,`state`) VALUES
	('admin',md5('admin123456'),'victor.villca@gmail.com','Administrador',1,NOW(),NULL,NULL,NULL,1);
INSERT INTO `tblOperator`(`visible`,`accountId`,`roleId`) VALUES
	(1,1,1);