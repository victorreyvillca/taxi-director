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
     * Id of the Taxi this model is associated with.
     * @Column(type="integer")
     * @var int
     */
    private $taxiId;

    /**
     * Taxi this model is associated with.
     * @ManyToOne(targetEntity="Taxi")
     * @JoinColumn(name="taxiId", referencedColumnName="id")
     * @var Taxi
     */
    private $taxi;

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

	/**
	 * @return Taxi
	 */
	public function getTaxi() {
		return $this->taxi;
	}

	/**
	 * @param Taxi $taxi
	 * @return Driver
	 */
	public function setTaxi($taxi) {
		$this->taxi = $taxi;
		return $this;
	}
}