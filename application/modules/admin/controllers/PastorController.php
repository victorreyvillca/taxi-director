<?php
use Model\Pastor;
use Model\Person;
use Model\District;

/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca.orion@gmail.com>
 * @copyright Copyright (c) 2014 Leadersotf A/S
 * @license Proprietary
 */

class Admin_PastorController extends Dis_Controller_Action {

    /**
     * (non-PHPdoc)
     * @see App_Controller_Action::init()
     */
    public function init() {
    	parent::init();
    }

    /**
     * @access public
     */
    public function indexAction() {
        $formFilter = new Admin_Form_SearchFilter();
        $formFilter->getElement('nameFilter')->setLabel(_('Nombre del Pastor'));
        $this->view->formFilter = $formFilter;
    }

    /**
     *
     * This action shows a form in create mode
     * @access public
     */
    public function addAction() {
        $this->_helper->layout()->disableLayout();

        $departmentRepo = $this->_entityManager->getRepository('Model\Department');
        $districtRepo = $this->_entityManager->getRepository('Model\District');

        $form = new Admin_Form_Pastor();
        $form->getElement('sex')->setMultiOptions(Person::getGenderArray());
        $form->getElement('department')->setMultiOptions($departmentRepo->findAllArray());
        $form->getElement('district')->setMultiOptions($districtRepo->findAllArray());
        $form->setAction($this->_helper->url('add-save'));

        $src = '/image/profile/female_or_male_default.jpg';
        $form->setSource($src);

        $this->view->form = $form;
    }

    /**
     *
     * Creates a new Directive
     * @access public
     */
    public function addSaveAction() {
        if ($this->_request->isPost()) {
            $departmentRepo = $this->_entityManager->getRepository('Model\Department');
            $districtRepo = $this->_entityManager->getRepository('Model\District');

            $form = new Admin_Form_Pastor();
            $form->getElement('sex')->setMultiOptions(Person::getGenderArray());
            $form->getElement('department')->setMultiOptions($departmentRepo->findAllArray());
            $form->getElement('district')->setMultiOptions($districtRepo->findAllArray());

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $department = $this->_entityManager->find('Model\Department', (int)$formData['department']);
                $district = $this->_entityManager->find('Model\District', (int)$formData['district']);

                $administrator = new Pastor();
                $administrator
                    ->setDepartment($department)
                    ->setDistrict($district)
                    ->setDateOfBirth(new DateTime('now'))
                    ->setFirstName($formData['firstName'])
                    ->setLastName($formData['lastName'])
                    ->setIdentityCard(10)
                    ->setPhone($formData['phone'])
                    ->setPhonemobil($formData['phonemobil'])
                    ->setSex((int)$formData['sex'])
                    ->setCreated(new DateTime('now'))
                    ->setState(TRUE)
                ;

                if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                    if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
                        $fh = fopen($_FILES['file']['tmp_name'], 'r');
                        $binary = fread($fh, filesize($_FILES['file']['tmp_name']));
                        fclose($fh);

                        $mimeType = $_FILES['file']['type'];
                        $fileName = $_FILES['file']['name'];

                        $dataVaultMapper = new Dis_Model_DataVaultMapper();
                        $dataVault = new Dis_Model_DataVault();
                        $dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                        $dataVaultMapper->save($dataVault);

                        $administrator->setProfilePictureId($dataVault->getId());
                    }
                }

                $this->_entityManager->persist($administrator);
                $this->_entityManager->flush();

