<?php 
global $wpdb;
$sql="select status,cat_id,priority,ticket_type FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$activeStatus = $wpdb->get_row( $sql );
$_POST['category']=$activeStatus->cat_id;
$_POST['priority']=$activeStatus->priority;
$_POST['ticket_type']=$activeStatus->ticket_type;
include 'setChangeTicketStatus.php';
?>