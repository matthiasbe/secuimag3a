<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
$values=array(
		'menu_text'=>$_POST['custom_menu_text'],
		'redirect_url'=>$_POST['custom_menu_url'],
		'menu_icon'=>$_POST['custom_menu_icon']
);
$wpdb->insert($wpdb->prefix.'wpsp_panel_custom_menu',$values);
?>