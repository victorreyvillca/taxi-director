<?php
/**
 * Form for TAXI DIRECTOR.
 *
 * @category Taxi
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_Recovery extends Zend_Form {

    public function init() {

        $this->addElement('text', 'email', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                'EmailAddress'
            ),
            'required' => TRUE,
            'attribs' => array('placeholder' => _('EMAIL'), 'class' => 'input-text'),
            'decorators' => array(
                array('ViewHelper'),
                array('Errors', array('class' => 'errorlist'))
            )
        ));

        $this->addElement('button', 'submit', array(
            'label' => _('ENVIAR'),
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