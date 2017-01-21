<?php 
global $wpdb;
$ticket_ids=explode(',', $_POST['ticket_ids']);

$mulStatus=$_POST['status'];
$mulCategory=$_POST['category'];
$mulPriority=$_POST['priority'];

foreach ($ticket_ids as $ticket_id){
	$_POST['ticket_id']=$ticket_id;
	$sql="select status,cat_id,priority,ticket_type	FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
	$activeStatus = $wpdb->get_row( $sql );
	
	if($mulStatus=='select') $_POST['status']=$activeStatus->status; else $_POST['status']=$mulStatus;
	if($mulCategory=='select') $_POST['category']=$activeStatus->cat_id; else $_POST['category']=$mulCategory;
	if($mulPriority=='select') $_POST['priority']=$activeStatus->priority; else $_POST['priority']=$mulPriority;
	$_POST['ticket_type']=$activeStatus->ticket_type;
	
	include 'setChangeTicketStatus.php';
}
?>