                $this->_helper->flashMessenger(array('success' => _('Pastor registrado')));
                $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
            } else {
                $this->_helper->flashMessenger(array('success' => _('Error Pastor NO registrado')));
                $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
            }
        } else {
            $this->_helper->flashMessenger(array('success' => _('Error Pastor NO registrado')));
            $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
        }
    }

    /**
     * This action shows the form in update mode for Directive.
     * @access public
     */
    public function editAction() {
        $this->_helper->layout()->disableLayout();

        $departmentRepo = $this->_entityManager->getRepository('Model\Department');
        $districtRepo = $this->_entityManager->getRepository('Model\District');

        $form = new Admin_Form_Pastor();
        $form->getElement('sex')->setMultiOptions(Person::getGenderArray());
        $form->getElement('department')->setMultiOptions($departmentRepo->findAllArray());
        $form->getElement('district')->setMultiOptions($districtRepo->findAllArray());
        $form->setAction($this->_helper->url('edit-save'));

        $id = $this->_getParam('id', 0);
        $pastor = $this->_entityManager->find('Model\Pastor', $id);
        if ($pastor != NULL) {
            $form->getElement('id')->setValue($pastor->getId());
            $form->getElement('firstName')->setValue($pastor->getFirstName());
            $form->getElement('lastName')->setValue($pastor->getLastName());
            $form->getElement('ci')->setValue($pastor->getIdentityCard());
            $form->getElement('sex')->setValue($pastor->getSex());
            $form->getElement('phonemobil')->setValue($pastor->getPhonemobil());
            $form->getElement('phone')->setValue($pastor->getPhone());
            $form->getElement('department')->setValue($pastor->getDepartment()->getId());
            $form->getElement('district')->setValue($pastor->getDistrict()->getId());

            $dataVaultMapper = new Dis_Model_DataVaultMapper();
            $dataVault = $dataVaultMapper->find($pastor->getProfilePictureId());
            if ($dataVault != NULL && $dataVault->getBinary()) {
                $src = $this->_helper->url('profile-picture', 'Administrator', 'admin', array('id' => $dataVault->getId(), 'timestamp' => time()));
            } else {
                if ($pastor->getSex() == Model\Person::SEX_MALE) {
                    $src = '/image/profile/male_default.jpg';
                } elseif ($pastor->getSex() == Model\Person::SEX_FEMALE) {
                    $src = '/image/profile/female_default.jpg';
                }
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
     * Updates a Directive of the club pathfinders
     * @access public
     */
    public function editSaveAction() {
        if ($this->_request->isPost()) {
            $departmentRepo = $this->_entityManager->getRepository('Model\Department');
            $districtRepo = $this->_entityManager->getRepository('Model\District');

            $form = new Admin_Form_Pastor();
            $form->getElement('sex')->setMultiOptions(Person::getGenderArray());
            $form->getElement('department')->setMultiOptions($departmentRepo->findAllArray());
            $form->getElement('district')->setMultiOptions($districtRepo->findAllArray());

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = $this->_getParam('id', 0);
                $administrator = $this->_entityManager->find('Model\Pastor', $id);
                if ($administrator != NULL) {
                    $department = $this->_entityManager->find('Model\Department', (int)$formData['department']);
                    $district = $this->_entityManager->find('Model\District', (int)$formData['district']);

                    $administrator
                        ->setIdentityCard((int)$formData['ci'])
                        ->setFirstName($formData['firstName'])
                        ->setLastName($formData['lastName'])
                        ->setPhone($formData['phone'])
                        ->setPhonemobil($formData['phonemobil'])
                        ->setSex((int)$formData['sex'])
                        ->setDepartment($department)
                        ->setDistrict($district)
                        ->setDateOfBirth(new DateTime('now'))
                        ->setChanged(new DateTime('now'))
                    ;

                    if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
                        if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
                            $fh = fopen($_FILES['file']['tmp_name'], 'r');
                            $binary = fread($fh, filesize($_FILES['file']['tmp_name']));
                            fclose($fh);

                            $mimeType = $_FILES['file']['type'];
                            $fileName = $_FILES['file']['name'];

                            $dataVaultMapper = new Dis_Model_DataVaultMapper();
                            if ($administrator->getProfilePictureId() != NULL) {// if it has image profile update
                                $dataVault = $dataVaultMapper->find($administrator->getProfilePictureId(), FALSE);
                                $dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                                $dataVaultMapper->update($administrator->getProfilePictureId(), $dataVault);
                            } elseif ($administrator->getProfilePictureId() == NULL) {// if it don't have image profile create
                                $dataVault = new Dis_Model_DataVault();
                                $dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
                                $dataVaultMapper->save($dataVault);

                                $administrator->setProfilePictureId($dataVault->getId());
                            }
                        }
                    }

                    $this->_entityManager->persist($administrator);
                    $this->_entityManager->flush();

                    $this->_helper->flashMessenger(array('success' => _('Pastor registrador y editado')));
                    $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
                } else {
                    $this->_helper->flashMessenger(array('error' => _('Pastor no encotrado')));
                    $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
                }
            } else {
                $this->_helper->flashMessenger(array('error' => _('Error al editar el Pastor')));
                $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
            }
        } else {
            $this->_helper->flashMessenger(array('error' => _('Error al editar el Pastor')));
            $this->_helper->redirector('index', 'Pastor', 'admin', array('type'=>'organization'));
        }
    }

    /**
     * Deletes administrators
     * @access public
     */
    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $itemIds = $this->_getParam('itemIds', array());
        if (!empty($itemIds) ) {
            $removeCount = 0;
            foreach ($itemIds as $id) {
                $administrator = $this->_entityManager->find('Model\Pastor', $id);
                $administrator
                    ->setState(FALSE)
                    ->setChanged(new \DateTime('now'));

                $this->_entityManager->persist($administrator);
                $this->_entityManager->flush();
                $removeCount++;
            }
            $message = sprintf(ngettext('%d pastor eliminado.', '%d pastores eliminados.', $removeCount), $removeCount);

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
	public function dsPastorEntriesAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['name'] = $this->_getParam('filter_name', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$pastorRepo = $this->_entityManager->getRepository('Model\Pastor');
		$pastores = $pastorRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pastorRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($pastores as $pastor) {
			$changed = $pastor->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $pastor->getId();
			$row[] = $pastor->getName();
			$row[] = $pastor->getPhonemobil();
			$row[] = $pastor->getPhone();
			$row[] = $pastor->getDistrict()->getName();
			$row[] = '';
			$row[] = $pastor->getIdentityCard();
			$row[] = $pastor->getCreated()->format('d.m.Y');
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
     * Outputs an XHR response, loads the first names of the pastores.
     */
    public function autocompleteAction() {
        $filterParams['name'] = $this->_getParam('name_auto', NULL);
        $filters = $this->getFilters($filterParams);

        $directiveRepo = $this->_entityManager->getRepository('Model\Pastor');
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
            $filters[] = array('field' => 'firstName', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
        }

        return $filters;
    }
}