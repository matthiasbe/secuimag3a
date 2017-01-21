jQuery(document).ready(function(){
	getGeneralSettings();
	jQuery('#tab_general_container').click(function(){
		getGeneralSettings();
	});
	jQuery('#tab_category_container').click(function(){
		getCategorySettings();
	});
	jQuery('#tab_mail_container').click(function(){
		getMailSettings();
	});
	jQuery('#tab_slider_menu_container').click(function(){
		getCustomSliderMenus();
	});
	jQuery('#tab_role_manege_menu_container').click(function(){
		getRollManagementSettings();
	});
	jQuery('#tab_custom_fields').click(function(){
		getCustomFields();
	});
	jQuery('#tab_faq_category_container').click(function(){
		getFaqCategorySettings();
	});
	jQuery('#tab_custom_css_container').click(function(){
		getCustomCSSSettings();
	});
        jQuery('#tab_addon_license_container').click(function(){
		getAddOnLicenses();
	});
});

function getGeneralSettings(){
	jQuery('#settingsGeneral .settingsGeneralContainer').hide();
	jQuery('#settingsGeneral .wait').show();
	
	var data = {
		'action': 'getGeneralSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsGeneral .wait').hide();
		jQuery('#settingsGeneral .settingsGeneralContainer').html(response);
		jQuery('#settingsGeneral .settingsGeneralContainer').show();
	});
}

