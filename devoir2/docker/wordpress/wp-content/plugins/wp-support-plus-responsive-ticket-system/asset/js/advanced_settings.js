jQuery(document).ready(function(){
	getAdvancedSettings();
	jQuery('#tab_advanced_container').click(function(){
		getAdvancedSettings();
	});
	jQuery('#tab_custom_status_container').click(function(){
		getCustomStatusSettings();
	});
	jQuery('#tab_fields_reorder_container').click(function(){
		getFieldReorderSettings();
	});
	jQuery('#tab_ticket_list_container').click(function(){
		getTicketListFieldSettings();
	});
	jQuery('#tab_custom_filter_container').click(function(){
		getCustomFilterFrontEnd();
	});
	jQuery('#tab_custom_priority_container').click(function(){
		getCustomPrioritySettings();
	});
        jQuery('#tab_ckeditor_settings').click(function(){
		getCKEditorSettings();
	});
        jQuery('#tab_export_to_excel_container').click(function(){
		getExportTicketToExcel();
	});
        jQuery('#tab_support_btn_container').click(function(){
		getSupportButton();
	});
        jQuery('#tab_woo_settings_container').click(function(){
		getWooSettings();
	});
        jQuery('#tab_front_end_display_container').click(function(){
		getFrontEndDisplay();
	});
});

function getAdvancedSettings(){
	jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
	jQuery('#settingsAdvanced .wait').show();
	
	var data = {
		'action': 'getAdvancedSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsAdvanced .wait').hide();
		jQuery('#settingsAdvanced .settingsAdvancedContainer').html(response);
		jQuery('#settingsAdvanced .settingsAdvancedContainer').show(function(){
			jQuery('#guest_ticket_submission_message').ckeditor();
                        jQuery('.wp-support-plus-color-picker').wpColorPicker();
		});
	});
}

