<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$wpsp_et_delete_ticket=get_option( 'wpsp_et_delete_ticket' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$ignore_emails=explode("\n",$emailSettings['ignore_emails']);
if(($current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==0 && !$current_user->has_cap('manage_support_plus_agent')) || (!$current_user->has_cap('manage_support_plus_ticket') && !$current_user->has_cap('manage_support_plus_agent'))){
	die();
}

/*
 * prepare email templete mail
 */
$et_success_staff_subject='['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripslashes($wpsp_et_delete_ticket['mail_subject']);
$et_staff_body=stripslashes($wpsp_et_delete_ticket['mail_body']);

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket=$wpdb->get_row($sql);

$sql="select name FROM {$wpdb->prefix}wpsp_catagories WHERE id=".$ticket->cat_id;
$category = $wpdb->get_row($sql);

$customerName='';
$customerEmail='';
if($ticket->created_by){
    $user=get_userdata($ticket->created_by);
    $customerName=$user->display_name;
    $customerEmail=$user->user_email;
}
else {
    $customerName=$ticket->guest_name;
    $customerEmail=$ticket->guest_email;
}

$sql="select body FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=".$_POST['ticket_id'].' ORDER BY create_time ASC';
$thread=$wpdb->get_row($sql);

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$etCustomField=array();
foreach ($customFields as $field){
    $cust_alice='cust'.$field->id;
    if ($field->field_type=='5'){
        $etCustomField['cust'.$field->id]=nl2br($ticket->$cust_alice);
    }
    else {
        $etCustomField['cust'.$field->id]=$ticket->$cust_alice;
    }
}

$description=stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES));

foreach ($wpsp_et_delete_ticket['templates'] as $et_key=>$et_val){
    switch ($et_key){
        case 'ticket_status':
            $et_success_staff_subject = str_replace('{ticket_status}', __(ucfirst($ticket->status),'wp-support-plus-responsive-ticket-system'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_status}', __(ucfirst($ticket->status),'wp-support-plus-responsive-ticket-system'), $et_staff_body);
            break;
        case 'customer_name':
            $et_success_staff_subject = str_replace('{customer_name}', $customerName, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_name}', $customerName, $et_staff_body);
            break;
        case 'customer_email':
            $et_success_staff_subject = str_replace('{customer_email}', $customerEmail, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_email}', $customerEmail, $et_staff_body);
            break;
        case 'ticket_id':
            $et_success_staff_subject = str_replace('{ticket_id}', $ticket->id, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_id}', $ticket->id, $et_staff_body);
            break;
        case 'ticket_subject':
            $et_success_staff_subject = str_replace('{ticket_subject}', stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_subject}', stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), $et_staff_body);
            break;
        case 'ticket_description':
            $et_success_staff_subject = str_replace('{ticket_description}', $description, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_description}', $description, $et_staff_body);
            break;
        case 'ticket_category':
            $et_success_staff_subject = str_replace('{ticket_category}', __($category->name,'wp-support-plus-responsive-ticket-system'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_category}', __($category->name,'wp-support-plus-responsive-ticket-system'), $et_staff_body);
            break;
        case 'ticket_priotity':
            $et_success_staff_subject = str_replace('{ticket_priotity}', __($ticket->priority,'wp-support-plus-responsive-ticket-system'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_priotity}', __($ticket->priority,'wp-support-plus-responsive-ticket-system'), $et_staff_body);
            break;
        case 'updated_by':
            $et_success_staff_subject = str_replace('{updated_by}', $current_user->display_name, $et_success_staff_subject);
            $et_staff_body = str_replace('{updated_by}', $current_user->display_name, $et_staff_body);
            break;
        case 'time_created':
            $et_success_staff_subject = str_replace('{time_created}', $ticket->create_time, $et_success_staff_subject);
            $et_staff_body = str_replace('{time_created}', $ticket->create_time, $et_staff_body);
            break;
        default:
            break;
    }
}
foreach ($etCustomField as $etFieldKey=>$etFieldVal){
    $et_success_staff_subject = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_staff_subject);
    $et_staff_body = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_staff_body);
}

/***************************Code for deleting attachment files****************************************************/
$sql="SELECT attachment_ids FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id =".$_POST['ticket_id'];
$thread_attachment_ids= $wpdb->get_results( $sql );
foreach ($thread_attachment_ids as $thread_attachment_id){
    $attachment_ids_temp=array();
    if($thread_attachment_id->attachment_ids){
        $attachment_ids_temp=  explode(',', $thread_attachment_id->attachment_ids);
    }
    $attachment_ids=$attachment_ids_temp;
    foreach ($attachment_ids as $attachment_id){
        $sql="SELECT * FROM {$wpdb->prefix}wpsp_attachments WHERE id =".$attachment_id;
        $result=$wpdb->get_row($sql);
        if(file_exists($result->filepath))
        {
            unlink($result->filepath);
        }
        $wpdb->delete( $wpdb->prefix.'wpsp_attachments', array( 'id' => $attachment_id ) );
    }
}
/**************************End************************************************************************************/

$wpdb->delete( $wpdb->prefix.'wpsp_ticket_thread', array( 'ticket_id' => $_POST['ticket_id'] ) );
$wpdb->delete( $wpdb->prefix.'wpsp_ticket', array( 'id' => $_POST['ticket_id'] ) );

$headers = array("Content-Type: text/html;charset=utf-8"); // clears any existing array data
$headers[] = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
if ( isset( $emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '' ) {
	$headers[] = 'Reply-To: ' .  $emailSettings['default_reply_to'];
}
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));


