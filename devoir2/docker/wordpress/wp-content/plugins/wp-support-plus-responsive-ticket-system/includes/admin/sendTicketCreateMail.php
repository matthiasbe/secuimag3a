<?php 
$generalSettings=get_option( 'wpsp_general_settings' );
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );

$wpsp_open_ticket_page_url=get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
$wpsp_open_ticket_page_url.='?ticket_id='.$this->Encrypt($ticket_id);
$wpsp_open_ticket_page_url='<a href="'.$wpsp_open_ticket_page_url.'">'.$wpsp_open_ticket_page_url.'</a>';
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
$ticket = $wpdb->get_row( $sql );

$etCustomField=array();
foreach ($customFields as $field){
	if(isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id]))
	{
		$_POST['cust'.$field->id]=implode(",",$_POST['cust'.$field->id]);
	}
	$etCustomField['cust'.$field->id]=(isset($_POST['cust'.$field->id]))?$_POST['cust'.$field->id]:'';
	if ($field->field_type=='5'){
		$etCustomField['cust'.$field->id]=nl2br($etCustomField['cust'.$field->id]);
	}
}

$et_success_subject=stripslashes($wpsp_et_create_new_ticket['success_subject']);
$et_success_body=stripslashes($wpsp_et_create_new_ticket['success_body']);
$et_success_staff_subject=stripslashes($wpsp_et_create_new_ticket['staff_subject']);
$et_staff_body=stripslashes($wpsp_et_create_new_ticket['staff_body']);

$user_name='';
$user_email='';
$signature='';
if($_POST['user_id']){
	$user=get_userdata($_POST['user_id']);
	$user_name=$user->display_name;
	$user_email=$user->user_email;
	
	$userSignature = $wpdb->get_row( "select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=".$_POST['user_id'] );
	if($wpdb->num_rows){
		$signature='<br>---<br>'.stripcslashes(htmlspecialchars_decode($userSignature->signature,ENT_QUOTES));
	}
}
else {
	$user_name=$_POST['guest_name'];
	$user_email=$_POST['guest_email'];
}
$sql="select *FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
$tickets = $wpdb->get_row( $sql );

$agent_created_name='';
if($tickets->agent_created){
    $agent_created=get_userdata($tickets->agent_created);
    $agent_created_name=$agent_created->display_name;
}
$etCategoryName=$wpdb->get_var( "SELECT name FROM {$wpdb->prefix}wpsp_catagories where id=".$cat_id );
$etCategoryName=__($etCategoryName,'wp-support-plus-responsive-ticket-system');
$priority=__($priority,'wp-support-plus-responsive-ticket-system');
$description=stripcslashes(htmlspecialchars_decode($description,ENT_QUOTES));
foreach ($wpsp_et_create_new_ticket['templates'] as $et_key=>$et_val){
	switch ($et_key){
		case 'customer_name': 
			$et_success_subject = str_replace('{customer_name}', $user_name, $et_success_subject);
			$et_success_body = str_replace('{customer_name}', $user_name, $et_success_body);
			$et_success_staff_subject = str_replace('{customer_name}', $user_name, $et_success_staff_subject);
			$et_staff_body = str_replace('{customer_name}', $user_name, $et_staff_body);
			break;
		case 'customer_email': 
			$et_success_subject = str_replace('{customer_email}', $user_email, $et_success_subject);
			$et_success_body = str_replace('{customer_email}', $user_email, $et_success_body);
			$et_success_staff_subject = str_replace('{customer_email}', $user_email, $et_success_staff_subject);
			$et_staff_body = str_replace('{customer_email}', $user_email, $et_staff_body);
			break;
		case 'ticket_id': 
			$et_success_subject = str_replace('{ticket_id}', $ticket_id, $et_success_subject);
			$et_success_body = str_replace('{ticket_id}', $ticket_id, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_id}', $ticket_id, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_id}', $ticket_id, $et_staff_body);
			break;
		case 'ticket_subject': 
			$et_success_subject = str_replace('{ticket_subject}', $wpsp_subject, $et_success_subject);
			$et_success_body = str_replace('{ticket_subject}', $wpsp_subject, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_subject}', $wpsp_subject, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_subject}', $wpsp_subject, $et_staff_body);
			break;
		case 'ticket_description': 
			$et_success_subject = str_replace('{ticket_description}', $description, $et_success_subject);
			$et_success_body = str_replace('{ticket_description}', $description, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_description}', $description, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_description}', $description, $et_staff_body);
			break;
		case 'ticket_category': 
			$et_success_subject = str_replace('{ticket_category}', $etCategoryName, $et_success_subject);
			$et_success_body = str_replace('{ticket_category}', $etCategoryName, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_category}', $etCategoryName, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_category}', $etCategoryName, $et_staff_body);
			break;
		case 'ticket_priotity': 
			$et_success_subject = str_replace('{ticket_priotity}', $priority, $et_success_subject);
			$et_success_body = str_replace('{ticket_priotity}', $priority, $et_success_body);
			$et_success_staff_subject = str_replace('{ticket_priotity}', $priority, $et_success_staff_subject);
			$et_staff_body = str_replace('{ticket_priotity}', $priority, $et_staff_body);
			break;
                case 'ticket_url': 
			$et_success_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_success_body);
			$et_staff_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_staff_body);
			break;
                case 'time_created':
                        $et_success_subject = str_replace('{time_created}', $tickets->create_time, $et_success_subject);
 			$et_success_body = str_replace('{time_created}', $tickets->create_time, $et_success_body);
 			$et_success_staff_subject = str_replace('{time_created}', $tickets->create_time, $et_success_staff_subject);
 			$et_staff_body = str_replace('{time_created}', $tickets->create_time, $et_staff_body);
			break;
                case 'agent_created':
                        $et_success_subject = str_replace('{agent_created}', $agent_created_name, $et_success_subject);
 			$et_success_body = str_replace('{agent_created}', $agent_created_name, $et_success_body);
 			$et_success_staff_subject = str_replace('{agent_created}', $agent_created_name, $et_success_staff_subject);
 			$et_staff_body = str_replace('{agent_created}', $agent_created_name, $et_staff_body);
			break;
		default:
			break;
	}
}
foreach ($etCustomField as $etFieldKey=>$etFieldVal){
        $etFieldVal=apply_filters('wpsp_email_template_key_value',$etFieldVal,$etFieldKey);
        
	$et_success_subject = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_subject);
	$et_success_body = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_body);
	$et_success_staff_subject = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_staff_subject);
	$et_staff_body = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_staff_body);
}