function setAdvancedSettings(){
    var guest_ticket_submission_message = jQuery('#guest_ticket_submission_message').val().trim();
    var pending_days=jQuery('#pendingTicketClose').val();
    var datecustfield=jQuery('#datecustfield option:selected').val();
    var active_tab=jQuery('#active_tab').val();
    var reply_above=jQuery('input[name=reply_above]:checked').val();
    var wpsp_reply_form_position =jQuery('input[name=wpsp_reply_form_position]:checked').val();
    var wpsp_shortcode_used_in =jQuery('input[name=wpsp_shortcode_used_in]:checked').val();
    var enable_accordion=jQuery('input[name=enable_accordion]:checked').val();
    var ticketId=jQuery('input[name=ticketId]:checked').val();
    var hide_selected_status_ticket=jQuery('#hide_selected_status_ticket option:selected').val();
    var logout_Settings=jQuery('input[name=logout_Settings]:checked').val();
    var admin_bar_Setting=jQuery('input[name=admin_bar_Setting]:checked').val();
    var hide_selected_status_ticket_backend = jQuery("input[name='hideSelectedStatusBackend\\[\\]']:checked")
        .map(function(){return jQuery(this).val();}).get();
    var modify_raised_by = jQuery("input[name='modifyRaisedBy\\[\\]']:checked")
        .map(function(){return jQuery(this).val();}).get();
    var bootcss=jQuery('input[name=bootcss]').is(':checked')?'1':'0';
    var bootjs=jQuery('input[name=bootjs]').is(':checked')?'1':'0';
    var attach_url=jQuery('input[name=wpsp_download_url]:checked').val();
    if (pending_days.trim()=='' || (!isNaN(pending_days) && parseInt(Number(pending_days)) == pending_days && !isNaN(parseInt(pending_days, 10))))
    {
        jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
        jQuery('#settingsAdvanced .wait').show();
        var allowSignUp=0;
        if(jQuery('#wpspAllowSignUp').is(':checked')){
                allowSignUp=1;
        }

        var ticket_alice=new Array();
        var aliceCounter=1;
        jQuery('[name=wpspTicketAlice]').each(function() {
            ticket_alice[aliceCounter]=jQuery(this).val();
            aliceCounter++;
        });
        
        var guest_ticket_redirect=0;
        if(jQuery('#guest_ticket_redirect').is(':checked')){
	        guest_ticket_redirect=1;
	}
        var guest_ticket_redirect_url=jQuery('#guest_ticket_redirect_url').val().trim();
        var message_for_ticket_url=jQuery('#wpsp_ticket_url_message').val().trim();
        var wpsp_ticket_list=jQuery('input[name=wpsp_ticket_list]:checked').val();
        var data = {
            'action': 'setAdvancedSettings',
            'guest_ticket_submission_message': guest_ticket_submission_message,
            'pending_ticket_close' : pending_days,
            'allowSignUp':allowSignUp,
            'defaultRole':jQuery('#wpspSignUpDefaultRole').val(),
            'ticket_label_alice':ticket_alice,
            'wpsp_reply_form_position': wpsp_reply_form_position,
            'wpsp_shortcode_used_in':wpsp_shortcode_used_in,
            'enable_accordion':enable_accordion,
            'hide_selected_status_ticket':hide_selected_status_ticket,
            'hide_selected_status_ticket_backend':hide_selected_status_ticket_backend,
            'modify_raised_by':modify_raised_by,
            'wpsp_dashboard_menu_label':jQuery('#dashboardMenuLabel').val(),
            'logout_Settings':logout_Settings,
            'admin_bar_Setting':admin_bar_Setting,
            'ticket_link_page':jQuery('#setTicketLinkPage').val(),
            'ticketId':ticketId,
            'wpsp_ticket_id_prefix':jQuery('#wpsp_ticket_id_prefix').val().trim(),
            'reply_above':reply_above,
            'datecustfield':datecustfield,
            'active_tab':active_tab,
            'guest_ticket_redirect':guest_ticket_redirect,
            'guest_ticket_redirect_url':guest_ticket_redirect_url,
            'message_for_ticket_url':message_for_ticket_url,
            'wpsp_ticket_list':wpsp_ticket_list,
            'wpspAttachMaxFileSize':jQuery('#wpspAttachMaxFileSize').val().trim(),
            'wpspBootstrapCSSSetting':bootcss,
            'wpspBootstrapJSSetting':bootjs,
            'wpsp_attach_download_url':attach_url,
            'wpspAttachment_bc':jQuery('#wpspAttachment_bc').val(),             
            'wpspAttachment_pc':jQuery('#wpspAttachment_pc').val()
        };

        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            getAdvancedSettings();
        });
    }
    else
    {
        alert(display_ticket_data.insert_integer_value);
        jQuery('#pendingTicketClose').val('');
        jQuery('#pendingTicketClose').focus();
    }
}

function getCustomStatusSettings(){
	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
	
	var data = {
		'action': 'getCustomStatusSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomStatus .wait').hide();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').html(response);
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').show();
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 1 - Change Custom Status Color
		 * Initialise color picker
		 */
		jQuery('.wp-support-plus-color-picker').wpColorPicker();
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		 */
	});
}

function delete_custom_status(id){
	if(confirm(display_ticket_data.sure+display_ticket_data.custom_status_warning)){
		jQuery('#settingsCustomStatus .wait').show();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
		
		var data = {
			'action': 'deleteCustomStatus',
			'id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomStatusSettings();
		});
	}
}

function getFieldReorderSettings(){
	jQuery('#settingsFieldsReorder .wait').show();
	jQuery('#settingsFieldsReorder .settingsCustomStatusContainer').hide();
	
	var data = {
		'action': 'getFieldsReorderSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsFieldsReorder .wait').hide();
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').html(response);
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').show();
	});
}

