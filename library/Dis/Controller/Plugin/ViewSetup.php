<?php

require_once ('Zend/Controller/Plugin/Abstract.php');

class Dis_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract {

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::postDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		// if not the request has been dispatched
		if (!$request->isDispatched()) {
			return;
		}
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$view = $bootstrap->getResource('view');

		if ( $view->title ) {
			$view->headTitle($view->title);
			$view->headTitle()->setSeparator(' - ');
		}

		$moduleName = $request->getModuleName();
		if ($moduleName === "default") {
			//if module default use the template
			return;
		}

		$config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
		if (isset($config[$moduleName]['resources']['layout']['layout'])) {
            $layoutScript = $config[$moduleName]['resources']['layout']['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        }

        if (isset($config[$moduleName]['resources']['layout']['layoutPath'])) {
            $layoutPath = $config[$moduleName]['resources']['layout']['layoutPath'];
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath($layoutPath);
        }
	}

	/**
	 * switch navigation meta-user if module is um
	 * @see Zend_Controller_Plugin_Abstract::routeShutdown()
	 */
	public function routeShutdown(Zend_Controller_Request_Abstract $request) {
		// config the view of zend_navigation
		$this->configViewNavigation($request);
	}

	/**
	 * switch navigation meta-user if module is um
	 * @param Zend_Controller_Request_Abstract $request
	 */
	private function configViewNavigation(Zend_Controller_Request_Abstract $request) {
		$bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $navigation = Zend_Registry::get('navigation');

        $view = $bootstrap->getResource('view');

        $layout = $bootstrap->getResource('layout');

    	$layout->moduleName = $request->getModuleName();
		if ($request->getModuleName() == 'user') {
			$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation_user.xml','navigation');
			$navigation = new Zend_Navigation($config);
			$view->navigation($navigation);
			Zend_Registry::set('navigation', $navigation);
			$layout->activeUser = TRUE;
		} else {
			if ($request->getModuleName() == 'member') {
				$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation_member.xml','navigation');
				$navigation = new Zend_Navigation($config);
				$view->navigation($navigation);
				Zend_Registry::set('navigation', $navigation);
				$layout->activeProtocol = TRUE;

				$menuPrincipal = $navigation->findBy('id', $request->getParam('type'));
				$menuPrincipal->setActive(TRUE);
			} else {
				if ($request->getModuleName() == 'guest') {
					$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation.xml','navigation');
					$navigation = new Zend_Navigation($config);
					$view->navigation($navigation);
					Zend_Registry::set('navigation', $navigation);
				} else {
					if ($request->getModuleName() == 'admin') {
						$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation/navigation_admin.xml','navigation');
						$navigation = new Zend_Navigation($config);
						$view->navigation($navigation);
						Zend_Registry::set('navigation', $navigation);
						$layout->activeAdmin = FALSE;

						$menuPrincipal = $navigation->findBy('id', $request->getParam('type'));
						$menuPrincipal->setActive(TRUE);
					}
				}
			}
		}
	}
}