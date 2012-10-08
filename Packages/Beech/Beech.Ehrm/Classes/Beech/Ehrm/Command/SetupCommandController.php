<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Setup command controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class SetupCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationRepository
	 * @Flow\Inject
	 */
	protected $applicationRepository;

	/**
	 * Create a company
	 *
	 * @param string $companyName Company name
	 * @return void
	 */
	public function initializeCommand($companyName) {
		if ($this->companyRepository->countByName($companyName) > 0) {
			$this->outputLine('Company "%s" already exists.', array($companyName));
			return;
		}

			// Create the company
		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName($companyName);
		$company->setChamberOfCommerceNumber('');

			// Create a new application
		$application = new \Beech\Ehrm\Domain\Model\Application($company);
		$application->setCompany($company);
		$this->applicationRepository->add($application);

		$this->outputLine('Created an application for company "%s".', array($companyName));
	}
}
?>