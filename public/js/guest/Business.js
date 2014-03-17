/**
 * Javascript for DIST 2.
 *
 * @category Geo
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.Business = function () {
		this.validator;

		//this.initFlashMessage();
	};
com.em.Business.prototype = {

	/**
	 * Deletes n items
	 * @param selector
	 */
	clickToDelete: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			var action = $(this).attr('href');
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					'Si': function() {
						$(location).attr('href', action);
					},
					'No': function() {
						$(this).dialog("close");
					}
				}
			});
		});
	}},
};