function setGeneralSettings(){
	jQuery('#settingsGeneral .settingsGeneralContainer').hide();
	jQuery('#settingsGeneral .wait').show();
	var enable_btn=0;
	if(jQuery('#setEnableSupportBtn').is(':checked')){
		enable_btn=1;
	}
	var enable_guest_ticket=0;
	if(jQuery('#setEnableGuestTicket').is(':checked')){
		enable_guest_ticket=1;
	}

	var enable_default_login=0;
	if(jQuery('#setEnableDefaultLogin').is(':checked')){
		enable_default_login=1;
	}

	var enable_ticket_type_selection_for_front_end=0;
	if(jQuery('#enableUserSelectionPublicPrivate').is(':checked')){
		enable_ticket_type_selection_for_front_end=1;
 	}

	var allow_agents_to_assign_tickets=0;
	if(jQuery('#setAgentTicketAssign').is(':checked')){
		allow_agents_to_assign_tickets=1;
	}
	
	var allow_agents_to_delete_tickets=0;
	if(jQuery('#setAgentTicketDelete').is(':checked')){
		allow_agents_to_delete_tickets=1;
	}

	var allow_attachment_guest_ticket=0;
	if(jQuery('#setAllowAttachmentGuestTicket').is(':checked')){
		allow_attachment_guest_ticket=1;
 	}
 	
	var front_end_submission = 0;
	if( jQuery( '#setFrontEndSubmission' ).is( ':checked' ) ) {
		front_end_submission = 1;
	}
        
        var enable_register_guest=0;
        if(jQuery('#setEnableregisterguestuser').is(':checked')){
	        enable_register_guest=1;
	}

	var data = {
		'action': 'setGeneralSettings',
		'post_id': jQuery('#setSupportPage').val(),
		'enable_support_button':enable_btn,
		'support_button_position':jQuery('#setBtnPosition').val(),
		'enable_guest_ticket':enable_guest_ticket,
		'fbAppID': jQuery('#fbAppID').val(),
		'fbAppSecret': jQuery('#fbAppSecret').val(),
		'enable_slider_menu':jQuery('input[name=rdbEnableSliderMenu]:checked').val(),
		'support_title': jQuery('#txtSupportTitle').val(),
		'support_phone_number': jQuery('#txtPhoneNumber').val(),
		'display_skype_chat': jQuery('input[name=rdbAvailableChat]:checked').val(),
		'display_skype_call': jQuery('input[name=rdbAvailableCall]:checked').val(),
		'default_ticket_category' : jQuery('#default_ticket_category').val(),
		'enable_default_login':enable_default_login,
		'enable_user_selection_public_private' : enable_ticket_type_selection_for_front_end,
		'default_ticket_type' : jQuery('#setDefaultTicketType').val(),
		'allow_agents_to_assign_tickets' : allow_agents_to_assign_tickets,
		'allow_agents_to_delete_tickets' : allow_agents_to_delete_tickets,
		'allow_attachment_guest_ticket'  : allow_attachment_guest_ticket,
		'ticket_status_after_cust_reply' : jQuery('#default_ticket_status_after_cust_reply').val(),
		'google_nocaptcha_key' : jQuery('#setGoogleCaptchaAPIKey').val().trim(),
		'google_nocaptcha_secret' : jQuery('#setGoogleCaptchaSecKey').val().trim(),
		'front_end_submission' : front_end_submission,
		'default_new_ticket_status' : jQuery('#default_ticket_status').val(),
                'default_login_module': jQuery('input:radio[name=wpsp_default_login_module]:checked').val(),
                'close_ticket_btn_status' : jQuery('#close_btn_ticket_status').val(),
                'close_btn_alice':jQuery('#wpsp_close_btn_alice').val(),
                'enable_register_guest_user':enable_register_guest,
                'guest_user_role':jQuery('#setUserRole').val()
	};
	/*var data = {
		'action': 'setGeneralSettings',
		'post_id': jQuery('#setSupportPage').val(),
		'enable_support_button':enable_btn,
		'support_button_position':jQuery('#setBtnPosition').val(),
		'enable_guest_ticket':enable_guest_ticket,
		'fbAppID': jQuery('#fbAppID').val(),
		'fbAppSecret': jQuery('#fbAppSecret').val(),
		'enable_slider_menu':jQuery('input[name=rdbEnableSliderMenu]:checked').val(),
		'support_title': jQuery('#txtSupportTitle').val(),
		'support_phone_number': jQuery('#txtPhoneNumber').val(),
		'display_skype_chat': jQuery('input[name=rdbAvailableChat]:checked').val(),
		'display_skype_call': jQuery('input[name=rdbAvailableCall]:checked').val(),
		'default_ticket_category' : jQuery('#default_ticket_category').val(),
		'enable_default_login':enable_default_login,
		'enable_user_selection_public_private' : enable_ticket_type_selection_for_front_end,
		'default_ticket_type' : jQuery('#setDefaultTicketType').val(),
		'allow_agents_to_assign_tickets' : allow_agents_to_assign_tickets,
		'allow_agents_to_delete_tickets' : allow_agents_to_delete_tickets,
		'allow_attachment_guest_ticket'  : allow_attachment_guest_ticket,
		'ticket_status_after_cust_reply' : jQuery('#default_ticket_status_after_cust_reply').val(),
		'google_nocaptcha_key' : jQuery('#setGoogleCaptchaAPIKey').val().trim(),
		'google_nocaptcha_secret' : jQuery('#setGoogleCaptchaSecKey').val().trim()
	};*/
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getGeneralSettings();
	});
}

function getCategorySettings(){
	jQuery('#settingsCategories .wait').show();
	jQuery('#settingsCategories .settingsCategoriesContainer').hide();
	
	var data = {
		'action': 'getCategories'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCategories .wait').hide();
		jQuery('#settingsCategories .settingsCategoriesContainer').html(response);
		jQuery('#settingsCategories .settingsCategoriesContainer').show();
	});
}

function getMailSettings(){
	jQuery('#settingsMail .wait').show();
	jQuery('#settingsMail .settingsMailContainer').hide();
	
	var data = {
		'action': 'getEmailNotificationSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsMail .wait').hide();
		jQuery('#settingsMail .settingsMailContainer').html(response);
		jQuery('#settingsMail .settingsMailContainer').show();
	});
}

function createNewCategory(){
	if(jQuery('#newCatName').val().trim()!=''){
		jQuery('#settingsCategories .wait').show();
		jQuery('#settingsCategories .settingsCategoriesContainer').hide();
		
		var data = {
			'action': 'createNewCategory',
			'cat_name':jQuery('#newCatName').val(),
			'cat_assignee':jQuery('#setCatAgent').val()
		};
                
                data=wpsp_filter_create_cat_data(data);

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCategorySettings();
		});
	}
	else{
		alert(display_ticket_data.insert_cat_name);
		jQuery('#newCatName').val('');
		jQuery('#newCatName').focus();
	}
}

