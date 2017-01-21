<?php
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

$generalSettings=get_option( 'wpsp_general_settings' );
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$default_category=1;
$userByEmail=get_user_by('email', $_POST['wpsp_email']);
$uID=0;
$guestName=$_POST['wpsp_name'];
$guestEmail=$_POST['wpsp_email'];
$userType='guest';
if($userByEmail){
    $uID=$userByEmail->ID;
    $userType='user';
}

$message=  nl2br($_POST['wpsp_desc']);
$ticket_id=$_POST['wpsp_ticket_id'];
if(!is_numeric($ticket_id)){
    die();
}

$sql="select subject,type,status,cat_id,priority,created_by,guest_name
FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$ticket_id;
$ticket = $wpdb->get_row( $sql );

$_POST['pipe']=1;
$_POST['ticket_id']=$ticket_id;
$_POST['replyBody']=$message;
$_POST['user_id']=$uID;
$_POST['guest_name']=$guestName;
$_POST['guest_email']=$guestEmail;
$_POST['reply_ticket_status']=$ticket->status;
$_POST['attachment_ids'] = array();

$this->replyTicket();
?>