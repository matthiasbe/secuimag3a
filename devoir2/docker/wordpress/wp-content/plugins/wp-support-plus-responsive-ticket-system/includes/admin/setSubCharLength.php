<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$frontend=$_POST['front_end_length'];
$backend=$_POST['back_end_length'];

$subCharLength=array(
		'frontend'=>$frontend,
		'backend'=>$backend
);
update_option('wpsp_ticket_list_subject_char_length',$subCharLength);

?>
