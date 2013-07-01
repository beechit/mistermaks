<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 11:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM,
	Beech\Ehrm\Annotations as MM;

/**
 * A Company
 *
 * @Flow\Entity
 * @MM\EntityWithDocument
 */
class Company extends \TYPO3\Party\Domain\Model\AbstractParty implements \TYPO3\Flow\Object\DeclaresGettablePropertyNamesInterface {

	use \Beech\Ehrm\Domain\EntityWithDocumentTrait {
		__get as ___get;
	}
	use \Beech\Ehrm\Domain\ConfigurableModelTrait;

	/**
	 * The company name
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Company>
	 * @ORM\ManyToMany
	 * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
	 * @Flow\Lazy
	 */
	protected $departments;

	/**
	 * @var boolean
	 */
	protected $deleted = FALSE;

	/**
	 * Construct the object
	 */
	public function __construct() {
		parent::__construct();
		$this->departments = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @param string $chamberOfCommerce
	 */
	public function setChamberOfCommerceNumber($chamberOfCommerce) {
		$this->chamberOfCommerce = $chamberOfCommerce;
	}

	/**
	 * @return string
	 */
	public function getChamberOfCommerceNumber() {
		return $this->chamberOfCommerce;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function addDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->add($department);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $department
	 * @return void
	 */
	public function removeDepartment(\Beech\Party\Domain\Model\Company $department) {
		$this->departments->removeElement($department);
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getDepartments() {
		return $this->departments;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
	}

// Todo: make this more generic, this is also in person model
// this is the function to get properties in collections see documentation in EHRM-Base for more info.
	public function __get($property) {
		$return = $this->___get($property);
		if ($return === NULL) {
			$explodedProperty = explode('_', $property);
			if (count($explodedProperty) === 2) {
				list($model, $type) = $explodedProperty;
				$model = ucfirst($model);
				$repositoryClassName = sprintf('Beech\Party\Domain\Repository\%sRepository', $model);
				$repository = new $repositoryClassName();
				$findBy = sprintf('findBy%sType', $model);
				$allFounded = $repository->{$findBy}($type);
				foreach ($allFounded as $object) {
					if ($object->getPrimary()) {
						return $object;
					}
				}
			}
		}
		return $return;
	}
}

?>