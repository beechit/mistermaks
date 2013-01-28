<?php
namespace Beech\WorkFlow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 11-09-12 11:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Package base class of the Beech.WorkFlow package.
 *
 * @Flow\Scope("singleton")
 */
class Package extends BasePackage {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();

		if (!isset($_SERVER['SHELL'])) {
				// TODO Move this to another place later on.
				// 1) this could be a performance killer
				// 2) the check for $_SERVER['SHELL'] is dirty, but has to be done to be able to migrate the database
			$dispatcher->connect('TYPO3\Flow\Mvc\Dispatcher', 'beforeControllerInvocation', 'Beech\WorkFlow\WorkFlow\ActionDispatcher', 'run');
		}
	}
}
?>