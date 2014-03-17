<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\PassengerRepository")
 * @Table(name="Passenger")
 */
class Passenger extends Person {

    /**
     * @Column(type="string")
     * @var string
     */
    private $address;

    /**
     * @Column(type="string")
     * @var string
     */
    private $description;

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return Passenger
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Passenger
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
}