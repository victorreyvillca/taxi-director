<?php
use Model\Account;
use Model\Administrator;

/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_AdministratorController extends Dis_Controller_Action {

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
        $formFilter->getElement('nameFilter')->setLabel(_('Nombre del Administrador'));
        $this->view->formFilter = $formFilter;
    }

    /**
     *
     * This action shows a form in create mode
     * @access public
     */
    public function addAction() {
        $this->_helper->layout()->disableLayout();

        $roleRepo = $this->_entityManager->getRepository('Model\Role');

        $form = new Admin_Form_Administrator();
        $form->getElement('sex')->setMultiOptions($this->getGenders());
        $form->getElement('role')->setMultiOptions($roleRepo->findAllArray());
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
            $roleRepo = $this->_entityManager->getRepository('Model\Role');

            $form = new Admin_Form_Administrator();
            $form->getElement('sex')->setMultiOptions($this->getGenders());
            $form->getElement('role')->setMultiOptions($roleRepo->findAllArray());

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $role = $this->_entityManager->find('Model\Role', (int)$formData['role']);

                $account = new Account();
                $account
                    ->setUsername($formData['username'])
                    ->setEmail($formData['email'])
                    ->setAccountType(1)
                    ->setPassword(md5($formData['password']))
                    ->setRole($role->getName())
                    ->setCreated(new DateTime('now'))
                    ->setState(TRUE)
                ;

                $this->_entityManager->persist($account);
                $this->_entityManager->flush();

                $administrator = new Administrator();
                $administrator
                    ->setDateOfBirth(new DateTime('now'))
                    ->setIdentityCard((int)$formData['ci'])
                    ->setFirstName($formData['firstName'])
                    ->setLastName($formData['lastName'])
                    ->setPhone($formData['phone'])
                    ->setPhonemobil($formData['phonemobil'])
                    ->setSex((int)$formData['sex'])
                    ->setAccount($account)
                    ->setRole($role)
                    ->setVisible(TRUE)
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

                $this->_helper->flashMessenger(array('success' => _('Administrador registrado')));
                $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'organization'));
            } else {
                $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'organization'));
            }
        } else {
            $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'organization'));
        }
    }

    /**
     * This action shows the form in update mode for Directive.
     * @access public
     */
    public function editAction() {
        $this->_helper->layout()->disableLayout();

        $roleRepo = $this->_entityManager->getRepository('Model\Role');

        $form = new Admin_Form_Administrator();
        $form->getElement('sex')->setMultiOptions($this->getGenders());
        $form->getElement('role')->setMultiOptions($roleRepo->findAllArray());
        $form->setAction($this->_helper->url('edit-save'));

        $id = $this->_getParam('id', 0);
        $administrator = $this->_entityManager->find('Model\Administrator', $id);
        if ($administrator != NULL) {
            $form->getElement('id')->setValue($administrator->getId());
            $form->getElement('firstName')->setValue($administrator->getFirstName());
            $form->getElement('lastName')->setValue($administrator->getLastName());
            $form->getElement('ci')->setValue($administrator->getIdentityCard());
            $form->getElement('sex')->setValue($administrator->getSex());
            $form->getElement('phonemobil')->setValue($administrator->getPhonemobil());
            $form->getElement('phone')->setValue($administrator->getPhone());
            $form->getElement('role')->setValue($administrator->getRole()->getId());

            $account = $administrator->getAccount();
            $form->getElement('username')->setValue($account->getUsername());
            $form->getElement('email')->setValue($account->getEmail());

            $dataVaultMapper = new Dis_Model_DataVaultMapper();
            $dataVault = $dataVaultMapper->find($administrator->getProfilePictureId());
            if ($dataVault != NULL && $dataVault->getBinary()) {
                $src = $this->_helper->url('profile-picture', 'Administrator', 'admin', array('id' => $dataVault->getId(), 'timestamp' => time()));
            } else {
                if ($administrator->getSex() == Model\Person::SEX_MALE) {
                    $src = '/image/profile/male_default.jpg';
                } elseif ($administrator->getSex() == Model\Person::SEX_FEMALE) {
                    $src = '/image/profile/female_default.jpg';
                }
            }
            $form->setSource($src);
        } else {
            $this->stdResponse->success = FALSE;
            $this->stdResponse->message = _("The requested record was not found.");
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
            $roleRepo = $this->_entityManager->getRepository('Model\Role');

            $form = new Admin_Form_Administrator();
            $form->getElement('password')->setRequired(FALSE);
            $form->getElement('passwordConfirm')->setRequired(FALSE);
            $form->getElement('sex')->setMultiOptions($this->getGenders());
            $form->getElement('role')->setMultiOptions($roleRepo->findAllArray());

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = $this->_getParam('id', 0);
                $administrator = $this->_entityManager->find('Model\Administrator', $id);
                if ($administrator != NULL) {
                    $role = $this->_entityManager->find('Model\Role', (int)$formData['role']);

                    $account = $administrator->getAccount();
                    $account
                        ->setUsername($formData['username'])
                        ->setPassword(md5($formData['password']))
                        ->setEmail($formData['email'])
                    ;

                    $administrator
                        ->setIdentityCard((int)$formData['ci'])
                        ->setFirstName($formData['firstName'])
                        ->setLastName($formData['lastName'])
                        ->setPhone($formData['phone'])
                        ->setPhonemobil($formData['phonemobil'])
                        ->setSex((int)$formData['sex'])
                        ->setAccount($account)
                        ->setRole($role)
                        ->setDateOfBirth(new DateTime('now'))
//                         ->setVisible(TRUE)
                        ->setChanged(new \DateTime('now'))
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

                    $this->_helper->flashMessenger(array('success' => _('Administrador editado')));
                    $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'pathfinder'));
                } else {
                    $this->_helper->flashMessenger(array('error' => _("Administrator do not found")));
                    $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'pathfinder'));
                }
            } else {
                $this->_helper->flashMessenger(array('error' => _('Error')));
                $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'pathfinder'));
            }
        } else {
            $this->_helper->redirector('index', 'Administrator', 'admin', array('type'=>'pathfinder'));
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
                $administrator = $this->_entityManager->find('Model\Administrator', $id);
                $administrator->setState(FALSE);

                $this->_entityManager->persist($administrator);
                $this->_entityManager->flush();
                $removeCount++;
            }
            $message = sprintf(ngettext('%d administrador eliminado.', '%d administradores eliminados.', $removeCount), $removeCount);

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
	public function readItemsAction() {
		$sortCol = $this->_getParam('iSortCol_0', 1);
		$sortDirection = $this->_getParam('sSortDir_0', 'asc');

		$filterParams['name'] = $this->_getParam('filter_name', NULL);
		$filters = $this->getFilters($filterParams);

		$start = $this->_getParam('iDisplayStart', 0);
		$limit = $this->_getParam('iDisplayLength', 10);
		$page = ($start + $limit) / $limit;

		$administratorRepo = $this->_entityManager->getRepository('Model\Administrator');
		$administrators = $administratorRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $administratorRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($administrators as $directive) {
			$changed = $directive->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $directive->getId();
			$row[] = $directive->getName();
			$row[] = $directive->getRole()->getName();
			$row[] = $directive->getPhonemobil();
			$row[] = $directive->getPhone();
			$row[] = $directive->getAccount()->getEmail();
			$row[] = $directive->getIdentityCard();
			$row[] = $directive->getCreated()->format('d.m.Y');
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
     *
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

    /**
     * Returns the genders of a person
     * @return array
     */
    private function getGenders() {
        return array(Model\Person::SEX_MALE => _('Masculino'), Model\Person::SEX_FEMALE => _('Femenino'));
    }
}