<?php
/**
 * Model Doctrine 2 for Taxi
 *
 * @category Dist
 * @package Models
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Emini A/S
 * @license Proprietary
 */

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\PersonRepository")
 * @Table(name="Person")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"person"="Person", "operator"="Operator", "driver"="Driver", "passenger"="Passenger"})
 */
class Person extends DomainObject {

    const SEX_MALE      = 1;
    const SEX_FEMALE    = 2;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $identityCard;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $firstName;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $lastName;

	/**
	 * @Column(type="datetime")
	 * @var datetime
	 */
	private $dateOfBirth;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $phone;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $phonework;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $phonemobil;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $sex;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $profilePictureId;

	/**
	 * @return int
	 */
	public function getIdentityCard() {
		return $this->identityCard;
	}

	/**
	 * @param int $identityCard
	 * @return Person
	 */
	public function setIdentityCard($identityCard) {
		$this->identityCard = $identityCard;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $name
	 * @return Base
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 * @return Base
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		return $this;
	}


	/**
	 * @return datetime
	 */
	public function getDateOfBirth() {
		return $this->dateOfBirth;
	}

	/**
	 * @param datetime $dateOfBirth
	 * @return Person
	 */
	public function setDateOfBirth($dateOfBirth) {
		$this->dateOfBirth = $dateOfBirth;
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
	 * @return Person
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhonework() {
		return $this->phonework;
	}

	/**
	 * @param string $phonework
	 * @return Person
	 */
	public function setPhonework($phonework) {
		$this->phonework = $phonework;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPhonemobil() {
		return $this->phonemobil;
	}

	/**
	 * @param int $phonemobil
	 * @return Person
	 */
	public function setPhonemobil($phonemobil) {
		$this->phonemobil = $phonemobil;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSex() {
		return $this->sex;
	}

	/**
	 * @param int $sex
	 * @return Person
	 */
	public function setSex($sex) {
		$this->sex = $sex;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
        return $this->firstName . " " . $this->lastName;
    }

	/**
	 * @return int
	 */
	public function getProfilePictureId() {
		return $this->profilePictureId;
	}

	/**
	 * @param int $profilePictureId
	 * @return Person
	 */
	public function setProfilePictureId($profilePictureId) {
		$this->profilePictureId = $profilePictureId;
		return $this;
	}

	/**
	 * @return array
	 */
	public static function getGenderArray() {
		return array(self::SEX_MALE => _('Masculino'), self::SEX_FEMALE => _('Femenino'));
	}
}