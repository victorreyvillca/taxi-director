<?php
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */
use Model\Taxi;
use Model\Driver;

class Admin_TaxiController extends Dis_Controller_Action {

    /**
     * (non-PHPdoc)
     * @see App_Controller_Action::init()
     */
    public function init() {
    	parent::init();
    }

    /**
     * Lists all the taxis entries
     * @access public
     */
    public function indexAction() {
        $formFilter = new Admin_Form_SearchFilter();
        $formFilter->getElement('nameFilter')->setLabel(_('Nombre del Movil'));
        $this->view->formFilter = $formFilter;
    }

    /**
     * Lists all the taxis off
     * @access public
     */
    public function offAction() {

    }

    /**
     * Lists all the taxis on going
     * @access public
     */
    public function ongoingAction() {

    }

    /**
     * Lists all the taxis without career
     * @access public
     */
    public function withoutcareerAction() {
        $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
        $taxis = $taxiRepo->findByStatus(Taxi::WITHOUT_CAREER);
        foreach ($taxis as $taxiObjetc) {
            $taxiObjetc
                ->setChanged(new DateTime('now'))
                ->setActive(FALSE);

            $this->_entityManager->persist($taxiObjetc);
            $this->_entityManager->flush();
        }
    }

    /**
     *
     * This action shows a form in create mode
     * @access public
     */
    public function addAction() {
        $this->_helper->layout()->disableLayout();

        $form = new Dis_Form_Taxi();
        $form->setAction($this->_helper->url('add-save'));

        $src = '/image/profile/female_or_male_default.jpg';
        $form->setSource($src);

        $srcTaxi = '/image/profile/logo-taxi.png';
        $form->setSourceTaxi($srcTaxi);

        $this->view->form = $form;
    }

    /**
     * Creates a new Taxi
     * @access public
     */
    public function addSaveAction() {
    	if ($this->_request->isPost()) {
    		$form = new Dis_Form_Taxi();
    		$formData = $this->getRequest()->getPost();

    		if ($form->isValid($formData)) {
                $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');

    		    if (!$taxiRepo->verifyExistNumber((int)$formData['number'])) {
                    $taxi = new Taxi();
                    $taxi
                        ->setActive(FALSE)
                        ->setNumber($formData['number'])
                        ->setName(_('Movil'))
                        ->setStatus(Taxi::WITHOUT_CAREER)
                        ->setMark($formData['mark'])
                        ->setPlaque($formData['plaque'])
                        ->setType($formData['typeMark'])
                        ->setModel((int)$formData['model'])
                        ->setColor($formData['color'])
                        ->setCreated(new DateTime('now'))
                        ->setState(TRUE)
        			;

        			if ($_FILES['filetaxi']['error'] !== UPLOAD_ERR_NO_FILE) {
        				if ($_FILES['filetaxi']['error'] == UPLOAD_ERR_OK) {
        					$fh = fopen($_FILES['filetaxi']['tmp_name'], 'r');
        					$binary = fread($fh, filesize($_FILES['filetaxi']['tmp_name']));
        					fclose($fh);

        					$mimeType = $_FILES['filetaxi']['type'];
        					$fileName = $_FILES['filetaxi']['name'];

        					$dataVaultMapper = new Dis_Model_DataVaultMapper();
        					$dataVault = new Dis_Model_DataVault();
        					$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
        					$dataVaultMapper->save($dataVault);

        					$taxi->setPictureId($dataVault->getId());
        				}
        			}

        			$this->_entityManager->persist($taxi);
        			$this->_entityManager->flush();

        			$driver = new Driver();
        			$driver
        			     ->setTaxi($taxi)
                        ->setPhone('132')
                        ->setPhonemobil(123)
                        ->setDateOfBirth(new DateTime('now'))
                        ->setAddress($formData['address'])
                        ->setSex(1)
                        ->setIdentityCard($formData['ci'])
                        ->setLastName($formData['lastName'])
            			->setFirstName($formData['firstName'])
            			->setCreated(new DateTime('now'))
            			->setState(TRUE)
        			;

        			if ($_FILES['filedriver']['error'] !== UPLOAD_ERR_NO_FILE) {
        				if ($_FILES['filedriver']['error'] == UPLOAD_ERR_OK) {
        					$fh = fopen($_FILES['filedriver']['tmp_name'], 'r');
        					$binary = fread($fh, filesize($_FILES['filedriver']['tmp_name']));
        					fclose($fh);

        					$mimeType = $_FILES['filedriver']['type'];
        					$fileName = $_FILES['filedriver']['name'];

        					$dataVaultMapper = new Dis_Model_DataVaultMapper();
        					$dataVault = new Dis_Model_DataVault();
        					$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
        					$dataVaultMapper->save($dataVault);

        					$driver->setProfilePictureId($dataVault->getId());
        				}
        			}

        			$this->_entityManager->persist($driver);
        			$this->_entityManager->flush();

        			$this->_helper->flashMessenger(array('success' => _('Taxi registrado')));
        			$this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
    		    } else {
                    $this->_helper->flashMessenger(array('error' => _('Ya existe Taxi con ese Numero')));
                    $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
    		    }
    		} else {
    		    $this->_helper->flashMessenger(array('error' => _('Los datos no son validados')));
    			$this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
    		}
    	} else {
    		$this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
    	}
    }

