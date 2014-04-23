<?php
use Model\Passenger;
use Model\Address;
use Model\Taxi;
use Model\Ride;
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_RideController extends Dis_Controller_Action {

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
	}

	/**
	 * This action shows a form to save Ride
	 * @access public
	 */
	public function addAction() {
		$this->_helper->layout()->disableLayout();

		$labelRepo = $this->_entityManager->getRepository('Model\Label');
		$taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
		$taxisArray = $taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER);

		$form = new Dis_Form_Ride();
		$form->getElement('taxi')->setMultiOptions($taxisArray);
		$form->getElement('label')->setMultiOptions($labelRepo->findAllArray());

		$this->view->form = $form;
	}

	/**
	 * Saves a new Ride
	 * @access public
	 */
	public function addSaveAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$labelRepo = $this->_entityManager->getRepository('Model\Label');
		$taxiRepo = $this->_entityManager->getRepository('Model\Taxi');

		$form = new Dis_Form_Ride();
		$form->getElement('label')->setMultiOptions($labelRepo->findAllArray());
		$form->getElement('taxi')->setMultiOptions($taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER));

        $formData = $this->getRequest()->getPost();
        if ($form->isValid($formData)) {
            $passengerRepo = $this->_entityManager->getRepository('Model\Passenger');
            if ($passengerRepo->verifyExistPhone($formData['phone'])) {
                $label = $this->_entityManager->find('Model\Label', (int)$formData['label']);
                $passenger = $this->_entityManager->find('Model\Passenger', (int)$formData['id']);
                $taxi = $this->_entityManager->find('Model\Taxi', (int)$formData['taxi']);

                $addressRepo = $this->_entityManager->getRepository('Model\Address');
                $address = $addressRepo->findByPassengerAndLabel($passenger, $label);
                $address->setName($formData['address']);

                $this->_entityManager->persist($address);
                $this->_entityManager->flush();

                $ride = new Ride();
                $ride
                    ->setLabel($label)
                    ->setPassenger($passenger)
                    ->setNotAssignedTime(1)
                    ->setNote($formData['note'])
                    ->setStatus(1)
                    ->setState(TRUE)
                    ->setCreated(new DateTime('now'))
                ;
                if ($taxi != NULL) {
                	$ride->setStatus(Ride::ONGOING);
                	$taxi->setStatus(Taxi::ONGOING);
                	$ride->setTaxi($taxi);
                } else {
                    $ride->setStatus(Ride::NOT_ASSIGNED);
                }

                $this->_entityManager->persist($ride);
                $this->_entityManager->flush();

//                 $label = $this->_entityManager->find('Model\Label', (int)$formData['label']);

//                 $address = new Address();
//                 $address
//                     ->setName($formData['address'])
//                     ->setPassenger($passenger)
//                     ->setLabel($label)
//                     ->setState(TRUE)
//                     ->setCreated(new DateTime('now'))
//                 ;

//                 $this->_entityManager->persist($address);
//                 $this->_entityManager->flush();

                $this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Carrera registrado');
			} else {
				$this->stdResponse->success = FALSE;
				$this->stdResponse->phone_duplicate = TRUE;
				$this->stdResponse->message = _('El Telefono No existe');
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
	        $labelRepo = $this->_entityManager->getRepository('Model\Label');

	        $this->stdResponse = new stdClass();
	        $this->stdResponse->success = FALSE;
	        $this->stdResponse->addressArray = $labelRepo->findAllArray();
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

		$form = new Dis_Form_Ride();
		$form->setOnlyRead(TRUE);

		$id = $this->_getParam('id', 0);
		$ride = $this->_entityManager->find('Model\Ride', $id);
		if ($ride != NULL) {//security
            $passenger = $ride->getPassenger();
		    $phone = $passenger->getPhone();
		    $name = $passenger->getFirstName();
		    $label = $ride->getLabel();

		    $addressRepo = $this->_entityManager->getRepository('Model\Address');
		    $address = $addressRepo->findByPassengerAndLabel($passenger, $label);

		    $form->getElement('id')->setValue($ride->getId());
		    $form->getElement('phone')->setValue($phone);
		    $form->getElement('name')->setValue($name);

            $form->getElement('phone')->setRequired(FALSE);
            $form->getElement('name')->setRequired(FALSE);

		    $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');

		    $taxisArray = $taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER);

            $form
                ->setPhone($phone)
                ->setName($name)
                ->setLabel($label->getName())
                ->setAddress($address->getName())
                ->setNote($ride->getNote())
            ;

            $form->getElement('taxi')->setMultiOptions($taxisArray);
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
	 * This action shows the form in update mode for Category.
	 * @access public
	 */
	public function resumeAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Dis_Form_Ride();
		$form->setOnlyRead(TRUE);
		$form->setIsResume(TRUE);

		$id = $this->_getParam('id', 0);
		$ride = $this->_entityManager->find('Model\Ride', $id);
		if ($ride != NULL) {//security
			$passenger = $ride->getPassenger();
			$label = $ride->getLabel();
			$taxi = $ride->getTaxi();

			$addressRepo = $this->_entityManager->getRepository('Model\Address');
			$address = $addressRepo->findByPassengerAndLabel($passenger, $label);

			$form->getElement('phone')->setValue($passenger->getPhone());
			$form->getElement('name')->setValue($passenger->getFirstName());

			$form
    			->setPhone($passenger->getPhone())
    			->setName($passenger->getFirstName())
    			->setLabel($label->getName())
    			->setAddress($address->getName())
    			->setNote($ride->getNote())
    			->setTaxi($taxi->getNumber())
			;
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

		$taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
		$taxisArray = $taxiRepo->findByStatusArrayNumber(Taxi::WITHOUT_CAREER);

		$form = new Dis_Form_Ride();
		$form->setOnlyRead(TRUE);
		$form->getElement('phone')->setRequired(FALSE);
		$form->getElement('name')->setRequired(FALSE);
		$form->getElement('taxi')->setMultiOptions($taxisArray);

		$formData = $this->getRequest()->getPost();
		if ($form->isValid($formData)) {
			$id = $this->_getParam('id', 0);
			$ride = $this->_entityManager->find('Model\Ride', $id);
			if ($ride != NULL) {
                $taxi = $this->_entityManager->find('Model\Taxi', (int)$formData['taxi']);
                $ride->setChanged(new DateTime('now'));

                if ($taxi != NULL) {
                    $ride->setStatus(Ride::ONGOING);
                    $taxi->setStatus(Taxi::ONGOING);
                    $ride->setTaxi($taxi);
                }

				$this->_entityManager->persist($ride);
				$this->_entityManager->flush();

				$this->stdResponse = new stdClass();
				$this->stdResponse->success = TRUE;
				$this->stdResponse->message = _('Carrera Actulizado');
			} else {
                $this->stdResponse = new stdClass();
				$this->stdResponse->success = FALSE;
				$this->stdResponse->message = _('No existe la Carrera');
			}
		} else {
            $this->stdResponse = new stdClass();
            $this->stdResponse->success = FALSE;
			$this->stdResponse->messageArray = $form->getMessages();
			$this->stdResponse->message = _('El Formulario contiene Errores');
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
	 * Outputs an XHR response containing all entries in rides.
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsRideEntriesNotAssignedAction() {
		$filterParams['name'] = $this->_getParam('filter_name', NULL);

		$filters = array();
		$filters[] = array('field' => 'status', 'filter' => Ride::NOT_ASSIGNED, 'operator' => '=');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$rideRepo = $this->_entityManager->getRepository('Model\Ride');
		$rides = $rideRepo->findByCriteria($filters, $limit, $start);
		$total = $rideRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($rides as $ride) {
			$changed = $ride->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $ride->getId();
			$row[] = $ride->getNote();
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
	 * Outputs an XHR response containing all entries in rides.
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsRideEntriesOnGoingAction() {
		$filterParams['name'] = $this->_getParam('filter_name', NULL);

		$filters = array();
		$filters[] = array('field' => 'status', 'filter' => Ride::ONGOING, 'operator' => '=');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$rideRepo = $this->_entityManager->getRepository('Model\Ride');
		$rides = $rideRepo->findByCriteria($filters, $limit, $start);
		$total = $rideRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($rides as $ride) {
			$changed = $ride->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $ride->getId();
			$row[] = $ride->getNote();
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
        $labelId = (int)$this->_getParam('labelId', 0);

        $label = $this->_entityManager->find('Model\Label', $labelId);
	    $passenger = $this->_entityManager->find('Model\Passenger', $passengerId);

	    $addressRepo = $this->_entityManager->getRepository('Model\Address');
	    $address = $addressRepo->findByPassengerAndLabel($passenger, $label);

	    $nameAddress = '';
	    if ($address != NULL) {
	    	$nameAddress = $address->getName();;
	    }

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

	public function notassignedAction() {

	}

	public function ongoingAction() {

	}
}