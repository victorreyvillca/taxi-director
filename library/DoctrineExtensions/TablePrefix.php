<?php
namespace DoctrineExtensions;
use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class TablePrefix {
	protected $_prefix = '';

	public function __construct($prefix) {
		$this->_prefix = (string) $prefix;
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {
		$classMetadata = $eventArgs->getClassMetadata();
		if(substr($classMetadata->getTableName(), 0,strlen($this->_prefix))!=$this->_prefix)
			$classMetadata->setTableName($this->_prefix . $classMetadata->getTableName());

		foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping) {
			if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY) {
				$mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
				$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->_prefix . $mappedTableName;
			}
		}
	}
}