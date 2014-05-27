<?php
/**
 * Form for TAXI DIRECTOR.
 *
 * @category Taxi
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

			->addElement('Textarea', 'address', array(
                'label' => _('Descrpcion de la Direccion'),
                'cols' => 20,
                'rows' => 4,
			))

            ->addElement('Button', 'searchButton', array(
                'label' => _('Buscar'),
                'attribs' => array('onclick' => 'search()', 'class' => 'button')
			))

            ->addElement('Button', 'changeNameButton', array(
            		'label' => _('Cambiar'),
            		'attribs' => array('onclick' => 'changeName()', 'class' => 'button')
            ))

            ->addElement('Button', 'deleteLabelButton', array(
            		'label' => _('Borrar'),
            		'attribs' => array('onclick' => 'deleteLabel()', 'class' => 'button')
            ))

             ->addElement('Button', 'changeAddressButton', array(
            		'label' => _('Cambiar'),
            		'attribs' => array('onclick' => 'changeAddress()', 'class' => 'button')
            ));
		;
	}

	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);
		$this->getDecorator('ViewScript')->setOption('viewScript', '/passenger/template/SearchPassengerForm.phtml');
	}
}