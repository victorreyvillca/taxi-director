<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_Driver extends Zend_Form {

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

			->addElement('TextArea', 'address', array(
                'label' => _('Direccion')
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Driver/template/DriverForm.phtml');
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