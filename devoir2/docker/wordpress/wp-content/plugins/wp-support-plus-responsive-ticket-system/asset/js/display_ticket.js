var page_no=0,link=true;
jQuery(document).ready(function(){
	getTickets('','');
	
	jQuery('#tab_ticket_container').click(function(){
		page_no=0;
		getTickets('','');
	});
	
	jQuery( '#wpspBackendTicketFilter' ).submit( function( e ) {
		page_no=0;
		jQuery('.wpspActionDashboardBody').hide();
		getTickets('','');
		e.preventDefault();
	});
	
	jQuery('#tab_create_ticket').click(function(){
		jQuery('#create_ticket_container').hide();
		jQuery('#create_ticket .wait').show();
		var data = {
			'action': 'getCreateTicketForm',
			'backend': 1
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			jQuery('#create_ticket_container').html(response);
			jQuery('#create_ticket .wait').hide();
			jQuery('#create_ticket_container').show(function(){
				jQuery('#create_ticket_body').ckeditor();
				jQuery( '#frmCreateNewTicket' ).unbind('submit');
				jQuery( '#frmCreateNewTicket' ).submit( function( e ) {
					if(validateTicketSubmit()){
						jQuery('#create_ticket_container').hide();
						jQuery('#create_ticket .wait').show();
						jQuery('#guest_name').val(jQuery('#create_ticket_guest_user_name').val());
						jQuery('#guest_email').val(jQuery('#create_ticket_guest_user_email').val());
						jQuery('#type_user_default').val(jQuery('#create_ticket_user_type').val());
						if(jQuery('#create_ticket_user_type').val()=='guest')
						{
							jQuery('#create_ticket_as_user_id').val('0');
						}
						
						var dataform=new FormData( this );
						if(jQuery('#create_ticket_body').val()){
							dataform.append("create_ticket_body", jQuery('#create_ticket_body').val().trim());
						}
						
						jQuery.ajax( {
					      url: display_ticket_data.wpsp_ajax_url,
					      type: 'POST',
					      data: dataform,
					      processData: false,
					      contentType: false
					    }) 
					    .done(function( msg ) {
					    	if(msg==1){
					    		jQuery('#tab_ticket_container')[0].click();
					    	}
					    });
					}
					e.preventDefault();
				});
			});
                        
                        jQuery(window).unbind('beforeunload');
                        jQuery(window).on('beforeunload', function(){
                            if(CKEDITOR.instances['create_ticket_body'].getData().trim()!=''){
                                return 'Are you sure you want to leave?';
                            }
                        });
                        
		});
	});
	
	jQuery('#tab_agent_settings').click(function(){
		jQuery('#agent_settings #agent_settings_area').hide();
		jQuery('#agent_settings .wait').show();
		
		var data = {
			'action': 'getAgentSettings'
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			jQuery('#agent_settings .wait').hide();
			jQuery('#agent_settings #agent_settings_area').html(response);
			jQuery('#agent_settings #agent_settings_area').show();
			jQuery('#agentSignature').ckeditor();
                        jQuery(window).unbind('beforeunload');
		});
	});
		
});

function wpsp_replyTicket(){	
    jQuery('#ticketContainer .ticket_indivisual').hide();
    jQuery('#ticketContainer .wait').show();

    var replyFromObject = document.getElementById('frmThreadReply');
    var dataform=new FormData(replyFromObject);
    dataform.append("replyBody", jQuery('#replyBody').val().trim());

    jQuery.ajax( {
      url: display_ticket_data.wpsp_ajax_url,
      type: 'POST',
      data: dataform,
      processData: false,
      contentType: false
    }) 
    .done(function( msg ) {
        if( msg==1 && display_ticket_data.wpsp_redirect_after_ticket_update==1){
           getTickets('','');
        }
        else{
           openTicket(jQuery('#frmThreadReply input[name=ticket_id]').val().trim());
        }
    });
}

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 15 - add note (no notifications)
 */
