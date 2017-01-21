<?php
global $wpdb;
$cu = wp_get_current_user(); 
if ($cu->has_cap('manage_options')) {     
    $wpdb->delete( $wpdb->prefix.'wpsp_ticket_thread', array( 'id' => $_POST['thread_id'] ) ); 
}
?>