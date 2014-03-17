<?php
/**
 * Controller for MOB.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_PictureController extends Dis_Controller_Action {
	const SRC_PICTURE = "/image/upload/news/";

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * lists all pictures news
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Titulo de la Imagen'));
		$this->view->formFilter = $formFilter;
	}

	/**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function addAction() {
		$this->_helper->layout()->disableLayout();

		$newsRepo = $this->_entityManager->getRepository('Model\News');

		$form = new Admin_Form_Picture();
		$form->setAction($this->_helper->url('add-save'));
		$form->getElement('news')->setMultiOptions($newsRepo->findAllArray());
		$this->view->form = $form;
	}

	/**
	 * Creates a new Picture
	 * @access public
	 */
	public function addSaveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_Picture();

			$newsRepo = $this->_entityManager->getRepository('Model\News');
			$form->getElement('news')->setMultiOptions($newsRepo->findAllArray());

			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$pictureRepo = $this->_entityManager->getRepository('Model\PictureNews');
				if (!$pictureRepo->verifyExistTitle($formData['title'])) {
					$imageFile = $form->getElement('file');

					try {
						$imageFile->receive();
					} catch (Zend_File_Transfer_Exception $e) {
						$e->getMessage();
					}

					$mimeType = $_FILES['file']['type'];
					$filename = $_FILES['file']['name'];

					$news = $this->_entityManager->find('Model\News', (int)$formData['news']);

					$picture = new Model\PictureNews();
					$picture
                        ->setTitle($formData['title'])
						->setDescription($formData['description'])
						->setSrc(self::SRC_PICTURE)
						->setFilename($filename)
						->setMimeType($mimeType)
						->setNews($news)
						->setCreated(new DateTime('now'))
						->setState(TRUE);

					$this->_entityManager->persist($picture);
					$this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _('Picture saved')));
					$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
				} else {
					$this->_helper->flashMessenger(array('success' => _('erro saved')));
					$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
				}
			} else {
				$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
			}
		} else {
			$this->_helper->redirector('read', 'Picture', 'admin', array('type'=>'information'));
		}
	}

	/**
	 * This action shows the form in update mode.
	 * @access public
	 */
	public function editAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Picture();

		$form->setAction($this->_helper->url('edit-save'));

		$id = $this->_getParam('id', 0);
		$picture = $this->_entityManager->find('Model\PictureNews', $id);
		if ($picture != NULL) {//security
		    $form->getElement('id')->setValue($picture->getId());
			$form->getElement('title')->setValue($picture->getTitle());
			$form->getElement('description')->setValue($picture->getDescription());
			$newsRepo = $this->_entityManager->getRepository('Model\News');
			$form->getElement('news')->setMultiOptions($newsRepo->findAllArray());
			$form->getElement('news')->setValue($picture->getNews()->getId());
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _('The requested record was not found.');
			$this->_helper->json($this->stdResponse);
		}
		$this->view->form = $form;
	}

	/**
	 * Updates the title and description of a Picture
	 * @access public
	 */
	public function editSaveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_Picture();
			$form->getElement('file')->setRequired(FALSE);

			$newsRepo = $this->_entityManager->getRepository('Model\News');
			$form->getElement('news')->setMultiOptions($newsRepo->findAllArray());

			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
			    $id = $this->_getParam('id', 0);
			    $picture = $this->_entityManager->find('Model\PictureNews', $id);
			    if ($picture != NULL) {
					$pictureRepo = $this->_entityManager->getRepository('Model\PictureNews');
					if (!$pictureRepo->verifyExistTitle($formData['title']) || $pictureRepo->verifyExistIdAndTitle($id, $formData['title'])) {
					    $news = $this->_entityManager->find('Model\News', (int)$formData['news']);

                        $imageFile = $form->getElement('file');

    					try {
    						$imageFile->receive();
    					} catch (Zend_File_Transfer_Exception $e) {
    						$e->getMessage();
    					}

    					$mimeType = $_FILES['file']['type'];
    					$filename = $_FILES['file']['name'];

    					if ($mimeType != NULL && $filename != '') {
    						$picture->setFilename($filename)->setMimeType($mimeType);
    					}

    					$news = $this->_entityManager->find('Model\News', (int)$formData['news']);

    					$picture
        					->setTitle($formData['title'])
        					->setDescription($formData['description'])
        					->setSrc(self::SRC_PICTURE)
        					->setNews($news)
        					->setChanged(new DateTime('now'))
                        ;

    					$this->_entityManager->persist($picture);
    					$this->_entityManager->flush();

    					$this->_helper->flashMessenger(array('success' => _('Picture saved')));
    					$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
					} else {
                        $this->_helper->flashMessenger(array('success' => _('Picture no exist')));
                        $this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
					}
				} else {
					$this->_helper->flashMessenger(array('success' => _('error saved')));
					$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
				}
			} else {
			    $this->_helper->flashMessenger(array('error' => _('the form contains errors')));
				$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
			}
		} else {
			$this->_helper->redirector('index', 'Picture', 'admin', array('type'=>'information'));
		}
	}