function addNote() {
    jQuery("input[name='notify']").val('false');
    wpsp_replyTicket();
}
function getTickets(sortby,order){
	jQuery('#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketActionDashboard,#ticketContainer .wait').show();
	
	var dataform=new FormData(jQuery('#wpspBackendTicketFilter')[0]);
	dataform.append("page_no", page_no);
	dataform.append("action", 'getTickets');
	dataform.append('sortby',sortby);
	dataform.append('order',order);
	
	jQuery.ajax( {
      url: display_ticket_data.wpsp_ajax_url,
      type: 'POST',
      data: dataform,
      processData: false,
      contentType: false
    }) 
    .done(function( response ) {
    	jQuery('#ticketContainer .wait').hide();
        jQuery('#ticketContainer .ticket_list').html(response);
        jQuery('#ticketContainer .ticket_list').show();
        wpspCheckBulkActionVisibility();
        jQuery(window).unbind('beforeunload');
    });
}

function wpsp_imap_loader(){
    jQuery('#ticketContainer .ticket_list').hide();
    jQuery('#ticketContainer .wait').show();
    var data = {
        'action': 'wpsp_check_imap_loader'
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response){
        getTickets('','');
    });
}

function openTicket(ticket_id){
	jQuery('#ticketContainer .wpspActionDashboardBody,#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'openTicket',
		'ticket_id': ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_indivisual').html(response);
		jQuery('#ticketContainer .ticket_indivisual').show(function(){
			jQuery('#replyBody').ckeditor();
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 18 - Thread accordion
		 	 * jQuery accordion for threads
			 */ 
                        var activeAcc=0;
                        if(display_ticket_data.reply_ticket_position==0){
                            activeAcc=jQuery("#threadAccordion h3").length-1;
                        }
                        if(display_ticket_data.enable_accordion==1){
                            jQuery("#threadAccordion").accordion({
                                    heightStyle:'content',
                                    active:activeAcc
                            });
                        }
			/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			 */
                        jQuery(window).unbind('beforeunload');
                        jQuery(window).on('beforeunload', function(){
                            if(CKEDITOR.instances['replyBody'].getData().trim()!=''){
                                return 'Are you sure you want to leave?';
                            }
                        });
		});
	});
}

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 14 - create new ticket from thread
 */ 
function ticketFromThread( thread_id ) {
    var r = confirm( 'Are you sure you wish to create a new ticket from this thread?' );
    if ( r == true ) {
        jQuery('#ticketContainer .wpspActionDashboardBody,#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
        jQuery('#ticketContainer .wait').show();
        
        var data = {
            'action': 'ticketFromThread',
            'thread_id': thread_id
        };
    
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            var ticket_id = response.substring(0,response.length - 1);
            openTicket(ticket_id);
        });
    }
}
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 */

function load_prev_page(prev_page_no,sortby,order){
	if(prev_page_no!=0){
		page_no=prev_page_no-1;
		getTickets(sortby,order);
	}
}

function load_next_page(next_page_no,sortby,order){
	if(next_page_no!=page_no){
		page_no=next_page_no;
		getTickets(sortby,order);
	}
}

