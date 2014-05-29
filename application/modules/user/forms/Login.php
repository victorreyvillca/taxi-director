<?php
/**
 * Form for TAXI DIRECTOR.
 *
 * @category Taxi
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class User_Form_Login extends Zend_Form {

    public function init() {

        $this->addElement('text', 'username', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', FALSE, array(3, 20)),
            ),
            'required' => TRUE,
            'attribs' => array('placeholder' => _('USUARIO'), 'class' => 'input-text'),
            'decorators' => array(
                array('ViewHelper'),
                array('Errors', array('class' => 'errorlist'))
            )
        ));

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(3, 20)),
            ),
            'required'  => TRUE,
            'attribs' => array('placeholder' => _('CLAVE'), 'class' => 'input-text'),
            'decorators' => array(
                    array('ViewHelper'),
                    array('Errors', array('class' => 'errorlist'))
            )
        ));

        $this->addElement('button', 'submit', array(
            'label' => _('INGRESE'),
            'type' => 'submit',
            'decorators' => array(
                array('ViewHelper')
            )
        ));
    }

    public function loadDefaultDecorators() {
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }
}