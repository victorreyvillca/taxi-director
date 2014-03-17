<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class User_Form_Login extends Zend_Form {

    public function init() {

        $this->setName("login");
        $this->setMethod('post');
        $this->setAttrib('class', 'featured-inner object-lead');

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', FALSE, array(3, 20)),
            ),
            'required'  => TRUE,
            'label'     => _('Username:')
        ));

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(3, 20)),
            ),
            'required'  => true,
            'label'     => _('Password:')
        ));

        $this->addElement('button', 'buttonlogin', array(
            'type'      => 'submit',
            'required'  => false,
            'ignore'    => true,
            'label'     => _('Login'),
            'class'     => 'prominent'
        ));
    }

    public function loadDefaultDecorators() {
        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));
    }
}