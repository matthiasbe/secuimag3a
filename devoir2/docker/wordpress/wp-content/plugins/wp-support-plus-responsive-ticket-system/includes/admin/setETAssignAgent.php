<?php 
global $wpdb;
$wpsp_et_change_ticket_assign_agent=get_option( 'wpsp_et_change_ticket_assign_agent' );

$staff_to_notify=array(
		'customer'=>$_POST['etEnableCustomer'],
		'administrator'=>$_POST['etEnableStaffAdmin'],
		'supervisor'=>$_POST['etEnableStaffSupervisor'],
		'assigned_agent'=>$_POST['etEnableStaffAssignedAgent'],
		'all_agents'=>$_POST['etEnableStaffAllAgent']
);

$wpsp_et_change_ticket_assign_agent['notify_to']=$staff_to_notify;
$wpsp_et_change_ticket_assign_agent['mail_subject']=$_POST['wpsp_et_assign_agent_suject'];
$wpsp_et_change_ticket_assign_agent['mail_body']=$_POST['wpsp_et_assign_agent_body'];

update_option('wpsp_et_change_ticket_assign_agent',$wpsp_et_change_ticket_assign_agent);
?>