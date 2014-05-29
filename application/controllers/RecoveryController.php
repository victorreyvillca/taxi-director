<?php
/**
 * Controller for Taxi Director.
 *
 * @category Taxi
 * @author Victor Villca <victor.villca.v@gmail.com>
 * @copyright Copyright (c) 2014 LeaderSoft A/S
 * @license Proprietary
 */

class RecoveryController extends Dis_Controller_Action {

    public function indexAction() {
        $form = new Dis_Form_Recovery();

        $this->view->form = $form;
    }
}
