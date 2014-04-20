<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\TaxiRepository")
 * @Table(name="Taxi")
 */
class Taxi extends DomainObject {

    const WITHOUT_CAREER = 0;
    const ONGOING = 1;
    const OFF = 2;

    /**
     * @Column(type="string")
     * @var string
     */
    private $name;

    /**
     * @Column(type="integer")
     * @var int
     */
    private $number;

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
     * @var int
     */
    private $status;

    /**
     * @Column(type="integer")
     * @var boolean
     */
    private $active;

    /**
     * @Column(type="integer")
     * @var boolean
     */
    private $activeimage;

    /**
     * @Column(type="string")
     * @var string
     */
    private $phone;

    /**
     * @Column(type="string")
     * @var string
     */
    private $codeactivation;

    /**
     * @Column(type="string")
     * @var string
     */
    private $codeuser;

    /**
     * @Column(type="integer")
     * @var int
     */
    private $pictureId;

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
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return Taxi
	 */
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPictureId() {
		return $this->pictureId;
	}

	/**
	 * @param int $pictureId
	 * @return Taxi
	 */
	public function setPictureId($pictureId) {
		$this->pictureId = $pictureId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param int $number
	 * @return Taxi
	 */
	public function setNumber($number) {
		$this->number = $number;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getActive() {
		return $this->active;
	}

	/**
	 * @param boolean $active
	 * @return Taxi
	 */
	public function setActive($active) {
		$this->active = $active;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getActiveimage() {
		return $this->activeimage;
	}

	/**
	 * @param boolean $activeimage
	 * @return Taxi
	 */
	public function setActiveimage($activeimage) {
		$this->activeimage = $activeimage;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 * @return Taxi
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCodeactivation() {
		return $this->codeactivation;
	}

	/**
	 * @param string $codeactivation
	 * @return Taxi
	 */
	public function setCodeactivation($codeactivation) {
		$this->codeactivation = $codeactivation;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCodeuser() {
		return $this->codeuser;
	}

	/**
	 * @param string $codeuser
	 * @return Taxi
	 */
	public function setCodeuser($codeuser) {
		$this->codeuser = $codeuser;
		return $this;
	}
}