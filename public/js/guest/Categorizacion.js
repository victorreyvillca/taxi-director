/**
 * Javascript for Metales CRM.
 *
 * @category Crm
 * @author Victor Villca <victor.villca@people-t.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.Categorizacion = function () {
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
com.em.Categorizacion.prototype = {

	/**
	 * Initializes JQuery flash message component
	 */
	initFlashMessage: function() {
		this.alert = new com.em.Alert();
	},

	/**
	 * Initializes all the events for items on page
	 */
	initEvents: function() {with(this) {
		$("#productoFilter").bind('keydown', function(e) {
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
			$('#productoFilter').attr('value', '');
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
	}},

	/**
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

		$("#datatable-headers").append('<th><input type="checkbox" id="check-master" value="0"></th>');
		$("#datatable-headers").prepend('<th >Id</th>');

		//Adding the event to check-master because it was removed
		$("#check-master").bind('click', function() {
			var checked = $("#check-master").attr('checked');
			if (checked) {
				$('#tblCategorizacion input[id^=check-slave-]').attr('checked', true);
			} else {
				$('#tblCategorizacion input[id^=check-slave-]').attr('checked', false);
			}
		});
	}},

	/**
	 * Configures the table and elements
	 * @param selector
	 */
	configureTable: function(selector, pdestroy) { with (this) {
		table = $(selector).dataTable({
			"bProcessing"	: true,
			"bFilter"		: false,
			"bSort"			: false,
			"bInfo"			: false, 
			"bLengthChange" : false,
			"bServerSide"	: true,
			"sAjaxSource"	: url.toTable,
			"aoColumns"		: getColumns(),
			"sPaginationType" : "full_numbers",
			"oLanguage": {
				"sEmptyTable": "No existe Categorizaciones."
			},
			"fnDrawCallback": function() {
//				clickToUpdate('#tblProducto a[id^=update-datoPromocion-]');
			},

			"fnServerData": function (sSource, aoData, fnCallback ) {
				//applying promocion filter
				var position = getPosition(aoData, 'filter_producto');

				if (position == -1)
					aoData.push({"name": "filter_producto","value": $('#productoFilter').attr('value')});
				else
					aoData[position].value=$('#productoFilter').attr('value');

				$.getJSON(sSource, aoData, function (json) {
					fnCallback(json);
				});
			}
		});
		$(selector).width("100%");
	}},

	/**
	 * Gets columns configuration for datatable
	 * @return Array
	 */
	getColumns: function() {with (this) {
		var columns = new Array;
		//Sets every element of the table headers
		columns.push({bVisible:false});
		columns.push({
			"sWidth": "30%",
			"bSercheable": "true",
			fnRender : function (oObj){
				return '<a id="edit-'+oObj.aData[0]+'" href="'+url.toUpdate+'/id/'+oObj.aData[0]+'">'+oObj.aData[1]+'</a>';
				}
			});

		columns.push({"sWidth": "15%"});
		columns.push({"sWidth": "15%"});
		columns.push({"sWidth": "15%"});
		columns.push({"sWidth": "19%"});
		columns.push({"sWidth": "19%"});
		columns.push({
			"bSortable": false,
			"sWidth": "2%",
			"sClass": "checkColumn",
			fnRender : function (oObj){
			   return '<input type="checkbox" name="itemIds[]" id="check-slave-'+oObj.aData[0]+'" value="'+oObj.aData[0]+'">';
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
	 * Configures the form
	 * @param selector (dialog of form)
	 * */
	configureDialogForm: function(selector) {with (this) {
		dialogForm = $(selector).dialog({
			autoOpen: false,
			height: 165,
			width: 350,
			modal: true,
			close: function(event, ui) {
				$(this).remove();
			}
		});

		// Configs font-size for header dialog and buttons
		$(selector).parent().css('font-size','0.7em');
	}},

	/**
	 * Deletes n items
	 * @param selector
	 */
	clickToEnable: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Serializes items checked
			var items = $('#tblDatoPromocion :checked');
			var itemsChecked = items.serialize();

			if (itemsChecked == '') {
				alert.flashInfo('No hay Premio Seleccionada', {header: com.em.Alert.NOTICE});
				return;
			}
			var action = $(this).attr('href');

			var promocionId = $('#promocion').attr('value');
			action = action+'/promocionId/'+promocionId;

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
		});
	}},

	/**
	 * Sets url for action side server
	 * @param url json
	 */
	setUrl: function(url) {
		this.url = url;
	},

	/**
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
	 * Deletes n items
	 * @param selector
	 */
	clickToDelete: function(selector) {with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			// Serializes items checked
			var items = $('#tblCategorizacion :checked');
			var itemsChecked = items.serialize();
			if (itemsChecked == '') {
				alert.flashInfo('No hay Categorizacion seleccionada', {header: com.em.Alert.NOTICE});
				return;
			}
			var action = $(this).attr('href');

			$('#dialog-confirm').dialog({
				resizable: false,
				height:140,
				modal: true,
				buttons: {
					'No': function() {
						$(this).dialog('close');
					},
					'Si': function() {
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
										$('#dialog-confirm').dialog('close');
										table.fnDraw();
										alert.flashSuccess(data.message, {header: com.em.Alert.SUCCESS});
									} else {
										$('#dialog-confirm').dialog('close');
										alert.flashInfo(data.message, {header: com.em.Alert.NOTICE});
									}
								}
							},

							complete: function(jqXHR, textStatus) {
								processingDisplay(false);
							},

							error: function(jqXHR, textStatus, errorThrown) {
								$(this).dialog('close');
								alert.flashError(errorThrown,{header: com.em.Alert.ERROR});
							}
						});
					}
				}
			});
		});
	}},

	/**
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