$headers = array("Content-Type: text/html;charset=utf-8");
$headers[] = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
if ( isset( $emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '' ) {
	$headers[] = 'Reply-To: ' .  $emailSettings['default_reply_to'];
}
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));


$ignore_emails=explode("\n",$emailSettings['ignore_emails']);

/*
 * send customer success email START
 */
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 20 - suppress owner notification for tickets created by agent
 */
/*new code start*/

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

if (!isset($_POST['agent_silent_create']) && !(array_search($user_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $user_email)) {
    if ($wpsp_et_create_new_ticket['enable_success']) {
        $subject_user = '[' . __($advancedSettings['ticket_label_alice'][1], 'wp-support-plus-responsive-ticket-system') . ' ' . $advancedSettings['wpsp_ticket_id_prefix'] . $ticket_id . '] ' . $et_success_subject;
        $body_user = '';
        if ($emailSettings['enable_email_pipe'] && $advancedSettings['reply_above'] == 1) {
            $body_user.='----------reply above----------';
            $body_user.='<br><br>';
        }
        $body_user.=$et_success_body;
        wp_mail($user_email, $subject_user, $body_user, $headers);
    }
}
/*new code end*/



/*
 * send customer success email END
 * send staff email START
 */
$to=array();



if($wpsp_et_create_new_ticket['staff_to_notify']['assigned_agent'] && $default_assignee_id != '0'){
	$assigned_users=explode(',', $default_assignee_id);
	foreach ($assigned_users as $user){
		$userdata=get_userdata($user);
		if($user_email!=$userdata->user_email){
			$to[] = $userdata->user_email;
		}
	}
}

$administrator_emails=explode("\n",$emailSettings['administrator_emails']);
if($wpsp_et_create_new_ticket['staff_to_notify']['administrator']){
	if($administrator_emails && !$to){
		$to=$administrator_emails;
	}
	else if($administrator_emails){
		foreach ($administrator_emails as $admin_email){
			if($user_email != $admin_email && !(array_search($admin_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $admin_email)){
				$headers[] = " Bcc:" . $admin_email;
			}
		}
	}
}

$roleManage=get_option( 'wpsp_role_management' );

if($wpsp_et_create_new_ticket['staff_to_notify']['supervisor']){
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
			if($user_email!=$supervisor_email && !(array_search($supervisor_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $supervisor_email)){
				$headers[] = " Bcc:" . $supervisor_email;
			}
		}
	}
}

if($wpsp_et_create_new_ticket['staff_to_notify']['all_agents']){
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
			if($user_email!=$agents_email && !(array_search($agents_email, $piping_emails)>-1) && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $agents_email)){
				$headers[] = " Bcc:" . $agents_email;
			}
		}
	}
}

foreach ($to as $key=>$val){
    if(array_search($val, $piping_emails)>-1 && apply_filters('wpsp_check_email_in_ignore_list', true, $ignore_emails, $val)){
        unset($to[$key]);
    }
}

$to_new=array();
foreach ($to as $to_email){
    if($to_email != $user_email){
        $to_new[]=$to_email;
    }
}

$subject='['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive-ticket-system').' '.$advancedSettings['wpsp_ticket_id_prefix'].$ticket_id.'] '.$et_success_staff_subject;
$body='';
if ($emailSettings['enable_email_pipe']){
	$body.='----------reply above----------';
	$body.='<br><br>';
}
$body.=$et_staff_body.$signature;

//error_log(PHP_EOL.'To emails in create ticket:'.PHP_EOL.implode(PHP_EOL, $to_new));
//error_log(PHP_EOL.'Headers in create ticket:'.PHP_EOL.implode(PHP_EOL, $headers));

if($to_new){
	wp_mail($to_new,$subject,$body,$headers,$emailAttachments);
	add_filter('wp_mail_content_type',create_function('', 'return "text/plain"; '));
}


?>
