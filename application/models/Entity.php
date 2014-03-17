<?php
/**
 * Model Entity for Dist 3
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Emini A/S
 * @license Proprietary
 */

class Dis_Model_Entity {

	/**
	 * @var date
	 */
	protected $_created;

	/**
	 * @var date
	 */
	protected $_changed;

	/**
	 * @var boolean
	 */
	protected $_state;

	/**
	 * @var int
	 */
	protected $_id;

	/**
	 * @param array $options
	 */
	public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Method creates the methods set of the attributes
     * @param string $name
     * @param object $value
     */
    public function __set($name, $value) {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid country property');
        }
        $this->$method($value);
    }

    /**
     * Method creates the methods get of the attributes
     * @param string $name
     */
    public function __get($name) {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid Entity property');
        }
        return $this->$method();
    }

    /**
     * This method inserts all methods get and set of the attributes
     * @param array $options
     */
    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

	/**
	 * @return int
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @param int $id
	 * @return Model_Entity
	 */
	public function setId($id) {
		$this->_id = (int)$id;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getCreated() {
		return $this->_created;
	}

	/**
	 * @param date $created
	 * @return Model_Entity
	 */
	public function setCreated($created) {
		$this->_created = $created;
		return $this;
	}

	/**
	 * @return date
	 */
	public function getChanged() {
		return $this->_changed;
	}

	/**
	 * @param date $changed
	 * @return Model_Entity
	 */
	public function setChanged($changed) {
		$this->_changed = $changed;
		return $this;
	}
}