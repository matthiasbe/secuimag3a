<?php 
global $wpdb;

$wpdb->delete( $wpdb->prefix.'wpsp_canned_reply', array( 'id' => $_REQUEST['id'] ) );

wp_redirect(admin_url('admin.php?page=wp-support-plus-Canned-Reply'));
?>
