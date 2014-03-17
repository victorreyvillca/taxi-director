<?php
/**
 * Controller for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_PathfinderController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see App_Controller_Action::init()
	 */
	public function init() {
		parent::init();
	}

	/**
	 * This action shows a paginated list of club pathfinders
	 * @access public
	 */
	public function indexAction() {
		$formFilter = new Admin_Form_SearchFilter();
		$formFilter->getElement('nameFilter')->setLabel(_("Name Club Pathfinder"));
		$this->view->formFilter = $formFilter;

		$p = $this->_entityManager->getRepository('Model\ClubPathfinder');
// 		var_dump($p->findByCriteria());
	}

	/**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function createAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_ClubPathfinder();
		$form->getElement('church')->setMultiOptions($this->getChurches());
		$form->setAction($this->_helper->url('save'));

		$src = '/image/profile/smile.jpg';
		$form->setSource($src);

		$this->view->form = $form;
	}

    /**
	 * This action shows a form in create mode
	 * @access public
	 */
	public function addAction() {
		$this->_helper->layout()->disableLayout();

		$form = new Admin_Form_ClubPathfinder();
		$form->getElement('church')->setMultiOptions($this->getChurches());
		$form->setAction($this->_helper->url('save'));

		$src = '/image/profile/smile.jpg';
		$form->setSource($src);

		$this->view->form = $form;
	}

	/**
	 * Creates a new Club Pathfinder
	 * @access public
	 */
	public function saveAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_ClubPathfinder();
			$form->getElement('church')->setMultiOptions($this->getChurches());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$pathfinderRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
				if (!$pathfinderRepo->verifyExistName($formData['name'])) {
					$church = $this->_entityManager->find('Model\Church', (int)$formData['church']);

					$club = new Model\ClubPathfinder();
					$club->setName($formData['name'])
						->setTextbible($formData['textbible'])
						->setAddress($formData['address'])
						->setCreated(new DateTime('now'))
						->setState(TRUE)
						->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id)
						->setChurch($church);

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
// 							var_dump($dataVault); exit;
// 							var_dump($dataVault); exit;

							$club->setLogoId($dataVault->getId());
						}
					}

					$this->_entityManager->persist($club);
					$this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _('Club Pathfinder saved')));
					$this->_helper->redirector('index', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
				} else {
					$this->_helper->redirector('index', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
				}
			} else {
				$this->_helper->redirector('index', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('index', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 * This action shows the form in update mode for Club Pathfinder.
	 * @access public
	 */
	public function updateAction() {
		$this->_helper->layout()->disableLayout();
		$form = new Admin_Form_ClubPathfinder();
		$form->getElement('church')->setMultiOptions($this->getChurches());
		$form->setAction($this->_helper->url('edit'));

		$id = $this->_getParam('id', 0);
		$club = $this->_entityManager->find('Model\ClubPathfinder', $id);
		if ($club != NULL) {
			$form->getElement('id')->setValue($club->getId());
			$form->getElement('name')->setValue($club->getName());
			$form->getElement('textbible')->setValue($club->getTextbible());
			$form->getElement('address')->setValue($club->getAddress());
			$form->getElement('church')->setValue($club->getChurch()->getId());

			$imageMapper = new Model_ImageDataVaultMapper();
			$imageLogo = $imageMapper->find($club->getLogoId());

			if ($imageLogo != NULL && $imageLogo->getBinary()) {
				$src = $this->_helper->url('profile-logo', NULL, NULL, array('id' => $imageLogo->getId(), 'timestamp' => time()));
			} else {
				$src = '/image/profile/smile.jpg';
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
	 * Updates a Club Pathfinder
	 * @access public
	 */
	public function editAction() {
		if ($this->_request->isPost()) {
			$form = new Admin_Form_ClubPathfinder();
			$form->getElement('church')->setMultiOptions($this->getChurches());

			$formData = $this->getRequest()->getPost();

			if ($form->isValid($formData)) {
				$id = $this->_getParam('id', 0);
				$club = $this->_entityManager->find('Model\ClubPathfinder', $id);
				$pathfinderRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
				if (!$pathfinderRepo->verifyExistName($formData['name']) || $pathfinderRepo->verifyExistIdAndName($id, $formData['name'])) {
					$church = $this->_entityManager->find('Model\Church', (int)$formData['church']);

					$club->setName($formData['name'])
						->setTextbible($formData['textbible'])
						->setAddress($formData['address'])
						->setChanged(new DateTime('now'))
						->setChangedBy(Zend_Auth::getInstance()->getIdentity()->id)
						->setChurch($church);

					if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
						if ($_FILES['file']['error'] == UPLOAD_ERR_OK) {
							$fh = fopen($_FILES['file']['tmp_name'], 'r');
							$binary = fread($fh, filesize($_FILES['file']['tmp_name']));
							fclose($fh);

							$mimeType = $_FILES['file']['type'];
							$fileName = $_FILES['file']['name'];

							$dataVaultMapper = new Model_ImageDataVaultMapper();

							if ($club->getLogoId() != NULL) {// if it has image profile update
								$dataVault = $dataVaultMapper->find($club->getLogoId(), FALSE);
								$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
								$dataVaultMapper->update($club->getLogoId(), $dataVault);
							} elseif ($club->getLogoId() == NULL) {// if it don't have image profile create
								$dataVault = new Model_ImageDataVault();
								$dataVault->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);
								$dataVaultMapper->save($dataVault);

								$club->setLogoId($dataVault->getId());
							}
						}
					}

					$this->_entityManager->persist($club);
					$this->_entityManager->flush();

					$this->_helper->flashMessenger(array('success' => _("Club Pathfinder updated")));
					$this->_helper->redirector('read', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
				} else {
					$this->_helper->redirector('read', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
				}
			} else {
				$this->_helper->redirector('read', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
			}
		} else {
			$this->_helper->redirector('read', 'Pathfinder', 'admin', array('type'=>'pathfinder'));
		}
	}

	/**
	 * Deletes clubs pathfinders
	 * @access public
	 * @internal
	 * 1) Gets the model ClubPathfinder
	 * 2) Validates the existance of dependencies
	 * 3) Changes the state field or records to delete
	 */
	public function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$itemIds = $this->_getParam('itemIds', array());
		if (!empty($itemIds) ) {
			$removeCount = 0;
			foreach ($itemIds as $id) {
				$club = $this->_entityManager->find('Model\ClubPathfinder', $id);
				$club->setState(FALSE);

				$this->_entityManager->persist($club);
				$this->_entityManager->flush();
				$removeCount++;
			}
			$message = sprintf(ngettext('%d club pathfinder removed.', '%d club pathfinders removed.', $removeCount), $removeCount);

			$this->stdResponse->success = TRUE;
			$this->stdResponse->message = _($message);
		} else {
			$this->stdResponse->success = FALSE;
			$this->stdResponse->message = _("Data submitted is empty.");
		}
		// sends response to client
		$this->_helper->json($this->stdResponse);
	}

	/**
	 * Outputs an XHR response containing all entries in club pathfinders.
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


		$pathfinderRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		$pathfinders = $pathfinderRepo->findByCriteria($filters, $limit, $start, $sortCol, $sortDirection);
		$total = $pathfinderRepo->getTotalCount($filters);

		$posRecord = $start+1;
		$data = array();
		foreach ($pathfinders as $pathfinder) {
			$changed = $pathfinder->getChanged();
			if ($changed != NULL) {
				$changed = $changed->format('d.m.Y');
			}

			$row = array();
			$row[] = $pathfinder->getId();
			$row[] = $pathfinder->getName();
			$row[] = $pathfinder->getTextbible();
			$row[] = $pathfinder->getAddress();
			$row[] = $pathfinder->getCreated()->format('d.m.Y');
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
	 * Outputs an XHR response, loads the first names of clubs pathfinders.
	 */
	public function autocompleteAction() {
		$filterParams['name'] = $this->_getParam('name_auto', NULL);
		$filters = $this->getFilters($filterParams);

		$clubRepo = $this->_entityManager->getRepository('Model\ClubPathfinder');
		$clubs = $clubRepo->findByCriteria($filters);

		$data = array();
		foreach ($clubs as $club) {
			$data[] = $club->getName();
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
			$filters[] = array('field' => 'name', 'filter' => '%'.$filterParams['name'].'%', 'operator' => 'LIKE');
		}

		return $filters;
	}

	/**
	 * (test)
	 */
	public function uploadAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$target_path = "image/upload/";
		// Set the new path with the file name
		$target_path = $target_path . basename( $_FILES['myfile']['name']);
		// Move the file to the upload folder
		if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
			// print the new image path in the page, and this will recevie the javascripts 'response' variable
			echo "/".$target_path;
		} else{
			// Set default the image path if any error in upload.
			echo "default.jpg";
		}
	}

	/**
	 * Uploads the profile logo (test)
	 * @access public
	 */
	public function uploadLogoAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$memberFileMapper = new Model_MemberFileMapper();

		$fh = fopen($_FILES['myfile']['tmp_name'], 'r');
		$binary = fread($fh, filesize($_FILES['myfile']['tmp_name']));
		fclose($fh);

		$mimeType = $_FILES['myfile']['type'];
		$fileName = $_FILES['myfile']['name'];

		$imageLogo = new Model_ImageDataVault();
		$imageLogo->setFilename($fileName)->setMimeType($mimeType)->setBinary($binary);

		$imageDataVaultMapper = new Model_ImageDataVaultMapper();
		$imageDataVaultMapper->save($imageLogo);

		if ($imageLogo->getBinary()) {
			echo $this->_helper->url('profile-logo', NULL, NULL, array('id' => $imageLogo->getId(), 'timestamp' => time()));
		} else {
			echo "/js/lib/ajax-upload/ep.jpg";
		}
	}

	/**
	 *
	 * Sends the binary file vault to the user agent.
	 * @return void
	 */
	public function profileLogoAction() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(TRUE);

		$id = (int)$this->_getParam('id', 0);

		$dataVaultMapper = new Model_ImageDataVaultMapper();
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
	 *
	 * Returns the ids and names of churches
	 * @return array
	 */
	private function getChurches() {
		$churchRepo = $this->_entityManager->getRepository('Model\Church');
		return $churchRepo->findAllName();
	}
}