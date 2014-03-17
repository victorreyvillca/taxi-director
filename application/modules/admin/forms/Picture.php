<?php
/**
 * Form for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2014 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_Picture extends Zend_Form {

	public function init() {
		$this
			->setAttrib('id', 'formId')
			->setMethod('post')
			->setAttrib('enctype', 'multipart/form-data');

        $hidden = new Zend_Form_Element_Hidden('id');
		$title = new Zend_Form_Element_Text('title');
		$title->setLabel(_('Titulo'))
			->setRequired(TRUE)
			->addFilter('StringTrim');

		$description = new Zend_Form_Element_Textarea('description');
		$description->setLabel(_('Descripcion'))
				->setAttrib("cols", 40)
				->setAttrib("rows", 4)
				->addFilter('StringTrim');

		$file = new Zend_Form_Element_File('file');
		$file->setLabel(_('Subir Imagen'))
				->setRequired(TRUE)
				->setDestination(APPLICATION_PATH.'/../public/image/upload/news')
				->addValidator('Extension', FALSE, 'jpg, png, gif');

		$newsSelect = new Zend_Form_Element_Select('news');
		$newsSelect->setRequired(TRUE)->setLabel(_('Seleccione la Noticia'));

		$this->addElements(array($hidden, $title, $description, $file, $newsSelect));
	}
}