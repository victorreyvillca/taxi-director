<?php
/**
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Emini A/S
 * @license Proprietary
 */

class Dis_Model_DataVault extends Dis_Model_Entity {

	/**
	 * @var string
	 */
	protected $_filename;

	/**
	 * @var string
	 */
	protected $_mimeType;

	/**
	 * @var date
	 */
	protected $_expires;

	/**
	 * @var string
	 */
	protected $_binary;

 	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

	/**
	 * @return string
	 */
	public function getFilename() {
		return $this->_filename;
	}

	/**
	 * @param string $filename
	 * @return Dis_Model_DataVault
	 */
	public function setFilename($filename) {
		$this->_filename = $filename;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->_mimeType;
	}

	/**
	 * @param string $mimeType
	 * @return Dis_Model_DataVault
	 */
	public function setMimeType($mimeType) {
		$this->_mimeType = $mimeType;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getExpires() {
		return $this->_expires;
	}

	/**
	 * @param date $expires
	 * @return Dis_Model_DataVault
	 */
	public function setExpires($expires) {
		$this->_expires = $expires;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBinary() {
		return $this->_binary;
	}

	/**
	 * @param string $binary
	 * @return Dis_Model_DataVault
	 */
	public function setBinary($binary) {
		$this->_binary = $binary;
		return $this;
	}
}