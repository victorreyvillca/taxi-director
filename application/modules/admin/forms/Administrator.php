<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Administrator extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

			->addElement('Hidden', 'id')

			->addElement('Text', 'firstName', array(
				'label' => _('Nombres'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'lastName', array(
				'label' => _('Apellidos'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'ci', array(
				'label' => _('Cedula de Identidad'),
				'required' => TRUE,
				'validators' => array(
					array('Digits', false)
				)
			))

			->addElement('Radio', 'sex', array(
				'label' => _('Genero'),
				'required' => TRUE
			))

			->addElement('Text', 'username', array(
				'label' => _('Nombre de Usuario'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Password', 'password', array(
				'label' => _('Contraseña'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Password', 'passwordConfirm', array(
                'label' => _('Confirmar Contraseña'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'email', array(
				'label' => _('Correo Electronico'),
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					'EmailAddress'
				)
			))

			->addElement('Text', 'phonemobil', array(
				'label' => _('Nro de Celular'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					array('Digits', false)
				)
			))

			->addElement('Text', 'phone', array(
				'label' => _('Nro de Telefono Fijo'),
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Select', 'role', array(
				'label' => _('Rol'),
				'required' => TRUE
			))
		;
	}

	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Administrator/template/AdministratorForm.phtml');
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @param string $source
	 * @return Zend_Form
	 */
	public function setSource($source) {
		$this->source = $source;
		return $this;
	}
}