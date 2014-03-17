<?php
/**
 * Validate user session.
 *
 * @category MOB
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Emini A/S
 * @license Proprietary
 * @package Dis
 */

class Dis_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
	    $auth = Zend_Auth::getInstance();

        if ($request->getModuleName() == 'default') {
            return TRUE;
        }
// 	    $acl = new My_Acl();
        $session = new Zend_Session_Namespace('admin');

        if($auth->hasIdentity() && $session->role = 'admin') {
            return TRUE;
        } else {//die('else');
            $request
                ->setModuleName('user')
                ->setControllerName('Auth')
            ;
            return FALSE;
        }
    }
}