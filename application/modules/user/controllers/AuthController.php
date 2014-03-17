<?php
/**
 * Controller for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class User_AuthController extends Dis_Controller_Action {

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		parent::init();
    }

	/**
	 * Redirects the method login
	 * @access public
	 */
	public function indexAction() {
		$this->view->error = $this->_getParam('error','0');
		$this->_forward('login');
	}

	/**
	 * Shows the form and verifies if the user is valid
	 * @access public
	 */
    public function loginAction() {
        $form = new User_Form_Login();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                if ($this->verify($formData)) {
                    $this->_helper->redirector('index', 'index', 'admin');
                } else {
                    $this->view->error = 1;
                }
            }
        }
//		$this->view->form = $form;
    }

    /**
     * Verifies if the username and password of the user are valid
     * @param array $values
     * @return boolean
     */
    private function verify($values) {
        $adapter = $this->getAuthAdapter();

        $adapter->setIdentity($values['username']);
        $adapter->setCredential(md5($values['password']));

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $data = $adapter->getResultRowObject(NULL, array('password', 'created'));

            $account = $this->_entityManager->find('Model\Account', (int)$data->id);
            $administratorRepo = $this->_entityManager->getRepository('Model\Administrator');
            $administrator = $administratorRepo->findByAccount($account);
            $data->id = $administrator->getId();

            $auth->getStorage()->write($data);

            $member = 'admin';
            $session = new Zend_Session_Namespace($member);
            $session->username = $user->username;
            $session->role = $user->role;

            // Receive Zend_Session_Namespace object (Meta is OK)
//	        require_once('Zend/Session/Namespace.php');
//	        $session = new Zend_Session_Namespace('Zend_Auth');
//	        // Set the time of user logged in 24 horas
//	        $session->setExpirationSeconds(24*3600);
//	        Zend_Session::rememberMe();
//
//            $authNamespace = new Zend_Session_Namespace('Cms_Auth');
//
//            $this->_helper->redirector('read','skill','admin',array('type'=>'qualification','level' => 'skill'));

        	return TRUE;
        } else {
        	return FALSE;
//        	switch ($result->getCode()) {
//		        case Zend_Auth_Result::FAILURE :
//		            $this->_messenger->addError(_('Ha ocurrido un error el servidor al validar el usuario, por favor intentelo de nuevo.'));
//		            break;
//		        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID :
//		            $this->_messenger->addError(_('The password is invalid.'));
//		            break;
//		        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND :
//		            $this->_messenger->addError(_("The user doesn't exist."));
//		            break;
//		        case Zend_Auth_Result::FAILURE_UNCATEGORIZED :
//		            $this->_messenger->addError(_('Ha ocurrido un error inesperado en el servidor, por favor consulte con su administrador.'));
//		            break;
//		        default :
//		        	$this->_messenger->addError(_('The user or password is invalid.'));
//		        	break;
//			}
        }
    }

    /**
     * Returns Zend_Auth_Adapter_DbTable that has the username and password of the users
     * @return Zend_Auth_Adapter_DbTable
     */
    private function getAuthAdapter() {
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('tblAccount')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');

        return $authAdapter;
    }

    /**
	 * Redirects the method login and cleans the identity
	 * @access public
	 */
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        $this->_helper->redirector('login', 'Auth', 'user');
    }
}