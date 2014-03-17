
var com = com ||{};
com.em = com.em ||{};

/**
 * This function manages the view for help text update
 *  
 */
	com.em.HelpTextUpdate = function (languageFromId, languageToId, tableHeaderId) {		
		
		this.languageFromId = languageFromId;
		this.languageToId = languageToId;
		this.tableHeaderId = tableHeaderId;
		this.url = {};		
		this.initEvents();
		this.previewDialog;
		this.validatorLeft = this.setValidatorForm('#formHelptextLeft');
		this.validatorRight = this.setValidatorForm('#formHelptextRight');
		this.initAlert();
		this.initPreview();
	};
com.em.HelpTextUpdate.prototype = {
	/**
	 * Inits Jquery alert component
	 *
	 */	
	initAlert : function() {
		this.alert = new com.em.Alert();
	},
	
	/**
	 * Initialize all the events for componentes on page
	 *
	 */
	initEvents : function() {with(this) {
		
		$( "#to-filter-input" ).combobox({
			appendTo 	: 'body'
		});		
		$( "#from-filter-input" ).combobox({
			appendTo 	: 'body',
			select		: function(event, ui) { 				
				refreshCombo("#to-filter-input",'','selected',$("#from-filter-input").val(),true,"");				
			}
		
		});
		
	}},
	
	/**
	 * Initialize modal dialog for preview
	 *
	 */
	initPreview : function() {with(this) {
		
		$( "#dialogPreviewHelpText" ).dialog({
			height: 150,
			width: 150,
			modal: true,
			autoOpen: false
		});
		
	}},
	
	/**
	 * Add event on "change" button
	 *  
	 * @param selector
	 */
	clickToChange : function(selector) { with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			if ($( "#to-filter-input" ).val() != languageToId || $( "#from-filter-input" ).val() != languageFromId) {
   				var confirmationCustomer;
   				confirmationCustomer = confirm("Are you sure to change language? Any unsaved change will be lost");   																						
   				if( !confirmationCustomer ) {
   					
   					if ($( "#from-filter-input" ).val() != languageFromId) {
   						refreshCombo("#to-filter-input",'','selected',languageFromId,true,"");
   					} else {
   						$('#to-filter-input').attr('value',languageFromId);
   	   					$('#to-filter-input').change();
   					}   					
   					$('#from-filter-input').attr('value',languageFromId);
   					$('#from-filter-input').change();   					
   					
   				} else {
   					//refresh field's data with new languagefrom and languageto combination if the user confirm action
   					languageToId = $('#to-filter-input').val();
   					languageFromId = $('#from-filter-input').val();
   					
   					$("#formHelptextLeft input[id=languageId]").val(languageFromId);
   					$("#formHelptextRight input[id=languageId]").val(languageToId);
   					
   					$("#labelFormLeft").text($('#from-filter-input').find("option:selected").text());
   					$("#labelFormRight").text($('#to-filter-input').find("option:selected").text());
   					
   					//reload form data
   					loadForms();
   				}
			}	
		});
		
	}},
	
	/**
	 * Add event on "preview" button
	 *  
	 * @param selector
	 */
	clickToPreview : function(selector) { with (this) {
		$(selector).bind('click',function(event) {
			event.preventDefault();
			//configure dialog according to selector
			if (selector == '#previewLeft') {
				var formSelector = '#formHelptextLeft';
			} else {
				var formSelector = '#formHelptextRight';
			}
			
			$( "#dialogPreviewHelpText" ).dialog('option', 'height', $(formSelector + " input[id=helpTextHeight]").val());
			$( "#dialogPreviewHelpText" ).dialog('option', 'width', $(formSelector + " input[id=helpTextWidth]").val());
			$( "#dialogPreviewHelpText" ).dialog('option', 'title', $(formSelector + " input[id=helpTextHeadline]").val());
			//fill preview div
			$('#dialogPreviewHelpText').html($(formSelector + " textarea[name=helpTextArea]").val());
			$( "#dialogPreviewHelpText" ).dialog('open');
		});
		
	}},
	
	/**
	 * Makes an ajax request to get data for loading right and left form
	 */
	loadForms : function() { with (this) {
		$.ajax({
			dataType 			: 'json', 
			data 				: {	languageFromId 	: languageFromId,
									languageToId	: languageToId,
									id				: tableHeaderId},
			url					: url.toLoad ,
			type				: "POST",
			beforeSend			: function(XMLHttpRequest) {
				processingDisplay(true);
			},			
			complete 			: function(jqXHR, textStatus) {
				processingDisplay(false);
			},
			success 			: function(data, textStatus, XMLHttpRequest) {
				if (data.message == 'Session_Lost') {
					loginAjaxForm.open();
	            	processingDisplay(false);	 
				}
				else {
					loadForm('Right',data.right);
					loadForm('Left',data.left);
				}
			},
			error				: function (jqXHR, textStatus, errorThrown) {
				alert.show(errorThrown, {header : com.em.Alert.ERROR});				
			}
		});
	}},
	
	/**
	 * Load form with data
	 *   
	 * @param string side ('Left'|'Rigth')
	 * @param object data
	 */
	loadForm : function(side, data) { with (this) {
		
		$("#formHelptext" + side + " input[id=helpTextHeight]").val(data.height);
		$("#formHelptext" + side + " input[id=helpTextWidth]").val(data.width);
		$("#formHelptext" + side + " input[id=helpTextHeadline]").val(data.headline);
		
		$('#helpTextArea'+side).val(data.content);
	}},
	
	/**
	 * Validate  education level form
	 * @param selector
	 */
	setValidatorForm : function(selector) { with (this) {
		return $(selector).validate({
			rules			: {				
				"helpTextHeight"	: {
					required		: true,
					min				: 1,
					max				: 9999
				},
				"helpTextWidth"	:{
					required		: true,
					min				: 1,
					max				: 9999
				},
				"helpTextHeadline"	:{
					required		: true,
					minlength		: 1,
					maxlength		: 255
				}
			},
			errorPlacement	: function(error, element) {
				error.appendTo( element.parent("div").parent("div").parent("td"));
			}
		});
	}},
	
	/**
	 * Add an event to save changes on left or right form
	 *   
	 * @param string selector of save button
	 */
	clickToUpdate : function(selector) { with (this){
		$(selector).bind('click',function(event) {
			if (selector == '#saveHelpTextLeft') {
				formSelector = '#formHelptextLeft';
				var validator = validatorLeft;
			}
			else {
				formSelector =  '#formHelptextRight';
				var validator = validatorRight;
			}
			
			$.ajax({
				url: url.toUpdate,
				type: "POST",
				dataType : 'json',
				data : $(formSelector).serialize(),
				beforeSend : function(XMLHttpRequest) {
					//validate form
					validator.form();
					if ( !validator.valid() ) {
						return false;
					}
					processingDisplay(true);
					return true;
				},						
				complete 			: function(jqXHR, textStatus) {
					processingDisplay(false);
				},
				success : function(data, textStatus, XMLHttpRequest) {
					if (data.success) {
						alert.show(data.message,{header : com.em.Alert.SUCCESS});						
					} else if (data.message == 'Session_Lost') {
							loginAjaxForm.open();							
					} else {
						alert.show(data.message,{header : com.em.Alert.FAILURE});
					}
					
				},				
				error : function(jqXHR, textStatus, errorThrown) {
					alert.show(errorThrown,{header : com.em.Alert.ERROR});
				}
			});
			
		}); 
	}},
	
	/**
	 * Configure tinymce textarea and elements
	 *  
	 * @param selector
	 */
	configureTinymce : function(selector) { with (this) {			
		$(selector).tinymce({
			relative_urls : false,
			// Location of TinyMCE script
			document_base_url : '/var/www/html/metasitenew/public/',
			//script_url : url.toBase + '/tinymce/jscripts/tiny_mce/tiny_mce.js',
			// General options
			theme : "advanced",
			

			plugins : "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist,imagemanager",
			
			// Theme options
			theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,insertimage,preview",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,

			// Example content CSS (should be your site CSS)
			content_css : "../../../../../css/screen.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
			imagemanager_rootpath : '{0}/' + tableHeaderId
			
		});
	}},
	
	/**
	 * Show proccessing display for data table
	 * @param bShow boolean
	 */
	processingDisplay : function(bShow) {
		if(bShow)
			$.blockUI({ 
				css : { 
					border					: 'none', 
					padding					: '15px', 
					backgroundColor			: '#000', 
					'-webkit-border-radius'	: '10px', 
					'-moz-border-radius'	: '10px', 
					opacity					: .5, 
					color					: '#fff' 
				} 
			}); 
		else
			$.unblockUI();
	},	
		
	/**
	 * set url for action side server
	 * @param array url
	 */
	setUrl : function(url) {
		this.url = url;
	},
	
	/**
	 * Return the url at parameter 1 with the new name and val inside
	 * @param string purl url to change
	 * @param string pname name of variable
	 * @param string pval value of variable
	 */
	getUrl:function(purl, pname, pval) { with (this) {
		
		res = purl;
		var pos = purl.indexOf('/' + pname + '/');
		var endString = '';
		if(pos == -1)
			res += '/' + pname + '/' + pval;
		else{
			
			var pos2 = res.indexOf('/', pos + 2 + pname.length);
			//get the values after the variable we are looking for
			if(pos2 = -1)
				endString = res.substring(pos2);
			res = res.substring(0,pos);
			res += '/' + pname + '/' + pval + endString;			
		}
		return res;
	}},		
		
	/**
	 * Show alert if it exists, if not create a new instance of alert and show it
	 * @param string par1 message to show
	 * @param string par2 header for message
	 */
	showAlert : function(par1, par2) {with (this) {		
		if(this.alert == undefined)
			this.alert = new com.em.Alert();
		alert.show(par1, par2);
	}},
	
	/**
	 * Refresh the "select" sended in selector with id an name returned from ajax request
	 * @param selector "select" element where refresh will be applied
	 * @param plevel table to get the data from (maingroup|subgroup|skill) 
	 * @param kind_selected set selected option as [all|selected|none] 
	 * 		  "all" adds a new option "all" and keep thah option as selected
	 * 		  "selected" take the value of the current selection and keep it after the combo was repopulated
	 * 		  "none" no option is selected
	 * @param pfilter sends a filter id to controller
	 * @param planguage if true then uses the toLanguage url else the toMaingroup url
	 * @param pexecute execute after the combo is loaded
	 */
	refreshCombo : function(selector, plevel, kind_selected, pfilter, planguage, pexecute) { with (this) {
		var vfilter = '';
		if(pfilter != undefined && pfilter != '')
			vfilter = pfilter;		
		if(kind_selected == 'selected')
			var selected = $(selector).children( ":selected" );		
		
		$(selector).empty();
		$.ajax({
			dataType 			: 'json', 
			data 				: {filter : vfilter},
			url					: url.toLanguage ,
			type				: "POST",
			success 			: function(data, textStatus, XMLHttpRequest) {
				if (data.message == 'Session_Lost') {
					loginAjaxForm.open();
	            	processingDisplay(false);	 
				}
				else {
					var options = '';
					var this_data = data.data;
					if(kind_selected == 'all')
						options += '<option value="all" selected="selected" >All</option>';					
				    for (var i = 0; i < this_data.length; i++) {
				    	if(!planguage)
				    		options += '<option value="' + this_data[i].id + '">' + this_data[i].name + '</option>';
				    	else
				    		options += '<option value="' + this_data[i][0] + '">' + this_data[i][1] + '</option>';
				    }
				    $(selector).html(options);
				    if(kind_selected == 'selected')
				    	$(selector).val(selected.val());
				    $(selector).change();
				    if(pexecute.length > 1)
				    	eval(pexecute);
				}
			},
			error				: function (jqXHR, textStatus, errorThrown) {
				alert.show(errorThrown, {header : com.em.Alert.ERROR});				
			}
		});
	}}	
};
