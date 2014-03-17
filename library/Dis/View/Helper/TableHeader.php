<?php
/**
 * Helper for DIST, Creates table header.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_View_Helper_TableHeader {

	/**
	 * @var Zend_View_Interface
	 */
	public $view;

	/**
	 * This function returns the html table header code for input
	 * @param string $title
	 * @param string $href
	 * @return string
	 */
	public function tableHeader($id , $title = "title", $href = "#") {
		$html = '<div class="head" id="'.$id.'">';
			$html.='<div class="left">';
				$html.='<a name="#" title="'.$title.'" href="'.$href.'">'.$title.'</a>';
			$html.='</div>';
			$html.='<div class="center">';
			$html.='</div>';
			$html.='<div class="right">';
			$html.='</div>';
		$html.= '</div>';

		return $html;
	}

	/**
	 *
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}