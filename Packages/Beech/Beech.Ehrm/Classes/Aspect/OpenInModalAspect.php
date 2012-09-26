<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 10:54
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DomCrawler;

/**
 * Open link in modal box functionality
 *
 * @FLOW3\Aspect
 */
class OpenInModalAspect {

	/**
	 * Parse generated menu to add support for opening links in modal box.
	 * If is set option 'modal: TRUE', open menu link in modal.
	 *
	 * By setting option modalId, its possible to open content in specified modal.
	 *
	 * @FLOW3\Around("method(Twitter\Bootstrap\ViewHelpers\Navigation\MenuViewHelper->render())")
	 * @param \TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint
	 * @return string
	 */
	public function addModalArguments(\TYPO3\FLOW3\Aop\JoinPointInterface $joinPoint) {
		$result = $joinPoint->getAdviceChain()->proceed($joinPoint);
		$items = $joinPoint->getMethodArgument('items');
		foreach ($items as $item) {
			if (isset($item['modal']) && $item['modal'] === TRUE) {
				$crawler = new \Symfony\Component\DomCrawler\Crawler($result);
				$crawler = $crawler->filterXPath('//a[@href="' . $item['href'] . '"]');
				foreach ($crawler as $domElement) {
					$domElement->setAttribute('data-toggle', 'modal');
					$domElement->setAttribute('data-target', '#' . (isset($item['modalId']) ? $item['modalId'] : 'entityModal'));
					$result = $domElement->ownerDocument->saveHTML();
				}
			}
		}
		return preg_replace('/<!DOCTYPE [^>]+>|<(\/){0,1}(html|body|_root)>/', '', $result);
	}
}

?>