<?php
namespace Beech\Absence\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Absence\Domain\Model\LeaveBalance as LeaveBalance;

/**
 * AbsenceBalance controller for the Beech.Absence package
 *
 * @Flow\Scope("singleton")
 */
class AbsenceBalanceController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Absence\Domain\Model\AbsenceBalance';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Absence\Domain\Repository\AbsenceBalanceRepository';
}

?>