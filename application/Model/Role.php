<?php
/**
 * Model Doctrine 2 for Dist 3
 *
 * @category Dist
 * @package Model
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Emini A/S
 * @license Proprietary
 */

namespace Model;

/**
 * @Entity(repositoryClass="Model\Repositories\RoleRepository")
 * @Table(name="Role")
 */
class Role extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $name;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $description;

	/**
	 * @ManyToMany(targetEntity="Resource")
	 * @JoinTable(name="Role_Resource",
	 *		joinColumns={@JoinColumn(name="roleId", referencedColumnName="id")},
	 *		inverseJoinColumns={@JoinColumn(name="resourceId", referencedColumnName="id")}
	 *		)
	 */
	private $resources;

	public function __construct() {
		$this->resources = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Role
	 */
	public function setName($name) {
		$this->name = $name;
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
	 * @return Role
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
}