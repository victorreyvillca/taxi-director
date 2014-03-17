var com = com || {};
com.em = com.em ||{};

/**
 * header			empty string				Optional header to prefix the message, this is often helpful for associating messages to each other.
 * sticky			false						When set to true a message will stick to the screen until it is intentionally closed by the user.
 * glue				after						Designates whether a jGrowl notification should be appended to the container after all notifications, or whether it should be prepended to the container before all notifications. Options are after or before.
 * position			top-right					Designates a class which is applied to the jGrowl container and controls it's position on the screen. By Default there are five options available, top-left, top-right, bottom-left, bottom-right, center. This must be changed in the defaults before the startup method is called.
 * theme			default						A CSS class designating custom styling for this particular message.
 * corners			10px						If the corners jQuery plugin is include this option specifies the curvature radius to be used for the notifications as they are created.
 * check			1000						The frequency that jGrowl should check for messages to be scrubbed from the screen.This must be changed in the defaults before the startup method is called.
 * life				3000						The lifespan of a non-sticky message on the screen.
 * speed			normal						The animation speed used to open or close a notification.
 * easing			swing						The easing method to be used with the animation for opening and closing a notification.
 * closer			true						Whether or not the close-all button should be used when more then one notification appears on the screen. Optionally this property can be set to a function which will be used as a callback when the close all button is clicked. This must be changed in the defaults before the startup method is called.
 * closeTemplate	&times;						This content is used for the individual notification close links that are added to the corner of a notification. This must be changed in the defaults before the startup method is called.
 * closerTemplate	<div>[ close all ]</div>	This content is used for the close-all link that is added to the bottom of a jGrowl container when it contains more than one notification. This must be changed in the defaults before the startup method is called.
 * log				function(e,m,o) {}			Callback to be used before anything is done with the notification. This is intended to be used if the user would like to have some type of logging mechanism for all notifications passed to jGrowl. This callback receives the notification's DOM context, the notifications message and it's option object.
 * beforeOpen		function(e,m,o) {}			Callback to be used before a new notification is opened. This callback receives the notification's DOM context, the notifications message and it's option object.
 * open				function(e,m,o) {}			Callback to be used when a new notification is opened. This callback receives the notification's DOM context, the notifications message and it's option object.
 * beforeClose		function(e,m,o) {}			Callback to be used before a new notification is closed. This callback receives the notification's DOM context, the notifications message and it's option object.
 * close			function(e,m,o) {}			Callback to be used when a new notification is closed. This callback receives the notification's DOM context, the notifications message and it's option object.
 * animateOpen		{ opacity: 'show' }			The animation properties to use when opening a new notification (default to fadeOut).
 * animateClose		{ opacity: 'hide' }			The animation properties to use when closing a new notification (defaults to fadeIn).
 */

/**
 * 
 */
com.em.Alert = function (){
	// type message
	com.em.Alert.ERROR = "Error";
	com.em.Alert.WARNING = "Warning";
	com.em.Alert.NOTICE = "Notice";
	com.em.Alert.SUCCESS = "Success";
	com.em.Alert.FAILURE = "Failure";
	
	// options by message default
	this.optionsDefault = {
		position : "bottom-left",
		header : com.em.Alert.SUCCESS,
	    animateOpen: {
	        opacity: 'show'
	    }
	};
};

com.em.Alert.prototype = {
	/**
	 * Show message on screen, By Default bottom-left.
	 * 
	 * @param message txt for user
	 * @param options
	 */
	show: function(message, options) {
		var settings = options || {};
		$.extend(this.optionsDefault, settings);
		$.jGrowl(message, this.optionsDefault);
	},

	/**
	 * 
	 * Shows flash message on screen at mode success
	 * @param message string
	 * @param options array
	 */
	flashSuccess: function(message, options) {
		var settings = options || {};
		var options = {position: "bottom-left", theme: 'success'};
		$.extend(options, settings);
		$.jGrowl(message, options);
	},
	
	/**
	 * 
	 * Shows flash message on screen at mode info
	 * @param message string
	 * @param options array
	 */
	flashInfo: function(message, options) {
		var settings = options || {};
		var options = {position: "bottom-left", theme: 'info'};
		$.extend(options, settings);
		$.jGrowl(message, options);
	},
	
	/**
	 * 
	 * Shows flash message on screen at mode warning
	 * @param message string
	 * @param options array
	 */
	flashWarning: function(message, options) {
		var settings = options || {};
		var options = {position: "bottom-left", theme: 'warning'};
		$.extend(options, settings);
		$.jGrowl(message, options);
	},
	
	/**
	 * 
	 * Shows flash message on screen at mode error
	 * @param message string
	 * @param options array
	 */
	flashError: function(message, options) {
		var settings = options || {};
		var options = {position: "bottom-left", theme: 'error'};
		$.extend(options, settings);
		$.jGrowl(message, options);
	},
	
	/**
	 * 
	 * Shows flash message on screen by type of message
	 * @param message string
	 * @param options array
	 * @param type string
	 */
	flashMessage: function(message, options, type) {
		var settings = options || {};
		var options = {position: "bottom-left", theme: type};
		$.extend(options, settings);
		$.jGrowl(message, options);
	}
	
};