<?php
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_NewsController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * This action shows a paginated list of news
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_DepartmentFilter();

		$categoryRepo = $this->_entityManager->getRepository('Model\Category');

		$formFilter->getElement('nameFilter')->setLabel(_('Titulo de la noticia'));
		$formFilter->getElement('countryFilter')->setLabel(_('Categoria'));
		$formFilter->getElement('countryFilter')->setMultiOptions($categoryRepo->findAllArray());
        $this->view->formFilter = $formFilter;
	}

	/**
	 * This action shows a form in create mode for the news
	 * @access public
	 */
	public function addAction() {
		$form = new Admin_Form_News();

		$categoryRepo = $this->_entityManager->getRepository('Model\Category');
		$form->getElement('categoryId')->setMultiOptions($categoryRepo->findAllArray());

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
// 				$newsMapper = new Model_NewsMapper();
// 				if (!$newsMapper->verifyExistTitle($formData['title'])) {
					$fileName = $_FILES['imageFile']['name'];

					$imageFile = $form->getElement('imageFile');
					try {
		 				$imageFile->receive();
					} catch (Zend_File_Transfer_Exception $e) {
						$e->getMessage();
					}

                    $administratorId = Zend_Auth::getInstance()->getIdentity()->id;
                    $administrator = $this->_entityManager->find('Model\Administrator', $administratorId);

                    $category = $this->_entityManager->find('Model\Category', (int)$formData['categoryId']);

					$news = new Model\News();
					$news
						->setSummary($formData['summary'])
						->setContain($formData['contain'])
						->setFount($formData['fount'])
						->setCategory($category)
						->setAdministrator($administrator)
						->setTitle($formData['title'])
						->setImagename($fileName)
						->setNewsdate(new DateTime('now'))
						->setCreatedBy($administratorId)
						->setCreated(new DateTime('now'))
						->setState(TRUE)
					;

					$this->_entityManager->persist($news);
                    $this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _('News saved')));
					$this->_helper->redirector('index', 'news', 'admin', array('type'=>'information'));
