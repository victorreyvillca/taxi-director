<?php
/**
 * App's version of Zend_Controller_Action, implementing custom functionality.
 * All application controlers subclass this rather than Zend's version directly.
 *
 * @package Dis
 * @subpackage Controller
 */

class Dis_Controller_Action extends Zend_Controller_Action {

	/**
	 * A variable to hold an identify of the user
	 *
	 * Will be !false if there is a valid identity
	 *
	 * @var object An instance of the user's identity or false
	 */
	protected $_identity = false;

	/**
	* A variable to hold an instance of the bootstrap object
	*
	* @var object An instance of the bootstrap object
	*/
	protected $_bootstrap;

	/**
	* A variable to hold an instance of the logger object
	*
	* @var Dis_Log An instance of the logger object
	*/
	protected $_logger = null;

	/**
	 * A variable to hold the mailer
	 *
	 * @var object An instance of the mailer
	 */
	protected $_mailer = null;

	/**
	 * A variable to hold the session namespace
	 *
	 * @var object An instance of the session namespace
	 */
	protected $_session = null;

	/**
	 * A variable to hold the messenger
	 *
	 * @var Dis_Controller_Action_Helper_Messenger
	 */
	protected $_messenger = null;

	/**
	 * A variable to hold the Doctrine manager
	 *
	 * @var Doctrine\ORM\EntityManager An instance of the entity manager
	 */
	protected $_entityManager = null;

	/**
	 * @var array an array representation of the application.ini
	 */
	protected $_options = null;

	/**
	 * set active for current module and action
	 */
	public function init() {
		parent::init();
	}

	/**
	 * Override the Zend_Controller_Action's constructor (which is called
	 * at the very beginning of this function anyway).
	 *
	 * @param object $request See Parent class constructor
	 * @param object $response See Parent class constructor
	 * @param object $invokeArgs See Parent class constructor
	 */
	public function __construct(Zend_Controller_Request_Abstract  $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = null) {

		// call the parent's version where all the Zend magic happens
		parent::__construct( $request, $response, $invokeArgs );

		// get the bootstrap object
		$this->_bootstrap = $invokeArgs['bootstrap'];

		// load up the options
// 		$this->_options = $this->_bootstrap->getOptions();

		// and from the bootstrap, we can get other resources:
		$this->_logger   = $this->_bootstrap->getResource( 'logger' );
		$this->_session  = $this->_bootstrap->getResource( 'namespace' );

		//$this->_messenger  = Zend_Controller_Action_HelperBroker::getStaticHelper('messenger');
		$this->_entityManager = $this->_bootstrap->getResource('doctrine');
		$this->_identity = Zend_Auth::getInstance()->getIdentity();

		try {
			$this->view->session     = $this->_session;
// 			$this->view->options     = $this->_options;
			$this->view->auth        = Zend_Auth::getInstance();
			$this->view->hasIdentity = Zend_Auth::getInstance()->hasIdentity();
			$this->view->identity    = $this->_identity;
		} catch ( Zend_Exception $e ) {
			echo _( 'Caught exception' ) . ': ' . get_class( $e ) . "\n";
			echo _( 'Message' ) . ': ' . $e->getMessage() . "\n";
		}

		$this->view->addHelperPath( 'Dis/View/Helper', 'Dis_View_Helper' );

		$this->view->controller = $this->getRequest()->getParam( 'controller' );
		$this->view->action     = $this->getRequest()->getParam( 'action'     );

		// if we issue a redirect, we want it to exit immediatly
		$this->getHelper( 'Redirector' )->setExit( true );
	}

	/**
	 * A utility method to get a named resource.
	 *
	 * @param string $resource
	 */
	public function getResource( $resource ) {
		return $this->_bootstrap->getResource( $resource );
	}

	/**
	 * Returns the logger object
	 *
	 * @return Dis_Log object
	 */
	public function getLogger() {
		return $this->_logger;
	}

	/**
	 * Returns the identify object for the Zend_Auth session.
	 *
	 * Will be !false if there is a valid identity
	 *
	 * @return array The Zend_auth identity object or false
	 */
	protected function getIdentity() {
		return $this->_identity;
	}

	/**
	 * Get the namespace (session).
	 *
	 * @return Zend_Session_Namespace The session namespace.
	 */
	protected function getSession() {
		return $this->_session;
	}
}