function validateTicketSubmit(){
    var flag=true;
    jQuery.each(jQuery( '#frmCreateNewTicket').find('.wpsp_required'),function(){
	if(jQuery(this).val().trim()=='' && jQuery(this).attr('type')!='checkbox'){
            alert(display_ticket_data.insert_all_required);
            jQuery(this).focus();
            flag=false;
            return false;
        }
	if(jQuery(this).attr('type')=='checkbox'){
            var check_attr=jQuery(this).attr('name');
            if(jQuery('input[name="'+check_attr+'"]:checked').length<1) {
                alert(display_ticket_data.insert_all_required);
                flag=false;
                return false;
            }
        }       
    });
        
    if(jQuery('#create_ticket_category').length && jQuery('#create_ticket_category').val() === ''){
        alert(display_ticket_data.insert_all_required); 
        flag=false;
        return false;
    }
    if(jQuery('#create_ticket_priority').length && jQuery('#create_ticket_priority').val() === ''){
        alert(display_ticket_data.insert_all_required); 
        flag=false;
        return false;
    }
        
    var user_type=jQuery('#create_ticket_user_type').val();
    if(user_type=="guest"){
        var email = document.forms["frmCreateNewTicket"]["create_ticket_guest_user_email"].value;
        var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;
        if(!regex.test(email)){
            alert(display_ticket_data.Not_valid_email_address);
            flag=false;
            return false;;
        }
    }

    if(!flag){return false;}
    if(jQuery('#create_ticket_body').val().trim()==''){
        alert(display_ticket_data.insert_all_required);
        return false;
    }
    
    if(wpsp_attachment_share_lock){
        alert(display_ticket_data.wait_until_upload);
        return false;
    }
    
    var extra_validation=wpsp_create_ticket_frm_extra_validation();
    if(!extra_validation){return false;}
    
    return true;
}

function validateReplyTicketSubmit(){
        var flag=true;
	if(jQuery('#replyBody').val().trim()==''){
		alert(display_ticket_data.reply_not_empty);
		return false;
	}
        
        if(wpsp_attachment_share_lock){
            alert(display_ticket_data.wait_until_upload);
            return false;
        }
        
        flag=wpsp_reply_form_extra_validation();
        if(!flag){return false;}
    
	return true;    
}

function backToTicketFromIndisual(){
	getTickets('','');
}

function setSignature(id){
	jQuery('#agent_settings #agent_settings_area').hide();
	jQuery('#agent_settings .wait').show();
	
	var data = {
		'action': 'setAgentSettings',
		'id':id,
		'signature':jQuery('#agentSignature').val().trim(),
		'skype_id':jQuery('#txtAgentSkypeId').val(),
		'chat_availability':jQuery('input[name=rdbAvailableChat]:checked').val(),
		'call_availability':jQuery('input[name=rdbAvailableCall]:checked').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#tab_agent_settings')[0].click();
	});
}

function assignAgent(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'getTicketAssignment',
		'ticket_id':ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_assignment').html(response);
		jQuery('#ticketContainer .ticket_assignment').show();
	});
}

function setTicketAssignment(ticket_id){
	jQuery('#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'setTicketAssignment',
		'ticket_id':ticket_id,
		'agent_id': jQuery('#assignTicketAgentId').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            if( display_ticket_data.wpsp_redirect_after_ticket_update==1){
	        getTickets('','');
	    }
            else{
                openTicket(ticket_id);
            }
        
	});
}

function deleteTicket(ticket_id){
	if(confirm(display_ticket_data.sure_to_delete+"\n("+display_ticket_data.can_not_undone+")"))
	{
		jQuery('#ticketContainer .ticket_indivisual').hide();
		jQuery('#ticketContainer .wait').show();
		
		var data = {
			'action': 'deleteTicket',
			'ticket_id':ticket_id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getTickets('','');
		});
	}
}
 function cloneTicket(ticket_id){
	if(confirm(display_ticket_data.sure_to_clone+"\n("+display_ticket_data.can_not_undone+")"))
	{
		jQuery('#ticketContainer .wpspActionDashboardBody,#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
                jQuery('#ticketContainer .wait').show();
                var data = {
			'action': 'cloneTicket',
			'ticket_id':ticket_id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
                    jQuery('#ticketContainer .wait').hide();
                    jQuery('#ticketContainer .ticket_indivisual').show();
                    var obj = jQuery.parseJSON( response );
                    alert(display_ticket_data.clone_succes+obj.ticket_id);
		});
	}
}
function getChangeTicketStatus(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'getChangeTicketStatus',
		'ticket_id':ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_assignment').html(response);
		jQuery('#ticketContainer .ticket_assignment').show();
	});
}

