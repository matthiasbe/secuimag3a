<?php
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Functions required to update database when custom status color changed
 */ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$sql = "UPDATE " . $wpdb->prefix . "wpsp_custom_status SET color='" . $_POST['custom_status_color'] . "' WHERE id='" . $_POST['custom_status_id'] . "'"; 
$wpdb->query( $sql );
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/
?>