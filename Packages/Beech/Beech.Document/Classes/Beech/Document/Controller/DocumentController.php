<?php
namespace Beech\Document\Controller;

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
use \Beech\Document\Domain\Model\Document;

/**
 * Document controller for the Beech.Document package
 *
 * @Flow\Scope("singleton")
 */
class DocumentController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Document\Domain\Model\Document';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Document\Domain\Repository\DocumentRepository';

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentTypeRepository
	 * @Flow\Inject
	 */
	protected $documentTypeRepository;

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * Shows a list of documents
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 * @return void
	 */
	public function listAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL) {
		$this->view->assign('documentCategories', $this->documentTypeRepository->findAllGroupedByCategories());
		if ($party != NULL) {
			$this->view->assign('documents', $this->repository->findByParty($party));
		} else {
			$this->view->assign('documents', $this->repository->findAll());
		}
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party
	 * @param array $documentCategories
	 */
	public function newAction(\TYPO3\Party\Domain\Model\AbstractParty $party = NULL, $documentCategories = array()) {
		if (empty($documentCategories)) {
			$documentCategories = $this->documentTypeRepository->findAllGroupedByCategories();
		}
		$this->view->assign('documentCategories', $documentCategories);
		$this->view->assign('party', $party);
		if ($party != NULL) {
			$this->view->assign('documents', $this->repository->findByParty($party));
		} else {
			$this->view->assign('documents', $this->repository->findAll());
		}
	}

	/**
	 * Adds the given new document object to the document repository
	 *
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function createAction(\Beech\Document\Domain\Model\Document $document) {
		$this->repository->add($document);
		$this->addFlashMessage($this->translator->translateById('document.documentUploaded', array(), NULL, NULL, 'Main', 'Beech.Document'));
		if ($document->getParty() != NULL) {
			if ($document->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$this->emberRedirect('#/person/show/'.$document->getParty()->getId());
			} else {
				$this->emberRedirect('#/company/show/'.$document->getParty()->getId());
			}
		} else {
			$this->redirect('new');
		}
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function editAction(\Beech\Document\Domain\Model\Document $document) {
		$this->view->assign('document', $document);
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @return void
	 */
	public function showAction(\Beech\Document\Domain\Model\Document $document) {
		$this->view->assign('document', $document);
	}

	/**
	 * @param \Beech\Document\Domain\Model\Document $document
	 * @Flow\IgnoreValidation("$document")
	 * @return string
	 */
	public function downloadAction(\Beech\Document\Domain\Model\Document $document) {

		$attachments = $document->getResources();
		$attachment = reset($attachments);

		$name = key($attachments);

		$this->response->setHeader('Content-Type', $attachment->getContentType());
		$this->response->setHeader('Content-Disposition', 'attachment; filename="' . $name . '"');
		$this->response->setContent($attachment->getRawData());

		return '';
	}

	/**
	 * Updates the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Document $document The document to update
	 * @return void
	 */
	public function updateAction(\Beech\Document\Domain\Model\Document $document) {
		$this->repository->update($document);
		$this->redirect('list');
	}

	/**
	 * Removes the given document object
	 *
	 * @param \Beech\Document\Domain\Model\Document $document The document to delete
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @Flow\IgnoreValidation("$document")
	 * @return void
	 */
	public function deleteAction(\Beech\Document\Domain\Model\Document $document, \Beech\Party\Domain\Model\Person $person = NULL) {
		$this->repository->remove($document);
		$this->addFlashMessage($this->translator->translateById('document.documentDeleted', array(), NULL, NULL, 'Main', 'Beech.Document'));
		if ($person !== NULL) {
			$this->emberRedirect('#/person/show/' . $person->getId());
		} else {
			$this->emberRedirect('#/documents');
		}

	}



}

?>