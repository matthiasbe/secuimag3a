<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

global $wpdb;
$wpdb->delete($wpdb->prefix.'wpsp_panel_custom_menu',array('id'=>$_POST['menu_id']));

?>