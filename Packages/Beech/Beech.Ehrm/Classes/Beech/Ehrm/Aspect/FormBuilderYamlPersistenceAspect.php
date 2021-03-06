<?php
namespace Beech\Ehrm\Aspect;

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

/**
 * Aspect allows for fetching an alternative yaml settings file for a form generated by the formbuilder
 * TYPO3.Formbuilder forms are stored in a .yaml file in /Data/Forms/
 * Copy a (modified) generated file to the /Beech.Ehrm/Resources/Private/Wizards folder to alter default behaviour
 *
 * @Flow\Aspect
 */
class FormBuilderYamlPersistenceAspect {

	/**
	 * @var string
	 */
	protected $alternativeSavePath = 'resource://Beech.Ehrm/Private/Wizards/';

	/**
	 * Load alternative wizard file if one is found
	 *
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Around("method(TYPO3\Form\Persistence\YamlPersistenceManager->getFormPathAndFilename())")
	 * @return string the absolute path and filename of the form with the specified $persistenceIdentifier
	 */
	public function getAlternativeFormPathAndFilename(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$persistenceIdentifier = $joinPoint->getMethodArgument('persistenceIdentifier');
		$alternativeFile = \TYPO3\Flow\Utility\Files::concatenatePaths(array($this->alternativeSavePath, sprintf('%s.yaml', $persistenceIdentifier)));
		if (file_exists($alternativeFile)) {
			return $alternativeFile;
		}

		$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		return $result;
	}
}

?>