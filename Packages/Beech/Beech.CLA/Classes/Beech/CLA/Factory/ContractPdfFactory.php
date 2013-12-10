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
class ContractPdfFactory extends \TYPO3\Form\Factory\AbstractFormFactory {

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreterUtility;

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
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return void
	 */
	protected function init($factorySpecificConfiguration, $presetName) {
		$this->factorySpecificConfiguration = $factorySpecificConfiguration;
		$formConfiguration = $this->getPresetConfiguration($presetName);
		$this->form = new FormDefinition('contractPreview', $formConfiguration);
	}

	/**
	 * @param array $factorySpecificConfiguration
	 * @param string $presetName
	 * @return \TYPO3\Form\Core\Model\FormDefinition
	 */
	public function build(array $factorySpecificConfiguration, $presetName) {
		$this->init($factorySpecificConfiguration, $presetName);

		/** @var $contract \Beech\CLA\Domain\Model\Contract */
		$contract = $factorySpecificConfiguration['contract'];

		/** @var $previewPage \Beech\CLA\FormElements\ContractPreviewPage */
		$previewPage = $this->form->createPage('previewPage', 'Beech.CLA:ContractPdfPage');
		$previewPage->setLabel($contract->getContractTemplate()->getTemplateName());

			// add renderingOption so view known's it is in preview mode
		$this->form->setRenderingOption('preview', TRUE);

		/** @var $contractHeader \Beech\CLA\FormElements\ContractHeaderPdfSection */
		$contractHeader = $previewPage->createElement('contractHeader', 'Beech.CLA:ContractHeaderPdfSection');
		$contractHeader->setProperty('contract', $contract);

		/** @var $contractFooter \Beech\CLA\FormElements\ContractFooterSection */
		$contractFooter = $previewPage->createElement('contractFooter', 'Beech.CLA:ContractFooterSection');
		$contractFooter->setProperty('contract', $contract);

		/** @var $articlesSection \Beech\CLA\FormElements\ContractArticlesSection */
		$articlesSection = $previewPage->createElement('articles', 'Beech.CLA:ContractArticlesSection');
		$articlesSection->setContract($contract);
		$articlesSection->initContractArticles();
		$articlesSection->initializeFormElement();

		return $this->form;
	}

}

?>