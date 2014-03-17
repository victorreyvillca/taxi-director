<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_SearchFilter extends Zend_Form {
	
	public function init() {
		$this
			->setAttrib('id', 'formFilterId')
						
			->addElement('Text', 'nameFilter', array(
				'label' => _('Name')
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/template/SearchFilterForm.phtml');
	}
}