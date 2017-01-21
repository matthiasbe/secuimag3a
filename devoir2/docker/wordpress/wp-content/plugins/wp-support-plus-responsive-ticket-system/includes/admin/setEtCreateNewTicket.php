<?php 
global $wpdb;
$wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );

$staff_to_notify=array(
		'administrator'=>$_POST['etEnableStaffAdmin'],
		'supervisor'=>$_POST['etEnableStaffSupervisor'],
		'assigned_agent'=>$_POST['etEnableStaffAssignedAgent'],
		'all_agents'=>$_POST['etEnableStaffAllAgent']
);

$wpsp_et_create_new_ticket['enable_success']=$_POST['etEnableSuccessBtn'];
$wpsp_et_create_new_ticket['success_subject']=$_POST['wpsp_et_success_email_subject'];
$wpsp_et_create_new_ticket['success_body']=$_POST['wpsp_et_success_email_body'];
$wpsp_et_create_new_ticket['staff_subject']=$_POST['wpsp_et_staff_email_subject'];
$wpsp_et_create_new_ticket['staff_body']=$_POST['wpsp_et_staff_email_body'];
$wpsp_et_create_new_ticket['staff_to_notify']=$staff_to_notify;

update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
?>