<?php 
$ticket_ids=explode(',', $_POST['ticket_ids']);
$agent_ids=$_POST['agent_ids'];

foreach ($ticket_ids as $ticket_id){
	$_POST['ticket_id']=$ticket_id;
	$_POST['agent_id']=$agent_ids;
	include 'setTicketAssignment.php';
}
?>
