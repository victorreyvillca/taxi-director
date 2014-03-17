<?php

class Admin_ErrorController extends Zend_Controller_Action {

	public function errorAction() {
		$errors = $this->_getParam('error_handler');

        if (!$errors) {
            $this->view->message = 'You have privilegio';
            return;
        }
	}
}