function updateCategory(){
	if(jQuery('#editCatName').val().trim()!=''){
		jQuery('#settingsCategories .wait').show();
		jQuery('#settingsCategories .settingsCategoriesContainer').hide();
		
		var data = {
			'action': 'updateCategory',
			'cat_id': jQuery('#editCatID').val(),
			'cat_name':jQuery('#editCatName').val(),
			'cat_assignee':jQuery('#editCatAgent').val()
		};
                
                data=wpsp_filter_update_cat_data(data);

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCategorySettings();
		});
	}
	else{
		alert(display_ticket_data.insert_cat_name);
		jQuery('#editCatName').val('');
		jQuery('#editCatName').focus();
	}
}

function deleteCategory(id){
	var str="All Tickets in this category will be moved to General.\nAre you sure to delete?";
	if(confirm(str)){
		jQuery('#settingsCategories .wait').show();
		jQuery('#settingsCategories .settingsCategoriesContainer').hide();
		
		var data = {
			'action': 'deleteCategory',
			'cat_id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCategorySettings();
		});
	}
}

function setEmailSettings(){
	if(jQuery('#adminEmails').val().trim()!=''){
		var enable_email_pipe=0;
                if(jQuery('#setEnableEmailPipe').is(':checked')){
                        enable_email_pipe=1;
                }
                
                jQuery('#settingsMail .wait').show();
		jQuery('#settingsMail .settingsMailContainer').hide();
		
		var data = {
			'action': 'setEmailSettings',
			'default_from_email': jQuery('#txtFromEmail').val(),
			'default_from_name': jQuery('#txtFromName').val(),
			'administrator_emails': jQuery('#adminEmails').val(),
			'enable_email_pipe': enable_email_pipe,
			'default_reply_to': jQuery('#wpsp_txtReplyTo').val(),
                        'piping_type':jQuery('#wpspPipingTypeSel').val(),
                        'ignore_emails':jQuery('#ignoreEmails').val()
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getMailSettings();
		});
	}
	else{
		alert(display_ticket_data.insert_admin_email_add);
	}
}

function getCustomSliderMenus(){
	jQuery('#settingsCustomSliderMenu .wait').show();
	jQuery('#settingsCustomSliderMenu .settingsSliderMenuContainer').hide();
	
	var data = {
		'action': 'getCustomSliderMenus'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomSliderMenu .wait').hide();
		jQuery('#settingsCustomSliderMenu .settingsSliderMenuContainer').html(response);
		jQuery('#settingsCustomSliderMenu .settingsSliderMenuContainer').show();
	});
}

function change_default_menu_icon(){
	tb_show('', 'media-upload.php?type=image&amp;amp;amp;TB_iframe=true');
		
	/* temporarily redefine send_to_editor() */
	window.send_to_editor = function(html)
	{
		imgurl = jQuery('img',html).attr('src');
		jQuery("#custom_menu_icon").val(imgurl); /*assign the value of the image src to the input*/
		jQuery("#cusom_slider_menu_icon").attr('src',imgurl);
		tb_remove();
	};
	return false;
}

function create_custom_panel_menu(){
	if(jQuery('#custom_menu_text').val().trim()==''){
		alert(display_ticket_data.insert_menu_text);
		jQuery('#custom_menu_text').focus();
		return;
	}
	if(jQuery('#custom_menu_url').val().trim()==''){
		alert(display_ticket_data.insert_redirection_url);
		jQuery('#custom_menu_url').focus();
		return;
	}
	jQuery('#settingsCustomSliderMenu .wait').show();
	jQuery('#settingsCustomSliderMenu .settingsSliderMenuContainer').hide();
	
	var data = {
		'action': 'addCustomSliderMenu',
		'custom_menu_text': jQuery('#custom_menu_text').val().trim(),
		'custom_menu_url': jQuery('#custom_menu_url').val().trim(),
		'custom_menu_icon': jQuery('#custom_menu_icon').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomSliderMenus();
	});
}

