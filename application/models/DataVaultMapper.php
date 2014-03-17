<?php
/**
 * DataMapper for Dist 3.
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_Model_DataVaultMapper {

	/**
	 * @var Zend_Db_Table_Abstract
	 */
	protected $_dbTable;

	/**
	 * Creates a new object Zend_Db_Table_Abstract
	 * @param string $dbTable
	 */
	public function setDbTable($dbTable) {
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	/**
	 * Returns the class abstract
	 * @return Zend_Db_Table_Abstract
	 */
	public function getDbTable() {
		if (null === $this->_dbTable) {
			$this->setDbTable('Dis_Model_DbTable_DataVault');
		}
		return $this->_dbTable;
	}

	/**
	 * Saves model
	 * @param Model_DataVault $dataVault
	 */
	public function save(Dis_Model_DataVault $dataVault) {

		$data = array(
			'filename' => $dataVault->getFilename(),
			'mimeType' => $dataVault->getMimeType(),
			'binary'   => $dataVault->getBinary(),
			'created'  => date('Y-m-d H:i:s'),
			'state'    => TRUE
		);

		unset($data['id']);
		$this->getDbTable()->insert($data);

		$id = (int)$this->getDbTable()->getAdapter()->lastInsertId();
		$dataVault->setId($id);
	}

	/**
	 * Updates model
	 * @param int $id
	 * @param Model_DataVault $dataVault
	 */
	public function update($id, Dis_Model_DataVault $dataVault) {
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}

		$data = array(
			'filename' => $dataVault->getFilename(),
			'mimeType' => $dataVault->getMimeType(),
			'binary'   => $dataVault->getBinary(),
			'created'  => date('Y-m-d H:i:s'),
//             'changed'  => date('Y-m-d H:i:s'),
		);

		$this->getDbTable()->update($data, array('id = ?' => $id));
	}

	/**
	 * Deletes model
	 * @param int $id
	 */
	public function delete($id) {
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}

		$data = array(
			'changed' => date('Y-m-d H:i:s'),
			'state' => FALSE,
		);

		$this->getDbTable()->update($data, array('id = ?' => $id));
	}

	/**
	 * Finds model with and without field binary
	 * @param int $id
	 * @param int $isBinary
	 * @param Model_DataVault $dataVault
	 */
	public function find($id, $isBinary = TRUE) {
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return NULL;
		}

		$row = $result->current();

		$dataVault = new Dis_Model_DataVault();
		$dataVault
			->setFilename($row->filename)
			->setMimeType($row->mimeType)
			->setExpires($row->expires)
			->setCreated($row->created)
			->setId($row->id);

		if ($isBinary) {
			$dataVault->setBinary($row->binary);
		}

		return $dataVault;
	}
}