/*
 * Send Email based on email template settings
 */
$customerName='';
$customerEmail='';
if($ticket->created_by){
	$user=get_userdata($ticket->created_by);
	$customerName=$user->display_name;
	$customerEmail=$user->user_email;
}
else {
	$customerName=$ticket->guest_name;
	$customerEmail=$ticket->guest_email;
}

$to=array();

$piping_emails=array();
if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='cpanel'){
    $piping_mail=$emailSettings['default_reply_to'];
    if(!$emailSettings['default_reply_to']){
        $piping_mail=$emailSettings['default_from_email'];
    }
    $piping_emails[]=$piping_emails;
} else if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='imap'){
    $imap_pipe_list=get_option( 'wpsp_imap_pipe_list' );
    foreach ($imap_pipe_list as $pipe_connection){
        $piping_emails[]=$pipe_connection['pipe_email'];
    }
}

if($wpsp_et_delete_ticket['notify_to']['customer'] && $current_user->user_email!=$customerEmail && !(array_search($customerEmail, $piping_emails)>-1)){
	$to[]=$customerEmail;
}

if($wpsp_et_delete_ticket['notify_to']['assigned_agent'] && $ticket->assigned_to != '0'){
	$assigned_users=explode(',', $ticket->assigned_to);
	if (!$to){
		foreach ($assigned_users as $user){
			$userdata=get_userdata($user);
			if($current_user->user_email!=$userdata->user_email){
				$to[] = $userdata->user_email;
			}
		}
	}
	else {
		foreach ($assigned_users as $user){
			$userdata=get_userdata($user);
			if($current_user->user_email!=$userdata->user_email && !(array_search($userdata->user_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $userdata->user_email)){
				$headers[] = " Bcc:" . $userdata->user_email;
			}
		}
	}
}

$administrator_emails=explode("\n",$emailSettings['administrator_emails']);
if($wpsp_et_delete_ticket['notify_to']['administrator']){
	if($administrator_emails && !$to){
		$to=$administrator_emails;
	}
	else if($administrator_emails){
		foreach ($administrator_emails as $admin_email){
			if($current_user->user_email != $admin_email && !(array_search($admin_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $admin_email)){
				$headers[] = " Bcc:" . $admin_email;
			}
		}
	}
}

$roleManage=get_option( 'wpsp_role_management' );

if($wpsp_et_delete_ticket['notify_to']['supervisor']){
	$supervisors=array();
	$supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
	foreach($roleManage['supervisors'] as $supervisorRole)
	{
		$supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
	}
	$supervisors_emails=array();
	foreach ($supervisors as $supervisor){
	    $supervisors_emails[]=$supervisor->user_email;
	}
        
        $supervisors_emails=apply_filters('wpsp_mail_supervisor',$supervisors_emails,$ticket);

	if($supervisors_emails && !$to){
		$to=$supervisors_emails;
	}
	else if($supervisors_emails){
		foreach ($supervisors_emails as $supervisor_email){
			if($current_user->user_email!=$supervisor_email && !(array_search($supervisor_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $supervisor_email)){
				$headers[] = " Bcc:" . $supervisor_email;
			}
		}
	}
}

if($wpsp_et_delete_ticket['notify_to']['all_agents']){
	$agents=array();
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
	foreach($roleManage['agents'] as $agentRole)
	{
		$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
	}

	$agents_emails=array();
	foreach ($agents as $agent){
		$agents_emails[]=$agent->user_email;
	}

	if($agents_emails && !$to){
		$to=$agents_emails;
	}
	else if($agents_emails){
		foreach ($agents_emails as $agents_email){
			if($current_user->user_email!=$agents_email && !(array_search($agents_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $agents_email)){
				$headers[] = " Bcc:" . $agents_email;
			}
		}
	}
}

foreach ($to as $key=>$val){
    if((array_search($val, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $val)){
        unset($to[$key]);
    }
}

$to_new=array();
foreach ($to as $to_email){
	if($to_email != $current_user->ID){
		$to_new[]=$to_email;
	}
}

//error_log(PHP_EOL.'To emails in delete ticket:'.PHP_EOL.implode(PHP_EOL, $to_new));
//error_log(PHP_EOL.'Headers in delete ticket:'.PHP_EOL.implode(PHP_EOL, $headers));

if($to_new){
	wp_mail($to_new,$et_success_staff_subject,$et_staff_body,$headers);
	add_filter('wp_mail_content_type',create_function('', 'return "text/plain"; '));
}

?>
