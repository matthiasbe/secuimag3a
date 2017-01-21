<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$sql = "UPDATE " . $wpdb->prefix . "wpsp_custom_priority SET color='" . $_POST['custom_priority_color'] . "' WHERE id='" . $_POST['custom_priority_id'] . "'"; 
$wpdb->query( $sql );
?>
