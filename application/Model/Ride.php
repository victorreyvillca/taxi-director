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
 * @Entity(repositoryClass="Model\Repositories\RideRepository")
 * @Table(name="Ride")
 */
class Ride extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $note;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $notAssignedTime;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $ongoingTime;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $status;

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
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param string $note
	 * @return Ride
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
	 * @return Ride
	 */
	public function setTaxi($taxi) {
		$this->taxi = $taxi;
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
	 * @return Ride
	 */
	public function setPassenger($passenger) {
		$this->passenger = $passenger;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNotAssignedTime() {
		return $this->notAssignedTime;
	}

	/**
	 * @param int $notAssignedTime
	 * @return Ride
	 */
	public function setNotAssignedTime($notAssignedTime) {
		$this->notAssignedTime = $notAssignedTime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOngoingTime() {
		return $this->ongoingTime;
	}

	/**
	 * @param int $ongoingTime
	 * @return Ride
	 */
	public function setOngoingTime($ongoingTime) {
		$this->ongoingTime = $ongoingTime;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return Ride
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
}