function setFieldReorderSettings(){
	jQuery('#settingsFieldsReorder .wait').show();
	jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').hide();
	var Adata=new Array();
	var Adata_display=new Array();
	jQuery('#field_order_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==3)
			{
				field_name="field_status_"+html_data;
				field_id=html_data;
				Adata.push(html_data);
			}
			if(counter==4)
			{
				if(jQuery('input:radio[name='+field_name+']:checked').val()==1){
					Adata_display.push(field_id);
				}
			}
			counter++;
            	});
	});
	
	var data = {
		'action': 'setFieldsReorderSettings',
		'data': Adata,
		'display_data': Adata_display,
		'name_label':jQuery('#wpsp_default_name_label').val(),
		'email_label':jQuery('#wpsp_default_email_label').val(),
		'subject_label':jQuery('#wpsp_default_subject_label').val(),
		'description_label':jQuery('#wpsp_default_description_label').val(),
		'category_label':jQuery('#wpsp_default_category_label').val(),
		'priority_label':jQuery('#wpsp_default_priority_label').val(),
		'attachment_label':jQuery('#wpsp_default_attachment_label').val(),
                'wpsp_default_value_of_subject':jQuery('#wpsp_default_subject_value').val()
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getFieldReorderSettings();
	});
}

function getTicketListFieldSettings(){
	jQuery('#settingsTicketListFields .wait').show();
	jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();
	
	var data = {
		'action': 'getTicketListFieldSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsTicketListFields .wait').hide();
		jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').html(response);
		jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').show();
	});
}

function setTicketListFieldSettings(){
	jQuery('#settingsTicketListFields .wait').show();
	jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();

	var Adata_frontend=new Array();
	var Adata_display_frontend=new Array();
	jQuery('#frontend_field_list_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data_frontend =  jQuery(this).html();
			if(counter==3)
			{
				field_name="frontend_field_"+html_data_frontend;
				field_id=html_data_frontend;
				Adata_frontend.push(html_data_frontend);
			}
			if(counter==4)
			{
				Adata_display_frontend.push(jQuery('input:radio[name='+field_name+']:checked').val());
			}
			counter++;
            	});
	});

	var Adata_backend=new Array();
	var Adata_display_backend=new Array();
	jQuery('#backend_field_list_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data_backend =  jQuery(this).html();
			if(counter==3)
			{
				field_name="backend_field_"+html_data_backend;
				field_id=html_data_backend;
				Adata_backend.push(html_data_backend);
			}
			if(counter==4)
			{
				Adata_display_backend.push(jQuery('input:radio[name='+field_name+']:checked').val());
			}
			counter++;
            	});
	});

	

	var data = {
		'action': 'setTicketListFieldSettings',
		'backend_data': Adata_backend,
		'backend_display_data': Adata_display_backend,
		'frontend_data': Adata_frontend,
		'frontend_display_data': Adata_display_frontend
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getTicketListFieldSettings();
        });
}

function getCustomFilterFrontEnd(){
	jQuery('#settingsCustomFilterFrontEnd .wait').show();
	jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').hide();
	
	var data = {
		'action': 'getCustomFilterFrontEnd'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomFilterFrontEnd .wait').hide();
		jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').html(response);
		jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').show();
	});
}

function setCustomFilterFrontEnd(){
	jQuery('#settingsCustomFilterFrontEnd .wait').show();
	jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').hide();

	var logged_in=new Array();
	var agents=new Array();
	var supervisors=new Array();
	jQuery('#custom_filter_front_end tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==3)
			{
				field_name=html_data;
			}
			if(counter==4)
			{
				if(jQuery('#logged_in_'+field_name).attr('checked')=='checked'){
					logged_in.push(field_name);
				}
				
			}
			if(counter==5)
			{
				if(jQuery('#agents_'+field_name).attr('checked')=='checked'){
					agents.push(field_name);
				}
				
			}
			if(counter==6)
			{
				if(jQuery('#supervisors_'+field_name).attr('checked')=='checked'){
					supervisors.push(field_name);
				}
				
			}
			counter++;
        });
	});
	
	var data = {
		'action': 'setCustomFilterFrontEnd',
		'logged_in': logged_in,
		'agents': agents,
		'supervisors': supervisors
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomFilterFrontEnd();
	});
}

