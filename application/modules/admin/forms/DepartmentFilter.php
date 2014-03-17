<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_DepartmentFilter extends Zend_Form {

	public function init() {
		$this
			->setAttrib('id', 'formFilterId')

			->addElement('Select', 'countryFilter', array(
				'label' => _('Country')
			))

			->addElement('Text', 'nameFilter', array(
				'label' => _('Name Department')
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/News/template/DepartmentFilterForm.phtml');
	}
}