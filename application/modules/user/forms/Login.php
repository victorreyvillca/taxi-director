<?php
/**
 * Form for TAXI DIRECTOR.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class User_Form_Login extends Zend_Form {

    public function init() {

        $this->setMethod('post');

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', FALSE, array(3, 20)),
            ),
            'required'  => TRUE
        ));

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(3, 20)),
            ),
            'required'  => true
        ));

        $this->addElement('button', 'buttonlogin', array(
            'type'      => 'submit',
            'required'  => false,
            'ignore'    => true,
            'label'     => _('¿OLVIDASTE TU CLAVE?'),
            'class'     => 'button'
        ));
    }

    public function loadDefaultDecorators() {
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }
}