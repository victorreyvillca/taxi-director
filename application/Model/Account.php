<?php
/**
 * Model Doctrine 2 for Dist 3
 *
 * @category Dist
 * @package Model
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Emini A/S
 * @license Proprietary
 */

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\AccountRepository")
 * @Table(name="Account")
 */
class Account extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $username;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $password;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $email;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $role;

	/**
	 * @Column(type="integer")
	 * @var int
	 */
	private $accountType;

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $username
	 * @return Account
	 */
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return Account
	 */
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return Account
	 */
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param string $role
	 * @return Account
	 */
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getAccountType() {
		return $this->accountType;
	}

	/**
	 * @param int $accountType
	 * @return Account
	 */
	public function setAccountType($accountType) {
		$this->accountType = $accountType;
		return $this;
	}
}