jQuery(document).ready(function(){
	et_get_create_new_ticket();
	jQuery('#tab_advanced_container').click(function(){
		et_get_create_new_ticket();
	});
	jQuery('#tab_custom_status_container').click(function(){
		et_get_reply_ticket();
	});
	jQuery('#tab_custom_priority_container').click(function(){
		et_get_change_ticket_status();
	});
	jQuery('#tab_fields_reorder_container').click(function(){
		et_get_assign_agent();
	});
	jQuery('#tab_ticket_list_container').click(function(){
		et_get_delete_ticket();
	});
	
});

function et_get_create_new_ticket(){
	jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
	jQuery('#settingsAdvanced .wait').show();
	
	var data = {
		'action': 'getETCreateNewTicket'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsAdvanced .wait').hide();
		jQuery('#settingsAdvanced .settingsAdvancedContainer').html(response);
		jQuery('#settingsAdvanced .settingsAdvancedContainer').show(function(){
			jQuery('#wpsp_et_success_email_body').ckeditor();
			jQuery('#wpsp_et_staff_email_body').ckeditor();
			jQuery( '#frmETCreateNewTicket' ).unbind('submit');
			jQuery( '#frmETCreateNewTicket' ).submit( function( e ) {
				
				jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
				jQuery('#settingsAdvanced .wait').show();
				var dataform=new FormData( this );
				dataform.append("action", 'setEtCreateNewTicket');
				dataform.append("wpsp_et_success_email_body", jQuery('#wpsp_et_success_email_body').val().trim());
				dataform.append("wpsp_et_staff_email_body", jQuery('#wpsp_et_staff_email_body').val().trim());
				
				jQuery.ajax( {
			      url: display_ticket_data.wpsp_ajax_url,
			      type: 'POST',
			      data: dataform,
			      processData: false,
			      contentType: false
			    }) 
			    .done(function( msg ) {
			    	et_get_create_new_ticket();
			    });
				
				e.preventDefault();
			});
		});
	});
}

function et_get_reply_ticket(){
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
	jQuery('#settingsCustomStatus .wait').show();
	
	var data = {
		'action': 'getETReplayTicket'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomStatus .wait').hide();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').html(response);
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').show(function(){
			jQuery('#wpsp_et_staff_email_body_for_reply').ckeditor();
			jQuery( '#frmETReplyTicket' ).unbind('submit');
			jQuery( '#frmETReplyTicket' ).submit( function( e ) {
				
                            jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
                            jQuery('#settingsCustomStatus .wait').show();
                            var dataform=new FormData( this );
                            dataform.append("action", 'setEtReplyTicket');
                            dataform.append("wpsp_et_staff_email_body", jQuery('#wpsp_et_staff_email_body_for_reply').val().trim());

                            jQuery.ajax( {
			      url: display_ticket_data.wpsp_ajax_url,
			      type: 'POST',
			      data: dataform,
			      processData: false,
			      contentType: false
			    }) 
			    .done(function( msg ) {
			    	et_get_reply_ticket();
			    });
				
				e.preventDefault();
			});
		});
	});
}

function et_get_change_ticket_status(){
	jQuery('#settingsCustomPriority .settingsCustomFilterFrontEndContainer').hide();
	jQuery('#settingsCustomPriority .wait').show();
	
	var data = {
		'action': 'getETChangeTicketStatus'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomPriority .wait').hide();
		jQuery('#settingsCustomPriority .settingsCustomFilterFrontEndContainer').html(response);
		jQuery('#settingsCustomPriority .settingsCustomFilterFrontEndContainer').show(function(){
			jQuery('#wpsp_et_change_ticket_status_body').ckeditor();
                        jQuery( '#frmETChangeTicketStatus' ).unbind('submit');
			jQuery( '#frmETChangeTicketStatus' ).submit( function( e ) {
				
                            jQuery('#settingsCustomPriority .settingsCustomFilterFrontEndContainer').hide();
                            jQuery('#settingsCustomPriority .wait').show();
                            var dataform=new FormData( this );
                            dataform.append("action", 'setEtChangeTicketStatus');
                            dataform.append("wpsp_et_change_ticket_status_body", jQuery('#wpsp_et_change_ticket_status_body').val().trim());
				
                            jQuery.ajax( {
                                url: display_ticket_data.wpsp_ajax_url,
                                type: 'POST',
                                data: dataform,
                                processData: false,
                                contentType: false
			    }) 
			    .done(function( msg ) {
			    	et_get_change_ticket_status();
			    });
				
                            e.preventDefault();
			});
		});
	});
}

function et_get_assign_agent(){
	jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').hide();
	jQuery('#settingsFieldsReorder .wait').show();
	
	var data = {
		'action': 'getETAssignAgent'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsFieldsReorder .wait').hide();
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').html(response);
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').show(function(){
			jQuery('#wpsp_et_assign_agent_body').ckeditor();
                        jQuery( '#frmETAssignAgent' ).unbind('submit');
			jQuery( '#frmETAssignAgent' ).submit( function( e ) {
				
                            jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').hide();
                            jQuery('#settingsFieldsReorder .wait').show();
                            var dataform=new FormData( this );
                            dataform.append("action", 'setETAssignAgent');
                            dataform.append("wpsp_et_assign_agent_body", jQuery('#wpsp_et_assign_agent_body').val().trim());
				
                            jQuery.ajax( {
                                url: display_ticket_data.wpsp_ajax_url,
                                type: 'POST',
                                data: dataform,
                                processData: false,
                                contentType: false
			    }) 
			    .done(function( msg ) {
			    	et_get_assign_agent();
			    });
				
                            e.preventDefault();
			});
		});
	});
}

function et_get_delete_ticket(){
    jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();
    jQuery('#settingsTicketListFields .wait').show();

    var data = {
        'action': 'getETDeleteTicket'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#settingsTicketListFields .wait').hide();
        jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').html(response);
        jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').show(function(){
            jQuery('#wpsp_et_delete_body').ckeditor();
            jQuery( '#frmETDeleteTicket' ).unbind('submit');
            jQuery( '#frmETDeleteTicket' ).submit( function( e ) {
                jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();
                jQuery('#settingsTicketListFields .wait').show();
                var dataform=new FormData( this );
                dataform.append("action", 'setETDeleteTicket');
                dataform.append("wpsp_et_delete_body", jQuery('#wpsp_et_delete_body').val().trim());
                jQuery.ajax( {
                    url: display_ticket_data.wpsp_ajax_url,
                    type: 'POST',
                    data: dataform,
                    processData: false,
                    contentType: false
                }) 
                .done(function( msg ) {
                    et_get_delete_ticket();
                });
                e.preventDefault();
            });
        });
    });
}