function create_custom_status(){
	if(jQuery('#custom_status_text').val().trim()==''){
		alert(display_ticket_data.insert_menu_text);
		jQuery('#custom_status_text').focus();
		return;
	}

	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();	
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 1 - Change Custom Status Color
	 * Add custom_status_color value to post data
	 */
	var data = {
		'action': 'addCustomStatus',
		'custom_status_text': jQuery('#custom_status_text').val().trim(),
		'custom_status_color': jQuery('#custom_status_color').val().trim()
	};
	/*var data = {
		'action': 'addCustomStatus',
		'custom_status_text': jQuery('#custom_status_text').val().trim()
	};*/
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomStatusSettings();
	});
}

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 1 - Change Custom Status Color
 * Initialise color picker
 */
function save_custom_status_color( theID ) {
	var color = jQuery( '#custom_status_color_' + theID).val();

	var data = {
		'action': 'setCustomStatusColor',
		'custom_status_id': theID,
		'custom_status_color': color
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#custom-status-color-saved-' + theID).show().delay(5000).fadeOut();
	});
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */

function getCustomPrioritySettings(){
	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();
	
	var data = {
		'action': 'getCustomPrioritySettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomPriority .wait').hide();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').html(response);
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').show();

		jQuery('.wp-support-plus-color-picker').wpColorPicker();
	});
}

function create_custom_priority(){
	if(jQuery('#custom_priority_text').val().trim()==''){
		alert(display_ticket_data.insert_menu_text);
		jQuery('#custom_priority_text').focus();
		return;
	}

	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();	
	var data = {
		'action': 'addCustomPriority',
		'custom_priority_text': jQuery('#custom_priority_text').val().trim(),
		'custom_priority_color': jQuery('#custom_priority_color').val().trim()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomPrioritySettings();
	});
}

function save_custom_priority_color( theID ) {
	var color = jQuery( '#custom_priority_color_' + theID).val();

	var data = {
		'action': 'setCustomPriorityColor',
		'custom_priority_id': theID,
		'custom_priority_color': color
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#custom-priority-color-saved-' + theID).show().delay(5000).fadeOut();
	});
}

function delete_custom_priority(id){
	if(confirm(display_ticket_data.sure+display_ticket_data.custom_priority_warning)){
		jQuery('#settingsCustomPriority .wait').show();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();
		
		var data = {
			'action': 'deleteCustomPriority',
			'id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomPrioritySettings();
		});
	}
}

function setSubCharLength(){
	var front_end_length=jQuery('#wpsp_frontend_sub_char_length').val().trim();
	var back_end_length=jQuery('#wpsp_backend_sub_char_length').val().trim();
	var data = {
		'action': 'setSubCharLength',
		'front_end_length': front_end_length,
		'back_end_length':back_end_length
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('.setSubCharLengthCSS').show().delay(3000).fadeOut();
	});
}

function setCustomStatusOrder(){
	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();

	var Adata=new Array();
	jQuery('#custom_status_order_table tbody tr').each(function(){
		var counter=1;var status_name="";var status_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==2)
			{
				Adata.push(html_data);
			}
			counter++;
            	});
	});

	var data = {
		'action': 'setCustomStatusOrder',
		'status_order': Adata
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomStatusSettings();
	});
}

function setCustomPriorityOrder(){
	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();

	var Adata=new Array();
	jQuery('#custom_priority_order_table tbody tr').each(function(){
		var counter=1;var status_name="";var status_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==2)
			{
				Adata.push(html_data);
			}
			counter++;
            	});
	});

	var data = {
		'action': 'setCustomPriorityOrder',
		'priority_order': Adata
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomPrioritySettings();
	});
}

function setDateFormat(){
	var cdt_date_format=jQuery('#wpsp_backend_cdt_format').val().trim();
	var udt_date_format=jQuery('#wpsp_backend_udt_format').val().trim();
	var cdt_date_format_front=jQuery('#wpsp_frontend_cdt_format').val().trim();
	var udt_date_format_front=jQuery('#wpsp_frontend_udt_format').val().trim();
	var data = {
		'action': 'setDateFormat',
		'cdt_date_format': cdt_date_format,
		'udt_date_format': udt_date_format,
		'cdt_date_format_front': cdt_date_format_front,
		'udt_date_format_front': udt_date_format_front
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('.setDateFormatCSS').show().delay(3000).fadeOut();
	});
}

