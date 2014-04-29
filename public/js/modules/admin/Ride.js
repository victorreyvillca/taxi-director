/**
 * Javascript for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@swissbytes.ch>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.Ride = function () {
		// For create or update register
		this.dialogForm;
		// For show message to client
		this.alert;
		// For data table
		this.table;
		// urls
		this.url = {};
		this.validator;

		this.initFlashMessage();
		this.initEvents();

		this.dtHeaders = undefined;
		this.actionSort = undefined;
	};
com.em.Ride.prototype = {

	getForm: function() {
		return this.dialogForm;
	},
	/**
	 *
	 * Initializes JQuery flash message component
	 */	
	initFlashMessage: function() {
		this.alert = new com.em.Alert();
	},

	/**
	 *
	 * Initializes all the events for items on page
	 */
	initEvents: function() {with(this) {
		$("#nameFilter").bind('keydown', function(e) {
			if (e.type == 'keydown' && e.which == '13') {
				initDisplayStart();
				table.oApi._fnAjaxUpdate(table.fnSettings());
			}
		});

		$("#searchButton").bind('click', function() {
			initDisplayStart();
			table.oApi._fnAjaxUpdate(table.fnSettings());
		});

		$("#resetButton").bind('click', function() {
			$('#nameFilter').attr('value', '');
			initDisplayStart();
			table.oApi._fnAjaxUpdate(table.fnSettings());
		});
	}},
	
	/**
	 * Initializes Display start of datatable
	 */
	initDisplayStart: function() {with(this) {
		var oSettings = table.fnSettings();
		oSettings._iDisplayStart = 0;
		//rows by page
//		oSettings._iDisplayLength = 3;
	}},

	/**
	 *
	 * Sets headers of datatable
	 * @param pheaders
	 */
	setHeaders: function(pheaders){with(this) {
		pheaders = typeof pheaders !== 'undefined' ? pheaders : dtHeaders;
			
		if (typeof dtHeaders === 'undefined') {
			dtHeaders = pheaders;
		}

		var headers = pheaders['headerArray'];

		$("#datatable-headers").empty();

		for ( var i = 0; i < headers.length; i++) {
			$("#datatable-headers").append('<th>'+headers[i]+'</th>');
		}

		$("#datatable-headers").prepend('<th >Id</th>');	
	}},

	/**
	 * Configures the table and elements
	 * @param selector
	 */
	configureTable: function(selector, pdestroy) { with (this) {
		table = $(selector).dataTable({
			"bProcessing"	: false,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false, 
			"bLengthChange" : false,
			"bServerSide"	: true,
			"sAjaxSource"	: url.toTable,
			"aoColumns"		: getColumns(),
			"oLanguage": {
				"sUrl": "/js/lib/jquery-datatables/languages/dataTables.spanish.txt"
			},
			"fnDrawCallback": function() {
				clickToUpdate('#tblRide a[id^=update-ride-]');
			},

			"fnServerData": function (sSource, aoData, fnCallback ) { 
				$.getJSON(sSource, aoData, function (json) {
					fnCallback(json);
				} );
			}
		});
		$(selector).width("100%");
	}},

	/**
	 *
	 * Gets columns configuration for datatable
	 * @return Array
	 */
	getColumns: function() {with (this) {
		var columns = new Array;
		//Sets every element of the table headers
		columns.push({bVisible:false});
		columns.push({
			"sWidth": "25%",
			"bSercheable": "true",
			fnRender : function (oObj){
				return '<a id="update-ride-'+oObj.aData[0]+'" href="'+url.toUpdate+'/id/'+oObj.aData[0]+'">'+oObj.aData[1]+'</a>';
				}
			});
		
		return columns;
	}},

	/**
	 * Shows proccessing display for data table
	 * @param bShow boolean
	 */
	processingDisplay: function(bShow) {
		var settings = table.fnSettings();
		settings.oApi._fnProcessingDisplay(settings, bShow);
	},

	/**
	 * Repaints the table
	 */
	repaintTable: function() {
		table.fnDraw();
	},

	/**
	 * Configures the form
	 * @param selector (dialog of form)	 
	 * */
	configureDialogForm: function(selector) {with (this) {
		dialogForm = $(selector).dialog({
			autoOpen: false,
			height: 300,
			width: 460,
			modal: true,
			close: function(event, ui) {
				$(this).remove();
			}
		});

//		$('#formId').submit(function() {
//			return false;
//		});

		// Configs font-size for header dialog and buttons
		$(selector).parent().css('font-size','0.7em');
	}},

	/**
	 * Opens dialog and manages the creation of new register
	 * @param selector
	 */
	clickToAdd: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Begins to get data
			var action = $(this).attr('href');
			// Sends request by ajax
			$.ajax({
				url: action ,
				type: "GET",
				beforeSend : function(XMLHttpRequest) {
					processingDisplay(true);
				},

				success: function(data, textStatus, XMLHttpRequest) {
					if (textStatus == 'success') {
						var contentType = XMLHttpRequest.getResponseHeader('Content-Type');
						if (contentType == 'application/json') {
							alert.show(data.message, {header: com.em.Alert.FAILURE});
						} else {
							// Getting html dialog
							$('#dialog').html(data);
							// Configs dialog
							configureDialogForm('#dialog-form', 'insert');
							// Sets validator
							setValidatorForm("#formId");
							// Opens dialog
							dialogForm.dialog('open');
							// Loads buttons for dialog. dialogButtons is defined by ajax
							dialogForm.dialog( "option" , 'buttons', dialogButtons);
						}
					} 
				},

				complete: function(jqXHR, textStatus) {
					processingDisplay(false);
				},

				error: function(jqXHR, textStatus, errorThrown) {
					dialogForm.dialog('close');
					alert.flashError(errorThrown,{header : com.em.Alert.ERROR});
				}
			});
		});
	}},

	/**
	 * Opens dialog and manages the creation of new register
	 * @param selector
	 */
	clickToSearch: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Begins to get data
			var action = $(this).attr('href');
			// Sends request by ajax
			$.ajax({
				url: action ,
				type: "GET",
				beforeSend : function(XMLHttpRequest) {
					processingDisplay(true);
				},

				success: function(data, textStatus, XMLHttpRequest) {
					if (textStatus == 'success') {
						var contentType = XMLHttpRequest.getResponseHeader('Content-Type');
						if (contentType == 'application/json') {
							alert.show(data.message, {header: com.em.Alert.FAILURE});
						} else {
							// Getting html dialog
							$('#dialog').html(data);
							// Configs dialog
							configureDialogForm('#dialog-form', 'insert');
							// Sets validator
							//setValidatorForm("#formId");
							// Opens dialog
							dialogForm.dialog('open');
							// Loads buttons for dialog. dialogButtons is defined by ajax
							dialogForm.dialog( "option" , 'buttons', dialogButtons);
						}
					} 
				},

				complete: function(jqXHR, textStatus) {
					processingDisplay(false);
				},

				error: function(jqXHR, textStatus, errorThrown) {
					dialogForm.dialog('close');
					alert.flashError(errorThrown,{header : com.em.Alert.ERROR});
				}
			});
		});
	}},

	/**
	 *
	 * Opens dialog and manages the update of register
	 * @param selector
	 */
	clickToUpdate: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Begins to get data
			var action = $(this).attr('href');
			// Sends request by ajax
			$.ajax({
				url: action,
				type: "GET",
				beforeSend: function(XMLHttpRequest) {
					processingDisplay(true);
				},

				success: function(data, textStatus, XMLHttpRequest) {
					if (textStatus == 'success') {
						var contentType = XMLHttpRequest.getResponseHeader('Content-Type');
						if (contentType == 'application/json') {
							alert.show(data.message, {header: com.em.Alert.FAILURE});
						} else {
							// Getting html dialog
							$('#dialog').html(data);
							// Configs dialog
							configureDialogForm('#dialog-form', 'update');
							// Sets validator
							setValidatorForm("#formId");
							// open dialog
							dialogForm.dialog('open');
							// Loads buttons for dialog. dialogButtons is defined by ajax
							dialogForm.dialog( "option" , 'buttons' , dialogButtons);
						}
					} 
				},

				complete: function(jqXHR, textStatus) {
					processingDisplay(false);
				},

				error: function(jqXHR, textStatus, errorThrown) {
					dialogForm.dialog('close');
					alert.flashError(errorThrown,{header: com.em.Alert.ERROR});
				}
			});
		});
	}},

	/**
	 *
	 * Deletes n items
	 * @param selector
	 */
	clickToDelete: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Serializes items checked
			var items = $('#tblRide :checked');
			var itemsChecked = items.serialize();
			if (itemsChecked == '') {
				alert.flashInfo('No hay registro seleccionado', {header: com.em.Alert.NOTICE});
				return;
			}
			var action = $(this).attr('href');

			jConfirm('Estas seguro de Eliminar ?', 'Eliminar', function(r) {
				if (r) {
					$.ajax({
						dataType: 'json', 
						type: "POST", 
						url: action,
						// Gets element checkbox checked
						data: itemsChecked,
						beforeSend : function(XMLHttpRequest) {
							processingDisplay(true);
						},

						success: function(data, textStatus, XMLHttpRequest) {
							if (textStatus == 'success') {
								if (data.success) {
									table.fnDraw();
									alert.flashSuccess(data.message, {header: com.em.Alert.SUCCESS});
								} else {
									alert.flashInfo(data.message, {header: com.em.Alert.NOTICE});
								}
							}
						},

						complete: function(jqXHR, textStatus) {
							processingDisplay(false);
						},

						error: function(jqXHR, textStatus, errorThrown) {
							alert.flashError(errorThrown,{header: com.em.Alert.ERROR});
						}
					});
				} else {
					return;
				}
			});
		});
	}},

	/**
	 *
	 * Configures the name autocomplete of the filter
	 * @param selector
	 */
	configureAuto: function(selector) { with (this) {
		$(selector).autocomplete({
			source: function(request, response) {
				$.ajax({
					url: url.toAutocomplete,
					dataType: 'json',
					data: {name_auto: request.term},
					success: function(data, textStatus, XMLHttpRequest) {
						response($.map(data.items, function(item) {
							return {
								label: item
							};
						}));
					}
				});
			}
		});
	}},

	/**
	 * Configures the name autocomplete of the filter
	 * @param selector
	 */
	configureLabelAuto: function(selector) { with (this) {
		$(selector).autocomplete({
			source: function(request, response) {
				$.ajax({
					url: url.toAutocompleteLabel,
					dataType: 'json',
					data: {name_auto: request.term},
					success: function(data, textStatus, XMLHttpRequest) {
						response($.map(data.items, function(item) {
							return {
								label: item
							};
						}));
					}
				});
			}
		});
	}},
	
	/**
	 *
	 * Validates Ride form
	 * @param selector
	 */
	setValidatorForm : function(selector) {
		validator = $(selector).validate({
			rules:{
				'phone':{
					required: true,
					maxlength: 10
				}
			}
		});
	},

	/**
	 *
	 * Sets url for action side server
	 * @param url json
	 */
	setUrl: function(url) {
		this.url = url;
	},

	/**
	 *
	 * Gets number position of name in array data
	 * @param array containing sub-arrays with the structure name->valname, value->valvalue
	 * @param name is the string we are looking for and must match with valname
	 */
	getPosition: function(data, name) {
		var pos = -1;
		for ( var i = 0; i < data.length; i++) {
			if (data[i].name == name) {
				pos = i;
			}
		}
		return pos;
	},

	/**
	 *
	 * Shows alert if it exists, if not create a new instance of alert and show it
	 * @param message to show
	 * @param header of the message
	 */
	showAlert: function(message, header) {with (this) {
		if (this.alert == undefined) {
			this.alert = new com.em.Alert();
		}
		alert.show(message, header);
	}},

	/**
	 *
	 * Shows flash message success if it exists, if not creates a new instance of flash message success and shows it.
	 * @param message string
	 * @param header string
	 */
	flashSuccess: function(message, header) {with (this) {
		if (this.alert == undefined) {
			this.alert = new com.em.Alert();
		}
		alert.flashSuccess(message, header);
	}},

	/**
	 *
	 * Shows flash message error if it exists, if not creates a new instance of flash message error and shows it.
	 * @param message string
	 * @param header string
	 */
	flashError: function(message, header) {with (this) {
		if (this.alert == undefined) {
			this.alert = new com.em.Alert();
		}
		alert.flashError(message, header);
	}}
};