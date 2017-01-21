<?php
global $wpdb;
$advancedSettings=get_option( 'wpsp_advanced_settings' );
if($advancedSettings['pending_ticket_close']!='')
{
	$diffDay=$advancedSettings['pending_ticket_close'];
	$wpdb->query("UPDATE ".$wpdb->prefix.'wpsp_ticket'." SET status='closed' WHERE status='pending' AND ( TIMESTAMPDIFF(DAY,update_time,UTC_TIMESTAMP()) >= ".$diffDay.") ");
}
?>