function delete_custom_menu(menu_id){
	if(confirm(display_ticket_data.sure)){
		jQuery('#settingsCustomSliderMenu .wait').show();
		jQuery('#settingsCustomSliderMenu .settingsSliderMenuContainer').hide();
		
		var data = {
			'action': 'deleteCustomSliderMenu',
			'menu_id': menu_id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomSliderMenus();
		});
	}
}

function getRollManagementSettings(){
	jQuery('#settingsRoleManegementMenu .wait').show();
	jQuery('#settingsRoleManegementMenu .settingsRoleManegementMenuContainer').hide();
	
	var data = {
		'action': 'getRollManagementSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsRoleManegementMenu .wait').hide();
		jQuery('#settingsRoleManegementMenu .settingsRoleManegementMenuContainer').html(response);
		jQuery('#settingsRoleManegementMenu .settingsRoleManegementMenuContainer').show();
	});
}

function setRoleManagement(){
	jQuery('#settingsRoleManegementMenu .wait').show();
	jQuery('#settingsRoleManegementMenu .settingsRoleManegementMenuContainer').hide();
	
	var agent_role = jQuery("input[name='agentRole\\[\\]']:checked")
    .map(function(){return jQuery(this).val();}).get();
	
	var supervisor_role = jQuery("input[name='supervisorRole\\[\\]']:checked")
    .map(function(){return jQuery(this).val();}).get();

	var front_ticket_all = jQuery("input[name='front_ticket_all']:checked").val();
        if(front_ticket_all!=1){front_ticket_all=0;}

	var front_ticket = jQuery("input[name='frontTicketRole\\[\\]']:checked")
    .map(function(){return jQuery(this).val();}).get();
	
	var data = {
		'action': 'setRoleManagement',
		'agent_role': agent_role,
		'supervisor_role':supervisor_role,
		'front_ticket_all':front_ticket_all,
		'front_ticket':front_ticket
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getRollManagementSettings();
	});
}

function getCustomFields(){
	jQuery('#settingsCustomFields .wait').show();
	jQuery('#settingsCustomFields .settingsCustomFieldsContainer').hide();
	
	var data = {
		'action': 'getCustomFields'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomFields .wait').hide();
		jQuery('#settingsCustomFields .settingsCustomFieldsContainer').html(response);
		jQuery('#settingsCustomFields .settingsCustomFieldsContainer').show();
	});
}

function createNewCustomField(){
	if(jQuery('#newCustFieldName').val().trim()==''){
		alert(display_ticket_data.insert_field_label);
		jQuery('#newCustFieldName').val('');
		jQuery('#newCustFieldName').focus();
	}
	else if((jQuery('#newFieldType').val()=='2' || jQuery('#newFieldType').val()=='3' || jQuery('#newFieldType').val()=='4') && jQuery('#newFieldOptions').val().trim()==''){
		alert(display_ticket_data.insert_field_options);
		jQuery('#newFieldOptions').focus();
	}
	else {
		jQuery('#settingsCustomFields .wait').show();
		jQuery('#settingsCustomFields .settingsCustomFieldsContainer').hide();
		var required=0;
                var isVariableFeild=0;
		if(jQuery('#newCustRequired').attr('checked'))
		{
			required=1;
		}
                if(jQuery('#newisVarFeild').attr('checked'))
		{
			isVariableFeild=1;
		}
		var data = {
			'action': 'createNewCustomField',
			'label':jQuery('#newCustFieldName').val(),
			'required':required,
			'field_type':jQuery('#newFieldType').val(),
			'field_options':jQuery('#newFieldOptions').val(),
                        'field_categories':jQuery('#field_categories').val(),
                        'isVariableFeild':isVariableFeild
		};
                data=updateCreateNewCustomField(data);
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomFields();
		});
	}
}