// 	/**
// 	 *
// 	 * Downloads Picture
// 	 * @access public
// 	 */
// 	public function downloadAction() {
// 		$this->_helper->layout()->disableLayout();
// 		$this->_helper->viewRenderer->setNoRender(true);

// 		$id = (int)$this->_getParam('id', 0);

// 		$pictureMapper = new Model_PictureMapper();
// 		$picture = $pictureMapper->find($id);

// 		$file = $picture->getFile();
// 		$this->_response
// 				->setHeader('Content-type', $file->getMimeType())
// 				->setHeader('Content-Disposition', sprintf('attachment; filename="%s"', $file->getFilename()));

// 		$protocol = 'http:';
// 		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
// 			$protocol = 'https:';
// 		}

// 		readfile(sprintf('%s//%s%s%s', $protocol, $_SERVER['SERVER_NAME'], $picture->getSrc(), $file->getFilename()));
// 	}

	/**
	 * Deletes pictures
	 * @access public
	 * @internal
	 * 1) Gets the model Picture
	 * 2) Validate the existance of dependencies
	 * 3) Change the state field or records to delete
	 */
	public function removeAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$picture = $this->_entityManager->find('Model\PictureNews', $id);
				$picture->setChanged(new DateTime('now'));
				$picture->setState(FALSE);

				$this->_entityManager->persist($picture);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d picture removed.', '%d pictures removed.', $removeCount), $removeCount);
			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _($message);
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _('Data submitted is empty.');
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Outputs an XHR response containing all entries in pictures.
	 * @xhrParam int filter_title
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
    public function dsPictureEntriesAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['title'] = $this->_getParam('filter_title', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$pictureRepo = $this->_entityManager->getRepository('Model\PictureNews');
		$pictures = $pictureRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pictureRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($pictures as $picture) {
			$changed = $picture->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $picture->getId();
			$row[] = $picture->getTitle();
			$row[] = $picture->getDescription();
			$row[] = $picture->getFilename();
			$row[] = $picture->getNews()->getTitle();
			$row[] = $picture->getCreated()->format('d.m.Y');
			$row[] = $changed;
			$row[] = '[]';
			$data[] = $row;
			$posRecord++;
		}
		// response
		$this->stdResponse->iTotalRecords = $total;
		$this->stdResponse->iTotalDisplayRecords = $total;
		$this->stdResponse->aaData = $data;
		$this->_helper->json($this->stdResponse);
	}

	/**
	 *
	 * Returns an associative array where:
	 * field: title of the table field
	 * filter: value to match
	 * operator: the sql operator.
	 * @param array $filterParams
	 * @return array(field, filter, operator)
	 */
	private function getFilters($filterParams) {
		$filters = array ();
		if (empty($filterParams)) {
			return $filters;
		}

		if (!empty($filterParams['title'])) {
			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['title'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * Outputs an XHR response, loads the titles of the pictures.
	 */
	public function autocompleteAction() {
// 		$filterParams['title'] = $this->_getParam('title_auto', NULL);
// 		$filters = $this->getFilters($filterParams);

// 		$pictureRepo = $this->_entityManager->getRepository('Model\Picture');
// 		$this->stdResponse->items = $pictureRepo->findByCriteriaOnlyTitle($filters);
// 		$this->_helper->json($this->stdResponse);


		$filterParams['title'] = $this->_getParam('title_auto', NULL);

		$filters = $this->getFilters($filterParams);


		$categoryRepo = $this->_entityManager->getRepository('Model\PictureNews');
		$categories = $categoryRepo->findByCriteria($filters);
// var_dump($categories); exit;
		$data = array();
		foreach ($categories as $category) {
			$data[] = $category->getTitle();
		}

		$this->stdResponse = new stdClass();
		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}
}