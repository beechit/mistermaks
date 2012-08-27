<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Karol Kamiński <karol@beech.it>
 * Date: 23-07-12 13:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\Party\Domain\Model\Company;

/**
 * Company controller for the Beech.Party package
 *
 * @FLOW3\Scope("singleton")
 */
class CompanyController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @FLOW3\Inject
	 */
	protected $companyRepository;

	/**
	 * @var \TYPO3\FLOW3\I18n\Translator
	 * @FLOW3\Inject
	 */
	protected $translator;

	/**
	 * Shows a list of companies
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('companies', $this->companyRepository->findAll());
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to show
	 * @return void
	 */
	public function showAction(Company $company) {
		$this->view->assign('departments', $this->companyRepository->findByParentCompany($company));
		$this->view->assign('company', $company);
	}

	/**
	 * Shows a form for creating a new company object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $newCompany A new company to add
	 * @return void
	 */
	public function createAction(Company $newCompany) {
		$this->companyRepository->add($newCompany);
		$this->addFlashMessage('Created a new company.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for creating a new company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The parent company
	 * @return void
	 */
	public function newDepartmentAction(\Beech\Party\Domain\Model\Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Adds the given new company object to the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $newDepartment A new department to add
	 * @param \Beech\Party\Domain\Model\Company $company The parent company
	 * @return void
	 */
	public function addDepartmentAction(Company $newDepartment, Company $company) {
		$newDepartment->setParentCompany($company);
		$this->companyRepository->add($newDepartment);
		$this->addFlashMessage($this->translator->translateById('flashmessage.newDepartment'));
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to edit
	 * @FLOW3\IgnoreValidation("$company")
	 * @return void
	 */
	public function editAction(Company $company) {
		$this->view->assign('company', $company);
	}

	/**
	 * Updates the given company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to update
	 * @return void
	 */
	public function updateAction(Company $company) {
		$this->companyRepository->update($company);
		$this->addFlashMessage($this->translator->translateById('flashmessage.updatedCompany'));
		$this->redirect('list');
	}

	/**
	 * Removes the given company object from the company repository
	 *
	 * @param \Beech\Party\Domain\Model\Company $company The company to delete
	 * @return void
	 */
	public function deleteAction(Company $company) {
		$this->companyRepositor($this->translator->translateById('flashmessage.deletedCompany'));
		$this->redirect('list');
	}

	/**
	 * Redirect to list action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('list');
	}

}

?>