function editCustomField(id,name,required,field_type,field_options,field_ids,isVarFeild){
	jQuery('#editCustFieldContainer').show();
	jQuery('#editCustFieldID').val(id);
	jQuery('#editCustFieldName').val(name);
	if(required=='1')
	{
		jQuery('#editCustRequired').prop('checked',true);
	}
	else
	{
		jQuery('#editCustRequired').prop('checked',false);
	}
        if(isVarFeild=='1')
	{
		jQuery('#editisVarFeild').prop('checked',true);
	}
	else
	{
		jQuery('#editisVarFeild').prop('checked',false);
	}
	jQuery('#editFieldType').val(field_type);
	if(field_type==2 || field_type==3 || field_type==4)
	{
		jQuery('#edit_field_options').show();
	}
	jQuery('#editFieldOptions').val(field_options.replace(/<br>/g,'\n'));
        jQuery('#field_categories_update').val(field_ids);	
        var default_assignee = field_ids.split(",");
	jQuery('#field_categories_update option').attr('selected', false);
	jQuery(default_assignee).each(function(){
            jQuery('#field_categories_update option[value='+this+']').attr('selected', true);
	});	
	jQuery('#editCustFieldID').val(id);
	jQuery('#editCustFieldName').val(name);
	window.location.href='#editCustFieldContainer';
	jQuery('#editCustFieldName').focus();
}



function deleteCustomField(id,name){
	var str="All Data related to this field will be deleted from all tickets.\nAre you sure to delete '"+name+"'?";
	if(confirm(str)){
		jQuery('#settingsCustomFields .wait').show();
		jQuery('#settingsCustomFields .settingsCustomFieldsContainer').hide();
		
		var data = {
			'action': 'deleteCustomField',
			'field_id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomFields();
		});
	}
}

/************Added for front end role restriction***********************/
function allow_front_role_new_tickets(){
	var front_ticket_all = jQuery("input[name='front_ticket_all']:checked")
	.map(function(){return jQuery(this).val();}).get();
	if(front_ticket_all=="1")
	{
		jQuery("input[name='frontTicketRole\\[\\]']").prop('disabled', true);
	}
	else
	{
		jQuery("input[name='frontTicketRole\\[\\]']").prop('disabled', false);
	}
}
/************End********************************************************/

function select_field_type_options(){
	var field_type=jQuery('#newFieldType').val();
	if(field_type=="1")
	{
		jQuery('#field_options').hide();
	}
	else if(field_type=="2")
	{
		jQuery('#field_options').show();
	}
	else if(field_type=="3")
	{
		jQuery('#field_options').show();
	}
	else if(field_type=="4")
	{
		jQuery('#field_options').show();
	}
	else if(field_type=="5")
	{
		jQuery('#field_options').hide();
	}
}

function select_field_type_options_edit(){
	var field_type=jQuery('#editFieldType').val();
	if(field_type=="1")
	{
		jQuery('#edit_field_options').hide();
	}
	else if(field_type=="2")
	{
		jQuery('#edit_field_options').show();
	}
	else if(field_type=="3")
	{
		jQuery('#edit_field_options').show();
	}
	else if(field_type=="4")
	{
		jQuery('#edit_field_options').show();
	}
	else if(field_type=="5")
	{
		jQuery('#edit_field_options').hide();
	}
}

function getFaqCategorySettings(){
	jQuery('#settingsFaqCategory .wait').show();
	jQuery('#settingsFaqCategory .settingsCategoriesContainer').hide();
	
	var data = {
		'action': 'getFaqCategories'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsFaqCategory .wait').hide();
		jQuery('#settingsFaqCategory .settingsFaqCategoryContainer').html(response);
		jQuery('#settingsFaqCategory .settingsFaqCategoryContainer').show();
	});
}

function createNewFaqCategory(){
	if(jQuery('#newFaqCatName').val().trim()!=''){
		jQuery('#settingsFaqCategory .wait').show();
		jQuery('#settingsFaqCategory .settingsFaqCategoryContainer').hide();
		
		var data = {
			'action': 'createNewFaqCategory',
			'faq_cat_name':jQuery('#newFaqCatName').val()
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getFaqCategorySettings();
		});
	}
	else{
		alert('Please insert category name!');
		jQuery('#newFaqCatName').val('');
		jQuery('#newFaqCatName').focus();
	}
}

