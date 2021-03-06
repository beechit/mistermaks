<?php
namespace Beech\Party\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A Group
 *
 * @Flow\Entity
 */
class Group {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty>
	 * @ORM\ManyToMany
	 */
	protected $members;

	/**
	 * The type of the group
	 *
	 * @var \Beech\Party\Domain\Model\GroupType
	 * @ORM\ManyToOne
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $type;

	/**
	 * @var \Beech\Party\Domain\Model\Group
	 * @ORM\ManyToOne(inversedBy="children")
	 * @ORM\JoinColumn(name="parent_id")
	 */
	protected $parent;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group>
	 * @ORM\OneToMany(mappedBy="parent")
	 * @Flow\Lazy
	 */
	protected $children;

	/**
	 * Constructs the object
	 */
	public function __construct() {
		$this->children = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the Group Type
	 *
	 * @param \Beech\Party\Domain\Model\GroupType $type
	 * @return void
	 */
	public function setType(\Beech\Party\Domain\Model\GroupType $type) {
		$this->type = $type;
	}

	/**
	 * Returns the Group Type of this application
	 *
	 * @return \Beech\Party\Domain\Model\GroupType
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set parent
	 *
	 * @param \Beech\Party\Domain\Model\Group $parent
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * Get parent
	 *
	 * @return \Beech\Party\Domain\Model\Group
	 */
	public function getParent() {
		return $this->parent;
	}


	/**
	 * @param \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group> $children
	 */
	public function setChildren($children) {
		$this->children = $children;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Group>
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Group $child
	 * @return void
	 */
	public function addChild(\Beech\Party\Domain\Model\Group $child) {
		$this->children->add($child);
		$child->setParent($this);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Group $child
	 * @return void
	 */
	public function removeChild(\Beech\Party\Domain\Model\Group $child) {
		$this->children->removeElement($child);
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty> $members
	 * @return void
	 */
	public function setMembers($members) {
		$this->members = $members;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\TYPO3\Party\Domain\Model\AbstractParty>
	 */
	public function getMembers() {
		return $this->members;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $member
	 * @return void
	 */
	public function addMember(\TYPO3\Party\Domain\Model\AbstractParty $member) {
		$this->members->add($member);
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $member
	 * @return void
	 */
	public function removeMember(\TYPO3\Party\Domain\Model\AbstractParty $member) {
		$this->members->removeElement($member);
	}

}

?>