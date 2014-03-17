<?php
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_RoleController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * This action shows a paginated list of roles
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_('Nombre del Rol'));

		$this->view->formFilter = $formFilter;
	}

	/**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function addAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_Category();
		$form->getElement('name')->setLabel(_('Nombre del Rol'));
		$this->view->form = $form;
	}

	/**
	 * Creates a new Role
	 * @access public
	 */
	public function addSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Category();

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
// 			if (!$categoryMapper->verifyExistName($formData['name'])) {
				$role = new Model\Role();
				$role
                    ->setName($formData['name'])
                    ->setDescription($formData['description'])
                    ->setCreated(new DateTime('now'))
                    ->setCreatedBy(1)
                    ->setState(TRUE)
                ;
                $this->_entityManager->persist($role);
                $this->_entityManager->flush();

                $this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Rol registrado');
// 			} else {
// 				$this->stdResponse->success = FALSE;
// 				$this->stdResponse->name_duplicate = TRUE;
// 				$this->stdResponse->message = _("The Category already exists");
// 			}
		} else {
            $this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _('El formulario tiene errores y no se ha registrado');
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * This action shows the form in update mode for Role.
	 * @access public
	 */
	public function editAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_Category();
		$form->getElement('name')->setLabel(_('Nombre del Rol'));

		$id = $this->_getParam('id', 0);
		$role = $this->_entityManager->find('Model\Role', $id);
		if ($role != NULL) {//security
			$form->getElement('name')->setValue($role->getName());
			$form->getElement('description')->setValue($role->getDescription());
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
	 * Updates a Role
	 * @access public
	 */
	public function editSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$form = new Admin_Form_Category();
		$form->getElement('name')->setLabel(_('Nombre del Rol'));

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);
			$role = $this->_entityManager->find('Model\Role', $id);
			if ($role != NULL) {
// 				if (!$categoryMapper->verifyExistName($formData['name']) || $categoryMapper->verifyExistIdAndName($id, $formData['name'])) {
				$role
                    ->setName($formData['name'])
                    ->setDescription($formData['description'])
                    ->setChanged(new DateTime('now'))
                    ->setChangedBy(1)
                ;

				$this->_entityManager->persist($role);
				$this->_entityManager->flush();

				$this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Role updated');
// 				} else {
// 					$this->stdResponse->success = FALSE;
// 					$this->stdResponse->name_duplicate = TRUE;
// 					$this->stdResponse->message = _("The Category already exists");
// 				}
			} else {
                $this->stdResponse = new stdClass();
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _('The Role does not exists');
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
				$role = $this->_entityManager->find('Model\Role', $id);
				$role->setState(FALSE);

				$this->_entityManager->persist($role);
				$this->_entityManager->flush();

				$removeCount++;
			}
			$message = sprintf(ngettext('%d role removed.', '%d roles removed.', $removeCount), $removeCount);

			$this->stdResponse = new stdClass();
			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _($message);
		} else {
            $this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("Data submitted is empty.");
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Outputs an XHR response containing all entries in roles.
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

		$roleRepo = $this->_entityManager->getRepository('Model\Role');
		$roles = $roleRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $roleRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($roles as $role) {
			$changed = $role->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $role->getId();
			$row[] = $role->getName();
			$row[] = $role->getDescription();
			$row[] = $role->getCreated()->format('d.m.Y');
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

		$roleRepo = $this->_entityManager->getRepository('Model\Role');
		$roles = $roleRepo->findByCriteria($filters);

		$data = array();
		foreach ($roles as $role) {
			$data[] = $role->getName();
		}

		$this->stdResponse = new stdClass();
		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}
}