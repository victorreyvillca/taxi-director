<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_Taxi extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	/**
	 * @var string
	 */
	private $sourceTaxi;

    /**
     * @var int
     */
	private $tabIndex = 1;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

			->addElement('Hidden', 'driverId')
			->addElement('Hidden', 'taxiId')

			->addElement('Text', 'firstName', array(
				'label' => _('Nombres'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'lastName', array(
				'label' => _('Apellidos'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				)
			))

			->addElement('Text', 'ci', array(
				'label' => _('Cedula de Identidad'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'validators' => array(
                    array('Digits', false)
				)
			))

			->addElement('TextArea', 'address', array(
				'label' => _('Direccion'),
                'tabIndex' => $this->tabIndex++,
                'cols' => '20',
                'rows' => '3',
			))

			->addElement('Text', 'name', array(
				'label' => _('Nombre movil'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'mark', array(
				'label' => _('Marca'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'plaque', array(
				'label' => _('Placa'),
                'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				)
			))

			->addElement('Text', 'typeMark', array(
				'label' => _('Tipo'),
                'tabIndex' => $this->tabIndex++,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'model', array(
                'label' => _('Modelo'),
                'tabIndex' => $this->tabIndex++,
				'filters' => array(
                    array('StringTrim')
                ),
				'validators' => array(
				array('Digits', false)
				)
			))

			->addElement('Text', 'color', array(
				'label' => _('Color'),
                'tabIndex' => $this->tabIndex++,
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Taxi/template/TaxiForm.phtml');
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

	/**
	 * @return string
	 */
	public function getSourceTaxi() {
		return $this->sourceTaxi;
	}

	/**
	 * @param string $sourceTaxi
	 * @return Zend_Form
	 */
	public function setSourceTaxi($sourceTaxi) {
		$this->sourceTaxi = $sourceTaxi;
		return $this;
	}
}