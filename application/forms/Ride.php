<?php
use Model\Ride;
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_Ride extends Zend_Form {

    /**
     * @var bool
     */
    private $onlyRead;

    /**
     * @var bool
     */
    private $isResume;

    /**
     * @var string
     */
	private $phone;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
    private $address;

    /**
     * @var string
     */
    private $note;

    /**
     * @var string
     */
    private $taxi;

	public function init() {
	    $this->onlyRead = FALSE;
	    $this->isResume = FALSE;

		$this
			->setAttrib('id', 'formId')

			->addElement('Hidden', 'id')

			->addElement('Text', 'phone', array(
                'label' => _('Telefono'),
                'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Text', 'name', array(
				'label' => _('Pasajero'),
                'required' => TRUE,
				'filters' => array(
					array('StringTrim')
				)
			))

			->addElement('Select', 'label', array(
				'label' => _('Direccion'),
                'required' => TRUE
			))

			->addElement('Textarea', 'address', array(
                'label' => _('Descrpcion de la Direccion'),
                'cols' => 18,
                'rows' => 2,
                'required' => TRUE
			))

			->addElement('Textarea', 'note', array(
                'label' => _('Note'),
				'cols' => 18,
				'rows' => 2
			))

			->addElement('Select', 'taxi', array(
                'label' => _('Numero de Taxi')
			))

			->addElement('Text', 'number', array(
				'label' => _('Numero de Taxi'),
                'attribs' => array('size' => 5),
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
                    array('Digits', false)
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/ride/template/RideForm.phtml');
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 * @return Dis_Form_Ride
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Dis_Form_Ride
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param string $label
	 * @return Dis_Form_Ride
	 */
	public function setLabel($label) {
		$this->label = $label;
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
	 * @return Dis_Form_Ride
	 */
	public function setAddress($address) {
		$this->address = $address;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param string $note
	 * @return Dis_Form_Ride
	 */
	public function setNote($note) {
		$this->note = $note;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTaxi() {
		return $this->taxi;
	}

	/**
	 * @param string $taxi
	 * @return Dis_Form_Ride
	 */
	public function setTaxi($taxi) {
		$this->taxi = $taxi;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getOnlyRead() {
		return $this->onlyRead;
	}

	/**
	 * @param boolean $onlyRead
	 * @return Dis_Form_Ride
	 */
	public function setOnlyRead($onlyRead) {
		$this->onlyRead = $onlyRead;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsResume() {
		return $this->isResume;
	}

	/**
	 * @param boolean $onlyRead
	 * @return Dis_Form_Ride
	 */
	public function setIsResume($isResume) {
		$this->isResume = $isResume;
		return $this;
	}
}