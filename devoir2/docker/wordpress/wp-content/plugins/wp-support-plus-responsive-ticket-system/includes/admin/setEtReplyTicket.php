<?php 
global $wpdb;
$wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );

$staff_to_notify=array(
		'customer'=>$_POST['etEnableCustomer'],
		'administrator'=>$_POST['etEnableStaffAdmin'],
		'supervisor'=>$_POST['etEnableStaffSupervisor'],
		'assigned_agent'=>$_POST['etEnableStaffAssignedAgent'],
		'all_agents'=>$_POST['etEnableStaffAllAgent']
);

$wpsp_et_reply_ticket['reply_subject']=$_POST['wpsp_et_staff_email_subject'];
$wpsp_et_reply_ticket['reply_body']=$_POST['wpsp_et_staff_email_body'];
$wpsp_et_reply_ticket['notify_to']=$staff_to_notify;

update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
?>