function editStatus(id,name,is_default){
	jQuery('#editCustomStatus').show();
	jQuery('#editCustomStatusID').val(id);
	jQuery('#editCustomStatusName').val(name);
	jQuery('#editCustomStatusDefault').val(is_default);
	
	window.location.href='#editCustomStatus';
	jQuery('#editCustomStatusName').focus();
}

function updateCustomStatus(){
	if(jQuery('#editCustomStatusName').val().trim()!=''){
		jQuery('#settingsCustomStatus .wait').show();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();

		var data = {
			'action': 'updateCustomStatus',
			'status_id': jQuery('#editCustomStatusID').val(),
			'name':jQuery('#editCustomStatusName').val(),
			'is_default':jQuery('#editCustomStatusDefault').val()
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomStatusSettings();
		});
	}
	else{
		alert(display_ticket_data.insert_field_label);
		jQuery('#editCustomStatusName').val('');
		jQuery('#editCustomStatusName').focus();
	}
}

function editPriority(id,name,is_default){
	jQuery('#editCustomPriority').show();
	jQuery('#editCustomPriorityID').val(id);
	jQuery('#editCustomPriorityName').val(name);
	jQuery('#editCustomPriorityDefault').val(is_default);
	
	window.location.href='#editCustomPriority';
	jQuery('#editCustomPriorityName').focus();
}

function updateCustomPriority(){
	if(jQuery('#editCustomPriorityName').val().trim()!=''){
		jQuery('#settingsCustomPriority .wait').show();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();

		var data = {
			'action': 'updateCustomPriority',
			'priority_id': jQuery('#editCustomPriorityID').val(),
			'name':jQuery('#editCustomPriorityName').val(),
			'is_default':jQuery('#editCustomPriorityDefault').val()
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomPrioritySettings();
		});
	}
	else{
		alert(display_ticket_data.insert_field_label);
		jQuery('#editCustomPriorityName').val('');
		jQuery('#editCustomPriorityName').focus();
	}
}

function getCKEditorSettings(){
    jQuery('#settingsCKEditor .wait').show();
    jQuery('#settingsCKEditor .settingsCKEditorContainer').hide();

    var data = {
        'action': 'getCKEditorSettings'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#settingsCKEditor .wait').hide();
        jQuery('#settingsCKEditor .settingsCKEditorContainer').html(response);
        jQuery('#settingsCKEditor .settingsCKEditorContainer').show();
    });
}

function setCKEditorSettings(){
    jQuery('#settingsCKEditor .wait').show();
    jQuery('#settingsCKEditor .settingsCKEditorContainer').hide();

    var ck_for_guest='0';
    if(jQuery('#ckeditor_enable_guest').is(':checked')){
        ck_for_guest='1';
    }
    var ck_for_login_user='0';
    if(jQuery('#ckeditor_enable_login_user').is(':checked')){
        ck_for_login_user='1';
    }
    
    var data = {
        'action': 'setCKEditorSettings',
        'guestUserFront': ck_for_guest,
        'loginUserFront': ck_for_login_user
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        getCKEditorSettings()
    });
}

function getExportTicketToExcel(){
    jQuery('#settingsExportToExcel .wait').show();
	jQuery('#settingsExportToExcel .settingsExportToExcelContainer').hide();
	
	var data = {
		'action': 'getExportTicketToExcel'
	};
        
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsExportToExcel .wait').hide();
		jQuery('#settingsExportToExcel .settingsExportToExcelContainer').html(response);
		jQuery('#settingsExportToExcel .settingsExportToExcelContainer').show();
		
	});
}

