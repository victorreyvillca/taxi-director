<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Club extends Zend_Form {

	public function init() {
		$this
			->setAttrib('id', 'formId')

            ->addElement('Select', 'church', array(
                'label' => _('Iglesia'),
                'required' => TRUE
			))

			->addElement('Select', 'area', array(
                'label' => _('Area'),
                'required' => TRUE
			))

			->addElement('Text', 'name', array(
				'label' => _('Nombre del Club'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'description', array(
				'label' => _('Ubicacion'),
				'cols' =>'40',
				'rows' =>'4',
				'filters' => array(
					array('StringTrim')
				)
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Club/template/ClubForm.phtml');
	}
}