function setChangeTicketStatus(ticket_id){
	jQuery('#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketContainer .wait').show();
	
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 16 - silent status change
	 */
	var data = {
		'action': 'setChangeTicketStatus',
		'ticket_id':ticket_id,
		'status': jQuery('.ticket_assignment #change_status_ticket_status').val(),
		'category': jQuery('.ticket_assignment #change_status_category').val(),
		'priority': jQuery('.ticket_assignment #change_status_priority').val(),
		'ticket_type': jQuery('.ticket_assignment #change_status_type').val(),
		'notify': jQuery('.ticket_assignment #notify').val()
	};
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
	    if( display_ticket_data.wpsp_redirect_after_ticket_update==1){
	        getTickets('','');
	    }
            else{
                openTicket(ticket_id);
            }
        });
}

function change_user_type(){
	var user_type=jQuery('#create_ticket_user_type').val();
	if(user_type=="user")
	{
		jQuery('#type_user_default').val('user');
		jQuery('#user_type_user').show();
		jQuery('#user_type_guest').hide();
		jQuery('#create_ticket_guest_user_name').removeClass('wpsp_required');
		jQuery('#create_ticket_guest_user_email').removeClass('wpsp_required');
	}
	else if(user_type=="guest")
	{
		jQuery('#type_user_default').val('guest');
		jQuery('#user_type_user').hide();
		jQuery('#user_type_guest').show();
		jQuery('#create_ticket_guest_user_name').addClass('wpsp_required');
		jQuery('#create_ticket_guest_user_email').addClass('wpsp_required');
	}
}

function get_all_checked(){
	if(jQuery('#all_selected').is(':checked')){
		jQuery(".bulk_action_checkbox").not(":disabled").attr("checked", "true");
	}else{
		jQuery(".bulk_action_checkbox").removeAttr("checked");
	}
	wpspCheckBulkActionVisibility();
}

function wpsp_open_apply_filter(){
	jQuery('.wpspActionDashboardBody').hide();
	jQuery('#wpspBodyTicketFilter').slideDown();
}

function wpspOpenBulkChangeStatus(){
	jQuery('.wpspActionDashboardBody').hide();
	jQuery('#wpspBodyChangeBulkStatus').slideDown();
}

function wpspOpenBulkAssignAgent(){
	jQuery('.wpspActionDashboardBody').hide();
	jQuery('#wpspBodyAssignBulkTickets').slideDown();
}

function wpspCheckBulkActionVisibility(){
	var values = jQuery('.bulk_action_checkbox:checked').map(function () {
  		return this.value;
	}).get();
	var str=String(values); 
	
	if(str==''){
		jQuery('.wpspActionDashboardBody').hide();
		jQuery('.wpspBulkActionBtn').slideUp();
	}  
	else {
		jQuery('.wpspActionDashboardBody').hide();
		jQuery('.wpspBulkActionBtn').slideDown();
	}
	
	wpspFilterActionSetToDefault();
}

function wpspHideFilterDashboardBody(){
	jQuery('.wpspActionDashboardBody').slideUp();
	wpspFilterActionSetToDefault();
}

function wpspBulkChangeStatusSubmitChanges(){
	jQuery('#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketContainer .wait').show();
	jQuery('.wpspActionDashboardBody').hide();
	var values = jQuery('.bulk_action_checkbox:checked').map(function () {
  		return this.value;
	}).get();
	var str=String(values);
	
	var data_status = {
		'action': 'setChangeTicketStatusMultiple',
		'ticket_ids':str,
		'status': jQuery('#wpspBodyChangeBulkStatus #change_status_ticket_status').val(),
		'category': jQuery('#wpspBodyChangeBulkStatus #change_status_category').val(),
		'priority': jQuery('#wpspBodyChangeBulkStatus #change_status_priority').val()
	};
	jQuery.post(display_ticket_data.wpsp_ajax_url, data_status, function(response) {
		getTickets('','');
	});
}

