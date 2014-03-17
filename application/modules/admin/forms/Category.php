<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Category extends Zend_Form {

	public function init() {
		$this
			->setAttrib('id', 'formId')

			->addElement('Text', 'name', array(
				'label' => _('Nombre de Categoria'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'description', array(
				'label' => _('Descripcion'),
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Category/template/CategoryForm.phtml');
	}
}