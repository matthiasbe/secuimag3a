<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$values=array(
		'category_id'=>1
	);
	$wpdb->update($wpdb->prefix.'wpsp_faq',$values,array('category_id'=>$_POST['faq_cat_id']));
	
	$wpdb->delete($wpdb->prefix.'wpsp_faq_catagories',array('id'=>$_POST['faq_cat_id']));
}
?>