function editFaqCategory(id,name){
	jQuery('#editFaqCategoryContainer').show();
	jQuery('#editFaqCatID').val(id);
	jQuery('#editFaqCatName').val(name);
	window.location.href='#editFaqCategoryContainer';
	jQuery('#editFaqCatName').focus();
}


function updateFaqCategory(){
	if(jQuery('#editFaqCatName').val().trim()!=''){
		jQuery('#settingsFaqCategory .wait').show();
		jQuery('#settingsFaqCategory .settingsFaqCategoryContainer').hide();
		
		var data = {
			'action': 'updateFaqCategory',
			'faq_cat_id': jQuery('#editFaqCatID').val(),
			'faq_cat_name':jQuery('#editFaqCatName').val()
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getFaqCategorySettings();
		});
	}
	else{
		alert('Please insert category name!');
		jQuery('#editFaqCatName').val('');
		jQuery('#editFaqCatName').focus();
	}
}

function deleteFaqCategory(id,name){
	var str="All FAQs in this category will be moved to General.\nAre you sure to delete '"+name+"'?";
	if(confirm(str)){
		jQuery('#settingsFaqCategory .wait').show();
		jQuery('#settingsFaqCategory .settingsFaqCategoriesContainer').hide();
		
		var data = {
			'action': 'deleteFaqCategory',
			'faq_cat_id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getFaqCategorySettings();
		});
	}
}

function getCustomCSSSettings(){
	jQuery('#settingsCustomCSS .wait').show();
	jQuery('#settingsCustomCSS .settingsCustomCSSContainer').hide();
	
	var data = {
		'action': 'getCustomCSSSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomCSS .wait').hide();
		jQuery('#settingsCustomCSS .settingsCustomCSSContainer').html(response);
		jQuery('#settingsCustomCSS .settingsCustomCSSContainer').show();
	});
}

function setCustomCSSSettings(){
	jQuery('#settingsCustomCSS .settingsCustomCSSContainer').hide();
	jQuery('#settingsCustomCSS .wait').show();
	var data = {
		'action': 'setCustomCSSSettings',
		'custom_css' : jQuery('#wp_support_plus_custom_css').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomCSSSettings();
	});
}

function getAddOnLicenses(){
    jQuery('#settingsAddOnLicenses .wait').show();
    jQuery('#settingsAddOnLicenses .settingsAddOnLicenses').hide();

    var data = {
            'action': 'getAddOnLicenses'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#settingsAddOnLicenses .wait').hide();
            jQuery('#settingsAddOnLicenses .settingsAddOnLicenses').html(response);
            jQuery('#settingsAddOnLicenses .settingsAddOnLicenses').show();
    });
}

function showHideEmailPipe(){
    if(jQuery('#setEnableEmailPipe:checked').length > 0){
        if(jQuery('#wpspPipingTypeSel').val()=='cpanel'){
            jQuery('#wpspPipingTypeDiv,#wpspPipingIMAPDiv,.wpspPipingIMAPElements').hide();
            jQuery('#wpspPipingTypeDiv,#wpspPipingCpanelDiv').show();
        } else {
            jQuery('#wpspPipingTypeDiv,#wpspPipingCpanelDiv,.wpspPipingIMAPElements').hide();
            jQuery('#wpspPipingTypeDiv,#wpspPipingIMAPDiv').show();
        }
    } else {
        jQuery('#wpspPipingTypeDiv,#wpspPipingCpanelDiv,#wpspPipingIMAPDiv,.wpspPipingIMAPElements').hide();
    }
}

function wpspShowAddImapConnection(){
    jQuery('#wpspImapEmail,#wpspImapIncommingServer,#wpspImapIncommingServerPort,#wpspImapUsername,#wpspImapPassword').val('');
    jQuery("#wpspImapCategory").val(jQuery("#wpspImapCategory option:first").val());
    jQuery("#wpspImapEncryption").val(jQuery("#wpspImapEncryption option:first").val());
    jQuery('.wpspPipingIMAPElements').show();
    jQuery('#wpspImapEmail').focus();
}

