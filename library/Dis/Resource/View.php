<?php
/**
 * The Zend View resource.
 * @package Dis
 * @subpackage Resource
 * @category Dis
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_Resource_View extends Zend_Application_Resource_ResourceAbstract {

	/**
	 * @var Zend_View
	 */
	protected $_view;

	/**
	 * Returns view so bootstrap will store it in the registry
	 * (non-PHPdoc)
	 * @see Zend_Application_Resource_Resource::init()
	 */
	public function init() {
		return $this->getView();
	}

	/**
	 * Get session configuration options from the application.ini file
	 * @return Zend_View
	 */
	public function getView() {
		$options = $this->getOptions();

		if ($options['enabled']) {
			if (null === $this->_view) {
				// Initialize view
				$view = new Zend_View();
				$view->doctype( $options['doctype'] );
				$view->headTitle( $options['title'] );
				$view->headMeta()->appendHttpEquiv('Content-Type',$options['content-type']);
				$view->addHelperPath(APPLICATION_PATH . '/../library/Dis/View/Helper','Dis_View_Helper');
				// Add it to the ViewRenderer
				$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper( 'ViewRenderer' );
				$viewRenderer->setView( $view );

				$this->_view = $view;
			}
			return $this->_view;
		}
	}
}