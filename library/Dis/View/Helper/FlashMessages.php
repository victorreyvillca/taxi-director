<?php
/**
 * Helper for DIST, Creates button remove.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_View_Helper_FlashMessages {

	/**
	 *
	 * Shows the flash messages across java script
	 */
	public function flashMessages() {
		$messenger = Zend_Controller_Action_HelperBroker::getStaticHelper("FlashMessenger");

		$js = '<script type="text/javascript">';

		$currentMessagesArray = $messenger->getCurrentMessages();
		foreach ($currentMessagesArray as $currentMessages) {
			foreach ($currentMessages as $type => $message) {
				$subJS = <<<JS
					this.alert = new com.em.Alert();
					alert.flashMessage('%s', {header: '%s'}, '%s');
JS;
				$subJS = sprintf($subJS, $message, $type, $type);
				$js = $js . $subJS;
			}
		}

		$messagesArray = $messenger->getMessages();
		foreach ($messagesArray as $messages) {
			foreach ($messages as $type => $message) {
				$subJS = <<<JS
					this.alert = new com.em.Alert();
					alert.flashMessage('%s', {header: '%s'}, '%s');
JS;
				$subJS = sprintf($subJS, $message, $type, $type);
				$js = $js . $subJS;
			}
		}

		$js.= '</script>';

		return $js;
	}
}