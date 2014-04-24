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

	/**
	 * @var string
	 */
	private $firstName;

	/**
	 * @var string
	 */
	private $lastName;

	/**
	 * @var int
	 */
	private $ci;

	/**
	 * @var string
	 */
	private $address;

	/**
	 * @var string
	 */
	private $number;

	/**
	 * @var string
	 */
	private $mark;

	/**
	 * @var string
	 */
	private $plaque;

	/**
	 * @var string
	 */
	private $typeMark;

	/**
	 * @var int
	 */
	private $model;

	/**
	 * @var string
	 */
	private $color;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

			->addElement('Hidden', 'driverId')
			->addElement('Hidden', 'taxiId')

			->addElement('Text', 'firstName', array(
				'label' => _('Nombres'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'lastName', array(
				'label' => _('Apellidos'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				)
			))

			->addElement('Text', 'ci', array(
				'label' => _('Cedula de Identidad'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'validators' => array(
                    array('Digits', false)
				)
			))

			->addElement('Textarea', 'address', array(
				'label' => _('Direccion'),
//                 'tabIndex' => $this->tabIndex++,
                'cols' => '20',
                'rows' => '3',
			))

			->addElement('Text', 'number', array(
				'label' => _('Numero movil'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				),
		        'validators' => array(
                    array('Digits', FALSE)
		        )
			))

			->addElement('Text', 'phone', array(
				'label' => _('Telefono'),
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				),
				'validators' => array(
					array('Digits', FALSE)
				)
			))

			->addElement('Text', 'mark', array(
				'label' => _('Marca'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'plaque', array(
				'label' => _('Placa'),
//                 'tabIndex' => $this->tabIndex++,
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				)
			))

			->addElement('Text', 'typeMark', array(
				'label' => _('Tipo'),
//                 'tabIndex' => $this->tabIndex++,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'model', array(
                'label' => _('Modelo'),
//                 'tabIndex' => $this->tabIndex++,
				'filters' => array(
                    array('StringTrim')
                ),
				'validators' => array(
				array('Digits', false)
				)
			))

			->addElement('Text', 'color', array(
				'label' => _('Color'),
//                 'tabIndex' => $this->tabIndex++,
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/taxi/template/TaxiForm.phtml');
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

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 * @return Zend_Form
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 * @return Zend_Form
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCi() {
		return $this->ci;
	}

	/**
	 * @param int $ci
	 * @return Zend_Form
	 */
	public function setCi($ci) {
		$this->ci = $ci;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return Zend_Form
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param string $number
	 * @return Zend_Form
	 */
	public function setNumber($number) {
		$this->number = $number;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMark() {
		return $this->mark;
	}

	/**
	 * @param string $mark
	 * @return Zend_Form
	 */
	public function setMark($mark) {
		$this->mark = $mark;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPlaque() {
		return $this->plaque;
	}

	/**
	 * @param string $plaque
	 * @return Zend_Form
	 */
	public function setPlaque($plaque) {
		$this->plaque = $plaque;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTypeMark() {
		return $this->typeMark;
	}

	/**
	 * @param string $typeMark
	 * @return Zend_Form
	 */
	public function setTypeMark($typeMark) {
		$this->typeMark = $typeMark;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @param int $model
	 * @return Zend_Form
	 */
	public function setModel($model) {
		$this->model = $model;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param string $color
	 * @return Zend_Form
	 */
	public function setColor($color) {
		$this->color = $color;
		return $this;
	}
}