function wpspFilterActionSetToDefault(){
	jQuery('#wpspBodyChangeBulkStatus #change_status_ticket_status').val('select');
	jQuery('#wpspBodyChangeBulkStatus #change_status_category').val('select');
	jQuery('#wpspBodyChangeBulkStatus #change_status_priority').val('select');
	
	jQuery('#assignTicketAgentIdMultiple option').removeAttr("selected");
}

function wpspBulkAssignAgentSubmitChanges(){
	var values = jQuery('.bulk_action_checkbox:checked').map(function () {
  		return this.value;
	}).get();
	var str=String(values);
	var agent_ids=jQuery('#assignTicketAgentIdMultiple').val();
	if(agent_ids){
		jQuery('#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
		jQuery('#ticketContainer .wait').show();
		jQuery('.wpspActionDashboardBody').hide();
		
		var data_status = {
			'action': 'setAssignAgentMultiple',
			'ticket_ids':str,
			'agent_ids':agent_ids
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data_status, function(response) {
			getTickets('','');
		});
	}
}

function wpspBulkTicketDelete(){
	var values = jQuery('.bulk_action_checkbox:checked').map(function () {
  		return this.value;
	}).get();
	var str=String(values);
	if(confirm(display_ticket_data.sure_to_delete_mult+"\n("+display_ticket_data.can_not_undone+")")){
		jQuery('#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
		jQuery('#ticketContainer .wait').show();
		jQuery('.wpspActionDashboardBody').hide();
		
		var data_status = {
			'action': 'deleteTicketMultiple',
			'ticket_ids':str
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data_status, function(response) {
			getTickets('','');
		});
	}
}

function sort_tickets(sort_by){
	if(jQuery("#"+sort_by+" span").attr('class')=='dashicons' || jQuery("#"+sort_by+" span").attr('class')=='dashicons dashicons-arrow-up-alt2')
	{
		jQuery("#"+sort_by+" span").addClass('dashicons-arrow-down-alt2');
		jQuery("#"+sort_by+" span").removeClass('dashicons-arrow-up-alt2');
		getTickets(sort_by,'down');
	}
	else
	{
		jQuery("#"+sort_by+" span").addClass('dashicons-arrow-up-alt2');
		jQuery("#"+sort_by+" span").removeClass('dashicons-arrow-down-alt2');
		getTickets(sort_by,'up');
	}
}

function getRaisedByTicketUser(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'getTicketRaisedByUser',
		'ticket_id':ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_assignment').html(response);
		jQuery('#ticketContainer .ticket_assignment').show();
	});
}

function setRaisedByTicketUser(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'setTicketRaisedByUser',
		'ticket_id':ticket_id,
		'user_id': jQuery('#assignTicketRaisedById').val(),
                'reg_user_id':jQuery('#create_ticket_as_user_id').val(),
                'guest_name':jQuery('#wpsp_guest_user_name').val(),
                'guest_email':jQuery('#wpsp_guest_user_email').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
	    if( display_ticket_data.wpsp_redirect_after_ticket_update==1){
	        getTickets('','');
	    }
            else{
                openTicket(ticket_id);
            }
        });
}

function wpsp_closeTicketStatus(ticket_id,status){   
    if(confirm(display_ticket_data.sure_to_close_status)){
        jQuery('#ticketContainer .wpspActionDashboardBody,#ticketActionDashboard,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
        jQuery('#ticketContainer .wait').show();
        var data = {
            'action': 'closeTicketStatus',
            'ticket_id':ticket_id,
            'status':status     
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            getTickets('','');      
        });
    }
}

