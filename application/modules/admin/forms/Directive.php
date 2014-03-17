<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Directive extends Zend_Form {

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

			->addElement('text', 'firstName', array(
    			'label' => _('Nombres'),
    			'required' => TRUE,
    			'filters' => array('StringTrim'),
    			'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Nombre'), 'required' => '', 'autofocus' => ''),
    			'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
		      ))

			->addElement('text', 'lastName', array(
				'label' => _('Apellidos'),
				'required' => TRUE,
    			'filters' => array('StringTrim'),
    			'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Apellido'), 'required' => '', 'autofocus' => ''),
    			'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Select', 'position', array(
				'label' => _('Cargo'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Seleccione su cargo'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'ci', array(
				'label' => _('Documento de Identidad'),
				'required' => TRUE,
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Documento de Identidad'), 'required' => '', 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'year', array(
				'label' => _('Edad'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese sus Edad'), 'autofocus' => '', 'readonly' => TRUE),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'dateOfBirth', array(
				'label' => _('Fecha de Nacimiento'),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese sus Fecha de Nacimiento'), 'required' => '', 'autofocus' => ''),
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
				'label' => _('Lugar de Nacimiento'),
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
				'label' => _('Años de Servicio'),
				'filters' => array(
                    array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese los años de Servicio'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'gradeSchool', array(
                'label' => _('Profesion'),
                'filters' => array(
                    array('StringTrim')
                ),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Profesion'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))
            // data optionals
			->addElement('Text', 'bloodGroup', array(
                'label' => _('Grupo Sanguineo'),
                'filters' => array(
                    array('StringTrim')
                ),
                'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese su Grupo Sanguineo'), 'autofocus' => ''),
                'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'allergies', array(
				'label' => _('Alergias'),
				'filters' => array(
                    array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese alguna Alergia'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'disease', array(
				'label' => _('Enfermedad'),
				'filters' => array(
					array('StringTrim')
				),
				'attribs' => array('class' => 'form-poshytip', 'title' => _('Ingrese alguna Enfermedad'), 'autofocus' => ''),
				'decorators' => array(array('ViewHelper'), array('label'), array('HtmlTag', array('tag' => 'div')))
			))

			->addElement('Text', 'treatment', array(
                'label' => _('Tratamiento'),
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
		$this->getDecorator('ViewScript')->setOption('viewScript', '/Registration/template/DirectiveForm.phtml');
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