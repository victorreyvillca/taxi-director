<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_ClubPathfinder extends Zend_Form {

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

			->addElement('Text', 'name', array(
				'label' => _('Name'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'textbible', array(
				'label' => _('Text bible'),
				'required'   => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('TextArea', 'address', array(
				'label' => _('Address'),
				'cols' =>'40',
				'rows' =>'4',
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Select', 'church', array(
				'label' => _('Church'),
				'required' => TRUE
			)
        );
	}

	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
					new \Zend_Form_Decorator_PrepareElements(),
					'ViewScript'
			)
		);
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Pathfinder/template/ClubPathfinderForm.phtml');
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