function wpspTestIMAPConnection(){
    jQuery('#wpspTestImapConnectionSuccess').hide();
    jQuery('#wpspTestImapConnectionLoading').show();
    var data = {
        'action': 'wpsp_testIMAPConnection',
        'imap_email':jQuery('#wpspImapEmail').val().trim(),
        'imap_category':jQuery('#wpspImapCategory').val(),
        'imap_encryption':jQuery('#wpspImapEncryption').val(),
        'imap_server':jQuery('#wpspImapIncommingServer').val().trim(),
        'imap_port':jQuery('#wpspImapIncommingServerPort').val().trim(),
        'imap_username':jQuery('#wpspImapUsername').val().trim(),
        'imap_password':jQuery('#wpspImapPassword').val()
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpspTestImapConnectionLoading').hide();
        jQuery('#wpspTestImapConnectionSuccess').html(response);
        jQuery('#wpspTestImapConnectionSuccess').show();
        if(response=='success!'){
            getMailSettings();
        }
    });
}


function wpsp_act_license(addon,item_id){
    jQuery('.wpsp_license_error_div').hide();
    jQuery('#wpsp_license_ajax_loading_'+addon).show();
    var data = {
        'action': 'wpsp_act_license',
        'item_id': item_id,
        'license': jQuery('#wpsp_lisense_txt_'+addon).val().trim(),
        'addon_slug':addon
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_license_ajax_loading_'+addon).hide();
        console.log(response);
        jQuery('#wpsp_license_error_div_'+addon).html(response);
        jQuery('#wpsp_license_error_div_'+addon).show();
        if(response=='key activation successfull!'){
            getAddOnLicenses();
        }
    });
}

function wpsp_dact_license(addon,item_id,license){
    jQuery('.wpsp_license_error_div').hide();
    jQuery('#wpsp_license_ajax_loading_'+addon).show();
    var data = {
        'action': 'wpsp_dact_license',
        'item_id': item_id,
        'license': license,
        'addon_slug':addon
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_license_ajax_loading_'+addon).hide();
        console.log(response);
        jQuery('#wpsp_license_error_div_'+addon).html(response);
        jQuery('#wpsp_license_error_div_'+addon).show();
        if(response=='key deactivation successfull!'){
            getAddOnLicenses();
        }
    });
}

function wpsp_check_license(addon,item_id,license){
    var data = {
        'action': 'wpsp_check_license',
        'item_id': item_id,
        'license': license,
        'addon_slug':addon
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_lic_status_ajax_img_'+addon).hide();
        jQuery('#wpsp_lic_status_'+addon).html(response);
        jQuery('#wpsp_lic_status_'+addon).show();
    });
}

function editImapConnection(index){
    wpspShowAddImapConnection();
    var data = {
        'action': 'wpsp_editImapConnection',
        'arr_index':index
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        var json_obj=jQuery.parseJSON(response);
        jQuery('#wpspImapEmail').val(json_obj.imap_email);
        jQuery('#wpspImapCategory').val(json_obj.imap_category);
        jQuery('#wpspImapEncryption').val(json_obj.imap_encryption);
        jQuery('#wpspImapIncommingServer').val(json_obj.incomming_server);
        jQuery('#wpspImapIncommingServerPort').val(json_obj.incomming_port);
        jQuery('#wpspImapUsername').val(json_obj.username);
        jQuery('#wpspImapPassword').val(json_obj.imap_password);
        jQuery('#wpspTestImapConnectionSuccess').hide();
    });
}

function deleteImapConnection(index){
    if(confirm("Are you sure?")){
        jQuery('#settingsMail .wait').show();
	jQuery('#settingsMail .settingsMailContainer').hide();
        
        var data = {
            'action': 'wpsp_deleteImapConnection',
            'arr_index':index
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            getMailSettings();
        });
    }
}
