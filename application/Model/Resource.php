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
 * @Entity(repositoryClass="Model\Repositories\ResourceRepository")
 * @Table(name="Resource")
 */
class Resource extends DomainObject {

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $title;

	/**
	 * @Column(type="string")
	 * @var string
	 */
	private $description;

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string title
	 * @return Resource
	 */
	public function setTitle($title) {
		$this->title = $title;
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
	 * @return Resource
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
}