function cat_wise_custom_field(){
    var ids=jQuery("#create_ticket_category").val();
    var data = {
        'action': 'get_cat_custom_field',
        'cat_id':ids
    };
    if(ids==''){
        jQuery('.wpsp_conditional_fields').hide();
    }else{
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            var data = JSON.parse(response);
            var field_ids=data[0];
            var other_field_ids=data[1];
            jQuery('.wpsp_conditional_fields').hide();
            for(var i=0;i<field_ids.length;i++){
                jQuery('#wpsp_custom_'+field_ids[i]).show();
                if(jQuery('#cust'+field_ids[i]).length){
                    jQuery('#cust'+field_ids[i]).val("");
                }
            }
            for(var i=0;i<other_field_ids.length;i++){
                switch(other_field_ids[i][1]){
                    case '1':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                    case '2':   jQuery('#cust'+other_field_ids[i][0]+" option:eq(1)").prop('selected', true);
                                break;
                    case '3':   jQuery('#wpsp_custom_'+other_field_ids[i][0]).find(":checkbox:eq(0)").attr("checked", "checked");
                                break;
                    case '4':   jQuery('#wpsp_custom_'+other_field_ids[i][0]).find(":radio:eq(0)").attr("checked", "checked");
                                break;        
                    case '5':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                    case '6':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                }
            }
        });
    }

}
function wpsp_change_user(){
    var user_type=jQuery('#assignTicketRaisedById').val();
        
 	if(user_type==1)
 	{
 		jQuery('#user_type_user_front').show();
 		jQuery('#user_type_guest_front').hide();
 		jQuery('#wpsp_guest_user_name').removeClass('wpsp_required');
 		jQuery('#wpsp_guest_user_email').removeClass('wpsp_required');
 	}
 	else if(user_type==0)
 	{       
                 jQuery('#user_type_user_front').hide();
 		jQuery('#user_type_guest_front').show();
 		jQuery('#wpsp_guest_user_name').addClass('wpsp_required');
 		jQuery('#wpsp_guest_user_email').addClass('wpsp_required');
 	}
         
}
function wpsp_close_popup(ticket_id){
    openTicket(ticket_id);;
}
function getEditCustomField(ticket_id){
    wpsp_show_front_popup();
    var data = {
        'action': 'getEditCustomField',
        'ticket_id':ticket_id
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_front_popup_body').html(response);
        jQuery('#wpsp_front_popup_blank,#wpsp_front_popup_loading_img').hide();
        jQuery('#wpsp_front_popup_body').show();
    });
}
function setEditCustomField(e,obj,ticket_id){
        wpsp_show_front_popup();
        var dataform=new FormData( obj );
       
        var flag=true;
        
        if(jQuery('#subject').val().trim()==''){
               flag=false;
               jQuery('#wpsp_front_popup_blank,#wpsp_front_popup_loading_img').hide();
               jQuery('#wpsp_front_popup_body').show();
               alert(display_ticket_data.insert_all_required);
        }else{
            flag=true;
            dataform.append("subject", jQuery('#subject').val().trim());
        }
        
        exatraCustomFieldValidations();
        
        if(flag){
           jQuery.ajax( {
            url: display_ticket_data.wpsp_ajax_url,
            type: 'POST',
            data:  dataform,
            processData: false,
            contentType: false
	    }) 
            .done(function( msg ) {
                if( display_ticket_data.wpsp_redirect_after_ticket_update==0){
                    wpsp_close_front_popup();
                    openTicket(ticket_id);
                }
                else{
                    wpsp_close_front_popup();
                    getTickets('','');
                }
            });
        }
        
        e.preventDefault();   
}