    /**
     * This action shows the form to edit Taxi.
     * @access public
     */
    public function editAction() {
        $this->_helper->layout()->disableLayout();

        $form = new Dis_Form_Taxi();
        $form->setAction($this->_helper->url('edit-save'));

        $id = $this->_getParam('id', 0);
        $taxi = $this->_entityManager->find('Model\Taxi', $id);
        if ($taxi != NULL) {
            $form->getElement('taxiId')->setValue($taxi->getId());
            $form->getElement('number')->setValue($taxi->getNumber());
            $form->getElement('mark')->setValue($taxi->getMark());
            $form->getElement('plaque')->setValue($taxi->getPlaque());
            $form->getElement('typeMark')->setValue($taxi->getType());
            $form->getElement('model')->setValue($taxi->getModel());
            $form->getElement('color')->setValue($taxi->getColor());

            $form->setNumber($taxi->getNumber());
            $form->setMark($taxi->getMark());
            $form->setPlaque($taxi->getPlaque());
            $form->setTypeMark($taxi->getType());
            $form->setModel($taxi->getModel());
            $form->setColor($taxi->getColor());

            $dataVaultMapper = new Dis_Model_DataVaultMapper();
            $dataVault = $dataVaultMapper->find($taxi->getPictureId());
            if ($dataVault != NULL && $dataVault->getBinary()) {
                $src = $this->_helper->url('profile-picture', 'Taxi', 'admin', array('id' => $dataVault->getId(), 'timestamp' => time()));
            } else {
                $src = '/image/profile/logo-taxi.png';
            }
            $form->setSourceTaxi($src);

            $driverRepo = $this->_entityManager->getRepository('Model\Driver');
            $driver = $driverRepo->findByTaxi($taxi);

            $form->getElement('driverId')->setValue($driver->getId());
            $form->getElement('firstName')->setValue($driver->getFirstName());
            $form->getElement('lastName')->setValue($driver->getLastName());
            $form->getElement('ci')->setValue($driver->getIdentityCard());
            $form->getElement('address')->setValue($driver->getAddress());

            $form->setFirstName($driver->getFirstName());
            $form->setLastName($driver->getLastName());
            $form->setCi($driver->getIdentityCard());
            $form->setAddress($driver->getAddress());

            $dataVault = $dataVaultMapper->find($driver->getProfilePictureId());
            if ($dataVault != NULL && $dataVault->getBinary()) {
            	$src = $this->_helper->url('profile-picture', 'Taxi', 'admin', array('id' => $dataVault->getId(), 'timestamp' => time()));
            } else {
            	$src = '/image/profile/male_default.jpg';
            }
            $form->setSource($src);
        } else {
            $this->stdResponse->success = FALSE;
            $this->stdResponse->message = _('The requested record was not found.');
            $this->_helper->json($this->stdResponse);
        }

        $this->view->form = $form;
    }

