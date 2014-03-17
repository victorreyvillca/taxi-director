/**
 * Javascript for DIST 2.
 *
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2012 Gisof A/S
 * @license Proprietary
 */

var com = com || {};
com.em = com.em ||{};
	com.em.Position = function () {
		// For create or update register
		this.dialogForm;
		// For show flash messages to client
		this.flashMessage;
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
com.em.Position.prototype = {
	
	/**
	 * 
	 * Initializes JQuery flash message component
	 */	
	initFlashMessage: function() {
		this.flashMessage = new com.em.FlashMessage();
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
	 * 
	 * Initializes Display start of datatable
	 */
	initDisplayStart: function() {with(this) {
		var oSettings = table.fnSettings();
		oSettings._iDisplayStart = 0;
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
		
		$("#datatable-headers").append('<th><input type="checkbox" id="check-master" value="0"></th>');
		$("#datatable-headers").prepend('<th >Id</th>');
		
		//Adding the event to check-master because it was removed
		$("#check-master").bind('click', function() {
			var checked = $("#check-master").attr('checked');
			if (checked) {
				$('#tblPosition input[id^=check-slave-]').attr('checked', true);
			} else {
				$('#tblPosition input[id^=check-slave-]').attr('checked', false);
			}
		});		
	}},
	
	/**
	 * 
	 * Configures the table and elements
	 * @param selector
	 */
	configureTable: function(selector, pdestroy) { with (this) {
		table = $(selector).dataTable({
			"bProcessing"   : true,
			"bFilter"       : false,
			"bSort"         : true,
			"bInfo"         : true, 
			"bLengthChange" : false,
			"bServerSide"   : true,
			"sAjaxSource"   : url.toTable,
			"aoColumns"     : getColumns(),
		    "sPaginationType" : "full_numbers",
			"oLanguage": {
				"sUrl": "/js/lib/jquery-datatables/languages/dataTables.spanish.txt"
			},
			"fnDrawCallback": function() {
				clickToUpdate('#tblPosition a[id^=update-position-]');
			},
			
			"fnServerData": function (sSource, aoData, fnCallback ) { 
				//applying filter_name
				var position = getPosition(aoData, 'filter_name');
				
				if (position == -1)
					aoData.push({"name": "filter_name","value": $('#nameFilter').attr('value')});				
				else
					aoData[position].value=$('#nameFilter').attr('value');
				
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
				return '<a id="update-position-'+oObj.aData[0]+'" href="'+url.toUpdate+'/id/'+oObj.aData[0]+'">'+oObj.aData[1]+'</a>';
				}
			});
		columns.push({"sWidth": "53%"});
		columns.push({"sWidth": "10%"});
		columns.push({"sWidth": "10%"});
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
	 * 
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
		
//		$('#formId').submit(function() {
//			return false;
//		});
		
		// Configs font-size for header dialog and buttons
		$(selector).parent().css('font-size','0.7em');
	}},
	
	/**
	 * 
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
							flashMessage.show(data.message, {header: com.em.FlashMessage.FAILURE});
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
					flashMessage.flashError(errorThrown,{header : com.em.FlashMessage.ERROR});
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
							flashMessage.show(data.message, {header: com.em.FlashMessage.FAILURE});
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
					flashMessage.flashError(errorThrown,{header: com.em.FlashMessage.ERROR});
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
			var items = $('#tblPosition :checked');
			var itemsChecked = items.serialize();
			if (itemsChecked == '') {
				flashMessage.flashInfo('There is no item selected', {header: com.em.FlashMessage.NOTICE});
				return;
			}
			var action = $(this).attr('href');
			
			jConfirm('Are you sure to delete?', 'Delete Position', function(r) {			    
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
									flashMessage.flashSuccess(data.message, {header: com.em.FlashMessage.SUCCESS});
								} else {
									flashMessage.flashInfo(data.message, {header: com.em.FlashMessage.NOTICE});
								}
							}
						},
						
						complete: function(jqXHR, textStatus) {
							processingDisplay(false);
						},
						
						error: function(jqXHR, textStatus, errorThrown) {
							flashMessage.flashError(errorThrown,{header: com.em.FlashMessage.ERROR});
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
	 *
	 * Validates position form
	 * @param selector
	 */
	setValidatorForm : function(selector) {
		validator = $(selector).validate({
	        rules:{
	        	'name':{
					required: true,
					maxlength: 100
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
	 * Shows flash message if it exists, if not create a new instance of FlashMessage and show it
	 * @param message to show
	 * @param header of the message
	 */
	showAlert: function(message, header) {with (this) {
		if (this.flashMessage == undefined) {
			this.flashMessage = new com.em.FlashMessage();
		}
		flashMessage.show(message, header);
	}},
	
	/**
	 * 
	 * Shows flash message success if it exists, if not creates a new instance of flash message success and shows it.
	 * @param message string
	 * @param header string
	 */
	flashSuccess: function(message, header) {with (this) {
		if (this.flashMessage == undefined) {
			this.flashMessage = new com.em.FlashMessage();
		}
		flashMessage.flashSuccess(message, header);
	}},
	
	/**
	 * 
	 * Shows flash message error if it exists, if not creates a new instance of flash message error and shows it.
	 * @param message string
	 * @param header string
	 */
	flashError: function(message, header) {with (this) {
		if (this.flashMessage == undefined) {
			this.flashMessage = new com.em.FlashMessage();
		}
		flashMessage.flashError(message, header);
	}}
};