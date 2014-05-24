<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * @var Zend_View
     */
    private $view;

    /**
     * Init autoload the resources
     */
    public function _initAutoloadPackage() {
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->registerNamespace('Dis_');
    	$autoloader->registerNamespace('Doctrine');
    }

    /**
     * Init autoload the resources ZendX_JQuery_View_Helper
     */
    public function _initViewResource() {
    	$this->bootstrap('view');
    	$this->view = $this->getResource('view');

    	$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
    	$viewRenderer->setView($this->view);
    	Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    /**
     * Config zend navigation
     */
    public function _initNavigation() {
    	if ($this->view === null) {
    		return;
    	}

    	$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation.xml','navigation');
    	$navigation = new Zend_Navigation($config);
    	Zend_Registry::set('navigation', $navigation);
    	$this->view->navigation($navigation);
    }

    /**
     * Append scripts file to layout
     */
    public function _initHeadScriptAndStyle() {
    	if ($this->view === null) {
    		return;
    	}

    	// jquery core
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery.min.js','text/javascript');
    	//         $this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery/jquery-2.0.3.min.js','text/javascript');

    	// Jquery ui
    	//         $this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery-ui/jquery-ui.min.js','text/javascript');
    	//         $this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery-ui/jquery-ui-themes/themes/ui-lightness/jquery-ui.css");
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jquery-ui/1.9.2/jquery-ui.min.js','text/javascript');
    	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery-ui/jquery-ui-themes/themes/ui/jquery-ui.css");

    	// Jgrowl plugin jquery
    	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jgrowl/jquery.jgrowl.css");
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jgrowl/jquery.jgrowl.js','text/javascript');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jgrowl/Alert.js','text/javascript');
    	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/lib/jgrowl/FlashMessage.js','text/javascript');

    	// Datatables plugin jquery
    	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery-datatables/css/demo_table.css");
    	$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery-datatables/jquery.dataTables.min.js","text/javascript");
    	$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery-validate/jquery.validate.min.js","text/javascript");

    	// Alert Dialogs plugin jquery
    	$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.js","text/javascript");
    	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.css");

    	$this->view->headScript()->appendFile($this->view->baseUrl() . "/js/lib/jquery.alerts-1.1/jquery.alerts.js","text/javascript");

    	$this->view->doctype("HTML5");
    }

    /**
     * Register plugins
     */
    public function _initRegisterPlugin() {
    	$frontController = Zend_Controller_Front::getInstance();

    	// Register plugin acl
    	if (!$frontController->hasPlugin('Dis_Controller_Plugin_Acl')) {
    		$frontController->registerPlugin(new Dis_Controller_Plugin_Acl(), 1);
    	}

    	//     	// Register plugin for router, enable parameter lang to zend_translate
    	//     	if (!$frontController->hasPlugin('App_Controller_Plugin_RouterSetup')) {
    	//     		$frontController->registerPlugin(new App_Controller_Plugin_RouterSetup(), 2);
    		//     	}

		// Register plugion view setup
		if (!$frontController->hasPlugin('Dis_Controller_Plugin_ViewSetup')) {
			$frontController->registerPlugin(new Dis_Controller_Plugin_ViewSetup(), 2);
		}
	}
}