    /**
     * Updates a Taxi of the Driver
     * @access public
     */
    public function editSaveAction() {
        if ($this->_request->isPost()) {
            $form = new Dis_Form_Taxi();
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = $this->_getParam('taxiId', 0);
                $taxi = $this->_entityManager->find('Model\Taxi', $id);
                if ($taxi != NULL) {
                    $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
                    if (!$taxiRepo->verifyExistNumber($formData['number']) || $taxiRepo->verifyExistIdAndNumber($id, $formData['number'])) {
                        $taxi
                            ->setNumber($formData['number'])
                            ->setMark($formData['mark'])
                            ->setPlaque($formData['plaque'])
                            ->setType($formData['typeMark'])
                            ->setModel((int)$formData['model'])
                            ->setColor($formData['color'])
                            ->setChanged(new DateTime('now'))
                        ;

                        if ($_FILES['filetaxi']['error'] !== UPLOAD_ERR_NO_FILE) {
                            if ($_FILES['filetaxi']['error'] == UPLOAD_ERR_OK) {
                                $fh = fopen($_FILES['filetaxi']['tmp_name'], 'r');
                                $binary = fread($fh, filesize($_FILES['filetaxi']['tmp_name']));
                                fclose($fh);

                                $mimeType = $_FILES['filetaxi']['type'];
                                $fileName = $_FILES['filetaxi']['name'];

                                $dataVaultMapper = new Dis_Model_DataVaultMapper();

                                if ($taxi->getPictureId() != NULL) {// if it has image profile update
                                    $dataVault = $dataVaultMapper->find($taxi->getPictureId(), FALSE);
                                    $dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                                    $dataVaultMapper->update($taxi->getPictureId(), $dataVault);
                                } elseif ($taxi->getPictureId() == NULL) {// if it don't have image profile create
                                    $dataVault = new Dis_Model_DataVault();
                                    $dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                                    $dataVaultMapper->save($dataVault);

                                    $taxi->setPictureId($dataVault->getId());
                                }
                            }
                        }

                        $this->_entityManager->persist($taxi);
                        $this->_entityManager->flush();

                        $driverRepo = $this->_entityManager->getRepository('Model\Driver');
                        $driver = $driverRepo->findByTaxi($taxi);
                        $driver
                            ->setTaxi($taxi)
                            ->setPhone('123')
                            ->setPhonemobil(123)
                            ->setAddress($formData['address'])
                            ->setSex(1)
                            ->setIdentityCard($formData['ci'])
                            ->setLastName($formData['lastName'])
                            ->setFirstName($formData['firstName'])
                            ->setChanged(new DateTime('now'))
                        ;

                        if ($_FILES['filedriver']['error'] !== UPLOAD_ERR_NO_FILE) {
                        	if ($_FILES['filedriver']['error'] == UPLOAD_ERR_OK) {
                        		$fh = fopen($_FILES['filedriver']['tmp_name'], 'r');
                        		$binary = fread($fh, filesize($_FILES['filedriver']['tmp_name']));
                        		fclose($fh);

                        		$mimeType = $_FILES['filedriver']['type'];
                        		$fileName = $_FILES['filedriver']['name'];

                        		$dataVaultMapper = new Dis_Model_DataVaultMapper();

                        		if ($driver->getProfilePictureId() != NULL) {// if it has image profile update
                        			$dataVault = $dataVaultMapper->find($driver->getProfilePictureId(), FALSE);
                        			$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                        			$dataVaultMapper->update($driver->getProfilePictureId(), $dataVault);
                        		} elseif ($driver->getProfilePictureId() == NULL) {// if it don't have image profile create
                        			$dataVault = new Dis_Model_DataVault();
                        			$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                        			$dataVaultMapper->save($dataVault);

                        			$driver->setProfilePictureId($dataVault->getId());
                        		}
                        	}
                        }

                        $this->_entityManager->persist($driver);
                        $this->_entityManager->flush();

                        $this->_helper->flashMessenger(array('success' => _('Taxi editado')));
                        $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
                    } else {
                        $this->_helper->flashMessenger(array('error' => _('Ya existe Taxi con ese numero')));
                        $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
                    }
                } else {
                    $this->_helper->flashMessenger(array('error' => _('Taxi no encontrado')));
                    $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
                }
            } else {
                $this->_helper->flashMessenger(array('error' => _('Error')));
                $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
            }
        } else {
            $this->_helper->redirector('index', 'Taxi', 'admin', array('type'=>'information'));
        }
    }

    /**
     * Deletes taxis
     * @access public
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $itemIds = $this->_getParam('itemIds', array());
        if (!empty($itemIds) ) {
            $removeCount = 0;
            foreach ($itemIds as $id) {
                $taxi = $this->_entityManager->find('Model\Taxi', $id);
                $taxi->setChanged(new DateTime('now'));
                $taxi->setState(FALSE);

                $this->_entityManager->persist($taxi);
                $this->_entityManager->flush();
                $removeCount++;
            }
            $message = sprintf(ngettext('%d taxi eliminado.', '%d taxis eliminados.', $removeCount), $removeCount);

            $this->stdResponse->success = TRUE;
            $this->stdResponse->message = _($message);
        } else {
            $this->stdResponse->success = FALSE;
            $this->stdResponse->message = _('Los datos estan vacios.');
        }
        // sends response to client
        $this->_helper->json($this->stdResponse);
    }

    /**
	 * Sends the binary file vault to the user agent.
	 * @return binary
	 */
	public function profilePictureAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $id = (int)$this->_getParam('id', 0);

        $dataVaultMapper = new Dis_Model_DataVaultMapper();
        $dataVault = $dataVaultMapper->find($id);

        if ($dataVault->getBinary()) {
            $this->_response
            //No caching
                ->setHeader('Pragma', 'public')
                ->setHeader('Expires', '0')
                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                ->setHeader('Cache-Control', 'private')
                ->setHeader('Content-type', $dataVault->getMimeType())
                ->setHeader('Content-Transfer-Encoding', 'binary')
                ->setHeader('Content-Length', strlen($dataVault->getBinary()));

			echo $dataVault->getBinary();
		} else {
			$this->_response->setHttpResponseCode(404);
		}
	}

	/**
	 * Outputs an XHR response containing all entries in directives.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsTaxiEntriesAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$administratorRepo = $this->_entityManager->getRepository('Model\Taxi');
		$administrators = $administratorRepo->findByCriteria(array(), $limit, $start, $sortCol, $sortDirection);
		$total = $administratorRepo->getTotalCount(array());

		$posRecord = $start+1;
		$data = array();
		foreach ($administrators as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName().' '.$directive->getNumber();
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
	 * Outputs an XHR response containing all entries in directives.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
    public function dsTaxiEntriesWithoutCareerAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$filters = array();
		$filters[] = array('field' => 'status', 'filter' => Taxi::WITHOUT_CAREER, 'operator' => '=');

		$administratorRepo = $this->_entityManager->getRepository('Model\Taxi');
		$administrators = $administratorRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $administratorRepo->getTotalCount(array());

		$posRecord = $start+1;
		$data = array();
		foreach ($administrators as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName().' '.$directive->getNumber();
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
	 * Outputs an XHR response containing all entries in directives.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsTaxiEntriesOnGoingAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$filters = array();
		$filters[] = array('field' => 'status', 'filter' => Taxi::ONGOING, 'operator' => '=');

		$administratorRepo = $this->_entityManager->getRepository('Model\Taxi');
		$administrators = $administratorRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $administratorRepo->getTotalCount(array());

		$posRecord = $start+1;
		$data = array();
		foreach ($administrators as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName().' '.$directive->getNumber();
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
	 * Outputs an XHR response containing all entries in directives.
	 * This action serves as a datasource for the read/index view
	 * @xhrParam int filter_name
	 * @xhrParam int iDisplayStart
	 * @xhrParam int iDisplayLength
	 */
	public function dsTaxiEntriesOffAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$filters = array();
		$filters[] = array('field' => 'status', 'filter' => Taxi::OFF, 'operator' => '=');

		$administratorRepo = $this->_entityManager->getRepository('Model\Taxi');
		$administrators = $administratorRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $administratorRepo->getTotalCount(array());

		$posRecord = $start+1;
		$data = array();
		foreach ($administrators as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName().' '.$directive->getNumber();
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
     * Outputs an XHR response, loads the first names of the directives.
     */
    public function autocompleteAction() {
        $filterParams['name'] = $this->_getParam('name_auto', NULL);
        $filters = $this->getFilters($filterParams);

        $directiveRepo = $this->_entityManager->getRepository('Model\Administrator');
        $directives = $directiveRepo->findByCriteria($filters);

        $data = array();
        foreach ($directives as $directive) {
            $data[] = $directive->getFirstName();
        }

        $this->stdResponse->items = $data;
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
	 * Outputs an XHR response containing all positions the taxis.
	 * @xhrParam int status
     * @access public
     */
    public function dsPositionTaxisAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $status = $this->_getParam('status', -1);

        $backtrackRepo = $this->_entityManager->getRepository('Model\Backtrack');

        $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
        $taxis = $taxiRepo->findByStatus(Taxi::WITHOUT_CAREER);

        $data = array();
        foreach ($taxis as $taxi) {
        $route = $backtrackRepo->findLastPositionByTaxi($taxi);
            if ($route != NULL) {
                $row = array();
                $row['latitud'] = $route->getLatitud();
                $row['longitud'] = $route->getLongitud();
                $row['name'] = sprintf('Movil %d', $taxi->getNumber());
                $row['active'] = $taxi->getActive();
                $data[] = $row;
            }
        }

        $this->stdResponse = new stdClass();
        $this->stdResponse = $data;
        $this->_helper->json($this->stdResponse);
    }

    /**
     * Outputs an XHR response the position the taxi active.
     * @xhrParam int taxiId
     * @access public
     */
    public function dsPositionTaxiAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $taxiId = $this->_getParam('taxiId', 0);
        $taxi = $this->_entityManager->find('Model\Taxi', $taxiId);

        $backtrackRepo = $this->_entityManager->getRepository('Model\Backtrack');
        $route = $backtrackRepo->findLastPositionByTaxi($taxi);

        $data = NULL;
        if ($route != NULL) {
            $taxiRepo = $this->_entityManager->getRepository('Model\Taxi');
            $taxis = $taxiRepo->findByStatus(Taxi::WITHOUT_CAREER);
            foreach ($taxis as $taxiObjetc) {
                $taxiObjetc
                    ->setChanged(new DateTime('now'))
                    ->setActive(FALSE);

                $this->_entityManager->persist($taxiObjetc);
                $this->_entityManager->flush();
            }

            $taxi
                ->setChanged(new DateTime('now'))
                ->setActive(TRUE);

            $this->_entityManager->persist($taxi);
            $this->_entityManager->flush();

            $data = array();
            $data['latitud'] = $route->getLatitud();
            $data['longitud'] = $route->getLongitud();
            $data['name'] = sprintf('Movil %d', $taxi->getNumber());
            $data['active'] = (int)$taxi->getActive();
        }

        $this->stdResponse = new stdClass();
        $this->stdResponse = $data;
        $this->_helper->json($this->stdResponse);
    }
}