function setExportTicketToExcel(){
    if(jQuery('#from_export').val().trim()!='' && jQuery('#to_export').val().trim()!=''){
        jQuery('#settingsExportToExcel .wait').show();
        jQuery('#settingsExportToExcel .settingsExportToExcelContainer').hide();

        var from_date=jQuery('#from_export').val();
        var to_date=jQuery('#to_export').val();
        var data = {
            'action': 'setExportTicketToExcel',
            'from_date':from_date,
            'to_date':to_date
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            var obj = jQuery.parseJSON( response );                 
            window.open(obj.url_to_export,'_blank');
            getExportTicketToExcel();
        });
    } else {
        alert(display_ticket_data.export_date_missing);
    }
}
function getSupportButton(){
    jQuery('#settingsSupportButton .wait').show();
	jQuery('#settingsSupportButton .settingsSupportButtonContainer').hide();
	
	var data = {
		'action': 'getSupportButton'
	};
        
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsSupportButton .wait').hide();
		jQuery('#settingsSupportButton .settingsSupportButtonContainer').html(response);
		jQuery('#settingsSupportButton .settingsSupportButtonContainer').show();
		
	});
}
function wpsp_image_upload(){
    var dataform=new FormData(jQuery('#wpsp_upload_icons')[0]);
    dataform.append("action","wpsp_image_upload");
   
    if(jQuery('#wpsp_fileToUpload_first').val().trim()!='' || jQuery('#wpsp_fileToUpload_second').val().trim()!='' || jQuery('#wpsp_fileToUpload_thried').val().trim()!=''){
        jQuery('#settingsSupportButton .wait').show();
        jQuery('#settingsSupportButton .settingsSupportButtonContainer').hide();
     
        jQuery.ajax( {
            url: display_ticket_data.wpsp_ajax_url,
            type: 'POST',
            data: dataform,
            processData: false,
            contentType: false
        }) 
        .done(function( msg ) {
            getSupportButton();
        });
    } else {
        alert(display_ticket_data.select_image);
    }
       
}

function getWooSettings(){
    jQuery('#settingsWooCommerce .wait').show();
    jQuery('#settingsWooCommerce .settingsWooCommerceContainer').hide();

    var data = {
            'action': 'getWooSettings'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#settingsWooCommerce .wait').hide();
            jQuery('#settingsWooCommerce .settingsWooCommerceContainer').html(response);
            jQuery('#settingsWooCommerce .settingsWooCommerceContainer').show(function(){
                jQuery('#wpsp_woo_submission_message').ckeditor();
            });
    });
}

