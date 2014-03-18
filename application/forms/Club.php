<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Dis_Form_Club extends Zend_Form {

	/**
	 * @var string
	 */
	private $source;

	/**
	 * @var bool
	 */
	private $genderMaleChecked;

	/**
	 * @var bool
	 */
	private $genderFemaleChecked;

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data')

			->addElement('Hidden', 'id')

			->addElement('text', 'name', array(
    			'label' => _('Nombre del Club'),
    			'required' => TRUE,
    			'filters' => array('StringTrim'),
    			'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese el Nombre del Club'), 'required' => '', 'autofocus' => ''),
    			'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
		      ))

		      ->addElement('Select', 'district', array(
                'label' => _('Distrito'),
	      		'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Distrito'), 'autofocus' => ''),
	      		'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
		      ))

		      ->addElement('Select', 'church', array(
	      		'label' => _('Iglesia'),
	      		'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Iglesia'), 'autofocus' => ''),
	      		'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
		      ))

			->addElement('text', 'lastName', array(
				'label' => _('Pastor'),
				'required' => TRUE,
    			'filters' => array('StringTrim'),
    			'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese el nombre del Pastor'), 'required' => '', 'autofocus' => ''),
    			'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'position', array(
				'label' => _('Cargo'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su cargo'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'street', array(
				'label' => _('Calle o Avenida'),
				'required' => TRUE,
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Documento de Identidad'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'year', array(
				'label' => _('Barrio'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese el Barrio'), 'autofocus' => '', 'readonly' => TRUE),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'dateOfInaguration', array(
				'label' => _('Fecha de Inaguración'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Fecha de Inaguración'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'birthplace', array(
				'label' => _('Lugar de Nacimiento'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese el Lugar de Nacimiento'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('TextArea', 'address', array(
				'label' => _('Direccion'),
			    'attribs' => array('cols' => 16, 'rows' => 3),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese sus Direccion'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'city', array(
				'label' => _('Ciudad'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese la Ciudad'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'department', array(
				'label' => _('Department'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione el Departamento'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'phone', array(
				'label' => _('Telefono Fijo'),
				'filters' => array(
                    array('StringTrim')
				),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Telefono Fijo'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'phonemobil', array(
				'label' => _('Telefono Celular'),
				'required' => TRUE,
				'filters' => array(
                    array('StringTrim')
				),
				'validators' => array(
                    array('Digits', false)
				),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su nro de Celular'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'email', array(
				'label' => _('Correo'),
				'filters' => array(
					array('StringTrim')
				),
				'validators' => array(
					'EmailAddress'
				),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Correo'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'rank', array(
				'label' => _('Lirerazgo'),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Grado de Liderazgo'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'area', array(
                'label' => _('Area de Lirerazgo'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione el Area de Liderazgo'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'club', array(
				'label' => _('Club'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Club'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'mission', array(
                'label' => _('Mision'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Mision'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'region', array(
                'label' => _('Region'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Region'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'district', array(
                'label' => _('Distrito'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Distrito'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'church', array(
                'label' => _('Iglesia'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su Iglesia'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'yearService', array(
				'label' => _('Zona'),
				'filters' => array(
                    array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese los años de Servicio'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'countMemberClub', array(
                'label' => _('¿Cuántos miembros tiene el Club?'),
                'filters' => array(
                    array('StringTrim')
                ),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese cantidad de miembros'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'countUnityMen', array(
                'label' => _('¿Cuántas unidades varones tiene?'),
                'filters' => array(
                    array('StringTrim')
                ),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese cantidad de unidades varones'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'countUnityWomen', array(
				'label' => _('¿Cuántas unidades mujeres tiene?'),
				'filters' => array(
                    array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese cantidad de unidades mujeres'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'countMemberDirective', array(
				'label' => _('¿Cuántos miembros tiene la Directiva?'),
				'filters' => array(
					array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese cantidad de miembros Directiva'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'flags', array(
                'label' => _('Banderas'),
                'filters' => array(
					array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese algun Tratamiento'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('MultiSelect', 'classConqueror', array(
                'label' => _('Investiduras'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione Investiduras'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Submit', 'saveButton', array(
                'label' => _('Guardar')
			))
		;
	}

	/**
	 * (non-PHPdoc)
	 * @see Zend_Form::loadDefaultDecorators()
	 */
	public function loadDefaultDecorators() {
		$this->setDecorators(
			array(
				new \Zend_Form_Decorator_PrepareElements(),
				'ViewScript'
			)
		);
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Club/template/ClubForm.phtml');
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
	public function getGenderMaleChecked() {
	    if ($this->genderMaleChecked) {
	    	return 'checked';
	    }
		return NULL;
	}

	/**
	 * @param boolean $genderMaleChecked
	 * @return Zend_Form
	 */
	public function setGenderMaleChecked($genderMaleChecked) {
		$this->genderMaleChecked = $genderMaleChecked;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getGenderFemaleChecked() {
        if ($this->genderFemaleChecked) {
            return 'checked';
        }
		return NULL;
	}

	/**
	 * @param boolean $genderFemaleChecked
	 * @return Zend_Form
	 */
	public function setGenderFemaleChecked($genderFemaleChecked) {
		$this->genderFemaleChecked = $genderFemaleChecked;
		return $this;
	}

}