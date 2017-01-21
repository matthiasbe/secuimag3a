<?php 
$ticket_ids=explode(',', $_POST['ticket_ids']);

foreach ($ticket_ids as $ticket_id){
	$_POST['ticket_id']=$ticket_id;
	include 'deleteTicket.php';
}
?>