function wpspUploadAttachment(files,page) {
    var current_filesize=files[0].size/1000000;
    if(current_filesize>display_ticket_data.wpspAttachMaxFileSize){
        alert(display_ticket_data.wpspAttachFileSizeExeeded);
        jQuery('#wpsp_frm_attachment_input_'+page).val('');
        return;
    }
    //hide attachment input and grab share loack
    jQuery('#wpsp_frm_attachment_input_'+page).hide();
    wpsp_attachment_share_lock=true;
    
    //prepend attachment code
    wpsp_attachment_counter++;
    var copyAttachmentTemplate=jQuery('#wpsp_frm_attachment_copy_'+page).html();
    copyAttachmentTemplate='<div id="wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+'" class="wpsp_frm_attachment">'+copyAttachmentTemplate+'</div>';
    jQuery('#wpsp_frm_attachment_list_'+page).prepend(copyAttachmentTemplate);
    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_name').text(files[0].name);
    
    var data = new FormData();
    jQuery.each(files, function(key, value){
        data.append(key, value);
    });
    data.append('action', 'wpsp_upload_attachment');
    
    jQuery.ajax({
        type: 'post',
        url: display_ticket_data.wpsp_ajax_url,
        data: data,
        xhr: function(){
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                    var percentComplete = Math.floor((evt.loaded / evt.total) * 100);
                    var percentRemain = 100 - percentComplete;
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_percentage').text('['+percentComplete+'%]');
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background': '-webkit-linear-gradient(left, '+display_ticket_data.wpspAttachment_bc+' '+percentComplete+'%, '+display_ticket_data.wpspAttachment_pc+' '+percentRemain+'%)'
                    });
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background': '-moz-linear-gradient(left, '+display_ticket_data.wpspAttachment_bc+' '+percentComplete+'%,'+display_ticket_data.wpspAttachment_pc+' '+percentRemain+'%)'
                    });
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background': '-o-linear-gradient(left, '+display_ticket_data.wpspAttachment_bc+' '+percentComplete+'%, '+display_ticket_data.wpspAttachment_pc+' '+percentRemain+'%)'
                    });
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background': '-ms-linear-gradient(left, '+display_ticket_data.wpspAttachment_bc+' '+percentComplete+'%, '+display_ticket_data.wpspAttachment_pc+' '+percentRemain+'%)'
                    });
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background': 'linear-gradient(left, '+display_ticket_data.wpspAttachment_bc+' '+percentComplete+'%, '+display_ticket_data.wpspAttachment_pc+' '+percentRemain+'%)'
                    });
                } else {
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                        'background-color': display_ticket_data.wpspAttachment_bc
                    });
                    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_percentage').text('['+display_ticket_data.label_uploading+']');
                }
            }, false);
            return xhr;
        },
        processData: false,
        contentType: false,
        success: function(response) {
            //alert(response);
            jQuery('#wpsp_frm_attachment_input_'+page).val('');
            jQuery('#wpsp_frm_attachment_input_'+page).show();
            //leave share loack
            wpsp_attachment_share_lock=false;
            //process response
            var res=jQuery.parseJSON( response.trim() );
            if(res.isError==1){
                jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).css({
                    'background': display_ticket_data.wpspAttachment_pc
                });
                jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_percentage').text('['+res.errorMessege+']');
            } else {
                jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_percentage').text('['+res.errorMessege+']');
                jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter+' .wpsp_frm_attachment_remove').html('[ <span class="wpsp_attachment_ui_remove" onclick="removeAttachment(\''+page+'\','+res.attachment_id+','+wpsp_attachment_counter+')">'+display_ticket_data.wpspRemoveAttachment+'</span> ]');
                jQuery('#wpsp_frm_attachment_ids_container_'+page).append('<input id="wpsp_attach_value_'+page+'_'+res.attachment_id+'" type="hidden" name="attachment_ids[]" value="'+res.attachment_id+'">');
            }
        }
    });
}

function removeAttachment(page,attachment_id,wpsp_attachment_counter){
    jQuery('#wpsp_attach_value_'+page+'_'+attachment_id).remove();
    jQuery('#wpsp_frm_attachment_'+page+'_'+wpsp_attachment_counter).remove();
}

function deleteThread(thread_id,ticket_id){
    if(confirm(display_ticket_data.sure_to_delete_thread+"\n("+display_ticket_data.can_not_undone+")"))
    {
        jQuery('#ticketContainer .ticket_indivisual').hide();
        jQuery('#ticketContainer .wait').show();
        var data = {
                'action': 'wpsp_deleteThread',
                'thread_id':thread_id,

        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#ticketContainer .ticket_indivisual').show();
            jQuery('#ticketContainer .wait').hide();
            openTicket(ticket_id);
        });
    }
}
