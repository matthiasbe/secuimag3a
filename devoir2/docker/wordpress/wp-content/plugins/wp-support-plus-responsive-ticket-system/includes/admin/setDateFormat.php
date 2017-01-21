<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$frontend_cdt=$_POST['cdt_date_format_front'];
$backend_cdt=$_POST['cdt_date_format'];
$frontend_udt=$_POST['udt_date_format_front'];
$backend_udt=$_POST['udt_date_format'];

$dateFormat=array(
	'cdt_backend'=>$backend_cdt,
	'udt_backend'=>$backend_udt,
	'cdt_frontend'=>$frontend_cdt,
	'udt_frontend'=>$frontend_udt
);
update_option('wpsp_ticket_list_date_format',$dateFormat);

?>
