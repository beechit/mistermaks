<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 07-08-12 15:39
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;

/**
 * To-Do controller for the Beech.Party package
 *
 * @FLOW3\Scope("singleton")
 */
class ToDoController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @FLOW3\Inject
	 */
	protected $toDoRepository;

	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 * @var Beech\Party\Domain\Repository\PersonRepository
	 * @FLOW3\Inject
	 */
	protected $personRepository;

	/**
	 * Shows a list of todos
	 *
	 * @param boolean $archived
	 * @return void
	 */
	public function indexAction() {
			// TODO Sort on null values first then the ascending archivedDateTime (something like ISNULL(archivedDateTime)
		$orderings = array(	'archivedDateTime' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_ASCENDING,
							'priority' => \TYPO3\FLOW3\Persistence\QueryInterface::ORDER_DESCENDING);
		$this->toDoRepository->setDefaultOrderings($orderings);
		$this->view->assign('todos', $this->toDoRepository->findAll());
		$this->view->assign('priorities', \Beech\Party\Domain\Model\ToDo::getPriorities());
	}

	/**
	 * Creates an todo
	 *
	 * @param \Beech\Party\Domain\Model\ToDo $newToDo
	 */
	public function createAction(\Beech\Party\Domain\Model\ToDo $newToDo, $userMayArchive = TRUE) {
		$account = $this->securityContext->getAccount();

		if ($this->securityContext->getAccount()->getParty() instanceof \TYPO3\Party\Domain\Model\AbstractParty) {
			$newToDo->setOwner($account->getParty());
			$newToDo->setStarter($account->getParty());
		}

		$newToDo->setUserMayArchive($userMayArchive);
		$this->toDoRepository->add($newToDo);

		$this->redirect('index');
	}

	/**
	 * User archives an todo
	 *
	 * @param \Beech\Party\Domain\Model\ToDo $toDo
	 */
	public function userArchiveAction(\Beech\Party\Domain\Model\ToDo $toDo) {
		if ($toDo->getUserMayArchive() === TRUE) {
			$dateTime = new \DateTime();
			$toDo->setArchivedDateTime($dateTime);
			$this->toDoRepository->update($toDo);
		}
		$this->redirect('index');
	}

}

?>