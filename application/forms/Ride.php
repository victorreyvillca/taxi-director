<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_SearchPassenger extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	public function init() {
		$this
			->setAttrib('id', 'formId')

			->addElement('Hidden', 'id')

			->addElement('Text', 'phone', array(
                'label' => _('Telefono'),
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'firstName', array(
				'label' => _('Nombres'),
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Select', 'label', array(
				'label' => _('Direccion'),
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'address', array(
                'label' => _('Descrpcion de la Direccion'),
                'cols' => 20,
                'rows' => 4,
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Ride/template/RideForm.phtml');
	}
}