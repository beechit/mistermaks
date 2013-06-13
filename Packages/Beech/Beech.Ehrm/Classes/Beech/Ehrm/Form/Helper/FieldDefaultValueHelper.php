<?php
namespace Beech\Ehrm\Form\Helper;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 26-03-13 16:08
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class FieldDefaultValueHelper {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * Get default value for property
	 *
	 * @param array $property
	 * @return string
	 */
	public function getDefault($property) {
		$defaultValue = '';
		if (isset($property['defaultValue'])) {
			if ($property['defaultValue'] === 'now' || $property['defaultValue'] === 'today') {
				$defaultValue = date('Y-m-d');
			} elseif ($property['defaultValue'] === 'currentUser') {
				$tokens = $this->securityContext->getAuthenticationTokens();

				foreach ($tokens as $token) {
					if ($token->isAuthenticated()) {
						$defaultValue = $token->getAccount()->getParty();
					}
				}
			} else {
				$defaultValue = $property['defaultValue'];
			}
		}
		return $defaultValue;
	}

	/**
	 * @param string $prefix
	 * @param integer $articleId
	 * @param string $suffix
	 * @return string
	 */
	public function generateIdentifierForArticle($prefix, $articleId, $suffix) {
		if (!empty($prefix)) {
			return sprintf('%s-article-%s-values.%s', $prefix, $articleId, $suffix);
		} else {
			return sprintf('article-%s-values.%s', $articleId, $suffix);
		}

	}
}

?>