// 				} else {
// 					$this->_helper->flashMessenger(array('error' => _("The News already exists")));
// 				}
			} else {
				$this->_helper->flashMessenger(array('error' => _('The form contains error and is not saved')));
			}
		}

		$this->view->form = $form;
	}

	/**
	 * This action shows the form in update mode for News on the view.
	 * @access public
	 */
	public function editAction() {
		$form = new Admin_Form_News();

		$categoryRepo = $this->_entityManager->getRepository('Model\Category');
		$form->getElement('categoryId')->setMultiOptions($categoryRepo->findAllArray());
		$form->getElement('update')->setLabel(_('Editar Noticia'));

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				$id = $this->_getParam('id', 0);
				$news = $this->_entityManager->find('Model\News', $id);
				if ($news != NULL) {//security
// 					if (!$newsMapper->verifyExistTitle($formData['title']) || $newsMapper->verifyExistIdAndTitle($id, $formData['title'])) {
						$fileName = $_FILES['imageFile']['name'];
						if ($fileName != NULL) {
							$imageFile = $form->getElement('imageFile');
							try {
								$imageFile->receive();
							} catch (Zend_File_Transfer_Exception $e) {
								$e->getMessage();
							}
						} else {
							$fileName = $news->getImagename();
						}

                        $administratorId = Zend_Auth::getInstance()->getIdentity()->id;
                        $administrator = $this->_entityManager->find('Model\Administrator', $administratorId);

                        $category = $this->_entityManager->find('Model\Category', (int)$formData['categoryId']);

						$news->setCategory($category)
							->setAdministrator($administrator)
							->setSummary($formData['summary'])
							->setFount($formData['fount'])
							->setTitle($formData['title'])
							->setContain($formData['contain'])
							->setImagename($fileName)
							->setChangedBy($administratorId)
							->setNewsdate(new DateTime('now'))
							->setChanged(new DateTime('now'))
                        ;

						$this->_entityManager->persist($news);
						$this->_entityManager->flush();

						$this->_helper->flashMessenger(array('success' => _('News updated')));
						$this->_helper->redirector('index', 'news', 'admin', array('type'=>'information'));
// 					} else {
// 						$this->_helper->flashMessenger(array('error' => _("The News already exists")));
// 					}
				} else {
					$this->_helper->flashMessenger(array('error' => _('The News does not exists')));
				}
			} else {
				$this->_helper->flashMessenger(array('error' => _('The form contains error and is not updated')));
			}
		} else {
			$id = $this->_getParam('id', 0);
			$news = $this->_entityManager->find('Model\News', $id);

			if ($news != NULL) {//security
				$form->getElement('newsId')->setValue($id);
				$form->getElement('title')->setValue($news->getTitle());
				$form->getElement('summary')->setValue($news->getSummary());
				$form->getElement('contain')->setValue($news->getContain());
				$form->getElement('fount')->setValue($news->getFount());
				$form->getElement('categoryId')->setValue($news->getCategory()->getId());
			}
		}

		$this->view->form = $form;
	}

	/**
	 * This action returns content to load the form
	 */
	public function loadAction() {
		try {
			$id = $this->_getParam('id', 0);
			$news = $this->_entityManager->find('Model\News', $id);
			if ($news != NULL) {//security
				$this->stdResponse->htmlContent = $news->getContain();
			} else {

			}
		} catch (Exception $e) {
			$this->exception($this->view, $e);
		}
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Deletes categories
	 * @access public
	 */
	public function removeAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$news = $this->_entityManager->find('Model\News', $id);
				$news->setState(FALSE);

				$this->_entityManager->persist($news);
				$this->_entityManager->flush();

				$removeCount++;
			}
			$message = sprintf(ngettext('%d news removed.', '%d news removed.', $removeCount), $removeCount);

			$this->stdResponse = new stdClass();
			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _($message);
		} else {
			$this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _('Data submitted is empty.');
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Outputs an XHR response containing all entries in news.
	 * @xhrParam int filter_category
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['nameFilter'] = $this->_getParam('filter_name', NULL);
		$filterParams['countryFilter'] = $this->_getParam('filter_category', -1);

		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$newsRepo = $this->_entityManager->getRepository('Model\News');
		$news = $newsRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $newsRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($news as $information) {
			$changed = $information->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $information->getId();
			$row[] = $information->getTitle();
			$row[] = $information->getSummary();
			$row[] = $information->getCategory()->getName();
			$row[] = $information->getImagename();
			$row[] = $information->getNewsdate()->format('d.m.Y');
			$row[] = $information->getFount();
            $row[] = $information->getCreated()->format('d.m.Y');
            $row[] = $changed;
			$row[] = '[]';
			$data[] = $row;
			$posRecord++;
		}
		// response
		$this->stdResponse = new stdClass();
		$this->stdResponse->iTotalRecords = $total;
		$this->stdResponse->iTotalDisplayRecords = $total;
		$this->stdResponse->aaData = $data;
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Returns an associative array where:
	 * field: name of the table field
	 * filter: value to match
	 * operator: the sql operator.
	 * @param array $filterParams contains the values selected by the user.
	 * @return array(field, filter, operator)
	 */
	private function getFilters($filterParams) {

		foreach ($filterParams as $field => $filter) {
			$filterParams[$field] = trim($filter);
		}

		$filters = array ();

// 		// condition when: country != All and name == ''
// 		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] == '') {
// 			$filters[] = array('field' => 'categoryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
// 			return $filters;
// 		}

// 		// condition when: mainGroup != All and name != ''
// 		if ($filterParams['countryFilter'] != -1 && $filterParams['nameFilter'] != '') {
// 			$filters[] = array('field' => 'categoryId', 'filter' => $filterParams['countryFilter'], 'operator' => '=' );
// 			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
// 			return $filters;
// 		}

// 		if (!empty($filterParams['nameFilter'])) {
// 			$filters[] = array('field' => 'title', 'filter' => '%'.$filterParams['nameFilter'].'%', 'operator' => 'LIKE');
// 		}

		return $filters;
	}

// 	/**
// 	 * Returns the ids and names of categories
// 	 * @return array
// 	 */
// 	private function getCategories() {
// 		$categoryMapper = new Model_CategoryMapper();
// 		return $categoryMapper->fetchAllName();
// 	}

// 	/**
// 	 * Returns the ids, names and the item "All" of categories
// 	 */
// 	private function getCategoriesFilter() {
// 		$categories = $this->getCategories();
// 		$data = array();
// 		$data[-1] = _("All");

// 		foreach ($categories as $key => $value) {
// 			$data[$key] = $value;
// 		}

// 		return $data;
// 	}
}