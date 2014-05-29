<?php

class IndexController extends Dis_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->_helper->redirector(NULL, NULL, 'admin');
    }
}