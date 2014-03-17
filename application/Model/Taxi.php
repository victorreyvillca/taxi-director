<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\TaxiRepository")
 * @Table(name="Taxi")
 */
class Taxi extends DomainObject {

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @Column(type="string")
     * @var string
     */
    private $mark;

    /**
     * @Column(type="string")
     * @var string
     */
    private $type;

    /**
     * @Column(type="integer")
     * @var int
     */
    private $model;

    /**
     * @Column(type="string")
     * @var string
     */
    private $color;

    /**
     * @Column(type="string")
     * @var string
     */
    private $plaque;

    /**
     * @Column(type="integer")
     * @var bool
     */
    private $status;

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Taxi
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMark() {
		return $this->mark;
	}

	/**
	 * @param string $mark
	 * @return Taxi
	 */
	public function setMark($mark) {
		$this->mark = $mark;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return Taxi
	 */
	public function setType($type) {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param int $model
	 * @return Taxi
	 */
	public function setModel($model) {
		$this->model = $model;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param string $color
	 * @return Taxi
	 */
	public function setColor($color) {
		$this->color = $color;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPlaque() {
		return $this->plaque;
	}

	/**
	 * @param string $plaque
	 * @return Taxi
	 */
	public function setPlaque($plaque) {
		$this->plaque = $plaque;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param boolean $status
	 * @return Taxi
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
}