function setWooSettings(){
    jQuery('#settingsWooCommerce .wait').show();
    jQuery('#settingsWooCommerce .settingsWooCommerceContainer').hide();
    
    var wpsp_woo_extension =jQuery('input[name=wpsp_woo_extension]:checked').val();
    var wpsp_prod_help =jQuery('input[name=wpsp_prod_help]:checked').val();
    var wpsp_prod_btn_label =jQuery('input[name=wpsp_prod_btn_label]').val();
    var wpsp_order_help =jQuery('input[name=wpsp_order_help]:checked').val();
    var wpsp_order_btn_label =jQuery('input[name=wpsp_order_btn_label]').val();
    var wpsp_woo_submission_message =jQuery('#wpsp_woo_submission_message').val().trim();
    
    var data = {
        'action': 'setWooSettings',
        'wpsp_woo_extension':wpsp_woo_extension,
        'wpsp_prod_help':wpsp_prod_help,
        'wpsp_prod_btn_label':wpsp_prod_btn_label,
        'wpsp_order_help':wpsp_order_help,
        'wpsp_order_btn_label':wpsp_order_btn_label,
        'wpsp_woo_submission_message':wpsp_woo_submission_message
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        getWooSettings();
    });
}
function getFrontEndDisplay(){
    jQuery('#settingsFrontEndDisplay .wait').show();
    jQuery('#settingsFrontEndDisplay .settingsFrontEndDisplayContainer').hide();

    var data = {
            'action': 'getFrontEndDisplay'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#settingsFrontEndDisplay .wait').hide();
            jQuery('#settingsFrontEndDisplay .settingsFrontEndDisplayContainer').html(response);
            jQuery('#settingsFrontEndDisplay .settingsFrontEndDisplayContainer').show(function(){
                jQuery('.wp-support-plus-color-picker').wpColorPicker();
            });
    });
}
function setFrontEndDisplay(){
    jQuery('#settingsFrontEndDisplay .wait').show();
    jQuery('#settingsFrontEndDisplay .settingsFrontEndDisplayContainer').hide();
    
    var wpsp_faq_display_setting = jQuery('input[name=wpsp_faq_display_setting]:checked').val();
    
    var wpsp_hideBackToTicket=0;
    if(jQuery('#hideBackToTicket').is(':checked')){
            wpsp_hideBackToTicket=1;
    }
    
    var wpsp_hideCloseTicket=0;
    if(jQuery('#hideCloseTicket').is(':checked')){
            wpsp_hideCloseTicket=1;
    }
    
    var wpsp_hideMoreAction=0;
    if(jQuery('#hideMoreAction').is(':checked')){
            wpsp_hideMoreAction=1;
    }
    
    var wpsp_hideChangeStatus=0;
    if(jQuery('#hideChangeStatus').is(':checked')){
            wpsp_hideChangeStatus=1;
    }
    
    var wpsp_hideCannedReply=0;
    if(jQuery('#hideCannedReply').is(':checked')){
            wpsp_hideCannedReply=1;
    }
    
    var wpsp_hideAssignAgent=0;
    if(jQuery('#hideAssignAgent').is(':checked')){
            wpsp_hideAssignAgent=1;
    }
    
    var wpsp_hideDeleteTicket=0;
    if(jQuery('#hideDeleteTicket').is(':checked')){
            wpsp_hideDeleteTicket=1;
    }
    
    var wpsp_hideCC=0;
    if(jQuery('#hideCC').is(':checked')){
            wpsp_hideCC=1;
    }
    
    var wpsp_hideBCC=0;
    if(jQuery('#hideBCC').is(':checked')){
            wpsp_hideBCC=1;
    }
    
    var wpsp_hideStatus=0;
    if(jQuery('#hideStatus').is(':checked')){
            wpsp_hideStatus=1;
    }
    
    var wpsp_hideCategory=0;
    if(jQuery('#hideCategory').is(':checked')){
            wpsp_hideCategory=1;
    }
    
    var wpsp_hidePriority=0;
    if(jQuery('#hidePriority').is(':checked')){
            wpsp_hidePriority=1;
    }
    
    var wpsp_hideAttachments=0;
    if(jQuery('#hideAttachments').is(':checked')){
            wpsp_hideAttachments=1;
    }
    
    var wpsp_hideAddNotes=0;
    if(jQuery('#hideAddNotes').is(':checked')){
            wpsp_hideAddNotes=1;
    }
    
    var wpsp_hideSubmitReply=0;
    if(jQuery('#hideSubmitReply').is(':checked')){
            wpsp_hideSubmitReply=1;
    }
    
    var wpsp_hideEmail=0;
    if(jQuery('#hideEmail').is(':checked')){
            wpsp_hideEmail=1;
    }
    
    var wpsp_hideDaysMonthsYearAgo=0;
    if(jQuery('#hideDaysMonthsYearAgo').is(':checked')){
            wpsp_hideDaysMonthsYearAgo=1;
    }
    
    var wpsp_hideExactDate=0;
    if(jQuery('#hideExactDate').is(':checked')){
            wpsp_hideExactDate=1;
    }
    
    var wpsp_hideExactTime=0;
    if(jQuery('#hideExactTime').is(':checked')){
            wpsp_hideExactTime=1;
    }
    var wpsp_ChangeRaisedBy=0;
     if(jQuery('#hideChangeRaisedBy').is(':checked')){
             wpsp_ChangeRaisedBy=1;
    }
    var front_end_display_alice=new Array();
        var aliceCounter=1;
        jQuery('[name=wpspFrontEndDisplayAlice]').each(function() {
            front_end_display_alice[aliceCounter]=jQuery(this).val();
            aliceCounter++;
    });
    var wpsp_btt_fc=jQuery('#wpsp_btt_fc').val();
    var wpsp_btt_bc=jQuery('#wpsp_btt_bc').val();
    var wpsp_ct_fc=jQuery('#wpsp_ct_fc').val();
    var wpsp_ct_bc=jQuery('#wpsp_ct_bc').val();
    var wpsp_ma_fc=jQuery('#wpsp_ma_fc').val();
    var wpsp_ma_bc=jQuery('#wpsp_ma_bc').val();   
    var wpsp_cs_fc=jQuery('#wpsp_cs_fc').val();
    var wpsp_cs_bc=jQuery('#wpsp_cs_bc').val();
    var wpsp_cr_fc=jQuery('#wpsp_cr_fc').val();
    var wpsp_cr_bc=jQuery('#wpsp_cr_bc').val();
    var wpsp_aa_fc=jQuery('#wpsp_aa_fc').val();
    var wpsp_aa_bc=jQuery('#wpsp_aa_bc').val();
    var wpsp_dt_fc=jQuery('#wpsp_dt_fc').val();
    var wpsp_dt_bc=jQuery('#wpsp_dt_bc').val();
    var wpsp_an_fc=jQuery('#wpsp_an_fc').val();
    var wpsp_an_bc=jQuery('#wpsp_an_bc').val();
    var wpsp_sr_fc=jQuery('#wpsp_sr_fc').val();
    var wpsp_sr_bc=jQuery('#wpsp_sr_bc').val();
    var wpsp_cb_fc=jQuery('#wpsp_cb_fc').val();
    var wpsp_cb_bc=jQuery('#wpsp_cb_bc').val();
   
    var data = {
            'action': 'setFrontEndDisplay',
            'wpsp_faq_display_setting':wpsp_faq_display_setting,
            'wpsp_hideBackToTicket':wpsp_hideBackToTicket,
            'wpsp_hideCloseTicket':wpsp_hideCloseTicket,
            'wpsp_hideMoreAction':wpsp_hideMoreAction,
            'wpsp_hideChangeStatus':wpsp_hideChangeStatus,           
            'wpsp_hideCannedReply':wpsp_hideCannedReply,
            'wpsp_hideAssignAgent':wpsp_hideAssignAgent,
            'wpsp_hideDeleteTicket':wpsp_hideDeleteTicket,
            'wpsp_hideCC':wpsp_hideCC,
            'wpsp_hideBCC':wpsp_hideBCC,
            'wpsp_hideStatus':wpsp_hideStatus,
            'wpsp_hideCategory':wpsp_hideCategory,
            'wpsp_hidePriority':wpsp_hidePriority,
            'wpsp_hideAttachments':wpsp_hideAttachments,
            'wpsp_hideAddNotes':wpsp_hideAddNotes,
            'wpsp_hideSubmitReply':wpsp_hideSubmitReply,
            'wpsp_hideEmail':wpsp_hideEmail,
            'wpsp_hideDaysMonthsYearAgo':wpsp_hideDaysMonthsYearAgo,
            'wpsp_hideExactDate':wpsp_hideExactDate,
            'wpsp_hideExactTime':wpsp_hideExactTime,
            'front_end_display_alice':front_end_display_alice,
            'wpsp_ChangeRaisedBy':wpsp_ChangeRaisedBy,
            'wpsp_btt_fc':wpsp_btt_fc,
            'wpsp_btt_bc':wpsp_btt_bc,
            'wpsp_ct_fc':wpsp_ct_fc,
            'wpsp_ct_bc':wpsp_ct_bc,
            'wpsp_ma_fc':wpsp_ma_fc,
            'wpsp_ma_bc':wpsp_ma_bc,
            'wpsp_cs_fc':wpsp_cs_fc,
            'wpsp_cs_bc':wpsp_cs_bc,
            'wpsp_cr_fc':wpsp_cr_fc,
            'wpsp_cr_bc':wpsp_cr_bc,
            'wpsp_aa_fc':wpsp_aa_fc,
            'wpsp_aa_bc':wpsp_aa_bc,
            'wpsp_dt_fc':wpsp_dt_fc,
            'wpsp_dt_bc':wpsp_dt_bc,
            'wpsp_an_fc':wpsp_an_fc,
            'wpsp_an_bc':wpsp_an_bc,
            'wpsp_sr_fc':wpsp_sr_fc,           
            'wpsp_sr_bc':wpsp_sr_bc,
            'wpsp_cb_fc':wpsp_cb_fc,           
            'wpsp_cb_bc':wpsp_cb_bc  
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        getFrontEndDisplay();
    });
}
