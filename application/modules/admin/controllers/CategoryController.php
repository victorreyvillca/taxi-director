<?php
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_CategoryController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * This action shows a paginated list of categories
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Nombre de categoria'));
		$this->view->formFilter = $formFilter;
	}

	/**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function addAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Category();
		$this->view->form = $form;
	}

	/**
	 * Creates a new Category
	 * @access public
	 */
	public function addSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Category();

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
// 			if (!$categoryMapper->verifyExistName($formData['name'])) {
				$category = new Model\Category();
				$category
                    ->setName($formData['name'])
                    ->setDescription($formData['description'])
                    ->setCreated(new DateTime('now'))
                    ->setCreatedBy(Zend_Auth::getInstance()->getIdentity()->id)
                    ->setState(TRUE)
                ;
                $this->_entityManager->persist($category);
                $this->_entityManager->flush();

                $this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Category saved');
// 			} else {
// 				$this->stdResponse->success = FALSE;
// 				$this->stdResponse->name_duplicate = TRUE;
// 				$this->stdResponse->message = _("The Category already exists");
// 			}
		} else {
            $this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _('The form contains error and is not saved');
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * This action shows the form in update mode for Category.
	 * @access public
	 */
	public function editAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Category();

		$id = $this->_getParam('id', 0);
		$category = $this->_entityManager->find('Model\Category', $id);
		if ($category != NULL) {//security
			$form->getElement('name')->setValue($category->getName());
			$form->getElement('description')->setValue($category->getDescription());
		} else {
			// response to client
            $this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _('The requested record was not found.');
			$this->_helper->json($this->stdResponse);
		}

		$this->view->form = $form;
	}

	/**
	 * Updates a Category
	 * @access public
	 */
	public function editSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Category();

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);
			$category = $this->_entityManager->find('Model\Category', $id);
			if ($category != NULL) {
// 				if (!$categoryMapper->verifyExistName($formData['name']) || $categoryMapper->verifyExistIdAndName($id, $formData['name'])) {
				$category
                    ->setName($formData['name'])
                    ->setDescription($formData['description'])
                    ->setChanged(new DateTime('now'))
                    ->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id)
                ;

				$this->_entityManager->persist($category);
				$this->_entityManager->flush();

				$this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Category updated');
// 				} else {
// 					$this->stdResponse->success = FALSE;
// 					$this->stdResponse->name_duplicate = TRUE;
// 					$this->stdResponse->message = _("The Category already exists");
// 				}
			} else {
                $this->stdResponse = new stdClass();
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _('The Category does not exists');
			}
		} else {
            $this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _('The form contains error and is not saved');
		}
		// sends response to client
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
				$category = $this->_entityManager->find('Model\Category', $id);
				$category->setState(FALSE);

				$this->_entityManager->persist($category);
				$this->_entityManager->flush();

				$removeCount++;
			}
			$message = sprintf(ngettext('%d category removed.', '%d categories removed.', $removeCount), $removeCount);

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
	 * Outputs an XHR response containing all entries in categories.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['name'] = $this->_getParam('filter_name', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$categoryRepo = $this->_entityManager->getRepository('Model\Category');
		$categories = $categoryRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $categoryRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($categories as $category) {
			$changed = $category->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $category->getId();
			$row[] = $category->getName();
			$row[] = $category->getDescription();
			$row[] = $category->getCreated()->format('d.m.Y');
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
		$filters = array ();

		if (empty($filterParams)) {
			return $filters;
		}

		if (!empty($filterParams['name'])) {
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * Outputs an XHR response, loads the names of the categories.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$categoryRepo = $this->_entityManager->getRepository('Model\Category');
		$categories = $categoryRepo->findByCriteria($filters);

		$data = array();
		foreach ($categories as $category) {
			$data[] = $category->getName();
		}

		$this->stdResponse = new stdClass();
		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}
}