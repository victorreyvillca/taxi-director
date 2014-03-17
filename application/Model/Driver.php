<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\DriverRepository")
 * @Table(name="Driver")
 */
class Driver extends Person {

    /**
     * @Column(type="string")
     * @var string
     */
    private $address;

    /**
     * @Column(type="string")
     * @var string
     */
    private $note;

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return Driver
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param string $note
	 * @return Driver
	 */
	public function setNote($note) {
		$this->note = $note;
		return $this;
	}
}