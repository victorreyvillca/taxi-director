<?php
use Model\Passenger;
use Model\Address;
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_PassengerController extends Dis_Controller_Action {

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

		$labelRepo = $this->_entityManager->getRepository('Model\Label');

		$form = new Dis_Form_Passenger();
		$form->getElement('label')->setMultiOptions($labelRepo->findAllArray());

		$this->view->form = $form;
	}

	/**
	 * Creates a new Category
	 * @access public
	 */
	public function addSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$labelRepo = $this->_entityManager->getRepository('Model\Label');

		$form = new Dis_Form_Passenger();
		$form->getElement('label')->setMultiOptions($labelRepo->findAllArray());

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
            $passengerRepo = $this->_entityManager->getRepository('Model\Passenger');
			if (!$passengerRepo->verifyExistPhone($formData['phone'])) {

				$passenger = new Passenger();
				$passenger
				    ->setSex(1)
				    ->setLastName('Lastname passenger')
				    ->setAddress($formData['address'])
				    ->setDateOfBirth(new DateTime('now'))
				    ->setCreated(new DateTime('now'))
				    ->setState(TRUE)
				    ->setIdentityCard(10)
                    ->setPhonemobil(100)
				    ->setPhone($formData['phone'])
                    ->setFirstName($formData['firstName'])
                ;

                $this->_entityManager->persist($passenger);
                $this->_entityManager->flush();

                $label = $this->_entityManager->find('Model\Label', (int)$formData['label']);

                $address = new Address();
                $address
                    ->setName($formData['address'])
                    ->setPassenger($passenger)
                    ->setLabel($label)
                    ->setState(TRUE)
                    ->setCreated(new DateTime('now'))
                ;

                $this->_entityManager->persist($address);
                $this->_entityManager->flush();

                $this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Pasajero registrado');
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->phone_duplicate = TRUE;
				$this->stdResponse->message = _('El Telefono ya existe');
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
	 * This action shows a form to search passengers
	 * @access public
	 */
	public function searchAction() {
		$this->_helper->layout()->disableLayout();

		$labelRepo = $this->_entityManager->getRepository('Model\Label');

		$form = new Dis_Form_SearchPassenger();
		$form->getElement('label')->setMultiOptions($labelRepo->findAllArray());

		$this->view->form = $form;
	}

	/**
	 * Searchs the Passenger by number phone
	 * @access public
	 */
	public function dsSearchAction() {
	    $this->_helper->viewRenderer->setNoRender(TRUE);

        $phone = $this->_getParam('phone', NULL);

	    $passengerRepo = $this->_entityManager->getRepository('Model\Passenger');
	    $passenger = $passengerRepo->findByPhone($phone);

	    $data = NULL;
	    if ($passenger != NULL) {
	        $addressRepo = $this->_entityManager->getRepository('Model\Address');
	        $addresses = $addressRepo->findByPassenger($passenger);

	        $addressArray = array();
	        $addressName = '';
	        $swName = TRUE;
	        foreach ($addresses as $address) {
	            if ($swName) {
                    $addressName = $address->getName();
                    $swName = FALSE;
	            }
                $label = $address->getLabel();
                if ($label != NULL) {
                	$addressArray[$label->getId()] = $label->getName();
                }
	        }
            $data = array(
                'id' => $passenger->getId(),
                'name' => $passenger->getFirstName(),
                'address' => $addressName
            );

            $this->stdResponse = new stdClass();
            $this->stdResponse->success = TRUE;
            $this->stdResponse->addressArray = $addressArray;
	    } else {
	        $this->stdResponse = new stdClass();
	        $this->stdResponse->success = FALSE;
	    }

	    $this->stdResponse->data = $data;
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

		$categoryRepo = $this->_entityManager->getRepository('Model\Passenger');
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
			$row[] = $category->getFirstName();
			$row[] = '';
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

	/**
	 * Outputs an XHR response, loads the names of passengers.
	 * @access public
	 */
	public function autocompleteLabelAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$labelRepo = $this->_entityManager->getRepository('Model\Label');
		$labels = $labelRepo->findByCriteria($filters);

		$data = array();
		foreach ($labels as $label) {
			$data[] = $label->getName();
		}

		$this->stdResponse = new stdClass();
		$this->stdResponse->items = $data;
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Outputs an XHR response, the name address of the passengers.
	 * @access public
	 */
	public function dsPassengerAddressAction() {
	    $passengerId = (int)$this->_getParam('passengerId', 0);
// 	    var_dump($passengerId);
//         $labelId = (int)$this->_getParam('labelId', 0);
// //         var_dump($labelId); exit;

//         $label = $this->_entityManager->find('Model\Label', $labelId);
// 	    $passenger = $this->_entityManager->find('Model\Passenger', $passengerId);


//         $addressRepo = $this->_entityManager->getRepository('Model\Address');
//         $address = $addressRepo->findByPassengerAndLabel($passenger, $label);var_dump($address); exit;


// 	    $nameAddress = '';
// 	    if ($address != NULL) {
// 	    	$nameAddress = $address->getName();;
// 	    }

	    $this->stdResponse = new stdClass();
	    $this->stdResponse->nameAddress = $nameAddress;

	    $this->_helper->json($this->stdResponse);
	}

	/**
	 * Changes the name for the passenger
	 * @access public
	 */
	public function dsChangeNameAction() {
		$formData = $this->_getAllParams();
		$passenger = $this->_entityManager->find('Model\Passenger', (int)$formData['id']);
		if ($passenger != NULL) {
			$passenger
                ->setFirstName($formData['firstName'])
                ->setPhone($formData['phone'])
                ->setChanged(new DateTime('now'))
			;

			$this->_entityManager->persist($passenger);
			$this->_entityManager->flush();

			$this->stdResponse = new stdClass();
			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _('Cambio realizado');
		} else {
		    $this->stdResponse->message = _('Cambio No realizado Pasajero no entrado');
		    $this->stdResponse = new stdClass();
		    $this->stdResponse->success = FALSE;
		}

		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Deletes label for the Address
	 * @access public
	 */
	public function dsDeleteLabelAction() {
		$formData = $this->_getAllParams();
		$passenger = $this->_entityManager->find('Model\Passenger', (int)$formData['id']);
		$label = $this->_entityManager->find('Model\Label', (int)$formData['label']);
		if ($passenger != NULL && $label != NULL) {

		    $addressRepo = $this->_entityManager->getRepository('Model\Address');
		    $address = $addressRepo->findByPassengerAndLabel($passenger, $label);
            $address
                ->setState(FALSE)
                ->setChanged(new DateTime('now'))
            ;

			$this->_entityManager->persist($address);
			$this->_entityManager->flush();

			$addresses = $addressRepo->findByPassenger($passenger);

			$addressArray = array();
			$addressName = '';
			$swName = TRUE;
			foreach ($addresses as $add) {
				if ($swName) {
					$addressName = $add->getName();
					$swName = FALSE;
				}
				$label = $add->getLabel();
				if ($label != NULL) {
					$addressArray[$label->getId()] = $label->getName();
				}
			}
			$data = array(
                'address' => $addressName
			);

			$this->stdResponse = new stdClass();
			$this->stdResponse->success = TRUE;
			$this->stdResponse->addressArray = $addressArray;
// 			$this->stdResponse->message = _('Etiqueta Eliminado');
		} else {
			$this->stdResponse = new stdClass();
			$this->stdResponse->success = FALSE;
// 			$this->stdResponse->message = _('Cambio No realizado Pasajero no entrado');
		}

		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Deletes label for the Address
	 * @access public
	 */
	public function dsChangeAddressAction() {
		$formData = $this->_getAllParams();
		$passenger = $this->_entityManager->find('Model\Passenger', (int)$formData['id']);
		$label = $this->_entityManager->find('Model\Label', (int)$formData['label']);
		if ($passenger != NULL && $label != NULL) {

			$addressRepo = $this->_entityManager->getRepository('Model\Address');
			$address = $addressRepo->findByPassengerAndLabel($passenger, $label);
			$address
    			->setName($formData['address'])
    			->setChanged(new DateTime('now'))
			;

			$this->_entityManager->persist($address);
			$this->_entityManager->flush();

			$addresses = $addressRepo->findByPassenger($passenger);

			$addressArray = array();
			$addressName = '';
			$swName = TRUE;
			foreach ($addresses as $address) {
				if ($swName) {
					$addressName = $address->getName();
					$swName = FALSE;
				}
				$label = $address->getLabel();
				if ($label != NULL) {
					$addressArray[$label->getId()] = $label->getName();
				}
			}
			$data = array(
				'address' => $addressName
			);

			$this->stdResponse = new stdClass();
			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _('Cambio realizado');
		} else {
		    $this->stdResponse = new stdClass();
		    $this->stdResponse->success = FALSE;
		    $this->stdResponse->message = _('Cambio No realizado Pasajero no entrado');
		}

		$this->_helper->json($this->stdResponse);
	}
}