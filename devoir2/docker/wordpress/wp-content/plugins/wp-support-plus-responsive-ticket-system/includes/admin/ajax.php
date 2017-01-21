<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class SupportPlusAjax {
	function createNewTicket(){
		//catch JS injection
		if(stristr($_POST['create_ticket_body'],"<script>")){
			die(__("Javascript Injection Not Allowed!",'wp-support-plus-responsive-ticket-system'));
		}
		
		//check recaptcha
		$generalSettings=get_option('wpsp_general_settings');
                $advancedSettings=get_option('wpsp_advanced_settings' );
		if($_POST['type']=='guest'&& !isset($_POST['backend']) && !isset($_POST['pipe']) && $generalSettings['google_nocaptcha_key'] && $generalSettings['google_nocaptcha_secret']){
			include( WCE_PLUGIN_DIR.'asset/lib/google_noCaptcha/checkCaptcha.php' );
		}
		
		global $wpdb;
		
		//CODE FOR ATTACHMENT START
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 11 - add support to save attachments and images from emails
		 */
		if( isset($_POST['pipe']) && $_POST['pipe'] == 1 ) {
			$attachment_ids = $_POST['attachment_ids'];
			if(!$attachment_ids) $attachment_ids=array();
                        $emailAttachments=array();
			foreach( $attachment_ids as $attachment_id ) {
				$attachments = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'wpsp_attachments WHERE id=' . $attachment_id );
				foreach ( $attachments as $attachment ) {
					$emailAttachments[] = $attachment->filepath;
				}
			}
			$attachment_ids = implode( ',', $attachment_ids );
		} else {
			$attachments=array();
                        if(isset($_POST['attachment_ids'])){
                            $attachments=$_POST['attachment_ids'];
                        }
			$attachment_ids=array();
			$emailAttachments=array();
			foreach ($attachments as $attachment_id){
                            $attachment_path=$wpdb->get_var("select filepath from ".$wpdb->prefix."wpsp_attachments where id=".$attachment_id);
                            $wpdb->update($wpdb->prefix.'wpsp_attachments',array('active'=>1),array('id'=>$attachment_id));
                            $emailAttachments[]=$attachment_path;
			}
			$attachment_ids=implode(',', $attachments);
		}
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		 */
		//CODE FOR ATTACHMENT END
		$default_assignee_id='0';
		
		if(isset($_POST['create_ticket_category'])){
			$default_assignees=$wpdb->get_var( "SELECT default_assignee FROM {$wpdb->prefix}wpsp_catagories WHERE id='".$_POST['create_ticket_category']."'" );
			if($default_assignees!='0'){
				$default_assignee_id=$default_assignees;
			}
		}
		
		if(isset($_POST['create_ticket_type']) && ($_POST['create_ticket_type']=="on" || $_POST['create_ticket_type']==1))
		{
			$ticket_type=1;
		}
		else
		{
			$ticket_type=0;
		}
		$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
                $wpsp_subject=(isset($_POST['create_ticket_subject']))?$_POST['create_ticket_subject']:$advancedSettingsFieldOrder['wpsp_default_value_of_subject'];
                $cat_id=(isset($_POST['create_ticket_category']))?$_POST['create_ticket_category']:1;
		$priority=(isset($_POST['create_ticket_priority']))?$_POST['create_ticket_priority']:'normal';

		$status_priority = get_option( 'wpsp_default_status_priority_names' );
		$generalSettings=get_option( 'wpsp_general_settings' );
		
		$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$generalSettings['default_new_ticket_status']." ";
		$status_data=$wpdb->get_results($sql);
		foreach($status_data as $status)
		{
			$status_name = $status->name;
		}
		if(!isset($status_name))
		{
			$status_name = $status_priority['status_names']['open'];
		}
		
		//create ticket
                if(!(isset($_POST['pipe'])) && is_user_logged_in() && get_current_user_id()!=$_POST['user_id']){
                    $current_user_id=get_current_user_id();
                }
                else{
                    $current_user_id=0;
                }
                if($advancedSettings['ticketId']==1){
                    $values=array(
                                    'subject'=>htmlspecialchars($wpsp_subject,ENT_QUOTES),
                                    'created_by'=>$_POST['user_id'],
                                    'assigned_to'=>$default_assignee_id,
                                    'guest_name'=>$_POST['guest_name'],
                                    'guest_email'=>$_POST['guest_email'],
                                    'type'=>$_POST['type'],
                                    'status'=>$status_name,
                                    'cat_id'=>$cat_id,
                                    'create_time'=>current_time('mysql', 1),
                                    'update_time'=>current_time('mysql', 1),
                                    'priority'=>$priority,
                                    'ticket_type'=>$ticket_type,
                                    'agent_created'=>$current_user_id
                    );
                } 
                else {
                    
                    $id=0;
                    do{
                        $id=rand(111111, 999999);
                        $sql="select id from {$wpdb->prefix}wpsp_ticket where id=".$id;
                        $result=$wpdb->get_var($sql);
                    }while ($result);
                    
                    $values=array(   
                                'id'=>$id,
				'subject'=>htmlspecialchars($wpsp_subject,ENT_QUOTES),
				'created_by'=>$_POST['user_id'],
				'assigned_to'=>$default_assignee_id,
				'guest_name'=>$_POST['guest_name'],
				'guest_email'=>$_POST['guest_email'],
				'type'=>$_POST['type'],
				'status'=>$status_name,
				'cat_id'=>$cat_id,
				'create_time'=>current_time('mysql', 1),
				'update_time'=>current_time('mysql', 1),
				'priority'=>$priority,
				'ticket_type'=>$ticket_type,
                                'agent_created'=>$current_user_id
                            );
                }
                
		//custom fields values
		$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
		foreach ($customFields as $field){
			if(!apply_filters('wpsp_extra_custom_fields_db_insert',false,$field) && isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id]))
			{
				$_POST['cust'.$field->id]=implode(",",$_POST['cust'.$field->id]);
			}
                        $catAssignFlag=TRUE;
                        $assignCategories=array();
                        if($field->field_categories){
                            $assignCategories=explode(',', $field->field_categories);
                        }
                        if($field->field_categories == 0){
                            $catAssignFlag=TRUE;
                        } else if(array_search($cat_id, $assignCategories)>-1){
                            $catAssignFlag=TRUE;
                        } else {
                            $catAssignFlag=FALSE;
                        }
			$values['cust'.$field->id]=(isset($_POST['cust'.$field->id]) && $catAssignFlag)?htmlspecialchars($_POST['cust'.$field->id],ENT_QUOTES):'';
		}
                
                if(isset($_POST['extension_meta'])){
                    $values['extension_meta']=$_POST['extension_meta'];
                }
		
                $values=apply_filters('wpsp_create_new_ticket_values',$values);
		$wpdb->insert($wpdb->prefix.'wpsp_ticket',$values);
		$ticket_id=$wpdb->insert_id;
                
                do_action('wpsp_after_ticket_create',$ticket_id);
                
                if(!(isset($_POST['pipe'])) && $_POST['type']=='guest'&& $generalSettings['enable_register_guest_user']==1){
                    $wpsp_user = get_user_by( 'email', $_POST['guest_email'] );
                    if(!$wpsp_user){
                        $user_login = $_POST['guest_email'];
                        $user_email = $_POST['guest_email'];
                        $errors = register_new_user($user_login, $user_email);
                        $wpsp_user = get_user_by( 'email', $user_email );
                        $wpsp_user->set_role( $generalSettings['guest_user_role'] );
                    }
                }
		
                if( (isset($_POST['create_ticket_body'])) && ( ((isset($_POST['ckeditor_enabled'])) && $_POST['ckeditor_enabled']=='0') || isset($_POST['extension_meta']) ) ){
                    $_POST['create_ticket_body']= $this->nl2br_save_html($_POST['create_ticket_body']);
                }
                $description=(isset($_POST['create_ticket_body']))?htmlspecialchars($_POST['create_ticket_body'],ENT_QUOTES):'';
				
		//create thread
		$values=array(
				'ticket_id'=>$ticket_id,
				'body'=>$description,
				'attachment_ids'=>$attachment_ids,
				'create_time'=>current_time('mysql', 1),
				'created_by'=>$_POST['user_id'],
				'guest_name'=>$_POST['guest_name'],
				'guest_email'=>$_POST['guest_email']
		);
                $values=apply_filters('wpsp_reply_field_ticket_thread_values',$values);                
		$wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$values);
                 /*
                 * create new ticket thread
                 */
                 if($default_assignee_id!='0'){
                     $threadvalues=array(
                                     'ticket_id'=>$ticket_id,
                                     'body'=>$default_assignee_id,
                                     'attachment_ids'=>'',
                                     'create_time'=>current_time('mysql', 1),
                                     'created_by'=>$_POST['user_id'],
                                     'guest_name'=>'',
                                     'guest_email'=>'',
                                     'is_note'=>2
                     );
                     $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threadvalues);
                }
		//check mail settings
		include( WCE_PLUGIN_DIR.'includes/admin/sendTicketCreateMail.php' );
		//end
		if(!(isset($_POST['pipe'])||isset($_POST['extension_meta']))){
			echo "1";die();
		}
	}
	
	function replyTicket(){
	
		//catch JS injection
		if(stristr($_POST['replyBody'],"<script>")){
			die(__("Javascript Injection Not Allowed!",'wp-support-plus-responsive-ticket-system'));
		}
		
		global $wpdb;
	
		//CODE FOR ATTACHMENT START
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
         * Update 11 - add support to save attachments and images from emails
         */
        if( isset($_POST['pipe']) && $_POST['pipe'] == 1 ) {
            $attachment_ids = $_POST['attachment_ids'];
            if(!$attachment_ids) $attachment_ids=array();
            $emailAttachments=array();
            foreach( $attachment_ids as $attachment_id ) {
                $attachments = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'wpsp_attachments WHERE id=' . $attachment_id );
                foreach ( $attachments as $attachment ) {
                    $emailAttachments[] = $attachment->filepath;
                }
            }
            $attachment_ids = implode( ',', $attachment_ids );
        } else {
            $attachments=array();
            if(isset($_POST['attachment_ids'])){
                $attachments=$_POST['attachment_ids'];
            }
            $attachment_ids=array();
            $emailAttachments=array();
            foreach ($attachments as $attachment_id){
                $attachment_path=$wpdb->get_var("select filepath from ".$wpdb->prefix."wpsp_attachments where id=".$attachment_id);
                $wpdb->update($wpdb->prefix.'wpsp_attachments',array('active'=>1),array('id'=>$attachment_id));
                $emailAttachments[]=$attachment_path;
            }
            $attachment_ids=implode(',', $attachments);
        }
        /* END CLOUGH I.T. SOLUTIONS MODIFICATION
         */
		//CODE FOR ATTACHMENT END
	
		//create ticket
		$generalSettings=get_option( 'wpsp_general_settings' );
		$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
		$ticket = $wpdb->get_row( $sql );
		
		if(!isset($_POST['pipe'])){
			$replyStatus=$_POST['reply_ticket_status'];
			
			if($generalSettings['ticket_status_after_cust_reply']!='default' && $ticket->created_by==$_POST['user_id']){
				$replyStatus=$generalSettings['ticket_status_after_cust_reply'];
			}
			$values=array(
					'status'=>$replyStatus,
					'cat_id'=>$_POST['reply_ticket_category'],
					'update_time'=>current_time('mysql', 1),
					'priority'=>$_POST['reply_ticket_priority']
			);
		}
		else {
			$replyStatus='';
			if($generalSettings['ticket_status_after_cust_reply']!='default' && $ticket->created_by==$_POST['user_id']){
                            $replyStatus=$generalSettings['ticket_status_after_cust_reply'];
			} else {
                            $status_priority = get_option( 'wpsp_default_status_priority_names' );
                            $sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$generalSettings['default_new_ticket_status']." ";
                            $status_data=$wpdb->get_results($sql);
                            foreach($status_data as $status){
                                $replyStatus = $status->name;
                            }
                            if(!$replyStatus){
                                $status_name = $status_priority['status_names']['open'];
                            }
                        }
			$values=array(
                            'status'=>$replyStatus,
                            'update_time'=>current_time('mysql', 1)
			);
		}
		$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id' => $_POST['ticket_id']));
                
                if( (isset($_POST['replyBody'])) && (isset($_POST['ckeditor_enabled'])) && $_POST['ckeditor_enabled']=='0' ){
                    $_POST['replyBody']= $this->nl2br_save_html($_POST['replyBody']);
                }
		//create thread
		$values=array(
				'ticket_id'=>$_POST['ticket_id'],
				'body'=>htmlspecialchars($_POST['replyBody'],ENT_QUOTES),
				'attachment_ids'=>$attachment_ids,
				'create_time'=>current_time('mysql', 1),
				'created_by'=>$_POST['user_id']
		);
		if(isset($_POST['pipe'])){
			$values['guest_name']=$_POST['guest_name'];
			$values['guest_email']=$_POST['guest_email'];
		}
		if (!( !isset($_POST['notify']) || ( isset( $_POST['notify'] ) && $_POST['notify'] == 'true' ) )) {
			$values['is_note']=1;
		}
		$values=apply_filters('wpsp_reply_field_ticket_thread_values',$values);                
		$wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$values);  
                
		if($_POST['reply_ticket_status']!=$ticket->status){
                    $threadstatus=array(
                                    'ticket_id'=>$_POST['ticket_id'],
                                    'body'=>$_POST['reply_ticket_status'],
                                    'attachment_ids'=>'',
                                    'create_time'=>current_time('mysql', 1),
                                    'created_by'=>$_POST['user_id'],
                                    'guest_name'=>'',
                                    'guest_email'=>'',
                                    'is_note'=>3
                    );
                    $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threadstatus);
                }
                if($_POST['reply_ticket_category']!=$ticket->cat_id){
                    $threadstatus=array(
                                    'ticket_id'=>$_POST['ticket_id'],
                                    'body'=>$_POST['reply_ticket_category'],
                                    'attachment_ids'=>'',
                                    'create_time'=>current_time('mysql', 1),
                                    'created_by'=>$_POST['user_id'],
                                    'guest_name'=>'',
                                    'guest_email'=>'',
                                    'is_note'=>4
                    );
                    $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threadstatus);
                }
                if($_POST['reply_ticket_priority']!=$ticket->priority){
                    $threadstatus=array(
                                    'ticket_id'=>$_POST['ticket_id'],
                                    'body'=>$_POST['reply_ticket_priority'],
                                    'attachment_ids'=>'',
                                    'create_time'=>current_time('mysql', 1),
                                    'created_by'=>$_POST['user_id'],
                                    'guest_name'=>'',
                                    'guest_email'=>'',
                                    'is_note'=>5
                    );
                    $wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$threadstatus);
                }
		//check mail settings
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	         * Update 15 - add note (no notifications)
	         */
	        if ( !isset($_POST['notify']) || ( isset( $_POST['notify'] ) && $_POST['notify'] == 'true' ) ) {
			include( WCE_PLUGIN_DIR.'includes/admin/sendTicketReplyMail.php' );
	        }
	        /* END CLOUGH I.T. SOLUTIONS MODIFICATION
	         */
		//end
		if(!isset($_POST['pipe'])){
			echo "1";die();
		}
	}
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
* Update 14 - create new ticket from thread
*/ 
function ticketFromThread() {
	global $wpdb;
	$thread_id = $_POST['thread_id'];
	$now = time();
	$generalSettings = get_option( 'wpsp_general_settings' );
        
	// get ticket id
	$sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
	$result = $wpdb->get_row( $sql );
	$ticket_id = $result->ticket_id;

	// get existing ticket and place into temporary table
	$sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $ticket_id . "'";
	$wpdb->query( $sql );
        
	// set default values
	// id, subject, updated_by, status, cat_id, create_time, update_time, priority, ticket_type
	$sql = "UPDATE 
			{$wpdb->prefix}wpsp_temp_table 
		SET 
			id='0',
			subject='New ticket from Ticket #" . $ticket_id . " (" . $thread_id . ")',
			updated_by='0',
			status='open',
			cat_id='" . $generalSettings['default_ticket_category'] . "',
			create_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			update_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			priority='normal',
			ticket_type='" . $generalSettings['default_ticket_type'] . "'
		WHERE 
			id='" . $ticket_id . "'";
	$wpdb->query( $sql );
        
	// add updated entry into tickets table from temp table
	$sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// get new ticket id
	$new_ticket = $wpdb->insert_id;
        
	// drop temp table
	$sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// get ticket owner information
	$sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $new_ticket . "'";
	$result = $wpdb->get_row( $sql );
	$created_by = $result->created_by;
	$guest_name = $result->guest_name;
	$guest_email = $result->guest_email;
        
	// get existing thread and place into temporary table
	$sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
	$wpdb->query( $sql );
        
	// set default values
	// id, ticket_id, create_time, created_by, guest_name, guest_email
	$sql = "UPDATE 
			{$wpdb->prefix}wpsp_temp_table 
		SET 
			id='0',
			ticket_id='" . $new_ticket . "', 
			create_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			created_by='" . $created_by . "',
			guest_name='" . $guest_name . "',
			guest_email='" . $guest_email . "' 
		WHERE id='" . $thread_id . "'";
	$wpdb->query( $sql );
        
	// add updated entry into thread table from temp table
	$sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket_thread SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// drop temp table
	$sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
	echo $new_ticket;
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/ 
	
	function getTickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketsByFilter.php' );
		die();
	}
	
	function getFrontEndTickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFrontEndTicket.php' );
		die();
	}
	
	function openTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getIndivisualTicket.php' );
		die();
	}
	
	function openTicketFront(){
		include( WCE_PLUGIN_DIR.'includes/admin/getIndivisualTicketFront.php' );
		die();
	}
	
	function getAgentSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getAgentSettings.php' );
		die();
	}
	
	function setAgentSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAgentSettings.php' );
		die();
	}
	
	function getGeneralSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getGeneralSettings.php' );
		die();
	}
	
	function setGeneralSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setGeneralSettings.php' );
		die();
	}
	
	function getCategories(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCategories.php' );
		die();
	}
	
	function createNewCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewCategory.php' );
		die();
	}
	
	function updateCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCategory.php' );
		die();
	}
	
	function deleteCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCategory.php' );
		die();
	}
	
	function getEmailNotificationSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getEmailNotificationSettings.php' );
		die();
	}
	
	function setEmailSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEmailSettings.php' );
		die();
	}
	
	//version 2.0
	function getTicketAssignment(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketAssignment.php' );
		die();
	}
	
	//version 2.0
	function setTicketAssignment(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketAssignment.php' );
		die();
	}
	
	//Version 3.0
	function deleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteTicket.php' );
		die();
	}
	function cloneTicket(){
                 include_once( WCE_PLUGIN_DIR.'includes/admin/cloneTicket.php' );
		die();
        }
	//Version 3.0
	function getChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getChangeTicketStatus.php' );
		die();
	}
	
	//Version 3.0
	function setChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/setChangeTicketStatus.php' );
		die();
	}
	
	//Version 3.1
	function loginGuestFacebook(){
		include( WCE_PLUGIN_DIR.'includes/admin/loginGuestFacebook.php' );
		die();
	}
	
	//Version 3.2
	function getChatOnlineAgents(){
		include( WCE_PLUGIN_DIR.'includes/admin/getChatOnlineAgents.php' );
		die();
	}
	
	//Version 3.2
	function getCallOnlineAgents(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCallOnlineAgents.php' );
		die();
	}
	
	//version 3.9
	function getCreateTicketForm(){
		include( WCE_PLUGIN_DIR.'includes/admin/create_new_ticket.php' );
		die();
	}
	
	//version 3.9
	function getCustomSliderMenus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomSliderMenus.php' );
		die();
	}
	
	//version 3.9
	function addCustomSliderMenu(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomSliderMenu.php' );
		die();
	}
	
	//version 3.9
	function deleteCustomSliderMenu(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomSliderMenu.php' );
		die();
	}
	
	//version 4.0
	function searchRegisteredUsaers(){
		include( WCE_PLUGIN_DIR.'includes/admin/searchRegisteredUsaers.php' );
		die();
	}
	
	//version 4.3
	function getRollManagementSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getRollManagementSettings.php' );
		die();
	}
	
	function setRoleManagement(){
		include( WCE_PLUGIN_DIR.'includes/admin/setRoleManagement.php' );
		die();
	}
	
	//version 4.4
	function getCustomFields(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomFields.php' );
		die();
	}
	
	function createNewCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewCustomField.php' );
		die();
	}
	
	function updateCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomField.php' );
		die();
	}
	
	function deleteCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomField.php' );
		die();
	}

	function getFrontEndFAQ(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFrontEndFAQ.php' );
		die();
	}
	function openFrontEndFAQ(){
		include( WCE_PLUGIN_DIR.'includes/admin/openFrontEndFAQ.php' );
		die();
	}

	function getFaqCategories(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFaqCategories.php' );
		die();
	}
	
	function createNewFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewFaqCategory.php' );
		die();
	}
	
	function updateFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateFaqCategory.php' );
		die();
	}
	
	function deleteFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteFaqCategory.php' );
		die();
	}
	
	function getCustomCSSSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomCSSSettings.php' );
		die();
	}
	
	function setCustomCSSSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomCSSSettings.php' );
		die();
	}

	function getAdvancedSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getAdvancedSettings.php' );
		die();
	}
	
	function setAdvancedSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAdvancedSettings.php' );
		die();
	}

	function getCustomStatusSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomStatusSettings.php' );
		die();
	}

	function deleteCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomStatus.php' );
		die();
	}

	function addCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomStatus.php' );
		die();
	}
	
	function setChangeTicketStatusMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/setChangeTicketStatusMultiple.php' );
		die();
	}
	
	function setAssignAgentMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAssignAgentMultiple.php' );
		die();
	}
	
	function deleteTicketMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteTicketMultiple.php' );
		die();
	}
	
	function wpspCheckLogin(){
		include( WCE_PLUGIN_DIR.'includes/admin/wpspCheckLogin.php' );
		die();
	}
	
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 1 - Change Custom Status Color
	 * Include file required to process database change for existing custom status color change
	 */
	function setCustomStatusColor(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomStatusColor.php' );
		die();
	}
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	*/
	
	function getFieldsReorderSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFieldsReorderSettings.php' );
		die();
	}

	function setFieldsReorderSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setFieldsReorderSettings.php' );
		die();
	}

	function getTicketListFieldSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketListFieldSettings.php' );
		die();
	}

	function setTicketListFieldSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketListFieldSettings.php' );
		die();
	}

	function getCustomFilterFrontEnd(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomFilterFrontEnd.php' );
		die();
	}

	function setCustomFilterFrontEnd(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomFilterFrontEnd.php' );
		die();
	}

	function getCustomPrioritySettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomPrioritySettings.php' );
		die();
	}

	function setCustomPrioritySettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPrioritySettings.php' );
		die();
	}

	function addCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomPriority.php' );
		die();
	}

	function setCustomPriorityColor(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPriorityColor.php' );
		die();
	}

	function deleteCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomPriority.php' );
		die();
	}
	
	function setSubCharLength(){
		include( WCE_PLUGIN_DIR.'includes/admin/setSubCharLength.php' );
		die();
	}
	
	function getETCreateNewTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETCreateNewTicket.php' );
		die();
	}
	
	function setEtCreateNewTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtCreateNewTicket.php' );
		die();
	}
	
	function getETReplayTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETReplayTicket.php' );
		die();
	}
	
	function setEtReplyTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtReplyTicket.php' );
		die();
	}
	
	function getETChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETChangeTicketStatus.php' );
		die();
	}
	
	function setEtChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtChangeTicketStatus.php' );
		die();
	}
	
	function getETAssignAgent(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETAssignAgent.php' );
		die();
	}
	
	function setETAssignAgent(){
		include( WCE_PLUGIN_DIR.'includes/admin/setETAssignAgent.php' );
		die();
	}
	
	function getETDeleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETDeleteTicket.php' );
		die();
	}
	
	function setETDeleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setETDeleteTicket.php' );
		die();
	}

	function setCustomStatusOrder(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomStatusOrder.php' );
		die();
	}
	
	function setCustomPriorityOrder(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPriorityOrder.php' );
		die();
	}
	
	function setDateFormat(){
		include( WCE_PLUGIN_DIR.'includes/admin/setDateFormat.php' );
		die();
	}
	
	function updateCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomStatus.php' );
		die();
	}
	
	function updateCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomPriority.php' );
		die();
	}

	function getTicketRaisedByUser(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketRaisedByUser.php' );
		die();
	}

	function setTicketRaisedByUser(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketRaisedByUser.php' );
		die();
	}
        
        function showcanned(){
		include( WCE_PLUGIN_DIR.'includes/admin/showcanned.php' );
		die();
	}
        
        function shareCanned(){
		include( WCE_PLUGIN_DIR.'includes/admin/shareCanned.php' );
		die();
	}
        
        function getCKEditorSettings(){
                include_once( WCE_PLUGIN_DIR.'includes/admin/getCKEditorSettings.php' );
                die();
        }
        
        function setCKEditorSettings(){
                include_once( WCE_PLUGIN_DIR.'includes/admin/setCKEditorSettings.php' );
                die();
        }
        
        function wpspSubmitLinkForm(){
                include_once( WCE_PLUGIN_DIR.'includes/wpspSubmitLinkForm.php' );
                die();
        }
        function getSupportButton(){
                include( WCE_PLUGIN_DIR.'includes/admin/getSupportButton.php' );
                die();
        }
        function image_upload(){
                include( WCE_PLUGIN_DIR.'includes/admin/imageUpload.php' );
                die();
        }
        function nl2br_save_html( $string ) {
                $string = str_replace( array( "\r\n", "\r", "\n" ), "\n", $string );
                $lines = explode( "\n", $string );
                $output = '';
                foreach( $lines as $line ) {
                    $line .= '<br />';
                    $output .= $line;
                }
                return $output;
        }
        function Encrypt($data){
            return dechex(rand()).'gqlrsdvfjfhds'.decbin($data).'mtdkjsdlsjjhc'.dechex(rand());
        }
        function Decrypt($e){
            $h=substr($e, strpos($e, 'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds'),strpos($e,'mtdkjsdlsjjhc')-(strpos($e,'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds')));
            return bindec($h);
        }
        function closeTicketStatus() {
                include_once( WCE_PLUGIN_DIR.'includes/admin/closeTicketStatus.php' );
                die();
        }
        function wpsp_getCatName(){
            include( WCE_PLUGIN_DIR.'includes/admin/wpsp_getCatName.php' );
            die();
        }
        function get_cat_custom_field(){
            include( WCE_PLUGIN_DIR.'includes/admin/cat_get_custom_field.php' );
            die();
        }
        function getAddOnLicenses(){
            include( WCE_PLUGIN_DIR.'includes/licenses/getAddOnLicenses.php' );
            die();
        }
        function wpsp_act_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_act_license.php' );
            die();
        }
        function wpsp_dact_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_dact_license.php' );
            die();
        }
        function wpsp_check_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_check_license.php' );
            die();
        }
        function getFrontEndDisplay(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/getFrontEndDisplay.php' );
            die();
        }
        
        function setFrontEndDisplay(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/setFrontEndDisplay.php' );
            die();
        }

	function getEditCustomField(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/editTicket.php' );
            die();
             
        }
  
	function setEditCustomField(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/setEditTicket.php' );
             die();  
        }
        
        function check_email_in_ignore_list($flag, $ignore_emails, $user_email){
            $emailSettings=get_option( 'wpsp_email_notification_settings' );
            $emailcount= strlen($emailSettings['ignore_emails']) ? count(explode(',', $emailSettings['ignore_emails'])) : 0;
            if($emailcount){
                if(array_search($user_email, $ignore_emails)>-1){
                    $flag=false;
                } else {
                    foreach ($ignore_emails as $ignore_email){
                        if($ignore_email!='' && $ignore_email[0]=='*'){

                            $checkStr=substr($ignore_email,1);
                            if(strpos($user_email,$checkStr)>-1){
                                $flag=false;
                                break;
                            }

                        } else if($ignore_email!='' && $ignore_email[strlen($ignore_email)-1]=='*'){

                            $checkStr=substr($ignore_email,0,-1);
                            if(strpos($user_email,$checkStr)>-1){
                                $flag=false;
                                break;
                            }

                        }
                    }
                }
            }else{
               $flag=true;
            }
             
            return $flag;
        }
        
        function wpsp_upload_attachment(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/attachment/uploadAttachment.php' );
            die();
        }
        
        function deleteThread(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/deleteThread.php' );
            die();
        }
        
        function wpsp_submit_reply_confirm_box(){
            include_once( WCE_PLUGIN_DIR.'includes/admin/submit_reply_confirm_box.php' );
        }
}
?>
