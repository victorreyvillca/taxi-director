<?php
/**
 * Form for DIST 3.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Admin_Form_News extends Zend_Form {

	public function init() {
		$this->setAttrib('id', 'formId')
            ->setMethod('post')
            ->setAttrib('enctype', 'multipart/form-data')
            ;

       	$hidden = new Zend_Form_Element_Hidden('newsId');

        $title = new Zend_Form_Element_Text('title');
        $title
        	->setLabel(_('Titulo'))
            ->setRequired(TRUE)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;

        $summary = new Zend_Form_Element_Textarea('summary');
        $summary
        	->setLabel(_('Resumen'))
        	->setAttrib('cols', 50)
        	->setAttrib('rows', 3)
        	->setRequired(TRUE)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;

       	$contain = new Zend_Form_Element_Textarea('contain');
        $contain
        	->setLabel(_('Contenido'))
        	->setAttrib('cols', 80)
        	->setAttrib('rows', 15)
        	->setAttrib('class', 'contain')
//        	->setRequired(TRUE)
           	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;

       $fount = new Zend_Form_Element_Text('fount');
       $fount
        	->setLabel(_('Fuente de la noticia'))
            ->setRequired(TRUE)
        	->addFilter('StripTags')
            ->addFilter('StringTrim')
            ;

        $file = new Zend_Form_Element_File('imageFile');
		$file->setLabel(_('Subir Imagen'))
			->setDestination(APPLICATION_PATH.'/../public/image/upload/news')
			->addValidator('Extension', false, 'jpg, png, gif')
            ;

        $category = new Zend_Form_Element_Select('categoryId');
		$category
			->setLabel(_('Categoria'))
			->setRequired(TRUE)
			;

        $saveButton = new Zend_Form_Element_Submit('update');
        $saveButton->setLabel(_('Guardar Noticia'))
        	->setAttrib('class', 'green buttonNg')
        	;

        $this->addElements(array($hidden, $title, $summary, $contain, $fount, $file, $category, $saveButton));
	}
}