<?php

require_once 'Zend/Loader/PluginLoader.php';
require_once 'Zend/Controller/Action/Helper/FlashMessenger.php';

/**
 * The messenger action helper.
 *
 * @package App
 */
class Dis_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_FlashMessenger {

	/**
	 * @var Zend_Loader_PluginLoader
	 */
	public $pluginLoader;
	const ERROR = 'error';
	const WARNING = 'warning';
	const NOTICE = 'notice';
	const SUCCESS  = 'success';

	protected $_namespace = 'Application';

	/**
	 * Constructor: initialize plugin loader
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->pluginLoader = new Zend_Loader_PluginLoader ();
	}

	/**
	 * Add error message
	 *
	 * @param string $message
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	public function addError($message, $class = null, $method = null) {
		return $this->_addMessage($message, self::ERROR, $class, $method);
	}

	/**
	 * Add success message
	 *
	 * @param string $message
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	public function addSuccess($message, $class = null, $method = null) {
		return $this->_addMessage($message, self::SUCCESS, $class, $method);
	}

	/**
	 * Add warning message
	 *
	 * @param string $message
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	public function addWarning($message, $class = null, $method = null) {
		return $this->_addMessage($message, self::WARNING, $class, $method);
	}

	/**
	 * Add notice message
	 *
	 * @param string $message
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	public function addNotice($message, $class = null, $method = null) {
		return $this->_addMessage($message, self::NOTICE, $class, $method);
	}

	/**
	 * Add message to the session according to type
	 *
	 * @param unknown_type $message
	 * @param unknown_type $type
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	protected function _addMessage($message, $type, $class = null, $method = null) {
		if (self::$_messageAdded === false) {
			//establecemos cuantas paginas debe saltar para expirar la session_namespace
			self::$_session->setExpirationHops(1,null,false);
			//self::$_session->setExpirationSeconds(15);
		}
		if (!is_array(self::$_session->{$this->_namespace})) {
			self::$_session->{$this->_namespace}[$type] = array();
		}
		self::$_session->{$this->_namespace}[$type][] = $this->_factory($message,$type,$class,$method);

		return $this;
	}

	/**
	 * format stdClass
	 *
	 * @param string $message
	 * @param unknown_type $type
	 * @param unknown_type $class
	 * @param unknown_type $method
	 */
	protected function _factory($message, $type, $class = null, $method = null) {
		$messg = new stdClass();
		$messg->message = $message;
		$messg->type = $type;
		$messg->class = $class;
		$messg->method = $method;
		return $messg;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action_Helper_FlashMessenger::getMessages()
	 */
	public function getMessages($type = null) {
		if ($type === null) {
			return parent::getMessages();
		}
		if (isset(self::$_messages[$this->_namespace][$type])) {
			return self::$_messages[$this->_namespace][$type];
		}
		return array();
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action_Helper_FlashMessenger::getCurrentMessages()
	 */
	public function getCurrentMessages($type = null) {
		if ($type === null) {
			return parent::getCurrentMessages();
		}
		if (isset(self::$_session->{$this->_namespace}[$type])) {
			return self::$_session->{$this->_namespace}[$type];
		}
		return array();
	}

	/**
     * hasMessages() - Wether a specific namespace has messages
     *
     * @return boolean
     */
    public function hasCurrentMessages($type) {
    	if ($type === null) {
			return parent::hasCurrentMessages();
		}
        return isset(self::$_session->{$this->_namespace}[$type]);
    }

    /**
     * Si $type es vacio entonces devuelve la cantidad de messages de
     * todos los namespace, caso contrario solo la cantidad
     * del namespace especificado.
     *
     * @param string $type
     * @return int
     */
    public function countCurrentMessages($type = "") {
    	switch ($type) {
    		case self::ERROR:
    			if ($this->hasCurrentMessages(self::ERROR)) {
		            $amount += count($this->getCurrentMessages($type));
		        }
    			break;
    		case self::WARNING:
    			if ($this->hasCurrentMessages(self::WARNING)) {
		            $amount += count($this->getCurrentMessages($type));
		        }
    			break;
    		case self::NOTICE:
    			if ($this->hasCurrentMessages(self::NOTICE)) {
		            $amount += count($this->getCurrentMessages($type));
		        }
    			break;
    		case self::SUCCESS:
    			if ($this->hasCurrentMessages(self::SUCCESS)) {
		            $amount += count($this->getCurrentMessages($type));
		        }
    			break;
    		default:
		    	if ($this->hasCurrentMessages(self::ERROR)) {
		            $amount += count($this->getCurrentMessages(self::ERROR));
		        }
    			if ($this->hasCurrentMessages(self::WARNING)) {
		            $amount += count($this->getCurrentMessages(self::WARNING));
		        }
    			if ($this->hasCurrentMessages(self::NOTICE)) {
		            $amount += count($this->getCurrentMessages(self::NOTICE));
		        }
    			if ($this->hasCurrentMessages(self::SUCCESS)) {
		            $amount += count($this->getCurrentMessages(self::SUCCESS));
		        }
    			break;
    	}
    	return $amount;
    }
}
