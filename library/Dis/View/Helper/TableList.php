<?php
/**
 * Helper for DIST, Creates table list.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_View_Helper_TableList {

	/**
	 *
	 * @var Zend_View_Interface
	 */
	public $view;

	/**
	 *
	 * This function returns the html button add code for input
	 * @param string $id
	 * @param string $name
	 * @param string $href
	 */
	public function tableList($id) {
		$html = '<table cellpadding="0" cellspacing="0" border="0" class="zebralist" id="'.$id.'">';
			$html .= '<thead>';
				$html .= '<tr id="datatable-headers">	</tr>';
			$html .= '</thead>';
			$html .= '<tbody>';
			$html .= '</tbody>';
		$html.= '</table>';

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