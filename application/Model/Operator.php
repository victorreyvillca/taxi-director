<?php

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\OperatorRepository")
 * @Table(name="Operator")
 */
class Operator extends Person {

	/**
	 * @Column(type="integer")
	 * @var boolean
	 */
	private $visible;

	/**
	 * Id of the Account this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $accountId;

	/**
	 * Account this model is associated with.
	 * @ManyToOne(targetEntity="Account")
	 * @JoinColumn(name="accountId", referencedColumnName="id")
	 * @var Account
	 */
	private $account;

	/**
	 * Id of the Role this model is associated with.
	 * @Column(type="integer")
	 * @var int
	 */
	private $roleId;

	/**
	 * Pathfinder this model is associated with.
	 * @ManyToOne(targetEntity="Role")
	 * @JoinColumn(name="roleId", referencedColumnName="id")
	 * @var Role
	 */
	private $role;

	/**
	 * @return boolean
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * @param boolean $visible
	 * @return Operator
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
		return $this;
	}

	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @param Account $account
	 * @return Operator
	 */
	public function setAccount($account) {
		$this->account = $account;
		return $this;
	}

	/**
	 * @return Role
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param Role $role
	 * @return Operator
	 */
	public function setRole($role) {
		$this->role = $role;
		return $this;
	}
}