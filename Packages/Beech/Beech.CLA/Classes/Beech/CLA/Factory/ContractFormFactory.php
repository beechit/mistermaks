<?php
namespace Beech\CLA\Factory;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Form\Core\Model\FormDefinition;

/**
 * Contract form factory
 */
class ContractFormFactory extends \TYPO3\Form\Factory\AbstractFormFactory {

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreter;

	/**
	 * @var \TYPO3\Form\Core\Model\FormDefinition
	 */
	protected $form;

	/**
	 * Contains setting's values passed to form
	 *
	 * @var array
	 */
	protected $factorySpecificConfiguration;

	/**
	 * Initial page index
	 *
	 * @var integer
	 */
	protected $pageIndex = 1;

	/**
	 * Number of fields per page
	 *
	 * @var integer
	 */
	protected $fieldsPerPage = 3;

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return void
	 */
	protected function init($factorySpecificConfiguration, $presetName) {
		$this->factorySpecificConfiguration = $factorySpecificConfiguration;
		$formConfiguration = $this->getPresetConfiguration($presetName);
		$this->form = new FormDefinition('contractCreator', $formConfiguration);
	}

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 */
	public function build(array $factorySpecificConfiguration, $presetName) {
		$this->init($factorySpecificConfiguration, $presetName);
		if (!isset($this->factorySpecificConfiguration['contractTemplate'])) {
			$selectTemplateStep = $this->form->createPage('initialStep');
			$selectTemplateStep->createElement('contractTemplate', 'Beech.CLA:ContractTemplateSelect');
				// Employee field
			$selectTemplateStep->createElement('employee', 'Beech.Party:EmployeeSelect');
				// Job description field
			$selectTemplateStep->createElement('jobDescription', 'Beech.CLA:JobDescriptionSelect');
			$redirectFinisher = new \Beech\CLA\Finishers\RedirectToTemplateFinisher();
			$redirectFinisher->setOptions(
				array(
					'action' => 'start',
					'controller' => 'Contract',
					'package' => 'Beech.CLA\\Administration'
				)
			);

			$this->form->addFinisher($redirectFinisher);
		} else {
			while ($this->nextArticlesPage()) {
				$this->pageIndex++;
			}
			$lastPage = $this->form->getPageByIndex(0);
			$contractTemplateField = $lastPage->createElement('contractTemplate', 'TYPO3.Form:HiddenField');
			$contractTemplateField->setDefaultValue($this->factorySpecificConfiguration['contractTemplate']);
			$employeeField = $lastPage->createElement('employee', 'TYPO3.Form:HiddenField');
			$employeeField->setDefaultValue($this->factorySpecificConfiguration['employee']);
			$jobDescriptionField = $lastPage->createElement('jobDescription', 'TYPO3.Form:HiddenField');
			$jobDescriptionField->setDefaultValue($this->factorySpecificConfiguration['jobDescription']);

			$databaseFinisher = new \Beech\Ehrm\Form\Finishers\DatabaseFinisher();
			$databaseFinisher->setOptions(
				array(
					'model' => 'Contract',
					'package' => 'Beech.CLA'
				)
			);
			$this->form->addFinisher($databaseFinisher);

			$redirectFinisher = new \TYPO3\Form\Finishers\RedirectFinisher();
			$redirectFinisher->setOptions(
				array(
					'action' => 'list',
					'package' => 'Beech.CLA\\Administration'
				)
			);
			$this->form->addFinisher($redirectFinisher);
		}
		return $this->form;
	}

	/**
	 * Create next page
	 * Return FALSE when there is no elements on page
	 *
	 * @return boolean
	 */
	protected function nextArticlesPage() {
		$articlesPage = $this->form->createPage('articlesStep' . $this->pageIndex);

		$articlesSection = $articlesPage->createElement('articles' . $this->pageIndex, 'Beech.CLA:ContractArticlesSection');
		$articlesSection->setContractTemplate($this->factorySpecificConfiguration['contractTemplate']);
		$contractArticles = $articlesSection->getContractArticles(($this->pageIndex - 1) * $this->fieldsPerPage, $this->fieldsPerPage);
		$articlesSection->initializeFormElement();
		if (count($contractArticles) > 0) {
			return TRUE;
		} else {
			$this->form->removePage($articlesPage);
			return FALSE;
		}
	}
}

?>