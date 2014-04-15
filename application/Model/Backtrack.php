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
 * @Entity(repositoryClass="Model\Repositories\BacktrackRepository")
 * @Table(name="Backtrack")
 */
class Backtrack extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
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
	 * @Column(type="string")
	 * @var string
	 */
	private $latitud;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $longitud;

	/**
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $timenow;

	/**
	 * @return Taxi
	 */
	public function getTaxi() {
		return $this->taxi;
	}

	/**
	 * @param Taxi $taxi
	 * @return Backtrack
	 */
	public function setTaxi($taxi) {
		$this->taxi = $taxi;
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

	/**
	 * @return string
	 */
	public function getLatitud() {
		return $this->latitud;
	}

	/**
	 * @param string $latitud
	 * @return Backtrack
	 */
	public function setLatitud($latitud) {
		$this->latitud = $latitud;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLongitud() {
		return $this->longitud;
	}

	/**
	 * @param string $longitud
	 * @return Backtrack
	 */
	public function setLongitud($longitud) {
		$this->longitud = $longitud;
		return $this;
	}

	/**
	 * @return datetime
	 */
	public function getTimenow() {
		return $this->timenow;
	}

	/**
	 * @param datetime $timenow
	 * @return Backtrack
	 */
	public function setTimenow($timenow) {
		$this->timenow = $timenow;
		return $this;
	}
}