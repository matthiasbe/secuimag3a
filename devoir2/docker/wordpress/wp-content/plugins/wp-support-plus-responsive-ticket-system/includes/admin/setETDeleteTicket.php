<?php 
global $wpdb;
$wpsp_et_delete_ticket=get_option( 'wpsp_et_delete_ticket' );

$staff_to_notify=array(
		'customer'=>$_POST['etEnableCustomer'],
		'administrator'=>$_POST['etEnableStaffAdmin'],
		'supervisor'=>$_POST['etEnableStaffSupervisor'],
		'assigned_agent'=>$_POST['etEnableStaffAssignedAgent'],
		'all_agents'=>$_POST['etEnableStaffAllAgent']
);

$wpsp_et_delete_ticket['notify_to']=$staff_to_notify;
$wpsp_et_delete_ticket['notify_to']=$staff_to_notify;
$wpsp_et_delete_ticket['mail_subject']=$_POST['wpsp_et_delete_subject'];
$wpsp_et_delete_ticket['mail_body']=$_POST['wpsp_et_delete_body'];

update_option('wpsp_et_delete_ticket',$wpsp_et_delete_ticket);
?>