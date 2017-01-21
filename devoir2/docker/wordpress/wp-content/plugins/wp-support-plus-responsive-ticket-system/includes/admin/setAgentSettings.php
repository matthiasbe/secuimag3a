<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();

$values=array(
		'signature'=>htmlspecialchars($_POST['signature'],ENT_QUOTES),
		'skype_id'=>$_POST['skype_id'],
		'skype_chat_availability'=>$_POST['chat_availability'],
		'skype_call_availability'=>$_POST['call_availability']
);
$wpdb->update($wpdb->prefix.'wpsp_agent_settings',$values,array('id'=>$_POST['id']));
?>