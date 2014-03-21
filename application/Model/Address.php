<?php
/**
 * Model Doctrine 2 for Taxi
 *
 * @category Dist
 * @package Model
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Emini A/S
 * @license Proprietary
 */

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\AddressRepository")
 * @Table(name="Address")
 */
class Address extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * Id of the Label this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $labelId;

	/**
	 * Label this model is associated with.
	 * @ManyToOne(targetEntity="Label")
	 * @JoinColumn(name="labelId", referencedColumnName="id")
	 * @var Label
	 */
	private $label;

	/**
	 * Id of the Passenger this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $passengerId;

	/**
	 * Passenger this model is associated with.
	 * @ManyToOne(targetEntity="Passenger")
	 * @JoinColumn(name="passengerId", referencedColumnName="id")
	 * @var Passenger
	 */
	private $passenger;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Address
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return Label
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param Label $label
	 * @return Address
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}

	/**
	 * @return Passenger
	 */
	public function getPassenger() {
		return $this->passenger;
	}

	/**
	 * @param Passenger $passenger
	 * @return Address
	 */
	public function setPassenger($passenger) {
		$this->passenger = $passenger;
		return $this;
	}
}