<?php 
global $wpdb;
$wpsp_et_change_ticket_status=get_option( 'wpsp_et_change_ticket_status' );

$staff_to_notify=array(
		'customer'=>$_POST['etEnableCustomer'],
		'administrator'=>$_POST['etEnableStaffAdmin'],
		'supervisor'=>$_POST['etEnableStaffSupervisor'],
		'assigned_agent'=>$_POST['etEnableStaffAssignedAgent'],
		'all_agents'=>$_POST['etEnableStaffAllAgent']
);
$wpsp_et_change_ticket_status['notify_to']=$staff_to_notify;
$wpsp_et_change_ticket_status['mail_subject']=$_POST['wpsp_et_change_ticket_status_suject'];
$wpsp_et_change_ticket_status['mail_body']=$_POST['wpsp_et_change_ticket_status_body'];

update_option('wpsp_et_change_ticket_status',$wpsp_et_change_ticket_status);
?>