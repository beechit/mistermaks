<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * setup command controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class UserCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 * @Flow\Inject
	 */
	protected $accountFactory;

	/**
	 * Create a user
	 *
	 * @param string $username Username
	 * @param string $password Password
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $roles Comma separated list of roles
	 * @return void
	 */
	public function createCommand($username, $password, $firstName, $lastName, $roles) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if ($account instanceof \TYPO3\Flow\Security\Account) {
			$this->outputLine('User "%s" already exists.', array($username));
			return;
		}

		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName(NULL, $firstName, NULL, $lastName));
		$this->personRepository->add($person);

		$account = $this->accountFactory->createAccountWithPassword($username, $password, explode(',', $roles), 'DefaultProvider');

		$account->setParty($person);
		$this->accountRepository->add($account);
		$this->outputLine('Created account "%s".', array($username));
	}

	/**
	 * Set a setting for a user
	 *
	 * Because default value is set to NULL its very important to use syntax
	 * --value NL_nl cause otherwise it will not work
	 *
	 * Example:
	 *
	 * CORRECT
	 * ./flow3 user:setting admin locale --value NL_nl
	 *
	 * NOT CORRECT
	 * ./flow3 user:setting admin locale NL_nl
	 *
	 * @param string $username
	 * @param string $setting
	 * @param string $value
	 * @return void
	 */
	public function settingCommand($username, $setting, $value = NULL) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($username, 'DefaultProvider');
		if (!$account instanceof \TYPO3\Flow\Security\Account) {
			$this->outputLine('Account "%s" not found', array($username));
		}

		if (!$account->getParty() instanceof \Beech\Party\Domain\Model\Person) {
			$this->outputLine('Account "%s" does not have a valid Person object ', array($username));
		}

		if ($value === NULL) {
			$this->outputLine('Setting "%s" for "%s" contains value "%s"', array(
				$setting,
				$account->getParty()->getName()->getFullName(),
				$this->preferenceUtility->getModelPreference($account->getParty(), \Beech\Ehrm\Utility\PreferenceUtility::CATEGORY_USER, $setting)
			));
		} else {
			$this->preferenceUtility->setModelPreference($account->getParty(), \Beech\Ehrm\Utility\PreferenceUtility::CATEGORY_USER, $setting, $value);

			$this->outputLine('Setting "%s" for "%s" set to "%s"', array(
				$setting,
				$account->getParty()->getName()->getFullName(),
				$value
			));
		}

		$this->personRepository->update($account->getParty());
	}

}

?>